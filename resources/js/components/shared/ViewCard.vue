<template>
    <div>
        <div class="d-flex flex-column align-items-start justify-content-start gap-2">

            <div class="d-flex gap-2">
                <p class="p-0 m-0 view-card-header">{{ title }}</p>
                <template v-if="hastooltip">
                    <img src="/assets/dashboard/icons/helpicon.svg" :id="'summary' + id" alt="" srcset="">
                    <Tooltip v-if="true" :message="tooltipmessage" :target="'summary' + id" />
                </template>

            </div>
            <p class="p-0 m-0 view-card-desc" :class="is_red ? 'view-card-red' : ''" v-if="desc">{{ desc }}</p>
            <slot></slot>

        </div>
    </div>

</template>


<script>
import Tooltip from './Tooltip.vue';

export default {
    props: ['title', 'desc', 'is_red','hastooltip','tooltipmessage' ,'id'],
    components:{Tooltip}
}
</script>
<style scoped>
.view-card-red {
    color: #FF2E2E !important;
}

.view-card-desc {
    color: #5063F4;
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    text-transform: capitalize;
}

.view-card-header {
    color: #252525;
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 500;
    line-height: 26px;
    text-transform: capitalize;
}
</style>