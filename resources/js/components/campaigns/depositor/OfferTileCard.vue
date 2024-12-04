<template>
    <div class="new-offers-tile">
        <div class="frame">
            <div style="text-transform: capitalize" class="non-redeemable">{{ datum?.lockout_period > 0 ?
                datum?.lockout_period+' '+pluralizeWord('Day',datum?.lockout_period) : '' }} {{ datum?.description }}
            </div>
            <div class="element">{{ datum?.rate }}%</div>
            <div class="div">
                <div class="text-wrapper">Term Length:</div>
                <div class="text-wrapper-2">{{ datum?.term_length + ' '+datum?.term_length_type }}</div>
            </div>
            <div class="div">
                <div class="text-wrapper-3">Min :</div>
                <div class="text-wrapper-4">{{ datum?.currency}} {{ formatNumberAbbreviated(datum?.minimum) }}</div>
                <div class="text-wrapper-5">Max :</div>
                <div class="text-wrapper-4">{{ datum?.currency}} {{ formatNumberAbbreviated(datum?.maximum) }}</div>
            </div>
            <div class="frame-wrapper">
                <div class="frame-2">
                    <avatar v-if="!datum?.campaign?.organization?.logo" :size="44" :color="'white'"
                        :backgroundColor="'#4975E3'" :initials="datum?.campaign?.organization?.name[0]"></avatar>
                    <avatar v-if="datum?.campaign?.organization?.logo" :size="44" :color="'white'"
                        :backgroundColor="'#4975E3'" :src="'image/' + datum?.campaign?.organization?.logo"></avatar>
                    <div class="text-wrapper-6">{{ addEllipsisIfNecessary(datum?.campaign?.organization?.name,30) }}
                    </div>
                </div>
            </div>
            <div class="div-wrapper">
                <div
                    style="width: 100%;align-self: stretch; padding-top: 10px; padding-bottom: 10px; justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex; cursor: pointer">
                    <div
                        style="flex: 1 1 0; align-self: stretch; justify-content: flex-start; align-items: flex-start; gap: 10px; display: flex">
                        <div @click="viewOffer"
                            style="width:100%;align-self: stretch; height: 40px; border-radius: 20px; overflow: hidden; border: 2px #5063F4 solid; justify-content: center; align-items: center; display: inline-flex">
                            <div
                                style="padding-left: 10px; padding-right: 10px; justify-content: center; align-items: flex-start; gap: 10px; display: flex">
                                <div
                                    style="color: #5063F4; font-size: 16px; font-weight: 500; text-transform: capitalize; line-height: 20px; word-wrap: break-word">
                                    View More</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="display: flex; flex-direction: row; justify-content: flex-end; width: 100%;">
            <div class="frame-3">
                <div class="text-wrapper-7">{{ calculateTimeLeft(datum.expiry_date) }}</div>
            </div>
        </div>
    </div>
