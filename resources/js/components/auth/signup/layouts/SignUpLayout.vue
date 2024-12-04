<template>
    <div class="d-flex">
        <div class="signup-sidebar">
            <MainSidebar></MainSidebar>

        </div>
        <div class="signup-content w-100">
            <Header></Header>

            <slot></slot>

        </div>

    </div>
</template>


<script>
import Header from './Header.vue';
import MainSidebar from './MainSidebar.vue';
export default {
    components: { MainSidebar, Header }
}

</script>

<style scoped>
.signup-sidebar {
    background-image: url('/assets/signup/signup-sidebar-bg.svg');

    width: 296px;
    /* height: 100vh; */
    /* background-color: red; */
    height: auto;
    min-height: 100vh;
}
</style>