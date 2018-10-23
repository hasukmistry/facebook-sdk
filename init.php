<?php
/**
 * Autoload vendor to use facebook sdk.
 */

require_once __DIR__ . '/vendor/autoload.php'; // change path as needed.

// using config class.
require_once __DIR__ . '/config.php';

// using classes.
require_once __DIR__ . '/classes/FBloader.php';
require_once __DIR__ . '/classes/FBtokens.php';
require_once __DIR__ . '/classes/FBcontrols.php';
require_once __DIR__ . '/classes/FBpage.php';
require_once __DIR__ . '/classes/FBdebug.php';

?>
