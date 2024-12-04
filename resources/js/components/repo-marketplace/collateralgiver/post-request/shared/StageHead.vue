<template>
    <div class="w-100 d-flex gap-2 justify-content-center align-items-center">
        <!-- <img v-if="icon" :src="'/' + icon" alt="" srcset=""> -->
        <p class="p-0 m-0 stage-header-style text-capitalize">{{ title }}</p>


    </div>
</template>

<script>
export default {
    props: ['title', 'icon']
}

</script>

<style scoped>
/* .stage-header-style {
    color: #252525;
    font-family: Montserrat !important;
    font-size: 28px;
    font-style: normal;
    font-weight: 700;
    line-height: 32px;
    text-transform: capitalize;
} */

.stage-header-style {

    color: #5063F4;
    text-align: center;
    font-size: 40px;
    font-style: normal;
    font-weight: 800;
    line-height: 125.4%;
    /* text-decoration: c */
}

</style>