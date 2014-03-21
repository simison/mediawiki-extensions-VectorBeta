jQuery( function( $ ) {
	var $body = $( 'body' );
	function goPositionFixed() {
		if ( $( this ).scrollTop() > 0 && !$( document.body ).hasClass( 'mw-special-MobileMenu' ) ) {
			$body.addClass( 'mw-scrolled' );
		} else {
			$body.removeClass( 'mw-scrolled' );
		}
	}
	$( window ).on( 'scroll', $.debounce( 100, goPositionFixed ) );
} );
