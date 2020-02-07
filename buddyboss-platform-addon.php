<?php
/**
 * Plugin Name: BuddyBoss Platform Add-on
 * Plugin URI:  https://buddyboss.com/
 * Description: Example plugin to show developers how to add their own settings into BuddyBoss Platform.
 * Author:      BuddyBoss
 * Author URI:  https://buddyboss.com/
 * Version:     1.0.0
 * Text Domain: buddyboss-platform-addon
 * Domain Path: /languages/
 * License:     GPLv3 or later (license.txt)
 */

/**
 * This file should always remain compatible with the minimum version of
 * PHP supported by WordPress.
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'MYPLUGIN_admin_menus' ) ) {

    function MYPLUGIN_admin_menus() {
	    add_submenu_page(
		    'bp-settings',
		    __( 'Add-on', 'buddyboss' ),
		    __( 'Add-on', 'buddyboss' ),
		    'manage_options',
		    'bp-addon',
		    'MYPLUGIN_screen'
	    );
    }

    add_action( 'bp_init', function() {
	    add_action( bp_core_admin_hook(), 'MYPLUGIN_admin_menus', 99 );
    } );
}

function MYPLUGIN_screen() {
	?>
    <div class="wrap">
        <h2 class="nav-tab-wrapper"><?php bp_core_admin_tabs( __( 'Addon', 'buddyboss' ) ); ?></h2>
        <form action="" method="post">
			<?php
			settings_fields( 'bp-addon' );
			bp_custom_pages_do_settings_sections( 'bp-addon' );

			printf(
				'<p class="submit">
				<input type="submit" name="submit" class="button-primary" value="%s" />
			</p>',
				esc_attr__( 'Save Settings', 'buddyboss' )
			);
			?>
        </form>
    </div>

	<?php
}

function MYPLUGIN_admin_enqueue_script() {
	wp_enqueue_style( 'buddyboss-addon-admin-css', plugin_dir_url( __FILE__ ) . 'style.css' );
}
add_action( 'admin_enqueue_scripts', 'MYPLUGIN_admin_enqueue_script' );

function MYPLUGIN_register_addon_settings() {
	require_once 'buddyboss-addon-admin-setting.php';
}

add_action( 'bp_register_admin_settings', 'MYPLUGIN_register_addon_settings', 99 );

function MYPLUGIN_get_settings_sections() {

	$settings = array(
		'MYPLUGIN_settings_section' => array(
			'page'  => 'addon',
			'title' => __( 'Add-on Settings', 'buddyboss' ),
		),
	);

	return (array) apply_filters( 'MYPLUGIN_get_settings_sections', $settings );
}

function MYPLUGIN_get_settings_fields_for_section( $section_id = '' ) {

	// Bail if section is empty
	if ( empty( $section_id ) ) {
		return false;
	}

	$fields = MYPLUGIN_get_settings_fields();
	$retval = isset( $fields[ $section_id ] ) ? $fields[ $section_id ] : false;

	return (array) apply_filters( 'MYPLUGIN_get_settings_fields_for_section', $retval, $section_id );
}

/**
 * Get all of the settings fields.
 *
 * @since BuddyBoss Platform Add-on 1.0.0
 * @return array
 */
function MYPLUGIN_get_settings_fields() {

	$fields = array();

	$fields['MYPLUGIN_settings_section'] = array(

		'MYPLUGIN_field'  => array(
			'title'             => __( 'My Field' ),
			'callback'          => 'MYPLUGIN_settings_callback_field',
			'sanitize_callback' => 'absint',
			'args'              => array(),
		),

	);

	return (array) apply_filters( 'MYPLUGIN_get_settings_fields', $fields );
}

function MYPLUGIN_settings_callback_field() {
	?>
    <input name="MYPLUGIN_field"
           id="MYPLUGIN_field"
           type="checkbox"
           value="1"
		<?php checked( MYPLUGIN_is_addon_field_enabled() ); ?>
    />
    <label for="MYPLUGIN_field">
		<?php _e( 'Enable my option' ); ?>
    </label>
	<?php
}

function MYPLUGIN_is_addon_field_enabled( $default = 1 ) {
	return (bool) apply_filters( 'MYPLUGIN_is_addon_field_enabled', (bool) get_option( 'MYPLUGIN_field', $default ) );
}

/***************************** Add section in current settings ***************************************/

/**
 * Register fields for settings hooks
 * bp_admin_setting_general_register_fields
 * bp_admin_setting_activity_register_fields
 * bp_admin_setting_friends_register_fields
 * bp_admin_setting_groups_register_fields
 * bp_admin_setting_invites_register_fields
 * bp_admin_setting_media_register_fields
 * bp_admin_setting_messages_register_fields
 * bp_admin_setting_registration_register_fields
 * bp_admin_setting_search_register_fields
 * bp_admin_setting_xprofile_register_fields
 */
add_action( 'bp_admin_setting_general_register_fields', function( $setting ){
	// Main General Settings Section
	$setting->add_section( 'bp_my_addon', __( 'Add-on Settings' ) );

	$args          = array();
	$setting->add_field( 'bp-enable-my-addon', __( 'My Field' ), 'MYPLUGIN_admin_general_setting_callback_my_addon', 'intval', $args );
} );


function MYPLUGIN_admin_general_setting_callback_my_addon() {
	?>
    <input id="bp-enable-my-addon" name="bp-enable-my-addon" type="checkbox" value="1" <?php checked( MYPLUGIN_enable_my_addon() ); ?> />
    <label for="bp-enable-my-addon"><?php _e( 'Enable my option', 'buddyboss' ); ?></label>
    <?php
}

function MYPLUGIN_enable_my_addon( $default = false ) {
	return (bool) apply_filters( 'MYPLUGIN_enable_my_addon', (bool) bp_get_option( 'bp-enable-my-addon', $default ) );
}
