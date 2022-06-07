<?php
/**
 * Created by PhpStorm.
 * User: Abnet
 * Date: 3/1/2021
 * Time: 11:05 AM
 */

namespace Absoft\Line\Modeling\Models;

class MongoModel implements ModelInterface
{


    /**
     * @param $key
     * @return array
     */
    public function findRecord($key)
    {
        // TODO: Implement findRecord() method.
    }

    /**
     * @return mixed
     */
    function getEntity()
    {
        // TODO: Implement getEntity() method.
    }

    /**
     * @param array $condition_array
     * @param array $filter_array
     * @param array $order_by
     * @return array
     */
    public function searchRecord(array $condition_array, array $filter_array = [], $order_by = [])
    {
        // TODO: Implement searchRecord() method.
    }

    /**
     * @param array $condition
     * @return boolean
     */
    public function deleteRecord(array $condition = [])
    {
        // TODO: Implement deleteRecord() method.
    }

    /**
     * @param $set
     * @param $condition
     * @return boolean
     */
    public function updateRecord($set, $condition)
    {
        // TODO: Implement updateRecord() method.
    }

    /**
     * @param array $values
     * @return boolean
     */
    public function addRecord(array $values)
    {
        // TODO: Implement addRecord() method.
    }

    /**
     * @param array $values
     * @return boolean
     */
    public function addMultiple(array $values)
    {
        // TODO: Implement addMultiple() method.
    }

    public function hasOne($model_name)
    {
        // TODO: Implement hasOne() method.
    }

    public function hasMany($model_name)
    {
        // TODO: Implement hasMany() method.
    }
}
