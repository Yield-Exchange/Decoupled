import * as types from "./mutation-types.js";

const AuthModule = {
    namespaced: true,
    state: {
        loggedInUser: null,
    },
    mutations: {
        [types.ADD_LOGGEDINUSER](state, request) {
            state.loggedInUser = request;
        },
        [types.REMOVE_LOGGEDINUSER](state, request) {
            state.loggedInUser = null;
        },
    },
    actions: {},
    getters: {
        getLoggedInUser(state) {
            return state.loggedInUser;
        },
    },
};
export default AuthModule;
