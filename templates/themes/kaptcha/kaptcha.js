/*
 * kaptcha.js - uses KolymaNET's Kaptcha service
 * https://github.com/KolymaNET/Tinyboard-Addons/tree/main/templates/themes/kaptcha/upload-selection.js
 *
 *
 * Usage:
 *   $config['additional_javascript'][] = 'js/jquery.min.js';
 *   $config['additional_javascript'][] = 'js/kaptcha.js';
 *                                                  
 */
 
// initialize
var reloadTimer;
var UNIQUE_KEY;
var k;
if (typeof(k) === "undefined") {
    document.head.insertAdjacentHTML('beforeEnd', '<link rel="stylesheet" href="https://sys.kolyma.org/kaptcha/kaptcha.css">');
    k = 0;
}
k++;
 
// setup
UNIQUE_KEY = gen_key();
var KaptchaTable  = `
<table id="ka` + k + `" style="float: left;" class="kaptcha" cellspacing="3" cellpadding="0"><tbody>
    <tr>
        <td class="ka_challenge">
            <img src="" alt="" width="180" height="60">
        </td>
        <td class="k_btns" rowspan="2" width="64" height="64" valign="TOP">
            <small>[<a href="javascript:void(0);" onclick="ka_reload(false);">Reload</a>]</small>
            <a href="https://www.kolyma.net/software/kaptcha" target="_blank" class="ka_q">?</a>
            <small>[<a href="javascript:void(0);" onclick="ka_check(this);">Check</a>]</small>
        </td>
    </tr>
    <tr>
        <td valign="BOTTOM">
            <input size="10" type="text" name="_KAPTCHA" value="" class="ka_input" placeholder="Please enter the text above" autocomplete="off" />
            <input type="hidden" name="_KAPTCHA_KEY" value="` + UNIQUE_KEY + `">
        </td>
    </tr>
</tbody></table>
`;

// reload
async function ka_reload(a = true) {
    clearInterval(reloadTimer);
    var xhttp;
    if (window.XMLHttpRequest)
        xhttp = new XMLHttpRequest();
    else
        xhttp = new ActiveXObject("Microsoft.XMLHTTP");
    xhttp.onload = function () {
        for (var i = k; i; i--) {
            var ka = document.getElementById("ka"+i);
            if (typeof(ka) === "undefined") continue;
            if (xhttp.responseText.startsWith("NONE"))
                ka.getElementsByClassName("ka_challenge")[0].querySelector("img").src = "https://sys.kolyma.org/kaptcha/vip.png";
            if (!xhttp.responseText.startsWith("NEW")) return;
            ka.getElementsByClassName("ka_challenge")[0].querySelector("img").src = xhttp.responseText.slice(4);
            ka.getElementsByClassName("ka_input")[0].value = "";
        }
    };
    xhttp.open("GET", "https://sys.kolyma.org/kaptcha/kaptcha.php?key="+UNIQUE_KEY);
    xhttp.send();

    if (a) reloadTimer = setTimeout(ka_reload, 300000);
}

// preview kaptcha results
async function ka_check(form) {
    var guess = document.querySelector("#ka"+k+" input").value;
    var xhttp;
    if (window.XMLHttpRequest)
        xhttp = new XMLHttpRequest();
    else
        xhttp = new ActiveXObject("Microsoft.XMLHTTP");
    xhttp.onload = function () {
        if (!xhttp.responseText.startsWith("CHECK")) return;
        if (xhttp.responseText.search("CHECK correct") != -1) {
            for (var i = k; i > 0; i--) {
                var ka = document.getElementById("ka"+i);
                if (typeof(ka) === "undefined") continue;
                ka.getElementsByClassName("ka_challenge")[0].querySelector("img").src = "https://sys.kolyma.org/kaptcha/complete.png";
            }
        } else {
            ka_reload(false);
        }
    };
    xhttp.open("GET", "https://sys.kolyma.org/kaptcha/kaptcha.php?key="+UNIQUE_KEY+"&_KAPTCHA="+guess);
    xhttp.send();
}

// :3
function gen_key() {
    var result = "";
    var characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789=-";
    for (var i = 0; i < 64; i++)
        result += characters.charAt(Math.floor(Math.random() * 64));
    return result;
}

// here we go!
$(function(){
    
    // Reply? No sir.
	if($('div.banner').length == 0){
		$("<tr><th>"+_("Kaptcha")+"</th><td id='kaptcha'></td></tr>").insertBefore("#upload");
		$("#kaptcha").html(KaptchaTable);
		ka_reload();
	}
	
});
