( function() {
    var po = document.createElement( 'script' ); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/client.js?onload=wppbOnLoadCallback';
    var s = document.getElementsByTagName( 'script' )[0]; s.parentNode.insertBefore( po, s );
} )();

function wppbOnLoadCallback() {
    gapi.client.load( 'plus', 'v1', function() {} );
}

var wppb_sc_form_ID_google;
function wppbGPLogin( wppb_sc_form_ID ) {
    wppb_sc_form_ID_google = wppb_sc_form_ID;

    var config = {
        'clientid'       :   wppb_sc_google_data.client_id,
        'cookiepolicy'   :   'single_host_origin',
        'callback'       :   'wppbLoginCallback',
        'scope'          :   'https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/plus.profile.emails.read'
    };

    gapi.auth.signIn( config );

    return false;
}

function wppbLoginCallback( result ) {
    if( result['status']['signed_in'] ) {
        if ( result['status']['method'] == 'PROMPT' ) {
            var data = {
                'action': 'wppb_sc_save_cookies',
                'wppb_sc_security_token': result['access_token'],
                'wppb_sc_platform_name': 'google'
            };

            jQuery.post( wppb_sc_google_data.ajaxUrl, data, function() {
                var request = gapi.client.plus.people.get( {
                    'userId': 'me'
                } );

                request.execute( function( response ) {
                    for( i = 0; i < response.emails.length; i++ ) {
                        if( response.emails[i].type == 'account' ) {
                            response.email = response.emails[i].value;
                        }
                    }

                    var data = {
                        'platform'                  : 'google',
                        'action'                    : 'wppb_sc_handle_login_click',
                        'platform_response'         : response,
                        'wppb_sc_security_token'    : result['access_token'],
                        'wppb_sc_form_ID'           : wppb_sc_form_ID_google
                    };

                    wppbSCLogin( data, wppb_sc_google_data, 'google' );
                } );
            } );
        }
    }
}

jQuery( function() {

    jQuery( '.wppb-logout-url, #wp-admin-bar-logout a' ).click( function() {

        if( ( wppbGetCookie( 'wppb_sc_security_token' ) != '' ) && ( wppbGetCookie( 'wppb_sc_platform_name' ) == 'google' ) ) {

            document.cookie = 'wppb_sc_security_token' + '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
            document.cookie = 'wppb_sc_platform_name' + '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';

            gapi.auth.signOut();
        }
    } );
} );