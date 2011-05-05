<?php
/**
* This file holds functions relating to processing our result rows.
*
* @author Douglas Muth <http://www.dmuth.org/>
*/


/**
* Process the result set from our query, and turn it into a proper
* array of form submissions.
*
* @param object $cursor Our result cursor from the query.
*
* @return array An array of form submissions.
*/
function anthrocon_webform_query_data_rows($cursor) {

	$retval = array();

	$row = array();
	$old_id = 0;
	while ($result = db_fetch_array($cursor)) {

		$id = $result["sid"];

		//
		// If we have a new SID, put the current row into it.
		//
		if ($id != $old_id) {

			if (!empty($row)) {
				$retval[] = $row;
			}

			$row = array();
			$row["id"] = $result["sid"];
			$row["uid"] = $result["uid"];
			$row["user"] = $result["username"];
			$row["submitted"] = date("r", $result["submitted"]);
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

	//
	// Put the last row onto the set.
	//
	if (!empty($row)) {
		$retval[] = $row;
	}

	return($retval);

} // End of anthrocon_webform_query_data_rows()




