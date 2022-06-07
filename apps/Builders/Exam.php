<?php
namespace Application\Builders;

use Absoft\Line\Modeling\DbBuilders\Builder;
use Absoft\Line\Modeling\DbBuilders\Schema;


class Exam extends Builder{

    function construct(Schema $table, $table_name = "Exam"){

        $this->TABLE_NAME = $table_name;

        $this->ATTRIBUTES = [
            //@att_start
            $table->autoincrement("id"),
            $table->string("title")->nullable(false)->length(100),
            $table->int("date")->nullable(false)->length(20),
            $table->string("subject")->nullable(false)->length(100),
            $table->text("description")->nullable(false),
            $table->int("count")->nullable(true),
            //@att_end
        ];
        
        $this->HIDDEN_ATTRIBUTES = [
            //@hide_start
            //@hide_end
        ];

    }

}

        