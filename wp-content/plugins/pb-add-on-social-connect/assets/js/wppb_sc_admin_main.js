/* hide Social Connect custom button text fields */
function wppb_sc_buttons_text_fields( element ) {
    if( jQuery( element ).val() == 'text' ) {
        jQuery( '.row-facebook-button-text, .row-google-button-text, .row-twitter-button-text, .row-facebook-button-text-ep, .row-google-button-text-ep, .row-twitter-button-text-ep' ).show();
        jQuery( '.row-heading-before-reg-buttons, .row-heading-before-ep-buttons' ).hide();
    } else {
        jQuery( '.row-facebook-button-text, .row-google-button-text, .row-twitter-button-text, .row-facebook-button-text-ep, .row-google-button-text-ep, .row-twitter-button-text-ep' ).hide();
        jQuery( '.row-heading-before-reg-buttons, .row-heading-before-ep-buttons' ).show();
    }
}

jQuery( document ).ready( function() {
    wppb_sc_handle_buttons_order_field();

    wppb_sc_buttons_text_fields( '#wppb_social_connect_settings ' + '#buttons-style' );
    jQuery( '#wppb_social_connect_settings ' + '#buttons-style' ).change( function() {
        wppb_sc_buttons_text_fields( this );
    } );
} );

/* function that handles the sorting of the buttons */
function wppb_sc_handle_buttons_order_field() {
    jQuery( '#wppb_social_connect_settings ' + '.row-buttons-order .wck-checkboxes' ).sortable( {

        // assign a custom handle for the drag and drop
        handle: '.sortable-handle',

        create: function( event, ui ) {

            // add the custom handle for drag and drop
            jQuery( this ).find( 'div' ).each( function() {
                jQuery( this ).prepend( '<span class="sortable-handle"></span>' );
            } );

            $sortOrderInput = jQuery( this ).parents( '.row-buttons-order' ).siblings( '.row-buttons-re-order' ).find( 'input[type=text]' );

            if( $sortOrderInput.val() == '' ) {
                jQuery( this ).find( 'input[type=checkbox]' ).each( function() {
                    $sortOrderInput.val( $sortOrderInput.val() + ', ' + jQuery( this ).val() );
                } );
            } else {
                sortOrderElements = $sortOrderInput.val().split( ', ' );
                sortOrderElements.shift();

                for( var i=0; i < sortOrderElements.length; i++ ) {
                    jQuery( '#wppb_social_connect_settings ' + '.row-buttons-order .wck-checkboxes' ).append( jQuery( '#wppb_social_connect_settings ' + '.row-buttons-order .wck-checkboxes input[value=' + sortOrderElements[i] + ']' ).parent().parent().get( 0 ) );
                }
            }
        },

        update: function( event, ui ) {
            $sortOrderInput = ui.item.parents( '.row-buttons-order' ).siblings( '.row-buttons-re-order' ).find( 'input[type=text]' );
            $sortOrderInput.val( '' );

            ui.item.parent().find( 'input[type=checkbox]' ).each( function() {
                $sortOrderInput.val( $sortOrderInput.val() + ', ' + jQuery( this ).val() );
            } );
        }
    } );
}