<template>
    <div>
        <m-button :attributes="submitAttributes" :loading="loading" class="mb-2" @click="submit"
                  :c-style="'font-size: 14px;width: 80%;'" text="Shop Rate" link="#" type="secondary" xclass="font-weight-bold m-1 custom-buttons"/>
    </div>
</template>

<style>
</style>

<script>
    export default {
        components: {},
        props: ['data','buy_url'],
        mounted() {

        },
        data() {
            return {
                date_of_deposit: null,
                amount: null,
                loading: false,
                submitAttributes: {
                    // 'disabled': ''
                },
            }
        },
        computed: {
        },
        methods: {
            showModal() {
                // this.$root.$emit('bv::show::modal', 'modal-1', '#btnShow')
            },
            hideModal() {
                this.$root.$emit('bv::hide::modal', '#buyNow'+this.data.id, '#btnShow')
            },
            submit() {
                this.loading = true;
                this.submitAttributes = {
                    'disabled': ''
                };

                axios.post(this.buy_url, {
                    is_shop_rate: 1,
                    market_place_offer_id: this.data.id
                }).then(response => {
                    if(response?.data?.success){
                        this.loading = false;
                        window.location.href = response?.data?.url;
                        return;
                    }

                    this.$swal({
                        title: 'Submit failed',
                        text: response?.data?.message,
                        confirmButtonText: 'Close'
                    });

                    this.loading = false;
                    this.submitAttributes={
                        // 'disabled': ''
                    };
                }).catch(error => {
                    let message;
                    if (error?.response?.status === 419) {
                        message = "The page has expired due to inactivity. Please refresh the page and try again.";
                    } else {
                        message = error?.response?.data?.message;
                    }

                    this.$swal({
                        title: 'Submit failed',
                        text: message,
                        confirmButtonText: 'Close'
                    });

                    this.loading = false;
                    this.submitAttributes={
                        // 'disabled': ''
                    };
                });
            },
        },
    }

</script>
