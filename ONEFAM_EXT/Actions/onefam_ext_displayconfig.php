<?php
/*
 * @author Anakeen
 * @package FDL
*/
/**
 * Retrieve and store ext display config for onefam
 *
 * @author Anakeen
 * @package FDL
 * @subpackage
 */
/**
 */

include_once ("FDL/Class.Dir.php");
include_once ("FDL/Class.SearchDoc.php");

include_once ("FDL/Class.Doc.php");
/**
 *  Retrieve ext display config from onefam
 *
 * @param Action &$action current action
 *
 */

function onefam_ext_getdisplayconfig(&$action)
{
    
    $dbaccess = $action->GetParam("FREEDOM_DB");
    
    $config = $action->getParam("ONEFAM_EXT_DISPLAYCONFIG", "{}");
    //$out=array("config"=>$config);
    return $config;
    //  $action->lay->noparse=true; // no need to parse after - increase performances
    //  $action->lay->template= json_encode($out);
    
    
}
/**
 *  Set ext display config from onefam
 *
 * @param Action &$action current action
 *
 */
function onefam_ext_setdisplayconfig(&$action)
{
    $config = GetHttpVars("config");
    
    $dbaccess = $action->GetParam("FREEDOM_DB");
    
    $action->setParamU("ONEFAM_EXT_DISPLAYCONFIG", $config);
    //$out = array("config"=>$config);
    $action->lay->noparse = true; // no need to parse after - increase performances
    $action->lay->template = $config;
}
?>