<template>
    <div class="w-100">
        <div class="mt-4">

            <table class="table" style="width: 100%; ">
                <thead class="customHeader">
                    <tr>
                        <th v-for="(column, index) in columns" :key="index">{{ column }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(value, index) in offers" :key="value.offer_uniqueid">

                        <td>
                            <div class="textContainer">
                                <div class="d-flex justify-content-start align-items-center gap-2">
                                    <ShowCG has_icon="true" :organization="value.organization" orgname=""></ShowCG>
                                    <span class="copied-baskets"
                                        v-if="offerWhere(value.offer_uniqueid).copied">Copied</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="textContainer">
                                <template v-if="istriparty == 'tri'">
                                    <NewCustomSelect :options="triparties_primary_baskets" idkey="id"
                                        valuekey="basket_name" placeholder="Select primary basket type"
                                        :haserror="primaryBasketError[value.offer_uniqueid]"
                                        :defaultValue="offerWhere(value.offer_uniqueid).primaryBasket"
                                        @change="changePrimaryBasket($event, value.offer_uniqueid)" />

                                </template>
                                <template v-else-if="istriparty == 'bi'">
                                    <NewCustomSelect :options="bilateral_primary_baskets" idkey="id"
                                        valuekey="collateral_name" placeholder="Select primary basket type"
                                        :defaultValue="primary_basket"
                                        @change="changePrimaryBasket($event, value.offer_uniqueid)" />
                                </template>
                                <span v-if="primaryBasketError[value.offer_uniqueid]" class="error-message">
                                    {{ primaryBasketError[value.offer_uniqueid] }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="textContainer" v-if="istriparty == 'tri'">
                                <NewCustomSelect v-if="offers[index].primaryBasket"
                                    :haserror="basketIDError[value.offer_uniqueid]"
                                    :options="filteredBasket(triparties, offerWhere(value.offer_uniqueid).primaryBasket, offerWhere(value.offer_uniqueid).ct)"
                                    idkey="id" valuekey="name" placeholder="Select primary basket type"
                                    :defaultValue="offerWhere(value.offer_uniqueid).basket"
                                    @change="selectBasket($event, value.offer_uniqueid)" :add_later="true" />

                                <NewCustomSelect v-else
                                    :options="filteredBasket(triparties, offerWhere(value.offer_uniqueid).primaryBasket, 4)"
                                    idkey="id" valuekey="name" placeholder="Select primary basket type"
                                    :defaultValue="offerWhere(value.offer_uniqueid).basket" :disabled="true"
                                    @change="selectBasket($event, value.offer_uniqueid)" :add_later="false" />

                                <span v-if="basketIDError[value.offer_uniqueid]" class="error-message">
                                    {{ basketIDError[value.offer_uniqueid] }}
                                </span>
                            </div>
                            <div class="textContainer" v-else>
                                <NewCustomSelect v-if="offers[index].primaryBasket"
                                    :haserror="basketIDError[value.offer_uniqueid]"
                                    :options="filteredBasket(bilaterals, offerWhere(value.offer_uniqueid).primaryBasket, offerWhere(value.offer_uniqueid).ct)"
                                    idkey="id" valuekey="name" placeholder="Select primary basket type"
                                    :defaultValue="offerWhere(value.offer_uniqueid).basket"
                                    @change="selectCollateral($event, value.offer_uniqueid)" :add_later="true" />

                                <NewCustomSelect v-else
                                    :options="filteredBasket(bilaterals, offerWhere(value.offer_uniqueid).primaryBasket, 4)"
                                    idkey="id" valuekey="name" placeholder="Select primary basket type"
                                    :defaultValue="offerWhere(value.offer_uniqueid).basket" :disabled="true"
                                    @change="selectCollateral($event, value.offer_uniqueid)" :add_later="false" />

                                <span v-if="basketIDError[value.offer_uniqueid]" class="error-message">
                                    {{ basketIDError[value.offer_uniqueid] }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="textContainer">
                                <CustomInput inputType="text"
                                    c-style="font-weight: 400;width:100%; margin-top:0px;height:90%;"
                                    p-style="width:100%" class="" name="Basket Rating" :has-validation="false"
                                    :disabled="true" input-type="text"
                                    :defaultValue="offerWhere(value.offer_uniqueid).rating" :hasSpecificError="false" />
                            </div>
                        </td>
                        <td>
                            <div class="textContainer">

                                <CustomInput inputType="number" :disabled="true"
                                    c-style="font-weight: 400;width:100%; margin-top:0px;height:90%;"
                                    p-style="width:100%" class="" name="Hair Cut" :has-validation="true"
                                    @inputChanged="hairCutValueChange($event, value)" input-type="number"
                                    defaultValue="AAA" :hasSpecificError="false" />
                            </div>

                        </td>
                        <td>
                            <div class="textContainer">
                                <div class="d-flex justify-content-center align-items-center gap-2 pointer">
                                    <div @click="addOffer(value.offer_uniqueid, index)"
                                        class="d-flex justify-content-center align-items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="18"
                                            viewBox="0 0 17 18" fill="none">
                                            <path
                                                d="M0 2.79167C0 2.18388 0.241443 1.60098 0.671214 1.17121C1.10098 0.741443 1.68388 0.5 2.29167 0.5H12.7083C13.3161 0.5 13.899 0.741443 14.3288 1.17121C14.7586 1.60098 15 2.18388 15 2.79167V8.01833C14.488 7.69095 13.9246 7.45206 13.3333 7.31167V2.79167C13.3333 2.62591 13.2675 2.46694 13.1503 2.34972C13.0331 2.23251 12.8741 2.16667 12.7083 2.16667H2.29167C2.12591 2.16667 1.96694 2.23251 1.84972 2.34972C1.73251 2.46694 1.66667 2.62591 1.66667 2.79167V13.2083C1.66667 13.5533 1.94667 13.8333 2.29167 13.8333H6.81167C6.95333 14.4333 7.195 14.9958 7.51833 15.5H2.29167C1.68388 15.5 1.10098 15.2586 0.671214 14.8288C0.241443 14.399 0 13.8161 0 13.2083V2.79167ZM16.6667 12.5833C16.6667 11.3678 16.1838 10.202 15.3242 9.34243C14.4647 8.48288 13.2989 8 12.0833 8C10.8678 8 9.70197 8.48288 8.84243 9.34243C7.98288 10.202 7.5 11.3678 7.5 12.5833C7.5 13.7989 7.98288 14.9647 8.84243 15.8242C9.70197 16.6838 10.8678 17.1667 12.0833 17.1667C13.2989 17.1667 14.4647 16.6838 15.3242 15.8242C16.1838 14.9647 16.6667 13.7989 16.6667 12.5833ZM12.5 13L12.5008 15.0858C12.5008 15.1963 12.4569 15.3023 12.3788 15.3805C12.3007 15.4586 12.1947 15.5025 12.0842 15.5025C11.9737 15.5025 11.8677 15.4586 11.7895 15.3805C11.7114 15.3023 11.6675 15.1963 11.6675 15.0858V13H9.58C9.46949 13 9.36351 12.9561 9.28537 12.878C9.20723 12.7998 9.16333 12.6938 9.16333 12.5833C9.16333 12.4728 9.20723 12.3668 9.28537 12.2887C9.36351 12.2106 9.46949 12.1667 9.58 12.1667H11.6667V10.0833C11.6667 9.97283 11.7106 9.86685 11.7887 9.7887C11.8668 9.71056 11.9728 9.66667 12.0833 9.66667C12.1938 9.66667 12.2998 9.71056 12.378 9.7887C12.4561 9.86685 12.5 9.97283 12.5 10.0833V12.1667H14.5808C14.6913 12.1667 14.7973 12.2106 14.8755 12.2887C14.9536 12.3668 14.9975 12.4728 14.9975 12.5833C14.9975 12.6938 14.9536 12.7998 14.8755 12.878C14.7973 12.9561 14.6913 13 14.5808 13H12.5Z"
                                                fill="#5063F4" />
                                        </svg>
                                        <p class="p-0 m-0">Copy</p>
                                    </div>
                                    <div @click="deleteOffer(index)"
                                        class="d-flex justify-content-center align-items-center gap-2 pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="20"
                                            viewBox="0 0 21 20" fill="none">
                                            <path
                                                d="M6.33398 17.5C5.87565 17.5 5.48343 17.3369 5.15732 17.0108C4.83121 16.6847 4.66787 16.2922 4.66732 15.8333V5H3.83398V3.33333H8.00065V2.5H13.0007V3.33333H17.1673V5H16.334V15.8333C16.334 16.2917 16.1709 16.6842 15.8448 17.0108C15.5187 17.3375 15.1262 17.5006 14.6673 17.5H6.33398ZM14.6673 5H6.33398V15.8333H14.6673V5ZM8.00065 14.1667H9.66732V6.66667H8.00065V14.1667ZM11.334 14.1667H13.0007V6.66667H11.334V14.1667Z"
                                                fill="#FF2E2E" />
                                        </svg>
                                        <!-- <p class="p-0 m-0">Copy</p> -->
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>

            </table>
            <!-- <Table :columns="columns" @selectedItems="$emit('selectedItems', $event)" @selectAllR="allselected"
                no-data-title="No Active Products" no-data-message="Start by adding a new product to populate data"
                :data="table_data" :has_action='false' @productDeletedAddNew="$emit('productDeletedAddNew')"
                :selectable="selectable" :selected_items="selected_items"
                :allselectable="(allselectablee) ? true : false" /> -->
            <!-- <Pagination @click-next-page="getData" v-if="data && data.links" :data="data" /> -->
        </div>

        <div class="d-flex justify-content-end gap-2 mt-2">
            <CustomSubmit title="Previous" :outline="true" @action="$emit('prevStep', 'true')">
            </CustomSubmit>
            <CustomSubmit title="Next" @action="goNext()"></CustomSubmit>
        </div>
        <ActionMessage style="width: 600px;" @closedSuccessModal="deleterequest = false" @btnTwoClicked="doleteOffer"
            @btnOneClicked="deleterequest = false" btnOneText="No" btnTwoText="Yes"
            icon="/assets/dashboard/icons/question-new.svg" title="Delete Request" :showm="deleterequest">
            <div class="ml-5 description-text-withdraw ">Are you sure you want to delete this request</div>
        </ActionMessage>


    </div>

</template>


<script>
    import ActionMessage from '../../../shared/messageboxes/ActionMessageBox.vue';
    import Pagination from '../../../shared/Table/Pagination.vue'
    import Table from '../../../shared/Table'
    import CustomInput from '../../../shared/CustomInput.vue';
    import NewCustomSelect from '../../../shared/NewCustomSelect.vue';
    import { mapGetters } from 'vuex';
    import * as types from '../../../../store/modules/publishratesoffer/mutation-types'
    import ShowCG from '../../shared/ShowCG.vue';
    import CustomSubmit from '../../../auth/signup/shared/CustomSubmit.vue';
    // import Tooltip


    export default {
        props: ['fi_data', 'selected_items', 'created_from', 'bilaterals', 'requests', 'triparties_primary_baskets', 'triparties', 'bilateral_primary_baskets'],
        components: { Table, CustomInput, NewCustomSelect, ShowCG, CustomSubmit, ActionMessage },
        mounted() {

            if (this.getSelectedFis) {
                let offers = []
                if (this.created_from == 'Copied') {
                    if (this.getOffers) {
                        offers = this.getOffers
                    }

                } else {

                    if (this.getOffers) {

                        this.getSelectedFis.forEach(element => {

                            // check if there is an existing offer

                            const foundItems = this.getOffers.filter(offerItem => offerItem.ct === element);
                            console.log(element, foundItems)
                            if (foundItems.length > 0) {
                                foundItems.forEach(element => {
                                    offers.push(element)
                                })
                            } else {
                                const foundOrg = this.getFIS.find(item => item.id == element);
                                const randValue = this.generateRandomValue();
                                const singleOffer = { ...this.single_offer, offer_uniqueid: randValue, ct: element, organization: foundOrg };
                                // offers.push(existingOffersMap.get(element));
                                offers.push(singleOffer);
                            }
                        });


                    } else {
                        console.log("else allocate baskets")

                        this.getSelectedFis.forEach(element => {
                            const foundOrg = this.getFIS.find(item => item.id == element);
                            const randValue = this.generateRandomValue();
                            const singleOffer = { ...this.single_offer, offer_uniqueid: randValue, ct: element, organization: foundOrg };
                            // offers.push(existingOffersMap.get(element));
                            offers.push(singleOffer);
                        });


                    }
                }

                this.offers = offers
                // console.log(offers.length, "Allocate baskets offers length")
            }

        },
        beforeMount() {
            if (this.tripartytype)
                this.istriparty = this.tripartytype
        },
        data() {
            return {
                table_data: null,
                reqtodelete: null,
                columns: ['Institution', 'Primary Basket', 'Basket ID', 'Basket Rating', 'Haircut (%)', 'Actions'],
                allselectablee: true,
                deleterequest: false,
                selectable: true,
                primaryBasketError: {},
                basketIDError: {},
                istriparty: null,
                primary_basket: null,
                offers: null,
                single_offer: {
                    'offer_uniqueid': null,
                    'ct': null,
                    'organization': null,
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
                    "convention_id": 2,
                    "rate_type": "fixed",
                    "rate_type_value": 0,
                    "entered_rate": null,
                    "spreadvalue": null,
                    "interest_rate": null,
                    "operator": "+",
                    "collateral_id": null,
                    "primaryBasket": null,
                    "termLengthHasAnError": false
                }
            }
        },
        computed: {
            ...mapGetters('publishrateoffer', ['getSelectedFis', 'getFIS', 'getOffers', 'tripartytype', 'selected_offers']),
            offerWhere() {
                return (value) => {
                    if (!Array.isArray(this.offers)) {
                        console.error('Offers is not an array:', this.offers);
                        return null; // or handle as needed
                    }

                    const found_item = this.offers.find(item => item.offer_uniqueid === value);
                    if (!found_item) {
                        console.warn(`No item found with unique ID: ${value}`);
                    }
                    return found_item;
                };
            },
        },
        methods: {
            goNext() {
                let haserror = false
                this.getOffers.forEach(element => {
                    let indexchecker = element.offer_uniqueid
                    if (this.istriparty == 'tri') {
                        if (element.basket == null) {
                            this.$set(this.basketIDError, indexchecker, "This field is required")
                            haserror = true
                        } else {
                            this.$set(this.basketIDError, indexchecker, null)
                        }
                        if (element.primaryBasket == null) {
                            this.$set(this.primaryBasketError, indexchecker, "This field is required")
                            haserror = true
                        } else {
                            this.$set(this.primaryBasketError, indexchecker, null)
                        }
                    }

                })
                if (!haserror) {
                    this.$emit('nextStep')
                }

            },
            addOffer(currentOffer, index) {
                let current_offer = this.offerWhere(currentOffer)

                var rand_value = this.generateRandomValue();
                var single_offer = { ...current_offer }; // Create a new object based on this.single_offer

                single_offer['offer_uniqueid'] = rand_value;
                single_offer['copied'] = true;

                this.offers.splice(index + 1, 0, single_offer);
                // this.offers.push(single_offer);

            },
            doleteOffer() {
                let offertodelete = { ...this.offers[this.reqtodelete] };
                let ct = offertodelete.ct
                this.offers.splice(this.reqtodelete, 1)

                let selectd_fis = [...this.getSelectedFis]

                if (!this.offers.some(item => item.ct == ct)) {
                    selectd_fis = selectd_fis.filter(item => item != ct);
                    this.$store.commit('publishrateoffer/' + types.SET_SELECTED_FIS, selectd_fis);
                }

                if (this.created_from == 'Copied') {
                    let ref_id = offertodelete.offer_id
                    console.log(this.offers.some(item => item.offer_id == ref_id), "Check Some", ref_id, "S O ", this.selected_offers)
                    if (!this.offers.some(item => item.offer_id == ref_id)) {
                        let selectedoffers = [...this.selected_offers]
                        selectedoffers = selectedoffers.filter(item => item != ref_id);

                        this.$store.commit('publishrateoffer/' + types.SET_SELECTED_OFFERS, selectedoffers);
                    }
                }

                if (this.offers.length == 0) {
                    this.$emit('prevStep', 'true')
                }

                this.reqtodelete = null
                this.deleterequest = false

            },
            deleteOffer(index) {
                this.reqtodelete = index
                this.deleterequest = true
            },
            hairCutValueChange(value, elemnetId) {

            },
            // offerWhere(value) {
            //     let found_item = this.offers.find(item => item.offer_uniqueid == value)
            //     // console.log(found_item, "found item ", value)
            //     return found_item
            // },
            changePrimaryBasket(value, elementId) {
                const offer = this.offers.find(element => element.offer_uniqueid === elementId);

                if (offer) {
                    offer.primaryBasket = value;
                    offer.basket = null;
                    offer.basket_id_no = null;
                    offer.rating = null;
                    if (this.istriparty == 'tri')
                        offer.primary_basket = this.triparties_primary_baskets.find(item => item.id === value);
                    else
                        offer.primary_basket = this.bilateral_primary_baskets.find(item => item.id === value);
                }
                this.$set(this.primaryBasketError, elementId, null)

                // console.log(this.offers)
            },
            selectBasket(value, elemnetId) {
                let found_item = null
                if (value != 0) {
                    if (this.istriparty == 'tri') {
                        found_item = this.triparties.find(item => item.id == value)
                    }
                }
                this.offers.forEach(element => {
                    if (element.offer_uniqueid == elemnetId) {
                        if (!found_item) {
                            element.rating = 'NA'
                            element.basket_id_no = 'NA'
                        } else {
                            element.basket_id_no = found_item.basket_id
                            element.rating = found_item.rating
                        }
                        element.basket = value
                        this.$set(this.basketIDError, elemnetId, null)

                        // console.log("Value found", value)
                    }
                });
                // console.log(this.offers)
                // console.log(this.triparties)
            },
            selectCollateral(value, elemnetId) {
                let found_item = null
                if (value != 0) {

                    found_item = this.bilaterals.find(item => item.id == value)


                }
                this.offers.forEach(element => {
                    if (element.offer_uniqueid == elemnetId) {
                        if (!found_item) {
                            element.rating = 'NA'
                            element.basket_id_no = 'NA'
                        } else {
                            element.basket_id_no = found_item.cucip
                            element.rating = found_item.rating
                        }
                        element.basket = value
                        this.$set(this.basketIDError, elemnetId, null)

                        // console.log("Value found", value)
                    }
                });
                // console.log(this.offers)
                // console.log(this.triparties)
            },
            filteredBasket(baskets, basket, org_id) {

                if (baskets && basket) {
                    baskets = baskets.filter(item => (item.primary_id == basket && org_id == item.org_id))
                    return baskets
                } else {
                    return []
                }
                // console.log(baskets, "baskets")
            },
            generateRandomValue() {
                var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                var length = 12;
                var randomValue = '';
                for (var i = 0; i < length; i++) {
                    var randomIndex = Math.floor(Math.random() * characters.length);
                    randomValue += characters.charAt(randomIndex);
                }
                return randomValue;
            },

            validateValues() {
                this.haserror = false
                this.fi_data.forEach(element => {
                    let indexchecker = element.id
                    if (!this.min_amount[indexchecker]) {
                        this.haserror = true
                        this.$set(this.minAmountError, indexchecker, "This field is required")
                    } else {
                        this.minAmountChange(this.min_amount[indexchecker], indexchecker)
                    }
                    if (!this.max_amount[indexchecker]) {
                        this.haserror = true
                        this.$set(this.maxAmountError, indexchecker, "This field is required")
                    } else {
                        this.maxAmountChange(this.max_amount[indexchecker], indexchecker)

                    }
                    if (!this.interest_rate[indexchecker]) {
                        this.haserror = true
                        this.$set(this.interestRateError, indexchecker, "This field is required")
                    } else {
                        this.InterestRateChange(this.interest_rate[indexchecker], indexchecker)
                    }

                });

                if (!this.haserror) {
                    const minnull = Object.values(this.minAmountError).every(minerror => minerror === null);
                    const maxnull = Object.values(this.maxAmountError).every(maxerror => maxerror === null);
                    const ratenull = Object.values(this.interestRateError).every(rateerror => rateerror === null);
                    if (minnull && maxnull && ratenull) {
                        this.rates_and_deposits = this.fi_data.map(element => ({
                            organization_id: element.id,
                            rate: this.interest_rate[element.id],
                            min_amount: this.removeCommas(this.min_amount[element.id]),
                            max_amount: this.removeCommas(this.max_amount[element.id])
                        }));
                        this.$emit('dosubmit', this.rates_and_deposits)
                        // return true;
                    } else {
                        // console.log("Not Ready for submit.");
                        this.rates_and_deposits = []
                        // return false;
                    }

                }

            },
            removeCommas(newValue) {
                return newValue ? parseFloat(newValue.toString().replace(/,/g, '')) : 0;
            },

        },
        watch: {
            offers() {
                this.$store.commit('publishrateoffer/' + types.SET_OFFERS, this.offers);
            }
        }
    }
</script>
<style>
    .demofirates input {
        border-radius: 0px !important;
        outline: none !important;
        border: none !important;
        border-bottom: 1px solid #ccc !important;
        font-family: Montserrat !important;
    }
</style>
<style scoped>
    .pointer {
        cursor: pointer;
    }

    .copied-baskets {
        display: flex;
        padding: 5px;
        justify-content: center;
        align-items: center;
        gap: 10px;
        background: var(--Yield-Exchange-Colors-Yield-Exchange-Green, #44E0AA);
        color: var(--Yield-Exchange-Pallette-Yield-Exchange-White, #FFF);
        font-family: Montserrat;
        font-size: 12px;
        font-style: normal;
        font-weight: 500;
        line-height: normal;
    }

    .textContainer {
        width: 100%;
        height: 100%;
        color: black;
        font-size: 15px;
        font-family: Montserrat;
        font-weight: 400;
        /* padding-top: 20px; */
        word-wrap: break-word
    }

    thead.customHeader {
        background: #eff2fe !important;
        height: 51px;
    }

    thead.customHeader tr th span .custom-checkbox ::before {
        border-radius: 4px !important;
        border: 0.50px #5063F4 solid !important;
        padding-left: 2px;
    }

    thead.customHeader tr th span .custom-checkbox .custom-control-label {
        border: 0.50px #5063F4 solid !important;
        margin-top: 0 !important;
    }

    thead .custom-control-label {
        margin-top: 0 !important;
    }

    thead.customHeader tr {
        border-bottom: 0 solid #b3b2b2 !important;
    }

    thead.customHeader tr th {
        color: black;
        font-size: 16px !important;
        font-weight: 700;
        background: inherit !important;
        max-width: 300px;
        /* min-width: 150px; */
        padding-right: 0.75rem;
        padding-left: 0.55rem;
    }

    tbody tr td {
        padding-top: 5px !important;
        font-size: 13px !important;
        vertical-align: middle !important;
    }

    @media screen and (max-width:1200px) {
        thead.customHeader tr th {
            font-size: .75em;
        }
    }

    .table tbody tr td {
        padding: none !important;
    }

    .error-message {
        color: red;
    }
</style>