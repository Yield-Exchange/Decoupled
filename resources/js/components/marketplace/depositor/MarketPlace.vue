<template>
    <div>
        <b-card no-body class="text-center pt-2 pb-3 card-wrapper">
            <b-row>
                <h3 style="font-size: 20px;font-weight: 600;color: black">Investment Details.</h3>
                <p class="mb-1" style="font-size: 13px;font-weight: normal">Update criteria to filter all available GICs.</p>
                <div class="mb-1 mt-0" @click="clearFilters" style="color: #3656A6;cursor:pointer;text-decoration: underline">Clear filters</div>
            </b-row>
            <b-row>
                    <b-container>
                        <b-row>
                            <b-col cols="3">
                                <CustomSelect :attributes="{ 'value_field':'id','text_field':'name' }"
                                              c-style="font-weight: 400;border-radius: 10px"
                                              :data="banksParsed"
                                              id="bank_id"
                                              name="Fi Organizations"
                                              :has-validation="false"
                                              :default-value="bank_id"
                                              @selectChanged="filter('bank_id',$event,true)"
                                />
                            </b-col>
                            <b-col cols="3">
                                <CustomInputGroup
                                        appended-style="width: 30%"
                                        input-style="width: 70%"
                                        append="prepend"
                                        :append-attributes="{ 'value_field':'id','text_field':'description' }"
                                        append-id="currency"
                                        append-name="Currency"
                                        c-style="font-weight: 400"
                                        :data="currency_options"
                                        id="investment_amount"
                                        name="Investment Amount"
                                        :has-validation="false"
                                        @selectChanged="filter('currency',$event,true)"
                                        @inputChanged="filter('amount',$event)"
                                        @onKeyUp="filter('amount',$event, true)"
                                        :append-default-value="currency"
                                        :input-default-value="amount"
                                        input-type="number"
                                />
                            </b-col>
                            <b-col cols="3">
                                <CustomInputGroup
                                        appended-style="width: 40%"
                                        input-style="width: 60%"
                                        append="prepend"
                                        append-id="term_length_type"
                                        append-name="Term Length Type"
                                        c-style="font-weight: 400;"
                                        :data="term_length_type_options"
                                        id="term_length"
                                        name="Term Length"
                                        :has-validation="false"
                                        @onKeyUp="filter('term_length',$event, true)"
                                        @inputChanged="filter('term_length',$event)"
                                        @selectChanged="filter('term_length_type',$event, true)"
                                        :append-default-value="term_length_type"
                                        :input-default-value="term_length"
                                        input-type="number"
                                />
                            </b-col>
                            <b-col cols="3">
                                <CustomSelect :attributes="{ 'value_field':'id','text_field':'description' }"
                                              c-style="font-weight: 400;border-radius: 10px"
                                              :data="productsParsed"
                                              id="product_id"
                                              name="Product"
                                              :has-validation="false"
                                              :default-value="product_id"
                                              @selectChanged="filter('product_id',$event,true)"
                                />
                            </b-col>
                        </b-row>
                    </b-container>
            </b-row>
        </b-card>

        <b-row class="mt-2" style="height: 65vh; overflow-y: auto; padding-bottom: 50px">
            <b-container>
                <b-row v-if="!loading && noResultsFound">
                    <p style="font-weight: normal;text-align: center;font-size: 14px;" v-show="check_filter">0 results found. <br/> Below are some offers that might interest you..</p>
                    <b-row v-if="suggestions && suggestions.hasOwnProperty('data')">
                        <OfferCard :filters="filters" :buy_url="buy_url" :data="datum" v-for="(datum, indx) in suggestions.data" :key="indx" :index="indx" />
                        <b-row class="text-center">
                            <b-col>
                                <div v-if="" style="display: inline-block" class="mr-4" >
                                    <b-icon v-if="suggestions.hasOwnProperty('prev_page_url') && suggestions.prev_page_url" @click="pageButtonClick(suggestions,'prev_page_url')" font-scale="2" icon="chevron-left" style="cursor: pointer"/>
                                </div>
                                <div style="display: inline-block" v-if="suggestions.hasOwnProperty('next_page_url') && this.suggestions.next_page_url">
                                    <b-icon font-scale="2" @click="pageButtonClick(suggestions,'next_page_url')" icon="chevron-right" style="cursor: pointer"/>
                                </div>
                            </b-col>
                        </b-row>
                    </b-row>
                </b-row>
                <b-row v-if="!loading && offersData && offersData.hasOwnProperty('data')">
                    <OfferCard :filters="filters" :buy_url="buy_url" :data="datum" v-for="(datum, indx) in offersData.data" :key="indx" :index="indx" />
                    <b-row class="text-center">
                        <b-col>
                            <div v-if="" style="display: inline-block" class="mr-4" >
                                <b-icon v-if="offersData.hasOwnProperty('prev_page_url') && offersData.prev_page_url" @click="pageButtonClick(offersData,'prev_page_url')" font-scale="2" icon="chevron-left" style="cursor: pointer"/>
                            </div>
