#!/usr/bin/env php
<?php
/*
 * @author Anakeen
 * @license http://www.fsf.org/licensing/licenses/agpl-3.0.html GNU Affero General Public License
 * @package FDL
*/

require_once 'WHAT/Lib.Common.php';
require_once 'Class.Action.php';
require_once 'Class.Application.php';

$action_desc = array(
    array(
        "name" => "ONEFAM_EXT",
        "short_name" => N_("one family extjs view") ,
        "acl" => "ONEFAM_READ",
        "root" => "N"
    ) ,
    array(
        "name" => "ONEFAM_EXT_MODPREF",
        "short_name" => N_("modify preferences") ,
        "acl" => "ONEFAM"
    ) ,
    array(
        "name" => "ONEFAM_EXT_MODMASTERPREF",
        "short_name" => N_("modify master preferences") ,
        "layout" => "onefam_ext_modpref.xml",
        "script" => "onefam_ext_modpref.php",
        "function" => "onefam_ext_modmasterpref",
        "acl" => "ONEFAM_MASTER"
    ) ,
    array(
        "name" => "ONEFAM_EXT_MENU",
        "short_name" => N_("get menu with family concordance") ,
        "acl" => "ONEFAM_READ"
    ) ,
    array(
        "name" => "ONEFAM_EXT_GETPREF",
        "short_name" => N_("get preferences") ,
        "script" => "onefam_ext_getpref.php",
        "function" => "onefam_ext_getpref",
        "acl" => "ONEFAM"
    ) ,
    array(
        "name" => "ONEFAM_EXT_GETMASTERPREF",
        "short_name" => N_("get master preferences") ,
        "script" => "onefam_ext_getpref.php",
        "function" => "onefam_ext_getmasterpref",
        "acl" => "ONEFAM_MASTER"
    ) ,
    array(
        "name" => "ONEFAM_EXT_GETDISPLAYCONFIG",
        "short_name" => N_("get ext display configuration") ,
        "script" => "onefam_ext_displayconfig.php",
        "function" => "onefam_ext_getdisplayconfig",
        "acl" => "ONEFAM"
    ) ,
    array(
        "name" => "ONEFAM_EXT_SETDISPLAYCONFIG",
        "short_name" => N_("set ext display configuration") ,
        "script" => "onefam_ext_displayconfig.php",
        "function" => "onefam_ext_setdisplayconfig",
        "acl" => "ONEFAM"
    )
);

$onefamApp = new Application();
$onefamApp->set("ONEFAM", $core);
$onefamAction = new Action();
$onefamAction->Init($onefamApp, $action_desc);
