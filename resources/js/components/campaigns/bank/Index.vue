<template>
    <div style="height: 100%" v-if="userCan(this.userloggedin,'bank/my-campaigns/page-access')">
        <Dashboard :provinces="provinces" :products="products" v-if="action === 'view' || is_modal"
            :action.sync="action" :userloggedin="userloggedin" :timezone="formattedtimezone" />
        <CampaignCreationPage @isModal="is_modal = $event" v-if="action === 'create'" @modal-closed="action = 'view'"
            :timeZone="timezone" :products="products" :depositors="depositors" :userloggedin="userloggedin"
            :unformattedusertimezone="unformattedusertimezone" :formattedtimezone="formattedtimezone" />
    </div>
</template>

<script>
    import Dashboard from "./Dashboard";
    import CampaignCreationPage from "./CampaignCreationPage";
    import { userCan } from "../../../utils/GlobalUtils";
    export default {
        mounted() {
            const urlParams = new URLSearchParams(window.location.search);
            let campaign_id = urlParams.get('campaign_id');
            let isNew = urlParams.get('new');
            if (campaign_id || isNew) {
                this.action = 'create';
            }
        },
        computed: {

        },
        components: {
            Dashboard,
            CampaignCreationPage,
        },
        created() {
        },
        props: ['products', 'depositors', 'timezone', 'provinces', 'unformattedusertimezone', 'formattedtimezone', 'userloggedin'],
        data() {

            return {
                details: null,
                existing: null,
                action: 'view',
                is_modal: false
            }
        },
        watch: {
            // details(newValue, oldValue){
            //     console.log(newValue);
            //     this.$emit('update:details', newValue);
            // },
            // existing(newValue, oldValue){
            //     if(newValue){
            //         console.log("Chose existing path");
            //         this.$emit('update:existing', newValue);
            //     }
            // }
        },
        methods: {
            userCan(user, permission) {
                return userCan(user, permission);
            },
        }
    }
</script>