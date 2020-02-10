<?php
/**
 * BuddyBoss Compatibility Integration Class.
 *
 * @since BuddyBoss 1.1.5
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Setup the bp compatibility class.
 *
 * @since BuddyBoss 1.1.5
 */
class MYPLUGIN_BuddyBoss_Integration extends BP_Integration {

	public function __construct() {
		$this->start(
			'add-on',
			__( 'Add-on', 'buddyboss-platform-addon' ),
			'add-on',
			array(
				'required_plugin' => array(),
			)
		);
	}

	/**
	 * Register admin integration tab
	 */
	public function setup_admin_integration_tab() {

		require_once 'buddyboss-addon-integration-tab.php';

		new MYPLUGIN_BuddyBoss_Admin_Integration_Tab(
			"bp-{$this->id}",
			$this->name,
			array(
				'root_path'       => MYPLUGIN_BB_ADDON_PLUGIN_PATH . '/integration',
				'root_url'        => MYPLUGIN_BB_ADDON_PLUGIN_URL . '/integration',
				'required_plugin' => $this->required_plugin,
			)
		);
	}
}
