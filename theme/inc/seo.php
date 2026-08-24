<?php
/**
 * Search-result copy — the title and the description every page answers with.
 *
 * A page's `<title>` and its meta description are the only two runs of text on
 * this site that a visitor reads *before* they arrive, so they are copy like any
 * other copy: written per page, owned by WordPress, and never a by-product of
 * whatever the H1 happens to say. Left to itself the site was answering with the
 * page's own title plus the site name, which is how the front page ended up as
 * "Home – INTERA ROLES - INTERA" — the brand twice, and nothing about what the
 * product does.
 *
 * So this file does four things, in the same shape as `inc/copy.php`:
 *
 *   1. `intera_seo_defaults()` holds the written title and description for every
 *      designed screen and every archive the theme renders. A page that carries
 *      no entry falls back to its own words — its excerpt, then its content —
 *      which is what a doc or a release note wants anyway.
 *   2. `_intera_seo_title` / `_intera_seo_description` post meta override the
 *      default per page, editable in the "Search result" box and writable over
 *      REST. WordPress owns the words; this file owns only the fallback.
 *   3. `intera_seo_description_last_resort()` is the floor under all of that:
 *      when a screen carries no written words at all — an undescribed docs
 *      category, a doc published before its first paragraph was typed — it
 *      names what the screen actually holds rather than letting the head go out
 *      with no description on it.
 *   4. The result is handed to Rank Math through its own filters when the plugin
 *      is active, and printed by the theme when it is not — so the site says the
 *      same thing either way, and a manually written Rank Math value still wins.
 *
 * The brand is appended once, by `intera_seo_document_title()`, and only when
 * the written title does not already carry it: the site name is added at the end
 * of every title, so a title that says INTERA itself would say it twice.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

/** Meta key holding a hand-written search-result title. */
const INTERA_SEO_TITLE_KEY = '_intera_seo_title';

/** Meta key holding a hand-written meta description. */
const INTERA_SEO_DESC_KEY = '_intera_seo_description';

/** Longest description the theme will emit; anything longer is cut on a word. */
const INTERA_SEO_DESC_MAX = 160;

/**
 * The written title and description for every screen that has one.
 *
 * Keys are resolved by `intera_seo_key_for()`, in this order of preference:
 *
 * | key                         | matches                                        |
 * | --------------------------- | ---------------------------------------------- |
 * | `front`                     | the page set as the front page                 |
 * | `slug:<page-slug>`          | one page, by its slug (the three legal pages)  |
 * | `template:page-*.php`       | whichever page carries that page template      |
 * | `blog`, `docs`, `search`, `404` | the archives and states the theme renders  |
 *
 * Titles are written *without* the brand — `intera_seo_document_title()` adds it
 * once — and are kept short enough that the brand still fits. Descriptions are
 * one or two sentences that say what the page answers, not what it contains.
 *
 * @return array<string,array{title:string,description:string}>
 */
