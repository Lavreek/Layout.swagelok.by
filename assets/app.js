/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (styles.scss in this case)
import './styles/styles.scss';

const $ = require('jquery');
const bootstrap = require('bootstrap');

// start the Stimulus application
import './bootstrap';
import './js/scroll';
import './js/sendOffer';
import './js/jquery.cookie';

$(document).ready(function () {
    // Initialize the agent at application startup.
    const fpPromise = new Promise((resolve, reject) => {
        const script = document.createElement('script');
        script.onload = resolve;
        script.onerror = reject;
        script.async = true;
        script.src = 'https://cdn.jsdelivr.net/npm/'
            + '@fingerprintjs/fingerprintjs-pro@3/dist/fp.min.js';
        document.head.appendChild(script);
    })
        .then(() => FingerprintJS.load({token: 'h7x0DxO8VolroOKyIOMk'}));

    // Get the visitor identifier when you need it.
    fpPromise
        .then(fp => fp.get())
        .then(result => {
            // This is the visitor identifier:
            const visitorId = result.visitorId;
            let width = $(window).width();

            $.cookie('width', width);
            $.cookie('FINGERPRINT_ID', visitorId);

            $.ajax({
                type: "POST",
                url: '/visit',
                data:
                    'FINGERPRINT_ID=' + visitorId +
                    '&Width=' + width,

                success: function (data) {
                    if (data === 'create') { // Delete in production
                        $.cookie('_ym_uid', 'undefined-' + visitorId);

                    }
                }
            });
        })
})