<template>
    <div class="pr-start-chat" @click="viewOffersSummary">
        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="13" viewBox="0 0 12 13" fill="none">
            <path
                d="M6 5C6.39782 5 6.77936 5.15804 7.06066 5.43934C7.34196 5.72064 7.5 6.10218 7.5 6.5C7.5 6.89782 7.34196 7.27936 7.06066 7.56066C6.77936 7.84196 6.39782 8 6 8C5.60218 8 5.22064 7.84196 4.93934 7.56066C4.65804 7.27936 4.5 6.89782 4.5 6.5C4.5 6.10218 4.65804 5.72064 4.93934 5.43934C5.22064 5.15804 5.60218 5 6 5ZM6 2.75C8.5 2.75 10.635 4.305 11.5 6.5C10.635 8.695 8.5 10.25 6 10.25C3.5 10.25 1.365 8.695 0.5 6.5C1.365 4.305 3.5 2.75 6 2.75ZM1.59 6.5C1.99413 7.32515 2.62165 8.02037 3.40124 8.50663C4.18083 8.99288 5.0812 9.25066 6 9.25066C6.9188 9.25066 7.81917 8.99288 8.59876 8.50663C9.37835 8.02037 10.0059 7.32515 10.41 6.5C10.0059 5.67485 9.37835 4.97963 8.59876 4.49337C7.81917 4.00712 6.9188 3.74934 6 3.74934C5.0812 3.74934 4.18083 4.00712 3.40124 4.49337C2.62165 4.97963 1.99413 5.67485 1.59 6.5Z"
                fill="#2A9928" />
        </svg>
        <p class="p-0 m-0"> View </p>
        <OfferSummary :userLoggedIn="getLoggedInUser" v-if="show" :show="show" :offer="offerDetails"
            @closeModal="show = false">
        </OfferSummary>

    </div>
</template>
<script>
import Button from "../../../../shared/Buttons/Button";
import TableActionButton from "../../../../shared/Buttons/PostTableActionButton";
import OfferSummary from "../ossummary/OfferSummary.vue";
import { mapGetters } from 'vuex';
export default {
    components: {
        TableActionButton,
        Button,
        OfferSummary
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
            this.offerDetails = this.offer
            this.show = true
            //get offer details

            //     let url = `/trade/market-place/CG/get-offer-details?offerId=${this.actionObjectDetails[0]}`;
            //     axios.get(url)
            //         .then(response => {
            //             console.log(response?.data);
            //             this.offerDetails = response?.data
            //             this.show = true;
            //         }).catch(error => {
            //             this.is_loading = false;

            //         });
            //     //get offer details
        }
    },
    props: ['actionObjectDetails', 'offer']
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
    background: #EAF5EA;
}

.pr-start-chat p {
    color: #2A9928;
}
</style>