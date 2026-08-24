<?php
/**
 * Block patterns — the designed marketing sections, rebuilt out of core blocks.
 *
 * The thirteen ported templates cover the pages the export drew. This file
 * covers the page an editor invents: it hands them the same sections, as core
 * blocks they can type into, so a page built in Gutenberg reads as part of the
 * same site.
 *
 * Three constraints shape every string below.
 *
 * 1. **Core blocks only.** There is no build step in this repo, so a pattern may
 *    only use blocks WordPress already ships — group, columns, heading,
 *    paragraph, list, details, image, buttons. Anything the export drew with an
 *    inlined Lucide icon (`intera_icon()`) is therefore left out rather than
 *    faked; the sections here are the ones that survive that cut intact.
 *
 * 2. **The inline style has to be one WordPress can regenerate.** A pattern is
 *    parsed into blocks the moment it is inserted, and a block whose markup does
 *    not match what its own `save()` would emit is flagged as "unexpected or
 *    invalid content". So the styling is written *twice*, exactly as core writes
 *    it: once as the block's `style` attribute in the comment, once as the
 *    `style="…"` it serialises to. The rendered declarations — and the
 *    `var(--token)` names inside them — are the templates' own.
 *
 *    The same rule blocks two shortcuts. `wp_kses_post()` runs over post content
 *    for anyone without `unfiltered_html`, and it drops declarations it cannot
 *    parse: a custom property (`--itr-edge`, `--itr-shot-h`) is not on its
 *    allowlist at all, and `repeat(auto-fit, …)` is not one of the five CSS
 *    functions it lets through. So no pattern sets a custom property inline, and
 *    no pattern writes a grid track: per-instance surfaces come from the class
 *    defaults in `assets/css/intera.css`, and every grid is a `columns` block or
 *    a block-layout attribute, which WordPress renders as its own CSS.
 *
 * 3. **PORT.md §1.** `.itr-card`, `.itr-lift`, `.itr-hl`, `.itr-row`,
 *    `.itr-tile`, `.itr-panel`, `.itr-hl-panel` and `.itr-frame` own their
 *    resting background, border, shadow and transition. Nothing here writes
 *    those four properties inline on an element carrying one of those classes —
 *    only padding, radius, type, colour and the 3px `border-top` accent, which
 *    the hover rules deliberately leave alone. That is also why the surfaces
 *    arrive as `className` rather than as block colour attributes: the class is
 *    the only place allowed to paint them.
 *
 * The copy is placeholder copy — an editor replaces it — so it is written in the
 * voice the rest of the site uses (ds-rules §2): second person, sentence case,
 * short declaratives, mono type on every number and identifier, no hype and no
 * invented figures. The signal chain is the one exception: its four labels and
 * captions are fixed product vocabulary, and its order and colours are not the
 * editor's to change.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

/**
 * Eyebrow paragraph — 12px, 600, +0.09em, uppercase.
 *
 * @param string $text  Escaped text.
 * @param string $color Token reference for the ink.
 * @return string Block markup.
 */
function intera_pattern_eyebrow( $text, $color = 'var(--blue-600)' ) {
	return '<!-- wp:paragraph {"style":{"typography":{"fontSize":"var(--text-xs)","fontWeight":"600","letterSpacing":"0.09em","textTransform":"uppercase"},"color":{"text":"' . $color . '"},"spacing":{"margin":{"bottom":"14px"}}}} -->' . "\n"
		. '<p class="has-text-color" style="color:' . $color . ';margin-bottom:14px;font-size:var(--text-xs);font-weight:600;letter-spacing:0.09em;text-transform:uppercase">' . $text . '</p>' . "\n"
		. '<!-- /wp:paragraph -->' . "\n";
}

/**
 * Section heading — the H2 every marketing section opens with.
 *
 * @param string $text  Escaped text.
 * @param string $color Token reference for the ink.
 * @return string Block markup.
 */
function intera_pattern_heading( $text, $color = 'var(--ink-900)' ) {
	return '<!-- wp:heading {"style":{"typography":{"fontSize":"var(--text-3xl)","fontWeight":"600","lineHeight":"1.22","letterSpacing":"-0.01em"},"color":{"text":"' . $color . '"}}} -->' . "\n"
		. '<h2 class="wp-block-heading has-text-color" style="color:' . $color . ';font-size:var(--text-3xl);font-weight:600;letter-spacing:-0.01em;line-height:1.22">' . $text . '</h2>' . "\n"
		. '<!-- /wp:heading -->' . "\n";
}

/**
 * Lede paragraph — 18/1.6, the sub-head that carries the proof.
 *
 * @param string $text  Escaped text.
 * @param string $color Token reference (or the inverse rgba the dark bands use).
 * @return string Block markup.
 */
