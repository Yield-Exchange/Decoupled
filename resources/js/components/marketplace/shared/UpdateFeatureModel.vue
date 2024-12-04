<template>
    <div>

        <m-button text="Feature" link="#" type="secondary" c-style="font-size: 14px"
            xclass="font-weight-bold  btn-xs font-weight-bold font-s-1 float-start mt-3" data-toggle="modal"
            :data-target="'#updateFeatured' + offer.id" />
        <!-- Modal -->
        <div class="modal fade" :id="'updateFeatured' + offer.id" tabindex="-1" role="dialog"
            :aria-labelledby="'updateFeaturedLabel' + offer.id" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content text-center w-45 rounded">
                    <div class="modal-header text-center">

                        <h6 class="text-center m-1-27 text-black font-w-b">Update your GIC Offer</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex flow-column">
                            <div>
                                <div class="bg-gray pl-2 pr-2 mb-2 py-2 rounded" style="min-height: 263px;">
                                    <h5 class="text-black font-w-b my-3 mb-4">Current Featured <br /> Product</h5>
                                    <FICard :market_offer="getFeaturedOffer" :organization="organization" />
                                </div>
                                <m-button text="Cancel" type="secondary" link="#" data-dismiss="modal"
                                    aria-label="Close" />
                            </div>
                            <div>
                                <div class="rounded mb-2 py-2">
                                    <h5 class="text-black font-w-b my-3 mb-4 ">New Featured <br />Product</h5>
                                    <FICard :market_offer="offer" :organization="organization" />
                                </div>
                                <m-button text="Save" type="primary" link="#" @click="updateFeaturedOffer"
                                    :loading="loading" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<style>
.rounded {
    border-radius: 0.85rem !important;
}

.w-45 {
    width: 45em !important;
}

.bg-gray {
    background: #f2f2f2;
}

.text-black {
    color: #000000;
}

.font-w-b {
    font-weight: 900;
}

.m-1-27 {
    margin: 1% 27%;
}

.p15 {
    padding: 15px;
}

.font-s-12 {
    font-size: 1.2em;
}

.font-s-13 {
    font-size: 13px !important;
}

.font-s-17 {
    font-size: 17px !important;
}

.font-s-20 {
    font-size: 30px !important;
}

.font-s-1 {
    font-size: .8em;
}

.button-primary {
    color: #fff;
    background-color: #3656A6;
    border-color: #3d66cd;
}

.button-primary:hover {
    color: #fff;
    background-color: #456cce;
    border-color: #366aee;
}
</style>


<script>
import Avatar from "vue-avatar";
import FICard from "./FICard.vue";
export default {
    components: {
        Avatar,
        FICard,
    },
    props: [
        'offer', 'organization', 'store_route', 'featured_offer_route', 'featured_offer'
    ],
    created: function () {
        this.$parent.$on('openCreateModel', this.openModel);
    },
    data() {
        return {
            "expiredDate": "",
            "featuredOfferRoute": JSON.parse(this.featured_offer_route),
            "loading": false
        }
    },
    mounted() {
        if (this.offer) this.storeRoute = this.storeRoute + "/" + this.offerId;
    },

    computed: {
        getOffer() {
            return this.offer ? this.offer : "";
        },
        parseOrganization() {
            return this.organization ? JSON.parse(this.organization) : [];
        },

        getFeaturedOffer() {
            return (this.featured_offer) ? this.featured_offer : "";
        },

    },

    methods: {
        openModel() {
            var modelBox = document.getElementById("createOffer");
            if (!modelBox.classList.contains('show')) {
                var modelBtn = document.getElementById("createOfferBtn");
                modelBtn.click()
            }

        },
        getTermLength(length, type) {
            var captalizedType = type.charAt(0).toUpperCase() + type.substring(1).toLowerCase();
            return Math.round(length) + " " + captalizedType;
        },
        updateFeaturedOffer() {
            this.loading = true;

            const data = new FormData();
            data.append("new_featured", this.offer.encoded_id);

            axios.post(JSON.parse(this.featured_offer_route), data)
                .then(response => {

                    if (response.data?.success) {
                        this.$swal({
                            title: 'Offer Featured successfully.',
                            text: response.data?.message,
                            confirmButtonText: 'Close'
                        }).then(() => {
                            this.loading = false;
                            window.location.reload();
                        });
                    }

                }).catch(error => {

                    console.log(error, "error")

                });
        }

    },

}

</script>
