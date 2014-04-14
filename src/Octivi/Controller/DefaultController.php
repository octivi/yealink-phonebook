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

class DefaultController extends BaseController
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction()
    {
        $fileName = $this->app['defaultFileName'];
        $filePath = $this->app['sourceDirectory'];

        $file = $filePath . '/' . $fileName;

        if (!file_exists($file)) {
            return $this->app->redirect($this->app['url_generator']->generate('source'));
        } else {
            return $this->app->redirect($this->app['url_generator']->generate('contact', array('name' => $fileName)));
        }
    }
}