<template>
    <b-col :class="getClass" :style="'min-width: 270px;max-width: 300px;margin-right: 2%'">

        <div class="card text-center">
            <div class="card-body p-2">
                <div class="box p-3 m-3">
                    <div v-if="isActive && market_offer">
                        <OfferTopCard :data="market_offer" />
                    </div>

                    <!-- Empty Featured Offer -->
                    <div v-if="isNotFeatured">
                        <h5 class="font-w-b text-black mb-5 mx-2">No Featured GIC offer selected</h5>
                        <p class="card-text my-1 text-gray mt-5 mx-2">Select a GIC offer to promote to all depositor
                        </p>
                    </div>

                    <!-- Empty Offer -->
                    <div v-if="isNotOffer">
                        <h5 class="font-w-b text-black mb-5 mx-2">No GIC Offer Created</h5>
                        <p class="card-text my-1 text-gray mt-5 mx-2">Create a GIC offer above to share with our
                            depositors
                        </p>
                    </div>
                </div>

                <div class="my-1">

                    <template
                        v-if="!market_offer || market_offer.is_featured && market_offer.status === 'EXPIRED' || market_offer.status === 'ACTIVE'">
                        <m-update-feature-model v-if="market_offer && !market_offer.is_featured" :offer="market_offer"
                            :organization="organization" :featured_offer_route="featured_offer_route"
                            :featured_offer="featured_offer">
                        </m-update-feature-model>
                        <m-button text="Featured" link="#" type="secondary" c-style="font-size: 14px"
                            xclass="float-start btn-xs font-weight-bold border-1-green mt-3" v-else>
                        </m-button>
                    </template>

                    <m-add-offer-model :products="products" :organization="organization" :store_route="home_route"
                        :offer="market_offer" v-if="market_offer" :expired="expired">
                    </m-add-offer-model>
                    <m-button text="Update" link="#" type="primary" c-style="font-size: 14px"
                        xclass="float-end btn-xs font-weight-bold mt-3" v-else>
                    </m-button>
                    <div class="disable-btn overlay" v-if="!market_offer" style="height: 65px;"></div>
                </div>

            </div>
        </div>

    </b-col>
</template>

<style scoped>
.dashboard-body .card {
    border-radius: 10px;
}

.text-black {
    color: #525252 !important;
}

.text-gray {
    color: #707070;
}

.font-w-b {
    font-weight: 900;
}

.font-s-1 {
    font-size: .8em;
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

.w-25-f {
    width: 21em;
}

.disable-btn {
    background: rgba(255, 255, 255, 0);
    border-radius: 15px;
    backdrop-filter: blur(2.5px);
    -webkit-backdrop-filter: blur(2.5px);
    border: 1px solid rgba(0, 0, 0, 0.125);
    height: 42px;
}

.box {
    border: 1px solid #dddddd;
    border-radius: 10px;
    margin: 0px 10px;
}

.border-1-green {
    border: 2px solid #44E0AA !important;
}
</style>


<script>

import OrganizationAvatar from "../../shared/OrganizationAvatar";
import OfferTopCard from "./OfferTopCard";
export default {
    name: 'OfferFI',
    components: {
        OfferTopCard,
        OrganizationAvatar
    },
    props: ["xclass", "isNotFeatured", "isNotOffer", "isActive", "market_offer", "products", "organization", "store_route", "home_route", "index", "featured_offer_route", "featured_offer", "expired"],

    data() {
        return {
            "expiredDate": ""
        }
    },
    computed: {
        getStoreRoute() {
            return (this.store_route) ? this.store_route : "";
        },

        getClass() {
            return (this.xclass) ? this.xclass : "";
        },

        parseOrganization() {
            return (this.organization) ? JSON.parse(this.organization) : "";
        },

        formatExpiryDate(id) {
            return id;
        }

    },

    methods: {
        getTermLength(length, type) {
            var captalizedType = type.charAt(0).toUpperCase() + type.substring(1).toLowerCase();
            return Math.round(length) + " " + captalizedType;
        }
    },

}

</script>
