const $ = require('jquery');

$(document).ready(function () {
    let header = $('.header-h');

    if ($(window).scrollTop() >= header.height()) {
        header.removeClass('header-high').addClass('header-shadow');
    }
});

$(document).scroll(function () {
    let header = $('.header-h');

    if ($(window).scrollTop() > header.height()) {
        header.removeClass('header-high').addClass('header-shadow');

    } else {
        header.addClass('header-high');
    }
});