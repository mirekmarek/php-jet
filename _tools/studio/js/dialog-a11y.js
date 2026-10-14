/*
 * Return-focus-to-trigger for Bootstrap 4 modals (WAI-ARIA APG dialog pattern:
 * https://www.w3.org/WAI/ARIA/apg/patterns/dialog-modal/).
 *
 * Bootstrap 4's modal.js already moves focus into the dialog on show (via the
 * tabindex="-1" now set on .modal) and keeps focus from leaving it while open, but
 * it does not reliably return focus to whatever triggered the dialog once it
 * closes - that part is done here instead.
 */
(function () {
	'use strict';

	if( !window.jQuery ) {
		return;
	}

	var $ = window.jQuery;
	var last_trigger = new WeakMap();

	$( document ).on( 'show.bs.modal', '.modal', function ( e ) {
		var trigger = document.activeElement;
		if( trigger && trigger !== document.body ) {
			last_trigger.set( e.target, trigger );
		}
	} );

	$( document ).on( 'hidden.bs.modal', '.modal', function ( e ) {
		var trigger = last_trigger.get( e.target );
		if( trigger && document.contains( trigger ) && typeof trigger.focus === 'function' ) {
			trigger.focus();
		}
		last_trigger.delete( e.target );
	} );
})();
