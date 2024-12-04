<template>
    <Table :columns="columns" no-data-title="No Institutions""
    no-data-message=" No Institutions available" :data="table_data" :has_action='false' :actions='actions'
        :selectable="false" />
</template>

<script>
    import Table from "../../shared/PostOffersTable";
    import Pagination from "../../shared/Table/Pagination";
    import Button from "../../shared/Buttons/Button";
    import TableActionButton from "../../shared/Buttons/TableActionButton";
    import { formatTimestamp } from "../../../utils/dateUtils";
    import ViewOffer from "../actions/ViewOffer"
    import WithdrawRequest from "../actions/WithdrawRequest"
    import EditRequest from "../actions/EditRequest"
    import ParticipationBadge from "../sharedComponents/CustomBadge.vue"
    import institutionProfile from "../sharedComponents/institutionProfile"

    export default {
        data() {
            let columnss = ['Institution', 'Province', 'Short Term DBRS Rating', 'Deposit Insurance', 'Status'];
            let act = [
                {
                    name: "View Offers",
                    component: ViewOffer
                },
                {
                    name: "Withdraw Request",
                    component: WithdrawRequest
                },
                {
                    name: "Edit Request",
                    component: EditRequest
                }
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
        components: {
            institutionProfile,
            ParticipationBadge,
            Table,
            EditRequest,
            WithdrawRequest,
            ViewOffer,
        },
        mounted() {
            const params = new URLSearchParams(window.location.search);
            this.requestId = params.get('request_id');
            this.getInstitutions("/get-prequest-institutions?request_id=" + this.requestId + "");

        },
        methods: {
            renderOrgData(org) {
                return ({ 'component': institutionProfile, 'attrs': { organization: org } });
            },
            renderListComponents(componentType, compnentData, state) {
                console.log(compnentData, "compnentData");
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
            getInstitutions(url) {
                let this_ = this;
                url = url ? url : "/get-prequest-institutions";
                axios.get(url)
                    .then(response => {

                        let table_data = [];
                        this_.data = response?.data;
                        console.log("institutions institutionsinstitutions", response?.data);
                        Object.values(response?.data).forEach((item) => {
                            table_data.push([
                                item?.id,
                                () => this.renderOrgData(item?.bank),
                                item?.bank.demographic_data.province,
                                item?.bank?.ratings?.credit_rating,
                                item?.bank?.deposit_credit_rating?.insurance_rating.description,
                                () => this.renderListComponents("Paticipation", (item?.invitation_status === "PARTICIPATED") ? 'success' : 'primary', item?.invitation_status)]
                            );
                            console.log(table_data, "institutions itemitem");
                        });
                        this_.table_data = table_data;
                        console.log(this.table_data, "table_data itemitem");
                    }).catch(error => {
                        console.log("error > " + error);
                    });
            }

        },
        props: ['actionId']
    }
</script>