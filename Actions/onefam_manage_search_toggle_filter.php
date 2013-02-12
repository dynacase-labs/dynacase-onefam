<?php

require_once 'FDL/freedom_util.php';
require_once 'FDL/Lib.Dir.php';

/**
 * Toggle the filter state of a search
 *
 * @param Action $action
 * @throws Exception
 */
function onefam_manage_search_toggle_filter(Action &$action)
{
    $return = array(
        "success" => true,
        "error" => array(),
        "data" => array()
    );

    try {
        $err = "";
        $usage = new ActionUsage($action);

        $searchId = $usage->addRequiredParameter("searchId", "search id");

        $usage->setStrictMode(false);
        $usage->verify(true);

        $search = new_Doc("", $searchId);

        $err .= $search->setValue("se_memo", ($search->getRawValue("se_memo") === "yes")? "no" : "yes");

        $err .= $search->store();

        if ($err) {
            throw new Exception($err);
        }

    } catch (Exception $e) {
        $return["success"] = false;
        $return["error"][] = $e->getMessage();
        unset($return["data"]);
    }

    $action->lay->template = json_encode($return);
    $action->lay->noparse = true;
    header('Content-type: application/json');

}
