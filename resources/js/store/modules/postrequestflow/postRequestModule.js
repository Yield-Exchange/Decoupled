// campaignModule.js
import * as types from "./mutation-types.js";
import { addCommasAndDecToANumber } from "../../../utils/commonUtils.js";

const postRequestModule = {
    namespaced: true,
    state: {
        postrequests: [],

    },
    mutations: {
        [types.ADD_NEW_REQUEST](state, request) {
            state.postrequests.splice(request[0], 1, request[1])
        },
        [types.REMOVE_A_REQUEST](state, request_id) {
            state.postrequests.splice(request_id, 1)
        },
        [types.CLEAR_REQUEST](state, newreq) {
            state.postrequests = []
            state.postrequests.splice(0, 1, newreq)
        },
    },
    actions: {},
    getters: {
        getAllRequests(state) {
            return state.postrequests;
        },
    },
};

export default postRequestModule;
