<?php
/**
 * Created by PhpStorm.
 * User: Abnet
 * Date: 4/27/2021
 * Time: 12:08 AM
 */

namespace Application\conf;

class Configuration {

    public static $conf = [
        "title" => "Travel Assistant",
        "type" => "API",
    ];

    public static $admin_conf = [
        "cli" => false,
        "webAPI" => false,
        "DB_SERVER" => "MySql",
        "DATABASE_NAME" => "first"
    ];

}
