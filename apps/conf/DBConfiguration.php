<?php
/**
 * Created by PhpStorm.
 * User: Abnet Kebede
 * Date: 3/1/2021
 * Time: 11:46 AM
 */
namespace Application\conf;

class DBConfiguration {

    public static $conf = [
        "MySql" => [
            "first" => [
                "DB_NAME" => "examination",
                "DB_USERNAME" => "feysel",
                "DB_PASSWORD" => "root",
                "HOST_ADDRESS" => "192.168.1.23"
            ],
            "third" => [
                "DB_NAME" => "ipdc_intranet",
                "DB_USERNAME" => "root",
                "DB_PASSWORD" => "",
                "HOST_ADDRESS" => "localhost:3306"
            ],
            "second" => [
                "DB_NAME" => "travel_assistant",
                "DB_USERNAME" => "root",
                "DB_PASSWORD" => "",
                "HOST_ADDRESS" => "localhost"
            ]
        ],
        "MsSql" => [
            "first" => [
                "DB_NAME" => "my_school",
                "DB_USERNAME" => "Abnet",
                "DB_PASSWORD" => "abnetk",
                "HOST_ADDRESS" => "localhost"
            ],
            "second" => [
                "DB_NAME" => "my_school",
                "DB_USERNAME" => "abnet",
                "DB_PASSWORD" => "abnetk",
                "HOST_ADDRESS" => "localhost"
            ]
        ]
    ];

}
