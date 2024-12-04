<template>
    <div v-if="!initializing && userCan(this.userloggedin, 'depositor/my-offers---campaigns/page-access')"
        style="display: flex; flex-direction: column; gap: 20px; justify-content: flex-start; align-items: flex-start">
        <template v-if="!is_summary">
            <div v-if="userCan(this.userloggedin, 'depositor/my-offers---campaigns/featured')"
                style="width: 100%; height: 100%; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex">
                <div
                    style="width: 100%; padding-left: 3px; background: #EFF2FE; justify-content: flex-start; align-items: center; display: inline-flex">
                    <div style="justify-content: space-between; display: inline-flex; width: 99%">
                        <div class="page-title">
                            <div class="title-icon">
                                <img src="/assets/dashboard/icons/PromoOffer.svg" />
                            </div>
                            <div class="text-div">Featured Offers</div>
                        </div>
                        <div @click="toggleView(1)"
                            style="justify-content: flex-end !important; align-items: center; gap: 9px; display: flex; cursor: pointer;">
                            <div
                                style="text-align: center; color: #252525; font-size: 14px; font-weight: 500; line-height: 18px; word-wrap: break-word">
                                View {{ viewMore1 ? 'Less' : 'More' }}</div>
                            <img v-if="viewMore1" src="/assets/dashboard/icons/Polygon.svg" />
                            <img v-else src="/assets/dashboard/icons/Polygon 2.svg" />
                        </div>
                    </div>
                </div>
                <div v-if="viewMore1"
                    style="width: 100%; padding-left: 2px; justify-content: flex-start; display: flex; flex-direction: column">
                    <div style="display: flex; flex-direction: row; justify-content: space-between">
                        <FeaturedFilterBox :filtered="featured_filtered" @apply_filters="submitFilters($event, true)"
                            @clear_filters="clearFilters(true)" @searching="search($event, true)"
                            :industries="industries" filterType='featured-products' :products="products"
                            :fiorganizations="fiorganizations">
                        </FeaturedFilterBox>
                        <div
                            style="margin-left: 20px; width: auto; display: flex; justify-content: center; align-items: center; flex-direction: column; height: 50px;">
                            <div style="background: #EFF2FE; padding: 5px">
                                <div @click="discalimerModel = true"
                                    style="color: #5063F4; font-size: 12px; font-weight: 500; word-wrap: break-word">
                                    Advertising&nbsp;Disclosure</div>
                            </div>
                        </div>
                    </div>
                    <div v-if="featured_data && featured_data?.data?.data.length > 0"
                        style="flex-direction: column; justify-content: flex-start; align-items: flex-start; display: inline-flex">
                        <div style="align-items: center; display: inline-flex;flex-wrap: wrap; gap: 5px">
                            <FeaturedOffers :key="index" :datum="datum"
                                v-for="(datum, index) in featured_data?.data?.data"
                                v-if="featured_data && featured_data?.data" :formattedtimezone="formattedtimezone" />
                        </div>
                    </div>
                    <NoData title="No Featured Offers Found" message="Featured offers by banks will be listed here"
                        v-else />
                    <div
                        style="width: 100%; height: 100%; justify-content: center; align-items: center; gap: 9px; display: inline-flex;">
                        <div v-for="(datum, index) in featured_data?.data?.links" :key="index"
                            v-if="datum.url && validLink(datum.label)">
                            <div v-if="datum.active"
                                style="width: 40px; height: 7px; background: #44E0AA; border-radius: 10px;cursor: pointer"
                                @click="getData(datum.url, true)"></div>
                            <div v-else
                                style="width: 20px; height: 7px; background: #9CA1AA; border-radius: 10px;cursor: pointer"
                                @click="getData(datum.url, true)"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="userCan(this.userloggedin, 'depositor/my-offers---campaigns/offers')"
                style="width: 100%; height: 100%;flex-direction: column; justify-content: flex-start; align-items: flex-start;display: inline-flex">
                <div
                    style="width: 100%; background: #EFF2FE; padding-left: 2px; justify-content: flex-start; align-items: center; display: inline-flex">
                    <div style="justify-content: space-between; display: inline-flex; width: 99%;">
                        <div class="page-title">
                            <div class="title-icon">
                                <img src="/assets/dashboard/icons/PromoOffer.svg" />
                            </div>
                            <div class="text-div">Offers</div>
                        </div>
                        <div @click="toggleView(2)"
                            style="justify-content: flex-start; align-items: center; gap: 9px; display: flex;cursor: pointer">
                            <div
                                style="text-align: center; color: #252525; font-size: 14px; font-weight: 500; line-height: 18px; word-wrap: break-word">
                                View {{ viewMore2 ? 'Less' : 'More' }}</div>
                            <img v-if="viewMore2" src="/assets/dashboard/icons/Polygon.svg" />
                            <img v-else src="/assets/dashboard/icons/Polygon 2.svg" />
                        </div>
                    </div>
                </div>
                <div v-if="viewMore2" style="justify-content: flex-end; display: flex;width: 100%; padding-top: 10px">
                    <div style="width: 75%;">
                        <FilterBox :filtered="filtered" @apply_filters="submitFilters($event, false)"
                            @clear_filters="clearFilters(false)" filterType='products' @searching="search"
                            :products="products" :fiorganizations="fiorganizations">
                        </FilterBox>
                    </div>
                    <div style="display: flex;gap: 10px;padding-left: 10px;padding-top: 5px">
                        <div v-bind:class="offers_view_type === 'list' ? 'activeView' : 'inActiveView'"
                            :style="'height: 40px;padding: 10px; border-radius: 8px; justify-content: flex-start; align-items: center; gap: 9px; display: flex; cursor: pointer'"
                            @click="updateOfferView('list')">
                            <img v-if="(offers_view_type === 'list')" src="/assets/dashboard/icons/Vector (3).svg" />
                            <img v-if="(offers_view_type === 'tile')"
                                src="/assets/dashboard/icons/Vector (3)-grey.svg" />
                            <div
                                style="font-size: 16px; font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                                List View</div>
                        </div>
                        <div v-bind:class="offers_view_type === 'tile' ? 'activeView' : 'inActiveView'"
                            style="height: 40px;padding: 10px; border-radius: 8px; justify-content: flex-start; align-items: center; gap: 9px; display: flex; cursor: pointer"
                            @click="updateOfferView('tile')">
                            <img v-if="(offers_view_type === 'tile')" src="/assets/dashboard/icons/Vector (4).svg" />
                            <img v-if="(offers_view_type === 'list')"
                                src="/assets/dashboard/icons/Vector (4)-grey.svg" />
                            <div
                                style="font-size: 16px; font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                                Tile View</div>
                        </div>
                    </div>
                </div>
                <div v-if="viewMore2"
                    style="justify-content: flex-start; padding-left: 2px;  align-items: flex-start; display: inline-flex;width: 100%;">
                    <Offers :view="offers_view_type" :data="data?.data" v-if="data && data?.data"
                        :formattedtimezone="formattedtimezone" />
                </div>

                <div v-if="viewMore2" style="width:98%">
                    <Pagination @click-next-page="getData" v-if="data && data?.data?.links" :data="data?.data" />
                </div>
            </div>

            <!-- Trends  -->
            <div v-if="userCan(this.userloggedin, 'depositor/my-offers---campaigns/trends')"
                style="width: 100%; height: 100%;flex-direction: column; justify-content: flex-start; align-items: flex-start;display: inline-flex">
                <div
                    style="width: 100%; background: #EFF2FE; padding-left: 2px; justify-content: flex-start; align-items: center; display: inline-flex">
                    <div style="justify-content: space-between; display: inline-flex; width: 99%;">
                        <div class="page-title">
                            <div class="title-icon">
                                <img src="/assets/dashboard/icons/PromoOffer.svg" />
                            </div>
                            <div class="text-div">Trends</div>
                        </div>
                        <div @click="toggleView(3)"
                            style="justify-content: flex-start; align-items: center; gap: 9px; display: flex;cursor: pointer">
                            <div
                                style="text-align: center; color: #252525; font-size: 14px; font-weight: 500; line-height: 18px; word-wrap: break-word">
                                View {{ viewMore3 ? 'Less' : 'More' }}</div>
                            <img v-if="viewMore3" src="/assets/dashboard/icons/Polygon.svg" />
                            <img v-else src="/assets/dashboard/icons/Polygon 2.svg" />
                        </div>
                    </div>
                </div>

                <div v-if="viewMore3" class="w-100 mt-3">
                    <TrendsOffers></TrendsOffers>
                </div>
            </div>
            <DisclaimerModal @closedSuccessModal="discalimerModel = false" :showm="discalimerModel"> </DisclaimerModal>

        </template>

        <template v-else>
            <Summary v-if="summary_data" :organization_id="organization_id" :is_summary="is_summary"
                :datum="summary_data" />
            <div v-else
                style="width: 100%; height: 300px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                <div>
                    <p style="font-size: 30px">Please wait...</p>
                </div>
            </div>
        </template>
    </div>
