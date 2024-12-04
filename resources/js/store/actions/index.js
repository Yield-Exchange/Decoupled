export const actions = {
    setUserType(context, payload) {
        context.commit('SET_USERTYPE', payload)
    },
    setDepositorType(context, payload) {
        context.commit('SET_DEPOSITORTYPE', payload)
    },
    setStageTitle(context, payload) {
        context.commit('SET_STAGETITLE', payload)
    },
    setCurrentStep(context, payload) {
        context.commit('SET_CURRENTSTEP', payload)
    },
    setPrevStep(context, payload) {
        context.commit('SET_PREVSTEP', payload)
    },
    goBack(context) {
        context.commit("GO_BACK")
    },
    setProgress(context, payload) {
        context.commit("SET_PROGRESS", payload)
    },
    setTimezones(context, payload) {
        context.commit("SET_TIMEZONE", payload)
    },
    setLoggedInUser(context, payload) {
        context.commit("SET_USER", payload)
    },
    setOrgDetails(context, payload) {
        context.commit("SET_ORGDETAILS", payload)
    },
    setWaitingListData(context, payload) {
        context.commit("SET_WAITINGLISTDATA", payload)
    },
    updateUserInfo(context, payload) {
        context.commit("SET_UPDATEUSERINFO", payload)
    },
    setWaitingText(context, payload) {
        context.commit("SET_WAITINGTEXT", payload)
    },
    setIndustries(context, payload) {
        context.commit("SET_INDUSTRIES", payload)
    },
    setProvinces(context, payload) {
        context.commit("SET_PROVINCES", payload)
    },
    setOrganizationTypes(context, payload) {
        context.commit("SET_ORGTYPES", payload)
    },
    setStepsTracker(context, payload) {
        context.commit("SET_STEPSTRACKER", payload)
    },
    setDepositorFICount(context, payload) {
        context.commit("SET_DEPOSITORFICOUNT", payload)
    },

    // mvp 2

    setkeyIndividuals(context, payload) {
        context.commit("SET_KEYINDIVIDUALS", payload)
    },
    setOrganizationEntities(context, payload) {
        context.commit("SET_ORGANIZATIONENTITIES", payload)
    },
    setDocumentsUploaded(context, payload) {
        context.commit("SET_DOCUMENTSUPLOADED", payload)
    },
    setDocumentsUploaded(context, payload) {
        context.commit("SET_DOCUMENTSUPLOADED", payload)
    },
    setIsConference(context, payload) {
        context.commit("SET_ISCONFERENCE", payload)
    },
    setisTermsAndConditions(context, payload) {
        context.commit("SET_TERMSANDCONDITIONS", payload)
    },

};