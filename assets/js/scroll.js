const $ = require('jquery');

$(document).ready(function () {
    let header = $('.header-h');

    if ($(window).scrollTop() >= header.height()) {
        header.removeClass('header-high');
    }
});

$(document).scroll(function () {
    let header = $('.header-h');

    if ($(window).scrollTop() > header.height()) {
        header.removeClass('header-high');

    } else {
        header.addClass('header-high');
    }
});