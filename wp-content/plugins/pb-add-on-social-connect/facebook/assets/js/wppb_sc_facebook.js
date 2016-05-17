window.fbAsyncInit = function() {
    FB.init( {
        appId      : wppb_sc_facebook_data.appId,
        cookie     : true,  // enable cookies to allow the server to access
                            // the session
        xfbml      : true,  // parse social plugins on this page
        version    : 'v2.2' // use version 2.2
    } );
};

function wppbFBLogIn( wppb_sc_form_ID ) {
    FB.login( function( response ) {
        if( response.authResponse ) {
            checkLoginState( wppb_sc_form_ID );
        }
    }, {
        scope: 'public_profile,email'
    } );

    return false;
}

// Load the SDK asynchronously
( function( d, s, id ) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if( d.getElementById( id ) ) return;
    js = d.createElement( s ); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore( js, fjs );
} ( document, 'script', 'facebook-jssdk' ) );

function statusChangeCallback( response, wppb_sc_form_ID ) {
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if( response.status === 'connected' ) {
        // Logged into your app and Facebook.
        var data = {
            'action'                    : 'wppb_sc_save_cookies',
            'wppb_sc_security_token'    : response.authResponse.accessToken,
            'wppb_sc_platform_name'     : 'facebook'
        };

        jQuery.post( wppb_sc_facebook_data.ajaxUrl , data, function() {
            wppbLoginIn( response.authResponse.accessToken, wppb_sc_form_ID );
        } );
    } else if( response.status === 'not_authorized' ) {
        // The person is logged into Facebook, but not your app.
        //document.getElementById('status').innerHTML = 'Please log ' + 'into this app.';
    } else {
        // The person is not logged into Facebook, so we're not sure if
        // they are logged into this app or not.
        //document.getElementById('status').innerHTML = 'Please log ' + 'into Facebook.';
    }
}

function checkLoginState( wppb_sc_form_ID ) {
    FB.getLoginStatus( function( response ) {
        statusChangeCallback( response, wppb_sc_form_ID );
    } );
}

function wppbLoginIn( token, wppb_sc_form_ID ) {
    FB.api( '/me?fields=first_name,last_name,email', function( response ) {
        var data = {
            'platform'                  : 'facebook',
            'action'                    : 'wppb_sc_handle_login_click',
            'platform_response'         : response,
            'wppb_sc_security_token'    : token,
            'wppb_sc_form_ID'           : wppb_sc_form_ID
        };

        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

        if( 'email' in response && response.email && emailReg.test( response.email ) ) {
            wppbSCLogin( data, wppb_sc_facebook_data, 'facebook' );
        } else {
            var data_id_check = {
                'platform'      : 'facebook',
                'action'        : 'wppb_sc_handle_platform_id_check',
                'platform_id'   : response.id
            };

            jQuery.post( wppb_sc_facebook_data.ajaxUrl, data_id_check, function( response ) {
                if( response == 'new_account' ) {
                    jQuery( "#wppb_sc_facebook_your_email_tb" ).remove();
                    jQuery( "body" ).append(
                        "<div id='wppb_sc_facebook_your_email_tb' style='display:none'>" +
                            "<p>" + wppb_sc_facebook_data.enter_facebook_email_text + "</p>" +
                            "<form class='wppb_sc_form'>" +
                                "<input type='text' id='wppb_sc_facebook_your_email' name='email'>" +
                                "<input type='submit' id='wppb_sc_submit_facebook_your_email' value='Ok' />" +
                            "</form>" +
                        "</div>"
                    );

                    tb_show( '', '#TB_inline?height=150&width=500&inlineId=wppb_sc_facebook_your_email_tb', '' );

                    jQuery( 'input#wppb_sc_submit_facebook_your_email' ).click( function( e ) {
                        e.preventDefault();
                        var yourEmail = jQuery( '#wppb_sc_facebook_your_email' ).val();
                        tb_remove();
                        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                        if( yourEmail != null && yourEmail.length !== 0 && emailReg.test( yourEmail ) ) {
                            data.platform_response.email = yourEmail;

                            wppbSCLogin( data, wppb_sc_facebook_data, 'facebook' );
                        } else {
                            jQuery( "#TB_window" ).remove();
                            jQuery( "body" ).append( "<div id='TB_window'></div>" );

                            setTimeout( function() {
                                jQuery( "body" ).append(
                                    "<div id='wppb_sc_wrong_email' style='display:none'>" +
                                        "<p>" + wppb_sc_facebook_data.facebook_invalid_email_text + "</p>" +
                                    "</div>"
                                );

                                tb_show( '', '#TB_inline?height=100&width=300&inlineId=wppb_sc_wrong_email', '' );
                            }, 500);
                        }
                    } );
                } else {
                    wppbSCLogin( data, wppb_sc_facebook_data, 'facebook' );
                }
            } );
        }
    } );
}

jQuery( function() {
    jQuery( '.wppb-logout-url, #wp-admin-bar-logout a' ).click( function() {
        if( ( wppbGetCookie( 'wppb_sc_security_token' ) != '' ) && ( wppbGetCookie( 'wppb_sc_platform_name' ) == 'facebook' ) ) {

            document.cookie = 'wppb_sc_security_token' + '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
            document.cookie = 'wppb_sc_platform_name' + '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';

            FB.getLoginStatus( function( response ) {
                FB.logout( function( response ) {
                    // Person is now logged out
                } );
            } );
        }
    } );
} );