function intera_seo_defaults() {
	return array(
		'front' => array(
			'title'       => __( 'Operational visibility across your existing systems', 'intera' ),
			'description' => __( 'INTERA connects the ERP, CRM, billing and finance systems you already run and gives each manager a clear view of what changed, what needs action and why.', 'intera' ),
		),

		'template:page-product.php' => array(
			'title'       => __( 'Product: what it watches across your systems', 'intera' ),
			'description' => __( 'Events, Reconciliations, Incidents and Patterns — the four things every Role tracks, how read-only DataSources connect, and what a market package contains.', 'intera' ),
		),

		'template:page-pricing.php' => array(
			'title'       => __( 'Pricing and the Early Adopter programme', 'intera' ),
			'description' => __( 'A free plan, twelve months free for beta Early Adopters, and commercial plans from €750 a year. What each plan includes, and what is quoted separately.', 'intera' ),
		),

		'template:page-contacts.php' => array(
			'title'       => __( 'Contacts: bring us one real operational problem', 'intera' ),
			'description' => __( 'Tell us about the check, report or reconciliation someone repeats every week. Usually answered the same working day, by the people who build the product.', 'intera' ),
		),

		'template:page-contact-request.php' => array(
			'title'       => __( 'Request Early Access', 'intera' ),
			'description' => __( 'Send one real problem: what is checked by hand today, which systems are involved, and what goes wrong when it is found too late. One call, one first result.', 'intera' ),
		),

		'template:page-faq.php' => array(
			'title'       => __( 'FAQ: what it does, and what it does not', 'intera' ),
			'description' => __( 'Straight answers on integrations and read-only access, local installation, the free plan, the Early Adopter programme and how the Method works.', 'intera' ),
		),

		'slug:privacy-policy' => array(
			'title'       => __( 'Privacy Policy', 'intera' ),
			'description' => __( 'What personal data this site collects through contact requests, why it is kept, how long it is kept, and how to ask for it to be corrected or deleted.', 'intera' ),
		),

		'slug:cookie-policy' => array(
			'title'       => __( 'Cookie Policy', 'intera' ),
			'description' => __( 'Which cookies this site sets, what each one is used for, how long it lasts, and how to control or remove them from your browser.', 'intera' ),
		),

		'slug:license' => array(
			'title'       => __( 'License Agreement', 'intera' ),
			'description' => __( 'The licence terms for using the software, including beta and Early Adopter use, what the licence permits, and the limits of warranty and liability.', 'intera' ),
		),

		'blog' => array(
			'title'       => __( 'Blog: release notes and operational stories', 'intera' ),
			'description' => __( 'Release notes for each version, and stories from real operations — where problems hide between systems, and what it takes to see them earlier.', 'intera' ),
		),

		'docs' => array(
			'title'       => __( 'Docs: install, connect, and build Roles', 'intera' ),
			'description' => __( 'Documentation for installing, connecting data sources, modelling assets and metrics, and building the Roles, dashboards and checks your team runs on.', 'intera' ),
		),

		'search' => array(
			'title'       => __( 'Search results', 'intera' ),
			'description' => __( 'Everything on this site that matches your search — docs, release notes and pages.', 'intera' ),
		),

		'404' => array(
			'title'       => __( 'Page not found', 'intera' ),
			'description' => __( 'That page is not here any more. The docs, the product pages and the way to reach us all still are.', 'intera' ),
		),
	);
}

/**
 * Which defaults entry, if any, the current request answers with.
 *
 * Only the front page and the designed pages are named here; a doc, a release
 * note or a category is answered from its own words instead, which is both more
 * accurate and one less thing to keep in step.
 *
 * @return string Key into `intera_seo_defaults()`, or '' when there is none.
 */
function intera_seo_key_for() {
	if ( is_404() ) {
		return '404';
	}

	if ( is_search() ) {
		return 'search';
	}

	if ( is_front_page() ) {
		return 'front';
	}

	if ( is_home() ) {
		return 'blog';
	}

	if ( is_post_type_archive( 'docs' ) ) {
		return 'docs';
	}

	if ( ! is_singular() ) {
		return '';
	}

	$post = get_post();

	if ( ! $post instanceof WP_Post ) {
		return '';
	}

	$defaults = intera_seo_defaults();
	$slug_key = 'slug:' . $post->post_name;

	if ( isset( $defaults[ $slug_key ] ) ) {
		return $slug_key;
	}

	$template = (string) get_page_template_slug( $post );

	if ( '' !== $template && isset( $defaults[ 'template:' . $template ] ) ) {
		return 'template:' . $template;
	}

	return '';
}

/**
 * One field of a written default, or ''.
 *
 * @param string $field `title` or `description`.
 * @return string
 */
function intera_seo_default( $field ) {
	$key      = intera_seo_key_for();
	$defaults = intera_seo_defaults();

	if ( '' === $key || ! isset( $defaults[ $key ][ $field ] ) ) {
		return '';
	}

	return (string) $defaults[ $key ][ $field ];
}

/**
 * A hand-written override stored on the current post.
 *
 * @param string $meta_key Which of the two keys to read.
 * @return string
 */
function intera_seo_meta( $meta_key ) {
	// `is_home()` too: the posts page is a real page and can carry an override.
	if ( ! is_singular() && ! is_home() ) {
		return '';
	}

	$post_id = get_queried_object_id();

	if ( ! $post_id ) {
		return '';
	}

	return trim( (string) get_post_meta( $post_id, $meta_key, true ) );
}

