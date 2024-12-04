<template>
    <Table :columns="columns" no-data-title="No Institutions" no-data-message=" No Institutions available"
        :data="table_data" :has_action='false' :actions='actions' :selectable="false" />
</template>

<script>
import Table from "../../../shared/PostOffersTable";
// import Pagination from "../../../shared/Table/Pagination";
import ParticipationBadge from "./../../shared/CustomBadge.vue"
import ShowCG from "../../shared/ShowCG.vue";

import { mapGetters } from "vuex";

export default {
    props: ['actionId'],

    components: {
        // institutionProfile,
        ParticipationBadge,
        Table,
        ShowCG
    },
    mounted() {
        if (this.getRequestSummary)
            this.setInstitutions()
    },
    data() {
        let columnss = ['Institution', 'Province', 'Short Term DBRS Rating', 'Deposit Insurance', 'Status'];
        let act = [
            // {
            //     name: "View Offers",
            //     component: ViewOffer
            // },
            // {
            //     name: "Withdraw Request",
            //     component: WithdrawRequest
            // },
            // {
            //     name: "Edit Request",
            //     component: EditRequest
            // }
        ];
        return {
            table_data: [],
            actions: act,
            columns: columnss,
            details: null,
            existing: null,
            action: 'view',
            is_modal: false
        }
    },


    computed: {
        ...mapGetters('repopostrequest', ['getRequestSummary']),

    },
    methods: {
        renderOrgData(org) {
            return ({ 'component': ShowCG, 'attrs': { orgname: org.name, organization: org } });
        },
        renderListComponents(componentType, compnentData, state) {
            // console.log(compnentData, "compnentData");
            switch (componentType) {
                case "Paticipation":

                    return ({ 'component': ParticipationBadge, 'attrs': { type: compnentData, state: state } });
                    break;
                    EnterOfferInput
                default:
                    return "-";
            }

        },
        capitalize(thestring) {
            if (thestring != undefined) {
                return thestring
                    .toLowerCase()
                    .split(' ')
                    .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
                    .join(' ');
            }

        },
        setInstitutions() {
            // 
            console.log("set Institutions", this.getRequestSummary?.invited_c_gs)
            let table_data = []
            this.getRequestSummary?.invited_c_gs.forEach((item) => {
                table_data.push([
                    item?.id,
                    () => this.renderOrgData(item?.organization),
                    item?.organization.demographic_data.province,
                    item?.organization?.ratings?.credit_rating,
                    item?.organization?.deposit_credit_rating?.insurance_rating.description,
                    () => this.renderListComponents("Paticipation", (item?.invitation_status === "PARTICIPATED") ? 'success' : 'primary', item?.invitation_status)]
                );
                // console.log(table_data, "institutions itemitem");
            });
            this.table_data = table_data;


        }

    },
    watch: {
        getRequestSummary() {
            this.setInstitutions();
        }
    },
}
</script>