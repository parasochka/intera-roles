# CLAUDE.md — Intera Roles theme (classic PHP)

Guidance for AI agents (and humans) working in this repo. Read this first.

## What this is

A custom WordPress **classic PHP theme** for
[intera-roles.com](https://intera-roles.com), built from a Claude Design
project. The theme lives in **`theme/`** — WP Pusher installs from that
subdirectory, so docs, design sources and CI at the repo root are never deployed
to WordPress.

The **design system is the source of truth for styling.** Tokens live in
`theme/_ds/intera/tokens/*.css` and are the single place colors, type, spacing,
radii and elevation are defined. They are inlined into `<head>` verbatim — the
only thing `inc/enqueue.php` does to them on the way is resolve relative `url()`
paths and strip comments and whitespace, so what ships is the same cascade.
There is **no `theme.json` token bridge**: two sources of tokens drift apart.
Edit a token and the whole site re-themes.

```
Claude Design  ─►  theme/ (_ds/intera + PHP)  ─►  GitHub  ─►  WP Pusher  ─►  WordPress
 export screens     this repo                     (main)     (subdir: theme)  live site
```

## File map (paths relative to `theme/`)

| Path | Purpose |
| --- | --- |
| `style.css` | WP theme header + a pointer note. No real CSS here. |
| `functions.php` | Thin loader: `INTERA_*` constants + `require inc/*`. |
| `inc/setup.php` | Theme supports, editor styles, image sizes, content width (1160), the five nav locations. |
| `inc/enqueue.php` | Parses the `_ds/intera/styles.css` `@import` manifest and inlines every sheet + `assets/css/intera.css` + `style.css` into `<head>`, rewriting each sheet's relative `url()` to an absolute one and minifying the result into a transient; preloads the two above-the-fold fonts; enqueues `assets/js/intera.js` deferred. DS files stay byte-identical on disk. |
| `inc/tokens.php` | Parses `_ds/intera/tokens/*.css` into PHP arrays. This is how the block editor gets the brand palette **without a second copy of any value**. |
| `inc/post-types.php` | CPTs `docs`, `role`, `plan`; taxonomy `docs_category` + its term meta (icon, tone). |
| `inc/betterdocs.php` | Everything that touches the BetterDocs plugin: the `template_include` takeback for the three docs screens, and the plugin's own article footer (reaction vote, share, tags, feedback modal), print button, AI summary and comments rendered back into `single-docs.php`. See **Living with BetterDocs** below. |
| `inc/meta.php` | Post meta registration and the meta boxes that make it editable. |
| `inc/seo.php` | The `<title>` and meta description every screen answers with: written defaults per designed page and archive, `_intera_seo_title` / `_intera_seo_description` overrides an editor can type, and the brand appended once at the end (never twice). A last resort under all of it, so a screen nobody has written words for still answers with what it holds. Feeds Rank Math through its own filters when the plugin is active. |
| `inc/customizer.php` | Theme options + `intera_option()`. No colour or font setting lives here — those come from the tokens. |
| `inc/template-tags.php` | `intera_icon()` (inlined Lucide), reading time, breadcrumbs, heading ids and TOC data. |
| `inc/forms.php` | The contact-request handler: nonce, honeypot, validation, storage, mail. The fallback behind Contact Form 7, not the default. |
| `inc/cf7.php` | Everything that touches Contact Form 7: renders the configured form on the request page and dresses the plugin's markup in the design system. See **Living with Contact Form 7** below. |
| `header.php` / `footer.php` | Shared chrome. The mobile menu is a CSS-only checkbox toggle; JS only mirrors it into ARIA. |
| `index.php` · `page.php` · `single.php` · `404.php` | Templates. |
| `template-parts/components/*.php` | One design-system component each, called via `get_template_part()` with an args array. |
| `template-parts/partials/*.php` | Repeated page markup — one source per block. |
| `page-templates/` | Named page templates (`Template Name:` header). |
| `_ds/intera/styles.css` | The **only** list of design-system sheets. Add a token file? Add it here. |
| `_ds/intera/tokens/*.css` | **Design-system source of truth**: every colour, type, spacing, radius and elevation value, as exported from the design project. Do not fork these values elsewhere. The one file that has intentionally moved on from the export is `fonts.css`, whose `@import` and pinned CDN URLs were replaced by the self-hosted faces in `assets/fonts/` — the *families and weights* are still the export's. |
| `assets/css/intera.css` | Supplemental CSS only: page canvas, prose, real `:hover`/`:focus`, responsive stacking, reduced-motion. References DS tokens. |
| `assets/js/intera.js` | Optional progressive JS. The site must work without it. |
| `assets/img/*` | Theme chrome images (logo lock-ups). Editorial images live in the media library. |
| `assets/fonts/*` | Self-hosted IBM Plex woff2, declared by `_ds/intera/tokens/fonts.css`. Sans is one variable file per subset (wght 400–700), Mono is static 400/500; `unicode-range` keeps a subset unfetched until the page needs it. No third-party font origin. |
| `screenshot.png` | 1200×900, what WordPress shows on Appearance → Themes. Rendered from the design system, not drawn — re-render it when the hero or the tokens change. |

Repo root (not deployed): `.github/workflows/` (`validate.yml` lints the theme,
`verify-deploy.yml` checks the live site), `bin/verify-deploy.sh`, `_design/`
(the Claude Design export), `README.md`, `SETUP.md`, this file.

## Living with BetterDocs

The live site keeps its documentation and its FAQ in **BetterDocs Pro**. The
plugin owns the `docs` post type, the `doc_category` taxonomy, every published
article and the FAQ block; `inc/post-types.php` steps aside when it finds them
already registered, and nothing in this repo moves that content.

What the theme owns is the *layout* of three screens — `archive-docs.php`,
`taxonomy-doc_category.php`, `single-docs.php` — which it takes back from the
plugin with a late `template_include` filter.

**Taking a template back takes the plugin's features with it.** That is the
trap this file exists to document, because it caught us once: a BetterDocs
article is not only a body of text, it is also a reaction vote, a feedback
form, a share row, the doc's tags, a print button, the AI summary and a comment
thread — all of them drawn by `views/templates/footer.php` and its parts, none
of which runs once the template points somewhere else. The reaction vote is the
one that costs real data: it is the only thing on the page that writes to
`/betterdocs/v1/feedback`, so BetterDocs → Analytics → Reactions was recording
nothing while the docs looked finished.

Three rules follow, and they apply to any plugin whose template we replace:

1. **Never reimplement a plugin control — render the plugin's.**
   `intera_betterdocs_part()` runs the plugin's own view files. That keeps every
   part gated on the plugin's own settings (a feature switched off in BetterDocs
   stays off here), keeps votes going to the plugin's endpoint under its own
   nonce, and keeps `betterdocs_docs_before_social` firing, which is the seam
   BetterDocs **Pro** hangs its own additions on.
