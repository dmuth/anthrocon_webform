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

	$retval .= anthrocon_webform_query_data($nid);

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
		. "name, cid, type "
		. "FROM {webform_component} "
		. "WHERE "
		. "nid='%s' "
		. "AND type != 'fieldset' "
		. "ORDER by cid "
		;
	$query_args = array($nid);
	$cursor = db_query($query, $query_args);
	while ($row = db_fetch_array($cursor)) {

		//print "<pre>"; print_r($row); print "</pre>"; // Debugging

		if (!empty($retval)) {
			$retval .= "\t";
		}

		$retval .= $row["name"];

	}

	$retval .= "\r\n";

	return($retval);

} // End of anthrocon_webform_query_header()


/**
* Get the submissions for this webform.
*
* @param integer The NID of the webform we're querying.
*
* @return string Multiple tab-delimited lines of submissions.
*/
function anthrocon_webform_query_data($nid) {

	$retval = "";

	$query = "SELECT "
		. "s.sid, s.uid, u.name AS username, "
		. "data.cid, data.no, c.name AS c_name, "
		. "c.type AS c_type, "
		. "data.data, data.cid, "
		. "s.submitted, s.remote_addr "
		. "FROM {webform_submissions} AS s "
		. "JOIN {webform_submitted_data} AS data "
			. "ON s.sid = data.sid "
		. "JOIN {webform_component} AS c "
			. "ON data.nid = c.nid AND data.cid = c.cid "
		. "JOIN {users} AS u "
			. "ON s.uid = u.uid "
		. "WHERE "
		. "s.nid = '%s' "
		. "ORDER by s.sid, data.cid, data.no "
		//. "LIMIT 100 " // Debugging
		;
	$query_args = array($nid);
	$cursor = db_query($query, $query_args);
	$rows = anthrocon_webform_query_data_rows($cursor);

	print "<pre>"; print_r($rows); print "</pre>"; // Debugging

/*
TODO:
Set $retval to text output
	$retval = anthrocon_webform_query_data_text($rows);
*/

	return($retval);

} // End of anthrocon_webform_query_data()


