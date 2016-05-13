<?php
/*
 * @author Anakeen
 * @package FDL
*/
/**
 * Edit preferences for onefam list
 *
 * @author Anakeen
 * @version $Id: onefam_editpref.php,v 1.11 2008/08/11 16:29:44 eric Exp $
 * @package FDL
 * @subpackage
 */
/**
 */

include_once "FDL/Lib.Dir.php";

function onefam_editpref(Action & $action, $idsattr = "ONEFAM_IDS", $modaction = "ONEFAM_MODPREF")
{
    $action->parent->addJsRef($action->getParam("CORE_JSURL") . "/geometry.js");
    $action->parent->addJsRef($action->getParam("CORE_JSURL") . "/resizeimg.js");
    $action->parent->addJsRef($action->getParam("CORE_PUBURL") . "/FDL/Layout/common.js");
    $action->parent->addJsRef("ONEFAM:onefam_editpref.js");
    $action->parent->addCssRef("css/dcp/main.css");
    $action->parent->addCssRef("ONEFAM:onefam_editpref.css");
    
    $tcdoc = GetClassesDoc($action->dbaccess, $action->user->id, 0, "TABLE");
    
    $idsfam = $action->getParam($idsattr);
    $tidsfam = explode(",", $idsfam);
    foreach ($tidsfam as $k => $v) {
        if (!is_numeric($v)) $tidsfam[$k] = getFamIdFromName($action->dbaccess, $v);
    }
    
    $openfam = $action->getParam("ONEFAM_FAMOPEN");
    $action->lay->set("openfirst", $openfam);
    $doc = new_Doc($action->dbaccess);
    
    $selectclass = array();
    if (is_array($tcdoc)) {
        while (list($k, $pdoc) = each($tcdoc)) {
            if ($pdoc["dfldid"] > 0) {
                $selectclass[$k]["cid"] = $pdoc["id"];
                $selectclass[$k]["ctitle"] = $pdoc["title"];
                $selectclass[$k]["iconsrc"] = $doc->getIcon($pdoc["icon"]);
                $selectclass[$k]["selected"] = (in_array($pdoc["id"], $tidsfam)) ? "checked" : "";
            }
        }
    }
    
    $action->lay->setBlockData("SELECTPREF", $selectclass);
    $action->lay->set("modaction", $modaction);
}

function onefam_editmasterpref(Action & $action)
{
    onefam_editpref($action, "ONEFAM_MIDS", "ONEFAM_MODMASTERPREF");
}
