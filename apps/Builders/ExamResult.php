<?php
namespace Application\Builders;

use Absoft\Line\Modeling\DbBuilders\Builder;
use Absoft\Line\Modeling\DbBuilders\Schema;


class ExamResult extends Builder{

    function construct(Schema $table, $table_name = "ExamResult"){

        $this->TABLE_NAME = $table_name;

        $this->ATTRIBUTES = [
            //@att_start
            $table->autoincrement("id"),
            $table->int("exam")->nullable(false)->sign(false),
            $table->int("user")->nullable(false)->sign(false),
            $table->int("score")->nullable(false),
            $table->int("date")->nullable(false)->length(20)
            //@att_end
        ];
        
        $this->HIDDEN_ATTRIBUTES = [
            //@hide_start
            //@hide_end
        ];

    }

}

        