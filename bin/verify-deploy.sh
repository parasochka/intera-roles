#!/usr/bin/env bash
#
# Verify that what is on the live server is a *whole* theme.
#
# WP Pusher deploys by replacing the theme directory on the server. On
# 2026-08-24 that replacement stopped half way: sixteen of the theme's
# sixty-four PHP files never arrived. One of them was a partial that
# `inc/setup.php` required without a guard, and since `functions.php` loads
# `inc/*` on every request, that was a PHP fatal on the front end, in wp-admin
# and on the REST API at once — so the dashboard the deploy would be re-run
# from was down too. Nothing in the commit removed a file, and `main` was
# correct the whole time; the only place the damage was visible was the server.
#
# The require is guarded now, and CI refuses an unguarded one, so a partial
# deploy should cost a feature rather than the site. This script is the other
# half: ask the web server, file by file, whether the deploy actually landed.
# A missing PHP file answers 404 from nginx while one that exists answers 200
# with an empty body (it runs, and every file is guarded by
# `defined( 'ABSPATH' ) || exit;`), which is what makes this checkable over
# plain HTTP with no credentials.
#
# Usage:
#   bin/verify-deploy.sh                 # check the live site
#   bin/verify-deploy.sh --wait 600      # wait up to 600s for the repo version
#                                        # to appear, then check
#
# Environment:
#   INTERA_BASE_URL     default https://intera-roles.com
#   INTERA_THEME_PATH   default /wp-content/themes/theme
#
# Exit codes:
#   0  site answers and every tracked theme file is present
#   1  something is missing or the site is not answering — details on stdout
#
# A version mismatch is reported but never fails the run: it only means the
# deploy has not been triggered yet, which is a normal state between a merge
# and someone pressing Update in WP Pusher.

set -uo pipefail

BASE_URL="${INTERA_BASE_URL:-https://intera-roles.com}"
THEME_PATH="${INTERA_THEME_PATH:-/wp-content/themes/theme}"
REPO_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
WAIT=0

while [ $# -gt 0 ]; do
	case "$1" in
		--wait) WAIT="${2:-0}"; shift 2 ;;
		--wait=*) WAIT="${1#*=}"; shift ;;
		-h|--help) sed -n '2,40p' "${BASH_SOURCE[0]}"; exit 0 ;;
		*) echo "unknown argument: $1" >&2; exit 2 ;;
	esac
done

THEME_URL="${BASE_URL%/}${THEME_PATH}"

# HTTP status of a URL, with a cache-buster so a CDN cannot answer for the
# origin.
#
# The host rate-limits: ninety requests in thirty seconds earns a 429, and a
# 429 read as "file missing" would report two dozen phantom losses. So an
# answer that is not about the file — no answer at all, a rate limit, a server
# error — is retried with a growing wait, and only what still answers after
# that is believed. The sweep also paces itself (see PROBE_PAUSE) so it stays
# under the limit in the first place.
status() {
	local url="$1" code attempt
	for attempt in 1 2 3; do
		code="$(curl -sS -o /dev/null -w '%{http_code}' --max-time 20 "${url}?deploycheck=$$$RANDOM" 2>/dev/null)"

		case "$code" in
			000|429|5??) ;;   # not an answer about the file — back off and ask again
			*) printf '%s' "$code"; return ;;
		esac

		sleep $(( attempt * 3 ))
	done

	printf '%s' "$code"
}

# The Version: line of a style.css, local file or URL.
version_of() {
	sed -n 's/^[[:space:]]*Version:[[:space:]]*//p' | head -n 1 | tr -d '\r'
}

repo_version="$(version_of < "$REPO_ROOT/theme/style.css")"

deployed_version() {
	curl -sS --max-time 20 "$THEME_URL/style.css?deploycheck=$$$RANDOM" 2>/dev/null | version_of
}

echo "Site:  $BASE_URL"
echo "Theme: $THEME_URL"
echo "Repo version: ${repo_version:-unknown}"

# ---------------------------------------------------------------------------
# 1. Optionally wait for the version the repo carries to appear on the server.
# ---------------------------------------------------------------------------
live_version="$(deployed_version)"

if [ "$WAIT" -gt 0 ] && [ -n "$repo_version" ] && [ "$live_version" != "$repo_version" ]; then
	echo "Waiting up to ${WAIT}s for $repo_version to appear (currently ${live_version:-unreadable})..."
	deadline=$(( $(date +%s) + WAIT ))
	while [ "$(date +%s)" -lt "$deadline" ]; do
		sleep 15
		live_version="$(deployed_version)"
		[ "$live_version" = "$repo_version" ] && break
	done
