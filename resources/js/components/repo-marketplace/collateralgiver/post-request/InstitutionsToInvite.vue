<template>
    <div>
        <div class="mt-4">
            <Table :columns="columns" @selectedItems="selectedItems" @selectAllR="allselected"
                no-data-title="No Available collatel givers"
                no-data-message="This page will be updated once we have collateral givers on board" :data="table_data"
                :has_action='false' :select_all="select_all" @productDeletedAddNew="productDeletedAddNew"
                :selectable="selectable" :selected_items="getSelectedFis"
                :allselectable="(allselectablee) ? true : false" />
            <!-- <Pagination @click-next-page="getData" v-if="data && data.links" :data="data" /> -->
        </div>
    </div>

</template>


<script>
    import Pagination from '../../../shared/Table/Pagination.vue'
    import Table from '../../../shared/Table'
    import ShowCG from '../../shared/ShowCG.vue'
    import { capitalize } from '../../../../utils/commonUtils'
    import * as types from '../../../../store/modules/publishratesoffer/mutation-types'
    import { mapGetters } from 'vuex'


    export default {
        props: ['selected_items'],
        components: { Table, ShowCG },
        mounted() {
            this.getInvitedFis()
        },
        data() {
            return {
                table_data: null,
                // columns: ['Collateral Giver', 'Province', 'Credit Rating', 'Deposit Insurance'],
                columns: ['Institution', 'Credit Rating', 'Industry', 'Country', 'Relationship','No Of Baskets'], 
                allselectablee: true,
                selectable: true,
                select_all: false,
                // selected_items: null,
                allcgs: null
                // selected_items: []
            }
        },
        computed: {
            ...mapGetters('publishrateoffer', ['getSelectedFis', 'getFIS'])
        },
        methods: {
            capitalize(value) {
                return capitalize(value)
            },
            allselected(value) {
                // console.log(value)
            },
            async getInvitedFis() {
                // let invited = []
                // await axios.get('/common/trade/get-collateral-takers').then(response => {
                //     const FIs = response.data
                //     // console.log("Fis ", response.data.data)
                // });

                this.allcgs = this.getFIS
                // this.$store.commit('publishrateoffer/' + types.SET_FIS, this.allcgs);


                this.setTableDefaults()
                this.selectedItems(this.selected_items)

            },
            productDeletedAddNew() {

            },
            selectedItems(value) {
                if (value) {
                    let objlength = Object.values(value).length
                    if (objlength > 0)
                        if (objlength == this.table_data.length)
                            this.select_all = true
                        else
                            this.select_all = false

                    let prevVals = this.getAllSelectedFIS
                    // console.log(value, "Selected Values");

                    this.$store.commit('publishrateoffer/' + types.SET_SELECTED_FIS, value);
                }
                // this.$emit('selectedItems', value)
            },
            setTableDefaults() {
                let table_data = []
                this.allcgs.forEach(element => {
                    let fi = [
                        element.id,
                        () => {
                            return ({ 'component': ShowCG, 'attrs': { organization: element, orgname: element.name } });
                        },
                        element?.deposit_credit_rating ?
                            element?.deposit_credit_rating?.credit_rating?.description : '-',
                        element?.industry ?
                            element?.industry?.name : '-',
                        'Canada',
                        Number.parseFloat(element?.relationships) > 0 ? 'Existing' : 'New',
                        Number.parseFloat(element?.relationships)

                        // element.demographic_data.province,
                        // element.ratings.credit_rating,
                        // element.ratings.insurance_rating,
                        // element.industry_id ? capitalize(element?.industry?.name) : '-',
                    ]
                    table_data.push(fi)
                });
                this.table_data = table_data

            }
        }

    }
</script>