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
		. "s.sid, data.cid, data.no, c.name AS c_name, "
		. "c.type AS c_type, "
		. "data.data, data.cid, "
		. "s.submitted, s.remote_addr "
		. "FROM {webform_submissions} AS s "
		. "JOIN {webform_submitted_data} AS data "
			. "ON s.sid = data.sid "
		. "JOIN {webform_component} AS c "
			. "ON data.nid = c.nid AND data.cid = c.cid "
		. "WHERE "
		. "s.nid = '%s' "
		. "ORDER by s.sid, data.cid, data.no "
		//. "LIMIT 100 " // Debugging
		;
	$query_args = array($nid);
	$cursor = db_query($query, $query_args);

	//
	// We're going to build a state machine here, which will keep track
	// of the current registration, store it in $row as a temporary home,
	// and eventually place it in $rows.
	//
	$rows = array();
	$row = array();
	$old_id = 0;
	while ($result = db_fetch_array($cursor)) {

		$id = $result["sid"];

		//
		// If we have a new SID, put the current row into it.
		//
		if ($id != $old_id) {

			if (!empty($row)) {
				$rows[] = $row;
			}

			$row = array();
			$row["submitted"] = $result["submitted"];
			$row["remote_addr"] = $result["remote_addr"];

			$old_id = $id;

		}

		//print "<pre>"; print_r($result); print "</pre>"; // Debugging

		$name = $result["c_name"];
		$type = $result["c_type"];
		$data = $result["data"];

		if ($type == "date") {
			//
			// Dates have multiple pieces which are glued together.
			//
			if (!empty($row[$name])) {
				$row[$name] .= "-";
			}

			$row[$name] .= $data;

		} else if ($type == "select") {
			//
			// A select can have more than one option.
			//
			if (!empty($row[$name])) {
				$row[$name] .= ",";
			}

			$row[$name] .= $data;

		} else {
			//
			// Default behavior, just store this in the array.
			//
			$row[$name] = $data;

		}

	}

	print "<pre>"; print_r($rows); print "</pre>"; // Debugging

/*
TODO: 
Refactor state machine loop into a separate array
Set $retval to text output
*/

	return($retval);

} // End of anthrocon_webform_query_data()


