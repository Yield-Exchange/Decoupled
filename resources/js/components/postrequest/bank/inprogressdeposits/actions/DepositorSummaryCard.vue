<template>
    <div>
        <div style="display: flex;padding: 10px;align-items: center;gap: 30px;">
            <div class="w-100">
                <div style="width: 100%; margin-top: 10px;" class="row mt-3">
                    <b-tabs content-class="mt-3"
                        nav-wrapper-class="custom-tab-nav-class custom-tab-nav-class-border-bottom">
                        <b-tab title="Institution Summary" active v-if="organization_data">
                            <div style="display: flex; gap: 30px">
                                <AboutDepositor :organization_data="organization" />
                            </div>
                        </b-tab>
                        <b-tab title="Contact Person Summary" v-if="depositRequest">
                            <ContactPerson :depositor_data="JSON.stringify(depositRequest?.user)" />
                        </b-tab>
                        <!-- <b-tab title="View Files"></b-tab> -->
                    </b-tabs>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
//import AboutBank from '../../../depositor/pendingdeposits/AboutBank.vue';
import AboutDepositor from '../../pendingdeposits/AboutDepositor.vue';
// import AboutDepositor from '../pendingdeposits/AboutDepositor.vue'
import ContactPerson from '../../../depositor/pendingdeposits/AboutOrgAdmin.vue';
import Avatar from 'vue-avatar';
// import ContactPerson from './ContactPerson.vue';

export default {
    mounted() {
        if (this.organization_data) {
            this.organization = JSON.parse(this.organization_data)
        }
        if (this.deposit_request) {
            this.depositRequest = JSON.parse(this.deposit_request)
        }

    },
    props: ['organization_data', 'deposit_request'],
    components: {
        AboutDepositor,
        Avatar,
        ContactPerson
    },
    data() {
        return {
            organization: null,
            depositRequest: null
        }
    },
}
</script>

<style>
.about_avator {
    background: var(--White, #FFF);
    box-shadow: 0px 4px 4px 0px #D9D9D9;
    display: flex;
    width: 261px;
    height: 280px;
    padding: 50px 40px;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 12px;
    flex-shrink: 0;
}

.log-container {
    display: flex;
    padding: 10px;
    align-items: center;
    gap: 10px;
    background: #EFF2FE;
    margin-bottom: 6px;
}

.counter_header {
    color: #5063F4;
    margin-bottom: -3px;
    font-family: Montserrat;
    font-size: 16px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 162.5% */
    text-transform: capitalize;
}

.counter_offer {
    flex-grow: 1;
}

.original_offer {
    flex-grow: 1;
}

.counter-values {
    color: #252525;
    font-family: Montserrat;
    font-size: 15px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
}

.counter-labels {
    color: #000;
    font-family: Montserrat;
    font-size: 14px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
}

.product-data {
    display: flex;
    padding: 0px 10px;
    justify-content: space-between;
    gap: 20px;
}

.fixed_term {
    color: #252525;
    font-family: Montserrat;
    font-size: 16px;
    font-style: normal;
    font-weight: 500;
    line-height: normal;
}

.request_amount_cash {
    color: #252525;
    font-family: Montserrat;
    font-size: 18px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
}

.request_amount {
    color: #5063F4;
    text-align: center;
    font-family: Montserrat;
    font-size: 18px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
}

.request_counter_offer {
    color: #252525;
    text-align: center;
    font-family: Montserrat;
    font-size: 30px;
    font-style: normal;
    font-weight: 600;
    line-height: normal;
    text-decoration: line-through;
}

.request_head {
    color: #252525;
    font-family: Montserrat;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 26px;
    /* 162.5% */
    text-transform: capitalize;
}

.request_rate {
    color: #5063F4;
    text-align: center;
    font-family: Montserrat;
    font-size: 55px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
}
</style>