/**
 * The page's title, without the brand.
 *
 * Written override first, then the design's own default, then the page's own
 * words — the post title for anything singular, the term or archive name
 * otherwise. `intera_seo_document_title()` is what adds the site name.
 *
 * @return string
 */
function intera_seo_title() {
	$title = intera_seo_meta( INTERA_SEO_TITLE_KEY );

	if ( '' === $title ) {
		$title = intera_seo_default( 'title' );
	}

	if ( '' === $title && is_singular() ) {
		$title = (string) get_the_title( get_queried_object_id() );
	}

	if ( '' === $title && ( is_category() || is_tag() || is_tax() ) ) {
		$term = get_queried_object();

		if ( $term instanceof WP_Term ) {
			$title = (string) $term->name;
		}
	}

	if ( '' === $title ) {
		$title = trim( wp_strip_all_tags( get_the_archive_title() ) );
	}

	$title = trim( wp_strip_all_tags( $title ) );

	/*
	 * A doc names its section. "Widgets" and "Reference" are titles a dozen
	 * products share; "Widgets - Docs" is the result someone looking for this
	 * product's documentation can recognise. Only for docs answering with their
	 * own title — a written default already says what it needs to.
	 */
	if ( '' === intera_seo_meta( INTERA_SEO_TITLE_KEY ) && is_singular( 'docs' ) ) {
		$section = __( 'Docs', 'intera' );

		if ( false === mb_stripos( $title, $section ) ) {
			$title .= ' ' . intera_seo_separator() . ' ' . $section;
		}
	}

	/**
	 * Filter the brand-free page title.
	 *
	 * @param string $title Title without the site name.
	 */
	return (string) apply_filters( 'intera_seo_title', $title );
}

/**
 * The page's meta description.
 *
 * Written override, then the design's own default, then the page's own words:
 * its excerpt when it has one, its opening prose when it does not. A term
 * archive answers with the term description for the same reason.
 *
 * @return string
 */
function intera_seo_description() {
	$description = intera_seo_meta( INTERA_SEO_DESC_KEY );

	if ( '' === $description ) {
		$description = intera_seo_default( 'description' );
	}

	if ( '' === $description && is_singular() ) {
		$post = get_post();

		if ( $post instanceof WP_Post ) {
			$description = trim( (string) $post->post_excerpt );

			if ( '' === $description ) {
				/*
				 * The raw content, not `the_content` — running the filters here
				 * means running every shortcode and block render on a request
				 * that only wants one sentence out of the result.
				 */
				$description = strip_shortcodes( wp_strip_all_tags( (string) $post->post_content ) );
			}
		}
	}

	if ( '' === $description && ( is_category() || is_tag() || is_tax() ) ) {
		$term = get_queried_object();

		if ( $term instanceof WP_Term ) {
			$description = wp_strip_all_tags( (string) $term->description );
		}
	}

	if ( '' === $description ) {
		$description = intera_seo_description_last_resort();
	}

	$description = trim( preg_replace( '/\s+/u', ' ', (string) $description ) );

	if ( mb_strlen( $description ) > INTERA_SEO_DESC_MAX ) {
		$description = rtrim( mb_substr( $description, 0, INTERA_SEO_DESC_MAX - 1 ), " \t\n\r\0\x0B.,;:—-" ) . '…';
	}

	/**
	 * Filter the meta description.
	 *
	 * @param string $description Description, already trimmed to length.
	 */
	return (string) apply_filters( 'intera_seo_description', $description );
}

/**
 * What a screen says about itself when nobody has said anything at all.
 *
 * Everything above this reads words someone wrote: an override, a written
 * default, an excerpt, a body, a term description. When a screen has none of
 * them — a docs category nobody described, a doc published before its first
 * paragraph was typed — the head used to carry no description at all, and the
 * result on a search page was whatever Google could scrape off an empty page.
 *
 * So this is the floor, not a policy: it names the things the screen actually
 * holds, and nothing else. No marketing sentence is written here — a sentence
 * worth writing belongs in the term description or the page itself, where an
 * editor owns it and this function stops running.
 *
 * @return string
 */
