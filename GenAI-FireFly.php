<?php
/*
Plugin Name: GenAI Plugin
Description: A simple plugin that generates featured Post images using Adobe FireFly APIs"
*/

ini_set("display_errors","On");

function my_prefix_add_admin_menus() 
{
	add_menu_page(
		'GenAI-Media', /* Page title */
		'GenAI-Media', /* Menu title */
		'manage_options', /* Capability */
		'gen-ai-admin-menu', /* Unique Menu slug */
		'my_prefix_add_menu_page_callback', /* Callback */
		'dashicons-admin-generic', /* Icon */
		5 /* Position 1 (very top because why not) */
	);

    add_submenu_page(
		'gen-ai-admin-menu',
		'Settings',
		'Settings',
		'manage_options',
		'gen-ai-settings',
		'gen_ai_settings_callback'
	);
}

add_action( 'admin_menu', 'my_prefix_add_admin_menus' );

/**
 * Callback function to render the menu page.
 */
function my_prefix_add_menu_page_callback()
{
	?>
	<div class="wrap">
		<h1>Generate Featured Image</h1>
        <br>
        <input type="text" name="type prompt" style="width:600px" placeholder="Enter Prompt" />
        <br> <br>
        <select name="blog-post" style="width:600px">
              <option>Select Blog Post</option>
        </select>
        <br> <br> <br>
        <input type="submit" name="runAPI" value="Generate Featured Image" />


	</div>
	<?php
}

/**
 * Callback function to render the movie settings page.
 */
function gen_ai_settings_callback() {
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Settings', 'my-prefix' ); ?></h1>
		<p>Enter API Credentials</p>
        <input type="text" name="key" style="width:400px" placeholder="Enter API key" />
        <input type="text" name="secret" style="width:400px" placeholder="Enter API Secret" />
        <input type="submit" name="Save Settings" value="Save Settings" />
	</div>
	<?php
}

?>
