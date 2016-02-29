<?php
require 'db.php';
require_once "../Slim/Slim.php";
include_once('../geoPHP/geoPHP.inc');
Slim\Slim::registerAutoloader ();

$app = new \Slim\Slim (); // slim run-time object

$app->get('/data','getData');
$app->post('/linestring(/:id)','postLinestring');

function getData()
{
	$sql = "SELECT * FROM potluck";
	try {
		$db = getDB();
		$stmt = pg_query($db, $sql);
		$res = pg_fetch_all($stmt);
		$db = null;
		echo '{"res": ' . json_encode($res) . '}';
	} 
	
	catch(PDOException $e) {
		//error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

function postLinestring($body)
{
	$geom = geoPHP::load("GEOMETRYCOLLECTION(LINESTRING($body))");
	$insert_string = pg_escape_bytea($geom->out('ewkb'));
	$sql = "INSERT INTO PATHS (geom) values (GeomFromWKB('$insert_string'))";
	try {
		$db = getDB();
		$stmt =   pg_query($db, $sql);
		$db = null;
	} 
	
	catch(PDOException $e) {
		//error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

// $app->map ( "/users/(:id)", function ($id=-1) use ($app){
// 	if ($id==-1)
// 		$app->response->write("list of users");
// 	else
// 		$app->response->write("user $id");
	
// } )->via( "GET");

$app->run ();
?>