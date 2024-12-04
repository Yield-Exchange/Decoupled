<template>

    <Modal :show="show" @isVisible="$emit('show', $event)" modalsize="xl" @closeModal="$emit('closemodal', false)"
        v-if="organization">
        <div style="padding: 35px;">
            <div style="justify-content: flex-start; align-items: center; gap: 11px; display: inline-flex; width: 100%">
                <div
                    style="width: 30%; height: 100%; padding-left: 40px; padding-right: 40px; padding-top: 20px; padding-bottom: 20px; background: white; box-shadow: 0px 4px 4px #D9D9D9; flex-direction: column; justify-content: center; align-items: center; display: inline-flex">
                    <avatar v-if="!organization?.logo" :size="80" :color="'white'" :backgroundColor="'#4975E3'"
                        :initials="organization?.name[0]"></avatar>
                    <img v-else style="width: 157px; height: 157px" :src="'/image/' + organization?.logo" />
                    <div
                        style="width: 100%; display:flex; justify-content:center;  align-content:center; height: 32px; color: #0F3D6F; font-size: 14px; font-family: Montserrat; font-weight: 900; word-wrap: break-word">
                        {{ organization?.name }}
                    </div>

                </div>


                <div
                    style="flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 30px; display: inline-flex; width: 75%">
                    <div style="width:100%"><!-- list summary -->

                        <div
                            style="color: #5063F4; font-size: 28px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 32px; word-wrap: break-word">
                            About {{
            organization?.name }}</div>

                        <div class="row w-100 mt-2">
                            <!-- organization type -->
                            <div class="col-md-6 mt-2">
                                <div
                                    style="justify-content: flex-start; align-items: center; gap: 10px; display: flex;flex: 1;">
                                    <div style="width: 30px; height: 30px; position: relative">
                                        <img src="/assets/dashboard/icons/Setting - 6.svg" />
                                    </div>
                                    <div
                                        style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                                        type: </div>
                                    <div style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">
                                        {{ getOrganizationType() }}
                                    </div>
                                </div>
                            </div>
                            <!-- Total Assets -->
                            <div class="col-md-6 mt-2">

                                <div class="d-flex gap-2" style="">
                                    <div style="width: 25px; height: 25px; position: relative">
                                        <img src="/assets/dashboard/icons/totaasset.svg" />
                                    </div>
                                    <div
                                        style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; ">
                                        Total Assets Size : <span
                                            style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">
                                            {{ organization?.demographic_data?.value_of_assets ?
            'CAD ' + shortNumberFormat(organization?.demographic_data?.value_of_assets)
            :
            '-'
                                            }}</span>
                                    </div>

                                </div>
                            </div>
                            <!-- Credit rating -->
                            <div class="col-md-6 mt-2">

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
                            <!-- No Of Branches -->
                            <div class="col-md-6 mt-2 d-none">
                                <div
                                    style="justify-content: flex-start; align-items: center; gap: 10px; display: flex;flex: 1;">
                                    <div style="width: 30px; height: 30px; position: relative">
                                        <img src="/assets/dashboard/icons/nob.svg" />
                                    </div>
                                    <div
                                        style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                                        No of Branches: </div>
                                    <div style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">
                                        {{
                organization?.demographic_data?.no_of_branches }}
                                    </div>
                                </div>
                            </div>

                            <!-- Year of establishement -->
                            <div class="col-md-6 mt-2 d-none">

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
                            <!-- Country -->
                            <div class="col-md-6 mt-2">
                                <div
                                    style="justify-content: flex-start; align-items: center; gap: 10px; display: flex;flex: 1;">
                                    <div style="width: 30px; height: 30px; position: relative">
                                        <img src="/assets/dashboard/icons/loc.svg" />
                                    </div>

                                    <div
                                        style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                                        Country </div>
                                    <div style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">
                                        Canada
                                    </div>
                                    <!-- <div
                                        style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                                        {{
            organization?.demographic_data.province ?
                organization?.demographic_data.province : '-' }}: </div>
                                    <div style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">
                                        {{
            organization?.demographic_data.city ? organization?.demographic_data.city :
                '-' }}
                                    </div> -->
                                </div>
                            </div>
                            <!-- Webiste -->

                            <div class="col-md-6 mt-2">

                                <div class="d-flex gap-2" style="">
                                    <div style="width: 25px; height: 25px; position: relative">
                                        <img src="/assets/dashboard/icons/Setting - 5.svg" />
                                    </div>
                                    <div style="color: black; font-size: 16px; font-weight: 700;">
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
            organization?.demographic_data?.description != null ? organization?.demographic_data?.description : '-'
                }}
                <br>
                <br>
                <span class="mt-4"
                    v-if="(organization?.demographic_data?.org_bio != 'null' && organization?.demographic_data?.org_bio !=
                null) ||   (organization?.demographic_data?.description!=null &&   organization?.demographic_data?.description!='null')">Last
                    Modified: {{
                    organization?.demographic_data?.updated_at?
                    formatTimestamp(organization?.demographic_data?.updated_at,false):'-' }} </span>
            </div>
        </div>

    </Modal>
</template>


<script>
import Modal from "../../shared/Modal";
import Avatar from 'vue-avatar';
import { addCommasToANumber, formatNumberAbbreviated, sentenceCase, formatCreatedAtToRequiredTimestamp, addCommasAndDecToANumber, formatTimestamp, sanitizeAmount, calculateIterestOnProduct } from "../../../utils/commonUtils";

export default {
    props: ['show', 'organization'],
    mounted() {
        // console.log(this.organization)
    },
    components: {
        Avatar,
        Modal,
    },
    data() {
        return {
            organization_type: '-',
            org_type: null,
        }
    },
    computed: {
        getOrganizationTypes() {
            return this.$store.getters.getOrganizationTypes
        },
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
        getOrganizationType(value) {
            if (this.organization.type === 'DEPOSITOR') {
                this.org_type = 'depositor'
                let orgtype = '-'
                if (this.organization?.registration_type != null) {
                    return this.getOrganizationTypes.find(element => element.id === this.organization.registration_type)?.name || null;
                } else {
                    return orgtype
                }
            } else {
                this.org_type = 'bank'

                return this.organization?.fi_type ? this.organization?.fi_type : '-'
            }
        }
    }
}

</script>