<?php

require_once "GENERIC/generic_util.php";

function onefam_manage_search_toggle_default(Action &$action)
{
    $return = array(
        "success" => true,
        "error" => array(),
        "data" => array()
    );

    try {
        $actionUsage = new ActionUsage($action);

        $famId = $actionUsage->addRequiredParameter("famid", "famid");
        $searchId = $actionUsage->addRequiredParameter("searchId", "searchId");

        $defaultSearchId = getDefU($action, "GENE_PREFSEARCH");

        if ($searchId === $defaultSearchId) {
            setFamilyParameter($action, $famId, 'GENE_PREFSEARCH', "");
        }else {
            setFamilyParameter($action, $famId, 'GENE_PREFSEARCH', $searchId);
            $search = new_Doc("", $searchId);
            $search->setValue("se_memo","yes");
            $search->disableEditControl();
            $search->store();
        }

    } catch (Exception $e) {
        $return["success"] = false;
        $return["error"][] = $e->getMessage();
        unset($return["content"]);
    }

    $action->lay->template = json_encode($return);
    $action->lay->noparse = true;
    header('Content-type: application/json');

}