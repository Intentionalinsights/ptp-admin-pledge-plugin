<?php
/**
* Description: Manages the Pro Truth Pledge form data
* Version: 1.0
* Author: Pro-Truth Pledge developers
* Author URI: protruthpledge.org
*/
class PTPAdminPledgePluginActivate
{
    public static function activate() {
        flush_rewrite_rules();
    }
}
