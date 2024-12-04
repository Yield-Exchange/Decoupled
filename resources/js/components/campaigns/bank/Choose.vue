<template>
    <div>
        <div
            style="width: 100%;flex-direction: column; justify-content: center; align-items: flex-start; gap: 31px; display: flex">
            <div style="width: 100%;justify-content: flex-start; align-items: center; gap: 10px; display: inline-flex">
                <div style="width: 60px; height: 60px; position: relative">
                    <img src="/assets/dashboard/icons/Promo.svg" />
                </div>
                <div
                    style="width: 100%; color: #2A2A2A; font-size: 30px; font-weight: 800; line-height: 32px; word-wrap: break-word">
                    How would you like to <br />set up your campaign?</div>
            </div>
            <div
                style="padding-left: 20px; padding-right: 20px; flex-direction: column; justify-content: center; align-items: flex-start; gap: 30px; display: flex">
                <div style="justify-content: flex-end; align-items: center; gap: 10px; display: inline-flex">
                    <div style="width: 31px; height: 31px; position: relative;cursor:pointer">
                        <b-form-checkbox name="type" v-model="type" value="new" :unchecked-value="null" size="lg"
                            class="campaign_type">
                        </b-form-checkbox>
                    </div>
                    <div style="color:#252525 !important;
font-family: Montserrat !important;
font-size: 14px !important;
font-weight: 400 !important;
line-height: 17px !important;
letter-spacing: 0em !important;
text-align: left !important;
"> Build A Brand New Campaign</div>
                    <div style="width: 16px; height: 16px; justify-content: center; align-items: center; display: flex">
                        <div style="width: 16px; height: 16px; position: relative; flex-direction: column; justify-content: flex-start; align-items: flex-start; display: flex"
                            id="new_campaign">
                            <img src="/assets/dashboard/icons/help-circle.svg" />
                            <Tooltip message="Creates a new campaign from scratch" target="new_campaign" />
                        </div>
                    </div>
                </div>
                <div style="justify-content: flex-end; align-items: center; gap: 10px; display: inline-flex; ">

                    <div style="width: 30px; height: 30px; position: relative">
                        <b-form-checkbox name="type" value="existing" v-model="type" :unchecked-value="null"
                            class="campaign_type" size="lg" :disabled="(existingnoofcampaigns > 0) ? false : true">
                        </b-form-checkbox>
                    </div>
                    <div style="color:#252525 !important;
font-family: Montserrat !important;
font-size: 14px !important;
font-weight: 400 !important;
line-height: 17px !important;
letter-spacing: 0em !important;
text-align: left !important;
">Copy From An Existing Campaign</div>
                    <div style="width: 16px; height: 16px; justify-content: center; align-items: center; display: flex">
                        <div style="width: 16px; height: 16px; position: relative; flex-direction: column; justify-content: flex-start; align-items: flex-start; display: flex"
                            id="existing_campaign">
                            <img src="/assets/dashboard/icons/help-circle.svg" />
                            <Tooltip message="Clones existing campaign for new edits" target="existing_campaign" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div
            style="padding-left: 10px; width: 100%; padding-right: 10px; justify-content: flex-end; align-items: center; gap: 30px; display: inline-flex; margin-top: 20px">

            <b-button @click="submit" class="message-action-btn-solid-">
                <b-spinner label="Loading" v-if="submitted" style="width: 1.3rem; height: 1.3rem;margin-right:5px">
                </b-spinner>
                Next
            </b-button>
        </div>
    </div>
</template>
<style>
    .message-action-btn-solid- {
        width: 30%;
        height: 40px;
        padding: 10px, 30px, 10px, 30px;
        border-radius: 20px;
        border: 2px;
        background-color: #5063F4 !important;
        color: white;
    }

    .campaign_type.custom-checkbox .custom-control-label::before {
        background: #F8FAFF;
        border-radius: 4px;
        border: 0.50px #5063F4 solid;
    }
</style>
<script>
    import Tooltip from "../../shared/Tooltip";
    import Button from "../../shared/Buttons/Button";
    export default {
        components: {
            Button,
            Tooltip,
        },
        props: ['existingcampaigns'],
        data() {
            return {
                disableClone: true,
                submitted: false,
                type: null,
                existingnoofcampaigns: 0
            }
        },
        computed: {

        },
        mounted() {
            this.getCampaigns();
        },
        methods: {
            getCampaigns() {

                axios.get("/campaigns/fi/my-campaigns")
                    .then(response => {
                        console.log("my campaign");
                        this.existingnoofcampaigns = response?.data?.data.length;

                    }).catch(error => {
                        // console.log("error > "+ error);

                    });
            },
            submit() {
                if (!this.type) {
                    return;
                }

                this.$emit('update:choice', this.type);
                this.$emit('update:show_modal', true);
            }
        }
    }
</script>