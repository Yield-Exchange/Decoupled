<template>
    <div class="bg-red">
        <div class="d-flex gap-2 justify-content-start align-items-center" v-if="has_icon">
            <div style="width: 52px;height: 52px;flex-shrink: 0; border-radius: 100%; padding: 5px ;border: 2px solid #D9D9D9; cursor: pointer;"
                @click="show = true" class="d-flex justify-content-center align-items-center">
                <avatar v-if="!organization?.logo" :size="40" :color="'white'" :backgroundColor="'#4975E3'"
                    :initials="organization?.name[0]"></avatar>
                <img v-else style="width: 40px;height: 40px; border-radius: 100%"
                    :src="'/image/' + organization?.logo" />
            </div>
            <p class="m-0 p-0 show-cg-class-with-icon" @click="show = true">{{ organization?.name }}</p>
        </div>
        <p v-else class="m-0 p-0 show-cg-class" @click="show = true">{{ organization?.name }}</p>
        <AboutCG :organization="organization ? organization : null" @closemodal="show = $event" :show="show" />
        <slot></slot>
    </div>
</template>
<script>
import AboutCG from './AboutCG.vue';
import Modal from '../../shared/Modal.vue'
import Avatar from 'vue-avatar';

export default {
    components: { AboutCG, Modal, Avatar },
    props: ['organization', 'orgname', 'has_icon'],
    data() {
        return {
            show: false
        }
    }
}
</script>
<style scoped>
.show-cg-class-with-icon {
    color: #0F3D6F;
    font-family: Montserrat !important;
    font-size: 14px;
    font-style: normal;
    font-weight: 600;
    line-height: normal;
    cursor: pointer;
}

.show-cg-class {
    color: #5063F4 !important;
    font-family: Montserrat !important;
    font-size: 15px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    text-decoration-line: underline;
    text-transform: capitalize;
    cursor: pointer;
}
</style>