/*ignore jslint start*/
"use strict";
var trackExitLinks = function () {
    var a = /^(https?:\/\/[^\/]+).*$/;
    var b = window.location.href.replace(a, "$1");
    var c = new RegExp("^" + b.replace(/\./, "\\."));
    var d = document.querySelectorAll("a");
    for (var e = 0; e < d.length; e++) {
        var f = d[e].href;
        if (f === "" || c.test(f) || !/^http/.test(f)) {
            continue;
        } else if (d[e].className.indexOf("gtb") !== -1) {
            firstPiece = f.indexOf(".");
            newhref = f.substring(firstPiece + 1);
            secondPiece = newhref.indexOf(".");
            newhref = f.substring(firstPiece + 1, firstPiece + secondPiece + 1);
            if(d[e].hasAttribute("onclick") === 0){
                d[e].setAttribute("onclick", "ga('send', 'event', 'Advertentie', '" + newhref + " ( " + f + " )', '" + window.location.href + "');");
            }
        } else {
            if(d[e].hasAttribute("onclick") === 0){
                d[e].setAttribute("onclick", "ga('send', 'event', external-link', '" + f + "', '"+window.location.href+"');");
            }
        }
    }
};

var trackPdfLinks = function () {
    var a = document.querySelectorAll("a");
    for (var b = 0; b < a.length; b++) {
        var c = a[b].href;
        if (c.indexOf("pdf") !== -1 && a[b].hasAttribute("onclick") === 0) {
            a[b].setAttribute("onclick", "ga('send', 'event', 'download', 'pdf', '" + c + "');");
        }
        if (c.indexOf("zip") !== -1 && a[b].hasAttribute("onclick") === 0) {
            a[b].setAttribute("onclick", "ga('send', 'event', 'download', 'zip', '" + c + "');");
        }
        if (c.indexOf("rar") !== -1 && a[b].hasAttribute("onclick") === 0) {
            a[b].setAttribute("onclick", "ga('send', 'event', 'download', 'rar', '" + c + "');");
        }
        if (c.indexOf("doc") !== -1 && a[b].hasAttribute("onclick") === 0) {
            a[b].setAttribute("onclick", "ga('send', 'event', 'download', 'doc', '" + c + "');");
        }
    }
};

window.onload = function () {
    trackExitLinks();
    trackPdfLinks();
};
/*ignore jslint end*/