<template>
    <div class="modal fade " :class="show ? 'show' : ''" id="staticBackdrop" data-backdrop="static"
        data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="false">
        <div class="modal-dialog modal-dialog-centered d-flex justify-content-center"
            :style="{ 'maxWidth': width + 'px', }">
            <div class="modal-content signup-pop-padding" style="width: 600px;"
                :style="{ 'maxWidth': width + 'px !important' }">
                <div class="modal-header">
                    <button @click="closeModal" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none">
                            <g clip-path="url(#clip0_5092_5859)">
                                <rect width="30" height="30" rx="5" fill="#44E0AA" />
                                <path
                                    d="M20.892 8.30178C20.7995 8.20907 20.6896 8.13553 20.5686 8.08534C20.4477 8.03516 20.318 8.00933 20.187 8.00933C20.0561 8.00933 19.9264 8.03516 19.8054 8.08534C19.6844 8.13553 19.5745 8.20907 19.482 8.30178L14.592 13.1818L9.70202 8.29178C9.60944 8.19919 9.49953 8.12575 9.37856 8.07565C9.2576 8.02554 9.12795 7.99976 8.99702 7.99976C8.86609 7.99976 8.73644 8.02554 8.61548 8.07565C8.49451 8.12575 8.3846 8.19919 8.29202 8.29178C8.19944 8.38436 8.126 8.49427 8.07589 8.61523C8.02579 8.7362 8 8.86585 8 8.99678C8 9.12771 8.02579 9.25736 8.07589 9.37832C8.126 9.49928 8.19944 9.60919 8.29202 9.70178L13.182 14.5918L8.29202 19.4818C8.19944 19.5744 8.126 19.6843 8.07589 19.8052C8.02579 19.9262 8 20.0558 8 20.1868C8 20.3177 8.02579 20.4474 8.07589 20.5683C8.126 20.6893 8.19944 20.7992 8.29202 20.8918C8.3846 20.9844 8.49451 21.0578 8.61548 21.1079C8.73644 21.158 8.86609 21.1838 8.99702 21.1838C9.12795 21.1838 9.2576 21.158 9.37856 21.1079C9.49953 21.0578 9.60944 20.9844 9.70202 20.8918L14.592 16.0018L19.482 20.8918C19.5746 20.9844 19.6845 21.0578 19.8055 21.1079C19.9264 21.158 20.0561 21.1838 20.187 21.1838C20.318 21.1838 20.4476 21.158 20.5686 21.1079C20.6895 21.0578 20.7994 20.9844 20.892 20.8918C20.9846 20.7992 21.058 20.6893 21.1081 20.5683C21.1583 20.4474 21.184 20.3177 21.184 20.1868C21.184 20.0558 21.1583 19.9262 21.1081 19.8052C21.058 19.6843 20.9846 19.5744 20.892 19.4818L16.002 14.5918L20.892 9.70178C21.272 9.32178 21.272 8.68178 20.892 8.30178Z"
                                    fill="#EFF2FE" />
                            </g>
                            <defs>
                                <clipPath id="clip0_5092_5859">
                                    <rect width="30" height="30" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                    </button>
                </div>
                <div class="modal-body mt-4">

                    <div class="d-flex justify-content-start align-items-start gap-2 mbi-20">
                        <div class="">
                            <img :src="'/assets/' + icon" alt="">
                        </div>
                        <div class="d-flex  flex-column justify-content-start align-items-start gap-2">
                            <p class="p-0 m-0 action-message-title">{{ title }}</p>
                            <p class="p-0 m-0 modal-message-description " v-if="message != null && message != ''">{{
        message }}
                            </p>
                            <slot></slot>

                            <div class="w-100 d-flex justify-content-end align-items-center gap-3 my-3">
                                <CustomSubmit v-if="outlinedbuttontext != null && outlinedbuttontext != ''"
                                    :outline="true" :title="outlinedbuttontext" @action="outlinedClicked" />
                                <CustomSubmit v-if="primarybuttontext != null && primarybuttontext != ''"
                                    :outline="false" :title="primarybuttontext" @action="primaryClicked" />

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>



<script>
import CustomSubmit from './CustomSubmit.vue';


export default {
    props: ['show', 'width', 'title', 'message', 'outlinedbuttontext', 'primarybuttontext', 'icon'],
    components: { CustomSubmit },
    methods: {
        closeModal() {
            this.$emit('close', false)
        },
        outlinedClicked() {
            this.$emit('outlinedClicked', true)
        },
        primaryClicked() {
            this.$emit('primaryClicked', true)
        }
    },

}
</script>
<style>
.signup-pop-padding {
    padding: 5px 10px !important;
}
</style>

<style scoped>
.show {
    display: block;
    background-color: rgba(0, 0, 0, 0.555);
    font-family: 'Montserrat';

}

.modal-message-description {
    color: #2A2A2A;
    font-feature-settings: 'clig' off, 'liga' off;
    font-family: Montserrat !important;
    font-size: 20px;
    font-style: normal;
    font-weight: 400;
    line-height: 26px;
    /* 130% */
    /* text-transform: capitalize; */
}

.action-message-title {
    color: #2A2A2A;

    /* Yield Exchange Text Styles/Modal  & Blue BG Titles */
    font-family: Montserrat !important;
    font-size: 28px;
    font-style: normal;
    font-weight: 700;
    line-height: 32px;
    /* 114.286% */
    text-transform: capitalize;
}
</style>