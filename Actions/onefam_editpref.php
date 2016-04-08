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
    $dbaccess = $action->GetParam("FREEDOM_DB");
    
    $action->parent->AddJsRef($action->GetParam("CORE_JSURL") . "/geometry.js");
    $action->parent->AddJsRef($action->GetParam("CORE_JSURL") . "/resizeimg.js");
    $action->parent->AddJsRef($action->GetParam("CORE_PUBURL") . "/FDL/Layout/common.js");
    $action->parent->AddJsRef("ONEFAM:onefam_editpref.js");
    $action->parent->AddCssRef("css/dcp/main.css");
    $action->parent->AddCssRef("ONEFAM:onefam_editpref.css");
    
    $tcdoc = GetClassesDoc($dbaccess, $action->user->id, 0, "TABLE");
    
    $idsfam = $action->GetParam($idsattr);
    $tidsfam = explode(",", $idsfam);
    foreach ($tidsfam as $k => $v) {
        if (!is_numeric($v)) $tidsfam[$k] = getFamIdFromName($dbaccess, $v);
    }
    
    $openfam = $action->getParam("ONEFAM_FAMOPEN");
    $action->lay->set("openfirst", $openfam);
    $doc = new_Doc($dbaccess);
    
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
    
    $action->lay->SetBlockData("SELECTPREF", $selectclass);
    $action->lay->Set("modaction", $modaction);
}

function onefam_editmasterpref(&$action)
{
    onefam_editpref($action, "ONEFAM_MIDS", "ONEFAM_MODMASTERPREF");
}
