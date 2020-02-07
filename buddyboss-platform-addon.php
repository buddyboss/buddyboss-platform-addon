<?php
/**
 * Plugin Name: BuddyBoss Platform Addon
 * Plugin URI:  https://buddyboss.com/
 * Description: The BuddyBoss Platform Addon
 * Author:      BuddyBoss
 * Author URI:  https://buddyboss.com/
 * Version:     1.0.0
 * Text Domain: buddyboss-platform-addon
 * Domain Path: /languages/
 * License:     GPLv2 or later (license.txt)
 */

/**
 * This files should always remain compatible with the minimum version of
 * PHP supported by WordPress.
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'buddyboss_addon_admin_menus' ) ) {

    function buddyboss_addon_admin_menus() {
	    add_submenu_page(
		    'bp-settings',
		    __( 'Add On', 'buddyboss' ),
		    __( 'Add On', 'buddyboss' ),
		    'manage_options',
		    'bp-addon',
		    'buddyboss_addon_screen'
	    );
    }

    add_action( 'bp_init', function() {
	    add_action( bp_core_admin_hook(), 'buddyboss_addon_admin_menus', 99 );
    } );
}

function buddyboss_addon_screen() {
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

function buddyboss_addon_register_addon_settings() {
	require_once 'bp-addon-admin-setting.php';
}

add_action( 'bp_register_admin_settings', 'buddyboss_addon_register_addon_settings', 99 );

function buddyboss_addon_get_settings_sections() {

	$settings = array(
		'buddyboss_addon_settings_section' => array(
			'page'  => 'addon',
			'title' => __( 'Add On Settings', 'buddyboss' ),
		),
	);

	return (array) apply_filters( 'buddyboss_addon_get_settings_sections', $settings );
}

function buddyboss_addon_get_settings_fields_for_section( $section_id = '' ) {

	// Bail if section is empty
	if ( empty( $section_id ) ) {
		return false;
	}

	$fields = buddyboss_addon_get_settings_fields();
	$retval = isset( $fields[ $section_id ] ) ? $fields[ $section_id ] : false;

	return (array) apply_filters( 'buddyboss_addon_get_settings_fields_for_section', $retval, $section_id );
}

/**
 * Get all of the settings fields.
 *
 * @since BuddyBoss 1.0.0
 * @return array
 */
function buddyboss_addon_get_settings_fields() {

	$fields = array();

	$fields['buddyboss_addon_settings_section'] = array(

		'buddyboss_addon_field'  => array(
			'title'             => __( 'Add On Field' ),
			'callback'          => 'buddyboss_addon_settings_callback_field',
			'sanitize_callback' => 'absint',
			'args'              => array(),
		),

	);

	return (array) apply_filters( 'buddyboss_addon_get_settings_fields', $fields );
}

function buddyboss_addon_settings_callback_field() {
	?>
    <input name="buddyboss_addon_field"
           id="buddyboss_addon_field"
           type="checkbox"
           value="1"
		<?php checked( buddyboss_addon_is_addon_field_enabled() ); ?>
    />
    <label for="buddyboss_addon_field">
		<?php _e( 'Allow add on field' ); ?>
    </label>
	<?php
}

function buddyboss_addon_is_addon_field_enabled( $default = 1 ) {
	return (bool) apply_filters( 'buddyboss_addon_is_addon_field_enabled', (bool) get_option( 'buddyboss_addon_field', $default ) );
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
	$setting->add_section( 'bp_my_addon', __( 'My add on Settings' ) );

	$args          = array();
	$setting->add_field( 'bp-enable-my-addon', __( 'My add on' ), 'buddyboss_addon_admin_general_setting_callback_my_addon', 'intval', $args );
} );


function buddyboss_addon_admin_general_setting_callback_my_addon() {
	?>
    <input id="bp-enable-my-addon" name="bp-enable-my-addon" type="checkbox" value="1" <?php checked( buddyboss_addon_enable_my_addon() ); ?> />
    <label for="bp-enable-my-addon"><?php _e( 'Allow my add on setting', 'buddyboss' ); ?></label>
    <?php
}

function buddyboss_addon_enable_my_addon( $default = false ) {
	return (bool) apply_filters( 'buddyboss_addon_enable_my_addon', (bool) bp_get_option( 'bp-enable-my-addon', $default ) );
}
