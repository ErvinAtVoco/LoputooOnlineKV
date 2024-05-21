<?php

function get_json() {
	$request = file_get_contents(__DIR__ . '/eesti.json');
	$data = json_decode($request);
	
	
	wp_send_json($data);

	wp_die();
}