2. **Dress the plugin's markup, do not strip its stylesheet.** Where plugin
   markup is on the page, its sheets stay and `assets/css/intera.css`
   out-specifies them with tokens — the arrangement the FAQ block has always
   used. Only the screens with *no* plugin markup (the docs archive and the
   category page) dequeue anything.

   *Out-specify* is literal, and equal specificity is not enough: the theme's
   CSS is inlined in `<head>`, the plugin's sheets load after it, so a tie goes
   to the plugin. 0.7.3 shipped four empty outlines exactly this way — the
   plugin's `display: inline-block` on the share anchor tied with the theme's
   `inline-flex` and won, which left the glyph an inline box with no size.
   Which also fixes how to check such a change: rendering it with the new CSS
   appended to the page proves nothing, because that is not where the rule
   lands. Verify against the deployed page — its own HTML and its own sheets,
   with our rule where it really sits.
3. **A control that records nothing is a fallback, never the default.** The
   theme's own "Was this page useful?" strip is a `GET` form to the contact
   page; it renders only when BetterDocs offers no vote of its own.

One more thing worth knowing, because it looks like a theme bug and is not: a
bare `post_type` query var is parsed by BetterDocs as a request for its docs
archive (`Core\Request::$query_vars`). So a docs search form must **not**
submit `?s=…&post_type=docs` — the term is dropped and every query answers with
the full archive. The forms send `intera_docs=1` and
`intera_docs_scope_search()` does the scoping in `pre_get_posts`.

## Living with Contact Form 7

The contact-request screen was built against `inc/forms.php`, the theme's own
handler. What the live site sends through is **Contact Form 7**: the form and
its fields, the two mails, the Gmail SMTP transport under them, the Flamingo
record of every message and the reCAPTCHA v2 checkbox in front of the button are
all configured in wp-admin. None of that is the theme's, and the same three
rules the BetterDocs section states apply for the same reason.

