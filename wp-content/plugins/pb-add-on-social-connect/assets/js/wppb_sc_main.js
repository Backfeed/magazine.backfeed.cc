function wppbGetCookie( cname ) {
    var name = cname + "=";
    var ca = document.cookie.split( ';' );
    for( var i=0; i<ca.length; i++ ) {
        var c = ca[i];
        while( c.charAt( 0 ) == ' ' ) c = c.substring( 1 );
        if( c.indexOf( name ) == 0 ) return c.substring( name.length, c.length );
    }
    return "";
}

function wppbSCLogin( data, platformSettings, platform ) {
    var login_selector = jQuery( '.wppb-sc-' + platform + '-login' );

    jQuery( login_selector.children() )
        .removeClass()
        .addClass( 'wppb-sc-icon' )
        .addClass( 'wppb-sc-icon-spinner' );

    jQuery.post( platformSettings.ajaxUrl, data, function( response ) {
        var platform_icon = platform;
        if( platform == 'google' ) {
            platform_icon = 'google-plus';
        }

        jQuery( login_selector.children() )
            .removeClass( 'wppb-sc-icon-spinner' )
            .addClass( 'wppb-sc-icon-' + platform_icon );

        /* remove previous messages */
        jQuery( '.wppb-sc-message' ).remove();

        if( response == 'failed' ) {
            jQuery( '.wppb-sc-buttons-container').append( '<div class="wppb-error wppb-sc-message">' + platformSettings.error_message + '</div>' );
        } else if( response == 'linked_successful' ) {
            jQuery( '.wppb-sc-buttons-container' ).append( '<div class="wppb-success wppb-sc-message">' + platformSettings.edit_profile_success_linked + '</div>' );

            jQuery( login_selector.children() )
                .removeClass( 'wppb-sc-icon-spinner' )
                .addClass( 'wppb-sc-icon-' + platform_icon );

            jQuery( login_selector )
                .addClass( 'wppb-sc-disabled-btn' );
        } else if( response == 'email_confirmation_on' ) {
            jQuery( '.wppb-sc-buttons-container' ).append( '<div class="wppb-success wppb-sc-message">' + platformSettings.email_confirmation_on + '</div>' );
        } else if( response == 'admin_approval_on' ) {
            jQuery( '.wppb-sc-buttons-container' ).append( '<div class="wppb-success wppb-sc-message">' + platformSettings.admin_approval_on + '</div>' );
        } else if( response == 'email_confirmation_error' ) {
            jQuery( '.wppb-sc-buttons-container' ).append( '<div class="wppb-error wppb-sc-message">' + platformSettings.email_confirmation_error + '</div>' );
        } else if( response == 'admin_approval_error' ) {
            jQuery( '.wppb-sc-buttons-container' ).append( '<div class="wppb-error wppb-sc-message">' + platformSettings.admin_approval_error + '</div>' );
        } else {
            var clickresponse = JSON.parse( response );

            if( typeof( clickresponse.redirect_to ) !== 'undefined' ) {
                jQuery( jQuery( '.wppb-sc-' + platform + '-login' ).children() )
                    .removeClass()
                    .addClass( 'wppb-sc-icon' )
                    .addClass( 'wppb-sc-icon-spinner' );

                window.location.href = clickresponse.redirect_to;
            } else if( clickresponse.action == 'wppb_sc_existing_account_prompt') {
                jQuery( "#wppb_sc_account_exists" ).remove();
                jQuery( "body" ).append(
                    "<div id='wppb_sc_account_exists' style='display:none'>" +
                        "<p>" + platformSettings.account_exists_text + "</p>" +
                        "<input type='submit' id='wppb_sc_account_connect' value='Yes' />" +
                        "<input type='submit' id='wppb_sc_new_account' value='No' />" +
                    "</div>"
                );

                tb_show( '', '#TB_inline?height=200&width=500&inlineId=wppb_sc_account_exists', '' );

                jQuery( 'input#wppb_sc_account_connect' ).click( function() {
                    wppbSCLogin_account_exists_connect( data, platformSettings, platform, clickresponse );
                } );

                jQuery( 'input#wppb_sc_new_account' ).click( function() {
                    wppbSCLogin_account_exists_make_new( data, platformSettings, platform, clickresponse );
                } );
            }
        }
    } );
}

function wppbSCLogin_account_exists_connect( data, platformSettings, platform, clickresponse ) {
    jQuery( "#wppb_sc_account_password_tb" ).remove();
    jQuery( "body" ).append(
        "<div id='wppb_sc_account_password_tb' style='display:none'>" +
            "<p>" + platformSettings.password_text + "</p>" +
            "<form class='wppb_sc_form'>" +
                "<input type='password' id='wppb_sc_account_password' name='password'>" +
                "<input type='submit' id='wppb_sc_submit_account_password' value='Ok' />" +
            "</form>" +
        "</div>"
    );

    jQuery( "#TB_window" ).remove();
    jQuery( "body" ).append( "<div id='TB_window'></div>" );

    tb_show( '', '#TB_inline?height=150&width=500&inlineId=wppb_sc_account_password_tb', '' );

    jQuery( 'input#wppb_sc_submit_account_password' ).click( function( e ) {
        jQuery( jQuery( '.wppb-sc-' + platform + '-login' ).children() )
            .removeClass()
            .addClass( 'wppb-sc-icon' )
            .addClass( 'wppb-sc-icon-spinner' );

        e.preventDefault();
        var password = jQuery( '#wppb_sc_account_password' ).val();
        tb_remove();
        if( password != null ) {
            clickresponse.action = 'wppb_sc_handle_login_click';
            clickresponse.platform = platform;
            clickresponse.password = password;
            clickresponse.wppb_sc_form_ID = data.wppb_sc_form_ID;

            jQuery.post( platformSettings.ajaxUrl, clickresponse, function( response ) {
                var anotherResponse = JSON.parse( response );
                if( typeof( anotherResponse.error ) !== 'undefined' ) {
                    var platform_icon = platform;
                    if( platform == 'google' ) {
                        platform_icon = 'google-plus';
                    }

                    jQuery( jQuery( '.wppb-sc-' + platform + '-login' ).children() )
                        .removeClass( 'wppb-sc-icon-spinner' )
                        .addClass( 'wppb-sc-icon-' + platform_icon );

                    jQuery( "body" ).append(
                        "<div id='wppb_sc_wrong_password' style='display:none'>" +
                            "<p>Wrong password !</p>" +
                        "</div>"
                    );

                    tb_show( '', '#TB_inline?height=100&width=300&inlineId=wppb_sc_wrong_password', '' );
                } else {
                    if( typeof( anotherResponse.redirect_to ) !== 'undefined' ) {
                        window.location.href = anotherResponse.redirect_to;
                    }
                }
            } );
        }
    } );
}

