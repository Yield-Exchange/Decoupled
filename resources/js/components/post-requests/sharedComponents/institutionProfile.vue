<template>
    <div>

        <div @click="show = true"
            style="width: 100%; height: 100%; color: #5063F4; cursor:pointer; font-size: 15px; font-family: Montserrat; font-weight: 400; text-decoration: underline; text-transform: capitalize; word-wrap: break-word">
            {{ organization?.name }} </div>
        <Modal :show="show" @isVisible="$emit('show', $event)" modalsize="xl" @closeModal="show = false">
            <div style="padding: 35px;">
                <div
                    style="justify-content: flex-start; align-items: center; gap: 11px; display: inline-flex; width: 100%">
                    <div
                        style="width: 30%; height: 100%; padding-left: 40px; padding-right: 40px; padding-top: 20px; padding-bottom: 20px; background: white; box-shadow: 0px 4px 4px #D9D9D9; flex-direction: column; justify-content: center; align-items: center; display: inline-flex">
                        <avatar v-if="organization?.logo" :size="80" :color="'white'" :backgroundColor="'#4975E3'"
                            :initials="organization?.name[0]"></avatar>
                        <img v-else style="width: 157px; height: 157px" :src="'/image/' + organization?.logo" />
                        <div
                            style="width: 100%; display:flex; justify-content:center;  align-content:center; height: 32px; color: #0F3D6F; font-size: 14px; font-family: Montserrat; font-weight: 900; word-wrap: break-word">
                            {{ organization?.name }}
                        </div>
                        <!-- <div>
                            <template v-for="i in 5">
                                <img v-if="i <= creditrating" style="width: 18.148px;height: 17.337px;"
                                    src="/assets/dashboard/icons/rating.svg" alt="">
                                <img v-else style="width: 18.148px;height: 17.337px;"
                                    src="/assets/dashboard/icons/no-color-rating.svg" alt="">
                            </template>
