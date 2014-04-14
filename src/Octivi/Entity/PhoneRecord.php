<?php
/*
 * Copyright 2014 IMAGIN Sp. z o.o. - imagin.pl
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Octivi\Entity;

class PhoneRecord
{
    protected $phoneNumbers = array();
    protected $recordName;

    /**
     * @return mixed
     */
    public function getRecordName()
    {
        return $this->recordName;
    }

    /**
     * @param $recordName
     */
    public function setRecordName($recordName)
    {
        $this->recordName = $recordName;
    }

    /**
     * @return array
     */
    public function getPhoneNumbers()
    {
        return $this->phoneNumbers;
    }

    /**
     * @param array $phoneNumbers
     */
    public function setPhoneNumbers(array $phoneNumbers)
    {
        $this->phoneNumbers = $phoneNumbers;
    }

    /**
     * @param PhoneNumber $number
     */
    public function addPhoneNumber(PhoneNumber $number)
    {
        $this->numbers = array_push($this->phoneNumbers, $number);
    }

}