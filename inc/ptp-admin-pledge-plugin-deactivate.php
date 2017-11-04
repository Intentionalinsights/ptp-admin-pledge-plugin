<?php
/**
 * @package  PTPAdminPledgePlugin
 */
class PTPAdminPledgePluginDeactivate
{
	public static function deactivate() {
		flush_rewrite_rules();
	}
}