<?php
/*
 * Copyright 2014 IMAGIN Sp. z o.o. - imagin.pl
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (isset($_SERVER['HTTP_CLIENT_IP'])
    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    || !in_array(
        @$_SERVER['REMOTE_ADDR'],
        array(
            '127.0.0.1',
            'fe80::1',
            '::1',
        )
    )
) {
    header('HTTP/1.0 404 Not Found');
    exit();
}

error_reporting(E_ALL);

require_once __DIR__ . '/../app/app.php';

$app['debug'] = true;
$app->run();