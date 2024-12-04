<template>
    <div>
        <b-form-input v-model="amount" placeholder="Enter Amount" class="enter_amount" @keyup="setValue"
            :disabled="shouldNotPerformNoAction" />
    </div>
</template>

<style scoped>
    .form-control {
        border: none;
        border-bottom: 1px solid #9CA1AA;
        border-radius: 0;
        padding: 0;
        box-shadow: none !important;
        outline: none !important;
    }

    .form-control:focus {
        box-shadow: none !important;
        outline: none !important;
    }
</style>


<script>
    import { addCommasToANumber, sanitizeAmount, addCommasAndDecToANumber } from "../../../utils/commonUtils";
    import { mapGetters } from 'vuex';
    import * as types from '../../../store/modules/postreq/mutation-types.js';
    export default {
        props: ["offer_id", "offer", "shouldNotPerformNoAction"],
        created() { },
        components: {
        },
        computed: {
            ...mapGetters('postreq', ['getAllSelectedRequestOffers', 'getPickedSelectedRequestOffers']),
        },
        data() {
            return {
                amount: "",
                debounceTimeout: null,

            }
        },
        methods: {
            addCommans(newvalue) {
                return addCommasAndDecToANumber(newvalue);
            },
            setValue(event) {

                if (this.debounceTimeout) {
                    clearTimeout(this.debounceTimeout);
                }

                if (event.target.value != undefined && event.target.value != null && event.target.value != "") {
                    let value = sanitizeAmount(event.target.value);
                    this.debounceTimeout = setTimeout(() => {
                        this.amount = addCommasToANumber(value);
                        //update store
                        console.log(value, "valuevalue");

                        if (this.offer.min > value) {
                            this.$store.commit('postreq/' + types.UPDATE_KEY_FOR_OFFERS, {
                                offer_id: this.offer_id,
                                field: 'awarded_error',
                                value: `Cannot be less than CAD ${this.addCommans(this.offer.min)}`
                            });
                        }
                        else if (this.offer.max < value) {
                            this.$store.commit('postreq/' + types.UPDATE_KEY_FOR_OFFERS, {
                                offer_id: this.offer_id,
                                field: 'awarded_error',
                                value: `Cannot be more than CAD ${this.addCommans(this.offer.max)}`
                            });
                        } else {
                            this.$store.commit('postreq/' + types.UPDATE_KEY_FOR_OFFERS, {
                                offer_id: this.offer_id,
                                field: 'awarded_error',
                                value: ''
                            });
                        }
                        this.$store.commit('postreq/' + types.UPDATE_KEY_FOR_OFFERS, {
                            offer_id: this.offer_id,
                            field: 'awarded',
                            value: value
                        });
                        //update store
                    }, 300);
                } else {
                    this.$store.commit('postreq/' + types.UPDATE_KEY_FOR_OFFERS, {
                        offer_id: this.offer_id,
                        field: 'awarded_error',
                        value: ''
                    });
                    this.$store.commit('postreq/' + types.UPDATE_KEY_FOR_OFFERS, {
                        offer_id: this.offer_id,
                        field: 'awarded',
                        value: 0
                    });
                }

            }
        }
    }

</script>