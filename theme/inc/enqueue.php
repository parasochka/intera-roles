<?php
/**
 * Styles and scripts.
 *
 * All first-party CSS is concatenated and inlined into <head>: the design-system
 * sheets listed by the `_ds/intera/styles.css` @import manifest, then the
 * supplemental theme CSS, then style.css. DS files stay byte-identical on disk
 * (they are the source of truth) and the page ships zero render-blocking CSS
 * requests.
 *
 * Two things happen to the stack on its way into <head>, neither of which
 * touches a byte on disk:
 *
 *  - **`url()` rewriting.** A sheet on disk writes its asset paths relative to
 *    itself, which is what a standalone stylesheet needs. Inlined, those paths
 *    would resolve against the *page* URL instead and 404, so each one is
 *    rewritten to an absolute URL built from the sheet's own directory. This is
 *    what lets `tokens/fonts.css` point at the self-hosted `assets/fonts/`.
 *  - **Minification.** 61 KB of source CSS — most of it the comments that make
 *    `assets/css/intera.css` readable — is 61 KB in every HTML response, and
 *    inline CSS cannot be cached separately from the document. The stack is
 *    minified once and kept in a transient keyed by the theme version *and* the
 *    modification times of the files, so an edit lands immediately in dev and
 *    costs nothing per request in production. `SCRIPT_DEBUG` turns it off.
 *
 * The fonts are preloaded rather than discovered: they are same-origin, they are
 * needed by the first paint, and the browser would otherwise only learn about
 * them after parsing the inline <style>.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

/**
 * The font files worth a `<link rel="preload">`.
 *
 * Only the two faces the first screen is guaranteed to use: the Latin subset of
 * the variable Sans (every heading, every run of body text) and the Latin
 * subset of Mono 400 (the ref/eyebrow lines the designed pages open with).
 * Every other subset stays lazy behind its `unicode-range` — preloading a file
 * the page never uses is a wasted round trip, not a saved one.
 */
const INTERA_PRELOAD_FONTS = array(
	'assets/fonts/ibm-plex-sans-var-latin.woff2',
	'assets/fonts/ibm-plex-mono-400-latin.woff2',
);

/**
 * Reads the `@import` manifest and returns the DS sheets in declaration order.
 *
 * @return string[] Absolute paths, empty while the DS has not landed yet.
 */
