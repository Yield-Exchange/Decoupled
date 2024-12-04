<template>
    <div class="w-100">
        <div
            style="display: flex; flex-direction:row ; margin-top: 10px; justify-content: flex-end; width: 100%; margin-bottom: 15px;">
            <div style="display: flex;justify-content:flex-end; border-radius: 12px; background-color: white; ">


            </div>
        </div>

        <div v-if="activeInsight === 1">
            <div class="row p-0 gx-0 gap-1 justify-content-between">

                <div class="col-md-6 p-3 bg-white stat-item-wrapper" style="width: 49%; max-height: 400px !important;">
                    <div class="d-flex justify-content-between">
                        <p class="gic-stat-name">
                            Trending Terms
                        </p>
                        <p class="gic-stat-for"> <span>{{ totalInvestors }} &nbsp</span>Total Investors</p>
                    </div>

                    <div class="row gx-0">
                        <!-- <DonutPie></DonutPie> -->

                        <div class="col-md-12" style="max-height: 400px;">
                            <TrendingTerms :labels="trendingTermlabels" seriesName="Deposits Made"
                                :series="trendingTermseries"></TrendingTerms>
                        </div>
                    </div>

                </div>

                <div class="col-md-6 p-3 bg-white stat-item-wrapper" style="width: 49%;">
                    <div class="d-flex justify-content-between">
                        <p class="gic-stat-name">
                            Trending GIC's
                        </p>
                        <p class="gic-stat-for"> <span>{{ totalInvestors }} </span>Total Investors</p>
                    </div>
                    <div v-if="plotgicstrents">
                        <TrendingGic height="200" barcolor="#44E0AA" seriesName="Deposits Made"
                            :labels="trendingGICSlabels" :values="trendingGICSseries" />
                    </div>
                </div>

                <!-- <div class="col-md-6"></div> -->
            </div>
        </div>
        <div v-if="activeInsight === 2">
            <div class="row p-0 gx-0 gap-1 justify-content-between">

                <div class="col-md-12 p-3 bg-white stat-item-wrapper">
                    <div class="d-flex justify-content-between">
                        <p class="gic-stat-name">
                            Market Overview
                        </p>
                    </div>
                    <div class="row">

                        <div v-if="plotMarketRates">
                            <MarketOverview :shorttermdatalist="shortTermMonthlyTrend"
                                :cashabledatalist="cashableMonthlyTrend"
                                :nonredeemabledatalist="nonRedeemableMonthlyTrend"
                                :reedemabledatalist="redeemableMonthlyTrend"
                                :noticedepositdatalist="noticeDepositMonthlyTrend"
                                :highinterrestratelist="highInterestMonthlyTrend" />
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <div class="w-100 d-flex justify-content-center">
            <div
                style="width: 100%; height: 100%; margin-top:10px;  display: flex; align-items: center; justify-content:center; cursor:pointer">
                <div @click="setActiveInsight(1)"
                    v-bind:class="(activeInsight === 1) ? 'activeInsightBar' : 'inactiveInsightBar'"
                    style="width: 40px; height: 7px; left: 0px; top: 0px;   border-radius: 10px; margin-right: 15px;">

                </div>
                <div @click="setActiveInsight(2)"
                    v-bind:class="(activeInsight === 2) ? 'activeInsightBar' : 'inactiveInsightBar'"
                    style="width: 40px; height: 7px; left: 49px; top: 0px;  border-radius: 10px;">
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    import DonutPie from '../../shared/Graphs/pie/DonutPie.vue';
    import BarGraph from '../../shared/Graphs/bar/BarGraph.vue';
    import MultiLine from '../../shared/Graphs/line/MultiLine.vue';
    import DonutPieNew from '../../shared/Graphs/pie/DonutPieNew.vue';

    export default {
        created() {

        },
        mounted() {

        },
        data() {
            this.getInsights();
            return {
                activeInsight: 1,
                activeFilterDuration: 3,
                totalInvestors: 0,
                trendingTermlabels: [],
                trendingTermseries: [],
                trendingGICSlabels: [],
                trendingGICSseries: [],
                plotgicstrents: false,
                plotMarketRates: false,
                shortTermMonthlyTrend: [],
                cashableMonthlyTrend: [],
                redeemableMonthlyTrend: [],
                nonRedeemableMonthlyTrend: [],
                noticeDepositMonthlyTrend: [],
                highInterestMonthlyTrend: [],

            };
        },
        computed: {
        },
        methods: {
            capitalize(thestring) {
                if (thestring != undefined) {
                    return thestring
                        .toLowerCase()
                        .split(' ')
                        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
                        .join(' ');
                }

            },
            getInsights() {
                //  let url = `https://api.npoint.io/9f208d6fc2d660d96e05`;
                let url = `/campaigns/depositor/analytics/get-trend-data`;
                axios.get(url)
                    .then(response => {
                        let returneddata = response?.data;
                        console.log(returneddata?.productByMonthTrend
                            , "returneddatareturneddatareturneddata");
                        this.totalInvestors = returneddata?.totalInvestors;
                        returneddata?.productPurchaseTrend.forEach(gictrend => {
                            this.trendingGICSlabels.push(this.capitalize(gictrend?.description));
                            this.trendingGICSseries.push(gictrend?.total_bought);
                        })
                        returneddata?.termTrend.forEach(prodTrend => {
                            this.trendingTermlabels.push(this.capitalize(prodTrend?.term));
                            this.trendingTermseries.push(prodTrend?.total_bought);
                        })
                        this.plotgicstrents = true;
                        let cashableMonths, cashablePurchases = [];
                        let shorttermMonths, shortTermPurchases = [];
                        let redeemableMonths, redeemablePurchases = [];
                        let nonredeemableMonths, nonRedeemablePurchases = [];
                        let noticedepositMonths, noticeDepositPurchases = [];
                        let highinterestMonts, highinterestPurchases = [];
                        returneddata?.productByMonthTrend?.forEach(prodTrend => {
                            if (prodTrend.Short_Term) {
                                shorttermMonths = Array.from(new Set(prodTrend.Short_Term.map(item => item.Month)));
                                shortTermPurchases = Array.from(new Set(prodTrend.Short_Term.map(item => item.total_bought)));
                            }
                            if (prodTrend.Cashable) {
                                cashableMonths = Array.from(new Set(prodTrend.Cashable.map(item => item.Month)));
                                cashablePurchases = Array.from(new Set(prodTrend.Cashable.map(item => item.total_bought)));
                            }
                            if (prodTrend.Non_redeemable) {
                                nonredeemableMonths = Array.from(new Set(prodTrend.Non_redeemable.map(item => item.Month)));
                                nonRedeemablePurchases = Array.from(new Set(prodTrend.Non_redeemable.map(item => item.total_bought)));
                            }
                            if (prodTrend.Non_Redeemable) {
                                nonredeemableMonths = Array.from(new Set(prodTrend.Non_Redeemable.map(item => item.Month)));
                                nonRedeemablePurchases = Array.from(new Set(prodTrend.Non_Redeemable.map(item => item.total_bought)));
                            }
                            if (prodTrend.Redeemable) {
                                redeemableMonths = Array.from(new Set(prodTrend.Redeemable.map(item => item.Month)));
                                redeemablePurchases = Array.from(new Set(prodTrend.Redeemable.map(item => item.total_bought)));
                            }
                            if (prodTrend.Notice_Deposit) {
                                noticedepositMonths = Array.from(new Set(prodTrend.Notice_Deposit.map(item => item.Month)));
                                noticeDepositPurchases = Array.from(new Set(prodTrend.Notice_Deposit.map(item => item.total_bought)));
                            }
                            if (prodTrend.Notice_deposit) {
                                noticedepositMonths = Array.from(new Set(prodTrend.Notice_deposit.map(item => item.Month)));
                                noticeDepositPurchases = Array.from(new Set(prodTrend.Notice_deposit.map(item => item.total_bought)));
                            }
                            if (prodTrend.High_Interest_Saving) {
                                highinterestMonts = Array.from(new Set(prodTrend.High_Interest_Saving.map(item => item.Month)));
                                highinterestPurchases = Array.from(new Set(prodTrend.High_Interest_Saving.map(item => item.total_bought)));

                            }
                        })
                        for (let month = 1; month < 12; month++) {
                            if (shorttermMonths != undefined && shorttermMonths != null && shorttermMonths != "") {
                                this.shortTermMonthlyTrend.push((shorttermMonths.indexOf(month) > -1) ? shortTermPurchases[shorttermMonths.indexOf(month)] : 0);
                            } else {
                                this.shortTermMonthlyTrend.push(0);
                            }
                            if (cashableMonths != undefined && cashableMonths != null && cashableMonths != "") {
                                this.cashableMonthlyTrend.push((cashableMonths.indexOf(month) > -1) ? cashablePurchases[cashableMonths.indexOf(month)] : 0);
                            } else {
                                this.cashableMonthlyTrend.push(0);
                            }
                            if (redeemableMonths != undefined && redeemableMonths != null && redeemableMonths != "") {
                                this.redeemableMonthlyTrend.push((redeemableMonths.indexOf(month) > -1) ? redeemablePurchases[redeemableMonths.indexOf(month)] : 0);
                            } else {
                                this.redeemableMonthlyTrend.push(0);
                            }
                            if (nonredeemableMonths != undefined && nonredeemableMonths != null && nonredeemableMonths != "") {
                                this.nonRedeemableMonthlyTrend.push((nonredeemableMonths.indexOf(month) > -1) ? nonRedeemablePurchases[nonredeemableMonths.indexOf(month)] : 0);
                            } else {
                                this.nonRedeemableMonthlyTrend.push(0);
                            }
                            if (noticedepositMonths != undefined && noticedepositMonths != null && noticedepositMonths != "") {
                                this.noticeDepositMonthlyTrend.push((noticedepositMonths.indexOf(month) > -1) ? noticeDepositPurchases[noticedepositMonths.indexOf(month)] : 0);
                            } else {
                                this.noticeDepositMonthlyTrend.push(0);
                            }

                            if (highinterestMonts != undefined && highinterestMonts != null && highinterestMonts != "") {
                                this.highInterestMonthlyTrend.push((highinterestMonts.indexOf(month) > -1) ? highinterestPurchases[highinterestMonts.indexOf(month)] : 0);
                            } else {
                                this.highInterestMonthlyTrend.push(0);
                            }
                        }
                        this.plotMarketRates = true
                        console.log(this.cashableMonthlyTrend, "this.cashableMonthlyTrend");





                    }).catch(error => {
                        console.log("error > " + error);

                    });
            },
            isFilterActive(value) {
                if (this.activeFilterDuration === value)
                    return true
            },
            setActiveInsight(value) {
                this.activeInsight = value;
            },
            changeFilterDuration(value) {
                this.activeFilterDuration = value
            },
            async getData(url) {
                let endpoint = url ? url : ''
                await axios.get(endpoint, {}).then(response => {
                    if (response.status == 200) {

                    }

                }).catch(e => {
                    console.log(e)

                })
            }
        },
        components: {
            DonutPie,
            TrendingGic: BarGraph,
            MarketOverview: MultiLine,
            TrendingTerms: DonutPieNew
        }
    }

