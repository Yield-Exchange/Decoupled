<template>
    <div>

        <div class="mt-4">
            <Table :columns="columns" @selectedItems="selectedItems($event)" @selectAllR="allselected"
                no-data-title="No Available Requests" no-data-message="Create new  request to proceed with this flow"
                :data="table_data" :has_action='true' :select_all="select_all"
                @productDeletedAddNew="productDeletedAddNew" :selectable="selectable" :selected_items="selected_items"
                :is_loading="is_loading" :allselectable="(allselectablee) ? true : false" />
            <Pagination @click-next-page="getPageData" v-if="data && data.links" :data="data" />
        </div>
        <div class="d-flex justify-content-end gap-2">
            <CustomSubmit title="Previous" :outline="true" @action="prevStep">
            </CustomSubmit>
            <CustomSubmit title="Next" @action="nextStep"></CustomSubmit>
        </div>
        <ActionMessage style="width: 600px;" @closedSuccessModal="slectearequest = false" @btnTwoClicked=""
            @btnOneClicked="slectearequest = false" btnOneText="" btnTwoText="" icon="/assets/signup/danger.svg"
            title="Please Select one Request" :showm="slectearequest">
            <!-- <div class="ml-5 description-text-withdraw ">Your changes will be cleared from the request</div> -->
        </ActionMessage>
    </div>

</template>


<script>
import ActionMessage from '../../../shared/messageboxes/ActionMessageBox.vue'
import CustomSubmit from '../../../auth/signup/shared/CustomSubmit.vue'
import Pagination from '../../../shared/Table/Pagination.vue'
import Table from '../../../shared/Table'
import ShowCG from '../../shared/ShowCG.vue'
import { capitalize } from '../../../../utils/commonUtils'
import * as types from '../../../../store/modules/publishratesoffer/mutation-types'
import { mapGetters } from 'vuex'
import axios from 'axios'


export default {
    // props: ['selected_items'],
    components: { Table, ShowCG, Pagination, CustomSubmit, ActionMessage },
    mounted() {
        this.getData()
        if (this.created_from_id != null){
            this.selected_items.push(this.created_from_id)
        }
    },
    data() {
        return {
            table_data: null,
            // columns: ['Collateral Giver', 'Province', 'Credit Rating', 'Deposit Insurance'],
            columns: ['Request ID', 'Status', 'Action'],
            allselectablee: false,
            selectable: true,
            slectearequest: false,
            select_all: false,
            is_loading: true,
            allcgs: null,
            filterString: null,
            data: null,
            selected_items: [],
            single_offer: {
                'offer_uniqueid': null,
                'ct': null,
                'organization': null,
                'primary_basket_id': null,
                'primary_basket': null,
                'basket_id_no': null,
                'haircut': null,
                'rating': null,
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
            }

        }
    },
    computed: {
        ...mapGetters('publishrateoffer', ['getSelectedFis', 'getFIS', 'created_from_id'])
    },

    methods: {
        async nextStep() {
            if (this.selected_items.length > 0 && this.selected_items.length == 1) {
                let selectecteditem = this.selected_items[0]
                let found_request = this.data.data.find(item => item.id = selectecteditem)
                this.$store.commit('publishrateoffer/' + types.SET_CREATED_FROM_ID, found_request.id);

                let offers = []
                let selected_cgs = []
                //    found_request.c_g_trade_request_invited_c_t

                await axios.get('/trade/market-place/CG/get-my-request-offers?req=' + found_request.encoded_id).then(response => {
                    let found_data = response.data
                    // console.log(found_data.length, "Length")
                    found_data.forEach(item => {
                        // this.getSelectedFis.forEach(element => {
                        const foundOrg = item?.c_g_trade_request_invited_c_t?.ct;
                        const randValue = this.generateRandomValue();
                        const singleOffer = {
                            ...this.single_offer, offer_uniqueid: randValue, ct: foundOrg?.id, organization: foundOrg, primaryBasket: item.basket.basket_details.trade_basket_type_id, rate_type: item?.rate_type,
                            term_length_type: capitalize(item?.offer_term_length_type), min: item?.offer_minimum_amount, max: item?.offer_maximum_amount,
                            term_length: item?.offer_term_length, interest_rate: item.offer_interest_rate.toFixed(2), convention_id: item?.interest_calculation_options_id,
                            basket: item?.basket ? item.basket.id : null, spreadvalue: item?.rate_type != 'fixed' ? item.fixed_rate.toFixed(2) : null, entered_rate: item?.fixed_rate.toFixed(2), primary_basket: item?.basket?.basket_details?.trade_basket_type, rating: item?.basket?.basket_details?.rating, basket_id_no: item?.basket?.basket_id,
                        };
                        // offers.push(existingOffersMap.get(element));
                        selected_cgs.push(foundOrg?.id)
                        offers.push(singleOffer);
                        // });

                    })


                }).catch(err => {
                    // console.log(err.response, "Error ")
                })


                // console.log(offers.length, "offers length")

                this.$store.commit('publishrateoffer/' + types.SET_SELECTED_FIS, selected_cgs);
                this.$store.commit('publishrateoffer/' + types.SET_OFFERS, offers);


                // this.getSelectedFis.forEach(element => {
                //     const foundOrg = this.getFIS.find(item => item.id == element);
                //     const randValue = this.generateRandomValue();
                //     const singleOffer = { ...this.single_offer, offer_uniqueid: randValue, ct: element, organization: foundOrg };
                //     // offers.push(existingOffersMap.get(element));
                //     offers.push(singleOffer);
                // });

                this.$emit('nextStep')
            } else
                this.slectearequest = true
        },
        prevStep() {
            this.$emit('prevStep')
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

        getPageData(url) {
            if (this.filterString != '') {
                this.formatLabel(this.filterString);
                url = `${url}&${this.filterString}&type=pending&`
            } else {
                url = `${url}&type=pending&`
            }
            this.getData(url);
        },
        capitalize(value) {
            return capitalize(value)
        },
        allselected(value) {
            // console.log(value)
        },
        async getData() {
            let table_data = []
            await axios.get('/trade/market-place/CG/get-my-requests').then(response => {
                this.data = response.data
                response.data.data.forEach(element => {
                    let request = [
                        element.id,
                        element.reference_no,
                        element.request_status,
                        // () => {
                        //     return ({ 'component': ShowCG, 'attrs': { organization: element, orgname: element.name } });
                        // },

                    ]
                    table_data.push(request)
                });
                this.table_data = table_data
                this.is_loading = false
                // this.allcgs = FIs
            }).catch(err => {

            });

            // this.$store.commit('publishrateoffer/' + types.SET_FIS, this.allcgs);
            // this.selectedItems(this.selected_items)

        },
        productDeletedAddNew() {

        },
        selectedItems(value) {
            console.log(value, "Selected value")
            if (value) {
                if (Object.values(value).length > 0) {
                    console.log("Has Values")
                    this.selected_items = value
                } else {
                    console.log("Has no Values")
                    this.selected_items = []
                }
            }
        },

    }

}
</script>