<?php
/*
 * Copyright 2014 IMAGIN Sp. z o.o. - imagin.pl
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Octivi\Entity;

class PhoneBook
{
    protected $phoneRecords = array();
    protected $urlRecords = array();

    /**
     * @return array
     */
    public function getPhoneRecords()
    {
        return $this->phoneRecords;
    }

    /**
     * @param array $phoneRecords
     */
    public function setPhoneRecords(array $phoneRecords)
    {
        $this->phoneRecords = $phoneRecords;
    }

    /**
     * @param PhoneRecord $phoneRecord
     */
    public function addPhoneRecord(PhoneRecord $phoneRecord)
    {
        array_push($this->phoneRecords, $phoneRecord);
    }

    /**
     * @return array
     */
    public function getUrlRecords()
    {
        return $this->urlRecords;
    }

    /**
     * @param array $urlRecords
     */
    public function setUrlRecords(array $urlRecords)
    {
        $this->urlRecords = $urlRecords;
    }

    /**
     * @param UrlRecord $urlRecord
     */
    public function addUrlRecord(UrlRecord $urlRecord)
    {
        $this->urlRecords = array_push($this->urlRecords, $urlRecord);
    }
}
