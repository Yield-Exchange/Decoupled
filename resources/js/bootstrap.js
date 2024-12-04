window._ = require("lodash");

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
import { getIPAddress } from "../js/utils/GlobalUtils";
window.axios = require("axios");
let cachedIpAddress = localStorage.getItem("ip_address");

window.axios.interceptors.request.use(async (config) => {
    if (cachedIpAddress) {
        config.headers["X-Client-IP"] = cachedIpAddress;
        return config;
    }

    const accessToken = "21f42bdc4db951";

    try {
        const response = await fetch(
            "https://api.ipify.org/?format=json&token=" + accessToken
        );
        const data = await response.json();
        // console.log('Data:', data);

        // Cache the obtained IP address
        cachedIpAddress = data?.ip;
        await axios.post("/set-session", { my_ip: cachedIpAddress });

        // Modify the config.headers synchronously
        config.headers["X-Client-IP"] = data?.ip;

        return config;
    } catch (error) {
        // console.error('Error:', error);
        // return Promise.reject(error);
    }
});

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
if (document.querySelector('meta[name="csrf-token"]')) {
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from "laravel-echo";

window.Pusher = require("pusher-js");

window.Echo = new Echo({
    broadcaster: "pusher",
    key: process.env.MIX_PUSHER_APP_KEY,
    // wsHost: process.env.PUSHER_APP_wsHost, /* For Laravel Web sockets */
    // wsPort: process.env.PUSHER_APP_wsPort,
    // wssPort: process.env.PUSHER_APP_wsPort,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    encrypted: true,
    // enabledTransports: ['ws', 'wss'],
    // disableStats: true,
});
