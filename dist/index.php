<?php
/*
 * Copyright 2014 IMAGIN Sp. z o.o. - imagin.pl
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$properties = array(
    'directory' => __DIR__ . '/pb',         // default directory of the XML files
    'defaultFileName' => 'contacts.xml',    // default phonebook file
    'debug' => true,                        // debug mode
);

// Don't edit below this line
$properties['rootDirectory'] = __DIR__;
require_once 'yealink-phonebook.phar';