<template>
    <div
        style="width: 100%; min-height: 736px; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 30px; display: inline-flex; ">
        <div
            style="flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 20px; display: flex; width: 95%;">

            <accordion :is_open="true"
                :title="hasNoticePeriod ? datum?.lockout_period + ' Day ' + datum.description : datum.description"
                width="100" title_image="/assets/dashboard/icons/Setting__3.svg" />
            <div style="width: 93%; justify-content: flex-start; gap: 20px; display: inline-flex; margin-top: 20px">
                <div style="justify-content: space-between;display: flex;flex-direction: column;">
                    <div
                        style="height: 240px; padding: 60px 40px; background: white; box-shadow: 0 4px 4px #D9D9D9; flex-direction: column; justify-content: center; align-items: center; gap: 12px; display: inline-flex;">
                        <div
                            style="width: 100%; color: #5063F4; font-size: 68px; font-weight: 700; word-wrap: break-word; display: flex; justify-content: center; align-items: center;">
                            {{ datum?.rate }}%
                        </div>
                        <div style="width: 128px; height: 0; border: 0.25px #9CA1AA solid"></div>
                        <div
                            style="flex-direction: column; justify-content: flex-start; align-items: flex-start; display: flex;">
                            <div
                                style="width: 171px; text-align: center; color: #252525; font-size: 18px; font-weight: 700; word-wrap: break-word;">
                                Term Length
                            </div>
                            <div
                                style="width: 171px; text-align: center; color: #5063F4; font-size: 28px; font-weight: 700; word-wrap: break-word; text-transform: capitalize;">
                                {{ datum?.term_length + ' ' + datum?.term_length_type }}
                            </div>
                        </div>
                    </div>

                    <div
                        style="padding: 10px 30px;background: #D9D9D9; border-radius: 32px; overflow: hidden; justify-content: center; align-items: center; gap: 4px; display: flex; margin-bottom: 12px">
                        <div id="compare-rate-tooltip"
                            style="padding-left: 10px; padding-right: 10px; justify-content: center; align-items: flex-start; gap: 10px; display: flex">
                            <div
                                style="color: #FDFDFD; font-size: 16px; font-weight: 500; text-transform: capitalize; line-height: 20px; word-wrap: break-word">
                                Compare Rate</div>
                        </div>
                        <Tooltip target="compare-rate-tooltip" message="Coming soon" />
                    </div>
                </div>

                <div
                    style="flex-direction: column; justify-content: flex-start; align-items: flex-start; display: inline-flex; padding: 10px; box-sizing: border-box;">
                    <div
                        style="min-height: 240px; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 30px; display: flex;">
                        <div style="width: 100%;">
                            <span
                                style="color: #252525; font-size: 16px; font-weight: 700; line-height: 20px; word-wrap: break-word;">About
                                This GIC</span>
                            <span
                                style="color: #5063F4; font-size: 16px; font-weight: 700; line-height: 20px; word-wrap: break-word;"><br /></span>
                            <span style="color: #252525; font-size: 16px; font-weight: 500; word-wrap: break-word;"> {{
                                datum?.description }} GIC by {{ datum?.campaign?.organization?.name }} - {{
                                datum?.definition
                                }}.</span>
                        </div>

                        <!-- <ProductDetails :earning_text="datum.earning_text" :earning_rate="datum.earning_rate"
                            :flexibility_rate="datum.flexibility_rate" :flexibility_text="datum.flexibility_text" /> -->

                        <div
                            style="flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 10px; display: flex;">
                            <div
                                style="justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex;">
                                <div
                                    style="width: 80px; color: #252525; font-size: 16px; font-weight: 500; word-wrap: break-word;">
                                    Currency</div>
                                <div
                                    style="width: 90px; color: #252525; font-size: 16px; font-weight: 500; word-wrap: break-word;">
                                    Minimum</div>
                                <div
                                    style="width: 90px; color: #252525; font-size: 16px; font-weight: 500; word-wrap: break-word;">
                                    Maximum</div>
                                <div
                                    style="width: 130px; color: #252525; font-size: 16px; font-weight: 500; word-wrap: break-word;">
                                    Locked in Period</div>
                                <div
                                    style="width: 150px; color: #252525; font-size: 16px; font-weight: 500; word-wrap: break-word;">
                                    Interest Paid</div>
                                <div
                                    style="width: 180px; color: #252525; font-size: 16px; font-weight: 500; word-wrap: break-word;">
                                    Compound Frequency</div>
                            </div>
                            <div
                                style="justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex;">
                                <div
                                    style="width: 80px; color: #5063F4; font-size: 17px; font-weight: 800; word-wrap: break-word;">
                                    {{ datum?.currency }}</div>
                                <div
                                    style="width: 90px; color: #5063F4; font-size: 17px; font-weight: 800; word-wrap: break-word;">
                                    {{ formatNumberAbbreviated(datum?.minimum) }}</div>
                                <div
                                    style="width: 90px; color: #5063F4; font-size: 17px; font-weight: 800; word-wrap: break-word;">
                                    {{ formatNumberAbbreviated(datum?.maximum) }}</div>
                                <div
                                    style="width: 130px; color: #5063F4; font-size: 17px; font-weight: 800; word-wrap: break-word;">
                                    {{ datum?.lockout_period ? datum?.lockout_period + ' Days'  :
                                        '-'
                                    }}</div>
                                    <!-- lockout Period should always be days what changes is term length -->

                                <div
                                    style="width: 150px; color: #5063F4; font-size: 17px; font-weight: 800; word-wrap: break-word;">
                                    {{ capitalize(datum?.interest_paid) }}</div>
                                <div
                                    style="width: 180px; color: #5063F4; font-size: 17px; font-weight: 800; word-wrap: break-word;">
                                    {{ capitalize(datum?.compound_frequency) }}</div>
                            </div>
                        </div>

                        <div
                            style="justify-content: flex-end; align-items: flex-end; gap: 30px; display: flex; flex-wrap: wrap; flex-direction: row;">

                            <div
                                style="align-self: stretch;flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 6px; display: flex;">
                                <CustomInput
                                    c-style="font-weight: 400; width: 100%; max-width: 273px; border-radius: 20px; height: 44px;"
                                    p-style="width: 100%; max-width: 273px; margin-top: 5px" id="investment_amount123"
                                    name="Enter Amount To Invest *" :has-validation="true"
                                    @inputChanged="investment_amount = $event" input-type="number"
                                    :default-value="investment_amount" :validation-failed="false"
                                    validation-error="Enter Amount To Invest" v-model="investment_amount" />
                            </div>

                            <!-- <div
                                style="padding: 10px 30px; border: #5063F4 1px; border-radius: 32px; overflow: hidden;  justify-content: center; align-items: center; gap: 4px; display: flex;">
                                <div style="padding-left: 10px; padding-right: 10px; justify-content: center; align-items: flex-start; gap: 10px; display: flex;"
                                    >
                                    <div
                                        style="color: white; font-size: 16px; font-weight: 500; text-transform: capitalize; line-height: 20px; word-wrap: break-word;">
                                        Counter Offer</div>
                                </div> -->
                                <!-- <Tooltip target="counter-offer-tooltip" message="Coming soon" /> -->
                                <!-- Your Tooltip component here -->
                            <!-- </div> -->

                            <div style="padding: 10px 30px; border: 2px solid  #5063F4; border-radius: 32px; overflow: hidden; justify-content: center; align-items: center; gap: 4px; display: flex; cursor: pointer;"
                                >
                                <div
                                    style="padding-left: 10px; padding-right: 10px; justify-content: center; align-items: flex-start; gap: 10px; display: flex;" @click="counterOfferSubmit()">
                                    <div
                                        style="color: #5063F4; font-size: 16px; font-weight: 400; text-transform: capitalize; line-height: 20px; word-wrap: break-word;">
                                        Counter Offer</div>
                                </div>
                            </div>

                            <div style="padding: 10px 30px; background: #5063F4; border-radius: 32px; overflow: hidden; justify-content: center; align-items: center; gap: 4px; display: flex; cursor: pointer;"
                                @click="submit()">
                                <div
                                    style="padding-left: 10px; padding-right: 10px; justify-content: center; align-items: flex-start; gap: 10px; display: flex;">
                                    <div
                                        style="color: white; font-size: 16px; font-weight: 700; text-transform: capitalize; line-height: 20px; word-wrap: break-word;">
                                        Confirm</div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <div style="width: 100%">
            <b-tabs content-class="mt-5"
                nav-wrapper-class="custom-tab-nav-class4 custom-tab-nav-class custom-tab-nav-classleft">
                <b-tab :title="'About ' + (datum?.campaign?.organization?.name)" active>
                    <AboutBank :datum="datum" />
                </b-tab>
                <b-tab :title="(datum?.campaign?.organization?.name) + ' Products'">
                    <BankOtherProducts :datum="datum" />
                </b-tab>
                <b-tab title="Related Products">
                    <RelatedProducts :datum="datum" />
                </b-tab>
                <b-tab title="Purchase History" v-if="hasOffers">
                    <PurchaseHistory :datum="datum"/>
                </b-tab>
                <b-tab title="Counter offer change log" v-if="hasCounterOffers">
                    <CounterOfferLogs :datum="datum" :offers="datum?.offers"/>
                </b-tab>
            </b-tabs>
        </div>
        <PurchaseRequestSubmitted :depositId="depositId" :show="purchaseCompleted"
            @visible="modalToggle($event, 'purchaseCompleted')" />
        <ReviewTransactionDetails :submitted="submitted" @submitConfirmed="submitConfirmed" :datum="datum"
            :investment_amount="investment_amount" :show="reviewTransactionDetails"
            @visible="modalToggle($event, 'reviewTransactionDetails')" />
        <OKButtonActionMessageBoxVue :size="successModalSize" @closedSuccessModal="showSuccessModal = false"
            :title="successModalTitle" :showm="showSuccessModal" @okClicked="showSuccessModal = false" fontsize="28px"
            :message="transError" />

        <ActionMessageBox style="width: 600px;" @closedSuccessModal="showResponseModal = false" @btnTwoClicked=""
            @btnOneClicked="showResponseModal = false" icon="/assets/signup/success_promo.svg"
            title="Your offer has been submitted." btnOneText="" btnTwoText="" :showm="showResponseModal">
            <div class="ml-5 description-text-withdraw ">Counter Offer Submitted</div>
        </ActionMessageBox>

        <CounterOffer :submitted="submitted" :datum="datum" :hasCounterOffer="hasCounterOffer"
            :investment_amount="investment_amount" :show="showCounterOfferModal" @closeModal = "showCounterOfferModal = false"
            @visible="closeCounterOfferModal" @closedSuccessModal="showSuccessModal = false" :offer="datum.offers" />


        <!-- <TransactionFailed :error="transError" :show="hasTransError" :toggleShow="toggleShow" /> -->
    </div>
