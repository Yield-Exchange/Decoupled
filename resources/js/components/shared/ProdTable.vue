<template>
    <div>
        <div class="">
            <table class="table table-vue-custom" style="border:0px !important;">
                <Header :columns='columns' :has_action='has_action' :selectable="selectable"
                    :allselectable="allselectable" @selectAllR="selectAllR" :select_all="select_all"
                    @selectAllUpdated="selectAllUpdated" :data="data" :selected_items="selected_items"
                    @selectAllRO="selectAllRO" :nonRenderbleColumns="nonRenderbleColumns" />
                <Data v-if="!is_loading && data?.length > 0"
                    @productDeletedAddNew="$emit('productDeletedAddNew', new Date())" :columns="columns"
                    @reloadData="$emit('reloadData', $event)" :selectAllRecords="selectAllRecords"
                    @selectedItems="channelSelectedItems($event)" :data="data" :has_action='has_action'
                    :selectable="selectable" :selected_items="selected_items" :actions='actions'
                    :nonRenderbleItems="nonRenderbleItems" />
            </table>
            <LoadingData v-if="is_loading" />
            <NoData :title="noDataTitle" :message="noDataMessage" v-if="!is_loading && data?.length === 0" />
        </div>
    </div>
</template>
<style scoped>
    .table-vue-custom {
        background: white;

    }
</style>
<script>
    import Header from "../shared/Table/ProdHeader";
    import Data from "../shared/Table/ProdData";
    import NoData from "./Table/NoData";
    import LoadingData from "./Table/LoadingData";
    export default {
        mounted() {
        },
        components: {
            NoData,
            Header,
            Data,
            LoadingData
        },
        created() {
        },
        methods: {
            channelSelectedItems(selected) {
                console.log("Selected oItems", selected);
                this.$emit("selectedItems", selected);
            },
            selectAllUpdated(val) {
                this.$emit("selectAllUpdated", val);
            },
            selectAllR() {
                this.$emit("selectAllR", new Date());
                this.selectAllRecords = !this.selectAllRecords;
            },
            selectAllRO(val) {

                this.selectAllRecords = val;
            }

        },
        props: ['nonRenderbleColumns', 'nonRenderbleItems', 'columns', 'data', 'has_action', 'actions', 'selectable', 'selected_items', 'is_loading', 'noDataTitle', 'noDataMessage', 'allselectable', 'select_all', 'isUngroupedDEpos'],
        data() {
            // console.log("On Table itself",this.data);

            return {
                selectAllRecords: false
            }
        }
    }
</script>