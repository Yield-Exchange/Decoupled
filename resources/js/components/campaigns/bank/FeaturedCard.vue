<template>
    <div style="width: 100%;">
        <div v-if="!is_loading && !featuredProduct">
            <div
                style="width: 98%; height: 100%; padding: 15px; background: white !important; border-radius: 10px;box-shadow: 0px 2px 6px rgba(13, 10, 44, 0.08);">
                <NoFeaturedProduct />
            </div>
        </div>
        <div v-if="!is_loading && featuredProduct" style="width: 100%; min-width: 900px ">
            <div class="row" style="gap: 20px">
                <div class="featured-cusom-radius   bg-white" style="width: calc(35% - 20px);">
                    <div
                        style=" border-radius: 10px; flex-direction: column; justify-content: flex-start; align-items: center; gap: 20px; display: inline-flex">
                        <div
                            style="flex-direction: column; justify-content: flex-start; align-items: center; gap: 3.60px; display: flex">
                            <div style="width: 90%;">
                                <div
                                    style="justify-content: flex-start; align-items: flex-start; gap: 4px; display: inline-flex">
                                    <img src="/assets/dashboard/icons/Promo2.svg" />
                                    <div
                                        style="color: #2A2A2A; font-size: 16px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                                        {{
                                        featuredProduct ? capitalize(featuredProduct?.custom_product_name) :
                                        'No Featured Product' }}</div>


                                </div>
                                <div style="width: 100%; height: 0px; border: 1px #D9D9D9 solid"></div>
                            </div>
                            <div v-if="featuredProduct"
                                style="width: 246px; justify-content: flex-start; align-items: center; gap: 3.60px; display: inline-flex">
                                <div
                                    style="width: 246px; height: 28px; color: #5063F4; font-size: 14px; font-family: Montserrat; font-weight: 600; text-transform: capitalize; word-wrap: break-word">
                                    {{ capitalize(featuredProduct?.campaign_name) }}</div>
                            </div>
                            <div
                                style="width: 219px; justify-content: flex-start; align-items: center; gap: 3.60px; display: inline-flex">
                                <div
                                    style="width: 78px; height: 28px; color: #9CA1AA; font-size: 14px; font-family: Montserrat; font-weight: 600; text-transform: capitalize; word-wrap: break-word">
                                    Limit:</div>
                                <div
                                    style="width: 149px; height: 22px; color: #9CA1AA; font-size: 14px; font-family: Montserrat; font-weight: 400; word-wrap: break-word">
                                    ({{ featuredProduct?.currency }}) {{
                                    addCommans(featuredProduct?.subscription_amount) }}</div>
                            </div>
                            <div
                                style="width: 219px; justify-content: flex-start; align-items: center; gap: 3.60px; display: inline-flex">
                                <div
                                    style="width: 76px; height: 28px; color: #9CA1AA; font-size: 14px; font-family: Montserrat; font-weight: 600; text-transform: capitalize; word-wrap: break-word">
                                    Invitees:</div>
                                <div
                                    style="width: 139px; height: 22px; color: #9CA1AA; font-size: 14px; font-family: Montserrat; font-weight: 400; word-wrap: break-word">
                                    {{ featuredProduct.campaign.campaign_depositor_count.invitees }} Investors
                                </div>
                            </div>
                            <div
                                style="width: 219px; justify-content: flex-start; align-items: center; gap: 3.60px; display: inline-flex">
                                <div
                                    style="width: 78px; height: 28px; color: #9CA1AA; font-size: 14px; font-family: Montserrat; font-weight: 600; text-transform: capitalize; word-wrap: break-word">
                                    Expiry</div>
                                <div
                                    style="width: 137px; height: 22px; color: #44E0AA; font-size: 12px; font-family: Montserrat; font-weight: 700; word-wrap: break-word">
                                    <TimerClock :targetTime="featuredProduct.expiry_date"> </TimerClock>
                                </div>
                            </div>
                        </div>
                        <div
                            style="height: 40px; padding-left: 30px; padding-right: 30px; padding-top: 10px; padding-bottom: 10px; background: #5063F4; border-radius: 32px; overflow: hidden; flex-direction: column; justify-content: center; align-items: center; display: flex">
                            <div style="justify-content: center; align-items: center; gap: 10px; display: inline-flex">
                                <div style="color: white; font-size: 16px; font-family: Montserrat; font-weight: 600; text-transform: capitalize; line-height: 20px; word-wrap: break-word; cursor: pointer; "
                                    @click="viewSummary">
                                    View </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="featured-cusom-radius  bg-white " style="width: calc(25% - 20px);">
                    <div
                        style="flex-direction: column; justify-content: flex-start; align-items: center; gap: 3.60px; display: flex">
                        <div
                            style="justify-content: flex-start; align-items: flex-start; gap: 4px; display: inline-flex">

                            <div
                                style="color: #5063F4; font-size: 14px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                                Number of Clicks</div>
                        </div>
                        <div style="width: 100%; height: 0px; border: 1px #D9D9D9 solid"></div>
                        <div
                            style="width: 100%; height: 100%; padding-left: 1px; padding-right: 1px; flex-direction: column; justify-content: flex-start; align-items: flex-start; display: inline-flex">
                            <div
                                style="flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 20px; display: flex">
                                <div
                                    style="justify-content: flex-start; align-items: center; gap: 8px; display: inline-flex">
                                    <div
                                        style="color: #1E1B39; font-size: 40px; font-family: Montserrat; font-weight: 800; word-wrap: break-word">
                                        {{ total_clicks }}</div>
                                    <div
                                        style="color: #9CA1AA; font-size: 18px; font-family: Montserrat; font-weight: 500; word-wrap: break-word">
                                        Total <br />Clicks</div>
                                </div>
                            </div>
                            <div style="width: 114.88px; height: 92.70px;">
                                <img src="/assets/dashboard/icons/linechart.svg" />
                            </div>
                            <!-- <div
                                style="color: #252525; font-size: 14px; font-family: Inter; font-weight: 500; line-height: 16px; word-wrap: break-word">
                                +1.04%</div> -->
                        </div>
                    </div>
                </div>
                <div class="featured-cusom-radius   bg-white" style="width: calc(40% - 20px);">
                    <div style="justify-content: flex-start; align-items: center; display: flex; flex-direction:column">
                        <div style="flex-direction: column; justify-content: flex-start; display: inline-flex">
                            <div
                                style="justify-content: flex-start; align-items: center; gap: 4px; display: inline-flex">
                                <div
                                    style="color: #5063F4; font-size: 15px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 26px; ">
                                    Subscription </div>
                                <div><span
                                        style="color: #9CA1AA; font-size: 12px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 14px; word-wrap: break-word">Goal
                                        : </span><span
                                        style="color: #FF2E2E; font-size: 12px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 14px; word-wrap: break-word">(CAD)
                                        {{ addCommans(featuredProduct?.subscription_amount) }}</span></div>
                                <div><span
                                        style="color: #9CA1AA; font-size: 12px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 14px; word-wrap: break-word">Sold
                                        : </span><span
                                        style="color: #2A9928; font-size: 12px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 14px; word-wrap: break-word">(CAD)
                                        {{ addCommans(this.featured_sold) }}</span></div>



                            </div>
                            <div style="width: 100%; height: 0px; border: 1px #D9D9D9 solid"></div>
                        </div>
                        <div style="display:flex; justify-content:center; width: 100%;">
                            <!-- <SubscriptionGoal v-else :counter_series="0" dheight="260" /> -->
                            <div v-if="sold_percent != null">
                                <SubscriptionGoal :counter_series="sold_percent" dheight="300" />
                            </div>
                            <div v-else>

                                <EmptySubscriptionGoal counter_series="0" dheight="300" />

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
<style scoped>
    .featured-cusom-radius {
        border-radius: 10px;
        background: #FFF;
        box-shadow: 0px 4px 6px 0px rgba(64, 117, 219, 0.15);
        margin-right: 0px;
        padding: 15px;
        /* width: 80%; */
    }

    .featured-cusom-radius>div {
        /* width: 95%; */
    }
