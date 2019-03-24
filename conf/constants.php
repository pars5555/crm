<?php

/**
 * default constants 
 * in this file should be store all constants
 *
 * @author Levon Naghashyan
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2015
 * @version 2.1.0
 * @copyright Naghashyan Solutions LLC
 *
 */
date_default_timezone_set("Asia/Yerevan");
//defaining project version
NGS()->define("VERSION", "1.0.0");

NGS()->define("SESSION_MANAGER", 'crm\managers\SessionManager');

//define error show status
if (NGS()->getDefinedValue("ENVIRONMENT") != "production") {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}

$documentRoot = $_SERVER['DOCUMENT_ROOT'];
$projectRoot = trim(dirname($documentRoot), '/');
$dataDir = trim(dirname($projectRoot), '/');

if (isLinux()) {
    $dataDir = '/' . $dataDir;
    $projectRoot = '/' . $projectRoot;
}

define('DATA_DIR', $dataDir . '/crm_data');
define('CHECKOUT_DATA_DIR', $dataDir . '/checkout_data');

function isLinux() {
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        return false;
    }
    return true;
}