fi

echo "Live version: ${live_version:-unreadable}"

if [ -n "$repo_version" ] && [ "$live_version" != "$repo_version" ]; then
	echo "NOTE: the live theme is not the one in this checkout — the deploy has"
	echo "      not run yet (WP Pusher → Themes → Update). Checking what is"
	echo "      there for completeness anyway."
fi

# ---------------------------------------------------------------------------
# 2. Is the site answering at all?
# ---------------------------------------------------------------------------
failures=0

home="$(status "$BASE_URL/")"
echo
echo "GET / -> $home"

if [ "$home" != "200" ]; then
	echo "FAIL: the front page does not answer 200."
	failures=$(( failures + 1 ))
fi

# ---------------------------------------------------------------------------
# 3. Is every file of the theme actually on the server?
# ---------------------------------------------------------------------------
# Dotfiles are skipped: hosts routinely refuse to serve them, and none of them
# is a file WordPress loads.
mapfile -t tracked < <(cd "$REPO_ROOT/theme" && git ls-files | grep -v '/\.' | grep -v '^\.')

missing=()
unverified=()
blocked=0
checked=0
PROBE_PAUSE="${INTERA_PROBE_PAUSE:-0.2}"

for rel in "${tracked[@]}"; do
	code="$(status "$THEME_URL/$rel")"
	checked=$(( checked + 1 ))

	case "$code" in
		200) ;;
		403) blocked=$(( blocked + 1 )) ;;
		000|429|5??) unverified+=( "$rel|$code" ) ;;
		*) missing+=( "$rel|$code" ) ;;
	esac

	sleep "$PROBE_PAUSE"
done

echo "Checked $checked files; ${#missing[@]} missing, ${#unverified[@]} unverified, $blocked not served (403)."

if [ "${#unverified[@]}" -gt 0 ]; then
	echo "NOTE: the server would not answer about these even after backing off,"
	echo "      so they are neither confirmed present nor missing:"
	printf '  %s\n' "${unverified[@]//|/ -> }"
fi

# A deploy in flight looks exactly like a broken one: WP Pusher writes the
# theme file by file, so a sweep that overtakes it sees files that are about to
# exist. This is not hypothetical — the first run of this check in CI caught
# `404.php` mid-write, thirty seconds after the new version appeared, and the
# file was there a minute later. So a miss is only a miss if it survives a
# second and a third look, spaced far enough apart for a deploy to finish.
# A check that cries wolf on every push is a check nobody reads.
settle_rounds="${INTERA_SETTLE_ROUNDS:-3}"
settle_delay="${INTERA_SETTLE_DELAY:-30}"
round=0

while [ "${#missing[@]}" -gt 0 ] && [ "$round" -lt "$settle_rounds" ]; do
	round=$(( round + 1 ))
	echo "Missing after pass $round; a deploy may still be writing. Waiting ${settle_delay}s and re-checking those ${#missing[@]}."
	sleep "$settle_delay"

	still=()
	for entry in "${missing[@]}"; do
		rel="${entry%%|*}"
		code="$(status "$THEME_URL/$rel")"
		[ "$code" = "200" ] || still+=( "$rel|$code" )
	done

	missing=( "${still[@]}" )
done

if [ "$round" -gt 0 ] && [ "${#missing[@]}" -eq 0 ]; then
	echo "Everything that was missing appeared within $(( round * settle_delay ))s — the deploy was still running, not broken."
fi

# Every PHP file answering 403 means the host stopped serving them directly, not
# that the theme is gone — say so rather than reporting sixty-four false alarms.
if [ "$blocked" -gt 20 ]; then
	echo "NOTE: the host is refusing direct file requests, so file-level"
	echo "      verification is no longer possible this way."
fi

if [ "${#missing[@]}" -gt 0 ]; then
	echo
	echo "FAIL: the deploy on the server is incomplete. Still missing after"
	echo "      $(( settle_rounds * settle_delay ))s:"
	printf '  %s\n' "${missing[@]//|/ -> }"
	echo
	echo "Fix: re-run WP Pusher → Themes → Intera Roles → Update. If wp-admin is"
	echo "     down too, upload theme/ over wp-content/themes/theme/ by FTP or"
	echo "     the host's file manager, then re-run this check."
	failures=$(( failures + 1 ))
fi

if [ "$failures" -gt 0 ]; then
	exit 1
fi

echo
echo "OK: the site answers and every tracked theme file is on the server."
