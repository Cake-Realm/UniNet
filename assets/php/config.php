<?php
try
{
	$conn = new PDO("mysql:host=localhost;dbname=universi_main", "universi_admin", 'aPVNT6aRX]zo');
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
	 die("Connection failed: " . $e->getMessage());
}
session_start();
define("USER", 1);
define("ADMIN", 2);
define("MODERATOR", 3);
?>