function intera_pattern_lede( $text, $color = 'var(--ink-600)' ) {
	return '<!-- wp:paragraph {"style":{"typography":{"fontSize":"var(--text-lg)","lineHeight":"1.6"},"color":{"text":"' . $color . '"},"spacing":{"margin":{"top":"16px"}}}} -->' . "\n"
		. '<p class="has-text-color" style="color:' . $color . ';margin-top:16px;font-size:var(--text-lg);line-height:1.6">' . $text . '</p>' . "\n"
		. '<!-- /wp:paragraph -->' . "\n";
}

/**
 * The section head: eyebrow, heading and optional lede, capped at the 720px
 * measure the sections use and left-aligned (the design centres type once per
 * page at most, and that once is the pricing head).
 *
 * @param string $eyebrow Escaped eyebrow text.
 * @param string $heading Escaped heading text.
 * @param string $lede    Escaped lede text, or '' for none.
 * @param bool   $dark    True on the `--ink-900` bands.
 * @return string Block markup.
 */
function intera_pattern_head( $eyebrow, $heading, $lede = '', $dark = false ) {
	$intera_inner = intera_pattern_eyebrow( $eyebrow, $dark ? 'var(--blue-200)' : 'var(--blue-600)' )
		. intera_pattern_heading( $heading, $dark ? 'var(--white)' : 'var(--ink-900)' );

	if ( '' !== $lede ) {
		$intera_inner .= intera_pattern_lede( $lede, $dark ? 'rgba(255,255,255,.72)' : 'var(--ink-600)' );
	}

	return '<!-- wp:group {"style":{"spacing":{"margin":{"bottom":"40px"}}},"layout":{"type":"constrained","contentSize":"720px","justifyContent":"left"}} -->' . "\n"
		. '<div class="wp-block-group" style="margin-bottom:40px">' . "\n"
		. $intera_inner
		. '</div>' . "\n"
		. '<!-- /wp:group -->' . "\n";
}

/**
 * A columns row at the design's 20px gutter.
 *
 * `blockGap` is deliberately absent from the serialised markup: WordPress skips
 * it on save and renders the gap as layout CSS instead.
 *
 * @param string[] $columns Block markup, one entry per column.
 * @param string   $gap     Gutter.
 * @return string Block markup.
 */
function intera_pattern_columns( array $columns, $gap = '20px' ) {
	$intera_markup = '<!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"' . $gap . '","left":"' . $gap . '"}}}} -->' . "\n"
		. '<div class="wp-block-columns">' . "\n";

	foreach ( $columns as $intera_column ) {
		$intera_markup .= '<!-- wp:column -->' . "\n"
			. '<div class="wp-block-column">' . "\n"
			. $intera_column
			. '</div>' . "\n"
			. '<!-- /wp:column -->' . "\n";
	}

	return $intera_markup . '</div>' . "\n" . '<!-- /wp:columns -->' . "\n";
}

/**
 * Pattern — section header.
 *
 * @return string Block markup.
 */
function intera_pattern_section_header() {
	return intera_pattern_head(
		esc_html__( 'What you get', 'intera' ),
		esc_html__( 'Say the promise here. Keep it to one line.', 'intera' ),
		esc_html__( 'The heading carries the argument; this line carries the proof — what actually happens, in the reader\'s own words.', 'intera' )
	);
}

/**
 * Pattern — feature card grid.
 *
 * The `.itr-card .itr-lift` pair is the Card component's surface plus its hover
 * rise, so the pattern's cards behave exactly like the templates' cards.
 *
 * @return string Block markup.
 */
