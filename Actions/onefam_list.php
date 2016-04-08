<?php
/*
 * @author Anakeen
 * @package FDL
*/
/**
 * list available families
 *
 * @author Anakeen
 * @version $Id: onefam_list.php,v 1.13 2007/01/03 19:38:59 eric Exp $
 * @package FDL
 * @subpackage
 */
/**
 */

include_once ("FDL/Class.Doc.php");
include_once ("FDL/Lib.Dir.php");

function onefam_list(Action & $action)
{
    $action->lay->set("APP_TITLE", _($action->parent->description));
    
    $action->parent->AddJsRef($action->GetParam("CORE_JSURL") . "/subwindow.js");
    $action->parent->AddJsRef($action->GetParam("CORE_JSURL") . "/resizeimg.js");
    
    $action->lay->SetBlockData("SELECTMASTER", getTableFamilyList($action->GetParam("ONEFAM_MIDS")));
    
    if (($action->GetParam("ONEFAM_IDS") != "") && ($action->GetParam("ONEFAM_MIDS") != "")) {
        $action->lay->SetBlockData("SEPARATOR", array(
            array(
                "zou"
            )
        ));
    }
    
    if ($action->HasPermission("ONEFAM")) {
        $action->lay->SetBlockData("CHOOSEUSERFAMILIES", array(
            array(
                "zou"
            )
        ));
        $action->lay->SetBlockData("SELECTUSER", getTableFamilyList($action->GetParam("ONEFAM_IDS")));
    }
    if ($action->HasPermission("ONEFAM_MASTER")) {
        $action->lay->SetBlockData("CHOOSEMASTERFAMILIES", array(
            array(
                "zou"
            )
        ));
    }
    $iz = $action->getParam("CORE_ICONSIZE");
    $izpx = intval($action->getParam("SIZE_IMG-SMALL"));
    
    $action->lay->set("izpx", $izpx);
}

function getTableFamilyList($idsfam)
{
    $selectclass = array();
    if ($idsfam != "") {
        $tidsfam = explode(",", $idsfam);
        
        $dbaccess = GetParam("FREEDOM_DB");
        
        foreach ($tidsfam as $k => $cid) {
            $cdoc = new_Doc($dbaccess, $cid);
            if ($cdoc->dfldid > 0) {
                if ($cdoc->control('view') == "") {
                    $selectclass[$k]["idcdoc"] = $cdoc->initid;
                    $selectclass[$k]["ftitle"] = $cdoc->getHTMLTitle();
                    $selectclass[$k]["iconsrc"] = $cdoc->getIcon();
                }
            }
        }
    }
    return $selectclass;
}
?>
