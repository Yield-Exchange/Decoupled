// campaignModule.js
import * as types from "./mutation-types.js";
import { addCommasAndDecToANumber } from "../../../utils/commonUtils.js";

const RepoPostRequestModule = {
    namespaced: true,
    state: {
        postrequests: [],
        prefferedColarteral: null,
        selected_fis: null,
        // request summary
        request_summary: null,
        postrequestsoffers: [],
        // prime rates
        prime_rates: null,
        // review offers
        review_request_offers: null,
        offer_errors: {},

        // settlement date
        settlement_date: null,
        global_products: null,
        // history offers
        history_offers: null,
    },
    mutations: {
        [types.UPDATE_SELECTED_OFFER_ENTRY](state, entry) {
            const offer_id = entry.offer_id;
            const existingProductIndex = state.review_request_offers.findIndex(
                (o) => o.offer_id === offer_id
            );
            switch (entry.field) {
                case "amount_validation_error":
                    console.log(entry, "field is name");
                    state.review_request_offers[
                        existingProductIndex
                    ].amount_validation_error = entry.value;
                    break;
                default:
                    console.log("Unknown field:", entry.field);
            }
        },
        [types.ADD_NEW_REQUEST](state, request) {
            state.postrequests.splice(request[0], 1, request[1]);
        },
        [types.REMOVE_A_REQUEST](state, request_id) {
            state.postrequests.splice(request_id, 1);
        },
        [types.CLEAR_REQUEST](state, newreq) {
            state.postrequests = [];
            state.postrequests.splice(0, 1, newreq);
        },
        [types.ADD_PREFFERED_COLLATERAL](state, newreq) {
            state.prefferedColarteral = newreq;
        },
        [types.SET_SELECTED_FIS](state, newreq) {
            state.selected_fis = newreq;
        },
        // set request summay in review offers
        [types.SET_REQUEST_SUMMARY](state, newreq) {
            state.request_summary = newreq;
        },
        // repo Offers on new requests
        [types.ADD_NEW_REQUEST_OFFER](state, request) {
            state.postrequestsoffers.splice(request[0], 1, request[1]);
        },
        [types.REMOVE_A_REQUEST_OFFER](state, request_id) {
            state.postrequestsoffers.splice(request_id, 1);
        },
        [types.CLEAR_REQUEST_OFFERS](state, newreq) {
            state.postrequestsoffers = [];
            state.postrequestsoffers.splice(0, 1, newreq);
        },
        // set prime rates
        [types.SET_PRIME_RATES](state, newreq) {
            state.prime_rates = newreq;
        },
        // review Offers
        [types.SET_REVIEW_REQUEST_OFFERS](state, payload) {
            state.review_request_offers = payload;
        },
        // set settlement date
        [types.SET_SETTLEMENT_DATE](state, payload) {
            state.settlement_date = payload;
        },
        [types.SET_GLOBAL_PRODUCTS](state, payload) {
            state.global_products = payload;
        },
        // History Offers
        [types.SET_HISTORY_OFFERS](state, payload) {
            state.history_offers = payload;
        },
        [types.SET_OFFER_ERRORS](state, payload) {
            delete state.offer_errors[payload[0]];
            state.offer_errors[payload[0]] = payload[1];
        },
    },
    actions: {},
    getters: {
        getAllRequests(state) {
            return state.postrequests;
        },
        getAllPrefferedCollaterals(state) {
            return state.prefferedColarteral;
        },
        getAllSelectedFIS(state) {
            return state.selected_fis;
        },
        getRequestSummary(state) {
            return state.request_summary;
        },
        getAllRequestsOffers(state) {
            return state.postrequestsoffers;
        },
        getPrimeRates(state) {
            return state.prime_rates;
        },
        getRateWithKey(state, value) {
            let foundElement = state.prime_rates.find(
                (element) => element.key === value
            );
            if (foundElement) {
                let frate = [foundElement.rate_label, foundElement.value];
                return frate;
            } else {
                return null;
            }
        },
        // review offers
        getAllOffersInReview(state, payload) {
            return state.review_request_offers;
        },
        // review offers
        getsettlemntdate(state, payload) {
            return state.settlement_date;
        },
        getgloabalproducts(state, payload) {
            return state.global_products;
        },
        // history Ofefrs
        getHistoryOffers(state, payload) {
            return state.history_offers;
        },
        getOfferErrors(state) {
            // console.log("get request")
            return state.offer_errors;
        },
    },
};

export default RepoPostRequestModule;