function intera_pattern_feature_grid() {
	$intera_cards = array(
		array(
			esc_html__( 'Your systems stay', 'intera' ),
			esc_html__( 'INTERA reads ERP, CRM, billing and spreadsheets. Nothing is written back.', 'intera' ),
		),
		array(
			esc_html__( 'Watch the numbers you own', 'intera' ),
			esc_html__( 'Every metric carries its value, its source and the time it was read.', 'intera' ),
		),
		array(
			esc_html__( 'See what needs action', 'intera' ),
			esc_html__( 'Each item says what changed, who owns it, and when it becomes a problem.', 'intera' ),
		),
	);

	$intera_columns = array();

	foreach ( $intera_cards as $intera_card ) {
		$intera_columns[] = '<!-- wp:group {"className":"itr-card itr-lift","style":{"spacing":{"padding":{"top":"var(--space-7)","right":"var(--space-7)","bottom":"var(--space-7)","left":"var(--space-7)"}}},"layout":{"type":"constrained"}} -->' . "\n"
			. '<div class="wp-block-group itr-card itr-lift" style="padding-top:var(--space-7);padding-right:var(--space-7);padding-bottom:var(--space-7);padding-left:var(--space-7)">' . "\n"
			. '<!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"var(--text-xl)","fontWeight":"600","lineHeight":"1.3","letterSpacing":"-0.01em"},"color":{"text":"var(--ink-900)"}}} -->' . "\n"
			. '<h3 class="wp-block-heading has-text-color" style="color:var(--ink-900);font-size:var(--text-xl);font-weight:600;letter-spacing:-0.01em;line-height:1.3">' . $intera_card[0] . '</h3>' . "\n"
			. '<!-- /wp:heading -->' . "\n"
			. '<!-- wp:paragraph {"style":{"typography":{"fontSize":"var(--text-md)","lineHeight":"1.6"},"color":{"text":"var(--ink-600)"},"spacing":{"margin":{"top":"10px"}}}} -->' . "\n"
			. '<p class="has-text-color" style="color:var(--ink-600);margin-top:10px;font-size:var(--text-md);line-height:1.6">' . $intera_card[1] . '</p>' . "\n"
			. '<!-- /wp:paragraph -->' . "\n"
			. '</div>' . "\n"
			. '<!-- /wp:group -->' . "\n";
	}

	return intera_pattern_head(
		esc_html__( 'What you get', 'intera' ),
		esc_html__( 'Visibility you can act on', 'intera' )
	) . intera_pattern_columns( $intera_columns );
}

/**
 * Pattern — numbered step list.
 *
 * The ordinal is mono, the rule under each row is `--border-hairline`, and the
 * list is capped at the 520px measure the Method list uses.
 *
 * @return string Block markup.
 */
function intera_pattern_step_list() {
	$intera_steps = array(
		esc_html__( 'Map how the operation actually runs', 'intera' ),
		esc_html__( 'Name the checks nobody owns', 'intera' ),
		esc_html__( 'Connect the systems that already hold the data', 'intera' ),
		esc_html__( 'Build the first role and watch it', 'intera' ),
	);

	$intera_rows  = '';
	$intera_index = 0;

	foreach ( $intera_steps as $intera_step ) {
		++$intera_index;

		$intera_rows .= '<!-- wp:group {"style":{"spacing":{"padding":{"top":"14px","bottom":"14px"},"blockGap":"14px"},"border":{"bottom":{"color":"var(--border-hairline)","style":"solid","width":"1px"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->' . "\n"
			. '<div class="wp-block-group" style="border-bottom-color:var(--border-hairline);border-bottom-style:solid;border-bottom-width:1px;padding-top:14px;padding-bottom:14px">' . "\n"
			. '<!-- wp:paragraph {"style":{"typography":{"fontFamily":"var(--font-mono)","fontSize":"var(--text-xs)"},"color":{"text":"var(--text-muted)"}}} -->' . "\n"
			. '<p class="has-text-color" style="color:var(--text-muted);font-family:var(--font-mono);font-size:var(--text-xs)">' . esc_html( sprintf( '%02d', $intera_index ) ) . '</p>' . "\n"
			. '<!-- /wp:paragraph -->' . "\n"
			. '<!-- wp:paragraph {"style":{"typography":{"fontSize":"var(--text-md)","lineHeight":"1.5"},"color":{"text":"var(--ink-700)"}}} -->' . "\n"
			. '<p class="has-text-color" style="color:var(--ink-700);font-size:var(--text-md);line-height:1.5">' . $intera_step . '</p>' . "\n"
			. '<!-- /wp:paragraph -->' . "\n"
			. '</div>' . "\n"
			. '<!-- /wp:group -->' . "\n";
	}

	return intera_pattern_head(
		esc_html__( 'The steps', 'intera' ),
		esc_html__( 'How the first role gets built', 'intera' ),
		esc_html__( 'One role. One operational problem. One working result.', 'intera' )
	)
		. '<!-- wp:group {"style":{"border":{"top":{"color":"var(--border-hairline)","style":"solid","width":"1px"}}},"layout":{"type":"constrained","contentSize":"520px","justifyContent":"left"}} -->' . "\n"
		. '<div class="wp-block-group" style="border-top-color:var(--border-hairline);border-top-style:solid;border-top-width:1px">' . "\n"
		. $intera_rows
		. '</div>' . "\n"
		. '<!-- /wp:group -->' . "\n";
}

/**
 * Pattern — metric tile row.
 *
 * `.itr-card` draws the same surface MetricTile draws inline (card ground,
 * `--border-card` edge, `--radius-card`, `--shadow-xs`), so the tiles match the
 * component without repeating a single value.
 *
 * @return string Block markup.
 */
