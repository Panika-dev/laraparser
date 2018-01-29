<?php

namespace App\Parsers;

use App\Contracts\ParserInterface;
use Yangqi\Htmldom\Htmldom;

class TursabParser extends Parser implements ParserInterface
{

    /**
     * @return array
     */
    public function fields(): array
    {
        return ['name', 'email', 'phone', 'address'];
    }

    /**
     * @return array
     */
    public function nameCsvCallback($name): array
    {
        return html_entity_decode($name);
    }


    /**
     * @return array
     */
    public function pages(): array
    {

        $pages = [];

        for ($i = 1; $i <= 494; $i++) {
            $pages[$i] = 'https://www.tursab.org.tr/en/travel-agencies/search-travel-agency?sayfa=' . $i;
        }

//        for ($i = 1; $i <= 30; $i++) {
//            $pages[$i] = 'https://www.tursab.org.tr/en/travel-agencies/search-iata-member-travel-agency?sayfa=' . $i;
//        }

        return $pages;
    }

    /**
     * @param Htmldom $pageHtmlDom
     * @return mixed
     */
    public function findItemsOnPage(Htmldom $pageHtmlDom) : array
    {
        $items = [];

        $pageItems = $pageHtmlDom->find('table#acentaTbl > tr[data-id]');

        foreach ($pageItems as $pageItem) {
            $items[] = [
                'oid' => $pageItem->getAttribute('data-id'),
                'url' => 'https://www.tursab.org.tr/en/displayAcenta?AID=' . $pageItem->getAttribute('data-id'),
            ];
        }

        return $items;
    }

    /**
     * @param Htmldom $pageHtmlDom
     * @return string
     */
    public function nameDataFind(Htmldom $pageHtmlDom): string
    {
        return $pageHtmlDom->find('b', 0)->plaintext;
    }

    /**
     * @param Htmldom $pageHtmlDom
     * @return string
     */
    public function emailDataFind(Htmldom $pageHtmlDom): string
    {
        return $pageHtmlDom->find('b', 0)->plaintext;
    }

    /**
     * @param Htmldom $pageHtmlDom
     * @return string
     */
    public function addressDataFind(Htmldom $pageHtmlDom): string
    {
        foreach ($pageHtmlDom->find('tr') as $tr) {
            if (!is_null($tr) && $tr->find('td', 0)->plaintext == "Address") {
                return $tr->find('td', 1)->plaintext;
            }
        }

        return '';
    }

    /**
     * @param Htmldom $pageHtmlDom
     * @return string
     */
    public function phoneDataFind(Htmldom $pageHtmlDom): string
    {
        foreach ($pageHtmlDom->find('tr') as $tr) {
            if (!is_null($tr) && $tr->find('td', 0)->plaintext == "Phone") {
                return $tr->find('td', 1)->plaintext;
            }
        }

        return '';
    }

    /**
     * @param $phone
     * @return string
     */
    public function phoneDataChange($phone): string
    {
        return substr($phone, 2);
    }

    /**
     * @param $phone
     * @return string
     */
    public function addressDataChange($phone): string
    {
        return substr($phone, 2);
    }
}