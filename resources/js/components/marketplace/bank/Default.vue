<template>
    <div>

        <div class="row">
            <div class="col-md-12">
                <div class="card p-3 pe-auto" @click="$emit('openCreateModel')" id="back">
                    <div class="d-flex">
                        <div class="">
                            <m-add-offer-model :products="products" :organization="organization"
                                :store_route="store_route" :modal-id="(new Date()).getTime()" />
                        </div>
                        <div>
                            <small class="text-black text-uppercase">Promote your offer directly with our
                                depositors</small>
                            <h5 class="text-black font-w-b">Create a new GIC Offer</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <b-row v-if="render_default_component">
            <MarketOffersContainer v-if="(!market_offers || market_offers.data.length === 0)
            && (!expired_market_offers || expired_market_offers.data.length === 0)"
                :featured_offer_route="featured_offer_route" />
        </b-row>

        <b-row>
            <MarketOffersContainer @fetchData="getData($event)" v-if="market_offers && market_offers.data.length > 0"
                :loading="loading" :market_offers="market_offers" :products="products" :store_route="store_route"
                :home_route="home_route" :expired="false" :featured_offer_route="featured_offer_route" />
        </b-row>

        <b-row>
            <MarketOffersContainer @fetchData="getData($event,true)"
                v-if="expired_market_offers && expired_market_offers.data.length > 0" :loading="loading_expired"
                :market_offers="expired_market_offers" :products="products" :store_route="store_route"
                :home_route="home_route" :expired="true" :featured_offer_route="featured_offer_route" />
        </b-row>

    </div>
</template>

<style>
li.page-item a.page-link {
    background-color: lightgray;
    border-radius: 100% !important;
    height: 20px;
    margin: 7px;
    width: 15px !important;
}

.text-black {
    color: #000000;
}

.font-w-b {
    font-weight: 900;
}

.p15 {
    padding: 15px;
}

.font-s-12 {
    font-size: 1.2em;
}

.image {
    width: 60px;
    border-radius: 100%;
    margin: 2px;
}

.rate {
    font-size: 2.5em;
    vertical-align: middle;
    margin: 2px;
}

.w-20 {
    width: 20em;
}

.pe-auto {
    cursor: pointer;
}
</style>


<script>

import MarketOffersContainer from "./MarketOffersContainer";
export default {
    components: { MarketOffersContainer },
    props: [
        "products", "organization", "store_route", "home_route", "get_data_route", "auth_user", "featured_offer_route", "accepted_market_place_offer_url"
    ],
    data() {
        return {
            market_offers: null,
            expired_market_offers: null,
            per_page: 10,
            loading: false,
            loading_expired: false,
            render_default_component: false
        }
    },
    mounted() {
        if(!JSON.parse(this.organization).accepted_market_place_offer) {
            this.$swal({
                title: "Acknowledgement of FIs to applicability of commission to sales via unsolicited rate offers",
                text: "As an authorized user of a Financial Institution (“FI”) account, you have the ability to post rates in the Yield Exchange Marketplace on behalf of your FI, By posting this rate you acknowledge and agree on behalf of your FI that if a potential depositor accepts this rate offer through Yield Exchange and completes the purchase, commission will be payable by FI to Yield Exchange Inc. on such transaction in the amount set out in the FI Participation Agreement between the FI and Yield Exchange Inc.",
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
                confirmButtonColor: '#4975E3',
                cancelButtonColor: '#E9ECEF',
                customClass: {
                    actions: 'swal-button-actions',
                    confirmButton: 'custom-primary round',
                    cancelButton: 'custom-secondary round'
                }
            }).then((response) => {
                if (response.isConfirmed) {
                    axios.post(this.accepted_market_place_offer_url)
                        .then(response => {
                            window.location.reload();
                        }).catch(error => {
                            window.location.href = "/dashboard";
                            return;
                        });
                } else {
                    window.location.href = "/dashboard";
                    return;
                }
            });
        }

        this.getData(JSON.parse(this.get_data_route) + "?getData=true");
        this.getData(JSON.parse(this.get_data_route) + "?getData=true", true);
        this.delayedShowDefaultPage();
    },
    computed: {
    },
    methods: {
        delayedShowDefaultPage() {
            setTimeout(() => {
                this.render_default_component = true;
            }, 50);
        },
        getData(url, expired = false) {
            if (expired) {
                this.loading_expired = false;
            } else {
                this.loading = false;
            }
            axios.get(url + "&status=" + (expired ? 'EXPIRED' : 'ACTIVE'))
                .then(response => {
                    if (expired) {
                        this.loading_expired = false;
                    } else {
                        this.loading = false;
                    }

                    if (response?.data?.data) {
                        if (expired) {
                            this.expired_market_offers = response?.data?.data;
                            return;
                        }
                        this.market_offers = response?.data?.data;
                    }

                }).catch(error => {
                    if (expired) {
                        this.loading_expired = false;
                    } else {
                        this.loading = false;
                    }
                });
        }
    },

}

</script>
