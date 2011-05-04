<?php
/**
* Hold code that relates to forms.
*
* @author Douglas Muth <http://www.dmuth.org/>
*/


/**
* Generate our form for viewing/downloading dealer tables.
*/
function anthrocon_webform_form() {

	$retval = array();

	//unset($GLOBALS["user"]->ac_webform); // Debugging
	$defaults = $GLOBALS["user"]->ac_webform;

	$retval["forms"] = array(
		"#type" => "fieldset",
		"#title" => t("Webform Download"),
		);

	$options = anthrocon_webform_get_webforms();

	$retval["forms"]["form"] = array(
		"#type" => "select",
		"#options" => $options,
		"#default_value" => $defaults["nid"],
		"#description" => t("Select a form to download submissions from!"),
		);

	$retval["forms"]["download"] = array(
		"#type" => "submit",
		"#value" => t("Download This List"),
		);

	$retval["forms"]["browser_dump"] = array(
		"#type" => "checkbox",
		"#default_value" => $defaults["dump"],
		"#title" => t("Dump to browser instead. (for testing/debugging purposes)"),
		);

	return($retval);

} // End of anthrocon_webform_form()


/**
* Our form submission handler.
*/
function anthrocon_webform_form_submit($form, $form_state) {

	//print "<pre>"; print_r($form_state); print "</pre>"; // Debugging
	$values = $form_state["values"];
	$nid = $values["form"];
	$dump = $values["browser_dump"];

	//
	// Save our defaults
	//
	$params = array();
	$params["ac_webform"]["nid"] = $nid;
	$params["ac_webform"]["dump"] = $dump;
	user_save($GLOBALS["user"], $params);

	//
	// Get the results from our webform
	//
	$text = anthrocon_webform_query($nid);
	
	if (!$dump) {
		//
		// Start a download in the user's browser
		//
	
		$node = node_load($nid);
		$filename = anthrocon_webform_get_filename($node);
		//print $filename;exit(); // Debugging

		$header = "Content-Type: octet/stream";
		drupal_set_header($header);

		$header = "Content-Disposition: attachment; filename=\"$filename\"";
		drupal_set_header($header);

	} else {
		//
		// We're dumping to the browser. Formatting time!
		//
		print "<pre>";

	}

	//
	// And print up our text!
	//
	print $text;

	//
	// Not sure if I have to put an exit() call here or not.
	// For now, this seems to be working without any issues in Google Chrome.
	//

} // End of anthrocon_webform_submit()


