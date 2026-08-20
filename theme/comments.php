<?php
/**
 * Comments — the thread under an article, and the form that adds to it.
 *
 * The export never drew a comment thread, so nothing here is a port: it is
 * written from the design system, in the vocabulary the rest of the site
 * already uses. The nearest precedent in the mockups is the hairline list row
 * (`08-blog`, `13-docs-category`) with mono metadata, and that is what a comment
 * is built from — avatar, author, mono timestamp, prose body, reply link, one
 * hairline underneath. `assets/css/intera.css` §"Comments" already carries the
 * classes this file emits (`.comment-list`, `.comment-body`, `.comment-author`,
 * `.comment-metadata`, `.comment-content`, `.comment-reply-link`,
 * `.comment-respond`, `.comment-form`, `.comment-notes`, `.logged-in-as`), so
 * no styling is invented here that the stylesheet does not already own.
 *
 * The form is the contact form's twin: the same DS `field` + `input` /
 * `textarea` / `checkbox` components, the same required marker, the same `lg`
 * primary button — so a visitor who has filled in the request form recognises
 * this one. WordPress's default field markup is replaced wholesale rather than
 * restyled.
 *
 * `single.php` and `page.php` both open `<div class="intera-comments">` around
 * `comments_template()` and close it again, so this file starts at the heading
 * and adds no wrapper of its own.
 *
 * Every state is covered, not just the happy path:
 *
 * | state                | what renders                                        |
 * | -------------------- | --------------------------------------------------- |
 * | password-protected   | nothing — the body is not readable yet               |
 * | has comments         | mono count heading + the threaded list               |
 * | thread is paged      | `partials/pagination`, the same pager as the archive |
 * | awaiting moderation  | a line inside the row, in the site's voice           |
 * | pingback / trackback | a compact mono row, no avatar, no body               |
 * | comments closed      | the fact, then where to write instead                |
 * | sign-in required     | the fact, then the sign-in link                      |
 *
 * Threading depth is WordPress's own setting: `wp_list_comments()` is left to
 * read `thread_comments_depth`, and `comment_reply_link()` is handed the depth
 * it is at, so the reply link disappears on the last allowed level instead of
 * offering a reply that cannot be posted. `inc/enqueue.php` already enqueues
 * `comment-reply` when threading is on.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

// The article itself is still locked, so its thread is nobody's business yet.
if ( post_password_required() ) {
	return;
}

if ( ! function_exists( 'intera_comment_row' ) ) {
	/**
	 * One comment, in the design system's list-row shape.
	 *
	 * Opens the `<li>` and leaves it open: `Walker_Comment::end_el()` closes it
	 * after any children have been walked.
	 *
	 * @param WP_Comment $intera_comment The comment being rendered.
	 * @param array      $intera_args    The `wp_list_comments()` arguments.
	 * @param int        $intera_depth   Depth of this comment, 1-based.
	 * @return void
	 */
	function intera_comment_row( $intera_comment, $intera_args, $intera_depth ) {
		$intera_comment_id   = (int) $intera_comment->comment_ID;
		$intera_comment_type = (string) get_comment_type( $intera_comment_id );

		// A mention from another site: the link and when it arrived, nothing else.
		if ( 'pingback' === $intera_comment_type || 'trackback' === $intera_comment_type ) {
			?>
			<li id="comment-<?php echo esc_attr( (string) $intera_comment_id ); ?>" <?php comment_class( 'intera-comment-ping', $intera_comment_id ); ?>>
				<div class="comment-body" style="display: flex; flex-wrap: wrap; align-items: baseline; gap: 10px">
					<span class="comment-metadata" style="text-transform: uppercase; letter-spacing: 0.09em"><?php esc_html_e( 'Mentioned by', 'intera' ); ?></span>
					<span class="comment-author"><?php echo get_comment_author_link( $intera_comment_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core markup, author name escaped by WordPress. ?></span>
					<span class="comment-metadata"><?php echo esc_html( get_comment_date( 'Y-m-d', $intera_comment_id ) ); ?></span>
				</div>
			<?php
			return;
		}

		$intera_comment_pending = ( '0' === (string) $intera_comment->comment_approved );
		$intera_comment_avatar  = get_avatar(
			$intera_comment_id,
			isset( $intera_args['avatar_size'] ) ? (int) $intera_args['avatar_size'] : 40,
			'',
			'',
			array(
				'class'      => 'intera-comment-avatar',
				'extra_attr' => 'style="border-radius: var(--radius-round); display: block"',
			)
		);

		// The mono stamp the rest of the site uses, with the time added: a thread
		// is read in order, and Y-m-d alone cannot separate two comments in a day.
		$intera_comment_stamp = get_comment_date( 'Y-m-d H:i', $intera_comment_id );
		$intera_comment_iso   = get_comment_date( 'c', $intera_comment_id );
		$intera_comment_edit  = current_user_can( 'edit_comment', $intera_comment_id )
			? (string) get_edit_comment_link( $intera_comment_id )
			: '';
		?>
		<li id="comment-<?php echo esc_attr( (string) $intera_comment_id ); ?>" <?php comment_class( 'intera-comment', $intera_comment_id ); ?>>
			<article id="div-comment-<?php echo esc_attr( (string) $intera_comment_id ); ?>" class="comment-body">
				<div style="display: flex; gap: 12px; align-items: flex-start">
					<?php if ( '' !== $intera_comment_avatar ) : ?>
						<div style="flex: 0 0 auto; line-height: 0">
							<?php echo $intera_comment_avatar; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_avatar() output. ?>
						</div>
					<?php endif; ?>
					<div style="flex: 1 1 auto; min-width: 0">
						<div style="display: flex; flex-wrap: wrap; align-items: center; gap: 10px">
							<span class="comment-author"><?php echo get_comment_author_link( $intera_comment_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core markup, author name escaped by WordPress. ?></span>
							<?php
							$intera_comment_author_id = (int) $intera_comment->user_id;
							$intera_comment_post_id   = (int) $intera_comment->comment_post_ID;

							if ( $intera_comment_author_id > 0 && $intera_comment_author_id === (int) get_post_field( 'post_author', $intera_comment_post_id ) ) {
								get_template_part(
									'template-parts/components/badge',
									null,
									array(
										'text' => __( 'Author', 'intera' ),
										'tone' => 'info',
									)
								);
							}
							?>
							<span class="comment-metadata">
								<a href="<?php echo esc_url( get_comment_link( $intera_comment_id, $intera_args ) ); ?>">
									<time datetime="<?php echo esc_attr( $intera_comment_iso ); ?>"><?php echo esc_html( $intera_comment_stamp ); ?></time>
								</a>
							</span>
						</div>

						<?php if ( $intera_comment_pending ) : ?>
							<p class="comment-awaiting-moderation" style="font-size: var(--text-xs); color: var(--ink-500); margin-top: 8px; line-height: var(--leading-normal)"><?php esc_html_e( 'Held for review. It appears here once it has been read.', 'intera' ); ?></p>
						<?php endif; ?>

						<div class="comment-content intera-prose">
							<?php comment_text( $intera_comment_id ); ?>
						</div>

						<div style="display: flex; flex-wrap: wrap; align-items: center; gap: 14px; margin-top: 10px">
							<?php
							comment_reply_link(
								array_merge(
									(array) $intera_args,
									array(
										'add_below'  => 'div-comment',
										'depth'      => (int) $intera_depth,
										'max_depth'  => isset( $intera_args['max_depth'] ) ? (int) $intera_args['max_depth'] : 0,
										'before'     => '',
										'after'      => '',
										'reply_text' => __( 'Reply', 'intera' ),
										'login_text' => __( 'Sign in to reply', 'intera' ),
									)
								),
								$intera_comment_id
							);

							if ( '' !== $intera_comment_edit ) {
								echo '<a class="comment-edit-link" href="' . esc_url( $intera_comment_edit ) . '" style="font-size: var(--text-sm)">' . esc_html__( 'Edit', 'intera' ) . '</a>';
							}
							?>
						</div>
					</div>
				</div>
			</article>
		<?php
	}
}

$intera_comments_count = (int) get_comments_number();

if ( have_comments() ) :
	?>
	<h2 class="comments-title" style="font-size: var(--text-xl); font-weight: 600; letter-spacing: -0.01em; color: var(--ink-900)">
		<?php
		printf(
			/* translators: %s: number of comments on the article, in mono. */
			esc_html( _n( '%s comment', '%s comments', $intera_comments_count, 'intera' ) ),
			'<span style="font-family: var(--font-mono)">' . esc_html( number_format_i18n( $intera_comments_count ) ) . '</span>'
		);
		?>
	</h2>

	<ol class="comment-list" style="margin-top: 18px">
		<?php
		wp_list_comments(
			array(
				'style'       => 'ol',
				'avatar_size' => 40,
				'short_ping'  => true,
				'callback'    => 'intera_comment_row',
			)
		);
		?>
	</ol>

	<?php
	/*
	 * A long thread pages exactly like an archive does: same nav, same mono
	 * "Page 1 of 3", same disabled pair at the ends. The base is built the way
	 * `paginate_comments_links()` builds it, so `comment-page-2` and `?cpage=2`
	 * both work, pretty permalinks or not.
	 */
	$intera_comment_pages = (int) get_comment_pages_count();

	if ( $intera_comment_pages > 1 && get_option( 'page_comments' ) ) {
		$intera_comment_page = (int) get_query_var( 'cpage' );

		if ( $intera_comment_page < 1 ) {
			$intera_comment_page = 1;
		}

		$intera_comment_page = max( 1, min( $intera_comment_page, $intera_comment_pages ) );
		$intera_comment_big  = 999999999;

		get_template_part(
			'template-parts/partials/pagination',
			null,
			array(
				'total'    => $intera_comment_pages,
				'current'  => $intera_comment_page,
				'label'    => __( 'Comment pages', 'intera' ),
				'base'     => str_replace( (string) $intera_comment_big, '%#%', esc_url( get_comments_pagenum_link( $intera_comment_big, $intera_comment_pages ) ) ),
				'format'   => '',
				'prev_url' => $intera_comment_page > 1 ? (string) get_comments_pagenum_link( $intera_comment_page - 1, $intera_comment_pages ) : '',
				'next_url' => $intera_comment_page < $intera_comment_pages ? (string) get_comments_pagenum_link( $intera_comment_page + 1, $intera_comment_pages ) : '',
				'style'    => 'margin-top: 20px',
			)
		);
	}

endif;

/*
 * Closed, with a thread already there: say so, then say where to write instead —
 * the empty-state rule ("state the fact, then the next step"). The target is the
 * contact-request page; when no page carries that template the sentence stops
 * after the fact rather than pointing nowhere.
 */
if ( ! comments_open() && $intera_comments_count > 0 && post_type_supports( get_post_type(), 'comments' ) ) :
	$intera_comments_ask = (string) intera_page_url( 'contact-request' );
	?>
	<p class="no-comments" style="font-size: var(--text-sm); color: var(--ink-500); margin-top: 22px; padding-top: 18px; border-top: 1px solid var(--border-hairline); line-height: 1.6">
		<?php
		if ( '' !== $intera_comments_ask ) {
			printf(
				/* translators: %s: link to the contact-request page, reading "bring us the problem". */
				esc_html__( 'This thread is closed. If you have something to add, %s.', 'intera' ),
				'<a href="' . esc_url( $intera_comments_ask ) . '">' . esc_html__( 'bring us the problem', 'intera' ) . '</a>'
			);
		} else {
			esc_html_e( 'This thread is closed.', 'intera' );
		}
		?>
	</p>
	<?php
endif;

/*
 * The form. Every control is the same DS component the contact-request page
 * uses, so the two forms are one form language: `field` carries the label, the
 * required marker and the hint; `input` / `textarea` / `checkbox` carry the
 * control; the submit is a `lg` primary Button.
 */
$intera_commenter    = wp_get_current_commenter();
$intera_name_email   = (bool) get_option( 'require_name_email' );
$intera_comment_user = wp_get_current_user();

/**
 * One DS control wrapped in a DS Field, returned as markup.
 *
 * @param string $intera_component  Component slug under template-parts/components.
 * @param array  $intera_control    Arguments for the control component.
 * @param array  $intera_field      Arguments for the field component.
 * @return string
 */
$intera_comment_field = static function ( $intera_component, array $intera_control, array $intera_field ) {
	ob_start();
	get_template_part( 'template-parts/components/' . $intera_component, null, $intera_control );
	$intera_field['content'] = (string) ob_get_clean();

	ob_start();
	get_template_part( 'template-parts/components/field', null, $intera_field );

	return (string) ob_get_clean();
};

$intera_comment_fields = array(
	'author' => $intera_comment_field(
		'input',
		array(
			'id'          => 'author',
			'name'        => 'author',
			'value'       => (string) $intera_commenter['comment_author'],
			'placeholder' => __( 'Anna Kovalenko', 'intera' ),
			'required'    => $intera_name_email,
			'attrs'       => array(
				'autocomplete' => 'name',
				'maxlength'    => '245',
			),
		),
		array(
			'label'    => __( 'Name', 'intera' ),
			'for'      => 'author',
			'required' => $intera_name_email,
			'class'    => 'comment-form-author',
		)
	),
	'email'  => $intera_comment_field(
		'input',
		array(
			'type'        => 'email',
			'id'          => 'email',
			'name'        => 'email',
			'value'       => (string) $intera_commenter['comment_author_email'],
			'placeholder' => __( 'a.kovalenko@company.com', 'intera' ),
			'required'    => $intera_name_email,
			'attrs'       => array(
				'autocomplete' => 'email',
				'maxlength'    => '100',
			),
		),
		array(
			'label'    => __( 'Email', 'intera' ),
			'for'      => 'email',
			'required' => $intera_name_email,
			'hint'     => __( 'Not published. Used only if we need to answer you directly.', 'intera' ),
			'class'    => 'comment-form-email',
		)
	),
	'url'    => $intera_comment_field(
		'input',
		array(
			'type'        => 'url',
			'id'          => 'url',
			'name'        => 'url',
			'value'       => (string) $intera_commenter['comment_author_url'],
			'placeholder' => __( 'company.com', 'intera' ),
			'attrs'       => array(
				'autocomplete' => 'url',
				'maxlength'    => '200',
			),
		),
		array(
			'label' => __( 'Website', 'intera' ),
			'for'   => 'url',
			'class' => 'comment-form-url',
		)
	),
);

// The cookie box only exists when WordPress is actually setting those cookies.
if ( has_action( 'set_comment_cookies', 'wp_set_comment_cookies' ) && get_option( 'show_comments_cookies_opt_in' ) ) {
	ob_start();
	get_template_part(
		'template-parts/components/checkbox',
		null,
		array(
			'id'      => 'wp-comment-cookies-consent',
			'name'    => 'wp-comment-cookies-consent',
			'value'   => 'yes',
			'label'   => __( 'Keep my name and email in this browser for next time.', 'intera' ),
			'checked' => ! empty( $intera_commenter['comment_author_email'] ),
		)
	);
	$intera_comment_fields['cookies'] = '<div class="comment-form-cookies-consent">' . (string) ob_get_clean() . '</div>';
}

$intera_comment_body = $intera_comment_field(
	'textarea',
	array(
		'id'          => 'comment',
		'name'        => 'comment',
		'rows'        => 6,
		'placeholder' => __( 'We reconcile mediation against billing by hand every month. How does INTERA handle a source that reports late?', 'intera' ),
		'required'    => true,
		'attrs'       => array( 'maxlength' => '65525' ),
	),
	array(
		'label'    => __( 'Your comment', 'intera' ),
		'for'      => 'comment',
		'required' => true,
		'hint'     => __( 'What you would add, correct or ask. Specifics are more useful than agreement.', 'intera' ),
		'class'    => 'comment-form-comment',
	)
);

/*
 * `comment_form()` runs the submit button through `sprintf()`, so a literal `%`
 * anywhere in the markup would be read as a placeholder: it is doubled here and
 * `sprintf()` puts it back.
 */
ob_start();
get_template_part(
	'template-parts/components/button',
	null,
	array(
		'label' => __( 'Send comment', 'intera' ),
		'size'  => 'lg',
		'type'  => 'submit',
		'attrs' => array(
			'id'   => 'submit',
			'name' => 'submit',
		),
	)
);
$intera_comment_submit = str_replace( '%', '%%', (string) ob_get_clean() );

$intera_comment_title_style = 'font-size: var(--text-xl); font-weight: 600; letter-spacing: -0.01em; color: var(--ink-900); margin-bottom: 14px';

comment_form(
	array(
		'fields'               => $intera_comment_fields,
		'comment_field'        => $intera_comment_body,
		'class_form'           => 'comment-form',
		'title_reply'          => __( 'Add a comment', 'intera' ),
		/* translators: %s: name of the person being replied to. */
		'title_reply_to'       => __( 'Reply to %s', 'intera' ),
		'title_reply_before'   => '<h2 id="reply-title" class="comment-reply-title" style="' . esc_attr( $intera_comment_title_style ) . '">',
		'title_reply_after'    => '</h2>',
		'cancel_reply_before'  => ' <small style="font-size: var(--text-sm); font-weight: var(--weight-regular); margin-left: 8px">',
		'cancel_reply_after'   => '</small>',
		'cancel_reply_link'    => __( 'Cancel reply', 'intera' ),
		'label_submit'         => __( 'Send comment', 'intera' ),
		'submit_button'        => $intera_comment_submit,
		'submit_field'         => '<div class="form-submit" style="display: flex; flex-wrap: wrap; align-items: center; gap: 16px">%1$s %2$s</div>',
		'comment_notes_before' => '<p class="comment-notes">' . esc_html__( 'Comments are read before they appear. Your email is never published.', 'intera' ) . '</p>',
		'comment_notes_after'  => '',
		'must_log_in'          => '<p class="must-log-in">' . sprintf(
			/* translators: %s: link to the sign-in screen, reading "Sign in". */
			esc_html__( 'Comments are open to signed-in readers. %s to add yours.', 'intera' ),
			'<a href="' . esc_url( wp_login_url( (string) get_permalink() ) ) . '">' . esc_html__( 'Sign in', 'intera' ) . '</a>'
		) . '</p>',
		'logged_in_as'         => '<p class="logged-in-as">' . sprintf(
			/* translators: 1: link to the profile screen, carrying the display name, 2: link that signs the reader out. */
			esc_html__( 'Signed in as %1$s. %2$s', 'intera' ),
			'<a href="' . esc_url( get_edit_profile_url( $intera_comment_user->ID ) ) . '">' . esc_html( $intera_comment_user->display_name ) . '</a>',
			'<a href="' . esc_url( wp_logout_url( (string) get_permalink() ) ) . '">' . esc_html__( 'Sign out', 'intera' ) . '</a>'
		) . '</p>',
	)
);
