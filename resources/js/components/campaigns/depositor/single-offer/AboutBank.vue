<template>
    <div class="d-flex flex-column w-100" v-if="datum">
        <div style="justify-content: flex-start; align-items: flex-start; gap: 11px; display: inline-flex; width: 100%">
            <div
                style="height: 214px; border-radius: 10px; border: 0.25px #D9D9D9 solid; flex-direction: column; justify-content: flex-start; align-items: flex-start; display: inline-flex">
                <div
                    style="width: 185px; height: 144px; padding: 21.60px; background: white; border-top-left-radius: 10.80px; border-top-right-radius: 10.80px; flex-direction: column; justify-content: center; align-items: center; gap: 5px; display: flex">
                    <div
                        style="flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 2px; display: flex;min-height: 50px">
                        <avatar v-if="!datum?.campaign?.organization?.logo" :size="80" :color="'white'"
                            :backgroundColor="'#4975E3'" :initials="datum?.campaign?.organization?.name[0]"></avatar>
                        <avatar v-else :size="80" :color="'white'" :backgroundColor="'#4975E3'"
                            :src="'/image/' + datum?.campaign?.organization?.logo"></avatar>
                    </div>
                    <div
                        style="height: 15px; flex-direction: column; justify-content: space-between; align-items: flex-start; display: flex">
                        <div style="color: #5063F4; font-size: 12px; font-weight: 500; word-wrap: break-word">{{
        datum?.campaign?.organization?.name }}</div>
                    </div>
                </div>
                <div
                    style="align-self: stretch; height: 72px; padding: 10px; background: #3BCBB2; border: 0.45px #3BCBB2 solid; flex-direction: column; justify-content: center; align-items: flex-start; display: flex">
                    <div style="justify-content: flex-start; align-items: center; display: inline-flex"
                        id="view-profile-bank-button">
                        <div style="width: 28px; height: 28px; position: relative">
                            <img src="/assets/dashboard/icons/Promo56.svg" />
                        </div>
                        <div
                            style="width: 137px; color: white; font-size: 15px; font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                            View Bank Profile</div>
                    </div>
                    <Tooltip target="view-profile-bank-button" message="Coming soon" />
                </div>
            </div>
            <div
                style="flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 30px; display: inline-flex; width: 75%">
                <div class="row w-100">
                    <div class="col-md-4">
                        <div
                            style="justify-content: flex-start; align-items: center; gap: 10px; display: flex;flex: 1;">
                            <div style="width: 30px; height: 30px; position: relative">
                                <img src="/assets/dashboard/icons/Setting - 6.svg" />
                            </div>
                            <div
                                style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                                type: </div>
                            <div style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">{{
        datum?.campaign?.organization?.fi_type ? datum?.campaign?.organization?.fi_type : '-' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">

                        <div class="d-flex gap-2" style="">
                            <div style="width: 25px; height: 25px; position: relative">
                                <img src="/assets/dashboard/icons/Setting - 5.svg" />
                            </div>
                            <div
                                style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                                Total assets size: </div>
                            <div style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">CAD {{
        addCommas(datum?.campaign.organization?.demographic_data?.value_of_assets) }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div
                            style="justify-content: flex-start; align-items: center; gap: 10px; display: flex;flex: 1;">
                            <div style="width: 25px; height: 25px; position: relative">
                                <img style="height: 25px" src="/assets/dashboard/icons/Setting__3.svg" />
                            </div>
                            <div style="justify-content: flex-start; align-items: center; gap: 10px; display: flex">
                                <div
                                    style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                                    Credit rating: </div>
                                <div style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">{{
        datum?.campaign?.organization?.deposit_credit_rating ?
            datum?.campaign?.organization?.deposit_credit_rating?.credit_rating?.description :
            '-'
    }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row w-100 ">
                    <div class="col-md-4">
                        <div style="justify-content: flex-start; align-items: center; gap: 9px; display: flex;flex: 1;">
                            <div style="width: 25px; height: 25px; position: relative">
                                <img style="height: 25px" src="/assets/dashboard/icons/Setting_4.svg" />
                            </div>
                            <div
                                style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                                Insurance:</div>
                            <div style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">{{
            datum?.campaign?.organization?.deposit_credit_rating ?
                datum?.campaign?.organization?.deposit_credit_rating?.insurance_rating?.description :
                '-' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div
                            style="height: 25px; justify-content: flex-start; align-items: center; gap: 10px; display: flex;flex: 1;">
                            <div style="width: 25px; height: 25px; position: relative">
                                <img style="height: 25px" src="/assets/dashboard/icons/Settingdeer3.svg" />
                            </div>
                            <div style="justify-content: flex-start; align-items: center; gap: 10px; display: flex">
                                <div><span
                                        style="color: black; font-size: 14px;  font-weight: 700; text-transform: capitalize; line-height: 19.11px; word-wrap: break-word">No
                                        of Branches</span><span
                                        style="color: black; font-size: 14px; font-weight: 600; text-transform: capitalize; line-height: 19.11px; word-wrap: break-word">:
                                    </span></div>
                                <div style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">{{
        datum?.campaign.organization?.demographic_data?.no_of_branches }}
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-4">
                        <div
                            style="justify-content: flex-start; align-items: center; gap: 10px; display: flex;flex: 1;">
                            <div style="width: 25px; height: 25px; position: relative">
                                <img style="height: 25px" src="/assets/dashboard/icons/Settingyth4.svg" />
                            </div>
                            <div
                                style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                                Year of establishment:</div>
                            <div style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">{{
        datum?.campaign.organization?.demographic_data?.year_of_establishment }}</div>
                        </div>
                    </div>
                </div>
                <div class="row w-100">
                    <div class="col-md-6">
                        <div
                            style="justify-content: flex-start; align-items: center; gap: 10px; display: flex;flex: 1;">
                            <div style="width: 25px; height: 25px; position: relative">
                                <img style="height: 25px" src="/assets/dashboard/icons/Settingpo5.svg" />
                            </div>
                            <div
                                style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                                Financial statement:</div> <br>

                            <div v-if="financialstatement === ''" @click="showWarning()"
                                style="color: #5063F4; font-size: 14px; font-weight: 500;  word-wrap: break-word; cursor:pointer;">
                                Financial Statements are not available
                            </div>
                            <div v-else
                                style="color: #5063F4; font-size: 14px; font-weight: 500;  word-wrap: break-word">

                                <a target="_blank" style="text-decoration: underline" :href="financialstatement">Click
                                    here
                                    to View </a>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row w-100">
                    <div class="col-md-12">
                        <div class="mt-1 ml-2"
                            style="width: 100%; margin-top:25px; color: #252525;  font-size: 16px; font-family: Montserrat; font-weight: 500; word-wrap: break-word">
                            {{ (organization?.demographic_data?.org_bio != 'null' &&
        organization?.demographic_data?.org_bio !=
        null) ? organization?.demographic_data?.org_bio :
        organization?.demographic_data?.description != null ?
            organization?.demographic_data?.description : '-'
                            }}
                            <br>
                            <br>
                            <span class="mt-4"
                                v-if="(organization?.demographic_data?.org_bio != 'null' && organization?.demographic_data?.org_bio !=
        null) || (organization?.demographic_data?.description != null && organization?.demographic_data?.description != 'null')">Last
                                Modified: {{
                                formatTimestamp(organization?.demographic_data?.updated_at, false) }} </span>
                        </div>
                    </div>
                </div>
            </div>
            <GeneralNoInteractionError :size="errorModalSize" @closedModal="closeErrorModal()" :title="errorModalTitle"
                :show="showErrorModal" :message="errorModalMessage" />
        </div>

    </div>
