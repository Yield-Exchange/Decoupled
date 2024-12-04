<template>
    <b-row style="margin-top: 20vh;text-align: center;color:black">
        <b-col cols="3" />
        <b-col cols="6" class="card-wrapper" style="background: white">
            <b-col cols="row mt-2">
                <h3 style="font-size: 20px;font-weight: bold;color: black">There are no offers matching your selection criteria</h3>
                <p style="font-size: 14px;font-weight: normal">Enter your GIC search criteria below to see what offers are available for you.</p>
            </b-col>
            <b-col cols="12" class="mb-3">
                <CustomInputGroup
                        appended-style="width: 25%"
                        input-style="width: 75%"
                        append="prepend"
                        :append-attributes="{ 'value_field':'id','text_field':'description' }"
                        append-id="currency"
                        append-name="Currency"
                        c-style="font-weight: 400;width:50%;margin: 0 auto"
                        :data="currency_options"
                        id="investment_amount"
                        name="Investment Amount*"
                        :has-validation="true"
                        @inputChanged="amount = $event"
                        @selectChanged="currency = $event"
                        :append-default-value="currency"
                        :input-default-value="amount"
                        :attributes="{min: 0}"
                        input-type="number"
                        :validation-failed="submitted && (!amount || amount < 1)"
                        validation-error="Enter amount"
                />
            </b-col>
            <b-col cols="12" class="mb-3" style="align-items: center">
                <CustomSelect :attributes="{ 'value_field':'id','text_field':'description' }"
                              c-style="font-weight: 400;width:50%;margin: 0 auto;border-radius: 10px"
                              :data="productsParsed"
                              id="product_id"
                              name="Product*"
                              :has-validation="true"
                              :default-value="product_id"
                              @selectChanged="product_id = $event"
                              :validation-failed="submitted && (!product_id || product_id < 1)"
                              validation-error="Select product"
                />
            </b-col>
            <b-col cols="12" class="mb-3" style="align-items: center">
                <CustomInputGroup
                        appended-style="width: 30%"
                        input-style="width: 70%"
                        append="prepend"
                        append-id="term_length_type"
                        append-name="Term Length Type"
                        c-style="font-weight: 400;width:50%;margin: 0 auto"
                        :data="term_length_type_options"
                        id="term_length"
                        :name="term_length_type!=='All' ? 'Term Length*' :'Term Length'"
                        :has-validation="true"
                        @inputChanged="term_length = $event"
                        @selectChanged="term_length_type = $event"
                        :append-default-value="term_length_type"
                        :input-default-value="term_length"
                        :attributes="{min: 0}"
                        input-type="number"
                        :validation-failed="submitted && term_length_type!=='All' && (!term_length || term_length < 1)"
                        validation-error="Enter term length"
                />
            </b-col>
            <b-col cols="12" class="mb-3 text-center">
                <m-button :attributes="submitAttributes" :loading="submitLoading" @click="submit()"
                          text="Submit" link="#" type="primary" xclass="font-weight-bold m-1 font-s-12"/>
            </b-col>
        </b-col>
        <b-col cols="3" />
    </b-row>
</template>
<style>
    .card-wrapper{
        border-radius: 10px;
        box-shadow: 1px 1px 5px 3px rgba(238,238,238,0.75);
        -webkit-box-shadow: 1px 1px 5px 3px rgba(238,238,238,0.75);
        -moz-box-shadow: 1px 1px 5px 3px rgba(238,238,238,0.75);
    }

    @media (max-width: 768px) {
        .card-wrapper {
           width: 100%;
        }
    }
    @media (min-width: 769px) and (max-width: 1024px) {
        .card-wrapper {
            width: 80%;
        }
    }
</style>
<script>
    import CustomSelect from "../../shared/CustomSelect";
    import CustomInputGroup from "../../shared/CustomInputGroup";
    export default {
        components: {CustomInputGroup, CustomSelect},
        props: ['products','marketplace_url'],
        computed: {
        },
        data() {
            return {
                currency_options: ['All','CAD','USD'],
                productsParsed: JSON.parse(this.products),
                term_length_type_options: ['All','DAYS','MONTHS'],
                term_length_type: 'All',
                term_length: null,
                product_id: 'all',
                amount: null,
                currency: 'All',
                submitLoading: false,
                submitAttributes: {
                    // 'disabled': ''
                },
                submitted: false
            }
        },
        methods: {
            submit(){
                this.submitted = true;
                if (this.product_id && this.amount && this.currency) {
                    if(this.term_length_type!=='All' && !this.term_length){
                        return;
                    }
                    this.submitLoading = true;
                    this.submitAttributes = {
                        'disabled': ''
                    };
                    axios.get(this.marketplace_url,{
                        params: {
                            product_id: this.product_id,
                            amount: this.amount.toString().replace(/,/g, ""),
                            term_length_type: this.term_length_type,
                            term_length: this.term_length,
                            currency: this.currency,
                            getData: 1,
                            filter: 1
                        },
                    }).then(response => {
                        if(response?.data?.success){
                            window.location.href = response?.data?.url;
                            return;
                        }

                        this.$swal({
                            title: 'Searching failed',
                            text: response?.data?.message,
                            confirmButtonText: 'Close'
                        });

                        this.submitted=false;
                        this.submitLoading=false;
                        this.submitAttributes = {};

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

                        this.submitted=false;
                        this.submitLoading=false;
                        this.submitAttributes = {};
                    });
                }
            }
        }
    }
</script>