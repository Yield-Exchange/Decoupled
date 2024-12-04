<template>
    <div>
        <div v-for="(grouped_offer, index ) in groupedOffers" :key="index">
            <AddRates :request="grouped_offer" :day__counts="day__counts" :holidays="holidays" defcurrency="CAD"
                ref="rates_offers" count="1" @goBack="goBack" @regroupData="regroupData" :created_from="created_from"
                :formattedtimezone="formattedtimezone" :prime_rate="prime_rate">
            </AddRates>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-2">
            <CustomSubmit title="Previous" :outline="true" @action="$emit('prevStep', 'true')">
            </CustomSubmit>
            <CustomSubmit title="Submit" @action="submitOffers()"></CustomSubmit>
        </div>
        <ActionMessage style="width: 600px;" @closedSuccessModal="successsubmit = false" @btnTwoClicked=""
            @btnOneClicked="successsubmit = false" btnOneText="" btnTwoText="" icon="/assets/signup/success_promo.svg"
            title="Request Submitted Successfully" :showm="successsubmit">
            <!-- <div class="ml-5 description-text-withdraw ">Are you sure you wat to delete this request</div> -->
        </ActionMessage>
        <ActionMessage style="width: 600px;" @closedSuccessModal="errorsubmit = false"
            @btnTwoClicked="errorsubmit = false" @btnOneClicked="errorsubmit = false" btnOneText="" btnTwoText="Close"
            icon="/assets/signup/danger.svg" title="Request has not been saved!!" :showm="errorsubmit">
            <div class="ml-5 description-text-withdraw ">Something's not right,please try again or contact
                info@yieldechange.ca</div>
        </ActionMessage>
        <ActionMessage style="width: 600px;" @closedSuccessModal="promtSubmit = false" @btnTwoClicked="doSubmit"
            @btnOneClicked="promtSubmit = false" btnOneText="No" btnTwoText="Submit"
            icon="/assets/dashboard/icons/question-new.svg" title="Sure to submit Offers?" :showm="promtSubmit">
            <div class="ml-5 description-text-withdraw ">Please ensure that your fields are well filled.</div>
        </ActionMessage>
    </div>
</template>

<script>
    import AddRates from './shared/AddRates.vue';
    import ActionMessage from '../../../shared/messageboxes/ActionMessageBox.vue';

    import { mapGetters } from 'vuex';
    import CustomSubmit from '../../../auth/signup/shared/CustomSubmit.vue';

    export default {
        props: ['prime_rate', 'formattedtimezone', 'day__counts', 'holidays', 'created_from'],

        components: {
            AddRates, CustomSubmit, ActionMessage
        },
        beforeMount() {
            this.regroupData()
        },
        data() {
            return {
                groupedOffers: null,
                successsubmit: false,
                errorsubmit: false,
                promtSubmit: false,
            }
        },
        computed: {
            ...mapGetters('publishrateoffer', ['getOffers', 'offer_rates', 'tripartytype', 'created_from_id'])
        },
        methods: {
            regroupData() {
                const groupedData = this.getOffers.reduce((acc, item) => {
                    // If the category doesn't exist in the accumulator, create it
                    if (!acc[item.primaryBasket]) {
                        acc[item.primaryBasket] = [];
                    }
                    // Push the current item into the appropriate category array
                    acc[item.primaryBasket].push(item);
                    return acc;
                }, {});

                this.groupedOffers = groupedData
                console.log(groupedData, "Grouped Data");
            },

            goBack() {
                this.$emit('goBack', true)
            },

            submitOffers() {

                const isValidRate = (rate) => rate > 0 && rate <= 100;
                const isValidMinMax = (min, max) => min > 0 && min <= 9999999999999 && min <= max && max > 0 && max <= 9999999999999;

                let hasError = false;

                this.getOffers.forEach(offer => {
                    // Check rate type and entered rate
                    if (offer.rate_type === 'fixed') {
                        if (!(offer.entered_rate && isValidRate(offer.entered_rate))) {
                            hasError = true;
                            console.log("fixed errro")
                        }
                    } else {
                        var operator = offer.operator
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
                });

                if (hasError) {
                    Object.values(this.groupedOffers).forEach((item, index) => {
                        console.log(index, "index", item)
                        const childComponent = this.$refs.rates_offers[index];
                        if (childComponent) {
                            childComponent.ableToSubmit(); // Call ableToSubmit if it's a function
                        }
                    })
                    // this.$refs.rates_offers.ableToSubmit()
                } else {
                    console.log("do submit")
                    this.promtSubmit = true


                }
            },
            async doSubmit() {
                let formdata = new FormData()
                let offerstosubmit = this.getOffers
                offerstosubmit.forEach(item => {
                    delete item.organization
                })
                formdata.append('collateralType', this.tripartytype)
                formdata.append('source', this.created_from)
                if (this.created_from == 'Copied')
                    formdata.append('copied_from', this.created_from_id)
                formdata.append('tradeOffers', JSON.stringify(offerstosubmit))
                await axios.post('/trade/CG/create-request', formdata).then(response => {
                    console.log(response)
                    this.successsubmit = true
                    setTimeout(() => {
                        this.successsubmit = false
                        window.location.reload()
                    }, 3000)

                }).catch(err => {
                    this.successsubmit = false
                    this.errorsubmit = true
                    // console.log(err.response, "erro")
                })
            }

        },
        watch: {
            offer_rates() {
                console.log(this.offer_rates)
            }
        }
    }
</script>

<style lang="scss" scoped></style>