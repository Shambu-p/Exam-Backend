<?php
/**
 * Created by PhpStorm.
 * User: Abnet
 * Date: 1/26/2020
 * Time: 12:02 PM
 */

namespace Absoft\Line\Modeling\DbBuilders;

use Absoft\Line\DbConnection\Attributes\SQL\Hidden;
use Absoft\Line\DbConnection\Attributes\SQL\Varchar;
use Absoft\Line\DbConnection\Attributes\SQL\Primary;
use Absoft\Line\DbConnection\Attributes\SQL\Text;
use Absoft\Line\DbConnection\Attributes\SQL\Number;
use Absoft\Line\DbConnection\Attributes\SQL\Date;
use Absoft\Line\DbConnection\Attributes\SQL\Time;
use Absoft\Line\DbConnection\Attributes\SQL\Numeric;
use Absoft\Line\DbConnection\Attributes\SQL\Decimal;
use Absoft\Line\DbConnection\Attributes\SQL\TimeStamp;

class Schema{

    function string($name){
        return new Varchar($name);
    }

    function autoincrement($name){
        return new Primary($name);
    }

    function text($name){
        return new Text($name);
    }

    function int($name){
        return new Number($name);
    }

    function date($name){
        return new Date($name);
    }

    function time($name){
        return new Time($name);
    }

    function double($name){
        return new Numeric($name);
    }

    function float($name){
        return new Decimal($name);
    }

    function timestamp($name){
        return new TimeStamp($name);
    }

    function hidden($name){
        return new Hidden($name);
    }
}
