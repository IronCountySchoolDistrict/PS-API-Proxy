<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/token.php';
require_once __DIR__ . '/service.php';

$app = new Silex\Application();

$dbe = $app['controllers_factory'];

$dbe->get('/', function () use ($app) {
	return "hi from silex";
});

$dbe->get('/schema/search/{table_name}/{query_string}', function ($table_name, $query_string) use ($app) {
	$token = getToken();
	$student_info = search($token, $table_name, $query_string);
	return $student_info;
});

$dbe->get('/schema/{table_name}/{id}', function ($table_name, $id) use ($app) {
	error_log('in route');
	$token = getToken();
	$student_info = get($token, $table_name, $id);
	return $student_info;
});

$dbe->put('/schema/{table_name}/{id}', function ($table_name, $id) use ($app) {
	$token = getToken();
	$student_info = update($token, $table_name, $id);
	return $student_info;
});

$dbe->post('/schema/{table_name}', function ($table_name) use ($app) {
	$token = getToken();
	$student_info = create($token, $table_name);
	return $student_info;
});

$dbe->delete('/schema/{table_name}/{id}', function ($table_name, $id) use ($app) {
	$token = getToken();
	$student_info = delete($token, $table_name, $id);
	return $student_info;
});

$dbe->post('/student', function () use ($app) {
	$token = getToken();
	$student_info = update_student($token);
	return $student_info;
});

$app->match("{url}", function ($url) use ($app) {
	return "OK";}
)->assert('url', '.*')->method("OPTIONS");

$app->mount('/api', $dbe);
$app->run();