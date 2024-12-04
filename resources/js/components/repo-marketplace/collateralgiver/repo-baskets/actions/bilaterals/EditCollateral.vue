<template>
    <div>
        <TableActionButton type="table-action-btn" variantColor="#FFFFFF" borderColor="#5063F4" fontColor="#5063F4"
            @click="showModal">
            <img src="/assets/dashboard/icons/edit_offers.svg">
            Edit
            <GeneralErrorNoInteraction :title="errorTitle" :message="errorMsg" :show="showErrorMsg" size="md"
                @closedModal="showErrorMsg = false" />
        </TableActionButton>

        <EditCollatearalBasket :getBilateralCollaterals="getBilateralCollaterals"
            :getCollateralIssuers="getCollateralIssuers" :primary="getBiBaskets" :actionId="actionId" :show="show"
            :billateral="billateral" :basket="actionId" v-if="show" @closeModal="show = false" />

    </div>

</template>
<script>
import Button from "../../../../../shared/Buttons/Button";
import TableActionButton from "../../../../../shared/Buttons/PostTableActionButton";
import GeneralErrorNoInteraction from "../../../../../shared/messageboxes/GeneralNoInteractionError.vue";
import EditCollatearalBasket from "./editBilateralBasket.vue";
import { mapGetters } from "vuex";
export default {
    components: {
        GeneralErrorNoInteraction,
        TableActionButton,
        Button,
        EditCollatearalBasket
    },
    data() {
        return {
            show: false,
            showErrorMsg: false,
            errorMsg: '',
            errorTitle: '',
            billateral: null,
            hasOffers: (this.actionObjectDetails[6] != undefined && this.actionObjectDetails[6] > 0) ? true : false
        }
    },
    methods: {
        showModal() {
            console.log()
            this.billateral = this.getBiBaskets.trade_organization_c_u_s_s_i_p.find(item => item.encoded_id == this.actionId)
            this.show = true
        }
    },
    computed: {
        ...mapGetters('basket', ['getBiBaskets', 'getCollateralIssuers', 'getBilateralCollaterals'])
    },
    mounted() {

    },
    props: ['actionId', 'actionObjectDetails']
}
</script>