<template>
    <AddProductForm @reloadData="$emit('reloadData',$event)" action="edit" :products="products" :product_id="actionId" />
</template>
<script>
    import AddProductForm from "./AddProductForm";
    export default {
        mounted() {

        },
        props:['products','actionId'],
        components: {AddProductForm},
        data(){
            return {
                data: null
            }
        },
        methods:{

        }
    }
</script>