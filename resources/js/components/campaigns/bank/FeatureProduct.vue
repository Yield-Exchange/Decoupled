<template>
    <div>
        <FeatureUnfeatureProduct :bt="bt" @reloadData="reloadData" :actionObjectDetails="actionObjectDetails" :actionId="actionId" action="Feature" variantColor="" textColor="" />
    </div>
</template>
<script>
    import FeatureUnfeatureProduct from "./FeatureUnfeatureProduct";
    export default {
        components: {FeatureUnfeatureProduct},
        props: ['actionId','bt','actionObjectDetails'],
        methods: {
            reloadData(){
                this.$emit('reloadData',true)
            }
        }
    }
</script>