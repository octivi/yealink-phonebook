<?php
/*
 * Copyright 2014 IMAGIN Sp. z o.o. - imagin.pl
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Octivi\Form;

use Octivi\Entity\PhoneNumber;
use Octivi\Entity\PhoneRecord;
use Octivi\Form\Type\PhoneRecordType;
use Octivi\Form\Type\PhoneBookType;
use Octivi\Entity\PhoneBook;

class DataEditForm
{
    const PHONE_RECORD_TAG = 'DirectoryEntry';
    const PHONE_NUMBER_TAG = 'Telephone';
    const PHONE_NAME_TAG = 'Name';

    private $form_factory;

    /**
     * @param $form_factory
     */
    public function __construct($form_factory)
    {
        $this->form_factory = $form_factory;
    }

    /**
     * @param $dataArray
     * @return mixed
     */
    public function createForm($dataArray)
    {
        $builder = $this->form_factory;

        $phoneBook = new PhoneBook();
        $phoneBook->addPhoneRecord($this->createCleanTemplate());

        foreach ($dataArray as $key => $value) {

            if ($key === self::PHONE_RECORD_TAG) {

                $phoneRecord = new PhoneRecord();
                $valueInArray = (array)$value;

                foreach ($valueInArray as $phoneNumberKey => $phoneNumberValue) {

                    if ($phoneNumberKey === self::PHONE_NUMBER_TAG) {

                        if (is_array($phoneNumberValue)) {
                            $this->addNumberArray($phoneNumberValue, $phoneRecord);
                        } else {
                            $this->addNumberSingle($phoneNumberValue, $phoneRecord);
                        }

                    } elseif ($phoneNumberKey === self::PHONE_NAME_TAG) {
                        $phoneRecord->setRecordName($phoneNumberValue);
                    }
                }
                $phoneBook->addPhoneRecord($phoneRecord);
            }
        }

        $builder = $builder->createBuilder(new PhoneBookType(), $phoneBook);

        return $builder;
    }

    /**
     * @param array $numbersArray
     * @param PhoneRecord $record
     */
    protected function addNumberArray(array $numbersArray, PhoneRecord $record)
    {
        foreach ($numbersArray as $value) {
            $this->addNumberSingle($value, $record);
        }
    }

    /**
     * @param $number
     * @param PhoneRecord $record
     */
    protected function addNumberSingle($number, PhoneRecord $record)
    {
        $phoneNumber = new PhoneNumber();
        $phoneNumber->setPhoneNumber($number);
        $phoneNumber->name = 'phoneNumber';
        $record->addPhoneNumber($phoneNumber);
    }

    /**
     * @return PhoneRecord
     */
    public function createCleanTemplate()
    {
        $phoneRecord = new PhoneRecord();
        $phoneNumber = new PhoneNumber();
        $phoneNumber->setPhoneNumber('');
        $phoneNumber->name='phoneNumber';
        $phoneRecord->addPhoneNumber($phoneNumber);
        $phoneRecord->setRecordName('');

        return $phoneRecord;
    }
}