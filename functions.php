<?php
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'MYPLUGIN_admin_enqueue_script' ) ) {
	function MYPLUGIN_admin_enqueue_script() {
		wp_enqueue_style( 'buddyboss-addon-admin-css', plugin_dir_url( __FILE__ ) . 'style.css' );
	}

	add_action( 'admin_enqueue_scripts', 'MYPLUGIN_admin_enqueue_script' );
}

if ( ! function_exists( 'MYPLUGIN_get_settings_sections' ) ) {
	function MYPLUGIN_get_settings_sections() {

		$settings = array(
			'MYPLUGIN_settings_section' => array(
				'page'  => 'addon',
				'title' => __( 'Add-on Settings', 'buddyboss-platform-addon' ),
			),
		);

		return (array) apply_filters( 'MYPLUGIN_get_settings_sections', $settings );
	}
}

if ( ! function_exists( 'MYPLUGIN_get_settings_fields_for_section' ) ) {
	function MYPLUGIN_get_settings_fields_for_section( $section_id = '' ) {

		// Bail if section is empty
		if ( empty( $section_id ) ) {
			return false;
		}

		$fields = MYPLUGIN_get_settings_fields();
		$retval = isset( $fields[ $section_id ] ) ? $fields[ $section_id ] : false;

		return (array) apply_filters( 'MYPLUGIN_get_settings_fields_for_section', $retval, $section_id );
	}
}

if ( ! function_exists( 'MYPLUGIN_get_settings_fields' ) ) {
	function MYPLUGIN_get_settings_fields() {

		$fields = array();

		$fields['MYPLUGIN_settings_section'] = array(

			'MYPLUGIN_field' => array(
				'title'             => __( 'Add-on Field', 'buddyboss-platform-addon' ),
				'callback'          => 'MYPLUGIN_settings_callback_field',
				'sanitize_callback' => 'absint',
				'args'              => array(),
			),

		);

		return (array) apply_filters( 'MYPLUGIN_get_settings_fields', $fields );
	}
}

if ( ! function_exists( 'MYPLUGIN_settings_callback_field' ) ) {
	function MYPLUGIN_settings_callback_field() {
		?>
        <input name="MYPLUGIN_field"
               id="MYPLUGIN_field"
               type="checkbox"
               value="1"
			<?php checked( MYPLUGIN_is_addon_field_enabled() ); ?>
        />
        <label for="MYPLUGIN_field">
			<?php _e( 'Enable this option', 'buddyboss-platform-addon' ); ?>
        </label>
		<?php
	}
}

if ( ! function_exists( 'MYPLUGIN_is_addon_field_enabled' ) ) {
	function MYPLUGIN_is_addon_field_enabled( $default = 1 ) {
		return (bool) apply_filters( 'MYPLUGIN_is_addon_field_enabled', (bool) get_option( 'MYPLUGIN_field', $default ) );
	}
}

/***************************** Add section in current settings ***************************************/

/**
 * Register fields for settings hooks
 * bp_admin_setting_general_register_fields
 * bp_admin_setting_xprofile_register_fields
 * bp_admin_setting_groups_register_fields
 * bp_admin_setting_forums_register_fields
 * bp_admin_setting_activity_register_fields
 * bp_admin_setting_forums_register_fields
 * bp_admin_setting_media_register_fields
 * bp_admin_setting_friends_register_fields
 * bp_admin_setting_invites_register_fields
 * bp_admin_setting_search_register_fields
 */
if ( ! function_exists( 'MYPLUGIN_bp_admin_setting_general_register_fields' ) ) {
    function MYPLUGIN_bp_admin_setting_general_register_fields( $setting ) {
	    // Main General Settings Section
	    $setting->add_section( 'MYPLUGIN_addon', __( 'Add-on Settings', 'buddyboss-platform-addon' ) );

	    $args          = array();
	    $setting->add_field( 'bp-enable-my-addon', __( 'My Field', 'buddyboss-platform-addon' ), 'MYPLUGIN_admin_general_setting_callback_my_addon', 'intval', $args );
    }

	add_action( 'bp_admin_setting_general_register_fields', 'MYPLUGIN_bp_admin_setting_general_register_fields' );
}

if ( ! function_exists( 'MYPLUGIN_admin_general_setting_callback_my_addon' ) ) {
	function MYPLUGIN_admin_general_setting_callback_my_addon() {
		?>
        <input id="bp-enable-my-addon" name="bp-enable-my-addon" type="checkbox"
               value="1" <?php checked( MYPLUGIN_enable_my_addon() ); ?> />
        <label for="bp-enable-my-addon"><?php _e( 'Enable my option', 'buddyboss-platform-addon' ); ?></label>
		<?php
	}
}

if ( ! function_exists( 'MYPLUGIN_enable_my_addon' ) ) {
	function MYPLUGIN_enable_my_addon( $default = false ) {
		return (bool) apply_filters( 'MYPLUGIN_enable_my_addon', (bool) bp_get_option( 'bp-enable-my-addon', $default ) );
	}
}

if ( ! function_exists( 'MYPLUGIN_modify_plugin_action_links' ) ) {
	function MYPLUGIN_modify_plugin_action_links( $links, $file ) {

		// Return normal links if not BuddyPress.
		if ( MYPLUGIN_BB_ADDON_PLUGIN_BASENAME != $file ) {
			return $links;
		}

		// Add a few links to the existing links array.
		return array_merge(
			$links,
			array(
				'settings' => '<a href="' . esc_url( bp_get_admin_url( 'admin.php?page=bp-settings&tab=bp-addon' ) ) . '">' . __( 'Settings', 'buddyboss-platform-addon' ) . '</a>',
			)
		);
	}

// Add link to settings page.
	add_filter( 'plugin_action_links', 'MYPLUGIN_modify_plugin_action_links', 10, 2 );
	add_filter( 'network_admin_plugin_action_links', 'MYPLUGIN_modify_plugin_action_links', 10, 2 );
}


/**************************************** MY PLUGIN INTEGRATION ************************************/

/**
 * Set up the my plugin integration.
 */
function MYPLUGIN_register_integration() {
	require_once dirname( __FILE__ ) . '/integration/buddyboss-integration.php';
	buddypress()->integrations['addon'] = new MYPLUGIN_BuddyBoss_Integration();
}
add_action( 'bp_setup_integrations', 'MYPLUGIN_register_integration' );
