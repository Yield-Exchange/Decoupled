<template>
    <Modal :show="show" @isVisible="CloseModal" modalsize="xl" v-if="offer">
        <div class="w-100 p-4">
            <!-- header -->
            <div
                style="width: 100%; height: 70px; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex">
                <div
                    style="width: 100%; padding-left: 3px; background: #EFF2FE; justify-content: flex-start; align-items: center; display: inline-flex">
                    <div style="justify-content: space-between; display: inline-flex; width: 99%">
                        <div class="page-title">
                            <div class="title-icon">
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
                            <div class="text-div">Edit Offer </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end header -->
            <div class="w-100">
                <AddRates v-if="offers.length > 0" :request="offers" :day__counts="getdaycount" :holidays="getholidays"
                    defcurrency="CAD" ref="rates_offers" count="1" :created_from="'New'" :isedit="true"
                    :formattedtimezone="getformatedtimezone" :prime_rate="getprimerates">
                </AddRates>
                <div class="d-flex justify-content-end gap-2 mt-2 w-100">
                    <CustomSubmit title="Previous" :outline="true" @action="CloseModal">
                    </CustomSubmit>
                    <CustomSubmit title="Submit" @action="submitOffers()"></CustomSubmit>
                </div>
            </div>




            <ActionMessage style="width: 600px;" @closedSuccessModal="success = false" @btnTwoClicked=""
                @btnOneClicked="success = false" icon="/assets/signup/success_promo.svg"
                title="Offer  Edited Successfully" btnOneText="" btnTwoText="" :showm="success">
                <!-- <div class="ml-5 description-text-withdraw "> The collateral taker has been notified..</div> -->
            </ActionMessage>
            <ActionMessage style="width: 600px;" @closedSuccessModal="fail = false" @btnTwoClicked=""
                @btnOneClicked="fail = false" btnOneText="" btnTwoText="" icon="/assets/signup/danger.svg"
                title="Offer not edited" :showm="fail">
                <!-- <div class="ml-5 description-text-withdraw ">{{ fail_desc }}</div> -->
            </ActionMessage>
        </div>
    </Modal>

</template>

<script>
import ActionMessage from '../../../../shared/messageboxes/ActionMessageBox.vue'
import AddRates from '../shared/AddRates.vue';

import Modal from '../../../../shared/Modal.vue';
import { addCommasAndDecToANumber, addDaysOrMonths, calculateIterestOnDateCountConnvention, formatNumberAbbreviated, formatTimestamp, generateRandomValue, repoProductName } from '../../../../../utils/commonUtils'
import { mapGetters } from 'vuex';
import { userCan } from '../../../../../utils/GlobalUtils';
import CustomSubmit from '../../../../auth/signup/shared/CustomSubmit.vue';
import * as types from '../../../../../store/modules/publishratesoffer/mutation-types'