</script>

<style scoped>
    .activeInsightBar {
        background: #44E0AA;
    }

    .filter-button {
        cursor: pointer;
        text-align: center;
        color: #9291A5;
        font-size: 11px;
        font-family: Montserrat;
        padding: 10px;
        font-weight: 400;
        line-height: 14px;
        word-wrap: break-word;
        margin-left: 30px;
        color: var(--neutral-colors-400, #9291A5);
        text-align: center;

        /* Yield Exchange Text Styles/Tooltips */
        font-family: Montserrat;
        font-size: 11px;
        font-style: normal;
        font-weight: 400;
        line-height: 14px;
        /* 127.273% */

    }

    .filter-button-active {
        background: var(--yield-exchange-colors-yield-exchange-purple, #5063F4);
        color: white;
        border-radius: 10px;
        padding-left: 15px;


    }

    .inactiveInsightBar {
        background: #9CA1AA;
    }

    .gic-stat-name {
        color: #5063F4;
        /* font-feature-settings: 'clig' off, 'liga' off; */

        /* Yield Exchange Text Styles/Chart Titles */
        font-family: Montserrat;
        font-size: 22px;
        font-style: normal;
        font-weight: 400;
        line-height: 26px;
        /* 118.182% */
        text-transform: capitalize;
    }

    .gic-stat-for {
        color: #9CA1AA;
        /* font-feature-settings: 'clig' off, 'liga' off; */
        /* Yield Exchange Text Styles/Promotion Chart titles */
        font-family: Montserrat;
        font-size: 16px;
        font-style: normal;
        font-weight: 700;
        line-height: 26px;
        /* 162.5% */
        text-transform: capitalize;
    }

    .gic-stat-for>span {
        color: #5063F4;
    }

    .stat-item-wrapper {
        border-radius: 10px;
        background: var(--White, #FFF);
        box-shadow: 0px 2px 10px 0px rgba(0, 0, 0, 0.10);
    }

    .duration-button {
        display: flex;
        height: 24px;
        padding: 8px 5px;
        justify-content: center;
        align-items: center;
        gap: 7.218px;
        border-radius: 8px;
        border: 1px solid #5063F4;
        background-color: white;
        padding: 10px;

    }

    .offer-period {
        font-family: Montserrat;
        font-size: 14px;
        font-style: normal;
        font-weight: 500;
        line-height: 10px;
        /* 71.429% */
    }
</style>