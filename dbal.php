<?php
use Doctrine\Common\ClassLoader;

$classLoader = new ClassLoader('Doctrine','/vendor/doctrine/common');
$classLoader->register();


$config = new \Doctrine\DBAL\Configuration();

$connParams = array(
	"dbname" 	=> "rest_api",
	"user"		=> "root",
	"password"	=> "",
	"host"		=> "localhost",
	"driver"	=> "pdo_mysql"
);//you can use this style or

//use this url style param

// $param = array(
// 	"url" => 'mysql://user:pass@localhost/dbname'
// );

$conn = \Doctrine\DBAL\DriverManager::getConnection($connParams, $config);

// use Doctirne\DBAL\DriverManager;
// $conn = DriverManager::getConnection($connParams,$config);
?>