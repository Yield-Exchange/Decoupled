// campaignModule.js
import * as types from "./mutation-types.js";
import { addCommasAndDecToANumber } from "../../../utils/commonUtils.js";

const BasketModule = {
    namespaced: true,
    state: {
        baskets: [],
        billateralbaskets: null,
        collateral_issuers: null,
        tripartybaskets: null,
        billateralCollaterals: null,
        triCollaterals: null,
    },
    mutations: {
        [types.ADD_BASKET](state, request) {
            state.baskets.splice(request[0], 1, request[1])
        },
        [types.REMOVE_BASKET](state, request_id) {
            state.baskets.splice(request_id, 1)
        },
        [types.CLEAR_BASKETS](state, newreq) {
            state.baskets = []
            state.baskets.splice(0, 1, newreq)
        },
        [types.ADD_BI_COLLATERALS](state, newreq) {
            state.billateralbaskets = newreq
        },
        [types.ADD_TRI_COLLATERALS](state, newreq) {
            state.tripartybaskets = newreq
        },
        [types.SET_COLLATERAL_ISSUERS](state, newreq) {
            state.collateral_issuers = newreq
        },
        [types.SET_BI_COLLATERAL](state, newreq) {
            state.billateralCollaterals = newreq
        },
        [types.SET_TRI_COLLATERAL](state, newreq) {
            state.triCollaterals = newreq
        },

    },
    actions: {},
    getters: {
        getAllBaskets(state) {
            return state.baskets;
        },
        getTriBaskets(state) {
            return state.tripartybaskets;
        },
        getBiBaskets(state) {
            return state.billateralbaskets;
        },
        getCollateralIssuers(state) {
            return state.collateral_issuers;
        },
        getBilateralCollaterals(state) {
            return state.billateralCollaterals;
        },
        gettripartyCollaterals(state) {
            return state.triCollaterals;
        },
    },
};

export default BasketModule;
