window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });

/*setInterval(() => {
    //document.getElementById("mySavedModel").value
    //const diagData = go.Model.fromJson(document.getElementById("mySavedModel").value);
    const options = {
        method: 'post',
        url: '/send-diag2-update',
        data: {
            json: { "class": "GraphLinksModel",
                "linkFromPortIdProperty": "fromPort",
                "linkToPortIdProperty": "toPort",
                "modelData": {"position":"-215 -283.6820068359375"},
                "nodeDataArray": [
            {"text":"Existing System","figure":"Rectangle","fill":"lightgray","key":-4,"loc":"-90 -150"},
            {"text":"","figure":"Rectangle","fill":"transparent","size":"95, 70","key":-5,"loc":"60 -10"}
            ],
                "linkDataArray": [{"points":[-39.8253173828125,-150,-29.8253173828125,-150,60,-150,60,-102.5,60,-55,60,-45],"from":-4,"to":-5,"fromPort":"R","toPort":"T"}]},
        },
    }
      axios(options);
}, 2000);*/

