<template>
    <div>
        <div class="d-flex flex-column align-items-start justify-content-start gap-2">
            <p class="p-0 m-0 view-card-header">{{ title }}</p>
            <p class="p-0 m-0 view-card-desc" v-if="desc">{{ desc }}</p>
            <slot></slot>

        </div>
    </div>

</template>


<script>

export default {
    props: ['title', 'desc']
}
</script>
<style scoped>
.view-card-header {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Blue, var(--Yield-Exchange-Colors-Yield-Exchange-Purple, #5063F4));
    font-feature-settings: 'clig' off, 'liga' off;

    /* Yield Exchange Text Styles/Promotion Chart titles */
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 162.5% */
    text-transform: capitalize;
}

.view-card-desc {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Black, var(--Yield-Exchange-Colors-Darks, #252525));
    font-feature-settings: 'clig' off, 'liga' off;

    /* Yield Exchange Text Styles/Promotion chart body */
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 26px;
    /* 162.5% */
    text-transform: capitalize;
}
</style>