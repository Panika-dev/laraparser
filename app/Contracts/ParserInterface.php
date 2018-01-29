<?php

namespace App\Contracts;

use Yangqi\Htmldom\Htmldom;

interface ParserInterface
{

    /**
     * @return array
     */
    public function fields(): array;


    /**
     * @return array
     */
    public function pages(): array;

    /**
     * @param Htmldom $pageHtmlDom
     * @return mixed
     */
    public function findItemsOnPage(Htmldom $pageHtmlDom): array;

    /**
     * @param $data
     * @return mixed
     */
    public function customUpdateData($data): array;

    /**
     * @param $data
     * @return mixed
     */
    public function customUpdateDataCsv($data);


}
