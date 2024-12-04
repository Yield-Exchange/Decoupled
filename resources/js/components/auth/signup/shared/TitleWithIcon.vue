<template>
    <div class="d-flex justify-content-start align-items-center gap-2">
        <img class="icon-size" :src="'assets/signup/' + icon" alt="">
        <div class="title-text">{{ title }}
            <slot></slot>
        </div>

    </div>
</template>

<script>
export default {
    props: ['title', 'icon']
}
</script>

<style scoped>
.icon-size {
    /* width: 47px;
    height: 47px; */
}

.title-text {
    color: #2A2A2A;

    /* Yield Exchange Text Styles/Modal  & Blue BG Titles */
    font-family: Montserrat;
    font-size: 28px;
    font-style: normal;
    font-weight: 700;
    line-height: 32px;
    /* 114.286% */
    text-transform: capitalize;
}
</style>