function intera_pattern_stat_tiles() {
	$intera_tiles = array(
		array(
			'label' => esc_html__( 'Open incidents', 'intera' ),
			'value' => '7',
			'note'  => '+2',
			'tone'  => 'var(--status-warning)',
		),
		array(
			'label' => esc_html__( 'Unreconciled records', 'intera' ),
			'value' => '4,812',
			'note'  => '-311',
			'tone'  => 'var(--status-ok)',
		),
		array(
			'label' => esc_html__( 'Invoices that differ', 'intera' ),
			'value' => '118',
			'note'  => esc_html__( 'billing vs ERP', 'intera' ),
			'tone'  => 'var(--text-muted)',
		),
	);

	$intera_columns = array();

	foreach ( $intera_tiles as $intera_tile ) {
		$intera_columns[] = '<!-- wp:group {"className":"itr-card","style":{"spacing":{"padding":{"top":"var(--space-4)","right":"var(--space-4)","bottom":"var(--space-4)","left":"var(--space-4)"},"blockGap":"6px"}},"layout":{"type":"flex","orientation":"vertical"}} -->' . "\n"
			. '<div class="wp-block-group itr-card" style="padding-top:var(--space-4);padding-right:var(--space-4);padding-bottom:var(--space-4);padding-left:var(--space-4)">' . "\n"
			. '<!-- wp:paragraph {"style":{"typography":{"fontSize":"var(--text-xs)","lineHeight":"1.4"},"color":{"text":"var(--text-secondary)"}}} -->' . "\n"
			. '<p class="has-text-color" style="color:var(--text-secondary);font-size:var(--text-xs);line-height:1.4">' . $intera_tile['label'] . '</p>' . "\n"
			. '<!-- /wp:paragraph -->' . "\n"
			. '<!-- wp:paragraph {"style":{"typography":{"fontFamily":"var(--font-mono)","fontSize":"var(--text-2xl)","fontWeight":"var(--weight-medium)","letterSpacing":"var(--tracking-snug)","lineHeight":"1.1"},"color":{"text":"var(--text-primary)"}}} -->' . "\n"
			. '<p class="has-text-color" style="color:var(--text-primary);font-family:var(--font-mono);font-size:var(--text-2xl);font-weight:var(--weight-medium);letter-spacing:var(--tracking-snug);line-height:1.1">' . esc_html( $intera_tile['value'] ) . '</p>' . "\n"
			. '<!-- /wp:paragraph -->' . "\n"
			. '<!-- wp:paragraph {"style":{"typography":{"fontFamily":"var(--font-mono)","fontSize":"var(--text-xs)"},"color":{"text":"' . $intera_tile['tone'] . '"}}} -->' . "\n"
			. '<p class="has-text-color" style="color:' . $intera_tile['tone'] . ';font-family:var(--font-mono);font-size:var(--text-xs)">' . $intera_tile['note'] . '</p>' . "\n"
			. '<!-- /wp:paragraph -->' . "\n"
			. '</div>' . "\n"
			. '<!-- /wp:group -->' . "\n";
	}

	return intera_pattern_columns( $intera_columns, '10px' );
}

/**
 * Pattern — the signal chain.
 *
 * Event → Reconciliation → Incident → Pattern, in that order, in those colours.
 * The chevrons the component draws between the panels are inlined SVG, so they
 * are left out here rather than approximated; the labels and captions are the
 * product's own definitions and are not placeholder copy.
 *
 * These four panels carry no hover class, so their soft fill and their outline
 * are inline — which is what the component does too.
 *
 * @return string Block markup.
 */
