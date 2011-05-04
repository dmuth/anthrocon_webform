<?php
/**
* This file contains functions to query the webform submissions.
*
* @author Douglas Muth <http://www.dmuth.org/>
*
*/


/**
* Query a specific webform's submissions.
*
* @param integer The NID of the webform we're querying.
*
* @return string A giant tab-delimited string of data to download
*/
function anthrocon_webform_query($nid) {

	$retval = "";

	$retval .= anthrocon_webform_query_header($nid);

	return($retval);

} // End of anthrocon_webform_query()


/**
* Get the header for this webform's submissions.
*
* @param integer The NID of the webform we're querying.
*
* @return string A giant tab-delimited string of data to download
*/
function anthrocon_webform_query_header($nid) {

	$retval = "";

	$query = "SELECT "
		. "name "
		. "FROM {webform_component} "
		. "WHERE "
		. "nid='%s' "
		;
	$query_args = array($nid);
	$cursor = db_query($query, $query_args);
	while ($row = db_fetch_array($cursor)) {

		if (!empty($retval)) {
			$retval .= "\t";
		}

		$retval .= $row["name"];

	}

	return($retval);

} // End of anthrocon_webform_query_header()


