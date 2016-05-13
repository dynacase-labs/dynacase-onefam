<?php
/*
 * @author Anakeen
 * @package FDL
*/
/**
 * Retrieve search from onefam
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
 *  Retrieve search from onefam
 *
 * @param Action &$action current action
 * @global appid int var : application name
 */
function onefam_gettreefamily(Action & $action)
{
    $out = onefam_getDataTreeFamily($action);
    
    $action->lay->noparse = true; // no need to parse after - increase performances
    $action->lay->template = json_encode($out);
}

function onefam_getDataTreeFamily(Action & $action)
{
    $tfs = array();
    
    $mids = explode(",", $action->getParam("ONEFAM_MIDS"));
    
    foreach ($mids as $fid) {
        if ($fid) {
            $cdoc = new_Doc($action->dbaccess, $fid);
            if ($cdoc->isAlive() && $cdoc->control('view') == "") {
                $fs = getFamilySearches($action->dbaccess, $fid);
                if ($fs) $tfs[] = $fs;
            }
        }
    }
    $utfs = array();
    
    $umids = explode(",", $action->getParam("ONEFAM_IDS"));
    
    foreach ($umids as $fid) {
        if ($fid && ($fs = getFamilySearches($action->dbaccess, $fid))) $utfs[] = $fs;
    }
    
    $out = array(
        "application" => array(
            "name" => $action->parent->name,
            "label" => _($action->parent->description)
        ) ,
        "user" => $utfs,
        "admin" => $tfs
    );
    return $out;
}
function getFamilySearches($dbaccess, $fid)
{
    /**
     * @var \DocFam $fam
     */
    $fam = new_Doc($dbaccess, $fid);
    
    if ($fam->isAlive()) {
        $to["info"] = array(
            "id" => $fam->id,
            "title" => $fam->getTitle() ,
            "icon" => $fam->getIcon()
        );
        
        $s = new SearchDoc($dbaccess, "SEARCH");
        $s->addFilter("owner=" . $fam->userid);
        $s->addFilter("se_famid='" . $fam->id . "'");
        $s->setObjectReturn();
        $s->search();
        while ($v = $s->getNextDoc()) {
            
            $to["userSearches"][] = array(
                "id" => $v->id,
                "icon" => $v->getIcon() ,
                "title" => $v->getTitle()
            );
        }
        
        $s = new SearchDoc($dbaccess, "SEARCH");
        $s->dirid = $fam->dfldid;
        $s->setObjectReturn();
        $s->search();
        while ($v = $s->getNextDoc()) {
            
            $to["adminSearches"][$fid] = array(
                "id" => $v->id,
                "icon" => $v->getIcon() ,
                "title" => $v->getTitle()
            );
        }
        
        if ($fam->wid > 0) {
            /**
             * @var \WDoc $w
             */
            $w = new_Doc($dbaccess, $fam->wid);
            if ($w->isAlive()) {
                
                foreach ($w->getStates() as $c) {
                    $to["workflow"][$c] = array(
                        "state" => $c,
                        "label" => _($c) ,
                        "activity" => $w->getActivity($c) ,
                        "color" => $w->getColor($c)
                    );
                }
            }
        }
        
        return $to;
    } else return null;
}
