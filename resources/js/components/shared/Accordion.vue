<template>
    <div v-if="width !== 100"
        :style="{ 'width': '100%', 'padding-left': '3px', 'background': '#EFF2FE', 'justify-content': 'flex-start', 'align-items': 'center', 'display': 'inline-flex' }">
        <div style="justify-content: space-between; display: inline-flex;">
            <div class="page-title">
                <div class="title-icon">
                    <img :src="title_image" />
                </div>
                <div class="text-div">{{ title }}</div>
            </div>

        </div>

    </div>
    <div v-else
        style="width: 100%; padding-left: 3px; background: #EFF2FE; justify-content: flex-start; align-items: center; display: inline-flex">
        <div style="justify-content: space-between; display: inline-flex;">
            <div class="page-title">
                <div class="title-icon">
                    <img src="/assets/dashboard/icons/PromoOffer.svg" />
                </div>
                <div class="text-div">{{ title }}</div>
            </div>

        </div>

    </div>
</template>
<script>
export default {
    props: ['title', 'title_image', 'is_open', 'width'],
    data() {
        return {

        }
    },
    computed: {

    },
    methods: {

    }
}
</script>