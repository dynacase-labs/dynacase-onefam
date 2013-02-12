<?php

require_once 'FDL/Lib.Dir.php';

/**
 * Generate the search abstract
 *
 * @param $dbaccess
 * @param Doc $currentDocument
 * @param null|int $folderId id of the family folder
 * @return array
 */
function getSearchAbstract($dbaccess, Doc $currentDocument, $folderId){
    $abstract =  array(
                "title" => $currentDocument->getTitle(),
                "id" => $currentDocument->getPropertyValue("id"),
                "initid" => $currentDocument->getPropertyValue("initid"),
                "icon" => $currentDocument->getIcon(),
                "url" => "?app=FDL&action=OPENDOC&mode=view&latest=Y&id=". $currentDocument->getPropertyValue("initid"),
                "isAlive" => $currentDocument->isAlive(),
                "isInFilter" => ($currentDocument->getRawValue("se_memo") === "yes")? "true" : "false",
                "isEditable" => ($currentDocument->canEdit() === "") ? "true": "false",
                "isDeleteable" => ($currentDocument->control("suppress") === "") ? "true": "false",
                "isInFamilyDir" => $folderId ? isInDir($dbaccess, $folderId, $currentDocument->getPropertyValue("id")) ? "true" : "false" : "none"
            );
    return array(
        "abstractData" => $abstract,
        "abstractHTML" => generateSearchAbstractHTML($abstract)
    );
}

/**
 * Compute the HTML of the search list
 *
 * @param array $abstractData
 * @return string
 */
function generateSearchAbstractHTML(Array $abstractData) {
    $title = _("ONEFAM:SEARCH_MANAGEMENT:Click to see sub menu");
return <<<"TEMPLATE"
<li class="css-abstract" data-url="{$abstractData["url"]}"
    data-id="{$abstractData["id"]}"
    data-is-in-filter="{$abstractData["isInFilter"]}"
    data-is-editable="{$abstractData["isEditable"]}"
    data-is-deleteable="{$abstractData["isDeleteable"]}"
    data-is-in-family-dir="{$abstractData["isInFamilyDir"]}"
    >
    <div class="css-abstractIconZone">
        <img class="css-abstractIcon" src='{$abstractData["icon"]}'/>
    </div>
    <span class="css-abstractTextZone">
        <span class="css-abstractTitle">{$abstractData["title"]}</span>
    </span>
    <div class="ui-state-highlight ui-corner-all css-abstract-subElement
    js-abstract-subElement css-abstract-subElement-hidden"
    title="$title">
        <span class="ui-icon ui-icon-triangle-1-e css-abstract-subElement-icon" ></span>
    </div>
</li>
TEMPLATE;
}