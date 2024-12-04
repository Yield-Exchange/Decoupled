<template>
    <div style="">
        <!-- <accordion :is_open="true" width="100" title="Campaign Summary" title_image="/assets/dashboard/icons/Promo.svg" /> -->

        <div
            style="width: 99%; background: #EFF2FE; padding-left: 2px; justify-content: flex-start; align-items: center; display: inline-flex">
            <div style="justify-content: space-between; display: inline-flex; width: 99%;">
                <div class="page-title">
                    <div class="title-icon">
                        <img src="/assets/dashboard/icons/Promo.svg" style="height: 40px; width: 50px;" />
                    </div>
                    <div class="text-div">Review Draft Campaign</div>
                </div>
                <div @click="toggleView(1)"
                    style="justify-content: flex-start; align-items: center; gap: 9px; display: flex;cursor: pointer">
                    <div
                        style="text-align: center; color: #252525; font-size: 14px; font-weight: 500; line-height: 18px; word-wrap: break-word">
                        View {{ viewMore1 ? 'Less' : 'More' }}</div>
                    <img v-if="viewMore1" src="/assets/dashboard/icons/Polygon.svg" />
                    <img v-else src="/assets/dashboard/icons/Polygon 2.svg" />
                </div>
            </div>
        </div>
        <div v-if="viewMore1">
            <div style="margin-top: 30px; width:99%">
                <div
                    style="flex-direction: column; justify-content: center; align-items: flex-start; gap: 10px; display: flex; width: 90%;">
                    <table class="table" style="background: transparent">
                        <thead class="customHeader1" style="background: transparent;border-collapse: collapse;">
                            <tr style="border: none!important;">
                                <th>Campaign Name</th>
                                <!-- <th>Products</th> -->
                                <th>Currency</th>
                                <th>Subscription Limit</th>
                                <th>Depositors</th>
                                <th>Start Date</th>
                                <th>Closing Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="customBody1" style="background: transparent;border-collapse: collapse;">
                            <tr style="border: none!important;">
                                <td>{{ capitalize(camp?.campaign_name) }}</td>
                                <!-- <td>{{ camp?.campaign_products?.length }}</td> -->
                                <td>{{ camp?.currency }}</td>
                                <td>{{ addCommas(camp?.subscription_amount) }}</td>
                                <td>{{ camp?.campaign_depositor_count?.invitees }}</td>
                                <td>{{ camp?.start_date }}</td>
                                <td>
                                    {{ camp?.expiry_date }}
                                </td>
                                <td>{{ capitalize(camp?.status) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div style="width: 99%;margin-top: 40px">
                <table class="table table-vue-custom">
                    <thead class="customHeader" style="background: transparent">
                        <tr>
                            <th>Product&nbsp;Name</th>

                            <th>Term&nbsp;Length</th>
                            <th>Rate</th>
                            <th>Minimum</th>
                            <th>Maximum</th>
                            <!-- <th>Order&nbsp;Limit</th> -->
                            <th>
                                <div style="display: flex; flex-direction: row; gap: 5px">
                                    PDS
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17"
                                        fill="none" id="pds">
                                        <path
                                            d="M6.06016 6.50016C6.2169 6.05461 6.52626 5.6789 6.93347 5.43958C7.34067 5.20027 7.81943 5.11279 8.28495 5.19264C8.75047 5.27249 9.17271 5.51451 9.47688 5.87585C9.78106 6.23718 9.94753 6.69451 9.94683 7.16683C9.94683 8.50016 7.94683 9.16683 7.94683 9.16683M8.00016 11.8335H8.00683M14.6668 8.50016C14.6668 12.1821 11.6821 15.1668 8.00016 15.1668C4.31826 15.1668 1.3335 12.1821 1.3335 8.50016C1.3335 4.81826 4.31826 1.8335 8.00016 1.8335C11.6821 1.8335 14.6668 4.81826 14.6668 8.50016Z"
                                            stroke="#5063F4" stroke-width="1.33333" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                    <Tooltip placement="top" target="pds" message="Product disclosure statement" />
                                </div>
                            </th>

                        </tr>
                    </thead>
                    <tbody class="customBody">
                        <tr v-if="camp.campaign_products" v-for="(product, index) in camp.campaign_products">
                            <td>{{ capitalize(product?.product?.custom_product_name) }}</td>
                            <td>{{ product?.product?.term_length }} {{ capitalize(product?.product?.term_length_type) }}
                            </td>
                            <td>{{ product.rate }}%</td>
                            <td>{{ addCommas(product.minimum) }}</td>
                            <td>{{ addCommas(product.maximum) }}</td>
                            <td>
                                <div v-if="product.pds" @click="viewLink('/uploads/pds/' + product.pds)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                        fill="none" style="cursor: pointer">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3.33301 3.33341C3.33301 2.89139 3.5086 2.46746 3.82116 2.1549C4.13372 1.84234 4.55765 1.66675 4.99967 1.66675H14.9997C15.4417 1.66675 15.8656 1.84234 16.1782 2.1549C16.4907 2.46746 16.6663 2.89139 16.6663 3.33341V16.6667C16.6663 17.1088 16.4907 17.5327 16.1782 17.8453C15.8656 18.1578 15.4417 18.3334 14.9997 18.3334H4.99967C4.55765 18.3334 4.13372 18.1578 3.82116 17.8453C3.5086 17.5327 3.33301 17.1088 3.33301 16.6667V3.33341ZM14.9997 3.33341H4.99967V16.6667H14.9997V3.33341ZM6.66634 7.50008C6.66634 7.27907 6.75414 7.06711 6.91042 6.91083C7.0667 6.75455 7.27866 6.66675 7.49967 6.66675H12.4997C12.7207 6.66675 12.9326 6.75455 13.0889 6.91083C13.2452 7.06711 13.333 7.27907 13.333 7.50008C13.333 7.7211 13.2452 7.93306 13.0889 8.08934C12.9326 8.24562 12.7207 8.33341 12.4997 8.33341H7.49967C7.27866 8.33341 7.0667 8.24562 6.91042 8.08934C6.75414 7.93306 6.66634 7.7211 6.66634 7.50008ZM7.49967 10.8334C7.27866 10.8334 7.0667 10.9212 6.91042 11.0775C6.75414 11.2338 6.66634 11.4457 6.66634 11.6667C6.66634 11.8878 6.75414 12.0997 6.91042 12.256C7.0667 12.4123 7.27866 12.5001 7.49967 12.5001H9.99967C10.2207 12.5001 10.4326 12.4123 10.5889 12.256C10.7452 12.0997 10.833 11.8878 10.833 11.6667C10.833 11.4457 10.7452 11.2338 10.5889 11.0775C10.4326 10.9212 10.2207 10.8334 9.99967 10.8334H7.49967Z"
                                            fill="#5063F4" />
                                    </svg>
                                </div>
                                <div v-else> - </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div
                style="justify-content: flex-end; align-items: center; display: inline-flex;width: 99%; margin-bottom: 10px;">
                <div style="justify-content: flex-end; align-items: flex-start; gap: 50px; display: inline-flex">
                    <b-button v-if="camp?.status.toLowerCase() !== 'expired'" @click="backToDrafts()"
                        style="width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: #5063F4 2px solid !important;background-color: #EFF2FE !important;color:#5063F4;  !important;">
                        Cancel
                    </b-button>
                    <b-button @click="pushToActiveCampaigns()"
                        style=" width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: 2px !important;background-color: #5063F4 !important;color: white !important;">
                        Activate
                    </b-button>
                </div>
            </div>
        </div>

        <GeneralNoInteractionError :size="box_size" @cancelled="() => showgeneralmessagebox = false" :title="box_title"
            :show="showgeneralmessagebox" :message="box_message" />

        <OKButtonActionMessageBoxVue :size="successModalSize" :title="successModalTitle" :showm="showSuccessModal"
            @okClicked="goToCamapignSummary" @closedSuccessModal="goToCamapignSummary" />

        <ConfirmActivation :size="successModalSize" @closedSuccessModal="confirmRemoveOrg = 0" btnOneText="No"
            btnTwoText="Yes" :title=confirmOrganizationRemoveMessage @btnOneClicked="confirmRemoveOrg = 0"
            @btnTwoClicked="validateCampaignDetails()" message="Are You sure you want to activate the campaign?"
            :showm="confirmRemoveOrg === 1" />



    </div>
</template>

<style scoped>
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

    .no-data {
        color: var(--yield-exchange-pallette-yield-exchange-blue, var(--yield-exchange-colors-yield-exchange-purple, #5063F4));
        text-align: center;
        font-family: Montserrat;
        font-size: 14px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
    }

    .province_clicks {
        color: var(--yield-exchange-pallette-yield-exchange-grey, #9CA1AA);
        font-feature-settings: 'clig' off, 'liga' off;

        /* Yield Exchange Text Styles/Promotion Chart titles */
        font-family: Montserrat;
        font-size: 16px;
        font-style: normal;
        font-weight: 700;
        line-height: 26px;
        /* 162.5% */
        text-transform: capitalize;
    }

    .province_clicks>span {
        color: #5063F4
    }

    .filter-button-active {
        background: var(--yield-exchange-colors-yield-exchange-purple, #5063F4);
        color: white;
        border-radius: 10px;
        padding-left: 15px;


    }

    .activeInsightBar {
        background: #44E0AA;
    }

    .inactiveInsightBar {
        background: #9CA1AA;
    }

    .table-vue-custom {
        background: white;
    }

    thead.customHeader1 {
        background: none !important;
        height: 51px;
    }

    thead.customHeader {
        background: #EFF2FE !important;
        height: 51px;
    }

    thead.customHeader tr {
        border-bottom: 0 solid #b3b2b2 !important;
    }

    thead.customHeader tr th {
        color: black;
        font-size: 13px !important;
        font-weight: 700;
        background: inherit !important;
        text-align: left !important;
    }

    thead.customBody tr td {
        /*text-align: center!important;*/
    }

    tbody.customBody1>tr>td {
        background: inherit !important;
        text-align: left !important;
        border: none !important;
        color: #5063F4;
        font-size: 14px !important;
        font-weight: 800 !important;
        word-wrap: break-word;
    }

    thead.customHeader1>tr>th {
        background: inherit !important;
        text-align: left !important;
        border: none !important;
        color: #252525;
        font-size: 16px;
        font-weight: 700;
        word-wrap: break-word;
    }

    tbody tr td {
        font-size: 13px !important;
    }

    .term_length_custom>.form-control {
        max-width: 70px !important;
    }
</style>
<script>
    import Accordion from "../../shared/Accordion";
    import Tooltip from "../../shared/Tooltip";
    import Button from "../../shared/Buttons/Button"
    import FeatureUnfeatureProduct from "./FeatureUnfeatureProduct";
    import Actions from "../../shared/Table/Actions";
    import DeactivateCampaignProduct from "./DeactivateCampaignProduct";
    import TimerClock from "../TimerClock";
    import BarGraph from "../../shared/Graphs/bar/BarGraph";
    import DonutPie from "../../shared/Graphs/pie/DonutPie.vue";
    import SemiCircularGraph from "../../shared/Graphs/pie/SemiCircularGraph";

    import LineColumnGraphVue from '../../shared/Graphs/bar/LineColumnGraph.vue';
    import LineColumn from '../../shared/Graphs/line/LineColumn.vue';
    import NoInsights from '../../shared/Graphs/NoInsights.vue'

    import TwoButtonActionMessageBox from "../../shared/messageboxes/TwoButtonActionMessageBox.vue";
    import OKButtonActionMessageBoxVue from "../../shared/messageboxes/OKButtonActionMessageBox.vue";
    import GeneralNoInteractionError from "../../shared/messageboxes/GeneralNoInteractionError.vue";
    import ConfirmActivation from "../../shared/messageboxes/ConfirmDeletionPrompt.vue";

    // LineColumnGraphVue
    export default {
        components: {
            ConfirmActivation,
            OKButtonActionMessageBoxVue,
            TwoButtonActionMessageBox,
            GeneralNoInteractionError,
            LineColumn,
            Invited: DonutPie,
            LineColumnGraphVue,
            InvitedPrrogress: DonutPie,
            InvitedActive: DonutPie,
            SubscriptioGoal: SemiCircularGraph,
            UniqueClicksProvince: BarGraph,
            UniqueClicksProduct: BarGraph,
            MarketSectorReach: BarGraph,
            TimerClock,
            FeatureUnfeatureProduct,
            Accordion,
            Tooltip,
            NoInsights,
            Button,
            Actions,
            DeactivateCampaignProduct
        },
        props: ['campaign'],
        mounted() {
            console.log(this.camp?.id)
        },
        created() {

        },
        computed: {
            percentSold() {
                return (Math.round(this.camp.subscription_amount / this.sold_amount))
            }
        },

        data() {
            // console.log(JSON.parse(this.campaign.id));
            return {
                box_size: 'md',
                confirmOrganizationRemoveMessage: 'Activate Camapign?',
                confirmRemoveOrg: 1,
                showgeneralmessagebox: false,
                box_title: '',
                box_message: '',
                successModalSize: "md",
                successModalTitle: '',
                showSuccessModal: false,
                needToUpdate: [],
                activeInsight: 1,
                products: ['Redeemable', 'Non-Redeemable', 'Overnight', 'High Interest', 'Cashable'],
                productsClicks: ['0', '0', '0', '0', '0'],
                provinces: [],
                viewMore1: true,
                viewMore2: false,
                provinceClicks: [],
                camp: JSON.parse(this.campaign),
                activeDepositsProducts: [],
                activeDepositsProductsValues: [],
                activeDepositsProductsColors: [],
                inProgressDepositsProducts: [],
                inProgressDepositsProductsValues: [],
                inProgressDepositsProductsColors: [],
                productWithClicks: [],
                productWithClicksValues: [],
                productWithClicksColors: [],
                inviteddepositors: [],
                inviteddepositorsValues: [],
                inviteddepositorsColors: [],
                sold_amount: 0,
                marketTypes: [],
                marketTypesClicks: [],
                total_market_clicks: 0,
                marketRates: [],
                marketRatesLabels: [],
                myRate: [],
                sold_percent: null,
                activeFilterDuration: 3,
                insights_available: false,
                product_clicks: 0,
                invited_depositors: 0,
                inprogress_depositors: 0,
                active_depositors: 0,
                province_count: 0


            }
        },
        methods: {
            changeFilterDuration(value) {
                this.provinces = []
                this.provinceClicks = []
                this.activeDepositsProducts = []
                this.activeDepositsProductsValues = []
                this.activeDepositsProductsColors = []
                this.inProgressDepositsProducts = []
                this.inProgressDepositsProductsValues = []
                this.inProgressDepositsProductsColors = []
                this.productWithClicks = []
                this.productWithClicksValues = []
                this.productWithClicksColors = []
                this.inviteddepositors = []
                this.inviteddepositorsValues = []
                this.inviteddepositorsColors = []
                this.sold_amount = 0
                this.marketTypes = []
                this.marketTypesClicks = []
                this.total_market_clicks = 0
                this.marketRates = []
                this.marketRatesLabels = []
                this.product_clicks = 0
                this.myRate = []
                this.sold_percent = null
                this.province_count = 0
                this.activeFilterDuration = value

            },

            getColor(label) {
                switch (label) {
                    case 'Redeemable':
                        return '#2A9928';
                    case 'Non-Redeemable':
                        return '#F4B63C';
                    case 'Overnight':
                        return '#5063F4';
                    case 'High Interest':
                        return '#44E0AA';
                    case 'Cashable':
                        return '#9CA1AA';
                    default:
                        return 'No Color Found'; // Or any default color if needed
                }
            },

            addCommas(newvalue) {
                return newvalue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            },
            back() {
                window.location.href = '/campaigns';
            },
            viewLink(link) {
                window.open(link, '_blank')
            },
            scheduleCampaign() {
                axios.post('/campaigns/fi/update-campaign', {
                    'campaignName': this.camp.campaign_name,
                    'expiryDate': this.camp.expiry_date,
                    'startDate': this.camp.start_date,
                    'currency': this.camp.currency,
                    'subscriptionAmount': this.camp.subscription_amount,
                    'status': 'SCHEDULED',
                    'campaign': this.camp?.id
                }).then(response => {
                    this.submittedSaveDraft = false;
                    if (response?.data?.success) {
                        this.showSuccessModal = true;
                        this.successModalTitle = "Campaign Scheduled for Activation";
                    } else {
                        throw new Error(response?.data?.message);
                    }
                }).catch(error => {

                });
            },
            backToDrafts() {
                window.location.href = '/campaigns/drafts/';
            },
            goToCamapignSummary() {
                this.showSuccessModal = false;
                // window.location.href = '/campaigns';
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('isDraft')) {
                    this.editableSteps = urlParams.get('steps').split(",");
                    var currentUrl = window.location.href;
                    var baseUrl = currentUrl.split('?')[0];
                    var newParams = `isDraft=1&steps=${this.editableSteps}`;
                    var updatedUrl = baseUrl + '?' + newParams;
                    console.log(this.editableSteps, "this.editableSteps");

                    if (this.editableSteps[0] != '') {
                        window.location.href = updatedUrl + '&_refresh=' + Math.random();
                    } else {
                        window.location.href = '/campaigns/summary/' + this.camp?.id;
                    }


                } else {
                    window.location.href = '/campaigns/summary/' + this.camp?.id;
                }
            },
            pushToActiveCampaigns() {
                this.confirmOrganizationRemoveMessage = 'Activate Camapign?';
                this.confirmRemoveOrg = 1;

            },
            validateCampaignDetails() {
                this.confirmRemoveOrg = 0;
                axios.get(`/campaigns/fi/get-campaign-details/?campaign=${this.camp?.id}`)
                    .then(response => {
                        let campaign_details = response?.data;
                        this.checkAllCampaignDetails(campaign_details);
                        this.checkCampaignProductDetails(campaign_details.campaign_products);
                        this.checkCampaignDepositorsDetails(campaign_details.campaign_invite_depositors);

                        if (this.needToUpdate?.length > 0) {
                            this.editIt();
                        } else {
                            this.scheduleCampaign();
                        }
                        console.log(this.needToUpdate, "needToUpdate");


                    }).catch(error => {
                        console.log("error > " + error);
                    });

            },
            checkAllCampaignDetails(campaign) {
                let campain_details_done = false;
                if (campaign.campaign_name == "" || campaign.start_date == "" || campaign.expiry_date == "" || campaign.subscription_amount == "" || campaign.currency == "") {

                    if (!this.needToUpdate.includes("campaign_details")) {
                        this.needToUpdate.push("campaign_details");
                    }
                    campain_details_done = false;
                } else {
                    campain_details_done = true;
                }
                return campain_details_done;
            },
            checkCampaignDepositorsDetails(depositors) {
                let campaign_depositors_done = false;
                if (depositors.length <= 0) {
                    if (!this.needToUpdate.includes("campaign_depositors")) {
                        this.needToUpdate.push("campaign_depositors");
                    }
                    campaign_depositors_done = true;
                }
                return campaign_depositors_done;
            },
            checkCampaignProductDetails(products) {
                let camapign_products_done = false;
                if (products.length <= 0) {

                    if (!this.needToUpdate.includes("campaign_products")) {
                        this.needToUpdate.push("campaign_products");
                    }
                    camapign_products_done = true;
                }
                products.forEach((value, key) => {
                    console.log(value);
                    if (value.maximum == 0 || value.minimum == 0 || value.rate == 0.00) {
                        if (!this.needToUpdate.includes("campaign_products")) {
                            this.needToUpdate.push("campaign_products");
                        }
                        camapign_products_done = false;
                        return value;
                    }
                })
                return camapign_products_done;
            }, editIt() {
                window.location.href = `/campaigns?campaign_id=${this.actionId}&isDraft=1&steps=${this.needToUpdate}`;
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
            toggleView(index) {
                if (index === 1)
                    this.viewMore1 = !this.viewMore1;
                else
                    this.viewMore2 = !this.viewMore2
            },
        }
    }
</script>
