<template>
    <Modal :visible.sync="show" :show="show" :hide-header-close="true" @closeModal="closemodal"
        :campaignId="campaign_id">

        <div
            style="width: 100%; height: 100%; flex-direction: column; justify-content: center; align-items: center; gap: 0; display: inline-flex">
            <div style="display: flex;flex-direction:column;">
                <div style="justify-content: flex-start; align-items: center; gap: 10px; display: inline-flex">
                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 60 60" fill="none">
                        <path
                            d="M8.88219 22.8761C10.5109 15.9325 15.9325 10.5109 22.8761 8.88219C27.8906 7.70594 33.1094 7.70594 38.1239 8.88219C45.0675 10.5109 50.4891 15.9325 52.1178 22.8761C53.2941 27.8906 53.2941 33.1094 52.1178 38.1239C50.4891 45.0675 45.0675 50.4891 38.1239 52.1178C33.1094 53.2941 27.8906 53.2941 22.8761 52.1178C15.9325 50.4891 10.5109 45.0675 8.88219 38.1239C7.70594 33.1094 7.70594 27.8906 8.88219 22.8761Z"
                            fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35" />
                        <path d="M22 31L28 37L40 25" stroke="#5063F4" stroke-width="3.33" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    <div style="font-size: 28px; font-weight: 700; line-height: 32px; word-wrap: break-word">
                        <span style="color: #2A2A2A; ">You can </span>
                        <span style="color: #5063F4; font-weight: 800; text-decoration: underline;">feature</span>
                        <span style="color: #2A2A2A;"> a product from the actions column</span>
                    </div>
                </div>
            </div>

            <div
                style="height: 251px; flex-direction: column; justify-content: center; align-items: flex-end; gap: 10px; display: flex">
                <div
                    style="flex-direction: column; justify-content: flex-start; align-items: flex-start; display: flex">
                    <div
                        style="flex-direction: column; justify-content: center; align-items: flex-start; display: flex">
                        <div
                            style="flex-direction: column; justify-content: flex-start; align-items: flex-start; display: flex">
                            <div
                                style="padding: 10px; background: rgba(80, 99, 244, 0.05); border-top-left-radius: 10px; border-top-right-radius: 10px; overflow: hidden; justify-content: flex-start; align-items: center; gap: 20px; display: inline-flex">

                                <div
                                    style="width: 70px; height: 39px; color: black; font-size: 13px; font-weight: 700; word-wrap: break-word">
                                    Minimum</div>
                                <div
                                    style="width: 70px; height: 39px; color: black; font-size: 13px; font-weight: 700; word-wrap: break-word">
                                    Maximum</div>
                                <div
                                    style="width: 80px; height: 39px; color: black; font-size: 13px; font-weight: 700; word-wrap: break-word">
                                    Order Limit</div>
                                <div
                                    style="width: 30px; height: 39px; color: black; font-size: 13px; font-weight: 700; word-wrap: break-word">
                                    PDS</div>
                                <div
                                    style="width: 80px; height: 39px; color: black; font-size: 13px; font-weight: 700; word-wrap: break-word">
                                    Action</div>
                            </div>
                            <!--                            <div style="align-self: stretch; flex: 1 1 0; border: 0.50px #D9D9D9 solid"></div>-->
                        </div>
                    </div>
                    <div
                        style="flex-direction: column; justify-content: center; align-items: flex-start; display: flex">
                        <div
                            style="flex-direction: column; justify-content: flex-start; align-items: flex-start; display: flex">
                            <div
                                style="padding: 10px; background: white; justify-content: flex-start; align-items: center; gap: 20px; display: inline-flex">
                                <div
                                    style="width: 70px; height: 28px; color: black; font-size: 13px; font-weight: 500; word-wrap: break-word">
                                    -</div>
                                <div
                                    style="width: 70px; height: 28px; color: black; font-size: 13px;  font-weight: 500; word-wrap: break-word">
                                    -</div>
                                <div
                                    style="width: 80px; height: 28px; color: black; font-size: 13px; font-weight: 500; word-wrap: break-word">
                                    -</div>
                                <div
                                    style="height: 20px; justify-content: flex-start; align-items: center; gap: 10px; display: flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="21" height="20" viewBox="0 0 21 20"
                                        fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3.83301 3.33317C3.83301 2.89114 4.0086 2.46722 4.32116 2.15466C4.63372 1.8421 5.05765 1.6665 5.49967 1.6665H15.4997C15.9417 1.6665 16.3656 1.8421 16.6782 2.15466C16.9907 2.46722 17.1663 2.89114 17.1663 3.33317V16.6665C17.1663 17.1085 16.9907 17.5325 16.6782 17.845C16.3656 18.1576 15.9417 18.3332 15.4997 18.3332H5.49967C5.05765 18.3332 4.63372 18.1576 4.32116 17.845C4.0086 17.5325 3.83301 17.1085 3.83301 16.6665V3.33317ZM15.4997 3.33317H5.49967V16.6665H15.4997V3.33317ZM7.16634 7.49984C7.16634 7.27882 7.25414 7.06686 7.41042 6.91058C7.5667 6.7543 7.77866 6.6665 7.99967 6.6665H12.9997C13.2207 6.6665 13.4326 6.7543 13.5889 6.91058C13.7452 7.06686 13.833 7.27882 13.833 7.49984C13.833 7.72085 13.7452 7.93281 13.5889 8.08909C13.4326 8.24537 13.2207 8.33317 12.9997 8.33317H7.99967C7.77866 8.33317 7.5667 8.24537 7.41042 8.08909C7.25414 7.93281 7.16634 7.72085 7.16634 7.49984ZM7.99967 10.8332C7.77866 10.8332 7.5667 10.921 7.41042 11.0772C7.25414 11.2335 7.16634 11.4455 7.16634 11.6665C7.16634 11.8875 7.25414 12.0995 7.41042 12.2558C7.5667 12.412 7.77866 12.4998 7.99967 12.4998H10.4997C10.7207 12.4998 10.9326 12.412 11.0889 12.2558C11.2452 12.0995 11.333 11.8875 11.333 11.6665C11.333 11.4455 11.2452 11.2335 11.0889 11.0772C10.9326 10.921 10.7207 10.8332 10.4997 10.8332H7.99967Z"
                                            fill="#5063F4" />
                                    </svg>
                                </div>
                                <div
                                    style="height: 20px; padding: 4px 8px;background: #EFF2FE; border-radius: 200px; justify-content: flex-start; align-items: center; gap: 2px; display: flex">
                                    <div
                                        style="justify-content: flex-start; align-items: center; gap: 2px; display: flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="6" viewBox="0 0 10 6"
                                            fill="none">
                                            <path d="M1.5 2.99984L3.83333 5.33317L8.5 0.666504" stroke="#5063F4"
                                                stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div
                                            style="width:60px;padding-left: 4px; padding-right: 4px; justify-content: flex-start; align-items: center; gap: 9px; display: flex">
                                            <div
                                                style="color: #5063F4; font-size: 9px; font-weight: 500; line-height: 9px; word-wrap: break-word">
                                                Feature</div>
                                            <div
                                                style="color: #9CA1AA; font-size: 7px; font-weight: 800; line-height: 12px; word-wrap: break-word">
                                                V</div>
                                            <div style="margin-top: 20px; margin-left: -20px">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="38"
                                                    viewBox="0 0 28 38" fill="none">
                                                    <path
                                                        d="M10.5085 13.8667C14.3377 13.8667 17.4419 10.7625 17.4419 6.93333C17.4419 3.10416 14.3377 0 10.5085 0C6.67935 0 3.5752 3.10416 3.5752 6.93333C3.5752 10.7625 6.67935 13.8667 10.5085 13.8667Z"
                                                        fill="#DFEAFB" />
                                                    <path
                                                        d="M15.9268 36.5661C14.8174 36.5661 13.7816 36.5661 12.8387 36.5065C11.6772 36.4279 10.5599 36.0305 9.60968 35.3579C8.65946 34.6854 7.91315 33.7638 7.45286 32.6945L1.60251 21.1325C1.17342 20.6329 0.959171 19.9839 1.00644 19.3271C1.05372 18.6702 1.35868 18.0586 1.85488 17.6256C2.26141 17.2989 2.76854 17.123 3.29009 17.1278C3.67406 17.139 4.05104 17.2333 4.39504 17.4042C4.73905 17.5751 5.0419 17.8186 5.28272 18.1179L7.93958 21.744L7.97979 21.7912V8.01463C7.97979 7.33113 8.2513 6.67562 8.73461 6.19232C9.21791 5.70902 9.87342 5.4375 10.5569 5.4375C11.2404 5.4375 11.8959 5.70902 12.3792 6.19232C12.8625 6.67562 13.134 7.33113 13.134 8.01463V17.028C13.1041 16.6963 13.1436 16.362 13.25 16.0465C13.3564 15.7309 13.5273 15.4409 13.752 15.1951C13.9766 14.9493 14.25 14.7529 14.5548 14.6186C14.8595 14.4843 15.1889 14.4149 15.5219 14.4149C15.8549 14.4149 16.1843 14.4843 16.489 14.6186C16.7937 14.7529 17.0671 14.9493 17.2918 15.1951C17.5164 15.4409 17.6874 15.7309 17.7938 16.0465C17.9002 16.362 17.9396 16.6963 17.9097 17.028V18.9069C17.8798 18.5752 17.9193 18.2409 18.0257 17.9254C18.1321 17.6098 18.303 17.3199 18.5277 17.074C18.7523 16.8282 19.0257 16.6319 19.3304 16.4975C19.6352 16.3632 19.9645 16.2938 20.2976 16.2938C20.6306 16.2938 20.9599 16.3632 21.2647 16.4975C21.5694 16.6319 21.8428 16.8282 22.0674 17.074C22.2921 17.3199 22.463 17.6098 22.5694 17.9254C22.6758 18.2409 22.7153 18.5752 22.6854 18.9069V20.3546C22.6555 20.0229 22.6949 19.6886 22.8013 19.3731C22.9077 19.0575 23.0787 18.7676 23.3033 18.5217C23.528 18.2759 23.8014 18.0795 24.1061 17.9452C24.4108 17.8109 24.7402 17.7415 25.0732 17.7415C25.4062 17.7415 25.7356 17.8109 26.0403 17.9452C26.3451 18.0795 26.6185 18.2759 26.8431 18.5217C27.0678 18.7676 27.2387 19.0575 27.3451 19.3731C27.4515 19.6886 27.491 20.0229 27.4611 20.3546V29.1419C27.4139 31.8445 26.1923 36.4011 21.895 36.4011C21.583 36.4149 18.94 36.5675 15.9323 36.5675L15.9268 36.5661Z"
                                                        fill="#5063F4" stroke="white" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="align-self: stretch; height: 0px; border: 0.50px #D9D9D9 solid"></div>
                        </div>
                    </div>
                    <div
                        style="flex-direction: column; justify-content: center; align-items: flex-start; display: flex">
                        <div
                            style="flex-direction: column; justify-content: flex-start; align-items: flex-start; display: flex">
                            <div
                                style="padding: 10px; background: white; justify-content: flex-start; align-items: center; gap: 20px; display: inline-flex">
                                <div
                                    style="width: 70px; height: 28px; color: black; font-size: 13px; font-weight: 500; word-wrap: break-word">
                                    -</div>
                                <div
                                    style="width: 70px; height: 28px; color: black; font-size: 13px; font-weight: 500; word-wrap: break-word">
                                    -</div>
                                <div
                                    style="width: 80px; height: 28px; color: black; font-size: 13px; font-weight: 500; word-wrap: break-word">
                                    -</div>
                                <div
                                    style="height: 20px; justify-content: flex-start; align-items: center; gap: 10px; display: flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="21" height="20" viewBox="0 0 21 20"
                                        fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3.83301 3.33317C3.83301 2.89114 4.0086 2.46722 4.32116 2.15466C4.63372 1.8421 5.05765 1.6665 5.49967 1.6665H15.4997C15.9417 1.6665 16.3656 1.8421 16.6782 2.15466C16.9907 2.46722 17.1663 2.89114 17.1663 3.33317V16.6665C17.1663 17.1085 16.9907 17.5325 16.6782 17.845C16.3656 18.1576 15.9417 18.3332 15.4997 18.3332H5.49967C5.05765 18.3332 4.63372 18.1576 4.32116 17.845C4.0086 17.5325 3.83301 17.1085 3.83301 16.6665V3.33317ZM15.4997 3.33317H5.49967V16.6665H15.4997V3.33317ZM7.16634 7.49984C7.16634 7.27882 7.25414 7.06686 7.41042 6.91058C7.5667 6.7543 7.77866 6.6665 7.99967 6.6665H12.9997C13.2207 6.6665 13.4326 6.7543 13.5889 6.91058C13.7452 7.06686 13.833 7.27882 13.833 7.49984C13.833 7.72085 13.7452 7.93281 13.5889 8.08909C13.4326 8.24537 13.2207 8.33317 12.9997 8.33317H7.99967C7.77866 8.33317 7.5667 8.24537 7.41042 8.08909C7.25414 7.93281 7.16634 7.72085 7.16634 7.49984ZM7.99967 10.8332C7.77866 10.8332 7.5667 10.921 7.41042 11.0772C7.25414 11.2335 7.16634 11.4455 7.16634 11.6665C7.16634 11.8875 7.25414 12.0995 7.41042 12.2558C7.5667 12.412 7.77866 12.4998 7.99967 12.4998H10.4997C10.7207 12.4998 10.9326 12.412 11.0889 12.2558C11.2452 12.0995 11.333 11.8875 11.333 11.6665C11.333 11.4455 11.2452 11.2335 11.0889 11.0772C10.9326 10.921 10.7207 10.8332 10.4997 10.8332H7.99967Z"
                                            fill="#5063F4" />
                                    </svg>
                                </div>
                                <div
                                    style="height: 20px; padding: 4px 8px;background: #EFF2FE; border-radius: 200px; justify-content: flex-start; align-items: center; gap: 2px; display: flex">
                                    <div
                                        style="justify-content: flex-start; align-items: center; gap: 2px; display: flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="6" viewBox="0 0 10 6"
                                            fill="none">
                                            <path d="M1.5 2.99984L3.83333 5.33317L8.5 0.666504" stroke="#5063F4"
                                                stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div
                                            style="padding-left: 4px; padding-right: 4px; justify-content: flex-start; align-items: center; gap: 9px; display: flex">
                                            <div
                                                style="color: #5063F4; font-size: 9px; font-weight: 500; line-height: 9px; word-wrap: break-word">
                                                Feature</div>
                                            <div
                                                style="color: #9CA1AA; font-size: 7px;font-weight: 800; line-height: 12px; word-wrap: break-word">
                                                V</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="align-self: stretch; height: 0px; border: 0.50px #D9D9D9 solid"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div
                style="padding-left: 90px; padding-right: 90px; justify-content: center; align-items: flex-start; gap: 50px; display: inline-flex">
                <div style="padding: 15px 41px;background: #5063F4; border-radius: 20px; overflow: hidden; justify-content: center; align-items: center; gap: 4px; display: flex; cursor: pointer"
                    @click="navigateIt">
                    <div
                        style="padding-left: 10px; padding-right: 10px; justify-content: center; align-items: flex-start; gap: 10px; display: flex">
                        <div
                            style="color: white; font-size: 16px; font-weight: 600; text-transform: capitalize; line-height: 20px; word-wrap: break-word">
                            Next</div>
                    </div>
                </div>

            </div> -->
            <div
                style="align-self: stretch; justify-content: flex-end; align-items: center; gap: 50px; display: inline-flex ; width: 100%;">
                <div @click="navigateIt"
                    style="padding-left: 30px; padding-right: 30px; padding-top: 10px; padding-bottom: 10px; background: #5063F4; border-radius: 20px; overflow: hidden; justify-content: center; align-items: center; display: flex">
                    <div
                        style="padding-left: 10px; padding-right: 10px; justify-content: center; align-items: flex-start; gap: 10px; display: flex">
                        <div
                            style="color: white; font-size: 16px; font-family: Montserrat; font-weight: 600; text-transform: capitalize; line-height: 20px; word-wrap: break-word">
                            Next</div>
                    </div>
                </div>
            </div>

        </div>
    </Modal>
</template>
<script>
    import Modal from "../../shared/CampaignSaveModal";
    import Button from "../../shared/Buttons/Button";
    export default {
        components: {
            Modal,
            Button
        },
        props: ['campaign_name', 'campaign_id', "show"],
        data() {
            return {
                showmodal: false
            }
        },
        methods: {
            closemodal() {
                window.location.href = '/campaigns/summary/' + this.campaign_id;
            },
            navigateIt() {
                window.location.href = '/campaigns/summary/' + this.campaign_id;
            }

        }
    }
</script>