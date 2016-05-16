<?php
add_filter('gform_rich_text_editor_options', function($editor_settings) {
    $editor_settings['media_buttons'] = true;
    return $editor_settings;
});

add_filter( 'gform_submit_button_1', function($button_input, $form) {
    return '<button id="gform_submit_button_1" class="gform_button button token-fee-button" tabindex="9" onclick="if(window[\'gf_submitting_1\']){return false;}  if( !jQuery(\'#gform_1\')[0].checkValidity || jQuery(\'#gform_1\')[0].checkValidity()){window[\'gf_submitting_1\']=true;}" onkeypress="if(window[\'gf_submitting_1\']){return false;}  if( !jQuery(\'#gform_1\')[0].checkValidity || jQuery(\'#gform_1\')[0].checkValidity()){window[\'gf_submitting_1\']=true;}"><span>Submit</span><span></span><span>1 Token</span></button>';
}, 10, 2 );

// Number of tokens owned by logged in agent, are added to the Submit Article form, so that the form could be hidden
// if agent doesn't have enough tokens for the contribution fee.
if (function_exists('Backfeed\get_current_agent_tokens'))
    add_filter('gform_field_value_tokensOfUser', 'Backfeed\get_current_agent_tokens');

// Update the token values in the UI upon submission of the Submit Article form.
// Assuming the contribution fee is 1.
add_action('gform_after_submission_1', function() {
    ?><script>
        Array.from(document.getElementsByClassName('backfeed-stat-tokens-value')).forEach(function(element) {
            element.textContent -= 1;
        });
    </script><?php
});