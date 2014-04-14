<?php
/*
 * Copyright 2014 IMAGIN Sp. z o.o. - imagin.pl
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Octivi\Service;

class XMLFileService
{
    protected $fileName;
    protected $filePath;

    /**
     * @param $fileName
     * @param $filePath
     */
    public function __construct($fileName, $filePath)
    {
        $this->fileName = $fileName;
        $this->filePath = rtrim($filePath, '/'). '/';
    }

    /**
     * @return \SimpleXMLElement
     */
    public function loadFile()
    {
        $filePath = $this->filePath;
        $fileName = $this->fileName;

        $this->checkIfFileExists();

        $phoneBookStr = file_get_contents($filePath . $fileName);
        if (strpos($phoneBookStr, '<?xml') === false) {
            $phoneBookStr = "<?xml version='1.0'?>" . $phoneBookStr;
        }
        $phoneBookStr = $this->deleteCdataBlocks($phoneBookStr);
        $phoneBookStr = $this->setCdataBlocks($phoneBookStr);

        $phoneBook = simplexml_load_string($phoneBookStr, null, LIBXML_NOCDATA);

        return $phoneBook;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function generateProperXML(array $data)
    {
        $filePath = $this->filePath;
        $fileName = $this->fileName;

        $xml = new \SimpleXMLElement("<?xml version=\"1.0\" encoding=\"utf-8\" ?><YealinkIPPhoneDirectory></YealinkIPPhoneDirectory>");

        //Add users to file
        if (array_key_exists('phoneRecords', $data)) {
            $this->addUsers($xml, $data['phoneRecords']);
        }
        //Add URLs to file
        if (array_key_exists('URL', $data)) {
            $this->addURLs($xml, $data['URL']);
        }

        $formatedStructure = $this->formatWhiteSpaces($xml);

        $readyString = str_replace("<?xml version=\"1.0\" encoding=\"utf-8\"?>\n", "", $formatedStructure);

        return $readyString;
    }

    /**
     * Save old file under date-generated name
     */
    public function archiveFile()
    {
        $filePath = $this->filePath;
        $fileName = $this->fileName;

        $dateStamp = date('Ymd-Hi', filemtime($filePath . $fileName));
        rename($filePath . $fileName, $filePath . $dateStamp . '-' . $fileName);
    }

    public function saveFile($readyString)
    {
        $filePath = $this->filePath;
        $fileName = $this->fileName;

        file_put_contents($filePath . $fileName, $readyString);
        return true;
    }

    /**
     * @param \SimpleXMLElement $xml
     * @param array $users
     */
    protected function addUsers(\SimpleXMLElement $xml, array $users)
    {
        $recordName = 'DirectoryEntry';
        $recordPropertyName = 'Name';
        $recordPropertyPhone = 'Telephone';

        foreach ($users as $user) {
            if ($user['recordName'] !== '') {
                $tmp = $xml->addChild($recordName);
                $tmp->addChild($recordPropertyName, $user['recordName']);

                foreach ($user['phoneNumbers'] as $phone) {
                    if ($phone['phoneNumber'] !== '') {
                        $tmp->addChild($recordPropertyPhone, $phone['phoneNumber']);
                    }
                }
            }
        }
    }

    /**
     * @param \SimpleXMLElement $xml
     * @param array $urls
     */
    protected function addURLs(\SimpleXMLElement $xml, array $urls)
    {
        $recordName = 'SoftKeyItem';
        $recordPropertyName = 'Name';
        $recordPropertyURL = 'URL';
        $recordPropertyPosition = 'Position';

        foreach ($urls as $url) {
            $tmp = $xml->addChild($recordName);
            $tmp->addChild($recordPropertyName, $url['name']);
            $tmp->addChild($recordPropertyURL, $url['url']);
            $tmp->addChild($recordPropertyPosition, $url['position']);
        }
    }

    /**
     * @throws \Exception
     */
    protected function checkIfFileExists()
    {
        $file = $this->filePath . $this->fileName;

        if (!file_exists($file)) {
            throw new \Exception(sprintf("Couldn't find file! On path: %s", $file));
        }
    }

    /**
     * @param $toChange
     * @return mixed
     */
    protected function deleteCdataBlocks($toChange)
    {

        $changed = str_replace("<![CDATA[", "", $toChange);
        $changed = str_replace("]]>", "", $changed);

        return $changed;
    }

    /**
     * @param $toChange
     * @return mixed
     */
    protected function setCdataBlocks($toChange)
    {
        return preg_replace('/\<URL>(.*)<\/URL>/', '<URL><![CDATA[$1]]></URL>', $toChange);

    }

    /**
     * @param \SimpleXMLElement $xml
     * @return string
     */
    protected function formatWhiteSpaces(\SimpleXMLElement $xml)
    {
        $formated = dom_import_simplexml($xml);
        $formated = $formated->ownerDocument;
        $formated->preserveWhiteSpace = false;
        $formated->formatOutput = true;
        return $formated->saveXML();
    }
}
