// campaignModule.js
import * as types from "./mutation-types.js";
import { addCommasAndDecToANumber } from "../../../utils/commonUtils";

const postModule = {
    namespaced: true,
    state: {
        allSelectedRequestOffers: [],
        pickedSelectedRequestOffers: [],
        allOffersRates: [],
        currentOfferCounters: [],
    },
    mutations: {
        [types.SET_CURRENT_OFFER_COUNTERS](state, counters) {
            state.currentOfferCounters = counters;
        },
        [types.SET_ALL_SELECTED_REQUEST_OFFERS](state, offers) {
            state.allSelectedRequestOffers = offers;
        },
        [types.SET_PICKED_SELECTED_REQUEST_OFFERS](state, offers) {
            state.pickedSelectedRequestOffers = offers;
        },
        [types.UPDATE_KEY_FOR_OFFERS](state, entry) {
            const offer_id = entry.offer_id;
            const existingOfferIndex = state.allSelectedRequestOffers.findIndex(
                (p) => p.offer_id === offer_id
            );
            switch (entry.field) {
                case "awarded":
                    state.allSelectedRequestOffers[existingOfferIndex].awarded =
                        entry.value;
                    break;
                case "awarded_error":
                    state.allSelectedRequestOffers[
                        existingOfferIndex
                    ].awarded_error = entry.value;
                    break;
                default:
                    console.log("Unknown field:", entry.field);
            }
        },
        [types.SET_ALL_SELECTED_REQUEST_OFFERS_RATES](state, rates) {
            state.allOffersRates = rates;
        },
        [types.UPDATE_KEY_FOR_EARNED_RATES](state, entry) {
            console.log("setting allOffersRates", state.allOffersRates);
            console.log("setting rates up", entry);
            const offer_id = entry.offer_id;
            const existingOfferIndex = state.allOffersRates.findIndex(
                (p) => p.offer_id === offer_id
            );
            switch (entry.field) {
                case "row_rate":
                    state.allOffersRates[existingOfferIndex].row_rate =
                        entry.value;
                    break;

                default:
                    console.log("Unknown field:", entry.field);
            }
        },
    },
    actions: {},
    getters: {
        getCurrentOfferCounters(state) {
            return state.currentOfferCounters;
        },
        getAllSelectedRequestOffers(state) {
            return state.allSelectedRequestOffers;
        },
        getAllSelectedRequestOffersRates(state) {
            return state.allOffersRates;
        },
        getPickedSelectedRequestOffers(state) {
            return state.pickedSelectedRequestOffers;
        },
        getSelectedRequestOfferRate: (state) => (offer) => {
            let index = state.allSelectedRequestOffers.findIndex(
                (p) => p.offer_id === offer
            );
            return addCommasAndDecToANumber(
                state.allOffersRates[index].row_rate
            );
        },
    },
};

export default postModule;
