<?php

////////////////////////////
/////Regex Patterns
///////////////////////////
$email_pattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{1,}$/";
$index_pattern = "/^[0-9]{1,}$/";
$free_text_pattern = "/^[a-zA-Z0-9 .?!öäüõ]+$/";

function check_regex_of_array($array, $pattern, $required)
{
	foreach ($array as $input) {
		if ($input === "" && !$required || $input === null && !$required) {
			continue;
		}
		if (preg_match($pattern, $input)) {
			continue;
		} else {
			return false;
		};
	}

	return true;
}