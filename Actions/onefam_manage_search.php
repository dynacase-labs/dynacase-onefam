<?php
/*
 * @author Anakeen
 * @license http://www.fsf.org/licensing/licenses/agpl-3.0.html GNU Affero General Public License
 * @package FDL
*/

require_once 'FDL/freedom_util.php';
require_once 'GENERIC/generic_util.php';
/**
 * Compute the manage search interface
 *
 * @param Action $action
 * @throws Exception
 */
function onefam_manage_search(Action & $action)
{
    
    $usage = new ActionUsage($action);
    
    $famId = $usage->addRequiredParameter("famId", "famId");
    
    $famDoc = new_Doc('', $famId);
    
    $action->parent->AddCssRef("css/dcp/jquery-ui.css");
    $action->parent->AddCssRef("ONEFAM:onefam_manage_search.css");
    if ($famDoc->isAlive()) {
        $action->lay->set("FAM_TITLE", str_replace('"', '\"', $famDoc->getTitle()));
        $action->lay->set("FAM_ID", str_replace('"', '\"', $famId));
        $action->lay->set("alsosub", getInherit($action, $famId));
        $sMngConfig = $action->getParam("ONEFAM_SEARCHMANAGERCONFIG");
        
        $mngConfig = json_decode($sMngConfig, true);
        if ($mngConfig === false) {
            throw new Exception(sprintf("Invalid parameter ONEFAM_SEARCHMANAGERCONFIG : \"%s\"", $sMngConfig));
        }
        if (!is_array($mngConfig)) {
            throw new Exception(sprintf("Not an json array parameter ONEFAM_SEARCHMANAGERCONFIG : \"%s\"", $sMngConfig));
        }
        $createSearch = array();
        foreach ($mngConfig as $aSearchConfig) {
            $famId = $aSearchConfig["familyId"];
            $fam = new_doc($action->dbaccess, $famId);
            if ($fam->isAlive() && $fam->doctype == 'C' && $fam->control('icreate') == '') {
                
                $createLabel = $aSearchConfig["createLabel"];
                if ($createLabel) {
                    $createLabel = _($createLabel);
                } else {
                    $createLabel = sprintf(_("onefam:create %s") , $fam->getTitle());
                }
                $createSearch[] = array(
                    "searchFamId" => $fam->id,
                    "createSearchFamLabel" => $createLabel
                );
            }
        }
        $action->lay->setBlockData("CREATESEARCH", $createSearch);
    } else {
        throw new Exception(sprintf("The family %s is not valid", $famId));
    }
}
