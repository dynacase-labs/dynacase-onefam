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

function onefam_modpref(Action & $action, $idsattr = "ONEFAM_IDS")
{
    $tidsfam = getHttpVars("idsfam", array()); // preferenced families
    $openfam = getHttpVars("preffirstfam"); //
    $idsfam = implode(",", $tidsfam);
    
    if ($idsattr == "ONEFAM_IDS") {
        $action->parent->param->Set($idsattr, $idsfam, Param::PARAM_USER . $action->user->id, $action->parent->id);
        $action->parent->param->Set("ONEFAM_FAMOPEN", $openfam, Param::PARAM_USER . $action->user->id, $action->parent->id);
    } else {
        $action->parent->param->Set($idsattr, $idsfam, Param::PARAM_APP, $action->parent->id);
        $action->parent->param->Set("ONEFAM_FAMOPEN", $openfam, Param::PARAM_APP, $action->parent->id);
    }
    
    Redirect($action, getHttpVars("app") , "ONEFAM_ROOT");
}
function onefam_modmasterpref(Action & $action)
{
    onefam_modpref($action, "ONEFAM_MIDS");
}
