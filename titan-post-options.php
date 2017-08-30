<?php
// Initialize options, called only when Titan Framework exists.
function create_wordpress_leiki_api_plugins_options() {
    $titan = TitanFramework::getInstance( 'wp-leiki-api' );

    // Creates the Admin panel.
    $admin_panel = $titan->createAdminPanel( array(
        'name' => 'Leiki API',
        'id' => 'leiki-api',
        'parent' => 'options-general.php',
    ) );
    // Allows text entry.

    $admin_panel->createOption( array(
        'name' => 'API Key',
        'id' => 'api_key',
        'type' => 'text',
        'desc' => 'Enter leiki API key. If you do not have one, please visit <a href="https://www.leiki.com/register-for-api-key" target="_blank">https://www.leiki.com/register-for-api-key</a> and signup for your key. It might take 2 days to get activated!'
    ) );
    // Save or reset.
    $admin_panel->createOption( array(
        'type' => 'save',
    ) );



    $panel = $titan->createMetaBox( array(
        'name' => 'Leiki API',
        'post_type' => ['post']
    ) );
    $panel->createOption( array(
        'name' => 'Blog Post URL',
        'id' => 'blog_post_url',
        'size' => 'large',
        'default' => 'http://plug.direct/audience-data-strip-miners-are-failing-to-deliver-local-advertising-markets/',
        'placeholder' => 'http://'
    ) );
    $panel->createOption( array(
        'name' => '&nbsp;',
        'type' => 'custom',
        'custom' => '<input type="button" name="get_leiki_data" id="get_leiki_data" value="Get Leiki Data" class="button button-primary button-large">',
    ) );
    $panel->createOption( array(
        'name' => 'Leiki Data(Leiki High Def)',
        'id' => 'leiki_preview_content_focus100k',
        'type' => 'textarea'
    ) );
    $panel->createOption( array(
        'name' => 'Leiki Data(IAB Tier 2)',
        'id' => 'leiki_preview_content_iabtier2',
        'type' => 'textarea'
    ) );
    
}
add_action( 'tf_create_options', 'create_wordpress_leiki_api_plugins_options' );