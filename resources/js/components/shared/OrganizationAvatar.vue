<template>
    <div :style="pStyle">
        <avatar :style="cStyle" v-if="!org.logo" :size="size ? size : 80" :color="'white'" :backgroundColor="'#4975E3'"
                :initials="org.name[0]"/>
        <avatar :style="cStyle" v-if="org.logo" :size="size ? size : 80" :color="'white'" :backgroundColor="'#4975E3'"
                :src="'/image/'+org.logo"/>
    </div>
</template>
<script>
    import Avatar from "vue-avatar";
    export default {
        name: 'OrganizationAvatar',
        components: {
            Avatar
        },
        props: ['organization','size','pStyle','cStyle'],

        data() {
            return {
                org: this.organization
            }
        },
        methods: {
        }
    }
</script>