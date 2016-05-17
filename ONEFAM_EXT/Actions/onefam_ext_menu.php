<?php
/*
 * @author Anakeen
 * @package FDL
*/
/**
 * Send javascript onefam collection menu
 *
 * @author Anakeen
 * @version $Id:  $
 * @package FDL
 * @subpackage
 */
/**
 */

include_once ("EXTUI/eui_xmlmenu.php");
/**
 * Colection menu
 * @param Action &$action current action
 * @global famid int var : family id for menu
 * @global fldid int var : id collection where is actually
 * @global menuxml string var : the xml menu file APP:file.xml
 */
function onefam_ext_menu(Action & $action)
{
    if (!file_exists('lib/ui/freedom-extui.js')) {
        $err = _("This action requires the installation of Dynacase Extui module");
        $action->exitError($err);
    }
    
    $fldid = $action->getArgument("fldid");
    $famid = $action->getArgument("famid");
    $docid = 0;
    $menuxml = $action->getArgument("menuxml", "EXTUI:default-collection-menu.xml");
    $menu = eui_getxmlmenu($docid, $menuxml, $fldid);
    
    $fld = null;
    if ($fldid) {
        $fld = new_Doc($action->dbaccess, $fldid);
        if (!$famid) {
            if ($fld->isAlive()) {
                $famid = $fld->getRawValue("se_famid");
            }
        }
    }
    
    unset($menu["menu"]["createsearch"]);
    $fam = new_Doc($action->dbaccess, $famid);
    $main = array();
    if ($fam->isAlive()) {
        if ($fam->control("icreate") == "") {
            $main["family"] = array(
                "type" => "menu",
                "label" => $fam->getTitle() ,
                //"icon"=>$fam->getIcon(),
                "items" => array()
            );
            
            $main["family"]["items"]["create"] = array(
                "script" => array(
                    "file" => "lib/ui/fdl-interface-action-common.js",
                    "class" => "Fdl.InterfaceAction.CreateDocument",
                    "parameters" => array(
                        "family" => $fam->id
                    )
                ) ,
                "label" => sprintf(_("Create %s") , $fam->getTitle()) ,
                "icon" => $fam->getIcon()
            );
        }
        $controlcreate = true;
        $tfam = $fam->getChildFam($fam->id, $controlcreate);
        
        if (count($tfam) > 0) {
            $main["family"]["items"]["subfam"] = array(
                "type" => "menu",
                "label" => _("other families") ,
                "items" => array()
            );
            foreach ($tfam as $k => $v) {
                $main["family"]["items"]["subfam"]["items"]["create" . $v["id"]] = array(
                    "script" => array(
                        "file" => "lib/ui/fdl-interface-action-common.js",
                        "class" => "Fdl.InterfaceAction.CreateDocument",
                        "parameters" => array(
                            "family" => $v["id"]
                        )
                    ) ,
                    "label" => sprintf(_("Create %s") , $v["title"]) ,
                    "icon" => $fam->getIcon($v["icon"])
                );
            }
        }
        
        if ($fldid && is_object($fld) && $fld->isAlive() && ($fld->doctype != 'T')) {
            $main["family"]["items"]["edit"] = array(
                "script" => array(
                    "file" => "lib/ui/fdl-interface-action-common.js",
                    "class" => "Fdl.InterfaceAction.EditSearchFilter"
                ) ,
                "label" => sprintf(_("Edit %s") , $fld->getTitle()) ,
                "icon" => $fld->getIcon()
            );
        }
        
        $fmenu = $fam->getMenuAttributes();
        if (count($fmenu) > 0) {
            $first = true;
            foreach ($fmenu as $k => $v) {
                if ($v->getOption("global") == "yes") {
                    
                    if ($first) $main["family"]["items"]["sepspec"] = array(
                        "type" => "separator"
                    );
                    $main["family"]["items"]["glob" . $k] = array(
                        "url" => $fam->urlWhatEncode($v->link) ,
                        "label" => $v->getLabel()
                    );
                    $first = false;
                }
            }
        }
        
        $menu = array(
            "menu" => array_merge($main, $menu["menu"])
        );
    }
    // print_r2($menu);
    $action->lay->noparse = true; // no need to parse after - increase performances
    $action->lay->template = json_encode($menu);
}
