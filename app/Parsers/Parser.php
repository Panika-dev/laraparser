<?php
/**
 * Created by PhpStorm.
 * User: evgeny
 * Date: 1/29/18
 * Time: 6:23 PM
 */

namespace App\Parsers;


abstract class Parser
{

    /**
     * @param $data
     * @return array
     */
    public function customUpdateData($data) : array
    {
        return $data;
    }

    /**
     * @param $data
     * @return array
     */
    public function customUpdateDataCsv($data) : array
    {
        return $data;
    }

}