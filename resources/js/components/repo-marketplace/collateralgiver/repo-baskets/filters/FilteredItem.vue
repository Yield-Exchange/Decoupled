<template>
    <div ref="filteredItemref" style="justify-content: flex-start; align-items: flex-start; gap: 11px; display: flex">
        <div class="selected-filter"> <span>{{ statusText }} </span>
            <span class="button-remove" @click="removeSingleItem(title)">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="19" viewBox="0 0 20 19" fill="none">
                    <path d="M15 4L5 14M5 4L15 14" stroke="#5063F4" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </span>
        </div>
    </div>
</template>


<style scoped>
.selected-filter {
    border-radius: 8px;
    background: var(--Yield-Exchange-Colors-Yield-Exchange-Light-Purple, #EFF2FE);
    border-radius: 8px;
    background: var(--Yield-Exchange-Colors-Yield-Exchange-Light-Purple, #EFF2FE);
    color: var(--Yield-Exchange-Colors-Yield-Exchange-Purple, #5063F4);

    /* Yield Exchange Text Styles/Table Body */
    font-family: Montserrat;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    display: flex;
    padding: 12px;
    flex-direction: row;
    align-items: center;
    gap: 4px;
    text-transform: capitalize;
}

.button-remove {
    cursor: pointer;
}
</style>

<script>
import { capitalize } from '../../../../../utils/commonUtils'


export default {
    props: ['value', 'title', 'termLengthType'],
    mounted() {
        // console.log(this.inputText)
        this.inputText = this.value
        this.getMinMax(this.inputText)

    },
    updated() {
        // this.getWidth()

    },


    data() {
        return {
            show: true,
            statusText: '',
            inputText: null,
            minvalue: null
        }
    },
    methods: {
        removeSingleItem(value) {
            this.$emit('removeSingleItem', value)
        },
        getWidth() {
            const childComponentWidth = this.$refs.filteredItemref.offsetWidth;
            // console.log('Child Component Width:', childComponentWidth);
        },

        getMinMax(value) {

            this.statusText = null
            let title = this.title.toLowerCase();
            if (title == 'baskettype')
                title = "Basket Type"
            if (title == 'collateralname')
                title = "collateral"
            if (title == 'status')
                value = capitalize(value)
            if (title == 'status'){
                value = capitalize(value)
            }
            this.statusText = capitalize(title) + " " + value

        },
    },
    watch: {
        value(newValue, oldvalue) {
            // console.log()
            this.getMinMax(newValue)
            // console.log('changed')
        }
    },




}




</script>