<template>
    <div>
        <MessageHeaderIconized title="How would you like to edit this campaign?" width="100"
            title_image="/assets/dashboard/icons/PromoEditPen.svg" />

        <div class="px-3 mt-4">
            <form @submit.prevent="submitSteps">
                <div class="form-group" v-if="userCan(this.userloggedin,'bank/my-campaigns/edit-campaign-details')">
                    <input type="checkbox" value="2" v-model="select_steps" name="stepselected[]" id="edit-step"
                        :disabled="ready_fo_edit">
                    <label for="">Edit Campaign Details </label>
                </div>
                <div class="form-group" v-if="userCan(this.userloggedin,'bank/my-campaigns/edit-campaign-products')">
                    <input type="checkbox" v-model="select_steps" value="3" name="stepselected[]" id="edit-step"
                        :disabled="ready_fo_edit">
                    <label for="">Edit Campaign Products</label>
                </div>
                <div class="form-group" v-if="userCan(this.userloggedin,'bank/my-campaigns/edit-target-depositors')">
                    <input type="checkbox" v-model="select_steps" value="4" name="sstepselected[]" id="edit-step"
                        :disabled="ready_fo_edit">
                    <label for="">Edit Target Depositors</label>
                </div>
                <div class="form-group" v-if="userCan(this.userloggedin,'bank/my-campaigns/edit-product-rates')">
                    <input type="checkbox" v-model="select_steps" value="5" name="stepselected[]" id="edit-step"
                        :disabled="ready_fo_edit">
                    <label for="">Edit Product Rates</label>
                </div>
                <!-- <div class="form-group" v-if="userCan(this.userloggedin,'bank/my-campaigns/edit-product-rates')">
                    <input type="checkbox" v-model="select_steps" value="6" name="step-selected" id="edit-step"
                        :disabled="ready_fo_edit">
                    <label for="">Muilt-step Edit</label>
                </div> -->
                <div class="d-flex justify-content-end ">
                    <button :disabled="cannotSubmit" class="submit-step-button">Submit</button>
                </div>

            </form>
        </div>
    </div>
</template>


<script>
    import MessageHeaderIconized from '../../shared/messageboxes/MessageHeaderIconized.vue';
    import { userCan } from "../../../utils/GlobalUtils";

    export default {
        components: { MessageHeaderIconized },
        props: ['ready_fo_edit', 'userloggedin'],
        data() {
            return {
                select_steps: [],
                cannotSubmit: false
            }
        },
        methods: {
            submitSteps() {

                console.log(this.select_steps, "this.select_steps");
                this.$emit('updateStepsClick', this.select_steps);
            },
            userCan(user, permission) {
                return userCan(user, permission);
            },
        },
        watch: {

        }
    }
</script>
<style scoped>
    label {
        font-size: 1rem;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
        color: black;
    }

    input[type=checkbox] {
        width: 1rem;
        height: 1rem;
        border: 1px solid #5063F4 !important;
        border-radius: 2px;
        outline: #5063F4;
    }

    .submit-step-button {
        outline: none;
        box-shadow: none;
        border: none;
        background-color: #5063F4;
        display: flex;
        padding: 10px 30px;
        justify-content: flex-end;
        align-items: center;
        border-radius: 32px;
        color: white;
        font-size: 16px;
        font-style: normal;
        font-weight: 600;
        line-height: 20px;
        /* 125% */
        text-transform: capitalize;
    }

    input[type=radio] {
        border: 1px solid #000000;
        padding: 0.5em;
        -webkit-appearance: none;
        border-radius: 2px;

    }

    input[type=radio]:checked {
        background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="31" height="31" viewBox="0 0 31 31" fill="none"><g clip-path="url(%23clip0_3323_85470)"><path d="M22.9387 12.2244C23.4966 11.6665 23.4966 10.762 22.9387 10.2041C22.3808 9.64624 21.4763 9.64624 20.9184 10.2041L12.6429 18.4797L9.3673 15.2041C8.8094 14.6462 7.90488 14.6462 7.34699 15.2041C6.7891 15.762 6.7891 16.6665 7.34699 17.2244L11.1276 21.0051C11.9645 21.8419 13.3212 21.8419 14.1581 21.0051L22.9387 12.2244Z" fill="%23EFF2FE"/><path fill-rule="evenodd" clip-rule="evenodd" d="M6.92857 0.5C3.37817 0.5 0.5 3.37817 0.5 6.92857V24.0714C0.5 27.6218 3.37817 30.5 6.92857 30.5H24.0714C27.6218 30.5 30.5 27.6218 30.5 24.0714V6.92857C30.5 3.37817 27.6218 0.5 24.0714 0.5H6.92857ZM1.92857 6.92857C1.92857 4.16715 4.16715 1.92857 6.92857 1.92857H24.0714C26.8329 1.92857 29.0714 4.16715 29.0714 6.92857V24.0714C29.0714 26.8329 26.8329 29.0714 24.0714 29.0714H6.92857C4.16715 29.0714 1.92857 26.8329 1.92857 24.0714V6.92857Z" fill="%23EFF2FE"/><path fill-rule="evenodd" clip-rule="evenodd" d="M6.92857 1.92857C4.16715 1.92857 1.92857 4.16715 1.92857 6.92857V24.0714C1.92857 26.8329 4.16715 29.0714 6.92857 29.0714H24.0714C26.8329 29.0714 29.0714 26.8329 29.0714 24.0714V6.92857C29.0714 4.16715 26.8329 1.92857 24.0714 1.92857H6.92857ZM22.9387 12.2244C23.4966 11.6665 23.4966 10.762 22.9387 10.2041C22.3808 9.64624 21.4763 9.64624 20.9184 10.2041L12.6429 18.4797L9.3673 15.2041C8.8094 14.6462 7.90488 14.6462 7.34699 15.2041C6.7891 15.762 6.7891 16.6665 7.34699 17.2244L11.1276 21.0051C11.9645 21.8419 13.3212 21.8419 14.1581 21.0051L22.9387 12.2244Z" fill="%235063F4"/></g><defs><clipPath id="clip0_3323_85470"><rect width="31" height="31" fill="white"/></clipPath></defs></svg>');
        ;
        background-repeat: no-repeat;
        /* position: center; */
        background-position: center;
        background-size: cover;
        padding: 0px;
        border: none;
        width: 1.2rem;
        height: 1.2rem;
        outline: none;
    }
</style>
