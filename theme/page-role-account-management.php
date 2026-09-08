<?php
/**
 * Template Name: Role: Account management
 *
 * `/roles/account-management` — a campaign landing, not an export screen. Same
 * three bands as the other two `/roles/*` pages, drawn by the same partials:
 * who it is for and what it promises, what INTERA surfaces for that job, and
 * the ask. See `page-role-finance.php`, whose shape this is.
 *
 * What comes from WordPress:
 *
 * | slot            | source                                                  |
 * | --------------- | ------------------------------------------------------- |
 * | breadcrumb      | `intera_breadcrumbs()` (auto: Home / Roles / <title>)   |
 * | headline        | `role_account_hero__*` copy, falling back to the title  |
 * | lede            | `the_content()`, falling back to the copy field         |
 * | everything else | `role_account_*` copy on this page                      |
 * | both CTA links  | `intera_page_url()`                                     |
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( have_posts() ) {
	the_post();
}

$intera_request_url = (string) intera_page_url( 'contact-request' );
$intera_product_url = (string) intera_page_url( 'product' );

get_template_part(
	'template-parts/partials/role-landing-hero',
	null,
	array(
		'eyebrow'         => intera_copy( 'role_account_hero__account_management' ),
		'headline'        => intera_copy( 'role_account_hero__know_what_was_promised_and_what' ),
		'lede'            => intera_copy( 'role_account_hero__account_managers_are_often_responsible_for' ),
		'primary_label'   => intera_copy( 'role_account_hero__apply_for_private_beta' ),
		'primary_url'     => $intera_request_url,
		'secondary_label' => intera_copy( 'role_account_hero__see_how_intera_works' ),
		'secondary_url'   => $intera_product_url,
		'promise'         => array(
			array(
				'icon'  => 'clipboard-check',
				'label' => intera_copy( 'role_account_hero__know_what_was_delivered_as_agreed' ),
			),
			array(
				'icon'  => 'clock',
				'label' => intera_copy( 'role_account_hero__know_what_is_still_pending' ),
			),
			array(
				'icon'  => 'search',
				'label' => intera_copy( 'role_account_hero__know_the_evidence_behind_both' ),
			),
		),
	)
);

get_template_part(
	'template-parts/partials/role-landing-signals',
	null,
	array(
		'eyebrow'    => intera_copy( 'role_account_signals__what_intera_can_help_you_see' ),
		'heading'    => intera_copy( 'role_account_signals__go_into_the_customer_conversation_prepared' ),
		'items'      => array(
			array(
				'icon'  => 'package',
				'label' => intera_copy( 'role_account_signals__equipment_promised_but_not_yet_delivered' ),
			),
			array(
				'icon'  => 'plug',
				'label' => intera_copy( 'role_account_signals__services_agreed_but_not_yet_activated' ),
			),
			array(
				'icon'  => 'receipt',
				'label' => intera_copy( 'role_account_signals__discounts_or_prices_that_do_not' ),
			),
			array(
				'icon'  => 'clock',
				'label' => intera_copy( 'role_account_signals__deadlines_that_are_slipping' ),
			),
			array(
				'icon'  => 'alert-triangle',
				'label' => intera_copy( 'role_account_signals__open_support_issues_affecting_delivery' ),
			),
			array(
				'icon'  => 'repeat',
				'label' => intera_copy( 'role_account_signals__customer_commitments_that_still_need_follow' ),
			),
		),
		'close_body' => intera_copy( 'role_account_signals__instead_of_checking_several_systems_or' ),
		'close_line' => intera_copy( 'role_account_signals__know_what_happened_know_what_did' ),
	)
);

get_template_part(
	'template-parts/partials/role-landing-cta',
	null,
	array(
		'eyebrow' => intera_copy( 'role_account_cta__private_beta' ),
		'heading' => intera_copy( 'role_account_cta__bring_us_one_customer_commitment_you' ),
		'body'    => intera_copy( 'role_account_cta__we_will_start_with_one_role' ),
		'label'   => intera_copy( 'role_account_cta__apply_for_private_beta' ),
		'url'     => $intera_request_url,
	)
);

/*
 * The Method and the ask that closes every page of the site. A landing aimed at
 * one job says what INTERA watches for that job; this says how the work is
 * actually done, which is the question a reader has once they believe the
 * first part. Its words are the product page's, read from that page so the two
 * cannot drift.
 *
 * It is also what gives the page its last beat of rhythm: the band above is
 * dark and so is the footer, and this is the light one between them.
 */
get_template_part(
	'template-parts/partials/method-band',
	null,
	array(
		'source_id' => intera_page_id( 'product' ),
		'cta_url'   => $intera_request_url,
	)
);

get_footer();
