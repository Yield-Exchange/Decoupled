// campaignModule.js
import * as types from "./mutation-types.js";

const RepoPostRequestModule = {
    namespaced: true,
    state: {
        // offers
        offers: null,

        fis: null,

        selected_fis: null,

        istriparty: 'tri',

        offer_rates: {},

        created_from: null,

        created_from_id: null,

        selected_offers: null,


        formated_timezone: null,

        triparty_baskets: null,
        tripaties: null,
        primerates: null,
        holidays: null,
        daycount: null

    },
    mutations: {
        [types.SET_OFFERS](state, payloaad) {
            state.offers = payloaad
        },

        [types.SET_FIS](state, payloaad) {
            state.fis = payloaad
        },

        [types.SET_SELECTED_FIS](state, payloaad) {
            state.selected_fis = payloaad
        },
        [types.SET_TRIPARTY_TYPE](state, payloaad) {
            state.istriparty = payloaad
        },
        [types.SET_CREATED_FROM](state, payloaad) {
            state.created_from = payloaad
        },
        [types.SET_CREATED_FROM_ID](state, payloaad) {
            state.created_from_id = payloaad
        },
        [types.SET_SELECTED_OFFERS](state, payloaad) {
            state.selected_offers = payloaad
        },
        [types.SET_OFFER_RATES](state, payloaad) {
            state.offer_rates[payloaad[0]] = payloaad[1]
        },

        [types.SET_TRIPARTIES](state, payloaad) {
            state.tripaties = payloaad
        },
        [types.SET_TRIPARTIES_BASKET](state, payloaad) {
            state.triparty_baskets = payloaad
        },
        [types.SET_PRIME_RATES](state, payloaad) {
            state.primerates = payloaad
        },
        [types.SET_TIME_ZONE](state, payloaad) {
            state.formated_timezone = payloaad
        },
        [types.SET_HOLIDAYS](state, payloaad) {
            state.holidays = payloaad
        },
        [types.SET_DAY_COUNT](state, payloaad) {
            state.daycount = payloaad
        },

    },
    actions: {},
    getters: {
        getOffers(state) {
            return state.offers;
        },
        getFIS(state) {
            return state.fis;
        },
        getSelectedFis(state) {
            return state.selected_fis;
        },
        tripartytype(state) {
            return state.istriparty;
        },

        offer_rates(state) {
            return state.offer_rates;
        },
        created_from(state) {
            return state.created_from;
        },
        created_from_id(state) {
            return state.created_from_id;
        },
        selected_offers(state) {
            return state.selected_offers;
        },
        getformatedtimezone(state) {
            return state.formated_timezone;
        },
        getprimerates(state) {
            return state.primerates;
        },
        gettripaties(state) {
            return state.tripaties;
        },
        gettripartybaskets(state) {
            return state.triparty_baskets;
        },
        getholidays(state) {
            return state.holidays;
        },
        getdaycount(state) {
            return state.daycount;
        },


    },

};

export default RepoPostRequestModule;