</template>
<style>
    .form-input-text {
        font-size: 16px !important;
    }

    .custom-tab-nav-class>ul li .nav-link {
        font-size: 14px;
    }

    .custom-tab-nav-class4>ul {
        border-bottom: 1px solid #ccc;
        width: 93%;
    }

    #investment_amount123 {
        margin: 0 !important;
        height: 44px !important;
    }
</style>
<script>
    import CounterOffer from "./CounterOffer.vue";
    import AboutBank from "./AboutBank";
    import CustomInput from "../../../shared/CustomInput";
    import RelatedProducts from "./RelatedProducts";
    import BankOtherProducts from "./BankOtherProducts";
    import ReviewTransactionDetails from "./ReviewTransactionDetails";
    import PurchaseRequestSubmitted from "./PurchaseRequestSubmitted";
    import Tooltip from "../../../shared/Tooltip";
    import Accordion from "../../../shared/Accordion";
    import TransactionFailed from "./TransactionFailed";
    import ProductDetails from "../../../shared/ProductDetails.vue";
    import PurchaseHistory from "./PurchaseHistory.vue";
    import CounterOfferLogs from "./CounterOfferLogs.vue";
    import ActionMessageBox from "../../../shared/messageboxes/ActionMessageBox.vue";

    import OKButtonActionMessageBoxVue from "../../../shared/messageboxes/NoButtonActionErrorMessageBox.vue";

    export default {
        props: ['datum', 'is_summary', 'organization_id'],

        mounted() {
            this.markAsCliked()
            // console.log(this.datum) 
        },
        components: {
            CounterOfferLogs,
            PurchaseHistory,
            CounterOffer,
            OKButtonActionMessageBoxVue,
            TransactionFailed,
            PurchaseRequestSubmitted,
            ReviewTransactionDetails,
            CustomInput,
            AboutBank,
            RelatedProducts,
            BankOtherProducts,
            Accordion,
            Tooltip,
            ProductDetails,
            ActionMessageBox
        },
        data() {
            return {
                showCounterOfferModal: false,
                amount_purchased: "",
                successModalSize: "md",
                showSuccessModal: false,
                successModalTitle: "Transaction Error",
                investment_amount: null,
                purchaseCompleted: false,
                reviewTransactionDetails: false,
                depositId: null,
                submitted: false,
                hasTransError: false,
                transError: '',
                hasCounterOffer: false,
                showResponseModal: false
            }
        },
        methods: {
            closeCounterOfferModal(event){
                this.showCounterOfferModal = event;
                this.showResponseModal = true;
                //this.showSuccessModal = true;
                this.successModalTitle = "Counter offer request submitted";
            },
            counterOfferSubmit(){
                //console.log('tete')
                this.showCounterOfferModal =true;
            },
            toggleShow(t) {
                this.hasTransError = t;submitted
                this.transError = '';
            },
            capitalize(thestring) {
                if (thestring != null || thestring != null) {
                    return thestring
                        .toLowerCase()
                        .split(' ')
                        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
                        .join(' ');
                }
            },
            modalToggle(value, whichOne) {
                if (whichOne === "reviewTransactionDetails") {
                    this.reviewTransactionDetails = value;
                    return;
                }
                if (whichOne === 'showCounterOfferModal') {
                    this.showCounterOfferModal = value;
                }

                this.purchaseCompleted = value;
                // window.location.href = `/purchase-gic/${this.depositId}`
            },
            submitConfirmed() {
                this.submitted = false;
                return this.submit(true);
            },
            submit(final_submit = false) {

                if (!this.investment_amount) {
                    this.transError = "Please Enter The  Amount to purchase.";
                    this.showSuccessModal = true;
                    return;

                }
                let investment_amount = this.sanitizeAmount(this.investment_amount);
                // console.log(investment_amount,' <> ',this.datum);


                if (investment_amount > this.datum.maximum) {
                    this.transError = "Amount to invest should be less than or equal to maximum of " + this.datum?.currency + " " + this.addComma(this.datum.maximum);
                    this.showSuccessModal = true;
                    return;
                } else {
                    this.showSuccessModal = false;
                }
                if (investment_amount < this.datum.minimum) {
                    this.transError = "Amount to invest should be greater than or equal to minimum of " + this.datum?.currency + " " + this.addComma(this.datum.minimum);
                    this.showSuccessModal = true;
                    return;
                } else {
                    this.showSuccessModal = false;
                }

                // if (investment_amount > this.datum.order_limit) {
                //     this.transError = "Amount to invest should be less than or equal to the order limit: " + this.datum?.currency + " " + this.datum.order_limit;
                //     this.hasTransError = true;
                //     return;
                // }
                this.reviewTransactionDetails = true;
                if (!final_submit) {
                    return;
                }
                if (this.submitted) {
                    return;
                }
                this.submitted = true;
                const formData = new FormData();
                formData.append("amount", investment_amount);
                formData.append("product_id", this.datum?.id);
                axios.post('/inv-camp-offers-store', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(response => {
                    this.submitted = false;
                    this.reviewTransactionDetails = false;
                    this.depositId = response?.data?.data?.id;
                    // this.purchaseCompleted = true;
                    this.hasTransError = false;
                    this.investment_amount = "";
                    this.transError = "";
                    window.location.href = `/purchase-gic/${this.depositId}`

                }).catch(error => {
                    this.transError = error;
                    this.submitted = false;
                    this.hasTransError = true;
                });
            },
            formatNumberAbbreviated(number) {
                const SI_SYMBOL = ["", "K", "M", "G", "T", "P", "E"];

                const tier = (Math.log10(number) / 3) | 0;

                if (tier === 0) return number;

                const suffix = SI_SYMBOL[tier];
                const scale = Math.pow(10, tier * 3);

                const scaledNumber = number / scale;

                return scaledNumber.toFixed(0) + suffix;
            },
            async markAsCliked() {
                // let this_ = this
                try {
                    const response = await axios.post('/campaigns/depositor/register-depositor-campaign-product-view', { campaign_product: this.is_summary, depositor_org: this.organization_id });
                    console.log('Post request successful!', response.data);
                } catch (error) {
                    console.error('Error:', error);
                }
            },
            aboutGIC(productType) {
                productType = productType.toLowerCase();
                return this.aboutArray()[productType]
            },
            aboutArray() {
                return {
                    'non-redeemable': ' is tailor-made for those who prioritize liquidity and flexibility in their investments. This financial product grants you the freedom to access your funds whenever you need them, without the worry of facing penalties. It\'s an ideal solution that strikes a harmonious balance between financial growth and accessibility. With the Non-Redeemable GIC, you\'re empowered with a versatile and potent financial tool, giving you the confidence to make your money work for you while still maintaining control over your assets.',
                    'short term': ' is designed for those seeking quick returns, the Short Term Deposit offers a swift investment opportunity. With a shorter maturity period, this product is perfect for those who prefer seeing their funds grow in a shorter timeframe. It\'s a dynamic choice that aligns with your need for a timely and impactful financial solution.',
                    'cashable': ' puts you in control of your investments. Offering the flexibility to access your funds without penalties, it provides a secure avenue for growth while ensuring your financial freedom remains intact. This product combines the best of both worlds—steady financial growth and the liberty to adapt to your evolving needs.',
                    'notice deposit': ' is built for strategic financial planning, the Notice Deposit empowers you with the ability to save and grow your money on your terms. With this product, you have the advantage of providing a notice period before accessing your funds. This encourages a disciplined approach to financial management, letting you reap the benefits of growth while maintaining the flexibility to fulfill your financial goals.',
                    'high interest savings': ' is your gateway to a higher rate of return on your savings. Tailored for those who value both security and growth, it offers a competitive interest rate while keeping your funds readily accessible. This account empowers you to maximize your savings potential without sacrificing your ability to manage your finances efficiently.',
                };
            },
            sanitizeAmount(val) {
                try {
                    return parseFloat(val.replace(/,/g, ''));
                } catch (e) {
                    return val;
                }
            },
            addComma(newValue) {

                try {
                    if (isNaN(parseFloat(newValue.toString().replace(/,/g, '')))) {
                        return '';
                    } else {
                        let commavalue = newValue ? parseFloat(newValue.toString().replace(/,/g, '')).toLocaleString() : '';
                        if (newValue > 999999999999) {
                            this.hasTransError = true;
                            this.transError = "Please provide a valid number.";
                            return commavalue;
                        } else {
                            this.hasTransError = false;
                            this.transError = "";

                            if (this.name === "Rate*") {

                                let formattedValue = parseFloat(commavalue).toFixed(2);
                                return formattedValue;

                            } else {
                                return commavalue;
                            }


                        }
                    }


                } catch (e) {
                    return newValue
                }
            },
        },
        computed: {
            hasNoticePeriod() {
                // Using the includes method
                return (this.datum.description.includes("Notice deposit") || this.datum.description.includes("Cashable"));
            },
            hasCounterOffers() {
                for (let i = 0; i < this.datum?.offers.length; i++) {
                    const offer = this.datum.offers[i];
                    if (offer.counter_offers.length > 0) {
                        return true;
                    }
                }
                return false;
            },
            hasOffers(){
                const offer = this.datum;
                if (offer.offers.length > 0) {
                    return true;
                }
                return false;
            }
        }
    }
</script>
