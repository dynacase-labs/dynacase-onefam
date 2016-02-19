<?php
/*
 * @author Anakeen
 * @license http://www.fsf.org/licensing/licenses/agpl-3.0.html GNU Affero General Public License
 * @package FDL
*/
/**
 * Generated Header (not documented yet)
 *
 * @author Anakeen
 * @version $Id: onefam_root.php,v 1.9 2008/04/18 09:47:38 eric Exp $
 * @license http://www.fsf.org/licensing/licenses/agpl-3.0.html GNU Affero General Public License
 * @package FDL
 * @subpackage
 */
/**
 */
include_once ("GENERIC/generic_util.php");
function onefam_root(Action & $action)
{
    // -----------------------------------
    $mode = $action->getArgument("mode");
    if (!$mode) $mode = $action->getParam("ONEFAM_DISPLAYMODE");
    if ($mode == "extjs") {
        include_once ("ONEFAM/onefam_ext.php");
        $action->lay = new Layout(getLayoutFile("ONEFAM", "onefam_ext.xml") , $action);
        onefam_ext($action);
    } else {
        $searchMode=$action->getParam("ONEFAM_SEARCHMODE", "words");
         $action->lay->set("searchWords", ($searchMode==="words"));
        $action->lay->set("ONEFAM_JS", $action->parent->getJsLink("ONEFAM:onefam_root.js"));
        $action->lay->set("APP_TITLE", _($action->parent->description));
        
        $nbcol = intval($action->getParam("ONEFAM_LWIDTH", 1));
        $action->lay->set("colNumber", $nbcol);
        
        $delta = 0;
        if ($action->read("navigator") == "EXPLORER") {
            $delta = 1;
        }
        
        $dbaccess = $action->GetParam("FREEDOM_DB");
        
        $izpx = intval($action->getParam("SIZE_IMG-SMALL"));
        $action->lay->set("wcols", $izpx * $nbcol + $delta);
        $action->lay->set("Title", _($action->parent->short_name));
        
        $openfam = $action->getParam("ONEFAM_FAMOPEN");
        if (($openfam != "") && (!is_numeric($openfam))) $openfam = getFamIdFromName($dbaccess, $openfam);
        if ($openfam > 0) {
            $action->lay->set("OPENFAM", true);
            $action->lay->set("openfam", $openfam);
        } else {
            $action->lay->set("OPENFAM", false);
        }
        
        $action->parent->AddCssRef("css/dcp/main.css");
        $action->parent->AddCssRef("css/dcp/jquery-ui.css");
        $action->parent->AddCssRef("ONEFAM:onefam.css", true);
        $action->parent->AddJsRef($action->GetParam("CORE_JSURL") . "/subwindow.js");
        $action->parent->AddJsRef($action->GetParam("CORE_JSURL") . "/resizeimg.js");
        $action->parent->AddJsRef($action->GetParam("CORE_JSURL") . "/geometry.js");
        
        $action->lay->Set("oneBgColor", (($action->getParam("ONEFAM_BGCOLOR") != 'inherit') && ($action->getParam("ONEFAM_BGCOLOR") != '')));
        
        $action->lay->SetBlockData("SELECTMASTER", getTableFamilyList($action->GetParam("ONEFAM_MIDS") , $izpx));
        
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
            $action->lay->SetBlockData("SELECTUSER", getTableFamilyList($action->GetParam("ONEFAM_IDS") , $izpx));
        }
        if ($action->HasPermission("ONEFAM_MASTER")) {
            $action->lay->SetBlockData("CHOOSEMASTERFAMILIES", array(
                array(
                    "zou"
                )
            ));
        }
        
        $action->lay->set("izpx", $izpx);
    }
}
function getTableFamilyList($idsfam, $izpx = null)
{
    $selectclass = array();
    if ($idsfam != "") {
        $tidsfam = explode(",", $idsfam);
        
        $dbaccess = GetParam("FREEDOM_DB");
        
        foreach ($tidsfam as $k => $cid) {
            /**
             * @var DocFam $cdoc
             */
            $cdoc = new_Doc($dbaccess, $cid);
            if ($cdoc->isAlive() && $cdoc->dfldid > 0) {
                
                if ($cdoc->control('view') == "") {
                    $selectclass[$k]["idcdoc"] = $cdoc->initid;
                    $selectclass[$k]["familyName"] = $cdoc->name;
                    $selectclass[$k]["ftitle"] = $cdoc->getHtmltitle();
                    $selectclass[$k]["iconsrc"] = $cdoc->getIcon('', $izpx);
                }
            }
        }
    }
    return $selectclass;
}
?>