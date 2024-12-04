<template>
    <div style="width:100% !important">
        <div style="margin-bottom: 10px">           
            <accordion :is_open="is_open" title="My Products" width="100" title_image="/assets/dashboard/icons/Setting - 4.svg" />
            <div style="height: 10px"></div>
            <AddProductForm class="pr-2" :products="products" @productUpdated="refreshData=$event" :showmodal="showwaddprodModal" />
        </div>

        <div class="pr-2">
            <AddProductList :sync="refreshData" :products="products"  @productDeletedAddNew="showwaddprodModal= true" />
        </div>
    </div>
</template>
<script>
    import Table from "../../shared/Table";
    import AddProductList from "./AddProductList";
    import AddProductForm from "./AddProductForm";
    import Accordion from "../../shared/Accordion";

    export default{
        mounted() {
        },
        components: {
            Table,
            AddProductList,
            AddProductForm,
            Accordion
        },
        created() {
        },
        methods:{
            poductDeleted(){
               
            }
        },
        props: ['products'],
        data() {
            return {
                showwaddprodModal:false,
                refreshData: null,
                is_open:false
            }
        }
    }
</script>