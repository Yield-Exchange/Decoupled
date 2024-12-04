<template>
    <div>
        <b-card no-body class="text-center pt-2 card-wrapper1 pb-0">
            <b-row class="pt-0">
                <b-col cols="1" class="text-left m-2 d-flex align-items-center">
                    <OrganizationAvatar :size="60" :organization="data.organization" class="newCenter" />
                </b-col>
                <b-col class="text-left m-2 d-flex align-items-center">
                    <h2 class="my-auto " style="font-weight: 600; font-size: 17px; color: black; display: inline-block">
                        {{
                                data.organization.name
                        }}</h2>
                </b-col>
                <b-col class="" cols="4">
                    <b-row>
                        <b-col cols="12" class="py-4">

                            <b-table stacked small borderless :items="[{
                                'Product :': data.product_name, 'Term :': parseInt(data.term_length).toFixed(0) + ' ' + data.term_length_type.toLowerCase()
                            }]">
                            </b-table>

                        </b-col>
                    </b-row>
                </b-col>
                <b-col cols="2" class="text-left p-1">
                    <div class="offer-interest-rate-rounded-container secondOpening">
                        <h2 style="font-weight: 600; font-size: 20px; color: black;">
                            {{ data.interest_rate }}%
                        </h2>
                    </div>
                </b-col>
                <b-col cols="2" class="p-0">
                    <b-row>
                        <b-col class="text-left p-1" cols="12">
                            <BuyNowModel :data="data" :buy_url="this.buy_url" />
                            <ShopRateButton :data="data" :buy_url="this.buy_url" />
                        </b-col>
                    </b-row>
                </b-col>
                <b-col cols="1" class="p-0">
                    <b-row>
                        <b-col cols="12" class="text-center p-0 ">
                            <b-img v-b-toggle="'collapse-' + index" src="/assets/icons/down_arrow_icon.png"
                                class="newCenter"
                                :style="'width: 30px; bottom:0; cursor: pointer; margin: 25%  auto;' + (!collapsed ? '-ms-transform: rotate(45deg);transform: rotate(180deg);' : '')" />
                        </b-col>
                    </b-row>
                </b-col>
            </b-row>
            <b-row>
                <b-col cols="12" class="mb-0 mt-2">
                    <b-collapse v-bind="{ 'visible': 0 === index }" :id="'collapse-' + index"
                        style="background: #DFE0E7">
                        <b-row class="p-2">
                            <b-col>
                                <b-table stacked small borderless class="text-nowrap" :items="[{
                                    'Credit Rating:': data.organization.ratings.credit_rating ?
                                        data.organization.ratings.credit_rating : '-',
                                    'Deposit Insurance:': data.organization.ratings.insurance_rating ?
                                        data.organization.ratings.insurance_rating : '-',
                                    'Interest Paid:': data.compound_frequency
                                }]">
                                </b-table>
                            </b-col>
                            <b-col cols="4" class="p-0 ">
                                <b-table stacked small borderless class="text-nowrap text-center" :items="[{
                                    'Compound Frequency:': data.compound_frequency,
                                    'Lockout Period:': data.lockout_period ? data.lockout_period :
                                        '-',
                                    'Interest Earned:': data.interest_earned ? (data.currency + ' ' + addComma(data.interest_earned)) + (' per ' + data.currency + ' ' + (filters.hasOwnProperty('amount') && filters.amount > 0 ? addComma(filters.amount) : '1,000,000')) : '-'
                                }]">
                                </b-table>
                            </b-col>
                            <b-col>
                                <b-table stacked small borderless class="text-nowrap" :items="[{
                                    'Minimum Amount:': data.currency + ' ' + addComma(Math.floor(data.minimum_amount)),
                                    'Maximum Amount:': data.currency + ' ' + addComma(Math.floor(data.maximum_amount)),
                                }]">
                                </b-table>
                            </b-col>
                        </b-row>
                    </b-collapse>
                </b-col>
            </b-row>
        </b-card>
    </div>
</template>
<style>
.card-wrapper1 {
    border-radius: 10px !important;
}

.offer-interest-rate-rounded-container.secondOpening {
    background: #fff;
    height: 100px;
    width: 100px;
    border-radius: 50%;
    border: 5px solid #4975E3;
    text-align: center;
    padding-top: 34px;
}

.collapsed-label1 {
    text-align: right;
    font-weight: 600;
    font-size: 14px;
    color: black;
    width: 48%;
    display: inline-block;
}

.collapsed-label2 {
    text-align: left;
    color: grey;
    font-size: 12px;
    width: 48%;
    display: inline-block;
}

.newCenter {
    display: flex;
    justify-content: center;
    top: 25% !important;
}

.newCenterPro {
    display: flex;
    flex-direction: column;
    justify-content: center;
}
</style>
<script>
import BuyNowModel from "../shared/BuyNowModel";
import Avatar from "vue-avatar";
import OrganizationAvatar from "../../shared/OrganizationAvatar";
import ShopRateButton from "../shared/ShopRateButton";
export default {
    components: { ShopRateButton, OrganizationAvatar, BuyNowModel, Avatar },
    props: ['data', 'index', 'buy_url', 'filters'],
    mounted() {
        this.$root.$on('bv::collapse::state', (collapseId, isJustShown) => {
            if ('collapse-' + this.index === collapseId) {
                this.collapsed = !isJustShown;
            }
        })
    },
    data() {
        return {
            collapsed: 0 === this.index
        }
    },
    computed: {

    },
    methods: {
        addComma(newValue) {
            return newValue ? newValue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") : '';
        },
    }
}
</script>