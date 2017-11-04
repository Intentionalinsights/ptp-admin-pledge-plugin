<?php
/**
 * @package  PTPAdminPledgePlugin
 */
class PTPAdminPledgePluginActivate
{
	public static function activate() {
		flush_rewrite_rules();
	}
}
