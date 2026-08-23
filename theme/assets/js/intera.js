/**
 * Intera Roles — progressive enhancement.
 *
 * Nothing on this site depends on this file. The navigation drawer opens and
 * closes with a checkbox and a `<label>` in pure CSS; everything here is the
 * layer on top: the ARIA state a checkbox cannot express on its own, closing the
 * drawer on Escape / an outside click / a widened viewport, a copy-to-clipboard
 * handler, and a print button. Every lookup is guarded — a page without the
 * markup simply skips that block.
 *
 * Hooks this file reads (all optional):
 *
 *   #intera-nav-toggle       the drawer checkbox, inside #intera-masthead
 *   #intera-nav-drawer       the drawer itself
 *   .intera-nav-burger       the <label> that acts as the burger
 *   [data-intera-copy]       copy control. The attribute value is the text to
 *                            copy; empty means the current URL.
 *                            [data-intera-copy-done] overrides the confirmation
 *                            label. Give the control a `hidden` attribute and
 *                            this file removes it, so a no-JS visitor never sees
 *                            a control that cannot work.
 *   [data-intera-print]      print control, same `hidden` treatment.
 *
 * @package Intera
 */

( function () {
	'use strict';

	/* ------------------------------------------------------------------ *
	 * A single polite live region, created on demand.
	 * ------------------------------------------------------------------ */

	var liveRegion = null;

	function announce( message ) {
		if ( ! message || ! document.body ) {
			return;
		}

		if ( ! liveRegion ) {
			liveRegion = document.createElement( 'p' );
			liveRegion.className = 'screen-reader-text';
			liveRegion.setAttribute( 'aria-live', 'polite' );
			document.body.appendChild( liveRegion );
		}

		liveRegion.textContent = message;
	}

	/* ------------------------------------------------------------------ *
	 * 1. Navigation drawer — ARIA state and the ways it closes.
	 * ------------------------------------------------------------------ */

	function initNav() {
		var masthead = document.getElementById( 'intera-masthead' );
		var toggle = document.getElementById( 'intera-nav-toggle' );

		if ( ! masthead || ! toggle ) {
			return;
		}

		var wide = window.matchMedia ? window.matchMedia( '( min-width: 1040px )' ) : null;

		function sync() {
			var open = !! toggle.checked;

			toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );

			if ( masthead.classList ) {
				masthead.classList.toggle( 'is-nav-open', open );
			}
		}

		function close( refocus ) {
			if ( ! toggle.checked ) {
				return;
			}

			toggle.checked = false;
			sync();

			if ( refocus ) {
				toggle.focus();
			}
		}

		sync();
		toggle.addEventListener( 'change', sync );

		// Escape closes and hands focus back to the control that opened it.
		document.addEventListener( 'keydown', function ( event ) {
			if ( 'Escape' === event.key || 'Esc' === event.key ) {
				close( true );
			}
		} );

		// A click anywhere outside the masthead closes it. No refocus: the
		// visitor's attention is already elsewhere.
		document.addEventListener( 'click', function ( event ) {
			if ( ! toggle.checked ) {
				return;
			}

			if ( event.target instanceof Node && masthead.contains( event.target ) ) {
				return;
			}

			close( false );
		} );

		// Following a link inside the drawer closes it, so an in-page anchor does
		// not leave the panel covering the target.
		var drawer = document.getElementById( 'intera-nav-drawer' );

		if ( drawer ) {
			drawer.addEventListener( 'click', function ( event ) {
				var node = event.target;

				while ( node && node !== drawer ) {
					if ( 'A' === node.nodeName ) {
						close( false );
						return;
					}

					node = node.parentNode;
				}
			} );
		}

		// Widening past the breakpoint hides the drawer in CSS; clear the state
		// so the burger is not left reading "expanded".
		if ( wide ) {
			var onWide = function ( event ) {
				if ( event.matches ) {
					close( false );
				}
			};

			if ( wide.addEventListener ) {
				wide.addEventListener( 'change', onWide );
			} else if ( wide.addListener ) {
				wide.addListener( onWide );
			}
		}
	}

	/* ------------------------------------------------------------------ *
	 * 2. Copy link — the blog post's "Copy link" control.
	 * ------------------------------------------------------------------ */

	/**
	 * Returns the control's own label text node, so the icon beside it survives.
	 */
	function labelNode( element ) {
		var i;

		for ( i = element.childNodes.length - 1; i >= 0; i-- ) {
			if ( 3 === element.childNodes[ i ].nodeType && element.childNodes[ i ].nodeValue.replace( /\s+/g, '' ) ) {
				return element.childNodes[ i ];
			}
		}

		return null;
	}

	function writeToClipboard( text ) {
		if ( navigator.clipboard && navigator.clipboard.writeText ) {
			return navigator.clipboard.writeText( text );
		}

		// Older browsers and insecure contexts.
		return new Promise( function ( resolve, reject ) {
			var field = document.createElement( 'textarea' );
			var copied = false;

			field.value = text;
			field.setAttribute( 'readonly', 'readonly' );
			field.style.position = 'fixed';
			field.style.top = '0';
			field.style.left = '0';
			field.style.opacity = '0';

			document.body.appendChild( field );
			field.select();

			try {
				copied = document.execCommand( 'copy' );
			} catch ( error ) {
				copied = false;
			}

			document.body.removeChild( field );

			if ( copied ) {
				resolve();
			} else {
				reject();
			}
		} );
	}

	function initCopy() {
		var controls = document.querySelectorAll( '[data-intera-copy]' );

		if ( ! controls.length ) {
			return;
		}

		Array.prototype.forEach.call( controls, function ( control ) {
			var restore = null;

			control.removeAttribute( 'hidden' );

			control.addEventListener( 'click', function ( event ) {
				event.preventDefault();

				var text = control.getAttribute( 'data-intera-copy' ) || window.location.href;
				var done = control.getAttribute( 'data-intera-copy-done' ) || 'Copied';
				var node = labelNode( control );

				writeToClipboard( text ).then( function () {
					announce( done );

					if ( ! node ) {
						return;
					}

					if ( null === restore ) {
						restore = node.nodeValue;
					}

					node.nodeValue = done;
					window.clearTimeout( control.interaCopyTimer );

					control.interaCopyTimer = window.setTimeout( function () {
						if ( null !== restore ) {
							node.nodeValue = restore;
							restore = null;
						}
					}, 2400 );
				}, function () {
					// Nothing to fall back to; leave the label alone.
				} );
			} );
		} );
	}

	/* ------------------------------------------------------------------ *
	 * 3. Print — the policy page's "Print this page" control.
	 * ------------------------------------------------------------------ */

	function initPrint() {
		var controls = document.querySelectorAll( '[data-intera-print]' );

		if ( ! controls.length ) {
			return;
		}

		Array.prototype.forEach.call( controls, function ( control ) {
			control.removeAttribute( 'hidden' );

			control.addEventListener( 'click', function ( event ) {
				event.preventDefault();
				window.print();
			} );
		} );
	}

	/* ------------------------------------------------------------------ */

	/**
	 * Make the BetterDocs FAQ rows reachable from the keyboard.
	 *
	 * The FAQ page keeps the plugin's own block, and the plugin builds each
	 * question as a <div> with a click handler: no tab stop, no Enter, nothing
	 * announced. The theme cannot rewrite that markup — an editor authors the
	 * questions there — so it upgrades it in place.
	 *
	 * The plugin keeps ownership of opening and closing: this only forwards
	 * Enter and Space to the click it already listens for, and mirrors the
	 * resulting state into aria-expanded. Without JavaScript the answers stay
	 * where the plugin left them, exactly as before.
	 */
	function initFaq() {
		var rows = document.querySelectorAll( '.intera-faq .betterdocs-faq-post' );

		if ( ! rows.length ) {
			return;
		}

		Array.prototype.forEach.call( rows, function ( row ) {
			var group = row.closest ? row.closest( '.betterdocs-faq-group' ) : null;
			var panel = group ? group.querySelector( '.betterdocs-faq-main-content' ) : null;

			row.setAttribute( 'tabindex', '0' );
			row.setAttribute( 'role', 'button' );

			function sync() {
				var open = !! ( group && group.classList.contains( 'active' ) );

				row.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
			}

			if ( panel ) {
				if ( ! panel.id ) {
					panel.id = 'intera-faq-answer-' + ( ++faqPanelCount );
				}

				row.setAttribute( 'aria-controls', panel.id );
			}

			sync();

			row.addEventListener( 'keydown', function ( event ) {
				if ( 'Enter' !== event.key && ' ' !== event.key && 'Spacebar' !== event.key ) {
					return;
				}

				event.preventDefault();
				row.click();
			} );

			/*
			 * The plugin toggles `.active` itself, and it animates the panel,
			 * so the class lands some time after the click rather than during
			 * it. Reading it on the next frame is what keeps aria-expanded
			 * honest.
			 */
			row.addEventListener( 'click', function () {
				window.requestAnimationFrame( function () {
					window.requestAnimationFrame( sync );
				} );
			} );
		} );
	}

	var faqPanelCount = 0;

	/* ------------------------------------------------------------------ */

	function init() {
		initNav();
		initCopy();
		initPrint();
		initFaq();
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
}() );
