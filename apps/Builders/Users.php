<?php
namespace Application\Builders;

use Absoft\Line\Modeling\DbBuilders\Builder;
use Absoft\Line\Modeling\DbBuilders\Schema;


class Users extends Builder{

    function construct(Schema $table, $table_name = "Users"){

        $this->TABLE_NAME = $table_name;

        $this->ATTRIBUTES = [
            //@att_start
            $table->autoincrement("id"),
            $table->string("fullname")->length(50)->nullable(false),
            $table->int("age")->nullable(false)->sign(false),
            $table->int("grade")->sign(false)->nullable(false),
            $table->string("email")->length(50)->nullable(false),
            $table->string("role")->length(25)->nullable(false)
            //@att_end
        ];
        
        $this->HIDDEN_ATTRIBUTES = [
            //@hide_start
            $table->hidden("password")->nullable(false)
            //@hide_end
        ];

    }

}

        