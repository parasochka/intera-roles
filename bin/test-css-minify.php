<?php
/**
 * Unit checks for intera_css_minify().
 *
 * The minifier is the last thing to touch a stylesheet before it is inlined
 * into <head>, and the way it fails is silent: a selector it mangles does not
 * raise a parse error, it simply stops matching, and the rule is gone from the
 * page with nothing to show for it. That happened once — the rule that stripped
 * whitespace around structural punctuation counted a colon as structural, so a
 * descendant combinator landing before a pseudo-class was eaten and
 * `.prose :where(ul)` shipped as the compound `.prose:where(ul)`.
 *
 * Run from the repo root: php bin/test-css-minify.php
 *
 * @package Intera
 */

define( 'ABSPATH', __DIR__ );

// The minifier needs no WordPress; the rest of the file it lives in does.
function add_action() {}
function add_filter() {}
function apply_filters( $tag, $value ) { return $value; }
function trailingslashit( $string ) { return rtrim( $string, '/\\' ) . '/'; }
function untrailingslashit( $string ) { return rtrim( $string, '/\\' ); }

require __DIR__ . '/../theme/inc/enqueue.php';

$cases = array(
	// name => array( input, expected )
	'declaration colon loses its trailing space' => array(
		'a { color: red; }',
		'a{color:red}',
	),
	'a descendant combinator before a pseudo-class survives' => array(
		'.prose :where(ul, ol) { margin: 0; }',
		'.prose :where(ul,ol){margin:0}',
	),
	'so does one before a pseudo-element' => array(
		'.prose ::selection { color: red; }',
		'.prose ::selection{color:red}',
	),
	'a pseudo-class attached to a selector stays attached' => array(
		'a:not(.itr-btn):hover { color: red; }',
		'a:not(.itr-btn):hover{color:red}',
	),
	'a child combinator before a pseudo-class is still a child combinator' => array(
		'.prose > :first-child { margin-top: 0; }',
		'.prose>:first-child{margin-top:0}',
	),
	'a media query keeps its meaning' => array(
		'@media (min-width: 768px) { a { color: red; } }',
		'@media (min-width:768px){a{color:red}}',
	),
	'calc() operators keep their spaces' => array(
		'a { margin-top: calc(24px + 12px); }',
		'a{margin-top:calc(24px + 12px)}',
	),
	'a comment goes, a url() survives whole' => array(
		"/* note: keep */\na { background: url( ../img/a b.png ); }",
		'a{background:url( ../img/a b.png )}',
	),
	'a colon inside a quoted string is left alone' => array(
		'a::after { content: "a: b"; }',
		'a::after{content:"a: b"}',
	),
	'the owl keeps its adjacent-sibling spaces' => array(
		'.prose > * + * { margin-top: 24px; }',
		'.prose>* + *{margin-top:24px}',
	),
);

$failed = 0;

foreach ( $cases as $name => $case ) {
	list( $input, $expected ) = $case;
	$actual = intera_css_minify( $input );

	if ( $actual === $expected ) {
		echo "ok    $name\n";
		continue;
	}

	++$failed;
	echo "FAIL  $name\n";
	echo "        in:       $input\n";
	echo "        expected: $expected\n";
	echo "        actual:   $actual\n";
}

if ( $failed > 0 ) {
	echo "\n$failed of " . count( $cases ) . " checks failed.\n";
	exit( 1 );
}

echo "\nAll " . count( $cases ) . " checks passed.\n";
