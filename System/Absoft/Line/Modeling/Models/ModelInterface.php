<?php
/**
 * Created by PhpStorm.
 * User: Abnet
 * Date: 2/28/2021
 * Time: 12:49 PM
 */

namespace Absoft\Line\Modeling\Models;

interface ModelInterface {

    /**
     * @param $key
     * @return array
     */
    public function findRecord($key);

    /**
     * @return mixed
     */
    function getEntity();

    /**
     * @param array $condition_array
     * @param array $filter_array
     * @param array $order_by
     * @return array
     */
    public function searchRecord(array $condition_array, array $filter_array = [], $other = []);

    /**
     * @param array $condition
     * @return boolean
     */
    public function deleteRecord(array $condition = []);

    /**
     * @param $set
     * @param $condition
     * @return boolean
     */
    public function updateRecord($set, $condition);

    /**
     * @param array $values
     * @return boolean
     */
    public function addRecord(array $values);

    /**
     * @param array $values
     * @return boolean
     */
    public function addMultiple(array $values);

    /*

    [
        "name" => "",
        "email" => "dma"
        ":post" => [
            ":on" => "poster",
            "text" => "",
            "date" => ""
        ]
    ]

     */

    public function advancedSearch(Array $search_array, $limit = []);

    public function hasOne($model_name);

    public function hasMany($model_name);

}
