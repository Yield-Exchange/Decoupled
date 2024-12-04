<template>
    <div
        style=" width: 93%;min-height: 736px;flex-direction: column;justify-content: flex-start; align-items: flex-start;gap: 30px; display: inline-flex;margin-left: 2%; overflow: auto;">
        <accordion :is_open="true"
            :title="hasNoticePeriod ? prod?.lockout_period + ' Day ' + prod.product_type.description : prod.product_type.description"
            width="100" title_image="/assets/dashboard/icons/Setting__3.svg" />
        <div
            style="flex-direction: column;justify-content: flex-start;align-items: flex-start; gap: 20px; display: flex;width:100%">
            <div style="width: 100%;justify-content: flex-start; gap: 20px; display: inline-flex;  margin-top: 20px;">
                <div style="width: 25%; justify-content: space-between; display: flex;flex-direction: column;">
                    <div
                        style="height: 100%;padding: 60px 40px;background: white;box-shadow: 0 4px 4px #d9d9d9;flex-direction: column;justify-content: center;align-items: center;gap: 12px; display: inline-flex;">


                        <div
                            style="flex-direction: column; justify-content: flex-start; align-items: flex-start; display: flex; ">
                            <div
                                style=" width: 100%; text-align: center; color: #252525; font-size: 18px; font-weight: 700;word-wrap: break-word; ">
                                Term Length
                            </div>
                            <div
                                style=" width: 100%;text-align: center;color: #5063f4;font-size: 28px; font-weight: 700; word-wrap: break-word; text-transform: capitalize; ">
                                {{ prod?.term_length + ' ' + capitalize(prod?.term_length_type) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    style="width: 100%;flex-direction: column;justify-content: flex-start; align-items: flex-start;display: inline-flex; ">
                    <div
                        style=" min-height: 240px;flex-direction: column;justify-content: flex-start; align-items: flex-start;gap: 30px; display: flex; ">

                        <div style="width: 100%; ">
                            <span
                                style=" color: #252525; font-size: 16px; font-weight: 700;line-height: 20px;word-wrap: break-word;">About
                                This GIC<br /></span>
                            <span
                                style="color: #5063f4; font-size: 16px; font-weight: 700;line-height: 20px;word-wrap: break-word;"><br /></span><span
                                style="color: #252525; font-size: 16px;  font-weight: 500;word-wrap: break-word;">
                                {{ prod.product_type.definition }}
                            </span>
                        </div>
                        <ProductDetails :earning_text="prod.product_type.earning_text"
                            :earning_rate="prod.product_type.earning_rate"
                            :flexibility_rate="prod.product_type.flexibility_rate"
                            :flexibility_text="prod.product_type.flexibility_text" />
                        <div
                            style=" width: 100%; flex-direction: column; justify-content: flex-start;align-items: flex-start;gap: 10px; display: flex; ">
                            <div
                                style="width: 100%;justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex;">


                                <div
                                    style=" width: 25%;height: 100%;color: #252525;font-size: 16px;font-weight: 500;word-wrap: break-word;">
                                    Product Name
                                </div>

                                <div
                                    style=" width: 25%;height: 100%;color: #252525;font-size: 16px;font-weight: 500;word-wrap: break-word;">
                                    Locked in Period
                                </div>
                                <div
                                    style=" width: 25%;height: 100%;color: #252525;font-size: 16px;font-weight: 500;word-wrap: break-word;">
                                    Interest Paid
                                </div>
                                <div
                                    style=" width: 25%;height: 100%;color: #252525;font-size: 16px;font-weight: 500;word-wrap: break-word;">
                                    Compound Frequency
                                </div>
                            </div>
                            <div
                                style="width: 100%;justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex;">

                                <div
                                    style=" width: 25%;height: 100%;color: #5063f4;font-size: 17px;font-weight: 800;word-wrap: break-word;">
                                    {{ prod?.custom_product_name }}
                                </div>
                                <div
                                    style=" width: 25%;height: 100%;color: #5063f4;font-size: 17px;font-weight: 800;word-wrap: break-word;">
                                    {{ prod?.lockout_period ? prod?.lockout_period + ' ' + prod?.term_length_type : '-'
                                    }}
                                </div>
                                <div
                                    style=" width: 25%;height: 100%;color: #5063f4;font-size: 17px;font-weight: 800;word-wrap: break-word;">
                                    {{ capitalize(prod?.interest_paid) }}
                                </div>
                                <div
                                    style=" width: 25%;height: 100%;color: #5063f4;font-size: 17px;font-weight: 800;word-wrap: break-word;">
                                    {{ capitalize(prod?.compound_frequency) }}
                                </div>
                            </div>
                        </div>
                        <div
                            style="justify-content: flex-end;align-items: center;display: inline-flex;width: 100%; gap: 20px; ">
                            <div style="justify-content: flex-end;align-items: flex-start;gap: 50px;display: inline-flex;">
                                <b-button @click="back" class="btnbtn">
                                    Back
                                </b-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.btn-add-rm-group {
    min-width: 200px;

    height: 40px !important;
    border: 0.50px #D9D9D9 solid;
    padding: 10px 30px 10px 30px !important;
    border-radius: 20px !important;
    color: #5063F4 !important;
    background-color: #F8FAFF !important;
    display: flex;
    justify-content: center;
    /* Use flexbox for layout */
    align-items: center;
    /* Center content vertically */
}

.form-input-text {
    font-size: 16px !important;
}

.custom-tab-nav-class>ul li .nav-link {
    font-size: 14px;
}

.custom-tab-nav-class4>ul {
    border-bottom: 1px solid #ccc;
    width: 93%;
}

#investment_amount123 {
    margin: 0 !important;
    height: 44px !important;
}
</style>
<script>
import Accordion from "../../shared/Accordion";
import Tooltip from "../../shared/Tooltip";
import Button from "../../shared/Buttons/Button";
import DeleteProduct from "./DeleteProduct";

import ProductDetails from "../../shared/ProductDetails.vue";
// import ProductDetails from "../../shared/ProductDetails.vue";
export default {
    components: {
        DeleteProduct,
        Button,
        Accordion,
        Tooltip,
        ProductDetails
    },
    props: ['product'],
    data() {
        return {
            prod: JSON.parse(this.product)
        }
    },
    computed: {
        hasNoticePeriod() {
            // Using the includes method
            return (this.prod.product_type.description.includes("Notice deposit") || this.prod.product_type.description.includes("Cashable"));
        }
    },
    methods: {
        back() {
            window.location.href = '/campaigns/products';
        },
        viewLink(link) {
            window.open(link, '_blank')
        },
        capitalize(thestring) {
            return thestring
                .toLowerCase()
                .split(' ')
                .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
                .join(' ');
        },
        formatNumberAbbreviated(number) {
            const SI_SYMBOL = ["", "K", "M", "G", "T", "P", "E"];

            const tier = (Math.log10(number) / 3) | 0;

            if (tier === 0) return number;

            const suffix = SI_SYMBOL[tier];
            const scale = Math.pow(10, tier * 3);

            const scaledNumber = number / scale;

            return scaledNumber.toFixed(0) + suffix;
        },
        aboutGIC(productType) {
            productType = productType.toLowerCase();
            return this.aboutArray()[productType]
        },
        aboutArray() {
            return {
                'non-redeemable': ' is tailor-made for those who prioritize liquidity and flexibility in their investments. This financial product grants you the freedom to access your funds whenever you need them, without the worry of facing penalties. It\'s an ideal solution that strikes a harmonious balance between financial growth and accessibility. With the Non-Redeemable GIC, you\'re empowered with a versatile and potent financial tool, giving you the confidence to make your money work for you while still maintaining control over your assets.',
                'short term': ' is designed for those seeking quick returns, the Short Term Deposit offers a swift investment opportunity. With a shorter maturity period, this product is perfect for those who prefer seeing their funds grow in a shorter timeframe. It\'s a dynamic choice that aligns with your need for a timely and impactful financial solution.',
                'cashable': ' puts you in control of your investments. Offering the flexibility to access your funds without penalties, it provides a secure avenue for growth while ensuring your financial freedom remains intact. This product combines the best of both worlds—steady financial growth and the liberty to adapt to your evolving needs.',
                'notice deposit': ' is built for strategic financial planning, the Notice Deposit empowers you with the ability to save and grow your money on your terms. With this product, you have the advantage of providing a notice period before accessing your funds. This encourages a disciplined approach to financial management, letting you reap the benefits of growth while maintaining the flexibility to fulfill your financial goals.',
                'high interest savings': ' is your gateway to a higher rate of return on your savings. Tailored for those who value both security and growth, it offers a competitive interest rate while keeping your funds readily accessible. This account empowers you to maximize your savings potential without sacrificing your ability to manage your finances efficiently.',
            };
        }
    }
}
</script>
