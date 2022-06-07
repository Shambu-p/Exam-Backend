<?php
namespace Application\Builders;

use Absoft\Line\Modeling\DbBuilders\Builder;
use Absoft\Line\Modeling\DbBuilders\Schema;
use Application\conf\Configuration;

class Admin extends Builder{

    function construct(Schema $table, $table_name = "Admin"){

        $this->TABLE_NAME = $table_name;
        $this->DATABASE = Configuration::$admin_conf["DB_SERVER"];
        $this->DATABASE_NAME = Configuration::$admin_conf["DATABASE_NAME"];

        $this->ATTRIBUTES = [
            //@att_start
            $table->autoincrement("id"),
            $table->string("username")->nullable(false)->unique(true)->length(15),
            $table->string("fullname")->nullable(false)->length(50),
            $table->string("email")->nullable(false)->length(30),
            $table->string("role")->length(20),
            $table->string("status")->length(10)
            //@att_end
        ];
        
        $this->HIDDEN_ATTRIBUTES = [
            //@hide_start
            $table->hidden("password")->nullable(false)
            //@hide_end
        ];

    }

}

        