</template>
<script>
import Tooltip from "../../../shared/Tooltip";
import GeneralNoInteractionError from "../../../shared/messageboxes/GeneralNoInteractionError.vue";
import Avatar from 'vue-avatar';
import { formatTimestamp, formatCreatedAtToRequiredTimestamp } from "../../../../utils/commonUtils";
export default {
    components: { Tooltip, Avatar, GeneralNoInteractionError },
    props: ['datum'],
    methods: {
        closeErrorModal() {
            this.showErrorModal = false;
        },
        addCommas(newvalue) {
            if (newvalue != undefined) {
                return newvalue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            } else {
                return "";
            }

        },
        formatTimestamp(timeStamp, includetime) {
            let correctlyformattedtimestamp = formatCreatedAtToRequiredTimestamp(timeStamp);
            return formatTimestamp(correctlyformattedtimestamp, includetime);
        },
        showWarning() {
            this.errorModalTitle = "Financial Statement.";
            this.errorModalMessage = "Financial Statement are not available.";
            this.showErrorModal = true;
        }
    },
    data() {
        // console.log(this.datum);
        let transferdetails = '';
        let financialstatement = '';
        if (this.datum?.campaign?.organization?.document != null) {
            this.datum?.campaign?.organization?.document.forEach((value, key) => {

                if (value.document_type == "Transfer Details") {
                    transferdetails = value.file_name;
                } else if (value.document_type == "Financial Statement") {
                    financialstatement = (value.file_name != null) ? value.file_name : '';
                }
            })

        }
        let organization = null
        if (this.datum?.campaign?.organization) {
            organization = this.datum?.campaign?.organization
        }


        return {
            organization: organization,
            errorModalTitle: "",
            errorModalMessage: "",
            showErrorModal: false,
            errorModalSize: "md",
            financialstatement: (financialstatement === '') ? '' : '/' + financialstatement,
            organi: null
        }


    }
}
</script>