</div> -->
                    </div>


                    <div
                        style="flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 30px; display: inline-flex; width: 75%">
                        <div style="width:100%"><!-- list summary -->

                            <div
                                style="color: #5063F4; font-size: 28px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 32px; word-wrap: break-word">
                                About {{
            organization?.name }}</div>

                            <div class="row w-100 mt-2">
                                <div class="col-md-6">
                                    <div
                                        style="justify-content: flex-start; align-items: center; gap: 10px; display: flex;flex: 1;">
                                        <div style="width: 30px; height: 30px; position: relative">
                                            <img src="/assets/dashboard/icons/Setting - 6.svg" />
                                        </div>
                                        <div
                                            style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                                            type: </div>
                                        <div
                                            style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">
                                            {{
            organization?.fi_type ? organization?.fi_type : '-' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">

                                    <div class="d-flex gap-2" style="">
                                        <div style="width: 25px; height: 25px; position: relative">
                                            <img src="/assets/dashboard/icons/totaasset.svg" />
                                        </div>
                                        <div
                                            style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; ">
                                            Total Assets Size : <span
                                                style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">CAD
                                                {{
            shortNumberFormat(organization?.demographic_data?.value_of_assets)
        }}</span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="row w-100 mt-2">
                                <div class="col-md-6">
                                    <div
                                        style="justify-content: flex-start; align-items: center; gap: 10px; display: flex;flex: 1;">
                                        <div style="width: 30px; height: 30px; position: relative">
                                            <img src="/assets/dashboard/icons/nob.svg" />
                                        </div>
                                        <div
                                            style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                                            No of Branches: </div>
                                        <div
                                            style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">
                                            {{
                organization?.demographic_data?.no_of_branches }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">

                                    <div class="d-flex gap-2" style="">
                                        <div style="width: 25px; height: 25px; position: relative">
                                            <img src="/assets/dashboard/icons/creditr.svg" />
                                        </div>
                                        <div
                                            style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; ">
                                            Credit rating: <span
                                                style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">
                                                {{
            organization?.deposit_credit_rating ?
                organization?.deposit_credit_rating?.credit_rating?.description : '-'
        }}</span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="row w-100 mt-2">
                                <div class="col-md-6">
                                    <div
                                        style="justify-content: flex-start; align-items: center; gap: 10px; display: flex;flex: 1;">
                                        <div style="width: 30px; height: 30px; position: relative">
                                            <img src="/assets/dashboard/icons/depoin.svg" />
                                        </div>
                                        <div
                                            style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                                            Deposit insurance: </div>
                                        <div
                                            style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">
                                            {{
                organization?.deposit_credit_rating ?
                    organization?.deposit_credit_rating?.insurance_rating?.description : '-' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">

                                    <div class="d-flex gap-2" style="">
                                        <div style="width: 25px; height: 25px; position: relative">
                                            <img src="/assets/dashboard/icons/year.svg" />
                                        </div>
                                        <div
                                            style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; ">
                                            Year of establishment: <span
                                                style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">
                                                {{
            organization?.demographic_data?.year_of_establishment }}</span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="row w-100 mt-2">
                                <div class="col-md-6">
                                    <div
                                        style="justify-content: flex-start; align-items: center; gap: 10px; display: flex;flex: 1;">
                                        <div style="width: 30px; height: 30px; position: relative">
                                            <img src="/assets/dashboard/icons/loc.svg" />
                                        </div>
                                        <div
                                            style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                                            {{
            organization?.demographic_data.province ?
                organization?.demographic_data.province : '-' }}: </div>
                                        <div
                                            style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">
                                            {{
            organization?.demographic_data.city ? organization?.demographic_data.city :
                '-' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">

                                    <div class="d-flex gap-2" style="">
                                        <div style="width: 25px; height: 25px; position: relative">
                                            <img src="/assets/dashboard/icons/Setting - 5.svg" />
                                        </div>
                                        <div
                                            style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; ">
                                            Website: <span
                                                style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">{{
            organization?.demographic_data?.website ?
                organization?.demographic_data?.website.toLowerCase() : '-' }}</span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- list summary -->
                        </div>



                    </div>

                </div>
                <div class="mt-10"
                    style="width: 100%; margin-top:25px; color: #252525;  font-size: 16px; font-family: Montserrat; font-weight: 500; word-wrap: break-word">
                    {{ (organization?.demographic_data?.org_bio != 'null' && organization?.demographic_data?.org_bio !=
            null) ? organization?.demographic_data?.org_bio :
                    organization?.demographic_data?.description != null ? organization?.demographic_data?.description :
                    '-'
                    }}
                    <br>
                    <br>
                    <span class="mt-4"
                        v-if="(organization?.demographic_data?.org_bio != 'null' && organization?.demographic_data?.org_bio !=
            null) || (organization?.demographic_data?.description != null && organization?.demographic_data?.description != 'null')">Last
                        Modified: {{
                        formatTimestamp(organization?.demographic_data?.updated_at,false) }} </span>
                </div>
            </div>

        </Modal>


    </div>

</template>

<style scoped></style>

<script>
import GeneralErrorNoInteraction from "../../shared/messageboxes/GeneralNoInteractionError.vue";
import Modal from "../../shared/Modal";
import CustomInput from "../../shared/CustomInput.vue";
import CustomDate from "./CustomDate.vue";
import CustomDateInput from './CustomDate.vue';
import FormLabelRequired from "../../shared/formLabels/FormLabelRequired.vue";
import CustomCurrencyValueInput from "./CustomCurrencyValue.vue";
import CustomInvitedStatusBadge from "../sharedComponents/CustomInvitedStatusBadge";
import { addCommasToANumber, formatNumberAbbreviated, sentenceCase, formatCreatedAtToRequiredTimestamp, addCommasAndDecToANumber, formatTimestamp, sanitizeAmount, calculateIterestOnProduct } from "../../../utils/commonUtils";

import Tooltip from "../../shared/Tooltip";
import GeneralNoInteractionError from "../../shared/messageboxes/GeneralNoInteractionError.vue";
import Avatar from 'vue-avatar';

export default {
    props: ["offer", "deposit_request", "from", "encoded_deposit_request_id", "shouldNotPerformNoAction", "offersselectsubmitting", "organization"],
    created() { },
    components: {
        Tooltip,
        Avatar,
        CustomInvitedStatusBadge,
        GeneralErrorNoInteraction,
        Modal,
        FormLabelRequired,
        CustomInput,
        selectedCurrency: 'CAD', // Default currency
        amount: null,
        CustomCurrencyValueInput,
        CustomDate
    },
    computed: {
        creditrating() {
            let rate = this.organization?.deposit_credit_rating?.credit_rating?.description;
            if (rate != null) {
                if (rate.toLowerCase() == "strong") {
                    return 4
                } else if (rate.toLowerCase() == "very strong") {
                    return 5
                } else if (rate.toLowerCase() == "adequate") {
                    return 3
                } else {
                    return 1
                }
            }
        }
    },
    data() {
        console.log(this.offer, "offer details on modal counter");
        return {
            show: false
        }
    },
    mounted() {


    },
    methods: {
        addCommas(number) {
            return addCommasAndDecToANumber(number);
        },
        shortNumberFormat(number) {
            return formatNumberAbbreviated(number);
        },
        formatTimestamp(timeStamp, includetime) {
            let correctlyformattedtimestamp = formatCreatedAtToRequiredTimestamp(timeStamp);
            return formatTimestamp(correctlyformattedtimestamp, includetime);
        },
    }
}

</script>