</template>
<style scoped>
    .new-offers-tile {
        margin-bottom: 20px;
        display: inline-flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
        padding: 25px;
        position: relative;
        background-color: var(--yield-exchange-palletteyield-exchange-white);
        border-radius: 10px;
        border: 0.5px solid #d9d9d9;
    }

    .new-offers-tile .frame {
        display: flex;
        flex-direction: column;
        width: 226px;
        align-items: flex-start;
        gap: 5px;
        padding: 20px 0px 0px;
        position: relative;
        flex: 0 0 auto;
    }

    .new-offers-tile .non-redeemable {
        position: relative;
        align-self: stretch;
        height: 28px;
        margin-top: -1px;
        font-weight: 900;
        color: var(--yield-exchange-palletteyield-exchange-blue);
        font-size: 16px;
        letter-spacing: 0;
        line-height: normal;
    }

    .new-offers-tile .element {
        position: relative;
        align-self: stretch;
        font-weight: 500;
        color: #5063f4;
        font-size: 50px;
        letter-spacing: 0;
        line-height: normal;
    }

    .new-offers-tile .div {
        display: flex;
        align-items: flex-start;
        gap: 5px;
        position: relative;
        align-self: stretch;
        width: 100%;
        flex: 0 0 auto;
    }

    .new-offers-tile .text-wrapper {
        position: relative;
        height: 28px;
        margin-top: -1px;
        font-weight: 700;
        color: var(--yield-exchange-palletteyield-exchange-black);
        font-size: 14px;
        letter-spacing: 0;
        line-height: normal;
    }

    .new-offers-tile .text-wrapper-2 {
        position: relative;
        flex: 1;
        height: 28px;
        margin-top: -1px;
        font-weight: 700;
        color: var(--yield-exchange-palletteyield-exchange-black);
        font-size: 14px;
        letter-spacing: 0;
        line-height: normal;
    }

    .new-offers-tile .text-wrapper-3 {
        width: 33px;
        position: relative;
        height: 28px;
        margin-top: -1px;
        font-weight: 400;
        color: var(--yield-exchange-palletteyield-exchange-black);
        font-size: 14px;
        letter-spacing: 0;
        line-height: normal;
        white-space: nowrap;
    }

    .new-offers-tile .text-wrapper-4 {
        flex: 1;
        position: relative;
        height: 28px;
        margin-top: -1px;
        font-weight: 400;
        color: var(--yield-exchange-palletteyield-exchange-black);
        font-size: 14px;
        letter-spacing: 0;
        line-height: normal;
        white-space: nowrap;

    }

    .new-offers-tile .text-wrapper-5 {
        width: 37px;
        position: relative;
        height: 28px;
        margin-top: -1px;
        font-weight: 400;
        color: var(--yield-exchange-palletteyield-exchange-black);
        font-size: 14px;
        letter-spacing: 0;
        line-height: normal;
        white-space: nowrap;

    }

    .new-offers-tile .frame-wrapper {
        display: flex;
        align-items: center;
        gap: 5px;
        position: relative;
        align-self: stretch;
        width: 100%;
        flex: 0 0 auto;
    }

    .new-offers-tile .frame-2 {
        display: inline-flex;
        align-items: center;
        position: relative;
        flex: 0 0 auto;
    }

    .new-offers-tile .istockphoto {
        position: relative;
        width: 44px;
        height: 44px;
    }

    .new-offers-tile .text-wrapper-6 {
        position: relative;
        width: 161px;
        height: 32px;
        font-weight: 900;
        color: #0f3d6f;
        font-size: 14px;
        letter-spacing: 0;
        line-height: normal;
        margin-top: 20px;
        margin-left: 5px;
    }

    .new-offers-tile .div-wrapper {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        position: relative;
        align-self: stretch;
        width: 100%;
        flex: 0 0 auto;
    }

    .new-offers-tile .small-button-wrapper {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        position: relative;
        flex: 1;
        align-self: stretch;
        flex-grow: 1;
        all: unset;
        box-sizing: border-box;
    }

    .new-offers-tile .small-button {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 10px 30px;
        position: relative;
        flex: 1;
        flex-grow: 1;
        border-radius: 32px;
        overflow: hidden;
        border: 2px solid;
        border-color: var(--collection-1-yield-exchange-colors-yield-exchange-purple);
        all: unset;
        box-sizing: border-box;
    }

    .new-offers-tile .search-wrapper {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        position: relative;
        flex: 0 0 auto;
    }

    .new-offers-tile .search {
        position: relative;
        width: fit-content;
        margin-top: -1px;
        font-family: var(--yield-exchange-text-styles-buttons-font-family);
        font-weight: var(--yield-exchange-text-styles-buttons-font-weight);
        color: var(--collection-1-yield-exchange-colors-yield-exchange-purple);
        font-size: var(--yield-exchange-text-styles-buttons-font-size);
        letter-spacing: var(--yield-exchange-text-styles-buttons-letter-spacing);
        line-height: var(--yield-exchange-text-styles-buttons-line-height);
        white-space: nowrap;
        font-style: var(--yield-exchange-text-styles-buttons-font-style);
    }

    .new-offers-tile .frame-3 {
        display: inline-flex;
        height: 21px;
        align-items: center;
        justify-content: center;
        padding: 5px;
        position: absolute;
        top: 15px;
    }

    .new-offers-tile .text-wrapper-7 {
        position: relative;
        width: fit-content;
        margin-top: -2.5px;
        margin-bottom: -1.5px;
        font-weight: 700;
        color: var(--collection-1-yield-exchange-colors-yield-exchange-purple);
        font-size: 12px;
        letter-spacing: 0;
        line-height: normal;
    }

    .small-button {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 10px 30px;
        position: relative;
        border-radius: 32px;
        overflow: hidden;
        border: 2px solid;
        border-color: var(--collection-1-yield-exchange-colors-yield-exchange-purple);
        all: unset;
        box-sizing: border-box;
    }

    .small-button .frame {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        position: relative;
        flex: 0 0 auto;
    }

    .small-button .search {
        position: relative;
        width: fit-content;
        margin-top: -1px;
        font-family: var(--yield-exchange-text-styles-buttons-font-family);
        font-weight: var(--yield-exchange-text-styles-buttons-font-weight);
        color: var(--collection-1-yield-exchange-colors-yield-exchange-purple);
        font-size: var(--yield-exchange-text-styles-buttons-font-size);
        letter-spacing: var(--yield-exchange-text-styles-buttons-letter-spacing);
        line-height: var(--yield-exchange-text-styles-buttons-line-height);
        white-space: nowrap;
        font-style: var(--yield-exchange-text-styles-buttons-font-style);
    }

    @media (max-width: 480px) {
        .text {
            font-size: 16px;
        }
    }
