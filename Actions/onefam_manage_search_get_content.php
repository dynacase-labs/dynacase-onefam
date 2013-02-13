<?php

require_once 'ONEFAM/onefam_manage_search_util.php';
require_once 'FDL/freedom_util.php';

/**
 * Get the search for the list of onefam_manage_search
 *
 * @param Action $action
 */
function onefam_manage_search_get_content(Action &$action)
{

    $return = array(
        "success" => true,
        "error" => array(),
        "data" => array()
    );

    try {
        $dbaccess = $action->GetParam("FREEDOM_DB");
        $actionUsage = new ActionUsage($action);

        $famId = $actionUsage->addRequiredParameter("famId", "famId");
        $uuid = $actionUsage->addRequiredParameter("uuid", "uuid");

        $keyWord = $actionUsage->addOptionalParameter("keyWord", "keyWord", array(), '');
        $start = $actionUsage->addOptionalParameter("start", "start offset", array(), 0);
        $slice = $actionUsage->addOptionalParameter("slice", "offset length", array(), 'ALL');

        $actionUsage->setStrictMode(false);
        $actionUsage->verify(true);

        if ($famId && (!is_numeric($famId))) {
            $famId = getFamIdFromName($dbaccess, $famId);
        }

        $keyWords = array();

        $search = new SearchDoc("", "DSEARCH");
        $search->setStart($start);
        $search->setSlice($slice);
        $search->addFilter("owner = %s", $action->user->id);
        $search->addFilter("se_famid = '%s'", $famId);
        $search->orderby = 'title';
        if ($keyWord) {
            $keyWords = explode(" ", $keyWord);
            foreach ($keyWords as $currentKeyWord) {
                $search->addFilter("svalues ~* '%s'", $currentKeyWord);
            }
        }
        $search->setObjectReturn();

        $return["data"] = array(
            "result" => array(),
            "nbResult" => "",
            "uuid" => $uuid
        );

        $famDoc = new_Doc("", $famId);
        $dfldid = $famDoc->dfldid;

        $folder = null;

        if ($dfldid) {
            $folderDoc = new_Doc("", $dfldid);
            error_log(__METHOD__.var_export($folderDoc->isLocked(true), true));
            if ($folderDoc->isAlive()
                && $folderDoc->control("modify") == ""
                && !$folderDoc->isLocked(true)) {
                $folder = $dfldid;
            }
        }

        foreach ($search->getDocumentList() as $currentDocument) {
            /* @var $currentDocument Doc */
            $return["data"]["result"][] = getSearchAbstract($dbaccess, $currentDocument, $folder);
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