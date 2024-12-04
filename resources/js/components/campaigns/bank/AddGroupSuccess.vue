<template>
    <Modal :show="show" :hide_close="true">
        <div style="width: 100%; height: 100%; flex-direction: column; justify-content: center; align-items: center; gap: 40px; display: inline-flex">
            <div style="justify-content: flex-start; align-items: center; gap: 10px; display: inline-flex">
                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 60 60" fill="none">
                    <path d="M8.88219 22.8761C10.5109 15.9325 15.9325 10.5109 22.8761 8.88219C27.8906 7.70594 33.1094 7.70594 38.1239 8.88219C45.0675 10.5109 50.4891 15.9325 52.1178 22.8761C53.2941 27.8906 53.2941 33.1094 52.1178 38.1239C50.4891 45.0675 45.0675 50.4891 38.1239 52.1178C33.1094 53.2941 27.8906 53.2941 22.8761 52.1178C15.9325 50.4891 10.5109 45.0675 8.88219 38.1239C7.70594 33.1094 7.70594 27.8906 8.88219 22.8761Z" fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35"/>
                    <path d="M22 31L28 37L40 25" stroke="#5063F4" stroke-width="3.33" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div style="min-width: 423px; color: #2A2A2A; font-size: 28px; font-weight: 700; line-height: 32px; word-wrap: break-word">{{ name }} Group has been successfully {{ is_create ? 'created' : 'updated'}}!</div>
            </div>
            <img src="/assets/dashboard/icons/SuccessGroupAdd.svg" />
            <div style="padding-left: 90px; padding-right: 90px; justify-content: center; align-items: flex-start; gap: 50px; display: inline-flex">
                <div @click="$emit('newGroup',true)" style="padding: 15px 41px;border-radius: 20px; overflow: hidden; border: 1px #5063F4 solid; justify-content: center; align-items: center; gap: 4px; display: flex; cursor: pointer">
                    <div style="padding-left: 10px; padding-right: 10px; justify-content: center; align-items: flex-start; gap: 10px; display: flex">
                        <div style="color: #5063F4; font-size: 16px; font-weight: 500; text-transform: capitalize; line-height: 20px; word-wrap: break-word">Create New Group</div>
                    </div>
                </div>
                <div @click="$emit('viewGroup',true)" style="padding: 15px 41px;background: #5063F4; border-radius: 20px; overflow: hidden; justify-content: center; align-items: center; gap: 4px; display: flex; cursor: pointer">
                    <div style="padding-left: 10px; padding-right: 10px; justify-content: center; align-items: flex-start; gap: 10px; display: flex">
                        <div style="color: white; font-size: 16px; font-weight: 600; text-transform: capitalize; line-height: 20px; word-wrap: break-word">View Group</div>
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
        props: ['show','name','is_create']
    }
</script>