<!--                            <div style="display: inline-block" v-if="offersData.hasOwnProperty('next_page_url') && this.offersData.next_page_url">-->
                            <!-- <div style="display: flex" class="mr-4" >
                                <b-icon v-if="offersData.hasOwnProperty('prev_page_url') && offersData.prev_page_url" @click="pageButtonClick(offersData,'prev_page_url')" font-scale="3" icon="chevron-left" style="cursor: pointer"/>
                            </div> -->
                            <div style="display: flex" v-if="offersData.hasOwnProperty('next_page_url') && this.offersData.next_page_url">
                                <b-icon font-scale="2" @click="pageButtonClick(offersData,'next_page_url')" icon="chevron-right" style="cursor: pointer"/>
                            </div>
                        </b-col>
                    </b-row>
                </b-row>
                <b-row v-if="loading">
                    <b-col>
                        <b-skeleton-table
                                :rows="10"
                                :columns="4"
                                :table-props="{ bordered: false, striped: false }"
                        />
                    </b-col>
                </b-row>
            </b-container>
        </b-row>
    </div>
</template>
<style scoped>
    .card-wrapper{
        border-radius: 15px!important;
        box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.25);
        -webkit-box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.25);
        -moz-box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.25);
    }
</style>
<script>
    import OfferCard from "./OfferCard";
    import CustomInputGroup from "../../shared/CustomInputGroup";
    import CustomSelect from "../../shared/CustomSelect";
    import NoResultsFound from "../../shared/NoResultsFound";
    export default {
        components: {NoResultsFound, CustomInputGroup, CustomSelect, OfferCard},
        props: ['products','banks','market_place_filters','marketplace_url','buy_url'],
        data() {
            let market_place_filters = JSON.parse(this.market_place_filters);
            return {
                productsParsed: JSON.parse(this.products),
                banksParsed: JSON.parse(this.banks),
                term_length_type_options: ['All','DAYS','MONTHS'],
                term_length_type: market_place_filters.term_length_type,
                term_length: market_place_filters.term_length,
                product_id: market_place_filters.product_id > 0 ? market_place_filters.product_id : 'all',
                amount: market_place_filters.amount,
                currency: market_place_filters.currency,
                currency_options:  ['All','CAD','USD'],
                bank_id: market_place_filters.fi_organization_id ? market_place_filters.fi_organization_id : 'all',
                loading: false,
                offersData: null,
                noResultsFound: false,
                suggestions: null,
                filters: null,
                check_filter: false,
            }
        },
        created() {
            this.submit();
        },
        computed: {

        },
        methods:{
            clearFilters(){
                this.term_length_type = 'All';
                this.term_length = null;
                this.product_id = 'all';
                this.amount = '';
                this.currency = 'All';
                this.bank_id='all';
                this.check_filter=false
                this.submit();
            },
            pageButtonClick(offer,action){
                if(action==="prev_page_url"){
                    this.submit(offer.prev_page_url);
                    return;
                }
                this.submit(offer.next_page_url);
            },
            filter(field, value, fetch=false) {
                switch (field) {
                    case 'term_length_type':
                        this.term_length_type  = value;
                        break;
                    case 'term_length':
                        this.term_length = value;
                        break;
                    case 'product_id':
                        this.product_id = value;
                        break;
                    case 'amount':
                        this.amount = value;
                        break;
                    case 'currency':
                        this.currency = value;
                        break;
                    case 'bank_id':
                        this.bank_id = value;
                        break;
                }
                if(fetch) {
                    this.check_filter = true;
                    this.submit();
                }
            },
            submit: _.debounce(function (url=null) {
                this.loading = true;
                axios.get(url==null ? this.marketplace_url :url, {
                    params: {
                        product_id: this.product_id,
                        amount: this.amount.toString().replace(/,/g, ""),
                        term_length_type: this.term_length_type,
                        term_length: this.term_length,
                        currency: this.currency,
                        getData: 1,
                        filter: 1,
                        fi_organization_id: this.bank_id,
                        filter_all: 1,
                        per_page: 10
                    },
                }).then(response => {
                    if(response?.data?.success){
                        this.populateData(response?.data?.data);
                        this.suggestions = response?.data?.suggestion;
                        this.filters = response?.data?.filters;
                        this.loading = false;
                        return;
                    }

                    this.$swal({
                        title: 'Searching failed',
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
                        title: 'Searching failed',
                        text: message,
                        confirmButtonText: 'Close'
                    });

                    this.loading = false;
                });
            },500),
            populateData(data){
                this.offersData = data;
                this.noResultsFound=this.offersData?.data .length === 0;
            }
        }
    }
</script>