<?php
/*
 * Copyright 2014 IMAGIN Sp. z o.o. - imagin.pl
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Octivi\Controller;

use Symfony\Component\HttpFoundation\Request;
use Octivi\Service\XMLFileService;
use Octivi\Form\DataEditForm;
use Symfony\Component\Routing\Generator\UrlGenerator;

class ContactController extends BaseController
{
    protected $form;

    /**
     * @param Request $request
     * @param $name
     * @return string
     */
    public function indexAction(Request $request, $name)
    {
        if ($request->headers->get('referer') === null) {
            $redirected = true;
        } else {
            $redirected = false;
        }

        $newPhoneBookForm = $this->generateFormModel($name);
        $newPhoneBookForm->setAction($this->app['url_generator']->generate('contact_backup', array('name' => $name)))
            ->setMethod('POST');
        $newPhoneBookForm = $newPhoneBookForm->getForm();
        $this->form = $newPhoneBookForm;

        return $this->twig->render(
            'twigs/Contact/index.html.twig',
            array(
                'name' => $name,
                'redirected' => $redirected,
                'phone_book' => $newPhoneBookForm->createView(),

            )
        );
    }

    /**
     * @param string $name
     * @return string
     */
    public function listAction($name = '')
    {
        return $this->twig->render('twigs/Contact/list.html.twig', array('phone_book' => $this->form->createView()));
    }

    /**
     * @param string $name
     * @return string
     */
    public function editAction($name = '')
    {
        return $this->twig->render('twigs/Contact/edit.html.twig');
    }

    /**
     * @param string $name
     * @return string
     */
    public function urlAction($name = '')
    {
        $directoryName = $this->app['directoryName'];

        $remote_url = $this->app['url_generator']->generate(
                'homepage',
                array(),
                UrlGenerator::ABSOLUTE_URL
            ) . $directoryName . '/' . $name;

        return $this->twig->render('twigs/Contact/url.html.twig', array('remote_url' => $remote_url));
    }

    /**
     * @param Request $request
     * @param $name
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function backupAction(Request $request, $name)
    {
        $this->savingProcess($request, $name, true);

        return $this->app->redirect($this->app['url_generator']->generate('contact', array('name' => $name)));
    }

    public function saveAction(Request $request, $name)
    {
        $this->savingProcess($request, $name, false);

        return true;
    }

    protected function savingProcess($request, $name, $createArchive)
    {
        $filePath = $this->app['sourceDirectory'];
        $fileName = $name;

        if ($request->getMethod() !== 'POST') {
            throw new \Exception('No data sended.');
        }

        $dataTable = $request->request->all();

        $XMLPattern = new XMLFileService($fileName, $filePath);
        $XMLToSave = $XMLPattern->generateProperXML($dataTable['phoneBook']);

        if ($createArchive) {
            $XMLPattern->archiveFile();
        }

        $XMLPattern->saveFile($XMLToSave);
    }
    /**
     * @param $name
     * @return mixed
     */
    protected function generateFormModel($name)
    {
        $XMLService = new XMLFileService($name, $this->app['sourceDirectory']);
        $FileContent = $XMLService->loadFile();

        $createPhonebookForm = new DataEditForm($this->app['form.factory']);

        return $createPhonebookForm->createForm($FileContent);
    }
}