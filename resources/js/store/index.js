import Vue from "vue";
import Vuex from "vuex";
import { actions } from "./actions/index.js";
import { mutations } from "./mutations/signupMutations.js";
import campaignModule from "./modules/campaigns/campaignModule.js";
import postModule from "./modules/postreq/postModule.js";
import postRequestModule from "./modules/postrequestflow/postRequestModule.js";
import PublishRateOffers from "./modules/publishratesoffer/PublishRateOfferModule.js";
import RepoPostRequestModule from "./modules/repos/RepoPostRequestModule.js";
import BasketModule from "./modules/baskets/BasketsModule.js";
import AuthModule from "./modules/auth/AuthModule.js";

Vue.use(Vuex);

const state = {
    selectedProducts: [1, 2, 3, 4, 5],
    waitingList: false,
    depositorType: null,
    userType: null,
    stageTitle: "What type of account would you like to set up",
    stepsTracker: ["landing"],
    prevStep: null,
    currentStep: "landing",
    progress: 0,
    timezones: null,
    loggedinuser: {
        first_name: "John",
        phone: "+12345567898",
        last_name: "Doe",
        user_id: "",
        email: "info@yieldexhange.ca",
        job_title: null,
    },
    passwords: {
        password: "",
        confirm: "password",
    },
    isConference: false,
    sessionData: null,
    organisationInformation: null,
    waitingListData: null,
    updateUserInfo: null,
    industries: null,
    provinces: null,
    depositorficount: [20, "thousand"],
    organizationTypes: [
        {
            id: "CORPORATION",
            name: "Incorporation (Corporation)",
        },
        {
            id: "SOLE",
            name: "Sole Proprietorship",
        },
        {
            id: "CROWN",
            name: "Crown Organization",
        },
        {
            id: "PARTNERSHIP",
            name: "Partnership",
        },
    ],
    // system currenciesf

    system_currencies:
        [
            'CAD', 'USD','EUR'
        ],
    system_ratings:
        [
            'AAA', 'AA(high)', 'AA'
        ],

    waitingText: "You’ll be the first to know when we go live!",
    // mvp2
    keyIndividuals: [],
    organizationEntities: [],
    documentsUploaded: [],
    isTermsAndConditions: false,
};
// actions;

const getters = {
    getUserType(state) {
        return state.userType;
    },
    systemCurrencies(state) {
        return state.system_currencies;
    },
    systemRating(state) {
        return state.system_ratings;
    },
    getIndustries(state) {
        return state.industries;
    },
    getIsConference(state) {
        return state.isConference;
    },
    getisTermsAndConditions(state) {
        return state.isTermsAndConditions;
    },
    getProvinces(state) {
        return state.provinces;
    },
    getOrganizationTypes(state) {
        return state.organizationTypes;
    },
    getDepositorType(state) {
        return state.depositorType;
    },
    getsStageTitle(state) {
        return state.stageTitle;
    },
    getCurrentStep() {
        return state.currentStep;
    },
    getPrevStep() {
        return state.prevStep;
    },
    getProgress() {
        return state.progress;
    },
    getTimezones() {
        return state.timezones;
    },
    getLoggedInUser() {
        return state.loggedinuser;
    },
    getOrgDetails() {
        return state.organisationInformation;
    },
    getWaitingListData() {
        return state.waitingListData;
    },
    getUpdatedUserInfo() {
        return state.updateUserInfo;
    },
    getWaitingText() {
        return state.waitingText;
    },
    getStepsTracker() {
        return state.stepsTracker;
    },
    getDepositorFICount() {
        return state.depositorficount;
    },
    // mvp2
    getkeyIndividuals() {
        return state.keyIndividuals;
    },
    getOrganizationEntities() {
        return state.organizationEntities;
    },
    getDocumentsUploaded() {
        state.documentsUploaded;
    },
};

export default new Vuex.Store({
    modules: {
        campaign: campaignModule,
        postreq: postModule,
        postrequestflow: postRequestModule,
        repopostrequest: RepoPostRequestModule,
        basket: BasketModule,
        publishrateoffer: PublishRateOffers,
        auth: AuthModule,
    },
    state,
    mutations,
    actions,
    getters,
});
