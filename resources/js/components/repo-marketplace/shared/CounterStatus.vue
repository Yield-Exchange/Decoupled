<template>
    <div>
        <!-- <p :class="`${colortheme}`">{{ rateValue }}</p> -->
        <p :class="` p-0 m-0 ${colortheme}`" v-if="checkFor == 'counterrate'">{{ rateValue }}</p>

        <div class="d-flex gap-2 justify-content-center align-items-center" v-else-if="checkFor == 'rate'">
            <img src="/assets/dashboard/icons/active-rate.svg" alt="" srcset="">
            <p :class="` p-0 m-0 ${colortheme}`">{{ rateValue }}</p>
        </div>
        <div class="d-flex gap-2 justify-content-center align-items-center px-2 py-1"
            :style="`background-color:${label_color}`" v-else-if="checkFor == 'counterstatus'">
            <!-- <img src="/assets/dashboard/icons/active-rate.svg" alt="" srcset=""> -->
            <p :class="` p-0 m-0 ${colortheme} normal-sizes`" :style="`color: ${text_color} !important;`">{{ label_text
                }}</p>
        </div>
    </div>
</template>

<script>
// checkfor can have three values
// 1.rate
// 2.counterrate
// 3.counterstatus
export default {
    props: ['status', 'checkFor', 'rateValue'],
    beforeMount() {
        if (this.checkFor) {
            this.defaultCounterState()
        }
    },
    data() {
        return {
            colortheme: 'black',
            label: null,
            label_text: null,
            text_color: null
        }
    },
    computed: {
        label_color() {
            let label_color = '#fff'
            if (this.checkFor == 'counterstatus') {
                let status = this.status ? this.status.toLowerCase() : 'accepted'
                if (status == 'accepted') {
                    this.label_text = 'Accepted'
                    this.text_color = '#EAF5EA'
                    label_color = '#44E0AA'

                }
                if (status == 'pending') {
                    label_color = 'rgba(244, 182, 60, 0.20);'
                    this.text_color = '#F4B63C'
                    this.label_text = 'Pending'

                } else if (status == 'declined') {
                    label_color = '#FFEBEB'
                    this.text_color = '#FF2E2E'
                    this.label_text = 'Declined'

                } else if (status == 'expired') {
                    label_color = '#FFEBEB'
                    this.text_color = '#FF2E2E'
                    this.label_text = 'Expired'

                } else if (status == 'edited') {
                    label_color = '#EFF2FE'
                    this.text_color = '#5063F4'
                    this.label_text = 'Edited'

                } else if (status == 'countered') {
                    this.label_text = 'Accepted'
                    label_color = '#44E0AA'
                    this.text_color = '#EAF5EA'

                } else if (status == 'closed_on_countered' || status == 'closed_on_offer_selection') {
                    this.label_text = 'Closed'
                    label_color = '#D9D9D9'
                    // label_color = '#9CA1AA'
                    this.text_color = '#9CA1AA'
                }

            }
            return label_color
        }
    },
    methods: {
        defaultCounterState() {
            let status = this.status ? this.status.toLowerCase() : 'accepted'
            if (this.checkFor == 'rate') {
                if (status == 'pending' || status == 'declined' || status == 'expired') {
                    this.colortheme = 'black'
                }
            } else if (this.checkFor == 'counterrate') {

                if (status == 'accepted') {
                    this.colortheme = 'accepted_countered'
                }
                if (status == 'pending') {
                    this.colortheme = 'pending'
                } else if (status == 'declined' || status == 'expired') {
                    this.colortheme = 'declined'
                } else if (status == 'edited') {
                    this.colortheme = 'closed'
                } else if (status == 'countered') {
                    this.colortheme = 'accepted_countered'
                } else if (status == 'closed_on_countered' || status == 'closed_on_offer_selection') {
                    this.colortheme = 'closed'
                }
            }
            // possuble counter states
            // 'ACCEPTED','DECLINED','COUNTERED','PENDING','EXPIRED','EDITED','CLOSED_ON_COUNTERED','CLOSED_ON_OFFER_SELECTION'
        }
    }
}

</script>
<style scoped>
.declined {
    color: #FF2E2E;
    font-family: Montserrat;
    font-size: 13px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    /* text-decoration: ; */
    text-decoration: line-through !important;
    text-transform: capitalize;
}

.pending {
    color: #F4B63C;
    font-family: Montserrat;
    font-size: 13px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    text-transform: capitalize;
}

.normal-sizes {
    text-transform: capitalize;
    font-family: Montserrat;
    font-size: 13px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;

}

.closed {
    color: #9CA1AA;
    font-family: Montserrat;
    font-size: 13px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    text-decoration: line-through !important;
    text-transform: capitalize;
}

.accepted_countered {
    color: #44E0AA;
    font-family: Montserrat;
    font-size: 13px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    /* text-decoration: line-through !important; */
    text-transform: capitalize;
}

.current_rate {
    color: #252525;
    font-family: Montserrat;
    font-size: 13px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    text-transform: capitalize;
}
</style>