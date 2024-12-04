<template>
    <div
        style="width: 100%; height: 100%; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 5px; display: inline-flex">
        <div
            style="align-self: stretch; height: 60px; background: #EFF2FE; flex-direction: column; justify-content: center; align-items: flex-start; gap: 10px; display: flex">
            <div
                style="align-self: stretch; justify-content: flex-start; align-items: center; gap: 815px; display: inline-flex">
                <div style="justify-content: center; align-items: center; gap: 10px; display: flex">
                    <div style="width: 40px; height: 40px; position: relative">
                        <div
                            style="width: 30px; height: 30px; left: 5px; top: 5px; position: absolute; background: #EFF2FE; border-radius: 28.80px; border: 1.35px #5063F4 solid">
                        </div>
                        <div
                            style="width: 14.11px; height: 14.11px; left: 13px; top: 13px; position: absolute; background: #5063F4">
                        </div>
                    </div>
                    <div
                        style="width: 327px; height: 25px; color: #252525; font-size: 28px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 32px; word-wrap: break-word">
                        Offer Summary
                    </div>
                </div>
            </div>
        </div>
        <div style="width: 100%; justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex">
            <div
                style="flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex">

                <div v-if="hasCounters"
                    style="width: 100%; height: 100%; padding-left: 40px; padding-right: 40px; padding-top: 30px; padding-bottom: 30px; background: white; box-shadow: 0px 4px 4px #D9D9D9; flex-direction: column; justify-content: center; align-items: center; gap: 10px; display: inline-flex">
                    <div
                        style="color: #252525; font-size: 16px; font-family: Montserrat; font-weight: 400; text-transform: capitalize; line-height: 26px; word-wrap: break-word">

                        {{ deposit_request?.invited?.deposit_request?.product_name}}</div>
                    <div
                        style="width: 181px; height: 42px; text-align: center; color: #5063F4; font-size: 55px; font-family: Montserrat; font-weight: 700; word-wrap: break-word">
                        {{latestOffer?.offered_interest_rate}}%
                    </div>
                    <div
                        style="width: 181px; text-align: center; color: #252525; font-size: 30px; font-family: Montserrat; font-weight: 600; text-decoration: line-through; word-wrap: break-word">
                        {{offer?.rate}}%</div>
                    <div style="width: 128px; height: 0px; border: 0.51px #9CA1AA solid"></div>
                    <div style="width: 171px; text-align: center"><span
                            style="color: #5063F4; font-size: 18px; font-family: Montserrat; font-weight: 700; word-wrap: break-word">Deposit
                            Amount<br /></span><span
                            style="color: #252525; font-size: 18px; font-family: Montserrat; font-weight: 700; word-wrap: break-word">CAD
                            {{ addCommans(deposit_request?.invited.deposit_request?.amount)}}</span></div>
                </div>
                <div v-else
                    style="padding-left: 40px; padding-right: 40px; padding-top: 30px; padding-bottom: 30px; background: white; box-shadow: 0px 4px 4px #D9D9D9; flex-direction: column; justify-content: center; align-items: center; gap: 10px; display: flex">

                    <div
                        style="color: #252525; font-size: 16px; font-family: Montserrat; font-weight: 400; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                        {{
                        deposit_request?.invited.deposit_request?.product_name }}</div>
                    <div
                        style="width: 181px; height: 42px; text-align: center; color: #5063F4; font-size: 55px; font-family: Montserrat; font-weight: 700; word-wrap: break-word">
                        {{offer.rate}}%</div>

                    <div
                        style="align-self: stretch; justify-content: flex-start; align-items: center; gap: 5px; display: inline-flex">
                        <div
                            style="flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 2px; display: flex;">
                            <avatar v-if=" deposit_request?.invited.organization?.logo" :size="30" :color="'white'"
                                :backgroundColor="'#4975E3'"
                                :initials=" deposit_request?.invited.organization?.name[0]"></avatar>
                            <avatar v-else :size="30" :color="'white'" :backgroundColor="'#4975E3'"
                                :src="'/image/' + deposit_request?.invited.organization?.logo"></avatar>
                        </div>
                        <div
                            style="height: 15px; flex-direction: column; justify-content: space-between; align-items: flex-start; display: flex">
                            <div style="color: #5063F4; font-size: 12px; font-weight: 500; word-wrap: break-word">{{
                                deposit_request?.invited.organization?.name }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div
                style="flex: 1 1 0; flex-direction: column; justify-content: center; align-items: flex-start; gap: 5px; display: inline-flex">
                <table class="table borderless" style="min-width: 1000px !important;">
                    <tbody>
                        <tr>
                            <td>
                                <div
                                    style="color: #252525; font-size: 16px; font-family: Montserrat; font-weight: 700; line-height: 20px; word-wrap: break-word">
                                    About {{
                                    deposit_request?.invited.deposit_request?.product_name }} GICs</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="textContainer">{{
                                    deposit_request?.invited.deposit_request?.product_description }} </div>
                            </td>
                        </tr>
                        <tr v-if="hasCounters">
                            <td>
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="202" height="21" viewBox="0 0 202 21"
                                        fill="none">
                                        <path d="M0 0H202L189.758 10.5L202 21H0V0Z" fill="#44E0AA" />
                                        <text x="15" y="15" font-family="Arial" font-size="20" fill="#FFF"
                                            style="color: #FFF;font-family: Montserrat;font-size: 14px;font-style: normal;font-weight: 600;line-height: normal;text-transform: capitalize;">{{latestOffer?.label}}</text>
                                    </svg>
                                </div>

                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="table borderless" style="margin-top: -14px !important; width:70%; min-width:850px">
                    <tbody>
                        <tr>
                            <td class="label">Minimum</td>
                            <td class="label">Maximum</td>
                            <td class="label">Rate Held Until</td>
                            <td class="label">PDS</td>
                            <td class="label">Special Instructions</td>
                        </tr>
                        <tr>
                            <td>
                                <div class="textContainer">CAD {{addCommans(offer.min)}}</div>
                            </td>
                            <td>
                                <div class="textContainer">CAD {{addCommans(offer.max)}}</div>
                            </td>
                            <td>
                                <div class="textContainer">{{formatDate(offer.rate_held)}}</div>
                            </td>
                            <td>
                                <div class="textContainer"></div>
                            </td>
                            <td>
                                <div class="textContainer"></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div
            style="width: 100%; justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex; margin-top:5px !important">
            <div
                style="flex-direction: column; width:280px; justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex">
                <CounterOfferBT :offer="offer" :deposit_request="this.deposit_request?.invited.deposit_request"
                    from="page" />
            </div>
            <div
                style="flex: 1 1 0; flex-direction: column; justify-content: center; align-items: flex-start; gap: 5px; display: inline-flex">
                <div
                    style="display: flex;flex-direction: row;justify-content: flex-start;width: 100%;gap: 50px; margin-left:10px; min-width: 1000px !important;">
                    <div style="width:25%">

                        <CustomInput name="Enter Awarded Amount" @inputChanged="setAwardedAmount($event)"
                            inputType="number" />
                        <span v-if="awardedAmountError.length>0" class="error-message">{{awardedAmountError}}</span>

                    </div>
                    <div style="width:22%; display:flex; flex-direction:column;">
                        <div
                            style="color: #252525; font-size: 15px; font-family: Montserrat; font-weight: 400; word-wrap: break-word">
                            Interest Earned</div>
                        <div
                            style="color: #9CA1AA; font-size: 20px; font-family: Montserrat; font-weight: 800; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                            CAD. {{addCommans(interestEarned)}}</div>
                    </div>
                    <div style="width:38%">
                        <b-button
                            style="width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important; color:#5063F4 !important; border: #5063F4 2px solid !important;background-color: white !important;color:#5063F4  !important;">
                            Cancel
                        </b-button>
                        <b-button
                            style=" width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: 2px !important;background-color: #5063F4 !important;color: white !important;">
                            Confirm
                        </b-button>
                    </div>
                </div>
            </div>
        </div>
        <div
            style="width: 100%; justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex;">
            <b-tabs content-class="mt-3" nav-wrapper-class="custom-tab-nav-class custom-tab-nav-class-border-bottom"
                style="width:100%;">
                <b-tab title="About The Bank" active>
                    <AboutFI :organization="deposit_request?.invited?.bank" />
                </b-tab>
                <b-tab title="Request Summary">
                    <div style="width: 99%;">
                        <table class="table borderless">
                            <tbody>
                                <tr>
                                    <td class="label">Product Type</td>
                                    <td class="label">Rate</td>
                                    <td class="label">Term Length</td>
                                    <td class="label">Lockout Period</td>
                                    <td class="label">Date Of Deposit</td>
                                    <td class="label">Requested Amount</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="textContainer">{{
                                            deposit_request?.invited.deposit_request?.product_name }}</div>
                                    </td>
                                    <td>
                                        <div class="textContainer">{{
                                            deposit_request?.invited.deposit_request?.requested_rate }}</div>
                                    </td>
                                    <td>
                                        <div class="textContainer">
                                            {{deposit_request?.invited.deposit_request?.term_length}}
                                            {{capitalize(deposit_request?.invited.deposit_request?.term_length_type) }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="textContainer">{{
                                            deposit_request?.invited.deposit_request?.lockout_period_days }} Days
                                        </div>
                                    </td>
                                    <td>
                                        <div class="textContainer">{{
                                            deposit_request?.invited.deposit_request?.date_of_deposit }}
                                        </div>

                                    </td>
                                    <td>
                                        <div class="textContainer">{{
                                            addCommans(deposit_request?.invited.deposit_request?.amount) }}</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table borderless" style="width:70%">
                            <tbody>
                                <tr>
                                    <td class="label">Compounding freq</td>
                                    <td class="label">DBRS Rating</td>
                                    <td class="label">Deposit Insurance</td>
                                    <td class="label">Closing Date & Time</td>
                                    <td class="label">Special Instructions</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="textContainer">{{
                                            deposit_request?.invited.deposit_request?.compound_frequency }}</div>
                                    </td>
                                    <td>
                                        <div class="textContainer">
                                            {{
                                            (
                                            deposit_request?.invited.deposit_request?.user?.organization?.ratings?.credit_rating?.description)
                                            ?
                                            deposit_request?.invited.deposit_request?.user?.organization?.ratings?.insurance_rating?.description:'-'
                                            }}</div>
                                    </td>
                                    <td>
                                        <div class="textContainer">
                                            {{
                                            (
                                            deposit_request?.invited.deposit_request?.user?.organization?.ratings?.insurance_rating?.description)?deposit_request?.invited.deposit_request?.user?.organization?.ratings?.insurance_rating?.description
                                            :'-'
                                            }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="textContainer">{{
                                            deposit_request?.invited.deposit_request?.closing_date_time }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="textContainer">{{
                                            (deposit_request?.invited.deposit_request?.special_instructions !='')?
                                            deposit_request?.invited.deposit_request?.special_instructions :'-' }}
                                        </div>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </b-tab>
                <b-tab title="Counter offer change log">
                    <table class="table" style="width: 100%;">
                        <thead class="customHeader">
                            <tr>
                                <th>#</th>
                                <th>Counter Offer Date</th>
                                <th>Deposit Amount</th>
                                <th>Interest Rate</th>
                                <th>Interest Rate Change</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(value,key) in deposit_request.counter_offers" :key="index">
                                <td>{{key+1}}</td>
                                <td>
                                    <div class="textContainer">
                                        {{formatDate(value.created_at)}}</div>
                                </td>
                                <td>
                                    <div class="textContainer">CAD {{value.maximum}}</div>
                                </td>
                                <td>
                                    <div class="textContainer"> {{value.offered_interest_rate.toFixed(2)}} %</div>
                                </td>
                                <td>
                                    <div class="textContainer"> {{(value.offered_interest_rate -
                                        deposit_request?.interest_rate_offer).toFixed(2) }}
                                        %</div>
                                </td>
                                <td>
                                    <CustomInvitedStatusBadge :text="value.status" />
                                </td>
                            </tr>
                        </tbody>


                    </table>

                    <!-- <div v-if="deposit_request.counter_offers.length>0"
                        style="display:flex; justify-content:flex-start; flex-direction:column; gap:5px;">
                        <div v-for="(value,key) in deposit_request.counter_offers"
                            style="width: 100%; height: 42px; padding: 10px; background: #EFF2FE; justify-content: flex-start; align-items: center; gap: 34px; display: inline-flex">
                            <div style="justify-content: center; align-items: center; gap: 10px; display: flex">
                                <div
                                    style="color: black; font-size: 14px; font-family: Montserrat; font-weight: 700; word-wrap: break-word">
                                    Counter offer </div>
                                <div
                                    style="color: #252525; font-size: 15px; font-family: Montserrat; font-weight: 400; word-wrap: break-word">
                                    {{formatDate(value.created_at)}}</div>
                            </div>
                            <div style="justify-content: center; align-items: center; gap: 10px; display: flex">
                                <div
                                    style="color: black; font-size: 14px; font-family: Montserrat; font-weight: 700; word-wrap: break-word">
                                    Deposit amount</div>
                                <div
                                    style="color: black; font-size: 15px; font-family: Montserrat; font-weight: 400; word-wrap: break-word">
                                    CAD {{value.maximum}}</div>
                            </div>
                            <div style="justify-content: center; align-items: center; gap: 10px; display: flex">
                                <div
                                    style="color: black; font-size: 14px; font-family: Montserrat; font-weight: 700; word-wrap: break-word">
                                    Interest rate</div>
                                <div
                                    style="color: black; font-size: 15px; font-family: Montserrat; font-weight: 400; word-wrap: break-word">
                                    {{value.offered_interest_rate.toFixed(2)}} %</div>
                            </div>
                            <div style="justify-content: center; align-items: center; gap: 10px; display: flex">
                                <div
                                    style="color: black; font-size: 14px; font-family: Montserrat; font-weight: 700; word-wrap: break-word">
                                    Interest rate change</div>
                                <div
                                    style="color: black; font-size: 15px; font-family: Montserrat; font-weight: 400; word-wrap: break-word">

                                    {{(value.offered_interest_rate - deposit_request?.interest_rate_offer).toFixed(2) }}
                                    %
                                </div>
                            </div>
                            <CustomInvitedStatusBadge :text="value.status" />
                        </div>
                    </div> -->

                    <!-- <div
                        style="width: 100%; height: 42px; padding: 10px; background: #EFF2FE; justify-content: flex-start; align-items: center; gap: 34px; display: inline-flex">
                        <div style="justify-content: center; align-items: center; gap: 10px; display: flex">
                            <div
                                style="color: black; font-size: 14px; font-family: Montserrat; font-weight: 700; word-wrap: break-word">
                                Counter offer 2</div>
                            <div
                                style="color: #252525; font-size: 15px; font-family: Montserrat; font-weight: 400; word-wrap: break-word">
                                Dec 4, 2024</div>
                        </div>
                        <div style="justify-content: center; align-items: center; gap: 10px; display: flex">
                            <div
                                style="color: black; font-size: 14px; font-family: Montserrat; font-weight: 700; word-wrap: break-word">
                                Deposit amount</div>
                            <div
                                style="color: black; font-size: 15px; font-family: Montserrat; font-weight: 400; word-wrap: break-word">
                                CAD 200,000</div>
                        </div>
                        <div style="justify-content: center; align-items: center; gap: 10px; display: flex">
                            <div
                                style="color: black; font-size: 14px; font-family: Montserrat; font-weight: 700; word-wrap: break-word">
                                Interest rate</div>
                            <div
                                style="color: black; font-size: 15px; font-family: Montserrat; font-weight: 400; word-wrap: break-word">
                                3.75</div>
                        </div>
                        <div style="justify-content: center; align-items: center; gap: 10px; display: flex">
                            <div
                                style="color: black; font-size: 14px; font-family: Montserrat; font-weight: 700; word-wrap: break-word">
                                Interest rate change</div>
                            <div
                                style="color: black; font-size: 15px; font-family: Montserrat; font-weight: 400; word-wrap: break-word">
                                0.12%</div>
                        </div>
                        <div style="justify-content: center; align-items: center; gap: 10px; display: flex">
                            <div
                                style="color: black; font-size: 14px; font-family: Montserrat; font-weight: 700; word-wrap: break-word">
                                Status</div>
                            <div
                                style="height: 21px; padding-left: 12px; padding-right: 12px; padding-top: 2px; padding-bottom: 2px; background: #44E0AA; justify-content: center; align-items: center; gap: 10px; display: flex">
                                <div
                                    style="color: white; font-size: 14px; font-family: Montserrat; font-weight: 600; text-transform: capitalize; word-wrap: break-word">
                                    Pending</div>
                            </div>
                        </div>
                    </div> -->
                </b-tab>
            </b-tabs>
        </div>



    </div>
    </div>
    </div>
</template>
<style scoped>
    .textContainer {
        width: 100%;
        height: 100%;
        color: black;
        font-size: 15px;
        font-family: Montserrat;
        font-weight: 400;
        word-wrap: break-word
    }

    .borderless,
    .borderless th,
    .borderless th,
    .borderless td {
        border: none !important;
    }

    .borderless thead {
        border-bottom: none !important;
    }

    .no-border {
        border-bottom: none;
    }

    .label {
        color: #5063F4;
        font-size: 16px !important;
        font-family: Montserrat;
        font-weight: 700 !important;
        text-transform: capitalize;
        line-height: 26px;
        word-wrap: break-word;
    }

    .error-message {
        color: red;
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

    @media screen and (max-width:1200px) {
        thead.customHeader tr th {
            font-size: .75em;
        }
    }

    .table tbody tr td {
        padding: none !important;
    }
</style>


<script>
    import CounterOfferBT from "../sharedComponents/CounterOfferR"
    import AboutFI from "../sharedComponents/AboutFI"
    //  import CustomInput from "../sharedComponents/CustomCurrencyValue"
    import CustomInput from "../sharedComponents/CustomInPutA"
    import CustomInvitedStatusBadge from "../sharedComponents/CustomInvitedStatusBadge";
    import { userCan } from "../../../utils/GlobalUtils";
    import Accordion from "../../shared/Accordion";
    import { addCommasToANumber, formatNumberAbbreviated, formatCreatedAtToRequiredTimestamp, sentenceCase, addCommasAndDecToANumber, formatTimestamp, sanitizeAmount, calculateIterestOnProduct } from "../../../utils/commonUtils";
    import Avatar from 'vue-avatar';
    export default {
        mounted() {

        },
        computed: {
            currencyOptions() {
                return ['CAD']; // Sample currency options
            }
        },
        components: {
            CustomInvitedStatusBadge,
            avatar: Avatar,
            CustomInput,
            CounterOfferBT,
            Accordion,
            AboutFI
        },
        created() {
        },
        props: ['deposit_request'],
        data() {
            console.log(this.deposit_request, "vgfrdthis.deposit_requestthis.deposit_request");
            let latestCounter = null;
            this.deposit_request.counter_offers.map((val, key) => {
                if (key === 0) {
                    latestCounter = val;
                }
            });
            console.log(latestCounter, "latestCounterlatestCounterlatestCounter");

            return {
                latestOffer: latestCounter,
                hasCounters: latestCounter !== null ? true : false,
                awardedAmountError: '',
                selectedCurrency: 'CAD',
                details: null,
                existing: null,
                action: 'view',
                is_modal: false,
                awardedAmount: 0,
                interestEarned: 0,
                offer: {
                    offer_id: this.deposit_request?.offer_id,
                    depositor_request_id: this.deposit_request?.depositor_request_id,
                    amount: this.deposit_request?.invited.deposit_request?.amount,
                    rate_held: this.deposit_request.rate_held_until,
                    org_name: this.deposit_request?.bank_name,
                    currency: this.deposit_request?.invited.deposit_request?.currency,
                    min: this.deposit_request?.minimum_amount,
                    max: this.deposit_request?.maximum_amount,
                    rate: this.deposit_request?.interest_rate_offer,
                    awarded: 0,
                    row_rate: 0,
                    counter_offers: this.deposit_request?.counter_offers,
                }
            }

        },
        watch: {

        },
        methods: {
            formatDate(timeStamp, includetime) {
                let correctlyformattedtimestamp = formatCreatedAtToRequiredTimestamp(timeStamp);
                return formatTimestamp(correctlyformattedtimestamp, includetime);
            },
            setAwardedAmount(amount) {
                if (sanitizeAmount(amount) < parseFloat(this.offer.min)) {
                    this.awardedAmountError = `Cannnot be less than the minimum amount of ${this.addCommans(this.offer.min)} `;
                } else if (sanitizeAmount(amount) > parseFloat(this.offer.max)) {
                    this.awardedAmountError = `Cannnot be more than the maximum amount of ${this.addCommans(this.offer.max)} `;
                } else {
                    this.awardedAmountError = '';
                    this.interestEarned = calculateIterestOnProduct(
                        sanitizeAmount(amount),
                        this.deposit_request.invited.deposit_request.term_length,
                        this.deposit_request.invited.deposit_request.term_length_type,
                        this.deposit_request.invited.deposit_request.product_name,
                        this.offer.rate
                    );
                }



            },
            addCommans(newvalue) {
                return addCommasAndDecToANumber(newvalue);
            },
            capitalize(thestring) {
                if (thestring != null || thestring != null) {
                    return thestring.charAt(0).toUpperCase() + thestring.slice(1).toLowerCase();
                }
            }
            ,
            back() {

            },
            userCan(user, permission) {
                return userCan(user, permission);
            },
        }
    }
</script>