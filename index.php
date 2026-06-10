<?php
/*
 * Mini bootstrap kompatibel pola CodeIgniter 3 untuk UAS.
 * Letakkan folder ini di htdocs/rs_pendaftaran lalu akses via index.php/auth/login.
 */
define('ENVIRONMENT', 'development');

switch (ENVIRONMENT) {
    case 'development':
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        break;
    default:
        error_reporting(0);
        ini_set('display_errors', 0);
        break;
}

define('BASEPATH', __DIR__ . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR);
define('APPPATH', __DIR__ . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR);

require_once BASEPATH . 'core/CodeIgniter.php';
