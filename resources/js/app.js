require('./bootstrap');
import Vue from 'vue';

import CKEditor from '@ckeditor/ckeditor5-vue2';

import VueCompositionAPI from '@vue/composition-api'
import store from './store';
import axios from 'axios';
Vue.use(VueCompositionAPI);

import VueApexCharts from 'vue-apexcharts';
import VueCarousel from 'vue-carousel';
Vue.use(VueApexCharts);
Vue.use(CKEditor);
Vue.use(VueCarousel);
Vue.use(axios)

Vue.component('apexchart', VueApexCharts);

import { Cropper } from 'vue-advanced-cropper';
import 'vue-advanced-cropper/dist/style.css';

if (document.getElementById("VueApp")) {
    require('./init');
}

Vue.config.productionTip = false;
require('./components');

if (document.getElementById("VueApp")) {
    new Vue({
        el: '#VueApp',
        store,
        updated() {
        },
    });

}

if (typeof user_object !== "undefined") {
    new Vue({
        el: '#nav-chats',

        data: {
            chats: 0
        },

        created() {
            this.fetchChatsCounts();
            Echo.private('DepositChatCountMessages.' + user_object.organization_id)
                .listen('DepositChatMessagesCountEvent', (e) => {
                    this.chats = e.count;
                    // console.log(e);
                });
        },

        updated() {
            this.scroll_to_bottom();
        },
        methods: {
            fetchChatsCounts() {
                axios.get('/deposit-chats.unread.count').then(response => {
                    this.chats = response.data;
                }).catch(e => { });
            }
        }
    });

    new Vue({
        el: '#nav-notify',

        data: {
            notifications: 0
        },
        watch: {
            notifications(n, o) {
                // console.log(n,o) // n is the new value, o is the old value.
            }
        },
        created() {
            this.fetchNotifyCounts();
            Echo.private('NotificationsCount.' + user_object.organization_id)
                .listen('NotificationsCountEvent', (e) => {
                    this.notifications = e.count;
                    // console.log(e);
                });
        },

        updated() {
        },

        methods: {
            fetchNotifyCounts() {
                axios.get('/notifications.unread.count').then(response => {
                    this.notifications = response.data;
                }).catch(e => { });
            }
        }
    });
}
if (typeof deposit_object !== "undefined") {
    new Vue({
        el: '#chatApp',

        data: {
            messages: []
        },
        methods: {
            scroll_to_bottom() {
                let container = this.$el.querySelector("#chatMessage");
                let shouldScroll = container.scrollTop + container.clientHeight === container.scrollHeight;
                if (!shouldScroll) {
                    container.scrollTop = container.scrollHeight;
                }
            }
        },

        created() {
            this.fetchMessages();
            Echo.private('Deposit_chat.' + deposit_object.reference_no)
                .listen('DepositChatEvent', (e) => {
                    this.messages.push(e);
                    this.markAsSeen(e);
                });
        },

        updated() {
            this.scroll_to_bottom();
        },

        methods: {
            fetchMessages() {
                axios.get('/deposit-chat-room/messages/' + deposit_object.id).then(response => {
                    this.messages = response.data.data;
                }).catch(e => { });
            },

            addMessage(message) {
                axios.post('/deposit-chat-room', message, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(response => {
                    this.messages.push(response.data.data);
                }).catch(e => { });
                // axios.post('/deposit-chat-room', message).then(response => {
                //     this.messages.push(response.data.data);
                // });
            },

            scroll_to_bottom() {
                let container = this.$el.querySelector(".chat-container");
                let shouldScroll = container.scrollTop + container.clientHeight === container.scrollHeight;
                if (!shouldScroll) {
                    container.scrollTop = container.scrollHeight;
                }
            },

            markAsSeen(message) {
                axios.get('/deposit-chat-room-mark-read', message).then(response => {
                    //  nothing
                }).catch(e => { });
            }
        }
    });
}