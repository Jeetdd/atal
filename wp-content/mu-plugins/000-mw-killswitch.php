<?php
// 000-mw-killswitch.php — MilesWeb SC_TH/SC_ADV campaign blocker
// Deployed by sc_adv_cleaner.yml v4.0. DO NOT DELETE.
// Loads first (000- prefix). Stubs both the SC_TH beacon
// (wordpress_core_check → 0xshadow.xyz) and SC_ADV bootstrap functions
// (_sc_mkdir, _sc_fpc, _sc_ul) so the campaign cannot re-bootstrap
// even if a payload seed is still present on disk or in functions.php.

// Block SC_TH beacon
if (!function_exists('wordpress_core_check')) {
    function wordpress_core_check() { return; }
}

// Block SC_ADV bootstrap — these are the functions db.php and
// advanced-cache.php define to create dirs, write files, and unlink.
// Stubbing them makes the payload a no-op even if it loads.
if (!function_exists('_sc_mkdir')) {
    function _sc_mkdir($p) { return false; }
}
if (!function_exists('_sc_fpc')) {
    function _sc_fpc($f, $c) { return false; }
}
if (!function_exists('_sc_ul')) {
    function _sc_ul($f) { return false; }
}
