<?php function connect_db() {
	$server = 'localhost'; 
	$user = 'root';
	$pass = 'root';
	$database = 'baseblink';
	$connection = new mysqli($server, $user, $pass, $database);

	return $connection;
}
?>