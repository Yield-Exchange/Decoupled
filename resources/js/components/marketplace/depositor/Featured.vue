<template>
    <div class="mb-1">
        <b-row>
            <b-col v-if="pages > 1">
                <b-form-checkbox v-model="auto_play" class="pb-2" name="check-button" switch>
                    Pause
                </b-form-checkbox>
            </b-col>
            <b-col>
                <h3 class="text-right mt-3 p-0" style="font-weight: bold; font-size: 16px;color: #456cce">
                    <span>Legal Disclaimer</span>
                    <b-icon id="tooltip-target-1" icon="exclamation-circle-fill" variant="primary"/>
                    <b-tooltip target="tooltip-target-1" triggers="hover">
<!--                        <b>Disclaimer/disclosure to potential depositors re advertised rate offers</b><br/>-->
                        Rate offers on Yield Exchange are posted by participating financial institutions who may compensate Yield Exchange Inc. (“YEI”) when their deposit products are sold through the Yield Exchange platform.  A participating financial institution may compensate YEI to “feature” a rate offer and display it directly on your user dashboard.
                        YEI is not responsible for and does not make any representations or give advice as to rates, products or information posted by or linked on Yield Exchange by participating financial institutions.  Such rates, products or information are not recommendations or endorsements by YEI of any participating financial institution or their deposit products.
                        Displaying, ranking, or ordering of posted rate offers is also not a recommendation or endorsement of any particular deposit product or financial institution. YEI makes no representations to you as to the participating financial institution or the suitability of deposit products offered by participating financial institutions to meet your needs.
                        YEI is not, on your behalf or on behalf of participating financial institutions, acting as an agent or deposit broker.  Refer to your Yield Exchange Depositor User Account Terms & Conditions for more detail.
                    </b-tooltip>
                </h3>
            </b-col>
        </b-row>
        <b-row class="p-0">
            <b-col class="ml-2">
                <b-row class="p-0" v-if="!loading && current_data">
                    <FeaturedOfferCard @autoPlayStopped="autoPlayStopped = $event" @mouseover="cancelAutoPlay" @mouseleave="autoPlay"
                                       :data="datum" v-for="(datum, indx) in current_data" :key="indx" :index="indx"
                                       :buy_url="buy_url"
                                       ref="FeaturedCard"
                    />
                </b-row>
                <b-row class="text-center mb-2" v-if="!loading && pages > 1">
                    <b-col>
                        <div style="display: inline-block">
                            <b-icon @click="goToPage(index)" v-if="!loading"
                                    v-for="index in pages" :key="index"
                                    font-scale="1.3" icon="circle-fill" :style="'cursor: pointer;margin-right: 5px;'+(current_page===index ? 'color: #3656A6;' : 'color:#ccc')"/>
                        </div>
                    </b-col>
                </b-row>
                <b-row class="text-center mb-2">
                    <span style="font-weight: bold; font-size: 15px;color: #456cce;cursor: pointer;" @click="marketPlaceLink">Compare all rates</span>
                </b-row>
                <b-row v-if="loading">
                    <b-col>
                        <b-skeleton-table
                                :rows="4"
                                :columns="4"
                                :table-props="{ bordered: false, striped: false }"
                        />
                    </b-col>
                </b-row>
            </b-col>
        </b-row>
    </div>
</template>
<style>
    .tooltip-inner{
        max-width: 500px!important;
        background-color: white;
        color: black;
        font-weight: normal;
    }
    .btn-primary {
        color: #fff;
        background-color: #2CADF5;
        border: 1px solid transparent;
        padding: 0.4375rem 0.875rem;
        font-size: .8125rem;
        line-height: 1.5385;
        border-radius: 0.1875rem;
        font-weight: bold
    }

    .table-border-double td {
        border: none;
    }

    .dropdown-item {
        font-size: 14px;
    }

    .table thead tr th {
        border-bottom: 1px solid #b3b2b2 !important;
    }
</style>
<script>
    import FeaturedOfferCard from "./FeaturedOfferCard";
    export default {
        components: {FeaturedOfferCard},
        props: ['marketplace_url','buy_url'],
        created() {
            this.fetch();
        },
        computed: {
        },
        data() {
            return {
                offersData: null,
                loading: false,
                data_count: 0,
                pages: 1,
                current_data: null,
                refreshIntervalId: null,
                current_page: 1,
                autoPlayStopped: false,
                autoPlayEnabled: true,
                per_page:5,
                auto_play: false
            }
        },
        methods: {
            marketPlaceLink(){
                window.location.href='/depositor/market-place-offer';
            },
            cancelAutoPlay(){
                clearInterval(this.refreshIntervalId);
            },
            autoPlay(){
                let _this = this;
                _this.goToPage(1, true);

                if(this.pages < 1){
                    return;
                }

                if(!_this.autoPlayEnabled){
                    this.cancelAutoPlay();
                    return;
                }

                this.refreshIntervalId = setInterval(function () {
                    if(_this.autoPlayStopped || _this.auto_play ){
                        return;
                    }

                    let nextPage = _this.current_page + 1;
                    if(nextPage > _this.pages){
                        nextPage=1;
                    }
                    _this.goToPage(nextPage);
                }, 60000/5); // 1/5 of a minute
            },
            goToPage(page_number=1,first_init=false){
                if( this.offersData && this.offersData.hasOwnProperty('data') ){
                    this.current_data =  this.paginate(this.offersData.data, this.per_page, page_number);
                    this.current_page = page_number;
                    // if (!first_init) {
                        let _this = this;
                        // setTimeout(function () {
                        //     let FeaturedCard =  _this.$refs.FeaturedCard;
                        //     if(FeaturedCard) {
                        //         for (var i = 0; i < FeaturedCard.length; i++) {
                        //             _this.$refs.FeaturedCard[i].shakeCard();
                        //         }
                        //     }
                        // },50);
                    // }
                    return;
                }
                return;
            },
            paginate(array, page_size, page_number = 2) {
                // human-readable page numbers usually start with 1, so we reduce 1 in the first argument
                return array.slice((page_number - 1) * page_size, page_number * page_size);
            },
            fetch() {
                this.loading = true;
                axios.get(this.marketplace_url, {
                    params: {
                        getData: 1,
                        per_page: 5
                    },
                }).then(response => {
                    if(response?.data?.success){
                        this.populateData(response?.data);
                        this.loading = false;
                        return;
                    }

                    this.$swal({
                        title: 'Fetching data failed',
                        text: response?.data?.message,
                        confirmButtonText: 'Close'
                    });

                    this.loading = false;
                }).catch(error => {
                    let message;
                    if (error?.response?.status === 419) {
                        message = "The page has expired due to inactivity. Please refresh the page and try again.";
                    } else {
                        message = error?.response?.data?.message;
                    }

                    this.$swal({
                        title: 'Fetching data failed',
                        text: message,
                        confirmButtonText: 'Close'
                    });

                    this.loading = false;
                });
            },
            populateData(data){
                this.offersData = data;
                this.data_count = data?.data?.length;
                this.pages = Math.ceil(this.data_count / this.per_page);
                this.autoPlay();
            }
        }
    }
</script>