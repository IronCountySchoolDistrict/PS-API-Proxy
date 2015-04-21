<?php

$API_URI = 'https://ps.irondistrict.org';

/**
 * @param $token
 * @param $table_name
 * @param $query_string
 * @return string
 */
function search($token, $table_name, $query_string) {

	$getHeaders = array(
		'Authorization: Bearer ' . $token['access_token'],
		'Accept: application/json',
	);

	$getUrl = $GLOBALS['API_URI'] . "/ws/schema/table/$table_name?q=$query_string&projection=*";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $getUrl);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $getHeaders);
	curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_STDERR, fopen('php://stderr', 'w'));
	$output = curl_exec($ch);
	curl_close($ch);
	return $output;
}

/**
 * @param $token
 * @param $table_name
 * @param $id
 * @return string
 */
function get($token, $table_name, $id) {

	$getHeaders = array(
		'Authorization: Bearer ' . $token['access_token'],
		'Accept: application/json',
	);

	$getUrl = $GLOBALS['API_URI'] . "/ws/schema/table/$table_name/$id?projection=*";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $getUrl);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $getHeaders);
	curl_setopt($ch, CURLOPT_VERBOSE, TRUE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_STDERR, fopen('php://stderr', 'w'));
	$output = curl_exec($ch);
	curl_close($ch);
	return $output;
}

/**
 * @param $token
 * @param $table_name
 * @param $record_id
 * @uses $_POST global directly
 * @return mixed
 */
function update($token, $table_name, $record_id) {
	$put_input = json_decode(file_get_contents("php://input"));
	$put_input_string = json_encode($put_input);
	$request_headers = array(
		'Authorization: Bearer ' . $token['access_token'],
		'Content-Type: application/json',
		'Accept:application/json',
	);

	$url = $GLOBALS['API_URI'] . "/ws/schema/table/$table_name/$record_id";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
	curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $put_input_string);
	curl_setopt($ch, CURLOPT_VERBOSE, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_STDERR, fopen('php://stderr', 'w'));

	$output = curl_exec($ch);
	curl_close($ch);
	return $output;
}

/**
 * @param $token
 * @param $table_name
 * @param $record_id
 * @uses $_POST global directly
 * @return mixed
 */
function update_student($token) {
	$post_input = json_decode(file_get_contents("php://input"));
	$post_input_string = json_encode($post_input);
	$request_headers = array(
		'Authorization: Bearer ' . $token['access_token'],
		'Content-Type: application/json',
		'Accept:application/json',
	);

	$url = $GLOBALS['API_URI'] . "/ws/v1/student";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_input_string);
	curl_setopt($ch, CURLOPT_VERBOSE, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_STDERR, fopen('php://stderr', 'w'));

	$output = curl_exec($ch);
	curl_close($ch);
	return $output;
}

function create($token, $table_name) {
	$post_input = json_decode(file_get_contents("php://input"));
	$post_input_string = json_encode($post_input);
	$request_headers = array(
		'Authorization: Bearer ' . $token['access_token'],
		'Content-Type: application/json',
		'Accept:application/json',
	);

	$url = $GLOBALS['API_URI'] . "/ws/schema/table/$table_name";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_input_string);
	curl_setopt($ch, CURLOPT_VERBOSE, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_STDERR, fopen('php://stderr', 'w'));

	$output = curl_exec($ch);
	curl_close($ch);
	return $output;
}

function delete($token, $table_name, $record_id) {
	$request_headers = array(
		'Authorization: Bearer ' . $token['access_token'],
		'Content-Type: application/json',
		'Accept:application/json',
	);

	$url = $GLOBALS['API_URI'] . "/ws/schema/table/$table_name/$record_id";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
	curl_setopt($ch, CURLOPT_VERBOSE, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_STDERR, fopen('php://stderr', 'w'));

	$output = curl_exec($ch);
	curl_close($ch);
	return $output;
}