<template>
    <b-modal :modal-class="customModalClass" :content-class="'vuew-modal-123 modal-height-' + elementHeight"
        :size="(modalsize) ? modalsize : 'md'"
        style="box-shadow: 0px 2px 7px rgba(64, 117, 219, 0.50) !important; border-radius: 999px; border: 0.25px solid; "
        v-model="visible" id="bv-modal-example12313" hide-footer hide-header :no-close-on-backdrop="true" centered
        :no-close-on-esc="true">
        <div style="display: flex; flex-direction:row; justify-content:center;margin-top: -15px !important;">
            <div
                style="padding: 9px 15px;background: #EFF2FE; justify-content: center; align-items: center; gap: 10px; display: inline-flex; margin-bottom:20px;">
                <div
                    style="color: #5063F4; font-size: 21px; font-weight: 400; line-height: 32px; word-wrap: break-word; ">
                    Campaign successfully submitted!</div>
            </div>
        </div>

        <b-container class="bv-example-row" style="width: 100% !important;padding: 0px !important;  ">

            <b-row
                style="width:100%;padding: 0px !important;margin-left:0px!important; margin-right:0px !important; margin-bottom:20px;">

                <b-col md="12 " class="d-flex justify-content-end " style="width:100%;padding: 0px !important;">
                    <div style="justify-content: flex-start; align-items: center; gap: 10px; display: inline-flex;">

                        <svg xmlns="http://www.w3.org/2000/svg" @click="toggleVisible()" width="31" height="31"
                            viewBox="0 0 31 31" fill="none" ref="closeIcon" class="clickable-icon">
                            <g clip-path="url(#clip0_2878_108587)">
                                <rect x="0.5" y="0.5" width="30" height="30" rx="5" fill="#44E0AA" />
                                <path
                                    d="M21.392 8.80202C21.2995 8.70932 21.1896 8.63577 21.0686 8.58559C20.9477 8.53541 20.818 8.50958 20.687 8.50958C20.5561 8.50958 20.4264 8.53541 20.3054 8.58559C20.1844 8.63577 20.0745 8.70932 19.982 8.80202L15.092 13.682L10.202 8.79202C10.1094 8.69944 9.99953 8.626 9.87856 8.57589C9.7576 8.52579 9.62795 8.5 9.49702 8.5C9.36609 8.5 9.23644 8.52579 9.11548 8.57589C8.99451 8.626 8.8846 8.69944 8.79202 8.79202C8.69944 8.8846 8.626 8.99451 8.57589 9.11548C8.52579 9.23644 8.5 9.36609 8.5 9.49702C8.5 9.62795 8.52579 9.7576 8.57589 9.87856C8.626 9.99953 8.69944 10.1094 8.79202 10.202L13.682 15.092L8.79202 19.982C8.69944 20.0746 8.626 20.1845 8.57589 20.3055C8.52579 20.4264 8.5 20.5561 8.5 20.687C8.5 20.818 8.52579 20.9476 8.57589 21.0686C8.626 21.1895 8.69944 21.2994 8.79202 21.392C8.8846 21.4846 8.99451 21.558 9.11548 21.6081C9.23644 21.6583 9.36609 21.684 9.49702 21.684C9.62795 21.684 9.7576 21.6583 9.87856 21.6081C9.99953 21.558 10.1094 21.4846 10.202 21.392L15.092 16.502L19.982 21.392C20.0746 21.4846 20.1845 21.558 20.3055 21.6081C20.4264 21.6583 20.5561 21.684 20.687 21.684C20.818 21.684 20.9476 21.6583 21.0686 21.6081C21.1895 21.558 21.2994 21.4846 21.392 21.392C21.4846 21.2994 21.558 21.1895 21.6081 21.0686C21.6583 20.9476 21.684 20.818 21.684 20.687C21.684 20.5561 21.6583 20.4264 21.6081 20.3055C21.558 20.1845 21.4846 20.0746 21.392 19.982L16.502 15.092L21.392 10.202C21.772 9.82202 21.772 9.18202 21.392 8.80202Z"
                                    fill="#F8FAFF" />
                            </g>
                            <defs>
                                <clipPath id="clip0_2878_108587">
                                    <rect width="30" height="30" fill="white" transform="translate(0.5 0.5)" />
                                </clipPath>
                            </defs>
                        </svg>
                    </div>
                </b-col>

            </b-row>
            <slot></slot>
        </b-container>
        <LeavePageConfirmDialog size="md" btnOneText="Stay" btnTwoText="Leave" title="Do you want to leave this page?"
            :showm="showLeaveWarning" @stayOnPage="stayOnPage()" @leaveThepage="leaveThePage()" />
    </b-modal>
</template>

<style scoped>
/* #bv-modal-example12313 { */
</style>
<script>
import LeavePageConfirmDialog from "../shared/messageboxes/LeavePageConfirmDialog.vue";
export default {
    props: ['show', 'modalHeight', 'hide_close', 'confirm_close_modal', 'modalWidth', 'modalsize', 'customModalClass', 'campaignId'],
    components: {
        LeavePageConfirmDialog
    },
    computed: {
        visible() {
            return this.show;
        },
        height() {
            return this.modalHeight ? this.modalHeight : "441px"
        }
    },
    data() {
        return {
            showLeaveWarning: false,
            elementHeight: this.modalWidth ? this.modalWidth : 600
        }
    },
    methods: {
        leaveThePage() {

            window.location.href = '/campaigns';
        },
        stayOnPage() {
            this.showLeaveWarning = false;
        },
        toggleVisible() {

            window.location.href = '/campaigns/summary/' + this.campaignId;
        }
    },
}
</script>