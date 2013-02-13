<?php

require_once 'FDL/freedom_util.php';
/**
 * Compute the manage search interface
 *
 * @param Action $action
 * @throws Exception
 */
function onefam_manage_search(Action &$action) {

    $usage = new ActionUsage($action);

    $famId = $usage->addRequiredParameter("famId", "famId");

    $famDoc = new_Doc('', $famId);

    if ($famDoc->isAlive()) {
        $action->lay->set("FAM_TITLE", $famDoc->getTitle());
        $action->lay->set("FAM_ID", $famId);
    }else {
        throw new Exception(sprintf("The family %s is not valid", $famId));
    }




}