<template>
    <div class="w-100">
        <div
            style="width: 100%;  padding-top: 10px; padding-bottom: 10px; background: #EFF2FE; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 30px; display: inline-flex">
            <div style="justify-content: center; align-items: flex-start; display: inline-flex">
                <div style="display: flex; flex-direction: column;">
                    <div
                        style="width: 100%; align-self: stretch; justify-content: flex-start; align-items: center; gap: 10px; display: flex">
                        <div style="width: 40px; height: 40px; position: relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40"
                                fill="none">
                                <path
                                    d="M5.58813 14.9174C6.67396 10.2883 10.2883 6.67395 14.9174 5.58813C18.2604 4.80396 21.7396 4.80396 25.0826 5.58813C29.7117 6.67395 33.3261 10.2884 34.4119 14.9174C35.196 18.2604 35.196 21.7396 34.4119 25.0826C33.3261 29.7117 29.7117 33.3261 25.0826 34.4119C21.7396 35.1961 18.2604 35.1961 14.9174 34.4119C10.2884 33.3261 6.67396 29.7117 5.58813 25.0826C4.80396 21.7396 4.80396 18.2604 5.58813 14.9174Z"
                                    fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35" />
                                <path
                                    d="M25.699 13H14.411C13.6349 13 13.0071 13.6349 13.0071 14.411L13 27.11L15.822 24.288H25.699C26.475 24.288 27.11 23.6531 27.11 22.877V14.411C27.11 13.6349 26.475 13 25.699 13ZM25.699 22.877H15.2364L14.8202 23.2932L14.411 23.7024V14.411H25.699V22.877ZM18.9967 21.466H24.288V20.055H20.4078L18.9967 21.466ZM21.72 17.3247C21.8611 17.1836 21.8611 16.9649 21.72 16.8238L20.4712 15.5751C20.3301 15.434 20.1114 15.434 19.9703 15.5751L15.822 19.7234V21.466H17.5646L21.72 17.3247Z"
                                    fill="#5063F4" />
                            </svg>
                        </div>
                        <div
                            style="color: #252525; font-size: 30px;  font-weight: 800; line-height: 32px; word-wrap: break-word">
                            Publish Rate Offers
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="d-flex flex-column justify-content-center align-items-center w-100 mt-3">

            <div class="w-100" v-if="step == 'allrateoffers'">
                <OffersListing :userLoggedIn="userLoggedIn" :timezone="formattedtimezone"
                    @nextStep="step = 'choosestartpoint'"></OffersListing>
            </div>
            <div v-if="step == 'choosestartpoint'" class="w-50">
                <StageHead class="my-3" title="How would you like to create your request"
                    icon="assets/dashboard/icons/orgsettings.svg">
                </StageHead>

                <ButtonCard width='100%' @click="setOptionType('new', 'chooseinstitutions')"
                    :selected="option_type == 'new'" image="assets/dashboard/icons/newrequestfromscratch.svg"
                    title=" Start a new request" desc="Share with someone"></ButtonCard>
                <ButtonCard width='100%' @click="setOptionType('Copied', 'chooseexistingreq')"
                    :selected="option_type == 'Copied'" image="assets/dashboard/icons/copyexisting.svg"
                    title=" Copy from my recent activity" desc="Share with someone"></ButtonCard>
                <!-- <ButtonCard width='100%' @click="option_type = 'ai'" :selected="option_type == 'ai'"
                    image="assets/dashboard/icons/useai.svg" title=" Use AI to generate my Request"
                    desc="Share with someone"></ButtonCard> -->

                <div class="d-flex justify-content-end gap-2">
                    <CustomSubmit title="Previous" :outline="true" @action="step = 'allrateoffers'"></CustomSubmit>
                    <!-- <CustomSubmit title="Next" @action="nextStep('startfrom')"></CustomSubmit> -->
                </div>
            </div>
            <!-- Choose invetment type -->
            <div v-if="step == 'chooseinvestmenttype'" class="w-50">
                <StageHead class="my-3 w-50" title="Select Product Type to Invest In Today"
                    icon="assets/dashboard/icons/orgsettings.svg">
                </StageHead>
                <!-- <ButtonCard width='100%' @click="istriparty = 'bi'" :selected="istriparty == 'bi'"
                    image="assets/dashboard/icons/bilateral-step.svg" title=" Bilateral"
                    desc="Private invitations for a more selective approach"></ButtonCard> -->
                <ButtonCard width='100%' @click="istriparty = 'tri'" :selected="istriparty == 'tri'"
                    image="assets/dashboard/icons/triparty-step.svg" title=" Triparty"
                    desc="Available to everyone in the marketplace for maximum exposure">
                </ButtonCard>
                <div class="d-flex justify-content-end gap-2">
                    <CustomSubmit title="Previous" :outline="true" @action="step = 'choosestartpoint'"></CustomSubmit>
                    <CustomSubmit title="Next" @action="nextStep('investtype')"></CustomSubmit>
                </div>
            </div>
            <!-- select a request -->
            <div class="w-100" v-if="step == 'chooseexistingreq'">
                <StageHead class="my-3 w-50" title="Select existing request to edit"
                    icon="assets/dashboard/icons/orgsettings.svg">
                </StageHead>
                <OffersListingSelectable :userLoggedIn="userLoggedIn" :timezone="formattedtimezone"
                    @prevStep="step = 'choosestartpoint'" @nextStep="step = 'setbaskets'">
                </OffersListingSelectable>

                <!-- <ExistingRequests @prevStep="step = 'choosestartpoint'" @nextStep="step = 'setbaskets'">
                </ExistingRequests> -->
            </div>
            <div class="w-100 d-flex justify-content-center" v-if="step == 'chooseinstitutions'">
                <!-- <div class="w-100" > -->
                <div class="w-75">

                    <StageHead class="my-3" title="Select Institutions to Invite"
                        icon="assets/dashboard/icons/orgsettings.svg">
                    </StageHead>

                    <InstitutionsToInvite :selected_items="selected_items"></InstitutionsToInvite>
                    <div class="d-flex justify-content-end gap-2">
                        <CustomSubmit title="Previous" :outline="true" @action="step = 'choosestartpoint'">
                        </CustomSubmit>
                        <CustomSubmit title="Next" @action="nextStep('choosefi')"></CustomSubmit>
                    </div>
                </div>
            </div>
            <div v-if="step == 'setbaskets'" class="w-100">
                <StageHead class="my-3" title="Allocate Baskets to your Selected Institutions"
                    icon="assets/dashboard/icons/orgsettings.svg">
                </StageHead>
                <AllocateBaskets :istriparty="istriparty" @nextStep="nextStep('allocaterates')"
                    @prevStep="prevStep('allcate_baskets')" :bilaterals="bilaterals"
                    :bilateral_primary_baskets="bilateral_primary_baskets" :created_from="option_type"
                    :triparties_primary_baskets="triparties_primary_baskets" :triparties="triparties"
                    :selected_items="selected_items"></AllocateBaskets>
            </div>
            <div v-if="step == 'allocaterates'" class="w-100">
                <StageHead class="my-3" title="Complete the Rate offers" icon="assets/dashboard/icons/rate-offers.svg">
                </StageHead>
                <AllocateRates @goBack="resetStep" :day__counts="daycount" :holidays="holidays" defcurrency="CAD"
                    count="1" :formattedtimezone="formattedtimezone" :prime_rate="formatedprimerate"
                    :created_from="option_type" @prevStep="step = 'setbaskets'">
                </AllocateRates>
                <!-- <div class="d-flex justify-content-end gap-2">
                    <CustomSubmit title="Previous" :outline="true" @action="step = 'setbaskets'">
                    </CustomSubmit>
                    <CustomSubmit title="Submit" @action="submitOffers"></CustomSubmit>
                </div> -->
            </div>
        </div>
        <!-- Action Message -->
        <ActionMessage style="width: 600px;" @closedSuccessModal="selectfierror = false" @btnTwoClicked=""
            @btnOneClicked="selectfierror = false" btnOneText="" btnTwoText="" icon="/assets/signup/danger.svg"
            title="Please Select  at least one Institution" :showm="selectfierror">
            <!-- <div class="ml-5 description-text-withdraw ">Your changes will be cleared from the request</div> -->
        </ActionMessage>
    </div>
