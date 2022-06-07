<?php
namespace Application\Builders;

use Absoft\Line\Modeling\DbBuilders\Builder;
use Absoft\Line\Modeling\DbBuilders\Schema;


class ExamResultQuestion extends Builder{

    function construct(Schema $table, $table_name = "ExamResultQuestion"){

        $this->TABLE_NAME = $table_name;

        $this->ATTRIBUTES = [
            //@att_start
            $table->int("exam_result")->nullable(false)->sign(false),
            $table->int("question")->nullable(false)->sign(false),
            $table->int("choice")->nullable(false)->sign(false),
            $table->string("state")->nullable(false)->length(50)
            //@att_end
        ];
        
        $this->HIDDEN_ATTRIBUTES = [
            //@hide_start
            //@hide_end
        ];

    }

}

        