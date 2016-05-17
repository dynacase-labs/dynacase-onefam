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
    
    $action->parent->addJsRef($action->getParam("CORE_JSURL") . "/subwindow.js");
    $action->parent->addJsRef($action->getParam("CORE_JSURL") . "/resizeimg.js");
    
    $action->lay->setBlockData("SELECTMASTER", getTableFamilyList($action->getParam("ONEFAM_MIDS")));
    
    if (($action->getParam("ONEFAM_IDS") != "") && ($action->getParam("ONEFAM_MIDS") != "")) {
        $action->lay->setBlockData("SEPARATOR", array(
            array(
                "zou"
            )
        ));
    }
    
    if ($action->hasPermission("ONEFAM")) {
        $action->lay->setBlockData("CHOOSEUSERFAMILIES", array(
            array(
                "zou"
            )
        ));
        $action->lay->setBlockData("SELECTUSER", getTableFamilyList($action->getParam("ONEFAM_IDS")));
    }
    if ($action->hasPermission("ONEFAM_MASTER")) {
        $action->lay->setBlockData("CHOOSEMASTERFAMILIES", array(
            array(
                "zou"
            )
        ));
    }
    $izpx = intval($action->getParam("SIZE_IMG-SMALL"));
    
    $action->lay->set("izpx", $izpx);
}

function getTableFamilyList($idsfam)
{
    $selectclass = array();
    if ($idsfam != "") {
        $tidsfam = explode(",", $idsfam);
        
        foreach ($tidsfam as $k => $cid) {
            /**
             * @var \DocFam $cdoc
             */
            $cdoc = new_Doc('', $cid);
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
