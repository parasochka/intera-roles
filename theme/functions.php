<?php
/**
 * Intera Roles — theme bootstrap.
 *
 * Thin loader only: constants + `require inc/*`. Real work lives in inc/.
 *
 * @package Intera
 */

defined( 'ABSPATH' ) || exit;

/** Theme version — bump on every release; used for cache busting. */
define( 'INTERA_VERSION', '0.1.0' );

/** Absolute path to the theme root, with a trailing slash. */
define( 'INTERA_DIR', trailingslashit( get_template_directory() ) );

/** Absolute path to the design-system directory (tokens = source of truth). */
define( 'INTERA_DS_DIR', INTERA_DIR . '_ds/intera/' );

require_once INTERA_DIR . 'inc/setup.php';
require_once INTERA_DIR . 'inc/enqueue.php';