`page-contact-request.php` renders the plugin's form inside the export's Card;
`inc/cf7.php` dresses it. Dressing means *adding* to the plugin's markup and
never replacing it: `wpcf7_form_class_attr` puts `itr-cf7` on the `<form>`,
`wpcf7_form_elements` adds `.itr-input`, `.itr-input--area` and `.itr-btn` to
the controls the plugin drew, and wraps two things the plugin leaves
unreachable — the label's bare text node and the `<select>` that needs the
design's chevron over it. Everything else is CSS keyed on `form.itr-cf7`. Every
hidden field, nonce, endpoint, validation message and script stays the
plugin's, which is what keeps the mail, the spam check and the captcha working.

Three things worth knowing before touching it:

- **The theme's own form is the fallback, never the default.** With the plugin
  off, the Customizer's *Contact Form 7 form ID* empty, or the form deleted,
  `intera_cf7_form_html()` returns `''` and the screen falls back to
  `inc/forms.php`, which still stores and mails. A request page that has quietly
  stopped sending is the one outcome no branch may have.
- **The captcha is a control, so it is visible.** `[recaptcha]` is the v2
  checkbox from the *ReCaptcha v2 for Contact Form 7* plugin, and the CSS gives
  it a full-width row of its own directly above the submit button (`order`, so
  it lands there whatever position the tag has in the form template). Google's
  widget is 304px wide wherever it is put and the card has 214px of content at
  320px, so from 480px down it is scaled and its box is given the height the
  scale leaves it.
- **The plugin's script enqueues at render time.** The reCAPTCHA script is
  printed in `wp_footer`, so rendering the form from a template rather than from
  post content still reaches it. Confirm that stays true after a plugin update:
  no `api.js` in the page means a widget that never draws and a form nobody can
  submit.

The fields are the form template's, not the theme's. The seven the form carries
fill the export's grid exactly — three rows of two, then the long answer — and
the consent checkbox the export draws below them was dropped on purpose: the
line under the form about what is done with a request is what stands in its
place. A field added in wp-admin needs nothing here; the CSS already has a row
for a checkbox, a radio and a select.

## How the templates are built

Each template is cut from a design-handoff **screen**. The rule: **keep the
screen's HTML and inline styles verbatim; swap only the dynamic slots** for
WordPress calls (`the_title`, `the_content`, `the_post_thumbnail`, `WP_Query`,
`get_category_link`, …). This is why templates carry inline `style="…"` with
`var(--token)` — that is the handoff, preserved 1:1.

- Preview-only attributes in the export (e.g. `style-hover="…"`) are **not real
  CSS** — reimplement every hover/focus/active state in `assets/css/intera.css`,
  keyed by a class.
- Repeated markup lives once in `template-parts/` so every grid, rail and
  "related" block stays identical.

## How to make design changes (order of preference)

1. **A token value** → `theme/_ds/intera/tokens/*.css`. One edit re-themes
   everywhere. This is the single source of truth.
2. **A shared style** (prose, hover, responsive) → `theme/assets/css/intera.css`,
   always via `var(--…)` — never raw hex/px that duplicates a token.
3. **Layout/structure** → the relevant `theme/*.php`, matching the handoff
   screen.

**A screen that is correct in Chrome is not yet checked.** The handoff is drawn
at one width in one engine, and the two things it leaves out both surface on a
phone. A chip with `white-space: nowrap` — every `Badge` and `SignalBadge` — is
a flex item with the initial `0 1 auto`, and the automatic minimum size is all
that stops a row from squeezing it below its own label: Blink honours that
floor, WebKit gives it up, and on iOS the product header's badges spilled their
labels past their own borders while every desktop browser drew them correctly.
The mirror image is a row that cannot shrink at all — an unbreakable word, a
name beside a chip, a control whose label the export keeps on one line — which
reaches past the card in *every* engine once the viewport is 375px instead of
560px. So: state `flex: none` on a chip rather than trusting the floor, give a
long name `min-width: 0` (on the item *and* its group — the floor has to come
off both) so `overflow-wrap` can act, let a label take a second line where the
column is too narrow for it, and check a change at 320, 375 and 390px, not only
at desktop.