</template>

<script>
import ButtonCard from '../../shared/ButtonCard.vue';
import InstitutionsToInvite from './InstitutionsToInvite.vue'
import AllocateBaskets from './AllocateBaskets.vue'
import CustomSubmit from '../../../auth/signup/shared/CustomSubmit.vue';
import ActionMessage from '../../../shared/messageboxes/ActionMessageBox.vue';
import axios from 'axios';
import { mapGetters } from 'vuex';
import * as types from '../../../../store/modules/publishratesoffer/mutation-types'
import AllocateRates from './AllocateRates.vue';
import OffersListing from './OffersListing.vue';
import StageHead from './shared/StageHead.vue';
import ExistingRequests from './ExistingRequests.vue';
import OffersListingSelectable from './OffersListingSelectable.vue';



export default {
    props: ['userLoggedIn', 'formattedtimezone', 'prime_rate'],
    components: {
        ButtonCard,
        InstitutionsToInvite,
        AllocateBaskets,
        CustomSubmit,
        AllocateRates,
        ActionMessage,
        OffersListing,
        StageHead,
        ExistingRequests,
        OffersListingSelectable
    },
    beforeMount() {
        if (this.formattedtimezone)
            this.$store.commit('publishrateoffer/' + types.SET_TIME_ZONE, this.formattedtimezone);

        // this.getTimezone()
        this.getAllDayCounts()
        this.getAllHolidays()
        this.getInvitedFis()
        if (this.prime_rate) {
            this.formatedprimerate = JSON.parse(this.prime_rate)
            this.$store.commit('publishrateoffer/' + types.SET_PRIME_RATES, this.formatedprimerate);
        }
    },
    mounted() {
        this.getTripartyBasketTypes()
        this.getBilateralBasketTypes()
        this.getTriParties()
        this.getCollaterals()


    },
    data() {
        return {
            step: 'allrateoffers',
            selected_items: null,
            bilateral_primary_baskets: null,
            triparties_primary_baskets: null,
            triparties: null,
            bilaterals: null,
            option_type: null,
            istriparty: null,
            holidays: null,
            daycount: null,
            formatedprimerate: null,
            selectfierror: false,


        }
    },
    computed: {
        ...mapGetters('publishrateoffer', ['getSelectedFis', 'getOffers'])
    },
    methods: {
        resetStep() {
            this.prevStep('allcate_baskets')
        },
        setOptionType(optionType, step) {
            this.option_type = optionType
            this.step = step
            this.istriparty = 'tri'
        },
        prevStep(value) {
            if (value == 'allcate_baskets') {
                if (this.option_type == 'new')
                    this.step = 'chooseinstitutions'
                else if (this.option_type == 'Copied')
                    this.step = 'chooseexistingreq'
            }
        },
        nextStep(value) {
            if (value == 'investtype') {
                if (this.istriparty) {
                    this.step = 'chooseinstitutions'
                }

            } else if (value == 'startfrom') {
                if (this.option_type) {
                    if (this.option_type == 'new') {
                        this.$store.commit('publishrateoffer/' + types.SET_CREATED_FROM, 'New');
                        this.step = 'chooseinvestmenttype'
                    }
                    if (this.option_type == 'Copied') {
                        this.$store.commit('publishrateoffer/' + types.SET_CREATED_FROM, 'Copied');
                        this.step = 'chooseexistingreq'

                    }
                }

            } else if (value == 'choosefi') {
                if (this.getSelectedFis && this.getSelectedFis.length > 0) {
                    this.step = 'setbaskets'
                } else {
                    this.selectfierror = true
                }

            } else if (value == 'allocaterates') {
                if (this.getSelectedFis && this.getSelectedFis.length > 0) {
                    this.step = 'allocaterates'
                }
            }
        },
        async getInvitedFis() {
            let invited = []
            let allcgs = null
            await axios.get('/common/trade/get-collateral-takers').then(response => {
                const FIs = response.data
                // console.log("Fis ", response.data.data)
                allcgs = FIs
            });

            this.$store.commit('publishrateoffer/' + types.SET_FIS, allcgs);


            // this.setTableDefaults()
            // this.selectedItems(this.selected_items)

        },
        getAllDayCounts() {
            axios.get('/common/trade/get-all-interest-calculation-options?status=ACTIVE').then(res => {
                //    this.holidays=res?.data?.holidays
                if (res.data.length > 0)
                    this.daycount = res.data
                this.$store.commit('publishrateoffer/' + types.SET_DAY_COUNT, this.daycount);

                // console.log(res.data, "Holdays")
                // this.formattedtimezone = JSON.stringify(res.data)
            })
        },
        getTripartyBasketTypes() {
            axios.get('/common/trade/get-basket-types?disabled=0').then(response => {
                this.triparties_primary_baskets = response.data
            })
            this.$store.commit('publishrateoffer/' + types.SET_TRIPARTIES, this.triparties_primary_baskets);
        },
        getBilateralBasketTypes() {
            axios.get('/common/trade/get-colletarals-list?disabled=0').then(response => {
                this.bilateral_primary_baskets = response.data
                // console.log(baskets)

            })
        },
        getAllHolidays() {
            axios.get('https://canada-holidays.ca/api/v1/holidays').then(res => {
                this.holidays = res?.data?.holidays
                // console.log(res.data.holidays, "Holdays")
                // this.formattedtimezone = JSON.stringify(res.data)
                this.$store.commit('publishrateoffer/' + types.SET_HOLIDAYS, this.holidays);

            })
        },
        getCollaterals() {
            axios.get('/trade/CG/get-colleterals?is_dummy=0').then(response => {
                let collateral = []
                if (response.data.length > 0) {
                    Object.values(response?.data).forEach((item, count) => {
                        // console.log(item, 'item')
                        Object.values(item.trade_organization_c_u_s_s_i_p).forEach((cusip) => {
                            if (cusip?.cusips_status == 'ACTIVE') {
                                collateral.push(
                                    {
                                        'id': cusip?.id,
                                        'primary_id': cusip?.collateral_details?.id,
                                        'cucip': cusip?.CUSIP_code,
                                        'collateral_name': cusip?.collateral_details?.collateral_name,
                                        'rating': item?.rating,
                                        'currency': item?.currency,
                                        'name': `${item?.currency}-${item?.rating}-${cusip?.CUSIP_code}`,
                                    }
                                )
                            }
                        });
                    });
                    // console.log(collateral, 'Data 2')
                    this.bilaterals = collateral
                }

            })
        },
        getTriParties() {
            axios.get('/trade/CG/get-baskets?is_dummy=0').then(response => {
                let triparties = []
                if (response.data.length > 0) {
                    Object.values(response?.data).forEach((item, count) => {
                        if (item?.is_disabled == 0) {
                            item.trade_tri_basket_third_party.forEach(basket => {
                                if (basket.basket_status == 'ACTIVE')
                                    triparties.push(
                                        {
                                            'id': basket.id,
                                            'org_id': basket.organization_id,
                                            'primary_id': item.trade_basket_type.id,
                                            'basket_id': basket.basket_id,
                                            'basket_name': item.trade_basket_type.basket_name,
                                            'rating': item.rating,
                                            'currency': item.currency,
                                            'name': `${item.currency}-${item.rating}-${basket.basket_id}`,
                                        }
                                    )
                            })

                        }
                    });
                    this.triparties = triparties
                    this.$store.commit('publishrateoffer/' + types.SET_TRIPARTIES_BASKET, this.triparties);

                    // console.log(triparties, "Tris")
                }

            })
        },
    },
    watch: {
        istriparty() {
            this.$store.commit('publishrateoffer/' + types.SET_TRIPARTY_TYPE, this.istriparty);
            // this.$store.commit('publishrateoffer',)

        }
    }
}

</script>

<style lang="" scoped>

</style>