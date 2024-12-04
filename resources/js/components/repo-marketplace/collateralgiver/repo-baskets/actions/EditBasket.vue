<template>
    <div>

        <TableActionButton type="table-action-btn" variantColor="#FFFFFF" borderColor="#5063F4" fontColor="#5063F4"
            @click="showModal">
            <img src="/assets/dashboard/icons/edit_offers.svg">
            Edit
            <GeneralErrorNoInteraction :title="errorTitle" :message="errorMsg" :show="showErrorMsg" size="md"
                @closedModal="showErrorMsg = false" />
        </TableActionButton>

        <EditCollatearalBasket :show="show" :triparty="triparty" :basket="actionId" v-if="show"
            @closeModal="show = false" />

    </div>

</template>
<script>
import Button from "../../../../shared/Buttons/Button";
import TableActionButton from "../../../../shared/Buttons/PostTableActionButton";
import GeneralErrorNoInteraction from "../../../../shared/messageboxes/GeneralNoInteractionError.vue";
import EditCollatearalBasket from "./editCollatearalBasket.vue";
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
            triparty: null,
            errorTitle: '',
            hasOffers: (this.actionObjectDetails[6] != undefined && this.actionObjectDetails[6] > 0) ? true : false
        }
    },
    computed: {
        ...mapGetters('basket', ['getTriBaskets'])
    },
    methods: {
        showModal() {
            this.triparty = this.getTriBaskets.trade_tri_basket_third_party.find(item => item.encoded_id == this.actionId)
            this.show = true
        }
    },
    mounted() {
        // console.log(this.getTriBaskets)
    },
    props: ['actionId', 'actionObjectDetails']
}
</script>