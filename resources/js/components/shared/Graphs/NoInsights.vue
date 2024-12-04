<template>
    <div
        style="display: flex; flex-direction:column ; justify-content: flex-end; margin-top: 5spx; gap: 0.5%; width: 100%; align-items: center;padding: 30px;">
        <img :src="`/assets/dashboard/icons/${image}`" :style="{ maxHeight: maxheight + 'px', height: height + 'px' }" alt=""
            srcset="">
        <div class=" d-flex flex-column justify-content-center align-items-center">
            <p class="title">{{ title ? title : '' }}</p>
            <p class="description" style="text-align: center:  !important;;">{{ desc }}
            </p>
        </div>
    </div>
</template>

<script>
export default {
    props: ['image', 'desc', 'title', 'maxheight', 'height'],
    data() {
        return {

        }
    }
}
</script>

<style scoped>
.description {
    color: var(--yield-exchange-pallette-yield-exchange-black, var(--yield-exchange-colors-darks, #252525));

    /* Yield Exchange Text Styles/Table Body */
    font-family: Montserrat;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    text-align: center !important;
    /* max-width: 200px */
}

.title {
    color: var(--yield-exchange-pallette-yield-exchange-black, var(--yield-exchange-colors-darks, #252525));
    text-align: center;
    font-feature-settings: 'clig' off, 'liga' off;

    /* Yield Exchange Text Styles/Chart Titles */
    font-family: Montserrat;
    font-size: 22px;
    font-style: normal;
    font-weight: 600;
    line-height: 26px;
    /* 118.182% */
    text-transform: capitalize;

}
</style>