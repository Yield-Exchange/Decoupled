<template>
    <div
        style=" width: 99%;min-height: 736px;flex-direction: column;justify-content: flex-start; align-items: flex-start;gap: 30px; display: inline-flex;margin-left: 2%; overflow: auto;">
        <accordion :is_open="true" title="Request Summary" width="100"
            title_image="/assets/dashboard/icons/Setting__3.svg" />
        <div>

            <div
                style=" width: 100%;flex-direction: column;justify-content: flex-start; align-items: flex-start;gap: 30px; display: inline-flex; ">
                <!-- <accordion :is_open="is_open" :title="" width="100"
                        title_image="/assets/dashboard/icons/Setting__3.svg" /> -->


                <div
                    style="flex-direction: column;justify-content: flex-start;align-items: flex-start; gap: 20px; display: flex;">
                    <div
                        style="width: 100%; min-width:250px; justify-content: flex-start; gap: 20px; display: inline-flex;  margin-top: 20px;">
                        <div
                            style="width: 25%; max-height:100%; min-width:250px;  max-width:250px; justify-content: space-between; display: flex;flex-direction: column;">
                            <div
                                style="width: 100%; height: 90%; padding: 40px; background: white; box-shadow: 0px 4px 4px #D9D9D9; flex-direction: column; justify-content: center; align-items: center; gap: 20px; display: inline-flex">
                                <div
                                    style="color: #252525; font-size: 16px; font-family: Montserrat; font-weight: 400; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                                    Non-Redeemable</div>
                                <div
                                    style=" width: 100%;color: #5063f4; font-size: 68px;display: inline-flex;justify-content: center;font-weight: 700; ">
                                    3.65%
                                </div>
                                <div style="width: 100%; height: 0; border: 0.25px #9ca1aa solid;margin-top:-10px;">
                                </div>
                                <div style="width: 100%; text-align: center"><span
                                        style="color: #5063F4; font-size: 18px; font-family: Montserrat; font-weight: 700; word-wrap: break-word">Request
                                        ID:<br /></span><span
                                        style="color: #252525; font-size: 18px; font-family: Montserrat; font-weight: 700; word-wrap: break-word">GIC45924</span>
                                </div>
                            </div>
                        </div>


                        <div style=" width: 100%;flex-direction: column;justify-content: flex-start; align-items:
                                        flex-start;display: inline-flex; ">
                            <div style=" min-height: 240px;flex-direction: column;justify-content: flex-start; align-items:
                                        flex-start;gap: 30px; display: flex; ">
                                <div style=" width: 100%; padding: 0.3rem; ">

                                    <span
                                        style="color: #5063f4; font-size: 16px; font-weight: 700;line-height: 20px;word-wrap: break-word;"><br /></span><span
                                        style="color: #252525; font-size: 16px;  font-weight: 500;word-wrap: break-word;">
                                        A fixed-term investment with a locked interest rate, providing steady and
                                        predictable earnings over the investment period.
                                    </span>
                                </div>

                                <!-- <ProductDetails :earning_text="prod.earning_text" :earning_rate="prod.earning_rate"
                                    :flexibility_rate="prod.flexibility_rate"
                                    :flexibility_text="prod.flexibility_text" /> -->
                                <div
                                    style="flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 10px; display: flex;">
                                    <div
                                        style="justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex;">
                                        <div
                                            style="width: 160px; color: #5063F4; font-size: 16px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 26px; word-wrap: break-word;">
                                            Term Length</div>

                                        <div
                                            style="width: 170px;color: #5063F4; font-size: 16px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                                            Lockout Period</div>
                                        <div
                                            style="width: 170px;color: #5063F4; font-size: 16px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                                            Date Of Deposit</div>
                                        <div
                                            style="width: 210px; color: #5063F4; font-size: 16px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                                            Requested Amount</div>
                                        <div
                                            style="width: 210px; color: #5063F4; font-size: 16px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                                            Compound Frequency</div>
                                    </div>
                                    <div
                                        style="justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex;">
                                        <div
                                            style="width: 160px; color: #252525; font-size: 16px; font-family: Montserrat; font-weight: 400; text-transform: capitalize; line-height: 26px; word-wrap: break-word;">
                                            30 days </div>
                                        <div
                                            style="width: 170px;color: #252525; font-size: 16px; font-family: Montserrat; font-weight: 400; text-transform: capitalize; line-height: 26px; word-wrap: break-word;">
                                            5 days </div>
                                        <div
                                            style="width: 170px;color: #252525; font-size: 16px; font-family: Montserrat; font-weight: 400; text-transform: capitalize; line-height: 26px; word-wrap: break-word;">
                                            2024-09-08 </div>
                                        <div
                                            style="width: 210px; color: #252525; font-size: 16px; font-family: Montserrat; font-weight: 400; text-transform: capitalize; line-height: 26px; word-wrap: break-word;">
                                            CAD 30,000,000.
                                        </div>
                                        <div
                                            style="width: 210px; color: #252525; font-size: 16px; font-family: Montserrat; font-weight: 400; text-transform: capitalize; line-height: 26px; word-wrap: break-word;">
                                            At Maturity </div>
                                    </div>
                                </div>
                                <div
                                    style="flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 10px; display: flex;">
                                    <div
                                        style="justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex;">
                                        <div
                                            style="width: 160px; color: #5063F4; font-size: 16px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 26px; word-wrap: break-word;">
                                            DBRS Rating</div>

                                        <div
                                            style="width: 170px;color: #5063F4; font-size: 16px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                                            Deposit Insurance</div>
                                        <div
                                            style="width: 170px;color: #5063F4; font-size: 16px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                                            Closing Date & Time</div>
                                        <div
                                            style="width: 420px; color: #5063F4; font-size: 16px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                                            Special Instructions</div>

                                    </div>
                                    <div
                                        style="justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex;">
                                        <div
                                            style="width: 160px; color: #252525; font-size: 16px; font-family: Montserrat; font-weight: 400; text-transform: capitalize; line-height: 26px; word-wrap: break-word;">
                                            Adequate </div>
                                        <div
                                            style="width: 170px;color: #252525; font-size: 16px; font-family: Montserrat; font-weight: 400; text-transform: capitalize; line-height: 26px; word-wrap: break-word;">
                                            CDIC </div>
                                        <div
                                            style="width: 170px;color: #252525; font-size: 16px; font-family: Montserrat; font-weight: 400; text-transform: capitalize; line-height: 26px; word-wrap: break-word;">
                                            2023-10-27 07:00 AM</div>
                                        <div
                                            style="width: 420px; color: #252525; font-size: 16px; font-family: Montserrat; font-weight: 400; text-transform: capitalize; line-height: 26px; word-wrap: break-word;">
                                            A fixed-term investment with a locked interest rate, providing steady and
                                            predictable.</div>
                                    </div>
                                </div>


                            </div>

                        </div>
                    </div>
                </div>
                <div style="justify-content: flex-end;align-items: center;display: inline-flex;width: 100%; gap: 20px ">
                    <div
                        style="justify-content: flex-end;align-items: flex-start;gap: 20px;display: inline-flex;margin-right: 1rem;">
                        <b-button @click="back" class="btnbtn1">
                            Back
                        </b-button>
                    </div>
                </div>
            </div>





        </div>
        <!-- <b-row>
            <b-col cols="3">
                <div class="d-flex flex-column justify-content-center align-items-center p-4 bg-white"
                    style="width: 100%; height: 100%; box-shadow: 0px 4px 4px #D9D9D9;">
                    <div class="text-center display-4 fw-bold text-primary"
                        style="font-family: Montserrat; word-wrap: break-word;">3.71%</div>
                    <hr class="w-100 border-primary">
                    <div class="text-center fs-5 fw-bold text-muted"
                        style="font-family: Montserrat; word-wrap: break-word;">Term Length</div>
                    <div class="text-center fs-2 fw-bold text-primary"
                        style="font-family: Montserrat; word-wrap: break-word;">20 Days</div>
                </div>

            </b-col>
            <b-col cols="9">
                A fixed-term investment with a locked interest rate, providing steady and predictable earnings over the
                investment period.
            </b-col>
        </b-row> -->

    </div>
</template>
<style>
    .rate-card-holder {}
</style>


<script>
    import { userCan } from "../../../utils/GlobalUtils";
    import Accordion from "../../shared/Accordion";
    export default {
        mounted() {

        },
        computed: {

        },
        components: {
            Accordion
        },
        created() {
        },
        props: ['offers'],
        data() {

            return {
                details: null,
                existing: null,
                action: 'view',
                is_modal: false
            }
        },
        watch: {

        },
        methods: {
            back() {

            },
            userCan(user, permission) {
                return userCan(user, permission);
            },
        }
    }
</script>