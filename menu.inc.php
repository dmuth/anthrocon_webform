<?php
/**
* Functions relating to our menu and permissions.
*
* @author Douglas Muth <http://www.dmuth.org/>
*/

/**
* Return an array of permissions for this module.
*/
function anthrocon_webform_perm() {
	$retval = array("view webform submissions");
	return($retval);
}


/**
* Return an array of menu options.
*/
function anthrocon_webform_menu() {

	$retval = array();

	$retval["admin/ac/webform"] = array(
		"title" => "View Webforms",
		"page callback" => "anthrocon_webform_main",
		"access arguments" => array("view webform submissions"),
		);

	return($retval);

} // End of anthrocon_webform_menu()