function intera_ds_sheets() {
	$manifest = INTERA_DS_DIR . 'styles.css';

	if ( ! is_readable( $manifest ) ) {
		return array();
	}

	$css = (string) file_get_contents( $manifest ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	preg_match_all( '/@import\s+(?:url\()?["\']([^"\']+)["\']\)?\s*;/', $css, $matches );

	$sheets = array();
	foreach ( $matches[1] as $relative ) {
		$path = INTERA_DS_DIR . ltrim( $relative, './' );
		if ( is_readable( $path ) ) {
			$sheets[] = $path;
		}
	}

	return $sheets;
}

/**
 * The whole first-party stylesheet stack, in cascade order.
 *
 * @return string[] Absolute paths.
 */
function intera_css_stack() {
	return array_merge(
		intera_ds_sheets(),
		array(
			INTERA_DIR . 'assets/css/intera.css',
			INTERA_DIR . 'style.css',
		)
	);
}

/**
 * Rewrites a sheet's relative `url()` targets to absolute URLs.
 *
 * Inlining moves a stylesheet out of its directory, which silently repoints
 * every relative path at the page URL. Resolving them against the file's own
 * directory keeps the sheet correct in both places — as a file on disk and as
 * an inline block — so nothing about the source has to know it will be inlined.
 *
 * Absolute URLs, protocol-relative URLs and `data:` payloads are left alone.
 *
 * @param string $css  Stylesheet contents.
 * @param string $path Absolute path of the file the CSS came from.
 * @return string
 */
function intera_css_rewrite_urls( $css, $path ) {
	$dir_path = trailingslashit( dirname( $path ) );

	// Where this file's directory lives on the web. Both sides of the swap are
	// absolute paths, so the tail of the URL is just the tail of the filesystem path.
	if ( 0 !== strpos( $dir_path, INTERA_DIR ) ) {
		return $css;
	}

	$dir_uri = trailingslashit( get_template_directory_uri() ) . substr( $dir_path, strlen( INTERA_DIR ) );

	return (string) preg_replace_callback(
		'#url\(\s*([\'"]?)([^\'")]+)\1\s*\)#i',
		static function ( $match ) use ( $dir_uri ) {
			$target = trim( $match[2] );

			// Already resolvable on its own.
			if ( '' === $target || preg_match( '#^(?:[a-z][a-z0-9+.-]*:|//|/|\#)#i', $target ) ) {
				return $match[0];
			}

			// Walk the leading ../ segments off the target and up the URL.
			$base = $dir_uri;
			while ( 0 === strpos( $target, '../' ) ) {
				$target = substr( $target, 3 );
				$base   = trailingslashit( dirname( untrailingslashit( $base ) ) );
			}

			$target = preg_replace( '#^\./#', '', $target );

			return 'url("' . $base . $target . '")';
		},
		$css
	);
}

/**
 * Minifies CSS without touching anything inside a string or a `url()`.
 *
 * Deliberately conservative. Comments go, whitespace collapses, and the space
 * that only ever separated a token from a brace, semicolon or comma goes with
 * it. Nothing else is rewritten: no shortening of colours, no reordering, and
 * above all no touching whitespace around `+`, `-`, `*` or `/`, which are
 * operators inside `calc()` and cannot lose their spaces.
 *
 * A colon is the one piece of punctuation that is not purely structural, and
 * it is handled on its own below.
 *
 * Quoted strings and `url()` payloads are lifted out before the rewrite and put
 * back after, so a comment marker or a run of spaces inside one survives intact.
 *
 * @param string $css Stylesheet contents.
 * @return string
 */
function intera_css_minify( $css ) {
	$literals = array();

	/*
	 * One pass, so that comments and literals cannot be read inside each other.
	 * Order matters: a comment is matched before the apostrophe it contains can
	 * open a string ("the export's own" appears in these sheets a dozen times),
	 * and a string is matched before a `/*` inside it can open a comment.
	 * Comments are dropped here; strings and unquoted url() payloads are parked
	 * behind a placeholder so the rewrites below cannot reach into them.
	 */
	$css = (string) preg_replace_callback(
		'#/\*.*?\*/|"(?:\\\\.|[^"\\\\])*"|\'(?:\\\\.|[^\'\\\\])*\'|url\(\s*[^\'")]+\s*\)#is',
		static function ( $match ) use ( &$literals ) {
			if ( 0 === strpos( $match[0], '/*' ) ) {
				return '';
			}

			$literals[] = $match[0];

			return "\0" . ( count( $literals ) - 1 ) . "\0";
		},
		$css
	);

	// Any run of whitespace is at most one space.
	$css = (string) preg_replace( '#\s+#', ' ', $css );

	// The space next to structural punctuation never carried meaning.
	$css = (string) preg_replace( '#\s*([{};,>~])\s*#', '$1', $css );

	/*
	 * A colon is two different things. Between a property and its value the
	 * space after it is noise; but a colon also opens a pseudo-class, and there
	 * the space *before* it is a descendant combinator carrying the whole
	 * meaning of the selector. Stripping both sides — which this did until
	 * 0.7.16 — turns `.prose :where(ul)` into `.prose:where(ul)`, a compound
	 * selector that matches nothing, and the rule is gone from the page with
	 * nothing to show for it: no parse error, no warning, just a style that
	 * quietly never applies. So only the trailing space goes. A space written
	 * before a colon in a declaration would survive, which costs a byte and no
	 * correctness; nothing in this theme writes one.
	 */
	$css = (string) preg_replace( '#:\s+#', ':', $css );

	// A semicolon immediately before a closing brace is redundant.
	$css = str_replace( ';}', '}', $css );

	$css = trim( $css );

	// Put the literals back.
	return (string) preg_replace_callback(
		'#\0(\d+)\0#',
		static function ( $match ) use ( $literals ) {
			return $literals[ (int) $match[1] ];
		},
		$css
	);
}

/**
 * Builds the inline stylesheet: read, rewrite, concatenate, minify.
 *
 * @return string
 */
function intera_css_build() {
	$css = '';

	foreach ( intera_css_stack() as $path ) {
		if ( ! is_readable( $path ) ) {
			continue;
		}

		$css .= intera_css_rewrite_urls( (string) file_get_contents( $path ), $path ) . "\n"; // phpcs:ignore WordPress.WP.AlternativeFunctions
	}

	if ( '' === $css ) {
		return '';
	}

	if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
		return $css;
	}

	return intera_css_minify( $css );
}

