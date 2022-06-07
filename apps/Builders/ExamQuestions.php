<?php
namespace Application\Builders;

use Absoft\Line\Modeling\DbBuilders\Builder;
use Absoft\Line\Modeling\DbBuilders\Schema;


class ExamQuestions extends Builder{

    function construct(Schema $table, $table_name = "ExamQuestions"){

        $this->TABLE_NAME = $table_name;

        $this->ATTRIBUTES = [
            //@att_start
            $table->int("exam")->nullable(false)->sign(false)->nullable(false),
            $table->int("question")->nullable(false)->sign(false)->nullable(false)
            //@att_end
        ];
        
        $this->HIDDEN_ATTRIBUTES = [
            //@hide_start
            //@hide_end
        ];

    }

}

        