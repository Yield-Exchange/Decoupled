<template>
    <div @click="viewOffersSummary"
        :style="'width:100%;height: 20px; padding: 4px 8px;border-radius: 200px; background: rgb(239, 242, 254); justify-content: flex-start; align-items: center; gap: 2px; display: inline-flex;color: rgb(80, 99, 244); font-size: 13px;  font-weight: 500; line-height: 9px; word-wrap: break-word;cursor: pointer'">
        <img src="/assets/dashboard/icons/edit_offers.svg">
        <p class="p-0 m-0"> Edit </p>
        <EditOffer :userLoggedIn="getLoggedInUser" v-if="show" :show="show" :offer="offerDetails"
            @closeModal="show = false">
        </EditOffer>
    </div>
</template>
<script>
    import Button from "../../../../shared/Buttons/Button";
    import TableActionButton from "../../../../shared/Buttons/PostTableActionButton";
    import EditOffer from "../ossummary/EditOffer.vue";
    import { mapGetters } from 'vuex';
    export default {
        components: {
            TableActionButton,
            Button,
            EditOffer
        },
        data() {
            // console.log(this.actionObjectDetails, "actionObjectDetailsactionObjectDetails");
            return {
                show: false,
                offerDetails: null
            }
        },
        computed: {
            ...mapGetters('auth', ['getLoggedInUser'])
        },
        methods: {
            viewOffersSummary() {
                //this.show = true
                //get offer details

                let url = `/trade/market-place/CG/get-offer-details?offerId=${this.actionObjectDetails[0]}`;
                axios.get(url)
                    .then(response => {
                        console.log(response?.data);
                        this.offerDetails = response?.data
                        this.show = true;
                    }).catch(error => {
                        this.is_loading = false;

                    });
                //get offer details
            }
        },
        props: ['actionObjectDetails']
    }
</script>

<style scoped>
    .pr-start-chat {
        display: flex;
        /* width: 97px; */
        padding: 2px 5px;
        align-items: center;
        max-width: 150px;
        justify-content: center;
        gap: 4px;
        border-radius: 91px;
        cursor: pointer;
        border-radius: 200px;
        background: #7786f8;
    }

    .pr-start-chat p {
        color: #fff;
    }
</style>