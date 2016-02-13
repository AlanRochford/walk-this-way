<?php
function getDB() {
$dbhost="fyp-walkthiswau.cloudapp.net";
$dbuser="root";
$dbpass="Rochie12";
$dbname="events";


$dbConnection = pg_connect("host=$dbhost dbname=$dbname user=$dbuser password=$dbpass")
or die("Can't connect to database".pg_last_error());

echo("She's Connecting anyway");

return $dbConnection;
}
?>