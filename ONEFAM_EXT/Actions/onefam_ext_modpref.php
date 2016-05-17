<?php
/*
 * @author Anakeen
 * @package FDL
*/
/**
 * validate user or master choosen families
 *
 * @author Anakeen
 * @version $Id: onefam_modpref.php,v 1.8 2006/10/05 09:22:38 eric Exp $
 * @package FDL
 * @subpackage
 */
/**
 */

include_once ("FDL/Class.Doc.php");

function onefam_ext_modpref(Action & $action, $idsattr = "ONEFAM_IDS")
{
    $tidsfam = getHttpVars("idsfam", array()); // preferenced families
    $idsfam = json_decode($tidsfam);
    $idsfam = implode(",", $idsfam);
    
    $action->addLogMsg('TEST');
    
    if ($idsattr == "ONEFAM_IDS") {
        //$action->parent->param->Set($idsattr,$idsfam,PARAM_USER.$action->user->id,$action->parent->id);
        $action->setParamU($idsattr, $idsfam);
        //$action->parent->param->Set("ONEFAM_FAMOPEN",$openfam,PARAM_USER.$action->user->id,$action->parent->id);
        
    } else {
        $action->parent->param->Set($idsattr, $idsfam, PARAM_APP, $action->parent->id);
        //$action->parent->param->Set("ONEFAM_FAMOPEN",$openfam,PARAM_APP,$action->parent->id);
        
    }
    
    $out = array(
        'ids' => explode(",", $idsfam)
    );
    
    $action->lay->noparse = true; // no need to parse after - increase performances
    $action->lay->template = json_encode($out);
}

function onefam_ext_modmasterpref(Action & $action)
{
    onefam_ext_modpref($action, "ONEFAM_MIDS");
}