function intera_seo_description_last_resort() {
	$brand = trim( wp_strip_all_tags( (string) get_bloginfo( 'name' ) ) );

	if ( is_tax( 'doc_category' ) ) {
		return intera_seo_term_contents( $brand );
	}

	if ( ! is_singular() ) {
		return '';
	}

	$post = get_post();

	if ( ! $post instanceof WP_Post ) {
		return '';
	}

	$title = trim( wp_strip_all_tags( (string) get_the_title( $post ) ) );

	if ( '' === $title ) {
		return '';
	}

	$terms   = get_the_terms( $post, 'doc_category' );
	$section = ( $terms && ! is_wp_error( $terms ) ) ? trim( wp_strip_all_tags( (string) $terms[0]->name ) ) : '';

	if ( '' !== $section && '' !== $brand ) {
		return sprintf(
			/* translators: 1: page title, 2: site name, 3: docs category name. */
			__( '%1$s: a page in the %2$s docs, filed under %3$s.', 'intera' ),
			$title,
			$brand,
			$section
		);
	}

	if ( '' === $brand ) {
		return '';
	}

	return sprintf(
		/* translators: 1: page title, 2: site name. */
		__( '%1$s on %2$s.', 'intera' ),
		$title,
		$brand
	);
}

/**
 * A docs category described by what is filed in it.
 *
 * The main query has already run by the time `wp_head` fires, so the first
 * few titles cost nothing to read; the total comes from the term, which counts
 * the whole archive rather than the current page of it.
 *
 * @param string $brand Site name, already stripped.
 * @return string
 */
function intera_seo_term_contents( $brand ) {
	$term = get_queried_object();

	if ( ! $term instanceof WP_Term || '' === $brand ) {
		return '';
	}

	$posts = ( isset( $GLOBALS['wp_query'] ) && $GLOBALS['wp_query'] instanceof WP_Query )
		? (array) $GLOBALS['wp_query']->posts
		: array();

	$titles = array();

	foreach ( $posts as $post ) {
		if ( ! $post instanceof WP_Post ) {
			continue;
		}

		$title = trim( wp_strip_all_tags( (string) get_the_title( $post ) ) );

		if ( '' !== $title ) {
			$titles[] = $title;
		}

		if ( count( $titles ) >= 4 ) {
			break;
		}
	}

	if ( ! $titles ) {
		return '';
	}

	$list = implode( ', ', $titles );
	$rest = (int) $term->count - count( $titles );

	if ( $rest > 0 ) {
		$list .= sprintf(
			/* translators: %d: number of further articles in the category. */
			_n( ' and %d more', ' and %d more', $rest, 'intera' ),
			$rest
		);
	}

	return sprintf(
		/* translators: 1: category name, 2: site name, 3: list of article titles. */
		__( '%1$s in the %2$s docs: %3$s.', 'intera' ),
		trim( wp_strip_all_tags( (string) $term->name ) ),
		$brand,
		$list
	);
}

/**
 * The full `<title>`: the page's title with the site name appended once.
 *
 * The site name goes at the end of every title, so a title that already names
 * the brand — "Uninstalling INTERA" — gets nothing appended rather than saying
 * it twice. That check is the whole reason this function exists.
 *
 * @return string
 */
function intera_seo_document_title() {
	$title = intera_seo_title();
	$name  = trim( wp_strip_all_tags( (string) get_bloginfo( 'name' ) ) );

	if ( '' === $title ) {
		return $name;
	}

	if ( '' === $name || false !== mb_stripos( $title, $name ) ) {
		return $title;
	}

	return $title . ' ' . intera_seo_separator() . ' ' . $name;
}

/**
 * What separates the parts of a title.
 *
 * Matches the separator Rank Math is configured with, so the two agree on the
 * pages this theme does not answer for.
 *
 * @return string Separator, without its surrounding spaces.
 */
function intera_seo_separator() {
	/**
	 * Filter the title separator.
	 *
	 * @param string $separator Separator, without its surrounding spaces.
	 */
	return (string) apply_filters( 'intera_seo_separator', '-' );
}

/**
 * Whether Rank Math is the thing writing the head.
 *
 * @return bool
 */
function intera_seo_plugin_active() {
	return defined( 'RANK_MATH_VERSION' ) || class_exists( 'RankMath' );
}

/**
 * Hand the title to Rank Math, unless someone wrote one there.
 *
 * A value typed into Rank Math's own box is a deliberate decision about one
 * page and outranks the theme's default; anything else is the plugin building a
 * title from a template, which is exactly what this file replaces.
 *
 * @param string $title Title Rank Math generated.
 * @return string
 */
