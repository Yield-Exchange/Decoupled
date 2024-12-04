<template>
    <div class="w-100">
        <div class="w-100 d-flex flex-row flex-wrap justify-content-start" style="gap: 20px;" v-if="offers">
            <div class="" v-for="offer in offers" :key="offer?.encoded_id">
                <OfferCard :offer="offer"></OfferCard>
            </div>

        </div>
    </div>

</template>


<script>
import OfferCard from './OfferCard.vue';
export default {
    props: ['data'],
    beforeMount() {
        if (this.data) {
            this.offers = this.data
            console.log(this.data, "Tile data")
        }
    },
    components: {
        OfferCard
    },
    data() {
        return {
            offers: null
        }
    }
}
</script>