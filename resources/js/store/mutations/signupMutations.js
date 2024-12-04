export const mutations = {
    SET_USERTYPE(state, payload) {
        state.userType = payload
    },
    SET_DEPOSITORTYPE(state, payload) {
        state.depositorType = payload
    },
    SET_STAGETITLE(state, payload) {
        state.stageTitle = payload
    },
    SET_CURRENTSTEP(state, payload) {
        state.currentStep = payload
    },
    SET_PREVSTEP(state, payload) {
        state.prevStep = payload
    },
    GO_BACK(state, payload) {
        state.currentStep = state.prevStep
    },
    SET_PROGRESS(state, payload) {
        state.progress = payload
    },
    SET_TIMEZONE(state, payload) {
        state.timezones = payload
    },
    SET_USER(state, payload) {
        state.loggedinuser = payload
    },
    SET_ORGDETAILS(state, payload) {
        state.organisationInformation = payload
    },
    SET_WAITINGLISTDATA(state, payload) {
        state.waitingListData = payload
    },
    SET_UPDATEUSERINFO(state, payload) {
        state.updateUserInfo = payload
    },
    SET_WAITINGTEXT(state, payload) {
        state.waitingText = payload
    },
    SET_PROVINCES(state, payload) {
        state.provinces = payload
    },
    SET_INDUSTRIES(state, payload) {
        state.industries = payload
    },
    SET_ORGTYPES(state, payload) {
        state.organizationTypes = payload
    },
    SET_STEPSTRACKER(state, payload) {
        if (!state.stepsTracker.includes(payload))
            state.stepsTracker.push(payload)
    },
    SET_DEPOSITORFICOUNT(state, payload) {
        state.depositorficount = payload
    },
    SET_KEYINDIVIDUALS(state, payload) {
        state.keyIndividuals = payload
    },
    SET_ORGANIZATIONENTITIES(state, payload) {
        state.organizationEntities = payload
    },
    SET_DOCUMENTSUPLOADED(state, payload) {
        state.documentsUploaded = payload
    },
    SET_ISCONFERENCE(state, payload) {
        state.isConference = payload
    },
    SET_TERMSANDCONDITIONS(state, payload) {
        state.isTermsAndConditions = payload
    },
};