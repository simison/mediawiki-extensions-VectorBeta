// FIXME: This is only for the Compact personal bar beta feature. The code
// below rearranges items in the personal bar, adds click event logging and
// hijacks mw.util.addPortletLink so that gadgets add new items to the flyout
// instead of the old personal bar.
// If this feature is ever merged into the core, this code should not be used
// anymore. Instead, the Vector skin itself should be modified.

( function( mw, $ ) {
	'use strict';

	var addPortletLinkOld = mw.util.addPortletLink, bar, menu;

	function createItem( options ) {
		var $a = $( '<a>' ).
			text( options.text ).
			attr( 'href', options.href ).
			attr( 'accesskey', options.accesskey ).
			attr( 'title', options.title );

		if ( options.count ) {
			$a.append( $( '<span>' ).text( options.count ) );
		}
		return $( '<li>' ).attr( 'id', options.id ).append( $a );
	}

	function CompactMenu( groups ) {
		var self = this;

		this.$el = $( '<ul>' );
		this.order = groups;
		this.items = {};
		$.each( groups, function() {
			self.items[this] = [];
		} );
	}

	CompactMenu.prototype.addItem = function( group, $el ) {
		if ( !this.items[group].length ) {
			$el.addClass( 'group-start' );
		}
		this.items[group].push( $el );
		this.render();

		return this;
	};

	CompactMenu.prototype.render = function() {
		var self = this;

		this.$el.empty();

		$.each( this.order, function() {
			$.each( self.items[this], function() {
				self.$el.append( this );
			} );
		} );
	};

	bar = new CompactMenu( ['main'] );
	menu = new CompactMenu( ['user', 'interactions', 'portlets', 'preferences', 'end'] );

	mw.util.addPortletLink = function( portlet, href, text, id, tooltip, accesskey ) {
		var $a, $li;

		// forward calls adding stuff to places other than personal bar
		if ( portlet !== 'p-personal' ) {
			return addPortletLinkOld.apply( mw.util, arguments );
		}

		$a = $( '<a>' ).text( text ).attr( 'href', href );
		$li = $( '<li>' ).append( $a );

		// copied from mediawiki.util.js
		if ( tooltip ) {
			// Trim any existing accesskey hint and the trailing space
			tooltip = $.trim( tooltip.replace( mw.util.tooltipAccessKeyRegexp, '' ) );
			if ( accesskey ) {
				tooltip += ' [' + accesskey + ']';
			}
		}

		menu.addItem( 'portlets', createItem( {
			id: id,
			text: text,
			href: href,
			accesskey: accesskey,
			title: tooltip
		} ) );
		return $li;
	};

	$( function() {
		var $barContainer = $( '#p-personal' );

		menu.
			addItem( 'user', $( '#pt-userpage' ) ).
			addItem( 'interactions', $( '#pt-mycontris' ) ).
			// notifications item can't be simply cloned, markup has to be changed
			// and label added
			addItem( 'interactions', createItem( {
				text: mw.msg( 'notifications' ),
				count: $( '#pt-notifications' ).text(),
				href: $( '#pt-notifications' ).find( 'a' ).attr( 'href' )
			} ) ).
			addItem( 'interactions', $( '#pt-mytalk' ) ).
			addItem( 'interactions', $( '#pt-watchlist' ) ).
			addItem( 'preferences', $( '#pt-preferences' ) ).
			addItem( 'preferences', $( '#pt-betafeatures' ) ).
			addItem( 'end', createItem( {
				text: mw.msg( 'privacy' ),
				href: mw.util.getUrl( mw.msg( 'privacypage' ) )
			} ) ).
			addItem( 'end', createItem( {
				text: mw.msg( 'help' ),
				href: mw.util.getUrl( mw.msg( 'helppage' ) )
			} ) ).
			addItem( 'end', $( '#pt-logout' ) );

		bar.
			addItem( 'main', $( '#pt-uls' ) ).
			addItem( 'main', $( '#pt-notifications' ) ).
			addItem( 'main', menu.$el.wrap( '<li id="pt-flyout">' ).parent() );

		// remove the old list
		$barContainer.find( 'ul' ).remove();
		// add the new one (setTimeout prevents CSS transition flash)
		setTimeout( function() {
			$barContainer.append( bar.$el );
		}, 0 );
	} );

}( mediaWiki, jQuery ) );

