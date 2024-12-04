<template>
    <div>
        <FeatureUnfeatureProduct @reloadData="reloadData" :actionObjectDetails="actionObjectDetails" :actionId="actionId"
            variantColor="rgba(68, 224, 170, 0.60);" textColor="#252525" action="Un Feature" />
    </div>
</template>
<script>
import FeatureUnfeatureProduct from "./FeatureUnfeatureProduct";
export default {
    components: { FeatureUnfeatureProduct },
    props: ['actionId', "actionObjectDetails"],
    methods: {
        reloadData() {
            this.$emit('reloadData', true)
        }
    }
}
</script>