<template>
    <Modal :show="show" :hide_close="true" @isVisible="close" custom-modal-class="transaction-failed-modal" modalsize="">
        <div style="justify-content: flex-start; align-items: center; gap: 10px; display: inline-flex">
            <div style="width: 60px; height: 60px; position: relative">
                <svg xmlns=" http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 60 60" fill="none">
                    <path
                        d="M8.88219 22.8761C10.5109 15.9325 15.9325 10.5109 22.8761 8.88219C27.8906 7.70594 33.1094 7.70594 38.1239 8.88219C45.0675 10.5109 50.4891 15.9325 52.1178 22.8761C53.2941 27.8906 53.2941 33.1094 52.1178 38.1239C50.4891 45.0675 45.0675 50.4891 38.1239 52.1178C33.1094 53.2941 27.8906 53.2941 22.8761 52.1178C15.9325 50.4891 10.5109 45.0675 8.88219 38.1239C7.70594 33.1094 7.70594 27.8906 8.88219 22.8761Z"
                        fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35" />
                    <path d="M30.0015 26.5254H33V33.4004H30.0015V26.5254ZM30 34.7754H32.9985V37.5254H30V34.7754Z"
                        fill="#5063F4" />
                    <path
                        d="M33.4602 18.0118C32.9758 17.095 32.0323 16.5254 30.9996 16.5254C29.967 16.5254 29.0234 17.095 28.5391 18.0132L18.3267 37.4161C18.1001 37.8422 17.9879 38.3203 18.001 38.8033C18.0142 39.2864 18.1524 39.7576 18.4019 40.1705C18.6478 40.5854 18.997 40.9285 19.4151 41.1661C19.8332 41.4037 20.3057 41.5275 20.7859 41.5254H41.2134C42.1987 41.5254 43.0908 41.0187 43.5987 40.1705C43.8479 39.7574 43.9858 39.2863 43.999 38.8033C44.0121 38.3204 43.9001 37.8423 43.6739 37.4161L33.4602 18.0118ZM20.7859 38.7261L30.9996 19.3232L41.2203 38.7261H20.7859Z"
                        fill="#5063F4" />
                </svg>
            </div>
            <div
                style="width: 474px; color: #2A2A2A; font-size: 28px; font-weight: 700; text-transform: capitalize; line-height: 32px; word-wrap: break-word">
                {{ title_ ? title_ : "Transaction Error" }}</div>
        </div>
        <div
            style="align-self: stretch; color: black; font-size: 18px; font-weight: 500; word-wrap: break-word; margin-bottom:30px;margin-top:20px;">
            {{
                error }}
        </div>
        <!-- <div
            style="align-self: stretch; justify-content: flex-end; align-items: center; gap: 50px; display: inline-flex; width: 100%;margin-top: 20px">
            <div style="padding: 10px 30px;background: #5063F4; border-radius: 20px; overflow: hidden; justify-content: center; align-items: center; display: flex; cursor: pointer"
                @click="toggleShow(false)">
                <div
                    style="padding-left: 10px; padding-right: 10px; justify-content: center; align-items: flex-start; gap: 10px; display: flex">
                    <div
                        style="color: white; font-size: 16px; font-weight: 600; text-transform: capitalize; line-height: 20px; word-wrap: break-word">
                        Edit Value</div>
                </div>
            </div>
        </div> -->
    </Modal>
</template>
<style>
.transaction-failed-modal .modal-content {
    max-width: 500px !important;
}
</style>
<script>
import Modal from "../../../shared/Modal";
export default {
    components: {
        Modal
    },
    props: ['show', 'error', 'title_'],
    data() {
        return {
        };
    },
    methods: {
        close() {
            // this.show = false
            console.log("clicke")
            this.$emit('toggleShow', false)
        }

    }
}
</script>