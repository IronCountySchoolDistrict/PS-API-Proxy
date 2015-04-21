<?php

/**
 * @return array {array}
 */
function getApiToken() {
	$powerSchoolUrl = 'https://ps.irondistrict.org/oauth/access_token';

	$keyArr = readKeyFile();
	$clientId = $keyArr['clientId'];
	$clientSecret = $keyArr['clientSecret'];
	$token = $clientId . ':' . $clientSecret;
	$b64Token = base64_encode($token);

	$postHeaders = array(
		'Authorization: Basic ' . $b64Token,
		'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
	);

	$postPayload = array(
		'grant_type' => 'client_credentials',
	);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $powerSchoolUrl);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postPayload));
	curl_setopt($ch, CURLOPT_HTTPHEADER, $postHeaders);
	curl_setopt($ch, CURLOPT_VERBOSE, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_STDERR, fopen('php://stderr', 'w'));

	$output = curl_exec($ch);
	if (curl_errno($ch)) {
		echo 'error:' . curl_error($ch);
	}

	curl_close($ch);
	$outJson = json_decode($output, true);
	$expDateTime = new DateTime();
	$expDateTime->add(new DateInterval('PT' . $outJson['expires_in'] . 'S'));
	$outJson['expireDateTime'] = $expDateTime->format('Y-m-d H:i:s');

	return $outJson;
}

function createTokenFile() {
	$tokenFile = 'access-token.json';
	$handle = fopen($tokenFile, 'w') or error_log('Cannot create file: ' . $tokenFile);
}

/**
 * Return token file if it exists.
 * Return false if token file does not exist.
 * @return array|bool
 */
function readTokenFile() {
	$tokenFile = 'access-token.json';
	if (!file_exists($tokenFile)) {
		return false;
	}
	$handle = fopen($tokenFile, 'r');
	$data = fread($handle, filesize($tokenFile));
	$jsonData = json_decode($data, true);
	return $jsonData;
}

/**
 * Return key file if it exists.
 * Return false if key file does not exist.
 * @return array|bool
 */
function readKeyFile() {
	$keyFile = 'key.json';
	if (!file_exists($keyFile)) {
		return false;
	}
	$handle = fopen($keyFile, 'r');
	$data = fread($handle, filesize($keyFile));
	$jsonData = json_decode($data, true);
	return $jsonData;
}

/**
 * @param $token {array}
 */
function setTokenFile($token) {
	$tokenFile = 'access-token.json';
	if (file_exists($tokenFile)) {
		$handle = fopen($tokenFile, 'w') or error_log('Cannot open file: ' . $tokenFile);
		fwrite($handle, json_encode($token));
	}

}

/**
 * @param token {array}
 * @return bool
 */

function checkTokenExpiration($token) {
	$dateTimeNow = new DateTime('now');
	ob_start();
	var_dump($token);
	$token_dump = ob_get_clean();
	$expireDateTime = new DateTime($token['expireDateTime']);
	// If expiration date is less than now, token has expired
	if ($expireDateTime->format('U') < $dateTimeNow->format('U')) {
		return false;
	}
	return true;
}

/**
 * Get token and check if it has expired.
 * @return array
 */
function getToken() {
	if (!readTokenFile()) {
		// generate and set new token file
		createTokenFile();
		$tokenArr = getApiToken();
		setTokenFile($tokenArr);
	}

	// token file is definitely there at this point, so read it again.
	$tokenArr = readTokenFile();

	if (!checkTokenExpiration($tokenArr)) {
		$newToken = getApiToken();
		setTokenFile(json_encode($newToken));
		$tokenArr = $newToken;
	}
	return $tokenArr;
}