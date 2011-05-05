<?php
/**
* This file holds functions that relate to turning the data into text.
*
* @author Douglas Muth <http://www.dmuth.org/>
*/


/**
* Turn our rows into text.
*
* @param array $rows Our array of submissions
*
* @return string Tab-delimited text.
*/
function anthrocon_webform_query_data_text($rows) {

	$retval = "";

	foreach ($rows as $key => $value) {

		$line = "";

		foreach ($value as $key2 => $value2) {

			if (!empty($line)) {
				$line .= "\t";
			}

			$value2 = preg_replace("/[\t\r\n]/", " ", $value2);

			$line .= $value2;

		}

		$line .= "\r\n";
		$retval .= $line;

	}

	return($retval);

} // End of anthrocon_webform_query_data_text()



