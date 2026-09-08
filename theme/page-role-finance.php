<?php
/**
 * Template Name: Role: Finance
 *
 * `/roles/finance` — a campaign landing, not an export screen. Traffic arrives
 * here from an advert aimed at one job, so the page is three bands and no more:
 * who it is for and what it promises, what INTERA surfaces for that job, and
 * the ask.
 *
 * All three bands are partials shared with the other two `/roles/*` pages —
 * `role-landing-hero`, `role-landing-signals`, `role-landing-cta` — so the
 * three landings cannot drift apart in layout while their words stay their own.
 * Everything this template holds is which words go where.
 *
 * What comes from WordPress:
 *
 * | slot            | source                                                  |
 * | --------------- | ------------------------------------------------------- |
 * | breadcrumb      | `intera_breadcrumbs()` (auto: Home / Roles / <title>)   |
 * | headline        | `role_finance_hero__*` copy, falling back to the title  |
 * | lede            | `the_content()`, falling back to the copy field         |
 * | everything else | `role_finance_*` copy on this page                      |
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
		'eyebrow'         => intera_copy( 'role_finance_hero__finance_management' ),
		'headline'        => intera_copy( 'role_finance_hero__know_where_the_numbers_do_not' ),
		'lede'            => intera_copy( 'role_finance_hero__finance_teams_often_spend_time_checking' ),
		'primary_label'   => intera_copy( 'role_finance_hero__apply_for_private_beta' ),
		'primary_url'     => $intera_request_url,
		'secondary_label' => intera_copy( 'role_finance_hero__see_how_intera_works' ),
		'secondary_url'   => $intera_product_url,
		'promise'         => array(
			array(
				'icon'  => 'scale',
				'label' => intera_copy( 'role_finance_hero__know_where_something_does_not_match' ),
			),
			array(
				'icon'  => 'trending-up',
				'label' => intera_copy( 'role_finance_hero__know_how_significant_it_is' ),
			),
			array(
				'icon'  => 'search',
				'label' => intera_copy( 'role_finance_hero__know_which_records_are_the_evidence' ),
			),
		),
	)
);

get_template_part(
	'template-parts/partials/role-landing-signals',
	null,
	array(
		'eyebrow'       => intera_copy( 'role_finance_signals__what_intera_can_help_you_see' ),
		'heading'       => intera_copy( 'role_finance_signals__the_few_items_that_need_investigation' ),
		'items'         => array(
			array(
				'icon'  => 'receipt',
				'label' => intera_copy( 'role_finance_signals__invoices_issued_but_not_matched_to' ),
			),
			array(
				'icon'  => 'circle-dollar-sign',
				'label' => intera_copy( 'role_finance_signals__payments_received_but_not_correctly_reflected' ),
			),
			array(
				'icon'  => 'boxes',
				'label' => intera_copy( 'role_finance_signals__supplier_costs_not_yet_rebilled' ),
			),
			array(
				'icon'  => 'contact',
				'label' => intera_copy( 'role_finance_signals__sales_commitments_that_do_not_match' ),
			),
			array(
				'icon'  => 'database',
				'label' => intera_copy( 'role_finance_signals__missing_delayed_or_inconsistent_financial_records' ),
			),
			array(
				'icon'  => 'alert-triangle',
				'label' => intera_copy( 'role_finance_signals__unusual_changes_that_deserve_a_closer' ),
			),
		),
		'close_heading' => intera_copy( 'role_finance_signals__from_checking_everything_to_checking_exceptions' ),
		'close_body'    => intera_copy( 'role_finance_signals__instead_of_repeatedly_reconciling_large_lists' ),
		'close_line'    => intera_copy( 'role_finance_signals__less_manual_checking_faster_investigation_better' ),
	)
);

get_template_part(
	'template-parts/partials/role-landing-cta',
	null,
	array(
		'eyebrow' => intera_copy( 'role_finance_cta__private_beta' ),
		'heading' => intera_copy( 'role_finance_cta__bring_us_one_reconciliation_you_repeat' ),
		'body'    => intera_copy( 'role_finance_cta__we_will_start_with_one_role' ),
		'label'   => intera_copy( 'role_finance_cta__apply_for_private_beta' ),
		'url'     => $intera_request_url,
	)
);

get_footer();