function intera_pattern_signal_chain() {
	$intera_signals = array(
		array(
			'key'     => 'event',
			'label'   => esc_html__( 'Event', 'intera' ),
			'caption' => esc_html__( 'Something important changed.', 'intera' ),
		),
		array(
			'key'     => 'reconciliation',
			'label'   => esc_html__( 'Reconciliation', 'intera' ),
			'caption' => esc_html__( 'Things that should agree — don\'t.', 'intera' ),
		),
		array(
			'key'     => 'incident',
			'label'   => esc_html__( 'Incident', 'intera' ),
			'caption' => esc_html__( 'Something requires attention and action.', 'intera' ),
		),
		array(
			'key'     => 'pattern',
			'label'   => esc_html__( 'Pattern', 'intera' ),
			'caption' => esc_html__( 'What keeps happening, and under which conditions.', 'intera' ),
		),
	);

	$intera_columns = array();

	foreach ( $intera_signals as $intera_signal ) {
		$intera_accent = 'var(--signal-' . $intera_signal['key'] . ')';
		$intera_line   = 'var(--signal-' . $intera_signal['key'] . '-line)';
		$intera_soft   = 'var(--signal-' . $intera_signal['key'] . '-soft)';

		$intera_border_json = '"border":{"top":{"color":"' . $intera_accent . '","style":"solid","width":"3px"},'
			. '"right":{"color":"' . $intera_line . '","style":"solid","width":"1px"},'
			. '"bottom":{"color":"' . $intera_line . '","style":"solid","width":"1px"},'
			. '"left":{"color":"' . $intera_line . '","style":"solid","width":"1px"},'
			. '"radius":"var(--radius-md)"}';

		$intera_border_css = 'border-top-color:' . $intera_accent . ';border-top-style:solid;border-top-width:3px;'
			. 'border-right-color:' . $intera_line . ';border-right-style:solid;border-right-width:1px;'
			. 'border-bottom-color:' . $intera_line . ';border-bottom-style:solid;border-bottom-width:1px;'
			. 'border-left-color:' . $intera_line . ';border-left-style:solid;border-left-width:1px;'
			. 'border-radius:var(--radius-md)';

		$intera_columns[] = '<!-- wp:group {"style":{' . $intera_border_json . ',"color":{"background":"' . $intera_soft . '"},"spacing":{"padding":{"top":"var(--space-4)","right":"var(--space-4)","bottom":"var(--space-4)","left":"var(--space-4)"},"blockGap":"6px"}},"layout":{"type":"constrained"}} -->' . "\n"
			. '<div class="wp-block-group has-background" style="' . $intera_border_css . ';background-color:' . $intera_soft . ';padding-top:var(--space-4);padding-right:var(--space-4);padding-bottom:var(--space-4);padding-left:var(--space-4)">' . "\n"
			. '<!-- wp:paragraph {"style":{"typography":{"fontSize":"var(--text-md)","fontWeight":"var(--weight-semibold)","letterSpacing":"var(--tracking-snug)"},"color":{"text":"var(--ink-900)"}}} -->' . "\n"
			. '<p class="has-text-color" style="color:var(--ink-900);font-size:var(--text-md);font-weight:var(--weight-semibold);letter-spacing:var(--tracking-snug)">' . $intera_signal['label'] . '</p>' . "\n"
			. '<!-- /wp:paragraph -->' . "\n"
			. '<!-- wp:paragraph {"style":{"typography":{"fontSize":"var(--text-sm)","lineHeight":"var(--leading-normal)"},"color":{"text":"var(--ink-600)"}}} -->' . "\n"
			. '<p class="has-text-color" style="color:var(--ink-600);font-size:var(--text-sm);line-height:var(--leading-normal)">' . $intera_signal['caption'] . '</p>' . "\n"
			. '<!-- /wp:paragraph -->' . "\n"
			. '</div>' . "\n"
			. '<!-- /wp:group -->' . "\n";
	}

	return intera_pattern_head(
		esc_html__( 'The chain', 'intera' ),
		esc_html__( 'Four object types, in a fixed order', 'intera' ),
		esc_html__( 'Everything a role shows you is one of them.', 'intera' )
	) . intera_pattern_columns( $intera_columns, '12px' );
}

/**
 * A list of short lines inside a dark panel.
 *
 * @param string[] $items Escaped lines.
 * @return string Block markup.
 */
function intera_pattern_inverse_list( array $items ) {
	$intera_markup = '<!-- wp:list {"style":{"typography":{"fontSize":"var(--text-md)","lineHeight":"1.5"},"color":{"text":"rgba(255,255,255,.82)"},"spacing":{"margin":{"top":"16px"}}}} -->' . "\n"
		. '<ul class="wp-block-list has-text-color" style="color:rgba(255,255,255,.82);margin-top:16px;font-size:var(--text-md);line-height:1.5">';

	foreach ( $items as $intera_item ) {
		$intera_markup .= '<!-- wp:list-item -->' . "\n"
			. '<li>' . $intera_item . '</li>' . "\n"
			. '<!-- /wp:list-item -->' . "\n";
	}

	return $intera_markup . '</ul>' . "\n" . '<!-- /wp:list -->' . "\n";
}

/**
 * Pattern — dark call-to-action band.
 *
 * A full-width `--ink-900` band with the two panels the Early Adopter section
 * uses: `.itr-panel` and its highlighted sibling `.itr-hl-panel`, both painted
 * entirely by the class.
 *
 * @return string Block markup.
 */
