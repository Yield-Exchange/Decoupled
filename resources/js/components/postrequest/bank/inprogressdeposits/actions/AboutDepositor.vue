<template>
    <div v-if="organization" style="justify-content: flex-start; align-items: flex-start; gap: 11px; display: inline-flex; width: 100%">

        <div
            style="justify-content: flex-start; align-items: flex-start; gap: 30px; display: inline-flex; width: 100%">
            <div class="about_avator">
                <Avatar v-if="!organization?.logo" :size="100" :color="'white'" :backgroundColor="'#4975E3'"
                    :initials="organization?.name[0]"></Avatar>
                <Avatar v-else :size="100" :color="'white'" :backgroundColor="'#4975E3'"
                    :src="'/image/' + organization?.logo"></Avatar>
                    <p style="color:  #5063F4;text-align: center;font-family: Montserrat;font-size: 24px;font-style: normal;font-weight: 700;line-height: 26px; text-transform: capitalize;">{{ organization?.name }}</p>
            </div>
            <div class="row w-100 " style="gap: 20px 0px !important;">
                <div style="width: 100%; color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">
                {{ organization?.demographic_data?.org_bio != null ? organization?.demographic_data?.org_bio
                    :
                    organization?.demographic_data?.description }}
            </div>
                <div class="col-md-4">
                    <div style="justify-content: flex-start; align-items: center; gap: 10px; display: flex;flex: 1;">
                        <div style="width: 30px; height: 30px; position: relative">
                            <img src="/assets/dashboard/icons/Setting - 6.svg" />
                        </div>
                        <div
                            style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                            type: </div>
                        <div style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">{{
                    organization?.fi_type ? organization?.fi_type : '-' }}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div style="justify-content: flex-start; align-items: center; gap: 10px; display: flex;flex: 1;">
                        <div style="width: 25px; height: 25px; position: relative">
                            <img style="height: 25px" src="/assets/dashboard/icons/Settingyth4.svg" />
                        </div>
                        <div
                            style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                            Year of establishment:</div>
                        <div style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">{{
                    organization?.demographic_data?.year_of_establishment }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div style="justify-content: flex-start; align-items: center; gap: 10px; display: flex;flex: 1;">
                        <div style="width: 25px; height: 25px; position: relative">
                            <img style="height: 25px" src="/assets/dashboard/icons/Settingyth4.svg" />
                        </div>
                        <div
                            style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                            Website:</div>
                        <div style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">
                            <a style="color:  #5063F4;" :href="organization?.demographic_data?.website"> {{
                    organization?.demographic_data?.website }}</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div style="justify-content: flex-start; align-items: center; gap: 10px; display: flex;flex: 1;">
                        <div style="width: 25px; height: 25px; position: relative">
                            <img style="height: 25px" src="/assets/dashboard/icons/Settingyth4.svg" />
                        </div>
                        <div
                            style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                            {{
                    organization?.demographic_data?.province ?  organization?.demographic_data?.province +", " + organization?.demographic_data?.city  : "-" 
                     }}

                        </div>
                        <div style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div style="justify-content: flex-start; align-items: center; gap: 10px; display: flex;flex: 1;">
                        <div style="width: 25px; height: 25px; position: relative">
                            <img style="height: 25px" src="/assets/dashboard/icons/Settingyth4.svg" />
                        </div>
                        <div
                            style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                            Postal Code:</div>
                        <div style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">
                            {{
                    organization?.demographic_data?.postal_code }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>
<script>
import Avatar from 'vue-avatar';
export default {
    components: {Avatar},
    mounted() {
        if (this.organization_data) {
            this.organization = JSON.parse(this.organization_data)
        }
    },
    props: ['datum', 'organization_data'],
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
        showWarning() {
            this.errorModalTitle = "Financial Statement.";
            this.errorModalMessage = "Financial Statement are not available.";
            this.showErrorModal = true;
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
    },
    data() {
        return {
            errorModalTitle: "",
            errorModalMessage: "",
            showErrorModal: false,
            errorModalSize: "md",
            organi: null,
            organization: null
        }

    },
    computed: {
        creditrating() {
            let rate = this.organization?.deposit_credit_rating?.credit_rating?.description
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
    },

}
</script>
