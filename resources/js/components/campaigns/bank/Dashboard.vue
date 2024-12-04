<template>
    <div>
        <accordion :is_open="is_open" title="My Campaigns" width="95" title_image="/assets/dashboard/icons/Promo.svg" />

        <div class="d-flex justify-content-between" style="height: auto;">
            <div class="justify-content-center" style="width:200px;min-width: 200px"
                v-if="userCan(this.userloggedin,'bank/my-campaigns/build-new-campaign')">
                <div style="padding-top: 60px;">
                    <Create @click="action = 'create'" />
                </div>
            </div>
            <div v-if="userCan(this.userloggedin,'bank/my-campaigns/featured-products-insights')" style="width: 80%;">
                <div style="width:100%">
                    <div style=" display: flex; flex-direction: column;">
                        <div style="padding: 10px;">
                            <div
                                style="width: 100%; height: 100%; flex-direction: column; justify-content: flex-start;  gap: 6px; display: inline-flex;">
                                <div
                                    style="text-align: left; color: #5063F4; font-size: 16px; font-weight: 700; line-height: 20px; word-wrap: break-word">
                                    Featured Product</div>
                                <div style="width: 59px; height: 3px; background: #5063F4; border-radius: 16px"></div>
                            </div>
                        </div>
                        <div style="margin-top: 10px; width: 98%; margin-left: 20px;">
                            <FeaturedCard />

                            <!-- featured -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="width: 100%; margin-top: 10px;" class="row mt-3">
            <b-tabs content-class="mt-3" nav-wrapper-class="custom-tab-nav-class custom-tab-nav-class-border-bottom">
                <b-tab title="Active Campaigns" active>
                    <ActiveList :products="products" :provinces="provinces" filterType="campaign"
                        :userloggedin="userloggedin" :timezone="timezone" />
                </b-tab>
                <b-tab title="Campaign History">
                    <ActiveList :is_history="true" :products="products" :provinces="provinces" filterType="campaign"
                        :timezone="timezone" />
                </b-tab>
                <b-tab title="Products">
                    <ActiveCampaignProductList :products="products" :provinces="provinces" filterType="products"
                        :userloggedin="userloggedin" :timezone="timezone" />
                </b-tab>
            </b-tabs>
        </div>
    </div>
</template>
<style>
    .top-cards-row {
        width: 100%;
        display: flex;
        justify-content: flex-start;
    }

    /* .top-cards-row div {
    width: auto;
    margin-right: 2px;
} */
</style>
<script>
    import Accordion from "../../shared/Accordion";
    import ActiveList from "../../campaigns/bank/ActiveList";
    import FeaturedCard from "../../campaigns/bank/FeaturedCard";
    import Create from "../../campaigns/bank/Create";
    import ActiveCampaignProductList from "./ActiveCampaignProductList";
    import NoOfClicks from "../../shared/Graphs/line/LineGraph.vue"
    import SubscriptionGoal from "../../shared/Graphs/pie/SemiCircularGraph.vue"
    import { userCan } from "../../../utils/GlobalUtils";

    export default {
        mounted() {
            const urlParams = new URLSearchParams(window.location.search);
            let campaign_id = urlParams.get('campaign_id');
            // if(campaign_id){
            //     this.action = 'create';
            // }
        },
        components: {
            NoOfClicks,
            SubscriptionGoal,
            Accordion,
            ActiveList,
            FeaturedCard,
            Create,
            ActiveCampaignProductList
        },
        created() {
        },
        props: ['products', 'provinces', 'userloggedin', 'timezone'],
        watch: {
            action(newValue, oldValue) {
                this.$emit('update:action', newValue);
            },
        },
        data() {
            return {
                details: null,
                existing: null,
                is_open: false,
                action: 'view',

            }
        },
        methods: {
            userCan(user, permission) {
                return userCan(user, permission);
            },
        }
    }
</script>