function intera_seo_filter_plugin_title( $title ) {
	if ( '' !== intera_seo_meta( 'rank_math_title' ) ) {
		return $title;
	}

	$ours = intera_seo_document_title();

	return '' === $ours ? $title : $ours;
}
add_filter( 'rank_math/frontend/title', 'intera_seo_filter_plugin_title' );

/**
 * Hand the description to Rank Math, unless someone wrote one there.
 *
 * @param string $description Description Rank Math generated.
 * @return string
 */
function intera_seo_filter_plugin_description( $description ) {
	if ( '' !== intera_seo_meta( 'rank_math_description' ) ) {
		return $description;
	}

	$ours = intera_seo_description();

	return '' === $ours ? $description : $ours;
}
add_filter( 'rank_math/frontend/description', 'intera_seo_filter_plugin_description' );

/**
 * The `<title>` when no SEO plugin is writing one.
 *
 * @param string $title Title WordPress built.
 * @return string
 */
function intera_seo_filter_document_title( $title ) {
	if ( intera_seo_plugin_active() ) {
		return $title;
	}

	$ours = intera_seo_document_title();

	return '' === $ours ? $title : $ours;
}
add_filter( 'pre_get_document_title', 'intera_seo_filter_document_title' );

/**
 * Print the meta description when no SEO plugin is printing one.
 *
 * @return void
 */
function intera_seo_print_description() {
	if ( intera_seo_plugin_active() || is_paged() ) {
		return;
	}

	$description = intera_seo_description();

	if ( '' === $description ) {
		return;
	}

	echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
}
add_action( 'wp_head', 'intera_seo_print_description', 2 );

/**
 * Register the two overrides so the block editor and the REST API see them.
 *
 * Every public post type gets them: a doc or a release note is as likely to want
 * a written search-result line as a designed page is.
 *
 * @return void
 */
function intera_seo_register_meta() {
	$fields = array(
		INTERA_SEO_TITLE_KEY => __( 'Title shown in search results. The site name is added at the end automatically — leave it out.', 'intera' ),
		INTERA_SEO_DESC_KEY  => __( 'Description shown in search results. One or two sentences, up to about 160 characters.', 'intera' ),
	);

	foreach ( intera_seo_post_types() as $post_type ) {
		foreach ( $fields as $meta_key => $description ) {
			register_post_meta(
				$post_type,
				$meta_key,
				array(
					'type'              => 'string',
					'description'       => $description,
					'single'            => true,
					'default'           => '',
					'show_in_rest'      => true,
					'sanitize_callback' => 'sanitize_text_field',
					'auth_callback'     => 'intera_seo_meta_auth',
				)
			);
		}
	}
}
add_action( 'init', 'intera_seo_register_meta', 12 );

/**
 * The post types that can carry a written search-result line.
 *
 * @return array<int,string>
 */
function intera_seo_post_types() {
	$post_types = get_post_types( array( 'public' => true ), 'names' );

	unset( $post_types['attachment'] );

	/**
	 * Filter the post types offered a search-result box.
	 *
	 * @param array<int,string> $post_types Post type names.
	 */
	return (array) apply_filters( 'intera_seo_post_types', array_values( $post_types ) );
}

/**
 * Who may read and write the two overrides.
 *
 * @param bool   $allowed  Whether the user can add the meta. Unused.
 * @param string $meta_key Meta key. Unused.
 * @param int    $post_id  Post ID.
 * @return bool
 */
function intera_seo_meta_auth( $allowed, $meta_key, $post_id ) {
	unset( $allowed, $meta_key );

	return current_user_can( 'edit_post', (int) $post_id );
}

/**
 * The "Search result" box.
 *
 * Not added when Rank Math is active: two boxes asking for the same sentence is
 * how a site ends up with two different answers to it. With the plugin on, the
 * plugin's box is the place to override, and this file is the default behind it.
 *
 * @return void
 */
function intera_seo_add_meta_box() {
	if ( intera_seo_plugin_active() ) {
		return;
	}

	foreach ( intera_seo_post_types() as $post_type ) {
		add_meta_box(
			'intera-seo',
			__( 'Search result', 'intera' ),
			'intera_seo_render_meta_box',
			$post_type,
			'normal',
			'default'
		);
	}
}
add_action( 'add_meta_boxes', 'intera_seo_add_meta_box' );

