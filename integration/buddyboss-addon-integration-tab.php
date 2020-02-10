<?php
/**
 * Compatibility integration admin tab
 *
 * @since BuddyBoss 1.1.5
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Setup Compatibility integration admin tab class.
 *
 * @since BuddyBoss 1.1.5
 */
class MYPLUGIN_BuddyBoss_Admin_Integration_Tab extends BP_Admin_Integration_tab {
	public function is_active() {
		return true;
	}

	/**
	 * Register setting fields
	 */
	public function register_fields() {
		$this->add_section(
			'MYPLUGIN_integration_section',
			__( 'Add-on Settings', 'buddyboss-platform-addon' )
		);
		$this->add_checkbox_field(
			'MYPLUGIN_Integration_Field',
			__( 'Add-on Field', 'buddyboss-platform-addon' ),
			array(
				'input_text'   => __( 'Enable this option', 'buddyboss-platform-addon' ),
			)
		);
	}
}