function intera_pattern_dark_cta() {
	$intera_panels = array(
		'<!-- wp:group {"className":"itr-panel","style":{"border":{"radius":"var(--radius-card)"},"spacing":{"padding":{"top":"24px","right":"24px","bottom":"24px","left":"24px"}}},"layout":{"type":"constrained"}} -->' . "\n"
			. '<div class="wp-block-group itr-panel" style="border-radius:var(--radius-card);padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px">' . "\n"
			. intera_pattern_eyebrow( esc_html__( 'What you get', 'intera' ), 'var(--text-inverse-muted)' )
			. intera_pattern_inverse_list(
				array(
					esc_html__( 'A working INTERA environment', 'intera' ),
					esc_html__( 'Connected data sources', 'intera' ),
					esc_html__( 'A first role your team actually uses', 'intera' ),
				)
			)
			. '</div>' . "\n"
			. '<!-- /wp:group -->' . "\n",

		'<!-- wp:group {"className":"itr-hl-panel","style":{"border":{"radius":"var(--radius-card)"},"spacing":{"padding":{"top":"24px","right":"24px","bottom":"24px","left":"24px"}}},"layout":{"type":"constrained"}} -->' . "\n"
			. '<div class="wp-block-group itr-hl-panel" style="border-radius:var(--radius-card);padding-top:24px;padding-right:24px;padding-bottom:24px;padding-left:24px">' . "\n"
			. intera_pattern_eyebrow( esc_html__( 'What we ask for', 'intera' ), 'var(--text-inverse-muted)' )
			. intera_pattern_inverse_list(
				array(
					esc_html__( 'A real business case', 'intera' ),
					esc_html__( 'Feedback', 'intera' ),
					esc_html__( 'Time with the people who run the area', 'intera' ),
				)
			)
			. '<!-- wp:group {"style":{"spacing":{"margin":{"top":"26px"},"padding":{"top":"20px"}},"border":{"top":{"color":"var(--border-inverse)","style":"solid","width":"1px"}}},"layout":{"type":"constrained"}} -->' . "\n"
			. '<div class="wp-block-group" style="border-top-color:var(--border-inverse);border-top-style:solid;border-top-width:1px;margin-top:26px;padding-top:20px">' . "\n"
			. '<!-- wp:buttons -->' . "\n"
			. '<div class="wp-block-buttons">' . "\n"
			. '<!-- wp:button {"url":"#","style":{"color":{"background":"var(--white)","text":"var(--ink-900)"},"border":{"radius":"var(--radius-control)"},"typography":{"fontSize":"var(--text-base)","fontWeight":"var(--weight-medium)"},"spacing":{"padding":{"top":"10px","right":"20px","bottom":"10px","left":"20px"}}}} -->' . "\n"
			. '<div class="wp-block-button"><a class="wp-block-button__link has-text-color has-background wp-element-button" href="#" style="border-radius:var(--radius-control);color:var(--ink-900);background-color:var(--white);padding-top:10px;padding-right:20px;padding-bottom:10px;padding-left:20px;font-size:var(--text-base);font-weight:var(--weight-medium)">' . esc_html__( 'I have a problem INTERA could solve', 'intera' ) . '</a></div>' . "\n"
			. '<!-- /wp:button -->' . "\n"
			. '</div>' . "\n"
			. '<!-- /wp:buttons -->' . "\n"
			. '</div>' . "\n"
			. '<!-- /wp:group -->' . "\n"
			. '</div>' . "\n"
			. '<!-- /wp:group -->' . "\n",
	);

	return '<!-- wp:group {"align":"full","style":{"color":{"background":"var(--ink-900)"},"spacing":{"padding":{"top":"clamp(51px, 7vw, 88px)","right":"clamp(20px, 5vw, 24px)","bottom":"clamp(51px, 7vw, 88px)","left":"clamp(20px, 5vw, 24px)"}}},"layout":{"type":"constrained","contentSize":"1160px"}} -->' . "\n"
		. '<div class="wp-block-group alignfull has-background" style="background-color:var(--ink-900);padding-top:clamp(51px, 7vw, 88px);padding-right:clamp(20px, 5vw, 24px);padding-bottom:clamp(51px, 7vw, 88px);padding-left:clamp(20px, 5vw, 24px)">' . "\n"
		. intera_pattern_head(
			esc_html__( 'Talk to us', 'intera' ),
			esc_html__( 'Start with one operational problem', 'intera' ),
			esc_html__( 'During beta we work with a small number of teams, on the tasks they already have to get right.', 'intera' ),
			true
		)
		. intera_pattern_columns( $intera_panels )
		. '</div>' . "\n"
		. '<!-- /wp:group -->' . "\n";
}

/**
 * Pattern — product screenshot frame.
 *
 * `.itr-frame` carries the edge, the shadow and the hover rise; `.itr-shot`
 * carries the crop, and it sits on the image block itself so the stylesheet's
 * `.itr-shot > img` rule reaches the `<img>` inside the figure. The crop height
 * is the class default (420px) because a per-instance `--itr-shot-h` would be
 * stripped out of post content by `wp_kses_post()`.
 *
 * The partial clips the crop to the frame radius with `overflow: hidden`; no
 * core block carries that property, so here the crop's two bottom corners stay
 * square. It is the one place a pattern is knowingly a hair off the template.
 *
 * @return string Block markup.
 */