/**
 * Render the "Search result" box.
 *
 * Both controls show what the page would answer with today as their
 * placeholder, so an editor can see the default before deciding to replace it.
 *
 * @param WP_Post $post Post being edited.
 * @return void
 */
function intera_seo_render_meta_box( $post ) {
	$defaults = intera_seo_defaults();
	$key      = '';

	if ( (int) get_option( 'page_on_front' ) === (int) $post->ID ) {
		$key = 'front';
	} elseif ( isset( $defaults[ 'slug:' . $post->post_name ] ) ) {
		$key = 'slug:' . $post->post_name;
	} else {
		$template = (string) get_page_template_slug( $post );

		if ( '' !== $template && isset( $defaults[ 'template:' . $template ] ) ) {
			$key = 'template:' . $template;
		}
	}

	$title_hint = '' === $key ? (string) get_the_title( $post ) : (string) $defaults[ $key ]['title'];
	$desc_hint  = '' === $key ? trim( (string) $post->post_excerpt ) : (string) $defaults[ $key ]['description'];

	wp_nonce_field( 'intera_save_seo_' . $post->ID, 'intera_seo_nonce' );
	?>
	<p class="description">
		<?php echo esc_html__( 'What this page says in a search result. Leave a field empty to keep the wording below it.', 'intera' ); ?>
	</p>
	<p>
		<label for="intera-seo-title"><strong><?php echo esc_html__( 'Title', 'intera' ); ?></strong></label><br>
		<input type="text" id="intera-seo-title" name="intera_seo_title" class="large-text"
			value="<?php echo esc_attr( (string) get_post_meta( $post->ID, INTERA_SEO_TITLE_KEY, true ) ); ?>"
			placeholder="<?php echo esc_attr( $title_hint ); ?>" />
		<span class="description">
			<?php
			printf(
				/* translators: %s: site name. */
				esc_html__( '“%s” is added at the end automatically — leave the brand out of the title.', 'intera' ),
				esc_html( get_bloginfo( 'name' ) )
			);
			?>
		</span>
	</p>
	<p>
		<label for="intera-seo-description"><strong><?php echo esc_html__( 'Description', 'intera' ); ?></strong></label><br>
		<textarea id="intera-seo-description" name="intera_seo_description" rows="3" class="large-text"
			placeholder="<?php echo esc_attr( $desc_hint ); ?>"><?php echo esc_textarea( (string) get_post_meta( $post->ID, INTERA_SEO_DESC_KEY, true ) ); ?></textarea>
		<span class="description">
			<?php
			printf(
				/* translators: %d: maximum number of characters. */
				esc_html__( 'One or two sentences. Anything past %d characters is cut.', 'intera' ),
				(int) INTERA_SEO_DESC_MAX
			);
			?>
		</span>
	</p>
	<?php
}

/**
 * Persist the "Search result" box.
 *
 * As with the copy box: no nonce means the save came from somewhere that never
 * posted these fields — a quick edit, an import, REST — and must not blank them.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post being saved.
 * @return void
 */
function intera_seo_save_meta( $post_id, $post ) {
	if ( ! isset( $_POST['intera_seo_nonce'] ) ) {
		return;
	}

	$nonce = sanitize_text_field( wp_unslash( $_POST['intera_seo_nonce'] ) );

	if ( ! wp_verify_nonce( $nonce, 'intera_save_seo_' . $post_id ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) || ! in_array( $post->post_type, intera_seo_post_types(), true ) ) {
		return;
	}

	$pairs = array(
		INTERA_SEO_TITLE_KEY => 'intera_seo_title',
		INTERA_SEO_DESC_KEY  => 'intera_seo_description',
	);

	foreach ( $pairs as $meta_key => $input ) {
		$value = isset( $_POST[ $input ] ) ? sanitize_text_field( wp_unslash( $_POST[ $input ] ) ) : '';

		if ( '' === trim( $value ) ) {
			delete_post_meta( $post_id, $meta_key );

			continue;
		}

		update_post_meta( $post_id, $meta_key, $value );
	}
}
add_action( 'save_post', 'intera_seo_save_meta', 10, 2 );
