<?php
$action = GETPOST('action') ?? 'index';
$element = GETPOST('element') ?? ".";
// Déconnexion
if (GETPOST('action') === 'logout') {
    session_unset();
    session_destroy();
    header('Location: index.php?action=login');
    exit;
}
if ($action === 'login') {
    include dirname(__FILE__) . '/../pages/controllers/login.php';
    include dirname(__FILE__) . '/../pages/views/login.php';
    exit;
}
require_once dirname(__FILE__) . '/../class/myAuthClass.php';
if (!myAuthClass::is_auth($_SESSION)) {
    header('Location: index.php?action=login');
    exit;
}
$target_c = (dirname(__FILE__) . "/../$element/controllers/$action.php");
$target_v = (dirname(__FILE__) . "/../$element/views/$action.php");
if (
    ($target_c && is_file($target_c))
    && ($target_v && is_file($target_v))
) {
    include($target_c);
    include($target_v);
} else
    include(dirname(__FILE__) . '/notfound.php');