</style>
<script>
    import Button from "../../shared/Buttons/Button";
    import NoFeaturedProduct from "./NoFeaturedProduct";
    import NoOfClicks from "../../shared/Graphs/line/LineGraph.vue"
    import SubscriptionGoal from "../../shared/Graphs/pie/SemiCircularGraph.vue"
    import EmptySubscriptionGoal from "../../shared/Graphs/pie/SemiCircularGraph.vue"
    import TimerClock from "../TimerClock.vue";
    // import TimerClock from "../TimerClock.vue";
    export default {
        components: {
            NoOfClicks,
            SubscriptionGoal,
            EmptySubscriptionGoal,
            Button,
            NoFeaturedProduct,
            TimerClock
        },
        mounted() {
        },
        created() {
            this.getData();
            // this.getDashboardSummary();
        },
        props: [],
        data() {
            return {
                featuredProduct: null,
                is_loading: false,
                total_clicks: 0,
                featured_sold: 0,
                sold_percent: null
            }
        },
        methods: {
            addCommans(newvalue) {
                return newvalue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
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
            getSoldPercentage() {
                let value = 0
                value = this.featured_sold / this.featuredProduct?.subscription_amount
                // console.log("value start", value)
                value = Math.round(value * 100)
                this.sold_percent = value
                // console.log("value end", value)
            },
            getData() {
                let url = "/campaigns/fi/my-campaign-products?isFeatured=1";
                let this_ = this;
                this.is_loading = true;
                axios.get(url)
                    .then(response => {
                        if (response?.data?.data?.length > 0) {

                            this_.featuredProduct = response?.data?.data[0];

                            this_.getDashboardSummary(this_.featuredProduct.id)
                            // console.log(this_.featuredProduct.id)
                            // console.log(this_.featuredProduct.campaign.campaign_depositor_count.invitees);
                        }
                        this_.is_loading = false;
                    }).catch(error => {
                        console.log("error > " + error);
                        this_.is_loading = false;
                    });
            },
            getDashboardSummary(produt_id) {
                let url = "/campaigns/fi/analytics/get-campaign-dashboard-insights?product=" + produt_id;
                axios.get(url)
                    .then(response => {
                        if (response.data) {
                            const data = response.data

                            this.total_clicks = data.proddetails.today_campaign_product_clicks_count
                            data.proddetails.deposit_requests.forEach((items, index) => {
                                if (items.request_status = "COMPLETED") {
                                    this.featured_sold += items.amount
                                    // console.log(this.featured_sold)
                                    // this.getSoldPercentage()

                                }
                            });
                            this.getSoldPercentage()
                            // console.log("Dashboard summary");
                        }
                    }).catch(error => {
                        console.log("error > " + error);
                        this.is_loading = false;
                    });
            },
            viewSummary() {
                window.location.href = '/campaigns/campaign-product-summary/' + this.featuredProduct.id;
            }
        },
        watch: {
            // featuredProduct(newValue, oldValue) {
            //     console.log(newValue)
            // }
        }
    }
</script>