/**
 * The inline stylesheet, cached until the theme version or a source file changes.
 *
 * The cache key carries the modification times of every sheet in the stack, so
 * saving a token file is visible on the next request without a version bump or
 * a manual flush — a stale key is simply never asked for again.
 *
 * @return string
 */
function intera_css_inline() {
	$stack = intera_css_stack();
	$stamp = INTERA_VERSION;

	foreach ( $stack as $path ) {
		$stamp .= '|' . $path . ':' . ( is_readable( $path ) ? (string) filemtime( $path ) : '0' );
	}

	$key    = 'intera_css_' . md5( $stamp );
	$cached = get_transient( $key );

	if ( is_string( $cached ) && '' !== $cached ) {
		return $cached;
	}

	$css = intera_css_build();

	if ( '' !== $css ) {
		set_transient( $key, $css, WEEK_IN_SECONDS );
	}

	return $css;
}

/**
 * Inline the whole stylesheet stack, in cascade order.
 */
function intera_enqueue_assets() {
	$css = intera_css_inline();

	// A registered-but-empty handle is the supported way to attach inline CSS.
	wp_register_style( 'intera', false, array(), INTERA_VERSION );
	wp_enqueue_style( 'intera' );

	if ( '' !== $css ) {
		wp_add_inline_style( 'intera', $css );
	}

	if ( is_readable( INTERA_DIR . 'assets/js/intera.js' ) ) {
		wp_enqueue_script(
			'intera',
			get_template_directory_uri() . '/assets/js/intera.js',
			array(),
			INTERA_VERSION,
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'intera_enqueue_assets' );

/**
 * Preloads the two font files the first screen always needs.
 *
 * The faces are declared in the inline <style>, which means the browser cannot
 * discover them until it has parsed the whole stack and matched a rule to a
 * node — late enough that the text has already painted in the fallback and will
 * reflow when the real face arrives. A preload moves the request to the top of
 * the document, where it overlaps the rest of the parse instead of following it.
 *
 * Fonts are always fetched in CORS mode, so `crossorigin` is required even
 * same-origin: without it the preloaded file is not the one the font loader
 * ends up using, and the page pays for it twice.
 *
 * For the same reason the URL carries no `?ver=` cache-buster: a preload only
 * satisfies a later request when the two URLs match exactly, and the `url()` in
 * `tokens/fonts.css` has no query string. The files are content-addressed by
 * name anyway — a new cut of a face arrives as a new filename.
 */
function intera_preload_fonts() {
	$base = trailingslashit( get_template_directory_uri() );

	foreach ( INTERA_PRELOAD_FONTS as $relative ) {
		if ( ! is_readable( INTERA_DIR . $relative ) ) {
			continue;
		}

		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( $base . $relative )
		);
	}
}
add_action( 'wp_head', 'intera_preload_fonts', 1 );