export default {
    components: { Modal, CustomSubmit, ActionMessage, AddRates },
    props: ['offer', 'show', 'userLoggedIn', 'formattedtimezone'],
    beforeMount() {
        if (this.offer) {
            this.setOffers()
            this.istriparty = this.offer?.product?.filter_key == 'tri'
        }


    },
    mounted() {
    },

    data() {
        return {
            viewMore1: false,
            hasCounters: false,
            requireAction: false,
            organization_data: null,
            offer_data: null,
            offer_id: null,
            request: null,
            collateral: null,
            success: false,
            fail: false,
            success: false,
            fail: false,
            success_title: '',
            fail_title: '',
            success_desc: '',
            fail_desc: '',
            deposit_request: null,
            withdrawpromt: false,
            fromPage: null,
            istriparty: false,
            latest_counter_offer_id: null,
            // offer: null,
            organization_data: null,
            log_table_data: [],
            purchase_history_data: [],
            logcolumns: ['#', 'Counter Offer Date', 'Investment', 'Counter Rate', 'Rate Change', 'Counter'],
            purchase_history_columns: ['#', 'Settlement Date', 'Maturity Date', 'Currency', 'Purchase Price', 'Interest', 'Repurchase Price', 'Basket ID', 'Status'],
            single_offer: {
                'offer_uniqueid': null,
                'ct': null,
                'organization': null,
                'primary_basket_id': null,
                'primary_basket': null,
                'basket_id_no': null,
                'haircut': null,
                'rating': null,
                'copied': null,
                'duplicated': null,
                'offer_id': null,
                // new structure
                "collateralType": 'tri',
                "currency": "CAD",
                "min": null,
                "max": null,
                "product": 1,
                "term_length_type": 'Days',
                "term_length": null,
                "basket": null,
                "rate_valid_until": null,
                "convention_id": 1,
                "rate_type": "fixed",
                "rate_type_value": 0,
                "entered_rate": null,
                "spreadvalue": null,
                "interest_rate": null,
                "operator": "+",
                "collateral_id": null,
                "primaryBasket": null,
                "termLengthHasAnError": false
            },
            offers: []

        }
    },
    computed: {
        ...mapGetters('publishrateoffer', ['getOffers', 'getholidays', 'getdaycount', 'gettripartybaskets', 'gettripaties', 'getprimerates', 'getformatedtimezone']),

        awarded_amount() {
            return this.addCommas(this.offer_data.amount)
        },
    },
    methods: {
        CloseModal() {
            this.$store.commit('publishrateoffer/' + types.SET_OFFERS, null);
            this.$emit('closeModal', false)
        },
        userCan(a, b) {
            return userCan(a, b)
        },
        calulateInterest(value, date) {
            return calculateIterestOnDateCountConnvention(
                value,
                this.offer?.offer_interest_rate,
                // this.daycount?.slug,
                this.offer?.interest_calculation_option?.slug,
                date,                // this.offer?.settlement_date,
                this.offer?.offer_term_length,
                this.offer?.offer_term_length_type,

            );
        },
        // addDaysOrMonths(date, daystoadd = 1) {
        //     return addDaysOrMonths(date, daystoadd)
        // },
        renderStatus(value) {
            return ({ 'component': InviteCard, 'attrs': { text: value } });
        },
        setOffers() {
            let found_item = this.offer
            // console.log(found_item)
            const randValue = generateRandomValue();
            const singleOffer = {
                ...this.single_offer, offer_uniqueid: randValue, ct: found_item?.c_g_trade_request_invited_c_t?.ct.id, organization: found_item.c_g_trade_request_invited_c_t?.ct, primaryBasket: found_item.basket.basket_details.trade_basket_type_id, rate_type: found_item?.rate_type,
                term_length_type: this.capitalize(found_item?.offer_term_length_type), min: found_item?.offer_minimum_amount, max: found_item?.offer_maximum_amount,
                term_length: found_item?.offer_term_length, interest_rate: found_item.offer_interest_rate.toFixed(2), convention_id: found_item?.interest_calculation_options_id,
                basket: found_item?.basket ? found_item.basket.id : null, spreadvalue: found_item?.rate_type != 'fixed' ? found_item.fixed_rate.toFixed(2) : null, entered_rate: found_item?.fixed_rate.toFixed(2), primary_basket: found_item?.basket?.basket_details?.trade_basket_type, rating: found_item?.basket?.basket_details?.rating, basket_id_no: found_item?.basket?.basket_id,
                offer_id: found_item.encoded_id,rate_valid_until: found_item?.rate_valid_until.split(' ')[0]
            };
            this.$store.commit('publishrateoffer/' + types.SET_OFFERS, [singleOffer]);
            this.offers.push(singleOffer)
            // console.log(singleOffer,"single offer")

        },
        setPageDefaults() {
            // console.log(this.getAllOffersInReview)
        },
        repoProductName(x, y, z) {
            return repoProductName(x, y, z)
        },
        addCommasAndDecToANumber(value) {
            return addCommasAndDecToANumber(value)
        },
        formatNumberAbbreviated(value) {
            return formatNumberAbbreviated(value)
        },
        formatTimestamp(val1, val2) {
            return formatTimestamp(val1, val2)

        },
        capitalize(thestring) {
            if (thestring != undefined) {
                return thestring
                    .toLowerCase()
                    .split(' ')
                    .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
                    .join(' ');
            }
        },

        submitOffers() {

            const isValidRate = (rate) => rate > 0 && rate <= 100;
            const isValidMinMax = (min, max) => min > 0 && min <= 9999999999999 && min <= max && max > 0 && max <= 9999999999999;

            let hasError = false;

            let offer = this.getOffers[0]
            console.log("Offer", offer)
            // Check rate type and entered rate
            if (offer.rate_type === 'fixed') {
                if (!(offer.entered_rate && isValidRate(offer.entered_rate))) {
                    hasError = true;
                    console.log("fixed errro")
                }
            } else {
                var operator = offer.operator


                if (offer.spreadvalue > 0 && offer.spreadvalue <= 100) {
                    let spreadvalue = Number.parseFloat(offer.spreadvalue)
                    let rate_type_value = Number.parseFloat(offer.rate_type_value)
                    let interest_rate = 0
                    if (operator === "+") {
                        interest_rate = rate_type_value + Number.parseFloat(spreadvalue);
                    } else {

                        interest_rate = rate_type_value - Number.parseFloat(spreadvalue);
                    }
                    if (!isValidRate(interest_rate)) {
                        hasError = true
                    }

                } else {

                    hasError = true;
                }

                // if (!(offer.spreadvalue && isValidRate(offer.interest_rate))) {
                //     console.log(spreadvalue, "Spread Value")
                //     console.log("spread errro")

                // }
            }

            // Check min and max values
            if (offer.min && offer.max) {
                if (!isValidMinMax(offer.min, offer.max)) {
                    hasError = true;
                    console.log("minmax errro")

                }
            }
            if (offer.termLengthHasAnError) {
                hasError = true;
            }

            // Check rate validity and term length
            if (!(offer.rate_valid_until && offer.term_length)) {
                hasError = true;
            }

            if (hasError) {

                const childComponent = this.$refs.rates_offers;
                if (childComponent) {
                    childComponent.ableToSubmit(); // Call ableToSubmit if it's a function
                }

                // this.$refs.rates_offers.ableToSubmit()
            } else {
                this.doSubmit()
                console.log("do submit")
                // this.promtSubmit = true
                // 

            }
        },
        async doSubmit() {
            // let formdata = new FormData()
            let offerstosubmit = { ...this.getOffers[0] };
            offerstosubmit['offerID'] = offerstosubmit['offer_id']
            delete offerstosubmit.organization
            delete offerstosubmit.primary_basket
            await axios.post('/trade/CG/update-request-offer', offerstosubmit).then(response => {

                this.success = true
                setTimeout(() => {
                    this.success = false
                    window.location.reload()
                }, 3000)

            }).catch(err => {
                this.success = false
                this.fail = true
                // console.log(err.response, "erro")
            })
        }




    },
    watch: {
        getAllOffersInReview() {
            console.log("changed")
        }
    }
}

</script>
<style>
.t-clock p {
    font-size: 16px !important;
    font-family: Montserrat;
    font-weight: 500;
    word-wrap: break-word
}
</style>
<style scoped>
.pr-deposit-summary-investment p {
    width: 100%;
    color: #252525;
    font-size: 16px;
    font-family: Montserrat;
    font-weight: 500;
    word-wrap: break-word
}

.description-text-withdraw {
    margin-top: -20px;
    font-size: 16px;
    font-family: Montserrat !important;
}
</style>