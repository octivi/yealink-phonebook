<?php
/*
 * Copyright 2014 IMAGIN Sp. z o.o. - imagin.pl
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Octivi\Controller;

use Silex\Application;

abstract class BaseController
{
    /**
     * @var \Silex\Application
     */
    protected $app;

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->twig = $app['twig'];
    }
}