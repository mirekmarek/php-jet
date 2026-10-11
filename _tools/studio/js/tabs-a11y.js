/*
 * Arrow-key navigation for [role="tablist"] widgets (WAI-ARIA APG tabs pattern:
 * https://www.w3.org/WAI/ARIA/apg/patterns/tabs/).
 *
 * Bootstrap 4's own tab.js handles click activation and toggling aria-selected,
 * but it does not move focus or roving tabindex on arrow keys, so that part is
 * done here instead.
 */
(function () {
	'use strict';

	function getTabs( tablist ) {
		return Array.prototype.slice.call( tablist.querySelectorAll( '[role="tab"]' ) );
	}

	function activate( tab ) {
		if( window.jQuery ) {
			window.jQuery( tab ).tab( 'show' );
		} else {
			tab.click();
		}
	}

	document.addEventListener( 'keydown', function ( e ) {
		var tab = e.target.closest && e.target.closest( '[role="tab"]' );
		if( !tab ) {
			return;
		}

		var tablist = tab.closest( '[role="tablist"]' );
		if( !tablist ) {
			return;
		}

		var tabs = getTabs( tablist );
		var index = tabs.indexOf( tab );
		var new_index = null;

		switch( e.key ) {
			case 'ArrowRight':
			case 'ArrowDown':
				new_index = ( index + 1 ) % tabs.length;
				break;
			case 'ArrowLeft':
			case 'ArrowUp':
				new_index = ( index - 1 + tabs.length ) % tabs.length;
				break;
			case 'Home':
				new_index = 0;
				break;
			case 'End':
				new_index = tabs.length - 1;
				break;
			default:
				return;
		}

		e.preventDefault();

		tabs.forEach( function ( t ) {
			t.setAttribute( 'tabindex', '-1' );
		} );

		var new_tab = tabs[new_index];
		new_tab.setAttribute( 'tabindex', '0' );
		new_tab.focus();
		activate( new_tab );
	} );
})();
