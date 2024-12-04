<template>
    <div class="new-features-tile">
        <div class="frame">
            <div style="text-transform: capitalize" class="element-day-non">{{ datum?.lockout_period > 0 ?
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
                    style="width: 100%; align-self: stretch; padding-top: 10px; padding-bottom: 10px; justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex; cursor: pointer">
                    <div
                        style="flex: 1 1 0; align-self: stretch; justify-content: flex-start; align-items: flex-start; gap: 10px; display: flex">

                        <div @click="viewOffer"
                            style="width: 100%; height: 100%; padding-left: 30px; padding-right: 30px; padding-top: 10px; padding-bottom: 10px; background: #5063F4; border-radius: 32px; overflow: hidden; flex-direction: column; justify-content: center; align-items: center; display: inline-flex; cursor: pointer">
                            <div style="justify-content: center; align-items: center; gap: 4px; display: inline-flex">
                                <div
                                    style="color: white; font-size: 16px;  font-weight: 600; text-transform: capitalize; line-height: 20px; word-wrap: break-word">
                                    View More</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div style="display: flex; flex-direction: row; justify-content: flex-end; width: 100%;">
            <div class="features-ribbon">
                <div class="overlap-group">
                    <div class="text-wrapper-7">Featured</div>
                </div>
            </div>
            <div class="element-day-left-wrapper">
                <div class="element-day-left">{{ calculateTimeLeft(datum.expiry_date) }}</div>
            </div>
        </div>
    </div>
</template>
<style scoped>
    .new-features-tile {
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

    .new-features-tile .frame {
        display: flex;
        flex-direction: column;
        width: 226px;
        align-items: flex-start;
        gap: 5px;
        padding: 28px 0px 0px;
        position: relative;
        flex: 0 0 auto;
    }

    .new-features-tile .element-day-non {
        position: relative;
        align-self: stretch;
        height: 28px;
        margin-top: -1px;
        font-weight: 900;
        color: var(--yield-exchange-palletteyield-exchange-black);
        font-size: 16px;
        letter-spacing: 0;
        line-height: normal;
    }

    .new-features-tile .element {
        position: relative;
        align-self: stretch;
        font-weight: 700;
        color: #5063f4;
        font-size: 50px;
        letter-spacing: 0;
        line-height: normal;
    }

    .new-features-tile .div {
        display: flex;
        align-items: flex-start;
        gap: 5px;
        position: relative;
        align-self: stretch;
        width: 100%;
        flex: 0 0 auto;
    }

    .new-features-tile .text-wrapper {
        position: relative;
        height: 28px;
        margin-top: -1px;
        font-weight: 700;
        color: var(--yield-exchange-palletteyield-exchange-black);
        font-size: 14px;
        letter-spacing: 0;
        line-height: normal;
    }

    .new-features-tile .text-wrapper-2 {
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

    .new-features-tile .text-wrapper-3 {
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

    .new-features-tile .text-wrapper-4 {
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

    .new-features-tile .text-wrapper-5 {
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

    .new-features-tile .frame-wrapper {
        display: flex;
        align-items: center;
        gap: 5px;
        position: relative;
        align-self: stretch;
        width: 100%;
        flex: 0 0 auto;
    }

    .new-features-tile .frame-2 {
        display: inline-flex;
        align-items: center;
        position: relative;
        flex: 0 0 auto;
    }

    .new-features-tile .istockphoto {
        position: relative;
        width: 44px;
        height: 44px;
    }

    .new-features-tile .text-wrapper-6 {
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

    .new-features-tile .div-wrapper {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        position: relative;
        align-self: stretch;
        width: 100%;
        flex: 0 0 auto;
    }

    .new-features-tile .small-button-wrapper {
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

    .new-features-tile .small-button {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 10px 30px;
        position: relative;
        flex: 1;
        flex-grow: 1;
        background-color: var(--collection-1-yield-exchange-colors-yield-exchange-purple);
        border-radius: 32px;
        overflow: hidden;
        all: unset;
        box-sizing: border-box;
    }

    .new-features-tile .search-wrapper {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        position: relative;
        flex: 0 0 auto;
    }

    .new-features-tile .search {
        position: relative;
        width: fit-content;
        margin-top: -1px;
        font-family: var(--yield-exchange-text-styles-buttons-bold-font-family);
        font-weight: var(--yield-exchange-text-styles-buttons-bold-font-weight);
        color: #ffffff;
        font-size: var(--yield-exchange-text-styles-buttons-bold-font-size);
        letter-spacing: var(--yield-exchange-text-styles-buttons-bold-letter-spacing);
        line-height: var(--yield-exchange-text-styles-buttons-bold-line-height);
        white-space: nowrap;
        font-style: var(--yield-exchange-text-styles-buttons-bold-font-style);
    }

    .new-features-tile .features-ribbon {
        position: absolute;
        width: 112px;
        height: 22px;
        top: 23px;
        left: 1px;
    }

    .new-features-tile .overlap-group {
        position: relative;
        width: 115px;
        height: 21px;
        top: 1px;
        left: -1px;
        background-image: url(https://c.animaapp.com/lf20PqCu/img/rectangle-5639-1.svg);
        background-size: 100% 100%;
    }

    .new-features-tile .text-wrapper-7 {
        position: absolute;
        width: 66px;
        height: 16px;
        top: 1px;
        left: 25px;
        font-family: var(--yield-exchange-text-styles-table-titles-font-family);
        font-weight: var(--yield-exchange-text-styles-table-titles-font-weight);
        color: var(--yield-exchange-palletteyield-exchange-white);
        font-size: var(--yield-exchange-text-styles-table-titles-font-size);
        letter-spacing: var(--yield-exchange-text-styles-table-titles-letter-spacing);
        line-height: var(--yield-exchange-text-styles-table-titles-line-height);
        white-space: nowrap;
        font-style: var(--yield-exchange-text-styles-table-titles-font-style);
    }

    .new-features-tile .element-day-left-wrapper {
        display: inline-flex;
        height: 21px;
        align-items: center;
        justify-content: center;
        padding: 5px;
        position: absolute;
        top: 24px;
    }

    .new-features-tile .element-day-left {
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