jQuery( function( $ ) {
	var $body = $( 'body' );
	function goPositionFixed() {
		if ( $( this ).scrollTop() > 0 ) {
			$body.addClass( 'mw-scrolled' );
		} else {
			$body.removeClass( 'mw-scrolled' );
		}
	}
	$( window ).on( 'scroll', $.debounce( 100, goPositionFixed ) );
} );
