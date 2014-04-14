<?php
/*
 * Copyright 2014 IMAGIN Sp. z o.o. - imagin.pl
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Octivi\Controller;

use Octivi\Form\NewFileForm;
use Octivi\Form\UploadFileForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Exception;

class SourceController extends BaseController
{
    /**
     * It should show or redirect to default routing when file found
     */
    public function indexAction()
    {
        return $this->twig->render(
            'twigs/Source/index.html.twig'
        );
    }

    /**
     * @param Request $request
     * @return string|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function uploadAction(Request $request)
    {
        $createUploadFileForm = new UploadFileForm($this->app['form.factory']);
        $uploadFileForm = $createUploadFileForm->createForm();

        if ($request->getMethod() === 'POST') {
            $uploadFileForm->bind($request);

            if ($uploadFileForm->isValid()) {
                $file = $request->files->get($uploadFileForm->getName());
                $path = $this->app['sourceDirectory'];
                $filename = $file['fileToUpload']->getClientOriginalName();

                $file['fileToUpload']->move($path, $filename);

                return $this->app->redirect(
                    $this->app['url_generator']->generate('contact', array('name' => $filename))
                );
            }
        }

        return $this->twig->render(
            'twigs/Source/upload.html.twig',
            array('uploadFileForm' => $uploadFileForm->createView(),)
        );
    }

    /**
     * @param Request $request
     * @return string|\Symfony\Component\HttpFoundation\RedirectResponse
     * @throws
     */
    public function newAction(Request $request)
    {
        $createNewFileForm = new NewFileForm($this->app['form.factory']);
        $newFileForm = $createNewFileForm->createForm();

        if ($request->getMethod() === 'POST') {
            $newFileForm->bind($request);

            if ($newFileForm->isValid()) {
                $filename = $newFileForm->getData();
                $filename = $filename['fileName'];
                $XMLPattern = new \SimpleXMLElement("<?xml version=\"1.0\" encoding=\"utf-8\" ?><YealinkIPPhoneDirectory></YealinkIPPhoneDirectory>");

                if ($XMLPattern->saveXML($this->app['sourceDirectory'] . '/' . $filename . '.xml') !== false) {
                    return $this->app->redirect(
                        $this->app['url_generator']->generate('contact', array('name' => $filename . ".xml"))
                    );
                } else {
                    throw \Exception("Problem with save file into given directory");
                }
            }
        }


        return $this->twig->render(
            'twigs/Source/new.html.twig',
            array(
                'newFileForm' => $newFileForm->createView(),
            )
        );
    }

    /**
     * @return string
     */
    public function listAction()
    {
        $sourceDirectory = $this->app['sourceDirectory'];
        $fileList = $this->searchXMLFiles($sourceDirectory);

        return $this->twig->render(
            'twigs/Source/list.html.twig',
            array(
                'fileList' => $fileList,
            )
        );
    }

    /**
     * Searching for XML files under given directory
     *
     * @param $sourceDirectory
     * @return array|null
     */
    protected function searchXMLFiles($sourceDirectory)
    {
        $i = 0;
        $fileNamesArray = array();

        if (is_dir($sourceDirectory)) {
            foreach (glob($sourceDirectory . "/*.xml") as $file) {

                $fileParametersArray = explode("/", $file);
                $fileName = end($fileParametersArray);

                $fileNamesArray[$i]['name'] = $fileName;
                $fileNamesArray[$i]['modified'] = date("m.d.Y H:i:s", filemtime($sourceDirectory . "/" . $fileName));
                $i+=1;
            }
        } else {
            return null;
        }

        if (empty($fileNamesArray)) {
            return null;
        }
        $fileNamesArray = $this->files_sort($fileNamesArray, 'modified', SORT_DESC);

        return $fileNamesArray;
    }

    function files_sort($array, $on, $order=SORT_ASC)
    {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }
}
