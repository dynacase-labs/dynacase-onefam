var selimg = null;

function imgborder() {
    for (var i = 0; i < document.images.length; i++) {
        document.images[i].style.borderStyle = 'inset';
    }
}
function ctrlPushed(event) {
    if (!event) event = window.event;
    if (!event) return false;
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
    for (i = 0, length = lif.length; i < length; i++) {
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
});
addEvent(window, 'resize', function () {
    resizeIconListAtEnd(window.onefamParam.colNumber);
});