</style>
<script>
    import Avatar from 'vue-avatar';
    import TimerClock from "../TimerClock";
    export default {
        components: {
            TimerClock,
            Avatar
        },
        props: ['datum', 'primary_btn', 'formattedtimezone'],
        data() {
            return {

            }
        },

        methods: {
            formatNumberAbbreviated(number) {
                const SI_SYMBOL = ["", "K", "M", "G", "T", "P", "E"];

                const tier = (Math.log10(number) / 3) | 0;

                if (tier === 0) return number;

                const suffix = SI_SYMBOL[tier];
                const scale = Math.pow(10, tier * 3);

                const scaledNumber = number / scale;

                return scaledNumber.toFixed(0) + suffix;
            },
            viewOffer() {
                window.location.href = '/inv-camp-offers/' + this.datum?.id
            },
            addEllipsisIfNecessary(inputString, maxLength) {
                if (inputString.length <= maxLength) {
                    return inputString;
                } else {
                    return inputString.substring(0, maxLength) + '...';
                }
            },
            calculateTimeLeft(targetDate) {
                targetDate = new Date(targetDate);
                // Get the current date and time

                let currentTimestr = new Date().toLocaleString("en-US", { timeZone: this.formattedtimezone });
                var currentDate = new Date(currentTimestr);

                // Calculate the time difference in milliseconds
                var timeDifference = targetDate.getTime() - currentDate.getTime();

                // Calculate days, hours, and minutes left
                var daysLeft = Math.floor(timeDifference / (1000 * 60 * 60 * 24));
                var hoursLeft = Math.floor((timeDifference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutesLeft = Math.floor((timeDifference % (1000 * 60 * 60)) / (1000 * 60));

                // Determine which unit to use based on the magnitude of the time difference
                if (daysLeft > 0) {
                    return daysLeft + " day(s) left";
                } else if (hoursLeft > 0) {
                    return hoursLeft + " hour(s) left";
                } else {
                    return minutesLeft + " minute(s) left";
                }
            },
            pluralizeWord(word, count) {
                if (count === 1) {
                    return word; // Singular form
                } else {
                    // Assume the plural form is just adding 's' to the word
                    return word + 's'; // Plural form
                }
            }
        }
    }
</script>