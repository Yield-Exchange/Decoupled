<template>
    <div class="row">
        <div class="col-md-12">
            <div class="card p-2">
                <h5 class="card-title text-black font-w-b px-3 py-1">{{ expired ? "Expired" : "Current" }} GIC Offer
                </h5>
                <div class=" card-body p-2">
                    <div class="row">
                        <div class="col-md-12">
                            <b-row v-if="loading">
                                <b-col>
                                    <b-skeleton-table :rows="4" :columns="4"
                                        :table-props="{ bordered: false, striped: false }" />
                                </b-col>
                            </b-row>

                            <b-row v-if="!loading && market_offers && market_offers.data" class="pl-3">
                                <m-offer-fi v-for="(market_offer, index) in market_offers.data" :key="index"
                                    :index="index" :isActive="true" :market_offer="market_offer" :products="products"
                                    :store_route="store_route" :organization="JSON.stringify(market_offer.organization)"
                                    :home_route="home_route" :featured_offer_route="featured_offer_route"
                                    :featured_offer="featuredOffer" :expired="expired">
                                </m-offer-fi>
                            </b-row>

                            <b-row v-if="!expired && (!market_offers || !market_offers.data) && !loading">
                                <m-offer-fi xclass="p-2" :isNotFeatured="true" :store_route="store_route"
                                    :products="products">
                                </m-offer-fi>
                                <m-offer-fi xclass="p-2" :isNotOffer="true" :store_route="store_route"
                                    :products="products" />
                            </b-row>
                        </div>
                    </div>
                    <b-row class="text-center mb-2" v-if="market_offers && market_offers.last_page > 1">
                        <b-col>
                            <div style="display: inline-block">
                                <b-icon
                                    v-if="link.url && !link.label.includes('Previous') && !link.label.includes('Next')"
                                    @click="$emit('fetchData',(link.url+'&getData=true'))"
                                    v-for="link in market_offers.links" :key="link.label" font-scale="1.3"
                                    :style="'cursor: pointer;margin-right: 5px;' + (link.active ? 'color: #3656A6;' : 'color:#ccc')"
                                    icon="circle-fill" style="cursor: pointer;margin-right: 5px" />
                            </div>
                        </b-col>
                    </b-row>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['market_offers', 'products', 'store_route', 'home_route', 'expired', 'loading', "featured_offer_route"],
    computed: {
    },
    data() {
        return {
            "featuredOffer": ""
        }
    },
    mounted() {
        this.getFeaturedOffer();
    },
    methods: {
        getFeaturedOffer() {

            axios.post(JSON.parse(this.featured_offer_route), {})
                .then(response => {

                    if (response?.data?.data) {
                        this.featuredOffer = response?.data?.data;
                    } else {
                        throw new Error(response.data.message)
                    }

                }).catch(error => {
                    console.log(error, "error")
                });

        }
    }
}
</script>