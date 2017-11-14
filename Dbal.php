<?php

namespace MyDoctrine;

use Doctrine\Common\ClassLoader;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

Class Dbal
{

    public function getConnection()
    {


        $classLoader = new ClassLoader('Doctrine', 'vendor/doctrine/common');
        $classLoader->register();

        $config = new Configuration();

        $connParams = array(
            "dbname" => "rest_api",
            "user" => "root",
            "password" => "",
            "host" => "localhost",
            "driver" => "pdo_mysql"
        );

        $conn = DriverManager::getConnection($connParams, $config);

        return $conn;

    }

}
