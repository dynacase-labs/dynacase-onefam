<?php

require_once 'FDL/freedom_util.php';
require_once 'FDL/Lib.Dir.php';

/**
 * Toggle the share state of a search
 *
 * @param Action $action
 * @throws Exception
 */
function onefam_manage_share(Action &$action)
{
    $return = array(
        "success" => true,
        "error" => array(),
        "data" => array()
    );

    try {
        $usage = new ActionUsage($action);

        $famId = $usage->addRequiredParameter("famId", "family id");
        $searchId = $usage->addRequiredParameter("searchId", "search id");

        $usage->setStrictMode(false);
        $usage->verify(true);

        $dbaccess = $action->GetParam("FREEDOM_DB");

        $famDoc = new_Doc("", $famId);
        $dfldid = $famDoc->dfldid;

        if ($dfldid) {
            $folder = new_Doc("", $dfldid);
            /* @var DIR $folder */
            if ($folder->isAlive()) {
                if (!isInDir($dbaccess, $dfldid, $searchId)) {
                    $err = $folder->insertDocument($searchId);
                }else {
                    $err = $folder->removeDocument($searchId);
                }
                if ($err) {
                    throw new Exception($err);
                }
            }else {
                throw new Exception(sprintf("Folder %s is not alive", $dfldid));
            }
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
