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
import { mapGetters } from 'vuex'

export default {
    props: ['value', 'title', 'termLengthType'],
    mounted() {
        // console.log(this.inputText)
        this.inputText = this.value
        if (this.value != null) {
            this.getMinMax(this.inputText)
            // console.log(this.value,"Value")
        }

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
    computed: {
        ...mapGetters('repopostrequest', ['getsettlemntdate']),
    },
    methods: {
        removeSingleItem(value) {
            this.$emit('removeSingleItem', value)
        },
        getWidth() {
            const childComponentWidth = this.$refs.filteredItemref.offsetWidth;
            console.log('Child Component Width:', childComponentWidth);
        },

        getMinMax(value) {
            let i = 1
            this.statusText = null
            let title = this.title.toLowerCase();
            if (!['products', 'status', 'investor'].some(keyword => title.includes(keyword))) {
                value.forEach(element => {
                    if (i == 1 && element != 0 && element != null) {
                        if (title == "term_length") {
                            this.statusText = "Term Length  Min " + element + " " + this.termLengthType
                            this.minvalue = "Term Length " + element
                        } else if (title == "lockoutperiod") {
                            this.statusText = "Lockout  Min " + element + " Days"
                            this.minvalue = "Lockout " + element
                        } else {
                            this.statusText = this.title.replace(/_/g, ' ') + " Min " + element
                            this.minvalue = this.title.replace(/_/g, ' ') + " " + element

                        }
                    } else if (i == 2) {
                        if (this.statusText != null && element != 0 && element != null) {
                            if (title == "termlength") {
                                this.statusText = this.minvalue + " - " + element + " " + this.termLengthType
                            } else if (title == "lockoutperiod") {
                                this.statusText = this.minvalue + " - " + element + " Days"

                            } else {
                                this.statusText = this.minvalue + " - " + element
                            }
                        } else if (this.statusText != null && element == 0) {
                            this.statusText = this.statusText

                        } else if (this.statusText == null && element != 0 && element != null) {
                            // this.statusText = this.title + " Max " + element
                            if (title == "termlength") {
                                this.statusText = this.title.replace(/_/g, ' ') + " Max " + element + " " + this.termLengthType
                            } else if (title == "lockoutperiod") {
                                this.statusText = this.title.replace(/_/g, ' ') + " Max " + element + " Days"

                            } else {
                                this.statusText = this.title.replace(/_/g, ' ') + " Max " + element
                            }

                        }
                    }
                    i = i + 1;
                });
            } else if (title == 'settlement') {
                console.log(value[0] == 'null', 'value')
                if (value[0] != null && value[0] != '' && value != 'null') {
                    let founditem = this.getsettlemntdate.find(item => item.id == value)
                    this.statusText = "Settlement : " + founditem.formated_date
                }

            } else {
                value.forEach(element => {
                    // console.log(element)
                    if (element != 0 && element != null && this.statusText == null) {
                        if (title == 'investor')
                            this.statusText = "Investors : " + element
                        else
                            this.statusText = this.title + " : " + element
                    } else {
                        this.statusText = this.statusText + " , " + element
                    }
                });
            }
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