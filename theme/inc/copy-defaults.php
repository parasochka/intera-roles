<?php
/**
 * The design's own words, as registered defaults.
 *
 * GENERATED from the ported templates — every entry is the string the handoff
 * screen carries, lifted verbatim out of `front-page.php`, `page-product.php`,
 * `page-pricing.php` and `page-contacts.php` when their copy was moved into
 * WordPress. Nothing here is invented and nothing here is layout: it is the run
 * of text an editor is allowed to change, keyed by where it sits on the page.
 *
 * The grouping is the design's own — each `$sections` key is one
 * `data-screen-label` from the export — so the meta box on a page reads in the
 * same order as the page itself.
 *
 * `intera_copy()` (inc/copy.php) reads a page's saved value and falls back to
 * the default here, so a field an editor never touched still renders the
 * handoff exactly. Editing a string in this file changes the fallback, not a
 * page that has already been saved.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

/**
 * Every editable run of text on the four designed pages.
 *
 * @return array<string,array{label:string,template:string,sections:array<string,array{label:string,fields:array<string,string>}>}>
 */
function intera_copy_schema() {
	return array(
		'home' => array(
			'label'    => __( 'Home page', 'intera' ),
			'template' => 'front-page.php',
			'sections' => array(
				'hero' => array(
					'label'  => __( 'Hero', 'intera' ),
					'fields' => array(
						'home_hero__your_business_clearly' => __( 'Your business, clearly', 'intera' ),
						'home_hero__see_what_needs_attention_before_someone' => __( 'See what needs attention. Before someone has to ask.', 'intera' ),
						'home_hero__intera_connects_the_systems_your_teams' => __( 'INTERA connects the systems your teams already use and gives each role a clear view of what matters — changes, risks, inconsistencies and trends.', 'intera' ),
						'home_hero__get_early_access' => __( 'Get Early Access', 'intera' ),
						'home_hero__see_how_intera_works' => __( 'See how INTERA works', 'intera' ),
						'home_hero__no_migration' => __( 'No migration', 'intera' ),
						'home_hero__read_only_access' => __( 'Read-only access', 'intera' ),
						'home_hero__start_with_one_role' => __( 'Start with one role', 'intera' ),
						'home_hero__reads_from' => __( 'Reads from', 'intera' ),
						'home_hero__internal_tools' => __( 'Internal tools', 'intera' ),
						'home_hero__fleet_health_overview_shipmanagement' => __( 'Fleet Health Overview · Shipmanagement', 'intera' ),
						'home_hero__critical_maintenance_task_overdue' => __( 'Critical maintenance task overdue', 'intera' ),
						'home_hero__5_days' => __( '5 days', 'intera' ),
						'home_hero__before_impact' => __( 'before impact', 'intera' ),
						'home_hero__4th_occurrence_always_after_a_delayed' => __( '4th occurrence — always after a delayed spare', 'intera' ),
					),
				),
				'problem' => array(
					'label'  => __( 'Problem', 'intera' ),
					'fields' => array(
						'home_problem__the_problem' => __( 'The problem', 'intera' ),
						'home_problem__this_will_feel_familiar' => __( 'This will feel familiar', 'intera' ),
						'home_problem__your_business_runs_across_several_systems' => __( 'Your business runs across several systems. Finance sees one part. Operations sees another.', 'intera' ),
						'home_problem__crm_billing_erp_spreadsheets_and_internal' => __( 'CRM, billing, ERP, spreadsheets and internal tools each contain pieces of the picture. Problems often become visible only when someone connects those pieces manually.', 'intera' ),
						'home_problem__teams_spend_time_checking_reconciling_explaining' => __( 'Teams spend time checking, reconciling, explaining and preparing information that already exists somewhere in the business.', 'intera' ),
						'home_problem__intera_makes_that_operating_picture_continuously' => __( 'INTERA makes that operating picture continuously visible.', 'intera' ),
						'home_problem__erp' => __( 'ERP', 'intera' ),
						'home_problem__erp_orders' => 'erp.orders',
						'home_problem__crm' => __( 'CRM', 'intera' ),
						'home_problem__crm_accounts' => 'crm.accounts',
						'home_problem__billing' => __( 'Billing', 'intera' ),
						'home_problem__billing_invoices' => 'billing.invoices',
						'home_problem__spreadsheets' => __( 'Spreadsheets', 'intera' ),
						'home_problem__ops_checks_xlsx' => 'ops_checks.xlsx',
						'home_problem__internal_tools' => __( 'Internal tools', 'intera' ),
						'home_problem__provisioning_api' => 'provisioning.api',
						'home_problem__pieces_of_the_same_picture_checked' => __( 'Pieces of the same picture, checked by hand.', 'intera' ),
					),
				),
				'how_it_works' => array(
					'label'  => __( 'How it works', 'intera' ),
					'fields' => array(
						'home_how_it_works__how_it_works' => __( 'How it works', 'intera' ),
						'home_how_it_works__get_full_visibility_without_changing_how' => __( 'Get full visibility without changing how your company operates', 'intera' ),
						'home_how_it_works__connect_your_existing_systems' => __( 'Connect your existing systems', 'intera' ),
						'home_how_it_works__intera_connects_to_finance_operations_crm' => __( 'INTERA connects to finance, operations, CRM, billing, ERP, Excel and internal systems without replacing them.', 'intera' ),
						'home_how_it_works__intera_understands_what_matters' => __( 'INTERA understands what matters', 'intera' ),
						'home_how_it_works__it_applies_your_business_logic_and' => __( 'It applies business logic and watches changes, risks and inconsistencies.', 'intera' ),
						'home_how_it_works__see_what_needs_attention' => __( 'See what needs attention', 'intera' ),
						'home_how_it_works__managers_immediately_see_what_changed_what' => __( 'Managers immediately see what changed, what requires action and where to investigate.', 'intera' ),
						'home_how_it_works__intera_doesn_t_replace_your_team' => __( 'INTERA doesn\'t replace your team — it removes unnecessary manual checking and reporting between systems and people.', 'intera' ),
					),
				),
				'champion' => array(
					'label'  => __( 'Champion', 'intera' ),
					'fields' => array(
						'home_champion__for_the_manager_who_owns_the' => __( 'For the manager who owns the area', 'intera' ),
						'home_champion__make_your_area_easier_to_run' => __( 'Make your area easier to run', 'intera' ),
						'home_champion__intera_doesn_t_just_give_management' => __( 'INTERA doesn\'t just give management more visibility. It helps you stay on top of the part of the business you\'re responsible for.', 'intera' ),
						'home_champion__know_before_you_re_asked' => __( 'Know before you\'re asked', 'intera' ),
						'home_champion__see_problems_and_unusual_changes_before' => __( 'See problems and unusual changes before they become questions or escalations.', 'intera' ),
						'home_champion__spend_less_time_proving_what_s' => __( 'Spend less time proving what\'s happening', 'intera' ),
						'home_champion__reduce_repetitive_reporting_manual_checks_and' => __( 'Reduce repetitive reporting, manual checks and status updates.', 'intera' ),
						'home_champion__bring_problems_with_answers' => __( 'Bring problems with answers', 'intera' ),
						'home_champion__see_the_supporting_data_and_understand' => __( 'See the supporting data and understand what requires action.', 'intera' ),
						'home_champion__show_that_your_area_is_under' => __( 'Show that your area is under control', 'intera' ),
						'home_champion__give_management_clear_and_consistent_visibility' => __( 'Give management clear and consistent visibility without preparing another spreadsheet.', 'intera' ),
						'home_champion__make_improvements_that_last' => __( 'Make improvements that last', 'intera' ),
						'home_champion__turn_the_checks_knowledge_and_working' => __( 'Turn the checks, knowledge and working practices your team already uses into something repeatable and visible across the organization.', 'intera' ),
						'home_champion__less_chasing' => __( 'Less chasing.', 'intera' ),
						'home_champion__fewer_surprises' => __( 'Fewer surprises.', 'intera' ),
						'home_champion__more_confidence_in_the_part_of' => __( 'More confidence in the part of the business you own.', 'intera' ),
					),
				),
				'in_action' => array(
					'label'  => __( 'In action', 'intera' ),
					'fields' => array(
						'home_in_action__intera_in_action' => __( 'INTERA in action', 'intera' ),
						'home_in_action__don_t_just_watch_the_business' => __( 'Don\'t just watch the business. Catch what matters.', 'intera' ),
						'home_in_action__something_important_changed' => __( 'Something important changed.', 'intera' ),
						'home_in_action__things_that_should_agree_don_t' => __( 'Things that should agree — don\'t.', 'intera' ),
						'home_in_action__something_requires_attention_and_action' => __( 'Something requires attention and action.', 'intera' ),
						'home_in_action__understand_what_keeps_happening_and_under' => __( 'Understand what keeps happening, and under which conditions.', 'intera' ),
						'home_in_action__from_something_looks_wrong_to_we' => __( 'From "something looks wrong" to "we know what is happening, why it matters, and what to watch next."', 'intera' ),
						'home_in_action__every_item_carries_the_reason_it' => __( 'Every item carries the reason it is on the list: what changed, who owns it, when it becomes a problem, and what keeps happening around it.', 'intera' ),
						'home_in_action__attention_queue_what_to_work_on' => __( 'Attention Queue · what to work on first', 'intera' ),
					),
				),
				'roles' => array(
					'label'  => __( 'Roles', 'intera' ),
					'fields' => array(
						'home_roles__intera_roles' => __( 'INTERA Roles', 'intera' ),
						'home_roles__pre_built_visibility_for_the_parts' => __( 'Pre-built visibility for the parts of your business that matter most', 'intera' ),
						'home_roles__roles_are_ready_made_business_modules' => __( 'INTERA Roles are ready-made business modules designed around real responsibilities — finance, operations, revenue, and more. Each role comes with predefined metrics, logic, and automatic issue detection, so you can see what\'s happening without building anything from scratch.', 'intera' ),
						'home_roles__different_responsibilities_one_operating_picture' => __( 'Different responsibilities. One operating picture.', 'intera' ),
						'home_roles__see_all_roles' => __( 'See all Roles', 'intera' ),
					),
				),
				'working_with_it' => array(
					'label'  => __( 'Working with IT', 'intera' ),
					'fields' => array(
						'home_working_with_it__working_with_existing_it' => __( 'Working with existing IT', 'intera' ),
						'home_working_with_it__your_systems_stay_intera_makes_them' => __( 'Your systems stay. INTERA makes them more useful.', 'intera' ),
						'home_working_with_it__erp_crm_billing_and_others_remain' => __( 'ERP, CRM, billing and others remain as your systems of record.', 'intera' ),
						'home_working_with_it__intera_connects_to_them_never_replacing' => __( 'INTERA connects to them, never replacing anything.', 'intera' ),
						'home_working_with_it__it_is_responsible_for_access_to' => __( 'IT is responsible for access to systems and data.', 'intera' ),
						'home_working_with_it__business_decides_which_metrics_events_incidents' => __( 'Business decides which Metrics, Events, Incidents, Reconciliations and Patterns are important.', 'intera' ),
						'home_working_with_it__no_company_wide_transformation_project' => __( 'No company-wide transformation project.', 'intera' ),
						'home_working_with_it__business_teams_know_what_they_need' => __( 'Business teams know what they need to control. IT knows how the systems work. INTERA gives them a practical place to meet.', 'intera' ),
						'home_working_with_it__dependencies_vendors_parts_external_commitments' => __( 'Dependencies · vendors, parts, external commitments', 'intera' ),
					),
				),
				'start_small' => array(
					'label'  => __( 'Start small', 'intera' ),
					'fields' => array(
						'home_start_small__start_small' => __( 'Start small', 'intera' ),
						'home_start_small__start_with_one_real_problem' => __( 'Start with one real problem', 'intera' ),
						'home_start_small__do_not_start_by_implementing_intera' => __( 'Do not start by implementing INTERA in your whole company. One role. One operational problem. One working result.', 'intera' ),
						'home_start_small__bring_us_a_real_problem' => __( 'Bring us a real problem', 'intera' ),
						'home_start_small__billing_and_usage_do_not_correspond' => __( 'Billing and usage do not correspond.', 'intera' ),
						'home_start_small__the_problem_is_detected_too_late' => __( 'The problem is detected too late.', 'intera' ),
						'home_start_small__the_same_exceptions_are_constantly_checked' => __( 'The same exceptions are constantly checked by hand.', 'intera' ),
						'home_start_small__a_manager_gathers_the_same_data' => __( 'A manager gathers data from several different systems.', 'intera' ),
					),
				),
				'pricing' => array(
					'label'  => __( 'Pricing', 'intera' ),
					'fields' => array(
						'home_pricing__pricing' => __( 'Pricing', 'intera' ),
						'home_pricing__start_free_pay_when_intera_is' => __( 'Start free. Pay when INTERA is doing real work.', 'intera' ),
					),
				),
				'early_adopter' => array(
					'label'  => __( 'Early Adopter', 'intera' ),
					'fields' => array(
						'home_early_adopter__early_adopter_offer' => __( 'Early Adopter offer', 'intera' ),
						'home_early_adopter__help_shape_intera_around_a_real' => __( 'Help shape INTERA around a real operation', 'intera' ),
						'home_early_adopter__we_are_looking_for_a_small' => __( 'We are looking for a small number of companies and managers who are ready to use INTERA to solve their real operational tasks during the beta testing stage.', 'intera' ),
						'home_early_adopter__early_adopters_receive' => __( 'Early Adopters receive', 'intera' ),
						'home_early_adopter__intera_free_for_the_first_12' => __( 'INTERA free for the first 12 months', 'intera' ),
						'home_early_adopter__custom_onboarding' => __( 'Custom onboarding', 'intera' ),
						'home_early_adopter__direct_contact_with_the_intera_team' => __( 'Direct contact with the INTERA team', 'intera' ),
						'home_early_adopter__priority_support' => __( 'Priority support', 'intera' ),
						'home_early_adopter__influence_over_product_development' => __( 'Influence over product development', 'intera' ),
						'home_early_adopter__help_setting_up_your_first_real' => __( 'Help setting up your first real use case', 'intera' ),
						'home_early_adopter__we_expect_in_return' => __( 'We expect in return', 'intera' ),
						'home_early_adopter__a_real_business_case' => __( 'A real business case', 'intera' ),
						'home_early_adopter__feedback' => __( 'Feedback', 'intera' ),
						'home_early_adopter__readiness_to_work_together_and_verify' => __( 'Readiness to work together and verify our solutions', 'intera' ),
						'home_early_adopter__i_have_a_problem_intera_could' => __( 'I have a problem INTERA could solve', 'intera' ),
					),
				),
				'partners' => array(
					'label'  => __( 'Partners', 'intera' ),
					'fields' => array(
						'home_partners__partners_and_resellers' => __( 'Partners and resellers', 'intera' ),
						'home_partners__turn_your_industry_knowledge_into_repeatable' => __( 'Turn your industry knowledge into repeatable solutions', 'intera' ),
						'home_partners__for_systems_integrators_and_consultants_who' => __( 'For systems integrators and consultants who already know their customers\' real problems. INTERA lets you turn your industry expertise into:', 'intera' ),
						'home_partners__solve_once_adapt_deploy_again' => __( 'Solve once. Adapt. Deploy again.', 'intera' ),
						'home_partners__become_an_intera_partner' => __( 'Become an INTERA partner', 'intera' ),
						'home_partners__roles' => __( 'Roles', 'intera' ),
						'home_partners__reconciliations' => __( 'Reconciliations', 'intera' ),
						'home_partners__business_logic' => __( 'Business logic', 'intera' ),
						'home_partners__patterns' => __( 'Patterns', 'intera' ),
						'home_partners__integrations' => __( 'Integrations', 'intera' ),
						'home_partners__market_packages' => __( 'Market packages', 'intera' ),
					),
				),
			),
		),
		'product' => array(
			'label'    => __( 'Product page', 'intera' ),
			'template' => 'page-product.php',
			'sections' => array(
				'header' => array(
					'label'  => __( 'Header', 'intera' ),
					'fields' => array(
						'product_headline' => __( 'One operating picture across the systems you already run', 'intera' ),
					),
				),
				'page' => array(
					'label'  => __( 'Page', 'intera' ),
					'fields' => array(
						'product_page__get_early_access' => __( 'Get Early Access', 'intera' ),
						'product_page__something_important_changed' => __( 'Something important changed.', 'intera' ),
						'product_page__things_that_should_agree_don_t' => __( 'Things that should agree — don\'t.', 'intera' ),
						'product_page__something_requires_attention_and_action' => __( 'Something requires attention and action.', 'intera' ),
						'product_page__understand_what_keeps_happening_and_under' => __( 'Understand what keeps happening, and under which conditions.', 'intera' ),
					),
				),
				'product_header' => array(
					'label'  => __( 'Product header', 'intera' ),
					'fields' => array(
						'product_product_header__read_the_docs' => __( 'Read the docs', 'intera' ),
						'product_product_header__operations_oversight' => __( 'Operations Oversight', 'intera' ),
						'product_product_header__live' => __( 'live', 'intera' ),
						'product_product_header__open_incidents' => __( 'Open incidents', 'intera' ),
						'product_product_header__7' => array(
							'default' => '7',
							'label'   => __( 'Open incidents — figure', 'intera' ),
						),
						'product_product_header__2' => array(
							'default' => '+2',
							'label'   => __( 'Open incidents — change', 'intera' ),
						),
						'product_product_header__unreconciled' => __( 'Unreconciled', 'intera' ),
						'product_product_header__4812' => array(
							'default' => '4,812',
							'label'   => __( 'Unreconciled — figure', 'intera' ),
						),
						'product_product_header__311' => array(
							'default' => '-311',
							'label'   => __( 'Unreconciled — change', 'intera' ),
						),
						'product_product_header__latest' => __( 'Latest', 'intera' ),
						'product_product_header__billing_vs_erp_118_invoices_differ' => __( 'Billing vs ERP: 118 invoices differ by more than 0.5%', 'intera' ),
						'product_product_header__no_data_received_from_wms_since' => __( 'No data received from WMS since 03:20 UTC', 'intera' ),
						'product_product_header__readiness_drops_every_monday_after_the' => __( 'Readiness drops every Monday after the weekend import', 'intera' ),
					),
				),
				'what_intera_watches' => array(
					'label'  => __( 'What INTERA watches', 'intera' ),
					'fields' => array(
						'product_what_intera_watches__the_chain' => __( 'The chain', 'intera' ),
						'product_what_intera_watches__event_reconciliation_incident_pattern' => __( 'Event, Reconciliation, Incident, Pattern', 'intera' ),
						'product_what_intera_watches__four_object_types_in_a_fixed' => __( 'Four object types, in a fixed order. Everything a role sees is one of them.', 'intera' ),
						'product_what_intera_watches__a_watched_metric_moved' => __( 'A watched Metric moved', 'intera' ),
						'product_what_intera_watches__thresholds_trends_and_data_status_on' => __( 'Thresholds, trends and data status on the numbers a role owns. The event carries the value, the source and the time.', 'intera' ),
						'product_what_intera_watches__metrics_trend_metrics_threshold' => __( 'metrics.trend · metrics.threshold', 'intera' ),
						'product_what_intera_watches__two_systems_disagree' => __( 'Two systems disagree', 'intera' ),
						'product_what_intera_watches__continuous_comparison_between_systems_periods_an' => __( 'Continuous comparison between systems, periods and business conditions — with the differing records listed, not summarised away.', 'intera' ),
						'product_what_intera_watches__usage_billing_orders_invoices' => __( 'usage ↔ billing · orders ↔ invoices', 'intera' ),
						'product_what_intera_watches__someone_has_to_act' => __( 'Someone has to act', 'intera' ),
						'product_what_intera_watches__a_tracked_item_with_an_owner' => __( 'A tracked item with an owner, a priority, an expected time to impact and the evidence behind it.', 'intera' ),
						'product_what_intera_watches__p0_impact_in_2_days_owner' => __( 'P0 · impact in 2 days · owner set', 'intera' ),
						'product_what_intera_watches__it_keeps_happening' => __( 'It keeps happening', 'intera' ),
						'product_what_intera_watches__recurring_combinations_of_conditions_behind_inci' => __( 'Recurring combinations of conditions behind incidents and changes — and the option to keep watching them.', 'intera' ),
						'product_what_intera_watches__4th_occurrence_same_precondition' => __( '4th occurrence · same precondition', 'intera' ),
					),
				),
				'pattern_studio' => array(
					'label'  => __( 'Pattern Studio', 'intera' ),
					'fields' => array(
						'product_pattern_studio__pattern_studio' => __( 'Pattern Studio', 'intera' ),
						'product_pattern_studio__understand_what_keeps_happening_and_under' => __( 'Understand what keeps happening, and under which conditions', 'intera' ),
						'product_pattern_studio__look_back_at_what_preceded_an' => __( 'Look back at what preceded an incident, find the combination that repeats, then turn it into something INTERA keeps watching. What one manager learns becomes a check the whole company keeps.', 'intera' ),
						'product_pattern_studio__if' => __( 'if', 'intera' ),
						'product_pattern_studio__a_spare_part_delivery_slips_more' => __( 'a spare part delivery slips more than 5 days', 'intera' ),
						'product_pattern_studio__and' => __( 'and', 'intera' ),
						'product_pattern_studio__the_vessel_already_carries_overdue_critical' => __( 'the vessel already carries overdue critical maintenance', 'intera' ),
						'product_pattern_studio__then' => __( 'then', 'intera' ),
						'product_pattern_studio__readiness_drops_below_plan_within_3' => __( 'readiness drops below plan within 3 weeks — 4 of the last 5 times', 'intera' ),
						'product_pattern_studio__how_patterns_are_defined' => __( 'How Patterns are defined', 'intera' ),
						'product_pattern_studio__role_view_readiness_and_upcoming_dates' => __( 'Role view · readiness and upcoming dates', 'intera' ),
						'product_pattern_studio__intera_role_view_with_readiness_metrics' => __( 'INTERA role view with readiness metrics and upcoming dates', 'intera' ),
					),
				),
				'integrations' => array(
					'label'  => __( 'Integrations', 'intera' ),
					'fields' => array(
						'product_integrations__integrations_and_datasources' => __( 'Integrations and DataSources', 'intera' ),
						'product_integrations__read_only_connections_to_the_systems' => __( 'Read-only connections to the systems of record', 'intera' ),
						'product_integrations__a_datasource_states_which_system_holds' => __( 'A DataSource states which system holds the data, what is mapped and how often it is read. Nothing is written back.', 'intera' ),
						'product_integrations__erp' => __( 'ERP', 'intera' ),
						'product_integrations__sap_oracle_bc' => __( 'SAP · Oracle · BC', 'intera' ),
						'product_integrations__crm' => __( 'CRM', 'intera' ),
						'product_integrations__accounts' => __( 'accounts', 'intera' ),
						'product_integrations__billing' => __( 'Billing', 'intera' ),
						'product_integrations__invoices_rating' => __( 'invoices · rating', 'intera' ),
						'product_integrations__mediation' => __( 'Mediation', 'intera' ),
						'product_integrations__usage_cdr' => __( 'usage · CDR', 'intera' ),
						'product_integrations__excel' => __( 'Excel', 'intera' ),
						'product_integrations__exports_checks' => __( 'exports · checks', 'intera' ),
						'product_integrations__internal_apis' => __( 'Internal APIs', 'intera' ),
						'product_integrations__custom' => __( 'custom', 'intera' ),
						'product_integrations__banking' => __( 'Banking', 'intera' ),
						'product_integrations__revolut' => __( 'Revolut', 'intera' ),
						'product_integrations__manual_inputs' => __( 'Manual inputs', 'intera' ),
						'product_integrations__forms_mail' => __( 'forms · mail', 'intera' ),
						'product_integrations__it_owns_access' => __( 'IT owns access', 'intera' ),
						'product_integrations__which_system_which_credentials_which_refresh' => __( 'Which system, which credentials, which refresh window.', 'intera' ),
						'product_integrations__intera_states_the_requirement_concretely_the' => __( 'INTERA states the requirement concretely: the system, the DataSource, the fields to map. No open-ended data project.', 'intera' ),
						'product_integrations__business_owns_logic' => __( 'Business owns logic', 'intera' ),
						'product_integrations__metrics_events_incidents_reconciliations_pattern' => __( 'Metrics, Events, Incidents, Reconciliations, Patterns.', 'intera' ),
						'product_integrations__the_people_who_know_how_the' => __( 'The people who know how the operation runs decide what counts as a problem — and can change it without a release.', 'intera' ),
					),
				),
				'roles' => array(
					'label'  => __( 'Roles', 'intera' ),
					'fields' => array(
						'product_roles__intera_roles' => __( 'INTERA Roles', 'intera' ),
						'product_roles__a_module_built_around_a_responsibility' => __( 'A module built around a responsibility, not a data source', 'intera' ),
						'product_roles__each_role_arrives_with_its_metrics' => __( 'Each role arrives with its metrics, its checks and its detection logic. Adjust them; you are not locked in.', 'intera' ),
						'product_roles__different_responsibilities_one_operating_picture' => __( 'Different responsibilities. One operating picture.', 'intera' ),
						'product_roles__roles_combine_several_sources_and_apply' => __( 'Roles combine several sources and apply business logic, so nobody connects the dots by hand.', 'intera' ),
					),
				),
				'market_packages' => array(
					'label'  => __( 'Market packages', 'intera' ),
					'fields' => array(
						'product_market_packages__market_packages' => __( 'Market packages', 'intera' ),
						'product_market_packages__industry_bundles_already_shaped_around_real' => __( 'Industry bundles, already shaped around real jobs', 'intera' ),
						'product_market_packages__a_market_package_is_a_reusable' => __( 'A market package is a reusable set of roles, checks and integrations for one industry. Two are in progress with beta partners.', 'intera' ),
						'product_market_packages__telecommunications' => __( 'Telecommunications', 'intera' ),
						'product_market_packages__where_usage_rating_billing_and_partner' => __( 'Where usage, rating, billing and partner settlement have to agree — and rarely do without checking.', 'intera' ),
						'product_market_packages__revenue_assurance_manager' => __( 'Revenue Assurance Manager', 'intera' ),
						'product_market_packages__billing_operations_manager' => __( 'Billing Operations Manager', 'intera' ),
						'product_market_packages__network_operations_manager' => __( 'Network Operations Manager', 'intera' ),
						'product_market_packages__partner_wholesale_manager' => __( 'Partner / Wholesale Manager', 'intera' ),
						'product_market_packages__commercial_director' => __( 'Commercial Director', 'intera' ),
						'product_market_packages__cfo_finance_controller' => __( 'CFO / Finance Controller', 'intera' ),
						'product_market_packages__coo_head_of_operations' => __( 'COO / Head of Operations', 'intera' ),
						'product_market_packages__telecommunications_package' => __( 'Telecommunications package', 'intera' ),
						'product_market_packages__shipmanagement' => __( 'Shipmanagement', 'intera' ),
						'product_market_packages__maintenance_backlog_defects_class_and_certificat' => __( 'Maintenance backlog, defects, class and certificate dates, and the vendor dependencies that quietly delay all of it.', 'intera' ),
						'product_market_packages__technical_superintendent' => __( 'Technical Superintendent', 'intera' ),
						'product_market_packages__fleet_manager' => __( 'Fleet Manager', 'intera' ),
						'product_market_packages__procurement_and_parts' => __( 'Procurement and parts', 'intera' ),
						'product_market_packages__compliance_and_audit' => __( 'Compliance and audit', 'intera' ),
						'product_market_packages__shipmanagement_package' => __( 'Shipmanagement package', 'intera' ),
						'product_market_packages__beta' => __( 'Beta', 'intera' ),
					),
				),
				'method' => array(
					'label'  => __( 'Method', 'intera' ),
					'fields' => array(
						'product_method__intera_method' => __( 'INTERA Method', 'intera' ),
						'product_method__a_working_system_not_a_set' => __( 'A working system, not a set of recommendations', 'intera' ),
						'product_method__the_method_is_a_hands_on' => __( 'The Method is a hands-on engagement: we work on site with your team, map how the operation actually runs, and leave a configured environment behind. Traditional consulting ends with a document. This ends with dashboards that keep working.', 'intera' ),
						'product_method__map_the_real_data_flows_not' => __( 'Map the real data flows, not the documented ones', 'intera' ),
						'product_method__identify_blind_spots_and_the_checks' => __( 'Identify blind spots and the checks nobody owns', 'intera' ),
						'product_method__define_metrics_that_reflect_the_operation' => __( 'Define Metrics that reflect the operation', 'intera' ),
						'product_method__connect_the_data_sources_with_it' => __( 'Connect the data sources with IT', 'intera' ),
						'product_method__build_the_first_roles_and_dashboards' => __( 'Build the first Roles and dashboards together', 'intera' ),
						'product_method__what_you_leave_with' => __( 'What you leave with', 'intera' ),
						'product_method__a_working_intera_environment' => __( 'A working INTERA environment', 'intera' ),
						'product_method__connected_data_sources' => __( 'Connected data sources', 'intera' ),
						'product_method__defined_metrics_and_business_logic' => __( 'Defined Metrics and business logic', 'intera' ),
						'product_method__operational_dashboards_in_use' => __( 'Operational dashboards in use', 'intera' ),
						'product_method__visibility_into_issues_you_could_not' => __( 'Visibility into issues you could not see before', 'intera' ),
						'product_method__delivered_over_several_intensive_on_site' => __( 'Delivered over several intensive on-site days. Scope depends on how many systems and roles are involved.', 'intera' ),
						'product_method__talk_to_us_about_the_method' => __( 'Talk to us about the Method', 'intera' ),
					),
				),
				'cta' => array(
					'label'  => __( 'CTA', 'intera' ),
					'fields' => array(
						'product_cta__start_with_one_real_problem' => __( 'Start with one real problem.', 'intera' ),
						'product_cta__one_role_one_operational_problem_one' => __( 'One role. One operational problem. One working result.', 'intera' ),
						'product_cta__bring_us_a_real_problem' => __( 'Bring us a real problem', 'intera' ),
					),
				),
			),
		),
		'pricing' => array(
			'label'    => __( 'Pricing page', 'intera' ),
			'template' => 'page-pricing.php',
			'sections' => array(
				'header' => array(
					'label'  => __( 'Header', 'intera' ),
					'fields' => array(
						'pricing_headline' => __( 'Start free. Pay when INTERA is doing real work.', 'intera' ),
					),
				),
				'page' => array(
					'label'  => __( 'Page', 'intera' ),
					'fields' => array(
						'pricing_page__what_each_plan_includes' => __( 'What each plan includes', 'intera' ),
						'pricing_page__roles' => __( 'Roles', 'intera' ),
						'pricing_page__roles_2' => __( 'roles', 'intera' ),
						'pricing_page__users' => __( 'Users', 'intera' ),
						'pricing_page__users_2' => __( 'users', 'intera' ),
						'pricing_page__market_package' => __( 'Market package', 'intera' ),
						'pricing_page__market_package_2' => __( 'market package', 'intera' ),
						'pricing_page__is_the_free_plan_time_limited' => __( 'Is the free plan time-limited?', 'intera' ),
						'pricing_page__no_it_is_limited_by_roles' => __( 'No. It is limited by roles, users, integrations and 30 days of history — not by a trial clock.', 'intera' ),
						'pricing_page__what_happens_after_the_12_free' => __( 'What happens after the 12 free months?', 'intera' ),
						'pricing_page__you_move_to_a_commercial_plan' => __( 'You move to a commercial plan, or you stop. Nothing you configured is held hostage.', 'intera' ),
						'pricing_page__do_we_need_the_method_to' => __( 'Do we need the Method to start?', 'intera' ),
						'pricing_page__no_the_method_is_for_teams' => __( 'No. The Method is for teams that want the first roles built together, on site, in a few intensive days.', 'intera' ),
						'pricing_page__where_does_intera_run' => __( 'Where does INTERA run?', 'intera' ),
						'pricing_page__local_installation_is_available_from_the' => __( 'Local installation is available from the free plan onwards. Access to source systems stays read-only.', 'intera' ),
					),
				),
				'comparison' => array(
					'label'  => __( 'Comparison', 'intera' ),
					'fields' => array(
						'pricing_comparison__capability' => __( 'Capability', 'intera' ),
						'pricing_comparison__prices_exclude_vat_custom_integrations_and' => __( 'Prices exclude VAT. Custom integrations and additional market packages are quoted separately.', 'intera' ),
					),
				),
				'early_adopter' => array(
					'label'  => __( 'Early Adopter', 'intera' ),
					'fields' => array(
						'pricing_early_adopter__early_adopter_offer' => __( 'Early Adopter offer', 'intera' ),
						'pricing_early_adopter__help_shape_intera_around_a_real' => __( 'Help shape INTERA around a real operation', 'intera' ),
						'pricing_early_adopter__twelve_months_free_custom_onboarding_and' => __( 'Twelve months free, custom onboarding and direct contact with the team. We ask for one real business case and your feedback.', 'intera' ),
						'pricing_early_adopter__i_have_a_problem_intera_could' => __( 'I have a problem INTERA could solve', 'intera' ),
					),
				),
				'pricing_questions' => array(
					'label'  => __( 'Pricing questions', 'intera' ),
					'fields' => array(
						'pricing_pricing_questions__questions_that_come_up_before_signing' => __( 'Questions that come up before signing', 'intera' ),
						'pricing_pricing_questions__read_the_full_faq' => __( 'Read the full FAQ', 'intera' ),
					),
				),
			),
		),
		'contacts' => array(
			'label'    => __( 'Contacts page', 'intera' ),
			'template' => 'page-contacts.php',
			'sections' => array(
				'header' => array(
					'label'  => __( 'Header', 'intera' ),
					'fields' => array(
						'contacts_headline' => __( 'Talk to us about one real problem', 'intera' ),
					),
				),
				'page' => array(
					'label'  => __( 'Page', 'intera' ),
					'fields' => array(
						'contacts_page__the_check_nobody_enjoys_doing' => __( 'The check nobody enjoys doing', 'intera' ),
						'contacts_page__the_report_reconciliation_or_status_update' => __( 'The report, reconciliation or status update someone repeats every week.', 'intera' ),
						'contacts_page__the_systems_involved' => __( 'The systems involved', 'intera' ),
						'contacts_page__erp_crm_billing_spreadsheets_names_are' => __( 'ERP, CRM, billing, spreadsheets — names are enough, no diagrams needed.', 'intera' ),
						'contacts_page__what_goes_wrong_when_it_is' => __( 'What goes wrong when it is found too late', 'intera' ),
						'contacts_page__lost_revenue_a_missed_date_an' => __( 'Lost revenue, a missed date, an escalation, a surprised customer.', 'intera' ),
						'contacts_page__early_adopter' => __( 'Early Adopter', 'intera' ),
						'contacts_page__you_have_a_real_operational_task' => __( 'You have a real operational task and want it solved during beta. Twelve months free, custom onboarding, direct line to the team.', 'intera' ),
						'contacts_page__apply_as_early_adopter' => __( 'Apply as Early Adopter', 'intera' ),
						'contacts_page__partner_or_reseller' => __( 'Partner or reseller', 'intera' ),
						'contacts_page__you_already_solve_operational_problems_for' => __( 'You already solve operational problems for customers in one industry and want to package that as roles, checks and integrations.', 'intera' ),
						'contacts_page__talk_about_partnership' => __( 'Talk about partnership', 'intera' ),
						'contacts_page__deployment_and_pricing' => __( 'Deployment and pricing', 'intera' ),
						'contacts_page__you_know_what_you_need_and' => __( 'You know what you need and want the commercial detail: installation, integrations, implementation scope and cost.', 'intera' ),
						'contacts_page__see_pricing_first' => __( 'See pricing first', 'intera' ),
						'contacts_page__what_to_send_us' => __( 'What to send us', 'intera' ),
					),
				),
				'contact_routes' => array(
					'label'  => __( 'Contact routes', 'intera' ),
					'fields' => array(
						'contacts_contact_routes__direct' => __( 'Direct', 'intera' ),
						'contacts_contact_routes__write_to_us' => __( 'Write to us', 'intera' ),
						'contacts_contact_routes__response_time' => __( 'Response time', 'intera' ),
						'contacts_contact_routes__working_language' => __( 'Working language', 'intera' ),
						'contacts_contact_routes__bring_us_a_real_problem' => __( 'Bring us a real problem', 'intera' ),
						'contacts_contact_routes__send_an_email' => __( 'Send an email', 'intera' ),
					),
				),
				'who_to_talk_to' => array(
					'label'  => __( 'Who to talk to', 'intera' ),
					'fields' => array(
						'contacts_who_to_talk_to__three_reasons_people_write_to_us' => __( 'Three reasons people write to us', 'intera' ),
						'contacts_who_to_talk_to__documentation' => __( 'Documentation', 'intera' ),
						'contacts_who_to_talk_to__documentation_2' => __( 'Documentation', 'intera' ),
						'contacts_who_to_talk_to__faq' => __( 'FAQ', 'intera' ),
						'contacts_who_to_talk_to__faq_2' => __( 'FAQ', 'intera' ),
						'contacts_who_to_talk_to__prefer_to_read_first_1_s' => __( 'Prefer to read first? %1$s and %2$s answer most first questions.', 'intera' ),
					),
				),
			),
		),
		'request' => array(
			'label'    => __( 'Contact request page', 'intera' ),
			'template' => 'page-contact-request.php',
			'sections' => array(
				'header' => array(
					'label'  => __( 'Header', 'intera' ),
					'fields' => array(
						'request_headline' => __( 'Bring us a real problem', 'intera' ),
					),
				),
				'request_form' => array(
					'label'  => __( 'Request form', 'intera' ),
					'fields' => array(
						'request_request_form__anna_kovalenko' => __( 'Anna Kovalenko', 'intera' ),
						'request_request_form__name' => __( 'Name', 'intera' ),
						'request_request_form__a_kovalenko_company_com' => __( 'a.kovalenko@company.com', 'intera' ),
						'request_request_form__work_email' => __( 'Work email', 'intera' ),
						'request_request_form__company_name' => __( 'Company name', 'intera' ),
						'request_request_form__company' => __( 'Company', 'intera' ),
						'request_request_form__billing_operations_manager' => __( 'Billing Operations Manager', 'intera' ),
						'request_request_form__your_role' => __( 'Your role', 'intera' ),
						'request_request_form__the_area_you_are_responsible_for' => __( 'The area you are responsible for.', 'intera' ),
						'request_request_form__choose_an_industry' => __( 'Choose an industry', 'intera' ),
						'request_request_form__industry' => __( 'Industry', 'intera' ),
						'request_request_form__choose_one' => __( 'Choose one', 'intera' ),
						'request_request_form__what_brings_you_here' => __( 'What brings you here', 'intera' ),
						'request_request_form__every_month_someone_exports_usage_from' => __( 'Every month someone exports usage from mediation and compares it with billing by hand. Last quarter we found unbilled usage six weeks late.', 'intera' ),
						'request_request_form__the_problem_in_your_words' => __( 'The problem, in your words', 'intera' ),
						'request_request_form__what_is_checked_manually_today_which' => __( 'What is checked manually today, which systems are involved, and what happens when it is noticed too late.', 'intera' ),
						'request_request_form__send_request' => __( 'Send request', 'intera' ),
						'request_request_form__we_use_what_you_send_only' => __( 'We use what you send only to answer you. See the %s.', 'intera' ),
						'request_request_form__privacy_policy' => __( 'privacy policy', 'intera' ),
						'request_request_form__we_use_what_you_send_only_2' => __( 'We use what you send only to answer you.', 'intera' ),
						'request_request_form__reference_s' => __( 'Reference: %s', 'intera' ),
						'request_request_form__answer_expected_s' => __( 'Answer expected: %s', 'intera' ),
						'request_request_form__read_the_docs' => __( 'Read the docs', 'intera' ),
						'request_request_form__send_another_request' => __( 'Send another request', 'intera' ),
						'request_request_form__what_happens_next' => __( 'What happens next', 'intera' ),
						'request_request_form__we_read_it_and_answer' => __( 'We read it and answer', 'intera' ),
						'request_request_form__usually_the_same_working_day_no' => __( 'Usually the same working day. No sales sequence.', 'intera' ),
						'request_request_form__one_call_30_minutes' => __( 'One call, 30 minutes', 'intera' ),
						'request_request_form__we_map_the_problem_to_a' => __( 'We map the problem to a role, metrics and the systems involved.', 'intera' ),
						'request_request_form__one_role_one_result' => __( 'One role, one result', 'intera' ),
						'request_request_form__we_set_up_the_first_check' => __( 'We set up the first check on real data and see whether it finds anything.', 'intera' ),
						'request_request_form__early_adopter_programme' => __( 'Early Adopter programme', 'intera' ),
						'request_request_form__free_for_the_first_12_months' => __( 'Free for the first 12 months, unlimited roles, custom onboarding and one market package included. We take a small number of companies during beta.', 'intera' ),
						'request_request_form__see_what_is_included' => __( 'See what is included', 'intera' ),
						'request_request_form__prefer_email_write_to_s' => __( 'Prefer email? Write to %s.', 'intera' ),
					),
				),
			),
		),
		'faq' => array(
			'label'    => __( 'FAQ page', 'intera' ),
			'template' => 'page-faq.php',
			'sections' => array(
				'faq_content' => array(
					'label'  => __( 'FAQ content', 'intera' ),
					'fields' => array(
						'faq_faq_content__on_this_page' => __( 'On this page', 'intera' ),
					),
				),
			),
		),
	);
}
