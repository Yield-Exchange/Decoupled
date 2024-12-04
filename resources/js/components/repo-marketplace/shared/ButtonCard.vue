<template>
    <div class="d-flex justify-content-center">
        <div @click="cardClicked" :class="(hover || selected ? 'headerHover' : 'acc-type-header')"
            @mouseleave="hover = false" :style="{ width: (width ? width : '600px') + ' !important' }"
            @mouseenter="hover = true">
            <div class="d-flex">
                <img :src="'/' + image" width="40" height="40" alt="">

                <h2 class="acc-header-title" :class="(hover || selected ? 'hovered-text ' : 'not-hovered-text')">{{
            title }}</h2>
                <!-- <img src="assets/signup/yie-Logo.svg" alt=""> -->

            </div>
            <hr>
            <p class="acc-header-desc" :class="(hover || selected ? 'hovered-text ' : 'not-hovered-text')"> {{ desc
                }} </p>
        </div>
    </div>
</template>
<script>
export default {
    props: ['title', 'desc', 'image', 'stage', 'selected', 'width'],
    data() {
        return {
            message: "choose Account",
            hover: false
        }
    },
    methods: {
        cardClicked() {
            this.$emit('click', true)
        }
    },
    watch: {

    }
}
</script>
<style scoped>
.acc-type-header {
    background-color: white;
    border-radius: 4px;
    /* background: #FFF; */
    box-shadow: 0px 2px 6px 0px rgba(80, 99, 244, 0.15);
    padding: 10px;
    margin-bottom: 10px;
    cursor: pointer;
}

.headerHover {
    /* background-color: white; */
    border-radius: 4px;
    /* background: #FFF; */
    box-shadow: 0px 2px 6px 0px rgba(80, 99, 244, 0.15);
    padding: 10px;
    margin-bottom: 10px;
    background-color: #5063F4 !important;
    cursor: pointer;
}

.acc-header-title {

    /* Yield Exchange Text Styles/Modal  & Blue BG Titles */
    font-family: Montserrat;
    font-size: 28px;
    font-style: normal;
    font-weight: 700;
    line-height: 32px;
    /* 114.286% */
    text-transform: capitalize;
    margin-left: 10px;
}

.not-hovered-text {
    color: #252525;
}

.hovered-text {
    color: white;
}

.acc-header-desc {
    font-family: Montserrat;
    font-size: 16px;
    font-style: normal;
    font-weight: 300;
    line-height: normal;
}
</style>