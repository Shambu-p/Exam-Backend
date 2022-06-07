<?php
/**
 * Created by PhpStorm.
 * User: Abnet
 * Date: 3/1/2021
 * Time: 7:48 PM
 */
namespace Absoft\Line\Engines\CLI\Commands;

use Absoft\Line\Engines\CLI\CLIConfiguration;

class CommandFactory
{

    /**
     * this method will find the receiver by taking receivers name as string
     * and then it will return the object of that class
     * @param string $receiver_name
     * this parameter is receiver name in string format
     * @return object
     */
    public static function get($command_name){

        $command_name = 'Absoft\\Line\\Engines\\CLI\\Commands\\'.$command_name."Command";

        try {

            $class = new \ReflectionClass($command_name);

            return $class->newInstance();

        } catch (\ReflectionException $e) {

            print $e->getMessage();
            return null;

        }

    }


    /**
     * @param string $name
     * this parameter is for setting the receiver command name or short name
     * @param string $receiver_full_name
     * this parameter is for setting the receiver name in namespace.
     * for example this class full name is Absoft\Line\Engines\CLI\Receivers\ReceiverFactory
     * this parameter should be set as string.
     */
    public static function set($name, $receiver_full_name){

    }

}

?>