</template>

<style scoped>
.activeView {
    border: 0.50px #5063F4 solid;
    color: #050505;
    color: #5063F4;
}

.inActiveView {

    /* background: white; */
    border: 0.50px #9CA1AA solid;
    /* color: #5063F4; */
    color: #9CA1AA;
}

.inActiveView:hover {
    border: 0.50px #5063F4 solid;
    color: #050505;
    color: #5063F4;
}
</style>

<script>
import FilterBox from "../../shared/Table/FilterBox";
import FeaturedFilterBox from "../../shared/Table/FilterBox";
import CustomSelect from "../../shared/CustomSelect";
import Offers from "./Offers";
import FeaturedOffers from "./FeaturedOffers";
import Pagination from "../../shared/Table/Pagination";
import Summary from "./single-offer/Summary";
import NoData from "../../shared/Table/NoData";
import DisclaimerModal from "../../shared/messageboxes/DisclaimerModal";
import TrendsOffers from "./TrendsOffers.vue";
import { userCan } from '../../../utils/GlobalUtils';
export default {
    mounted() {
        this.initializing = true;
        let this__ = this;
        document.addEventListener("DOMContentLoaded", function () {
            // Get the screen width and height in pixels
            this__.screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

            if (this__.is_summary) {
                this__.getData('/inv-camp-offers-fetch-data?campaign_product_id=' + this__.is_summary, false, true);
            } else {
                this__.getData();
                this__.getData("/inv-camp-offers-fetch-data?", true);
            }
        });
    },
    components: {
        NoData,
        FilterBox,
        CustomSelect,
        Offers,
        FeaturedOffers,
        Pagination,
        Summary,
        FeaturedFilterBox,
        TrendsOffers,
        DisclaimerModal
    },
    props: ['products', 'is_summary', 'industries', 'fiorganizations', 'organization_id', 'userloggedin', 'formattedtimezone'],
    data() {
        return {
            term_length_filter: null,
            product_type_filter: null,
            featured_filtered: [],
            filtered: [],
            data: null,
            featured_data: null,
            per_page: 5,
            offers_view_type: 'tile',
            summary_data: null,
            viewMore1: true,
            viewMore2: true,
            viewMore3: false,
            icon1tile: true,
            icon2tile: false,
            icon1list: true,
            icon2list: false,
            screenWidth: null,
            initializing: false,
            discalimerModel: false
        }
    },
    methods: {
        userCan(user, permission) {
            return userCan(user, permission);
        },
        toggleView(index) {
            if (index === 1)
                this.viewMore1 = !this.viewMore1;
            else if (index === 2)
                this.viewMore2 = !this.viewMore2
            else
                this.viewMore3 = !this.viewMore3

        },
        updateOfferView(type) {
            this.offers_view_type = type;
        },
        getItemCount() {
            let card_nos = Math.floor((this.screenWidth * 60 / 100) / 226);
            if (card_nos < 1) {
                card_nos = 1;
            }
            return card_nos;
        },
        getData(url, is_featured = false, is_summary_data = false) {
            url = url ? url : "/inv-camp-offers-fetch-data?";
            if (is_featured) {
                let card_nos = Math.floor((this.screenWidth * 60 / 100) / 226);
                if (card_nos < 1) {
                    card_nos = 1;
                }
                url = url + "&isFeatured=1&per_page=" + card_nos;
            } else {
                let itemstoshow = this.getItemCount()
                url = url + "&per_page=" + itemstoshow * 2;
            }

            let this_ = this;
            axios.get(url)
                .then(response => {
                    if (is_summary_data) {
                        this.summary_data = response?.data?.data?.data && response?.data?.data?.data?.length > 0 ? response?.data?.data?.data[0] : null;
                        this_.initializing = false;
                        return;
                    }

                    if (is_featured) {

                        if (response?.data?.data?.data.length === 0) {
                            this.viewMore1 = true;
                            this.viewMore2 = true;
                        } else {
                            this.viewMore2 = true;
                        }
                        this_.featured_data = response?.data;

                        this_.initializing = false;
                        return;
                    }
                    this_.data = response?.data;
                    this_.initializing = false;
                }).catch(error => {
                    if (is_featured) {
                        this_.featured_data = null;
                        this_.initializing = false;
                        return;
                    }

                    this_.data = null;
                    this_.table_data = null;
                    this_.initializing = false;
                });
        },
        search(value, is_featured = false) {
            if (is_featured) {
                this.getData("/inv-camp-offers-fetch-data?isFeatured=1&search=" + value);
                return;
            }
            this.getData("/inv-camp-offers-fetch-data?search=" + value);
        },
        submitFilters(value, is_featured = false) {
            if (is_featured) {
                this.getData("/inv-camp-offers-fetch-data?isFeatured=1&search=&" + value, true);
                return;
            }
            this.getData("/inv-camp-offers-fetch-data?search=" + value);
            console.log(value, "Not Featured")
        },
        filterInputChanges(value, key, is_featured = false) {
            let valuesToCheck = [];
            switch (key) {
                case 'term_length_filter':
                    valuesToCheck = ['MONTHS', 'DAYS'];
                    break;
                case 'product_type_filter':
                    Object.values(this.products).forEach((item) => {
                        valuesToCheck.push(item.description);
                    });

                    let fv = Object.values(this.products).filter((item => {
                        return item.id == value
                    }));

                    value = fv.length > 0 ? fv[0].description : value;
                    break;
            }

            if (is_featured) {
                this.featured_filtered = this.featured_filtered.filter(item => !valuesToCheck.includes(item));

                if (!this.featured_filtered.includes(value)) {
                    this.featured_filtered.push(value);
                }

                return;
            }

            this.filtered = this.filtered.filter(item => !valuesToCheck.includes(item));

            if (!this.filtered.includes(value)) {
                this.filtered.push(value);
            }
        },
        clearFilters(value) {
            this.getData('', value)
        },
        validLink(label) {
            if (label.includes('Next')) {
                return false;
            }

            if (label.includes('Previous')) {
                return false;
            }
            return label;
        }
    }
}
</script>