// Silence is golden.
$( function ( $ ) {
	/**
	 * Tabbable JavaScript codes & Initiate Color Picker
	 * PageFlash Settings API
	 * This code uses local storage for displaying active tabs.
	 * @version 1.0.0
	 * @since 1.0.0
	 */

	// Initiate Color Picker
	$( '.wp-color-picker-field' ).wpColorPicker();

	// Switches option sections
	$( '.group' ).hide();
	let activeTab = '';
	if ( typeof localStorage !== 'undefined' ) {
		activeTab = localStorage.getItem( 'activetab' );
	}
	if ( activeTab !== '' && $( activeTab ).length ) {
		$( activeTab ).fadeIn();
	} else {
		$( '.group:first' ).fadeIn();
	}
	$( '.group .collapsed' ).each( function () {
		$( this )
			.find( 'input:checked' )
			.parent()
			.parent()
			.parent()
			.nextAll()
			.each( function () {
				if ( $( this ).hasClass( 'last' ) ) {
					$( this ).removeClass( 'hidden' );
					return false;
				}
				$( this ).filter( '.hidden' ).removeClass( 'hidden' );
			} );
	} );

	if ( activeTab !== '' && $( activeTab + '-tab' ).length ) {
		$( activeTab + '-tab' ).addClass( 'nav-tab-active' );
	} else {
		$( '.nav-tab-wrapper a:first' ).addClass( 'nav-tab-active' );
	}

	$( '.nav-tab-wrapper a' ).on( 'click', function ( evt ) {
		$( '.nav-tab-wrapper a' ).removeClass( 'nav-tab-active' );
		$( this ).addClass( 'nav-tab-active' ).trigger( 'blur' );
		const clickedGroup = $( this ).attr( 'href' );
		if ( typeof localStorage !== 'undefined' ) {
			localStorage.setItem( 'activetab', $( this ).attr( 'href' ) );
		}
		$( '.group' ).hide();
		$( clickedGroup ).fadeIn();
		evt.preventDefault();
	} );

	$( '.wpsa-browse' ).on( 'click', function ( event ) {
		event.preventDefault();

		const self = $( this );

		// Create the media frame.
		const fileFrame = ( wp.media.frames.fileFrame = wp.media( {
			title: self.data( 'uploader_title' ),
			button: {
				text: self.data( 'uploader_button_text' ),
			},
			multiple: false,
		} ) );

		fileFrame.on( 'select', function () {
			attachment = fileFrame.state().get( 'selection' ).first().toJSON();
			self.prev( '.wpsa-url' ).val( attachment.url );
		} );

		// Finally, open the modal
		fileFrame.open();
	} );
} );
