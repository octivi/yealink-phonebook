<?php
/*
 * Copyright 2014 IMAGIN Sp. z o.o. - imagin.pl
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__ . '/bootstrap.php';

use Octivi\Controller\SourceController;
use Octivi\Controller\ContactController;
use Octivi\Controller\DefaultController;

$propertiesFile = __DIR__ . '/properties.json';

// $properties can come from the index.php (Phar distribution)
if (isset($properties)) {
    $directoryPath = $properties['directory'];
    $directoryName = $properties['directory'];
} else {
    $propertiesJson = file_get_contents($propertiesFile);
    $properties = json_decode($propertiesJson, true);

    $directoryPath = __DIR__ . '/../web/' . $properties['directory'];
    $directoryName = $properties['directory'];
}

if (!isset($properties['rootDirectory'])) {
    $properties['rootDirectory'] = __DIR__;
}

$properties['rootDirectory'] = rtrim($properties['rootDirectory'], '/'). '/';

if (substr($directoryName, 0, strlen($properties['rootDirectory'])) == $properties['rootDirectory']) {
    $directoryName = substr($directoryName, strlen($properties['rootDirectory']));
}

$defaultFileName = $properties['defaultFileName'];

$app['sourceDirectory'] = $directoryPath;
$app['defaultFileName'] = $defaultFileName;
$app['directoryName'] = $directoryName;

if (isset($properties['debug']) && $properties['debug']) {
    error_reporting(E_ALL);
    $app['debug'] = true;
}

$app['controller.source'] = $app->share(
    function () use ($app) {
        return new SourceController($app);
    }
);

$app['controller.contact'] = $app->share(
    function () use ($app) {
        return new ContactController($app);
    }
);

$app['controller.default'] = $app->share(
    function () use ($app) {
        return new DefaultController($app);
    }
);

$app->get('/', "controller.default:indexAction")->bind('homepage');

$app->get('/source', "controller.source:indexAction")->bind('source');
$app->match('/source/new', "controller.source:newAction")->bind('source_new');
$app->get('/source/list', "controller.source:listAction")->bind('source_list');
$app->match('/source/upload', "controller.source:uploadAction")->bind('source_upload');

$name_pregmatch = '[A-Za-z.0-9-_ ]+';

$app->get('/contact/{name}', "controller.contact:indexAction")
    ->assert('name', $name_pregmatch)
    ->bind('contact');

$app->match('/contact/{name}/list', "controller.contact:listAction")
    ->assert('name', $name_pregmatch)
    ->bind('contact_list');

$app->get('/contact/{name}/edit', "controller.contact:editAction")
    ->assert('name', $name_pregmatch)
    ->bind('contact_edit');

$app->get('/contact/{name}/url', "controller.contact:urlAction")
    ->assert('name', $name_pregmatch)
    ->bind('contact_url');

$app->post('/contact/{name}/backup', "controller.contact:backupAction")
    ->assert('name', $name_pregmatch)
    ->bind('contact_backup');

$app->post('/contact/{name}/save', "controller.contact:saveAction")
    ->assert('name', $name_pregmatch)
    ->bind('contact_save');

return $app;