function wppbSCLogin_account_exists_make_new( data, platformSettings, platform, clickresponse ) {
    jQuery( "#wppb_sc_account_email_tb" ).remove();
    jQuery( "body" ).append(
        "<div id='wppb_sc_account_email_tb' style='display:none'>" +
            "<p>" + platformSettings.new_email_text + "</p>" +
            "<form class='wppb_sc_form'>" +
                "<input type='text' id='wppb_sc_account_email' name='email'>" +
                "<input type='submit' id='wppb_sc_submit_account_email' value='Ok' />" +
            "</form>" +
        "</div>"
    );

    jQuery( "#TB_window" ).remove();
    jQuery( "body" ).append( "<div id='TB_window'></div>" );

    tb_show( '', '#TB_inline?height=150&width=500&inlineId=wppb_sc_account_email_tb', '' );

    jQuery( 'input#wppb_sc_submit_account_email' ).click( function( e ) {
        jQuery( jQuery( '.wppb-sc-' + platform + '-login' ).children() )
            .removeClass()
            .addClass( 'wppb-sc-icon' )
            .addClass( 'wppb-sc-icon-spinner' );

        e.preventDefault();
        var newEmail = jQuery( '#wppb_sc_account_email' ).val();
        tb_remove();
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        if( newEmail != null && newEmail.length !== 0 && emailReg.test( newEmail ) ) {
            clickresponse.action = 'wppb_sc_handle_login_click';
            clickresponse.platform = platform;
            clickresponse.new_email = newEmail;
            clickresponse.existing_user_id = null;
            clickresponse.wppb_sc_form_ID = data.wppb_sc_form_ID;

            jQuery.post( platformSettings.ajaxUrl, clickresponse, function( response ) {
                var anotherResponse = JSON.parse( response );

                if( typeof( anotherResponse.redirect_to ) !== 'undefined' ) {
                    window.location.href = anotherResponse.redirect_to;
                }

            } );
        } else {
            jQuery( "#TB_window" ).remove();
            jQuery( "body" ).append( "<div id='TB_window'></div>" );

            setTimeout( function() {
                var platform_icon = platform;
                if( platform == 'google' ) {
                    platform_icon = 'google-plus';
                }

                jQuery( jQuery( '.wppb-sc-' + platform + '-login' ).children() )
                    .removeClass( 'wppb-sc-icon-spinner' )
                    .addClass( 'wppb-sc-icon-' + platform_icon );

                jQuery( "body" ).append(
                    "<div id='wppb_sc_wrong_email' style='display:none'>" +
                        "<p>Please enter a valid email!</p>" +
                    "</div>"
                );

                tb_show( '', '#TB_inline?height=100&width=300&inlineId=wppb_sc_wrong_email', '' );
            }, 500 );
        }
    } );
}

// unlink social accounts url
jQuery( window ).load( function() {
    jQuery( '.wppb_sc_unlink_account' ).click( function( e ) {
        e.preventDefault();

        var data = {
            'action'                        : 'wppb_sc_unlink_account',
            'wppb_sc_unlink_platform_id'    : e.currentTarget.id
        };

        jQuery.post( wppb_sc_data.ajaxUrl , data, function( response ) {
            if( response == 'successful_unlink' ) {
                if( jQuery( e.currentTarget.parentNode ).prev( '.wppb-sc-separator').length == 0 ) {
                    jQuery( e.currentTarget.parentNode ).next( '.wppb-sc-separator' ).remove();
                }
                jQuery( e.currentTarget.parentNode ).prev( '.wppb-sc-separator' ).remove();
                jQuery( e.currentTarget.parentNode ).remove();
                jQuery( '.wppb-sc-buttons-container .wppb-sc-message' ).remove();
                jQuery( '.wppb-sc-buttons-container' ).append( '<div class="wppb-success wppb-sc-message">' + wppb_sc_data.edit_profile_success_unlink + '</div>' );

                switch( e.currentTarget.id ) {
                    case 'wppb_sc_unlink_facebook':
                        jQuery( '.wppb-sc-facebook-login' ).removeClass( 'wppb-sc-disabled-btn' );
                        break;
                    case 'wppb_sc_unlink_google':
                        jQuery( '.wppb-sc-google-login' ).removeClass( 'wppb-sc-disabled-btn' );
                        break;
                    case 'wppb_sc_unlink_twitter':
                        jQuery( '.wppb-sc-twitter-login' ).removeClass( 'wppb-sc-disabled-btn' );
                        break;
                }
            }
        } );
    } );
} );