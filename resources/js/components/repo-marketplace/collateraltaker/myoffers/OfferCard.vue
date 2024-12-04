<template>
    <div class="d-flex flex-column gap-2 p-4 mo-offer-card" style="height: 100% !important">
        <div class="mo-organization-section">
            <div class="d-flex gap-2 justify-content-start align-items-center" v-if="organization">
                <div style="width: 52px;height: 52px;flex-shrink: 0; border-radius: 100%; padding: 5px ;border: 2px solid #D9D9D9; cursor: pointer;"
                    @click="show = true" class="d-flex justify-content-center align-items-center">
                    <avatar v-if="!organization?.logo" :size="40" :color="'white'" :backgroundColor="'#4975E3'"
                        :initials="organization?.name[0]"></avatar>
                    <img v-else style="width: 40px;height: 40px; border-radius: 100%"
                        :src="'/image/' + organization?.logo" />
                </div>
                <p class="m-0 p-0 show-cg-class-with-icon" @click="show = true">{{ organization?.name }}</p>
            </div>
        </div>

        <div class="mo-product-type-section">
            <p class="offer-product-card text-capitalize p-0 m-0">

                {{
                offer?.offer_term_length_type ?
                    repoProductName(offer?.offer_term_length, offer?.offer_term_length_type,
                        offer?.product?.product_name) : '-',
                }}
            </p>
        </div>
        <div class="mo-rate-section">
            <p class="offer-rate-section-card">
                {{ interest_rate }}%
            </p>
        </div>

        <div>
            <div class="d-flex gap-2  justify-content-start align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="21" viewBox="0 0 20 21" fill="none">
                    <path
                        d="M8.33203 12.2917C8.33203 12.7337 8.50763 13.1576 8.82019 13.4702C9.13275 13.7827 9.55667 13.9583 9.9987 13.9583C10.4407 13.9583 10.8646 13.7827 11.1772 13.4702C11.4898 13.1576 11.6654 12.7337 11.6654 12.2917C11.6654 11.8496 11.4898 11.4257 11.1772 11.1132C10.8646 10.8006 10.4407 10.625 9.9987 10.625C9.55667 10.625 9.13275 10.8006 8.82019 11.1132C8.50763 11.4257 8.33203 11.8496 8.33203 12.2917Z"
                        stroke="#5063F4" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M14.166 8.95825L12.4994 3.95825M5.8327 8.95825L7.49937 3.95825M4.16687 7.29159H15.8327C16.073 7.29156 16.3105 7.34349 16.5288 7.44384C16.7472 7.54418 16.9413 7.69055 17.0977 7.87292C17.2542 8.05529 17.3694 8.26935 17.4354 8.50041C17.5015 8.73147 17.5167 8.97407 17.4802 9.21159L16.4344 15.1716C16.3436 15.762 16.0444 16.3003 15.591 16.6892C15.1376 17.0781 14.56 17.2918 13.9627 17.2916H6.03603C5.43886 17.2916 4.86143 17.0778 4.40822 16.6889C3.95501 16.3001 3.65597 15.7618 3.5652 15.1716L2.51937 9.21159C2.48284 8.97407 2.49811 8.73147 2.56412 8.50041C2.63013 8.26935 2.74533 8.05529 2.90182 7.87292C3.05831 7.69055 3.25238 7.54418 3.47073 7.44384C3.68909 7.34349 3.92656 7.29156 4.16687 7.29159Z"
                        stroke="#5063F4" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <p class="p-0 m-0  mo-basket-title">Basket:</p>
                <p class="p-0 m-0 mo-basket-description">{{ basket }}</p>
            </div>
        </div>
        <div>
            <div class="d-flex gap-2  justify-content-start align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="21" viewBox="0 0 20 21" fill="none">
                    <path
                        d="M10.0007 18.9584C8.0701 18.4723 6.47621 17.3645 5.21898 15.6351C3.96176 13.9056 3.33343 11.9856 3.33398 9.87508V4.79175L10.0007 2.29175L16.6673 4.79175V9.87508C16.6673 11.9862 16.039 13.9065 14.7823 15.6359C13.5257 17.3654 11.9318 18.4729 10.0007 18.9584ZM10.0007 17.2084C11.4451 16.7501 12.6395 15.8334 13.584 14.4584C14.5284 13.0834 15.0007 11.5556 15.0007 9.87508V5.93758L10.0007 4.06258L5.00065 5.93758V9.87508C5.00065 11.5556 5.47287 13.0834 6.41732 14.4584C7.36176 15.8334 8.55621 16.7501 10.0007 17.2084Z"
                        fill="#5063F4" />
                </svg>
                <p class="p-0 m-0 mo-basket-title">Basket Rating:</p>
                <p class="p-0 m-0 mo-basket-description">{{ basket_rating }}</p>
            </div>
        </div>

        <CustomSubmit class="w-100" title="View More" @action="ViewMore"></CustomSubmit>


    </div>
</template>

<script>
import { Avatar } from 'vue-avatar';
import CustomSubmit from '../../../auth/signup/shared/CustomSubmit.vue'
import { capitalize, repoProductName } from '../../../../utils/commonUtils';


export default
    {
        props: ['offer'],
        mounted() {
            if (this.offer) {
                this.organization = this.offer?.c_g
                this.offer_id = this.offer.encoded_id
                this.interest_rate = this.offer?.offer_interest_rate.toFixed(2)
                this.basket_rating = this.offer.basket.basket_details.rating
                this.basket = this.offer.basket.basket_details.trade_basket_type.basket_name
                this.term_length = this.offer?.offer_term_length + " " + capitalize(this.offer?.offer_term_length_type)
                this.is_bilateral = this.offer.product.filter_key != 'tri'

            }

        },
        components: { Avatar, CustomSubmit },
        data() {
            return {
                organization: null,
                term_length: null,
                is_bilateral: null,
                offer_id: null,
                has_icon: true,
                basket_rating: null,
                basket: null,
                interest_rate: null
            }
        },
        methods: {
            ViewMore() {
                window.location.href = '/repos/ct-repos-offer/summary/' + this.offer_id + '?fromPage=repos/ct-repos-my-offers';
            },
            repoProductName(x, y, z) {
                return repoProductName(x, y, z)
            },
        }
    }

</script>

<style scoped>
.mo-basket-description {
    color: #252525;
    font-family: Montserrat;
    font-size: 12px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
}

.mo-basket-title {
    color: #252525;
    font-family: Montserrat;
    font-size: 12px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
}

.show-cg-class-with-icon {
    color: #0F3D6F;
    font-family: Montserrat !important;
    font-size: 14px;
    font-style: normal;
    font-weight: 600;
    line-height: normal;
    cursor: pointer;
}

.offer-product-card {
    color: #252525;
    font-family: Montserrat;
    font-size: 16px;
    font-style: normal;
    font-weight: 900;
    line-height: normal;
}

.offer-rate-section-card {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Blue, var(--Yield-Exchange-Colors-Yield-Exchange-Blue, #5063F4));
    font-family: Montserrat;
    font-size: 50px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
}

.mo-offer-card {
    border-radius: 10px;
    border: 0.5px solid #D9D9D9;
    background: var(--white-100, #FFF);
    box-shadow: 0px 2px 6px 0px rgba(0, 0, 0, 0.10);
    padding: 20px;
    border-radius: 10px;
    width: 290px;
}
</style>