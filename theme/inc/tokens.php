<?php
/**
 * Design tokens, read from the design system at runtime.
 *
 * `_ds/intera/tokens/*.css` is the single source of truth for every colour,
 * size, radius, shadow and duration in this theme. WordPress occasionally needs
 * those values as PHP (the editor colour palette and font-size presets), so this
 * file *parses* the CSS rather than restating it: there is never a second copy
 * of a token value in the repo.
 *
 * The parse follows the same pattern as inc/enqueue.php — read the
 * `_ds/intera/styles.css` @import manifest, in declaration order — and the
 * result is cached in a transient keyed by INTERA_VERSION.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

/**
 * Token files, in the manifest's declaration order.
 *
 * Only `tokens/*.css` entries are considered: `base.css` is a reset and
 * `fonts.css` carries @font-face, neither declares custom properties, but both
 * are harmless to scan and stay in order for free.
 *
 * @return string[] Absolute paths; empty while the DS has not landed yet.
 */
function intera_token_files() {
	$manifest = INTERA_DS_DIR . 'styles.css';
	$files    = array();

	if ( is_readable( $manifest ) ) {
		$css = (string) file_get_contents( $manifest ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		preg_match_all( '/@import\s+(?:url\()?["\']([^"\']+)["\']\)?\s*;/', $css, $matches );

		foreach ( $matches[1] as $relative ) {
			$relative = ltrim( $relative, './' );

			if ( 0 !== strpos( $relative, 'tokens/' ) ) {
				continue;
			}

			$path = INTERA_DS_DIR . $relative;
			if ( is_readable( $path ) ) {
				$files[] = $path;
			}
		}
	}

	if ( empty( $files ) ) {
		$glob = glob( INTERA_DS_DIR . 'tokens/*.css' );
		if ( is_array( $glob ) ) {
			sort( $glob );
			$files = $glob;
		}
	}

	return $files;
}

/**
 * Normalise a token name to its declared form (`--blue-600`).
 *
 * @param string $name Token name, with or without the leading dashes.
 * @return string
 */
function intera_token_name( $name ) {
	$name = trim( (string) $name );

	return '' === $name ? '' : '--' . ltrim( $name, '-' );
}

/**
 * Read every custom property declared by the token files.
 *
 * @return array<string,string> Raw declared values, keyed by `--token`.
 */
function intera_tokens_parse() {
	$tokens = array();

	foreach ( intera_token_files() as $path ) {
		$css = (string) file_get_contents( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions

		// Comments carry prose and `@kind` annotations — never values.
		$css = preg_replace( '#/\*.*?\*/#s', '', $css );

		if ( ! preg_match_all( '/(--[A-Za-z0-9_-]+)\s*:\s*([^;{}]+)\s*[;}]/', (string) $css, $matches, PREG_SET_ORDER ) ) {
			continue;
		}

		foreach ( $matches as $match ) {
			// A later declaration of the same token wins, as it does in CSS.
			$tokens[ $match[1] ] = trim( $match[2] );
		}
	}

	return $tokens;
}

/**
 * Follow `var(--alias)` references until a literal value is reached.
 *
 * Roughly half the palette is semantic (`--text-primary: var(--ink-900)`), and
 * PHP consumers need the literal. Unresolvable references are left verbatim so a
 * typo is visible rather than silently blank.
 *
 * @param array<string,string> $tokens Raw declared values.
 * @return array<string,string> Resolved values.
 */
function intera_tokens_resolve( $tokens ) {
	$resolved = array();

	foreach ( $tokens as $name => $value ) {
		$depth = 0;

		while ( $depth < 12 && false !== strpos( $value, 'var(' ) ) {
			$next = preg_replace_callback(
				'/var\(\s*(--[A-Za-z0-9_-]+)\s*(?:,\s*([^()]*?))?\s*\)/',
				static function ( $match ) use ( $tokens ) {
					if ( isset( $tokens[ $match[1] ] ) ) {
						return $tokens[ $match[1] ];
					}

					return isset( $match[2] ) ? trim( $match[2] ) : $match[0];
				},
				$value
			);

			if ( null === $next || $next === $value ) {
				break;
			}

			$value = $next;
			++$depth;
		}

		$resolved[ $name ] = trim( $value );
	}

	return $resolved;
}

/**
 * Every design token, aliases resolved to literal values.
 *
 * Shaped as `[ '--blue-600' => <the hex from colors.css>, … ]`. The values are
 * deliberately not repeated here: this docblock would become the second copy
 * of a token the moment one changed, which is the drift CLAUDE.md forbids.
 *
 * @return array<string,string>
 */
function intera_tokens_get() {
	static $tokens = null;

	if ( null !== $tokens ) {
		return $tokens;
	}

	$key = 'intera_tokens_' . INTERA_VERSION;

	// While debugging, token edits must show up without a version bump.
	if ( ! ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ) {
		$cached = get_transient( $key );

		if ( is_array( $cached ) ) {
			$tokens = $cached;

			return $tokens;
		}
	}

	$tokens = intera_tokens_resolve( intera_tokens_parse() );

	if ( ! ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ) {
		set_transient( $key, $tokens, 0 );
	}

	return $tokens;
}

/**
 * One token value.
 *
 * @param string $name    Token name, with or without the leading dashes.
 * @param string $default Returned when the token is not declared.
 * @return string
 */
function intera_token_get( $name, $default = '' ) {
	$tokens = intera_tokens_get();
	$name   = intera_token_name( $name );

	if ( '' !== $name && isset( $tokens[ $name ] ) && '' !== $tokens[ $name ] ) {
		return $tokens[ $name ];
	}

	return (string) $default;
}

/**
 * Drop the cached parse whenever the theme is (re)activated.
 */
function intera_tokens_flush() {
	delete_transient( 'intera_tokens_' . INTERA_VERSION );
}
add_action( 'switch_theme', 'intera_tokens_flush' );
add_action( 'after_switch_theme', 'intera_tokens_flush' );

/**
 * The editor colour palette, built from the tokens.
 *
 * Deliberately short. The design system says a colour "always answers 'what kind
 * of thing is this?' — never 'make this look nicer'", so an author gets the ink
 * ramp for text, the one interface accent, the four signal colours, the two
 * status colours, and the two page surfaces. Everything else in the 230 is
 * structural (borders, washes, hover fills) and is not an author's to apply.
 *
 * @return array<int,array<string,string>> WP `editor-color-palette` shape.
 */
function intera_editor_palette_get() {
	$palette = array(
		array(
			'name'  => __( 'Ink', 'intera' ),
			'slug'  => 'ink-900',
			'token' => '--ink-900',
		),
		array(
			'name'  => __( 'Secondary ink', 'intera' ),
			'slug'  => 'ink-600',
			'token' => '--ink-600',
		),
		array(
			'name'  => __( 'Muted ink', 'intera' ),
			'slug'  => 'ink-400',
			'token' => '--ink-400',
		),
		array(
			'name'  => __( 'Intera blue', 'intera' ),
			'slug'  => 'blue-600',
			'token' => '--blue-600',
		),
		array(
			'name'  => __( 'Reconciliation teal', 'intera' ),
			'slug'  => 'teal-600',
			'token' => '--teal-600',
		),
		array(
			'name'  => __( 'Incident amber', 'intera' ),
			'slug'  => 'amber-600',
			'token' => '--amber-600',
		),
		array(
			'name'  => __( 'Pattern violet', 'intera' ),
			'slug'  => 'violet-600',
			'token' => '--violet-600',
		),
		array(
			'name'  => __( 'Positive green', 'intera' ),
			'slug'  => 'green-600',
			'token' => '--green-600',
		),
		array(
			'name'  => __( 'Critical red', 'intera' ),
			'slug'  => 'red-600',
			'token' => '--red-600',
		),
		array(
			'name'  => __( 'White', 'intera' ),
			'slug'  => 'white',
			'token' => '--white',
		),
		array(
			'name'  => __( 'Sunken', 'intera' ),
			'slug'  => 'ink-25',
			'token' => '--ink-25',
		),
	);

	$out = array();

	foreach ( $palette as $entry ) {
		$color = intera_token_get( $entry['token'] );

		if ( '' === $color ) {
			continue;
		}

		$out[] = array(
			'name'  => $entry['name'],
			'slug'  => $entry['slug'],
			'color' => $color,
		);
	}

	return $out;
}

/**
 * The editor font-size presets, built from the tokens.
 *
 * Heading sizes are intentionally absent: a heading gets its size from its
 * level (h2/h3/h4 are already typed by the DS reset), not from a size preset.
 * What is left is the run of sizes an author may legitimately apply to a piece
 * of text inside prose.
 *
 * @return array<int,array<string,mixed>> WP `editor-font-sizes` shape.
 */
function intera_editor_font_sizes_get() {
	$sizes = array(
		array(
			'name'      => __( 'Caption', 'intera' ),
			'slug'      => 'caption',
			'shortName' => __( 'XS', 'intera' ),
			'token'     => '--text-xs',
		),
		array(
			'name'      => __( 'Data', 'intera' ),
			'slug'      => 'data',
			'shortName' => __( 'S', 'intera' ),
			'token'     => '--text-sm',
		),
		array(
			'name'      => __( 'UI', 'intera' ),
			'slug'      => 'ui',
			'shortName' => __( 'M', 'intera' ),
			'token'     => '--text-md',
		),
		array(
			'name'      => __( 'Body', 'intera' ),
			'slug'      => 'body',
			'shortName' => __( 'L', 'intera' ),
			'token'     => '--text-base',
		),
		array(
			'name'      => __( 'Lead', 'intera' ),
			'slug'      => 'lead',
			'shortName' => __( 'XL', 'intera' ),
			'token'     => '--text-lg',
		),
	);

	$out = array();

	foreach ( $sizes as $entry ) {
		$value = intera_token_get( $entry['token'] );
		$size  = (int) $value;

		if ( $size <= 0 ) {
			continue;
		}

		$out[] = array(
			'name'      => $entry['name'],
			'slug'      => $entry['slug'],
			'shortName' => $entry['shortName'],
			'size'      => $size,
		);
	}

	return $out;
}
