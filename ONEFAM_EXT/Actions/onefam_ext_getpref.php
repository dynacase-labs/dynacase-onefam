<?php
/*
 * @author Anakeen
 * @package FDL
*/
/**
 * Retrieve family information for onefam
 *
 * @author Anakeen
 * @package FDL
 * @subpackage
 */
/**
 */

include_once ("FDL/Class.Dir.php");
include_once ("FDL/Class.SearchDoc.php");
/**
 *  Retrieve families from onefam
 *
 * @param Action &$action current action
 * @global appid int var : application name
 */

function onefam_ext_getpref(Action & $action, $idsattr = "ONEFAM_IDS")
{
    $ids = explode(",", $action->getParam($idsattr));
    //$umids=explode(",",$action->getParam("ONEFAM_IDS"));
    $out = array(
        "ids" => $ids
    );
    
    $action->lay->noparse = true; // no need to parse after - increase performances
    $action->lay->template = json_encode($out);
}

function onefam_ext_getmasterpref(Action & $action)
{
    onefam_ext_getpref($action, "ONEFAM_MIDS");
}
