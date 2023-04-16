<?php
function db_connection() {
	$env = parse_ini_file('.env');

	$host = $env["PMA_HOST"];
	$db_name = $env["DB_NAME"];
	$db_user = $env["DB_USER"];
	$db_password = $env["DB_PASSWORD"];

	$dsn = "mysql:host=$host;dbname=$db_name;charset=UTF8";

	try {
		return new PDO($dsn, $db_user, $db_password);
	} catch (PDOException $e) {
		die("Error: ".$e->getMessage());
	}
}

?>