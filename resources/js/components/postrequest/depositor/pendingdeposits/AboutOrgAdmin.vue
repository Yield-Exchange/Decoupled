<template>
    <div style="justify-content: flex-start; align-items: flex-start; gap: 11px; display: inline-flex; width: 100%"
        v-if="depositor">
        <div
            style="border-radius: 10px; border: 0.25px #D9D9D9 solid; flex-direction: column; justify-content: flex-start; align-items: flex-start; display: inline-flex">
            <div class="d-flex flex-column justify-content-center align-items-center"
                style="width: 185px; padding: 21.60px; background: white; border-top-left-radius: 10.80px; border-top-right-radius: 10.80px;">
                <div
                    style="flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 2px; display: flex;min-height: 50px">
                    <avatar v-if="depositor?.profile_pic == null" :size="80" :color="'white'" backgroundColor="#4975E3"
                        :initials="depositor?.name[0]"></avatar>
                    <avatar v-else :size="80" :color="'white'" :backgroundColor="'#4975E3'"
                        :src="'/image/' + depositor?.profile_pic"></avatar>
                </div>

                <div
                    style="color: #5063F4;font-feature-settings: 'clig' off, 'liga' off;font-family: Montserrat;font-size: 16px;font-style: normal;text-align: center; font-weight: 700;line-height: 26px;text-transform: capitalize;">
                    {{ depositor?.name }}
                </div>
            </div>

        </div>
        <div
            style="flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 30px; display: inline-flex; width: 75%">
            <div class="row w-100 " style="gap: 20px 0px !important;">
                <div class="col-md-4">
                    <div style="justify-content: flex-start; align-items: center; gap: 10px; display: flex;flex: 1;">
                        <div style="width: 30px; height: 30px; position: relative">
                            <svg width="25" height="25" viewBox="0 0 25 25" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M1.441 8.438a9.39 9.39 0 0 1 6.997-6.997 16.7 16.7 0 0 1 7.624 0 9.39 9.39 0 0 1 6.997 6.997 16.7 16.7 0 0 1 0 7.624 9.39 9.39 0 0 1-6.997 6.997 16.7 16.7 0 0 1-7.624 0 9.39 9.39 0 0 1-6.997-6.997 16.7 16.7 0 0 1 0-7.624Z"
                                    fill="#FDFDFD" stroke="#5063F4" stroke-width="1.35" stroke-linecap="round" />
                                <g clip-path="url(#a)" fill="#5063F4">
                                    <path
                                        d="M15.125 17.73h-1.083a1.084 1.084 0 0 1 0-2.166h1.083V14.48h-1.083a2.166 2.166 0 1 0 0 4.334h1.083zm4.333-1.083a2.166 2.166 0 0 1-2.166 2.167h-1.084V17.73h1.084a1.084 1.084 0 0 0 0-2.166h-1.084V14.48h1.084a2.166 2.166 0 0 1 2.166 2.167" />
                                    <path
                                        d="M14.583 16.106h2.167v1.083h-2.167zm-3.791.541q0-.272.048-.541H8.625v-.759c0-1.083 2.167-1.679 3.25-1.679q.368.005.731.068a3.2 3.2 0 0 1 1.436-.339h2.708V8.522a1.087 1.087 0 0 0-1.083-1.083h-2.264a1.619 1.619 0 0 0-3.056 0H8.084A1.087 1.087 0 0 0 7 8.522v7.584a1.087 1.087 0 0 0 1.083 1.083h2.757a3 3 0 0 1-.048-.542m1.083-9.208a.541.541 0 1 1 0 1.083.541.541 0 0 1 0-1.083m0 2.167a1.625 1.625 0 1 1 0 3.25 1.625 1.625 0 0 1 0-3.25" />
                                </g>
                                <defs>
                                    <clipPath id="a">
                                        <path fill="#fff" d="M7 6.355h13v13H7z" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </div>
                        <div
                            style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize;white-space: nowrap">
                            Job Title: </div>
                        <div style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">{{
            depositor?.demographic_data?.job_title ? depositor?.demographic_data?.job_title : '-' }}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div style="justify-content: flex-start; align-items: center; gap: 10px; display: flex;flex: 1;">
                        <div style="width: 25px; height: 25px; position: relative">
                            <svg width="25" height="25" viewBox="0 0 25 25" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M1.441 8.438a9.39 9.39 0 0 1 6.997-6.997 16.7 16.7 0 0 1 7.624 0 9.39 9.39 0 0 1 6.997 6.997 16.7 16.7 0 0 1 0 7.624 9.39 9.39 0 0 1-6.997 6.997 16.7 16.7 0 0 1-7.624 0 9.39 9.39 0 0 1-6.997-6.997 16.7 16.7 0 0 1 0-7.624Z"
                                    fill="#FDFDFD" stroke="#5063F4" stroke-width="1.35" stroke-linecap="round" />
                                <g clip-path="url(#a)" fill="#5063F4">
                                    <path d="M17.585 7.168a.7.7 0 0 0-.148 0H7.327a.7.7 0 0 0-.192.029l5.218 5.196z" />
                                    <path
                                        d="m18.12 7.668-5.258 5.236a.72.72 0 0 1-1.018 0L6.633 7.708a.7.7 0 0 0-.026.18v7.223a.72.72 0 0 0 .723.722h10.11a.72.72 0 0 0 .723-.723V7.889a.7.7 0 0 0-.043-.22M7.824 15.111h-.502v-.517l2.626-2.603.509.509zm9.61 0h-.506L14.295 12.5l.51-.51 2.625 2.604z" />
                                </g>
                                <defs>
                                    <clipPath id="a">
                                        <path fill="#fff" d="M5.91 5h13v13h-13z" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </div>
                        <div
                            style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize;white-space: nowrap">
                            Email:</div>
                        <div style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">
                            <a v-if="depositor?.email" style="color:  #5063F4;" :href="`mailto:${depositor?.email}`">
                                {{ depositor?.email }}</a>
                            <span v-else>-</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div style="justify-content: flex-start; align-items: center; gap: 10px; display: flex;flex: 1;">
                        <div style="width: 30px; height: 30px; position: relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="26" viewBox="0 0 25 26"
                                fill="none">
                                <g clip-path="url(#clip0_11255_129717)">
                                    <path
                                        d="M1.4411 8.93804C2.25547 5.46626 4.96626 2.75546 8.43804 1.9411C10.9453 1.35297 13.5547 1.35297 16.062 1.9411C19.5337 2.75547 22.2445 5.46626 23.0589 8.93805C23.647 11.4453 23.647 14.0547 23.0589 16.562C22.2445 20.0337 19.5337 22.7445 16.062 23.5589C13.5547 24.147 10.9453 24.147 8.43804 23.5589C4.96626 22.7445 2.25547 20.0337 1.4411 16.562C0.852968 14.0547 0.852968 11.4453 1.4411 8.93804Z"
                                        fill="#FDFDFD" stroke="#5063F4" stroke-width="1.35" stroke-linecap="round" />
                                    <path
                                        d="M9.58583 12.3446C10.3658 13.8775 11.6225 15.1342 13.1554 15.9142L14.3471 14.7225C14.4988 14.5708 14.71 14.5275 14.8996 14.5871C15.5063 14.7875 16.1562 14.8958 16.8333 14.8958C16.977 14.8958 17.1148 14.9529 17.2164 15.0545C17.3179 15.1561 17.375 15.2938 17.375 15.4375V17.3333C17.375 17.477 17.3179 17.6148 17.2164 17.7164C17.1148 17.8179 16.977 17.875 16.8333 17.875C14.3911 17.875 12.049 16.9048 10.3221 15.1779C8.59516 13.451 7.625 11.1089 7.625 8.66667C7.625 8.52301 7.68207 8.38523 7.78365 8.28365C7.88523 8.18207 8.02301 8.125 8.16667 8.125H10.0625C10.2062 8.125 10.3439 8.18207 10.4455 8.28365C10.5471 8.38523 10.6042 8.52301 10.6042 8.66667C10.6042 9.34375 10.7125 9.99375 10.9129 10.6004C10.9725 10.79 10.9292 11.0013 10.7775 11.1529L9.58583 12.3446Z"
                                        fill="#5063F4" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_11255_129717">
                                        <rect width="25" height="25" fill="white" transform="translate(0 0.5)" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </div>
                        <div
                            style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize;white-space: nowrap">
                            Telephone Number: </div>
                        <div style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">
                            <a v-if="depositor?.demographic_data.phone" style="color:  #5063F4;"
                                :href="`tel:${depositor?.demographic_data.phone}`">
                                {{ depositor?.demographic_data.phone }}</a>
                            <span v-else>-</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div style="justify-content: flex-start; align-items: center; gap: 10px; display: flex;flex: 1;">
                        <div style="width: 25px; height: 25px; position: relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30"
                                fill="none">
                                <path
                                    d="M4.4411 11.438C5.25547 7.96626 7.96626 5.25546 11.438 4.4411C13.9453 3.85297 16.5547 3.85297 19.062 4.4411C22.5337 5.25547 25.2445 7.96626 26.0589 11.438C26.647 13.9453 26.647 16.5547 26.0589 19.062C25.2445 22.5337 22.5337 25.2445 19.062 26.0589C16.5547 26.647 13.9453 26.647 11.438 26.0589C7.96626 25.2445 5.25547 22.5337 4.4411 19.062C3.85297 16.5547 3.85297 13.9453 4.4411 11.438Z"
                                    fill="#FDFDFD" stroke="#5063F4" stroke-width="1.35" stroke-linecap="round" />
                                <path
                                    d="M15 11.15C15.4736 11.15 15.9278 11.3344 16.2627 11.6626C16.5976 11.9908 16.7857 12.4359 16.7857 12.9C16.7857 13.1298 16.7395 13.3574 16.6498 13.5697C16.56 13.782 16.4285 13.9749 16.2627 14.1374C16.0969 14.2999 15.9 14.4288 15.6834 14.5168C15.4667 14.6047 15.2345 14.65 15 14.65C14.5264 14.65 14.0722 14.4656 13.7373 14.1374C13.4024 13.8092 13.2143 13.3641 13.2143 12.9C13.2143 12.4359 13.4024 11.9908 13.7373 11.6626C14.0722 11.3344 14.5264 11.15 15 11.15ZM15 8C16.3261 8 17.5979 8.51625 18.5355 9.43518C19.4732 10.3541 20 11.6004 20 12.9C20 16.575 15 22 15 22C15 22 10 16.575 10 12.9C10 11.6004 10.5268 10.3541 11.4645 9.43518C12.4021 8.51625 13.6739 8 15 8ZM15 9.4C14.0528 9.4 13.1444 9.76875 12.4746 10.4251C11.8048 11.0815 11.4286 11.9717 11.4286 12.9C11.4286 13.6 11.4286 15 15 19.697C18.5714 15 18.5714 13.6 18.5714 12.9C18.5714 11.9717 18.1952 11.0815 17.5254 10.4251C16.8556 9.76875 15.9472 9.4 15 9.4Z"
                                    fill="#5063F4" />
                            </svg>
                        </div>
                        <div
                            style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize;white-space: nowrap">
                            <!-- Postal Code: -->
                            {{ (depositor?.demographic_data?.province && depositor?.demographic_data?.city)
            ? depositor?.demographic_data?.province + "," + depositor?.demographic_data?.city : '-' }}
                        </div>
                        <div style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div style="justify-content: flex-start; align-items: center; gap: 10px; display: flex;flex: 1;">
                        <div style="width: 25px; height: 25px; position: relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="26" viewBox="0 0 25 26"
                                fill="none">
                                <g clip-path="url(#clip0_11255_129737)">
                                    <path
                                        d="M1.4411 8.93804C2.25547 5.46626 4.96626 2.75546 8.43804 1.9411C10.9453 1.35297 13.5547 1.35297 16.062 1.9411C19.5337 2.75547 22.2445 5.46626 23.0589 8.93805C23.647 11.4453 23.647 14.0547 23.0589 16.562C22.2445 20.0337 19.5337 22.7445 16.062 23.5589C13.5547 24.147 10.9453 24.147 8.43804 23.5589C4.96626 22.7445 2.25547 20.0337 1.4411 16.562C0.852968 14.0547 0.852968 11.4453 1.4411 8.93804Z"
                                        fill="#FDFDFD" stroke="#5063F4" stroke-width="1.35" stroke-linecap="round" />
                                    <path
                                        d="M12.2298 7.66536C11.727 7.66536 11.2448 7.8651 10.8893 8.22064C10.5337 8.57618 10.334 9.05839 10.334 9.5612C10.334 10.064 10.5337 10.5462 10.8893 10.9018C11.2448 11.2573 11.727 11.457 12.2298 11.457C12.7326 11.457 13.2148 11.2573 13.5704 10.9018C13.9259 10.5462 14.1257 10.064 14.1257 9.5612C14.1257 9.05839 13.9259 8.57618 13.5704 8.22064C13.2148 7.8651 12.7326 7.66536 12.2298 7.66536ZM9.25065 9.5612C9.25065 8.77107 9.56453 8.01331 10.1232 7.45461C10.6819 6.89591 11.4397 6.58203 12.2298 6.58203C13.0199 6.58203 13.7777 6.89591 14.3364 7.45461C14.8951 8.01331 15.209 8.77107 15.209 9.5612C15.209 10.3513 14.8951 11.1091 14.3364 11.6678C13.7777 12.2265 13.0199 12.5404 12.2298 12.5404C11.4397 12.5404 10.6819 12.2265 10.1232 11.6678C9.56453 11.1091 9.25065 10.3513 9.25065 9.5612ZM13.7909 12.8112H17.7104L18.8376 15.0656L15.7507 18.496L12.6632 15.0656L13.7909 12.8112ZM14.4604 13.8945L13.9626 14.8901L15.7507 16.8764L17.5382 14.8901L17.0409 13.8945H14.4604ZM10.334 14.1654C9.75935 14.1654 9.20825 14.3936 8.80192 14.8C8.39559 15.2063 8.16732 15.7574 8.16732 16.332H11.9861V17.4154H7.08398V16.332C7.08398 15.4701 7.42639 14.6434 8.03589 14.0339C8.64538 13.4244 9.47203 13.082 10.334 13.082H11.959V14.1654H10.334Z"
                                        fill="#5063F4" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_11255_129737">
                                        <rect width="25" height="25" fill="white" transform="translate(0 0.5)" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </div>
                        <div
                            style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize;white-space: nowrap">
                            <!-- Postal Code: -->
                            User Role

                        </div>
                        <div style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">
                            {{ depositor?.role_name }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <GeneralNoInteractionError :size="errorModalSize" @closedModal="closeErrorModal()" :title="errorModalTitle"
            :show="showErrorModal" :message="errorModalMessage" /> -->
    </div>
</template>
<script>
// import Tooltip from "../../../shared/Tooltip";
// import GeneralNoInteractionError from "../../../shared/messageboxes/GeneralNoInteractionError.vue";
import Avatar from 'vue-avatar';
export default {
    components: { Avatar },
    props: ['datum', 'depositor_data'],
    mounted() {
        if (this.depositor_data) {
            this.depositor = JSON.parse(this.depositor_data)
        }
    },
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
        console.log(this.datum);
        let transferdetails = '';
        let financialstatement = '';
        if (this.depositor?.document != null) {
            this.depositor?.document.forEach((value, key) => {

                if (value.document_type == "Transfer Details") {
                    transferdetails = value.file_name;
                } else if (value.document_type == "Financial Statement") {
                    financialstatement = (value.file_name != null) ? value.file_name : '';
                }
            })

        }


        return {
            depositor: null,
            errorModalTitle: "",
            errorModalMessage: "",
            showErrorModal: false,
            errorModalSize: "md",
            financialstatement: (financialstatement === '') ? '' : '/' + financialstatement,
            organi: null
        }

    },

}
</script>