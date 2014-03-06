( function( mw, $ ) {
	'use strict';

	mw.beta = mw.beta || {};
	mw.beta.trackClick = $.noop;

	// https://github.com/Modernizr/Modernizr/blob/master/feature-detects/storage/localstorage.js
	function supportsLocalStorage() {
		var mod = 'modernizr';
		try {
			localStorage.setItem(mod, mod);
			localStorage.removeItem(mod);
			return true;
		} catch(e) {
			return false;
		}
	}

	function logSavedClick() {
		var
			schemaName = localStorage.getItem( 'trackClickSchemaName' ),
			data = localStorage.getItem( 'trackClickData' );

		if ( schemaName ) {
			mw.eventLog.logEvent( schemaName, JSON.parse( data ) );
			localStorage.removeItem( 'trackClickSchemaName' );
			localStorage.removeItem( 'trackClickData' );
		}
	}

	if ( supportsLocalStorage() && mw.eventLog ) {

		mw.beta.trackClick = function( el, schemaName, data ) {
			$( el ).on( 'click', function() {
				localStorage.setItem( 'trackClickSchemaName', schemaName );
				localStorage.setItem( 'trackClickData', JSON.stringify( data ) );
				// schedule sending the event if the click target was not a link,
				// a link that was prevented or a link that only changed the anchor
				setTimeout( logSavedClick, 2000 );
			} );
		};

		// send an event stored by a click on a link on previous page (if present)
		logSavedClick();

	}
}( mediaWiki, jQuery ) );