function intera_pattern_screenshot() {
	return '<!-- wp:group {"className":"itr-frame","style":{"border":{"radius":"var(--radius-xl)"}},"layout":{"type":"constrained"}} -->' . "\n"
		. '<div class="wp-block-group itr-frame" style="border-radius:var(--radius-xl)">' . "\n"
		. '<!-- wp:group {"style":{"color":{"background":"var(--surface-sunken)"},"border":{"bottom":{"color":"var(--border-hairline)","style":"solid","width":"1px"}},"spacing":{"padding":{"top":"10px","right":"14px","bottom":"10px","left":"14px"}}},"layout":{"type":"constrained"}} -->' . "\n"
		. '<div class="wp-block-group has-background" style="border-bottom-color:var(--border-hairline);border-bottom-style:solid;border-bottom-width:1px;background-color:var(--surface-sunken);padding-top:10px;padding-right:14px;padding-bottom:10px;padding-left:14px">' . "\n"
		. '<!-- wp:paragraph {"style":{"typography":{"fontFamily":"var(--font-mono)","fontSize":"var(--text-2xs)"},"color":{"text":"var(--ink-500)"}}} -->' . "\n"
		. '<p class="has-text-color" style="color:var(--ink-500);font-family:var(--font-mono);font-size:var(--text-2xs)">' . esc_html__( 'Attention queue · ranked by priority and time to impact', 'intera' ) . '</p>' . "\n"
		. '<!-- /wp:paragraph -->' . "\n"
		. '</div>' . "\n"
		. '<!-- /wp:group -->' . "\n"
		. '<!-- wp:image {"className":"itr-shot"} -->' . "\n"
		. '<figure class="wp-block-image itr-shot"><img alt=""/></figure>' . "\n"
		. '<!-- /wp:image -->' . "\n"
		. '</div>' . "\n"
		. '<!-- /wp:group -->' . "\n";
}

/**
 * One accordion row: a core Details block with the answer as a paragraph.
 *
 * This is the exact shape `page-faq.php` looks for — `<details …><summary>…
 * </summary>` followed by paragraphs — so the FAQ template can dress it with the
 * export's rules, chevron and prose column. It carries no styling of its own on
 * purpose: the template merges its own in, and on any other page the row stays a
 * plain, working `<details>`.
 *
 * @param string $question Escaped question.
 * @param string $answer   Escaped answer.
 * @return string Block markup.
 */
function intera_pattern_details( $question, $answer ) {
	return '<!-- wp:details -->' . "\n"
		. '<details class="wp-block-details"><summary>' . $question . '</summary>' . "\n"
		. '<!-- wp:paragraph -->' . "\n"
		. '<p>' . $answer . '</p>' . "\n"
		. '<!-- /wp:paragraph -->' . "\n"
		. '</details>' . "\n"
		. '<!-- /wp:details -->' . "\n";
}

/**
 * Pattern — question and answer group.
 *
 * An `h2` opens the group (the FAQ template reads those headings for its "On
 * this page" rail and starts a new rule above the first row of each group), then
 * one Details block per question.
 *
 * @return string Block markup.
 */
function intera_pattern_faq() {
	$intera_pairs = array(
		array(
			esc_html__( 'What does INTERA need from our systems?', 'intera' ),
			esc_html__( 'Read-only access to the systems that already hold the data. Nothing is written back.', 'intera' ),
		),
		array(
			esc_html__( 'Does it replace our ERP or CRM?', 'intera' ),
			esc_html__( 'No. Your systems stay. INTERA reads them and makes what they hold easier to act on.', 'intera' ),
		),
		array(
			esc_html__( 'How long before we see something useful?', 'intera' ),
			esc_html__( 'One role, one operational problem. The first working result comes out of that, not out of a full rollout.', 'intera' ),
		),
	);

	$intera_markup = '<!-- wp:heading -->' . "\n"
		. '<h2 class="wp-block-heading">' . esc_html__( 'Getting started', 'intera' ) . '</h2>' . "\n"
		. '<!-- /wp:heading -->' . "\n";

	foreach ( $intera_pairs as $intera_pair ) {
		$intera_markup .= intera_pattern_details( $intera_pair[0], $intera_pair[1] );
	}

	return $intera_markup;
}

/**
 * Every pattern, keyed by the slug it registers under.
 *
 * Keywords are what an editor would actually type into the inserter, so they
 * include the plain-English name of the thing as well as the product's own word
 * for it.
 *
 * @return array[] Pattern definitions for register_block_pattern().
 */
