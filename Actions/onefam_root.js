/*
GET.js v0.2
Copyright © 2012 Mickaël Raybaud-Roig, All rights reserved.
Licensed under the BSD 3-clause license, see the COPYING file for details
*/

(function () {

    window.onefam = window.onefam || {};

    window.onefam.iframeOverlay = null;

    window.onefam.debounce = function (func, wait, immediate) {
        var timeout, result;
        return function () {
            var context = this, args = arguments, callNow,
                later = function () {
                    timeout = null;
                    if (!immediate) {
                        result = func.apply(context, args);
                    }
                };
            callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) {
                result = func.apply(context, args);
            }
            return result;
        };
    };

    window.openIframeOverlay = window.onefam.openIframeOverlay = function openIframeOverlay(target, callback) {
        var $width = $(window).width() * 0.8,
            $height = $(window).height() * 0.8;
        if (!target) {
            return;
        }
        window.onefam.iframeOverlay = $('<iframe class="overlay-iframe" style="padding: 0;" src="' + target + '" />');
        window.onefam.iframeOverlay.dialog({
            autoOpen :    true,
            width :       $width,
            height :      $height,
            modal :       true,
            resizable :   false,
            autoResize :  true,
            draggable :   false,
            overlay :     {
                opacity :    0.5,
                background : "black"
            },
            beforeClose : function beforeClose() {
                window.onefam.iframeOverlay.attr("src", "Images/1x1.gif");
                return false;
            }
        }).width($width).height($height).on("load", function load() {
            var $this = $(this), doc;
            doc = this.contentDocument || this.contentWindow.document;
            if (doc) {
                window.onefam.iframeOverlay.dialog("option", "title", doc.title || "");
            }
            if (doc && doc.location && doc.location.href &&
                    doc.location.href.toLowerCase().indexOf("images/1x1.gif") > -1) {
                if ($.isFunction(callback)) {
                    if (window.onefam.manageSearch && window.onefam.manageSearch.nextURL) {
                        callback(window.onefam.manageSearch.nextURL);
                        window.onefam.manageSearch.nextURL = null;
                    } else {
                        callback("reload");
                    }
                }
                $this.remove();
                window.onefam.iframeOverlay = null;
            }
        });
    };

}());

var selimg = null;

function imgborder() {
    var i;
    for (i = 0; i < document.images.length; i += 1) {
        document.images[i].style.borderStyle = 'inset';
    }
}
function ctrlPushed(event) {
    if (!event) {
        event = window.event;
    }
    if (!event) {
        return false;
    }
    return event.ctrlKey;
}
function openiframe(event, th, docid) {
    var idf = 'if_' + docid, nf, reloadlist, lif, isrc, i, length;

    if (selimg !== null) {
        selimg.setAttribute("selected", 0);
    }
    if (th) {
        th.setAttribute("selected", 1);
        selimg = th;
    }

    nf = document.getElementById(idf);
    if (nf && (nf.style.display != 'none')) {
        reloadlist = true;
    }
    lif = document.getElementsByTagName('iframe');
    for (i = 0, length = lif.length; i < length; i += 1) {
        lif[i].style.display = 'none';
    }
    if (nf) {
        if (reloadlist) {
            if (window.frames[idf]) {
                lif = window.frames[idf].document.getElementsByTagName('frame');
                if (lif.length > 0) {
                    isrc = lif[0].src;
                    window.frames[idf].flist.location.href = isrc;
                }
            }
        }
        nf.style.display = '';
    } else {
        var tdi = document.getElementById('tdiframe');
        nf = document.createElement('iframe');
        nf.id = idf;
        nf.name = idf;
        nf.className = 'ifonefam';
        nf.src = '[CORE_STANDURL]&app=' + window.onefamParam.appName + '&action=ONEFAM_GENROOT&famid=' + docid;
        tdi.appendChild(nf);
        [IF ISAPPLEWEBKIT]
        window.setTimeout(function () {
            nf.style.width = '90%';
        }, 50);
        window.setTimeout(function () {
            nf.style.width = '';
        }, 100);
        [ENDIF ISAPPLEWEBKIT]
    }
}
function reloadiframe(event, th, docid) {
    var idf = 'if_' + docid;
    var nf;

    nf = document.getElementById(idf);
    if (nf) {
        nf.style.display = '';
        nf.src = '[CORE_STANDURL]&app=' + window.onefamParam.appName + '&action=ONEFAM_GENROOT&famid=' + docid;
    }
}

function openfirst(docid) {
    var i = document.getElementById('imgu' + docid);

    if (!i) {
        i = document.getElementById('imgm' + docid);
    }
    if (i) {
        i.onclick.apply(i, []);
    } else {
        openiframe(null, null, docid);
    }
}

function resizeIconList(colNumber) {
    var delta = 6;
    var il = document.getElementById('iconList');
    var iconWidth = parseInt(window.onefamParam.izpx, 10) + 4; // padding 2x2

    il.style.height = (getFrameHeight() - 15) + 'px';

    il.style.width = (colNumber * iconWidth + delta) + 'px';
    document.getElementById('tdiframe').style.left = (colNumber * iconWidth + delta) + 'px';
    if (il.scrollHeight > il.clientHeight) {
        delta = 20;
        il.style.width = (colNumber * iconWidth + delta) + 'px';
        document.getElementById('tdiframe').style.left = (colNumber * iconWidth + delta) + 'px';
    }
}
var resizeIconListTimer = 0;
function resizeIconListAtEnd(colNumber) {
    if (resizeIconListTimer) {
        clearTimeout(resizeIconListTimer);
    }
    resizeIconListTimer = setTimeout(function () {
        resizeIconList(colNumber);
        resizeIconList(colNumber);
    }, 200);
}

if (window.onefamParam.openfam) {
    addEvent(window, 'load', function () {
        openfirst(window.onefamParam.openfam);
    });
}

addEvent(window, 'load', function () {
    resizeIconList(window.onefamParam.colNumber);
    document.getElementById('loading').style.display = "none";
    document.getElementById('iconList').style.display = "";
});

addEvent(window, 'resize', window.onefam.debounce(function () {
    var $width = $(window).width() * 0.8,
    $height = $(window).height() * 0.8;
    if (window.onefam.iframeOverlay) {
        window.onefam.iframeOverlay.dialog("option", {
            width : $width,
            height : $height
        });
        window.onefam.iframeOverlay.width($width).height($height);
    }
    resizeIconListAtEnd(window.onefamParam.colNumber);
}, 500));