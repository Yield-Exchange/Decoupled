<template>
    <div style="justify-content: flex-start; align-items: flex-start; gap: 11px; display: flex">

        <div v-if="!showFilters" class="selected-filter"> <span> See More </span>
            <span class="button-remove" @click="showAllFilters(true)">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path
                        d="M5.09258 2.55636L5.10103 2.54792L5.10112 2.54802C5.31035 2.34857 5.58831 2.2373 5.87737 2.2373C6.16643 2.2373 6.4444 2.34857 6.65362 2.54802L13.6198 9.18542C13.6198 9.18543 13.6198 9.18543 13.6198 9.18543C13.7301 9.2905 13.8178 9.41686 13.8778 9.55687C13.9378 9.69687 13.9688 9.84761 13.9688 9.99992C13.9688 10.1522 13.9378 10.303 13.8778 10.443L13.42 10.2468L13.8778 10.443C13.8178 10.583 13.7301 10.7093 13.6198 10.8144L5.09258 2.55636ZM5.09258 2.55636L5.08889 2.56005C4.98569 2.65945 4.90339 2.7785 4.84684 2.91022C4.78932 3.0442 4.75965 3.18849 4.75965 3.3343C4.75965 3.48011 4.78932 3.6244 4.84684 3.75838C4.90429 3.89219 4.98832 4.01294 5.09385 4.11329C5.09398 4.11342 5.09411 4.11354 5.09425 4.11367L11.2737 10.0011L5.09425 15.8862C5.09418 15.8862 5.09411 15.8863 5.09404 15.8864C4.98842 15.9868 4.90432 16.1076 4.84684 16.2415C4.78932 16.3755 4.75965 16.5197 4.75965 16.6655C4.75965 16.8114 4.78932 16.9556 4.84684 17.0896C4.90339 17.2214 4.98571 17.3404 5.08892 17.4398L5.09259 17.4435L5.09249 17.4436L5.10112 17.4518C5.31035 17.6513 5.58831 17.7625 5.87737 17.7625C6.16639 17.7625 6.44432 17.6513 6.65353 17.4519C6.65356 17.4519 6.65359 17.4519 6.65362 17.4518L13.6198 10.8144L5.09258 2.55636Z"
                        fill="#5063F4" stroke="#5063F4" />
                </svg>
            </span>
        </div>
        <div v-else class="selected-filter"> <span> See Less </span>
            <span class="button-remove" @click="showAllFilters(true)">
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

export default {
    props: ['value', 'title', 'termLength'],
    mounted() {
        // console.log(this.inputText)
        // this.inputText = this.value
        // this.getMinMax(this.inputText)

    },
    updated() {
        // this.getWidth()

    },


    data() {
        return {
            show: true,
            statusText: '',
            inputText: null, showFilters: false
        }
    },
    methods: {
        showAllFilters(value) {
            this.$emit('showAllFilters', this.showFilters = !this.showFilters)
        },
        getWidth() {
            const childComponentWidth = this.$refs.filteredItemref.offsetWidth;
            // console.log('Child Component Width:', childComponentWidth);
        },

        getMinMax(value) {
            let i = 1
            this.statusText = null
            value.forEach(element => {
                if (i == 1 && element != 0 && element != null) {
                    this.statusText = this.title + " Min " + element
                } else if (i == 2) {
                    if (this.statusText != null && element != 0 && element != null) {
                        this.statusText = this.statusText + " and Max " + element
                    } else if (this.statusText != null && element == 0) {
                        this.statusText = this.statusText

                    } else if (this.statusText == null && element != 0 && element != null) {
                        this.statusText = this.title + " Max " + element
                    }
                }
                i = i + 1;
            });
        },
    },
    watch: {
        // value(newValue, oldvalue) {
        //     // console.log()
        //     this.getMinMax(newValue)
        //     // console.log('changed')
        // }
    },




}




</script>