function intera_block_patterns() {
	return array(
		'section-header'   => array(
			'title'       => __( 'Section header', 'intera' ),
			'description' => __( 'An eyebrow, a section heading and a lede, at the measure the marketing sections use.', 'intera' ),
			'keywords'    => array( __( 'header', 'intera' ), __( 'heading', 'intera' ), __( 'eyebrow', 'intera' ), __( 'intro', 'intera' ) ),
			'content'     => intera_pattern_section_header(),
		),
		'feature-grid'     => array(
			'title'       => __( 'Feature card grid', 'intera' ),
			'description' => __( 'Three cards that lift on hover, for the points a section has to make.', 'intera' ),
			'keywords'    => array( __( 'cards', 'intera' ), __( 'features', 'intera' ), __( 'grid', 'intera' ), __( 'roles', 'intera' ), __( 'columns', 'intera' ) ),
			'content'     => intera_pattern_feature_grid(),
		),
		'step-list'        => array(
			'title'       => __( 'Numbered step list', 'intera' ),
			'description' => __( 'Steps in order, each with a mono ordinal and a hairline rule under it.', 'intera' ),
			'keywords'    => array( __( 'steps', 'intera' ), __( 'numbered', 'intera' ), __( 'how it works', 'intera' ), __( 'method', 'intera' ), __( 'process', 'intera' ) ),
			'content'     => intera_pattern_step_list(),
		),
		'metric-tiles'     => array(
			'title'       => __( 'Metric tile row', 'intera' ),
			'description' => __( 'A row of tiles, each with a label, a mono value and the change under it.', 'intera' ),
			'keywords'    => array( __( 'metrics', 'intera' ), __( 'tiles', 'intera' ), __( 'numbers', 'intera' ), __( 'stats', 'intera' ), __( 'kpi', 'intera' ) ),
			'content'     => intera_pattern_stat_tiles(),
		),
		'signal-chain'     => array(
			'title'       => __( 'Signal chain', 'intera' ),
			'description' => __( 'Event, reconciliation, incident, pattern — the four object types in their fixed order and colours.', 'intera' ),
			'keywords'    => array( __( 'signals', 'intera' ), __( 'chain', 'intera' ), __( 'event', 'intera' ), __( 'reconciliation', 'intera' ), __( 'incident', 'intera' ), __( 'pattern', 'intera' ) ),
			'content'     => intera_pattern_signal_chain(),
		),
		'dark-cta'         => array(
			'title'       => __( 'Dark call-to-action band', 'intera' ),
			'description' => __( 'A full-width dark band with two panels: what you get, what we ask for, and the button.', 'intera' ),
			'keywords'    => array( __( 'cta', 'intera' ), __( 'dark', 'intera' ), __( 'band', 'intera' ), __( 'early adopter', 'intera' ), __( 'contact', 'intera' ) ),
			'content'     => intera_pattern_dark_cta(),
		),
		'screenshot-frame' => array(
			'title'       => __( 'Product screenshot frame', 'intera' ),
			'description' => __( 'A framed product shot with a mono caption bar over a fixed crop.', 'intera' ),
			'keywords'    => array( __( 'screenshot', 'intera' ), __( 'frame', 'intera' ), __( 'image', 'intera' ), __( 'product', 'intera' ) ),
			'content'     => intera_pattern_screenshot(),
		),
		'faq-group'        => array(
			'title'       => __( 'Question and answer group', 'intera' ),
			'description' => __( 'A group heading and three collapsible questions, in the markup the FAQ template styles.', 'intera' ),
			'keywords'    => array( __( 'faq', 'intera' ), __( 'questions', 'intera' ), __( 'accordion', 'intera' ), __( 'details', 'intera' ) ),
			'content'     => intera_pattern_faq(),
		),
	);
}

/**
 * Register the pattern category and every pattern in it.
 *
 * Runs on `init`, which is where WordPress expects both registries to be
 * populated. Guarded because the theme is deployed straight from `main`: on an
 * install without the pattern API the site loses its patterns and nothing else.
 */
function intera_register_block_patterns() {
	if ( ! function_exists( 'register_block_pattern' ) || ! function_exists( 'register_block_pattern_category' ) ) {
		return;
	}

	register_block_pattern_category(
		'intera',
		array( 'label' => __( 'INTERA sections', 'intera' ) )
	);

	foreach ( intera_block_patterns() as $intera_slug => $intera_pattern ) {
		$intera_pattern['categories']    = array( 'intera' );
		$intera_pattern['viewportWidth'] = 1160;

		register_block_pattern( 'intera/' . $intera_slug, $intera_pattern );
	}
}
add_action( 'init', 'intera_register_block_patterns' );
