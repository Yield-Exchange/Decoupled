<template>
    <b-card
        :style="'min-width: 250px;max-width: 300px;width: 19%; border-radius: 10px;' + (index % 4 != 0 || index == 0 ? 'margin-right: 1.2%' : '')"
        :class="'card-custom text-center display:inline-block; ' + (shaking ? 'cardStyle' : '')"
        @mouseover="eventMouseOver" @mouseleave="eventMouseLeave" v-on:animationend="animationComplete">
        <OfferTopCard :data="data" />

        <b-row v-if="isOwner">
            <p class="card-text my-1"> {{ data.status === 'ACTIVE' ? 'Expiry: ' + data.rate_held_until : 'Expired' }}
            </p>
        </b-row>
        <b-row class="mt-3 text-center" v-if="!isOwner">
            <p class="p-0 m-0" style="font-size: 15px;font-weight: 600">{{ data.organization.name }}</p>
            <p class="p-0 m-0" style="font-size: 14px;font-weight: 500">
                <span>Range: {{ this.c_amount(this.data.currency, this.data.minimum_amount) + " - " +
    this.c_amount(this.data.currency, this.data.maximum_amount)
                }}</span>
            </p>
        </b-row>
        <b-row class="mt-3" v-if="!isOwner">
            <b-col class="text-center p-0">
                <BuyNowModel :buy_url="this.buy_url" @modalOpen="autoPlayStopped" @mouseleave="eventMouseLeave"
                    :from_featured="true" :data="data" />
                <ShopRateButton :data="data" :buy_url="this.buy_url" />
            </b-col>
        </b-row>
    </b-card>
</template>
<style scoped>
.card-custom:hover {
    transform: scale(1.05);
    /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
}

@-webkit-keyframes bounce {

    0%,
    20%,
    50%,
    80%,
    100% {
        -webkit-transform: translateY(0);
    }

    40% {
        -webkit-transform: translateY(20px);
    }

    60% {
        -webkit-transform: translateY(10px);
    }
}

@keyframes bounce {

    0%,
    20%,
    50%,
    80%,
    100% {
        transform: translateY(0);
    }

    40% {
        transform: translateY(20px);
    }

    60% {
        transform: translateY(10px);
    }
}

.cardStyle {
    /*-webkit-animation-name: bounce;*/
    /*animation-name: bounce;*/
    animation: bounce 5s;
    ;

    /*-webkit-animation: slide 0.5s forwards;*/
    /*-webkit-animation-delay: 2s;*/
    /*animation: slide 0.5s forwards;*/
    /*animation-delay: 2s;*/
    /* Start the shake animation and make the animation last for 0.5 seconds */
    /*animation: fadeIn 5s;;*/

    /* When the animation is finished, start again */
    animation-iteration-count: 1;
}

/*@keyframes shake {*/
/*    0% { transform: translate(1px, 1px) rotate(0deg); }*/
/*    10% { transform: translate(-1px, -2px) rotate(-1deg); }*/
/*    20% { transform: translate(-3px, 0px) rotate(1deg); }*/
/*    30% { transform: translate(3px, 2px) rotate(0deg); }*/
/*    40% { transform: translate(1px, -1px) rotate(1deg); }*/
/*    50% { transform: translate(-1px, 2px) rotate(-1deg); }*/
/*    60% { transform: translate(-3px, 1px) rotate(0deg); }*/
/*    70% { transform: translate(3px, 1px) rotate(-1deg); }*/
/*    80% { transform: translate(-1px, -1px) rotate(1deg); }*/
/*    90% { transform: translate(1px, 2px) rotate(0deg); }*/
/*    100% { transform: translate(1px, -2px) rotate(-1deg); }*/
/*}*/

/*@-webkit-keyframes slide {*/
/*    100% { left: 0; }*/
/*}*/

/*@keyframes slide {*/
/*    100% { left: 0; }*/
/*}*/
</style>
<script>
import OrganizationAvatar from "../../shared/OrganizationAvatar";
import BuyNowModel from "../shared/BuyNowModel";
import ShopRateButton from "../shared/ShopRateButton";
import OfferTopCard from "../shared/OfferTopCard";
export default {
    components: { OfferTopCard, ShopRateButton, OrganizationAvatar, BuyNowModel },
    props: ['marketplace_url', 'data', 'index', 'buy_url', 'isOwner'],
    created() {
        this.shaking = false;
    },
    computed: {
    },
    data() {
        return {
            shaking: false
        }
    },
    methods: {
        eventMouseLeave() {
            this.$emit('mouseleave', true)
        },
        eventMouseOver() {
            this.$emit('mouseover', true)
        },
        autoPlayStopped(event) {
            this.$emit('autoPlayStopped', event)
        },
        animationComplete() {
            this.shaking = false;
        },
        shakeCard() {
            let element = this.$el;
            if (!this.shaking) {
                void element.offsetWidth;
                this.shaking = true;
            }
        },
        addComma(newValue) {
            return newValue ? newValue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") : '';
        },
        c_amount(currency, value) {
            if (value.length > 6 && value.length < 10) {
                var newValue = value.split("").reverse().join("").slice(6).split("").reverse().join("");
                var note = "K";
            } else if (value.length >= 10) {
                var newValue = value.split("").reverse().join("").slice(9).split("").reverse().join("");
                var note = "M";
            } else {
                var newValue = value;
                var note = "";
            }
            return ' $' + this.addComma(Math.floor(newValue)) + note;
        },
    }
}
</script>