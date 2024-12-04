<template>
    <Modal :show="show" :hide_close="true">
        <div style="width: 100%; height: 100%; flex-direction: column; justify-content: center; align-items: center; gap: 40px; display: inline-flex">
            <div style="justify-content: flex-start; align-items: center; gap: 10px; display: inline-flex">
                <svg xmlns="http://www.w3.org/2000/svg" width="61" height="60" viewBox="0 0 61 60" fill="none">
                    <path d="M9.38219 22.8761C11.0109 15.9325 16.4325 10.5109 23.3761 8.88219C28.3906 7.70594 33.6094 7.70594 38.6239 8.88219C45.5675 10.5109 50.9891 15.9325 52.6178 22.8761C53.7941 27.8906 53.7941 33.1094 52.6178 38.1239C50.9891 45.0675 45.5675 50.4891 38.6239 52.1178C33.6094 53.2941 28.3906 53.2941 23.3761 52.1178C16.4325 50.4891 11.0109 45.0675 9.38219 38.1239C8.20594 33.1094 8.20594 27.8906 9.38219 22.8761Z" fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35"/>
                    <path d="M22.5 31L28.5 37L40.5 25" stroke="#5063F4" stroke-width="3.33" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div style="width: 588px; color: #2A2A2A; font-size: 28px; font-weight: 700; line-height: 32px; word-wrap: break-word">Group successfully deleted</div>
            </div>
            <img src="/assets/dashboard/icons/Group.svg" />
            <div style="padding-left: 90px; padding-right: 90px; justify-content: center; align-items: flex-start; gap: 50px; display: inline-flex">
                <div @click="viewGroup" style="padding: 15px 41px;border-radius: 20px; overflow: hidden; border: 1px #5063F4 solid; justify-content: center; align-items: center; gap: 4px; display: flex; cursor: pointer">
                    <div style="padding-left: 10px; padding-right: 10px; justify-content: center; align-items: flex-start; gap: 10px; display: flex">
                        <div style="color: #5063F4; font-size: 16px; font-weight: 500; text-transform: capitalize; line-height: 20px; word-wrap: break-word">View Groups</div>
                    </div>
                </div>
            </div>
        </div>
    </Modal>
</template>
<script>
    import Modal from "../../shared/Modal";
    export default {
        components: {
            Modal
        },
        props: ['show'],
        methods: {
            viewGroup(){
                this.$emit('hideModal',true);
            },
        }
    }
</script>