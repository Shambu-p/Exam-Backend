<?php
namespace Application\Builders;

use Absoft\Line\Modeling\DbBuilders\Builder;
use Absoft\Line\Modeling\DbBuilders\Schema;


class Question extends Builder{

    function construct(Schema $table, $table_name = "Question"){

        $this->TABLE_NAME = $table_name;

        $this->ATTRIBUTES = [
            //@att_start
            $table->autoincrement("id"),
            $table->text("text")->nullable(false),
            $table->string("subject")->nullable(false)->length(100),
            $table->int("answer")->nullable(true)->sign(false)->nullable(false),
            $table->int("user")->nullable(false)->sign(false)->nullable(false)
            //@att_end
        ];

        $this->HIDDEN_ATTRIBUTES = [
            //@hide_start
            //@hide_end
        ];

    }

}

        