Letting something wrap costs whatever the one line was paying for, and the
phone breakpoint has to pay it back. A button's fixed height was centring its
label, so a wrapping button turns that height into a `min-height` and centres
with padding worked out from the same token — control height, less one line of
text, less the border `box-sizing` counts inside it, halved — which leaves a
one-line button exactly the height it has today. And wrap where a reader would
see the break, not wherever the arithmetic first goes negative: a label that
merely leans into its own padding still reads, so the tile labels take their
second line below 375px rather than at the phone breakpoint the rest uses.

## Non-negotiable rules

- **Tokens are the source of truth.** No second copy of a color or spacing value
  in PHP or `intera.css` — reference `var(--…)`.
- **Escape everything on output.** `esc_html`, `esc_attr`, `esc_url`,
  `wp_kses_post`. Never echo raw user or meta content.
- **Guard every file** with `defined( 'ABSPATH' ) || exit;`.
- **Prefix every global** with `intera_` / `INTERA_`.
- **No build step.** The theme is shipped as source: WP Pusher copies `theme/`
  to the server as-is. Anything requiring compilation must be committed compiled.
- **Keep CI green.** `main` is what WP Pusher deploys; a broken push is a broken
  live site.
- **Content is not code.** Posts, pages, menus and media live in WordPress, not
  in this repo.
- **The template owns the layout, WordPress owns the words.** No marketing
  string, URL, image or list may be hardcoded in a template if an editor would
  ever want to change it: navigation comes from a menu location, repeated chrome
  from `intera_option()`, editorial text from post content, and repeated records
  (`role`, `plan`, `docs`) from their custom post type. Layout, spacing and
  colour stay in the template.

## Workflow

- Develop on a feature branch, keep `php -l` clean, then merge to `main`.
- Bump `Version:` in `theme/style.css` **and** `INTERA_VERSION` in
  `functions.php` together — the version busts asset caches.
- After merging: WP Pusher → Themes → **Update**.
- **Then verify the deploy: `bin/verify-deploy.sh`.** Not optional — see below.

## The deploy is the part that breaks

`main` being right does not mean the server got it. WP Pusher deploys by
replacing `wp-content/themes/theme/` wholesale, and on 2026-08-24 that
replacement stopped half way: sixteen of the theme's sixty-four PHP files never
arrived. Nothing in the commit removed a file — it changed three — and the CSS
it was shipping never reached the server at all.

What turned a half-written directory into a dead site was one line of ours.
`inc/setup.php` did an unguarded `require_once` of
`template-parts/partials/search-form.php`, one of the files that had not
arrived. `functions.php` loads `inc/*` on **every** request, so the fatal fired
on the front end, in wp-admin and on the REST API alike: "There has been a
critical error on this website", 500 everywhere, and therefore no dashboard to
re-run the deploy from. The way back in was the host's file manager.

Two things that sound like they would have saved it, and do not. WordPress only
checks that a theme is whole in `validate_current_theme()`, which no ordinary
front-end request ever calls — and a fatal while `functions.php` is loading
happens before WordPress could act on it anyway. So the default theme installed
next to ours (Twenty Twenty-Four) was never going to catch this, and neither
would CI: `main` was correct the whole time.

Three guards follow from that, and the first is the one that matters.

1. **Never `require` a theme file unguarded.** `functions.php` has always
   wrapped its requires in `file_exists()`; `inc/setup.php` now does the same,
   and `validate.yml` fails the build on any `require`/`include` in `theme/`
   that is not guarded by `file_exists()` or `is_readable()` within the three
   preceding lines. A theme file that goes missing should cost the feature that
   lives in it, not the site.
2. **`bin/verify-deploy.sh`** asks the web server, file by file, whether every
   tracked theme file is actually there and whether the front page answers. A
   missing PHP file answers 404 from nginx; one that exists answers 200 with an
   empty body, because every file starts with `defined( 'ABSPATH' ) || exit;`.
   No credentials, no plugin — it works while the site is down. Run it after
   every Update: a clean run is what "deployed" means.
3. **`.github/workflows/verify-deploy.yml`** runs that script after every push
   touching `theme/`, and every two hours in between, so a deploy that breaks
   outside a push surfaces as a GitHub notification within hours instead of
   whenever somebody opens the site.

One risk the guards narrow but do not remove: `functions.php` skips an
`inc/*.php` that is not there, and the templates then call functions nothing
defined — a fatal of a different shape. There is no way to guard a call site
the way a require can be guarded, so what stands between that and the site is
the file-level check above. Run it after every deploy.
