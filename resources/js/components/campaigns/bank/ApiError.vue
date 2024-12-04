<template>
    <Modal :show="show" :hide_close="true">
        <div style="width: 100%; height: 100%; flex-direction: column; justify-content: center; align-items: center; gap: 31px; display: inline-flex">
            <div style="width: 324px; justify-content: flex-start; align-items: center; gap: 10px; display: inline-flex">
                <svg xmlns="http://www.w3.org/2000/svg" width="61" height="61" viewBox="0 0 61 61" fill="none">
                    <path d="M9.38219 23.3507C11.0109 16.4071 16.4325 10.9855 23.3761 9.3568C28.3906 8.18054 33.6094 8.18055 38.6239 9.3568C45.5675 10.9855 50.9891 16.4071 52.6178 23.3507C53.7941 28.3653 53.7941 33.584 52.6178 38.5985C50.9891 45.5421 45.5675 50.9637 38.6239 52.5924C33.6094 53.7687 28.3906 53.7687 23.3761 52.5924C16.4325 50.9637 11.0109 45.5421 9.38219 38.5985C8.20594 33.584 8.20594 28.3652 9.38219 23.3507Z" fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35"/>
                    <path d="M30.5015 27H33.5V33.875H30.5015V27ZM30.5 35.25H33.4985V38H30.5V35.25Z" fill="#5063F4"/>
                    <path d="M33.9602 18.4864C33.4758 17.5696 32.5323 17 31.4996 17C30.467 17 29.5234 17.5696 29.0391 18.4878L18.8267 37.8907C18.6001 38.3168 18.4879 38.7949 18.501 39.2779C18.5142 39.761 18.6524 40.2322 18.9019 40.6451C19.1478 41.06 19.497 41.4031 19.9151 41.6407C20.3332 41.8783 20.8057 42.0021 21.2859 42H41.7134C42.6987 42 43.5908 41.4933 44.0987 40.6451C44.3479 40.232 44.4858 39.7609 44.499 39.2779C44.5121 38.795 44.4001 38.3169 44.1739 37.8907L33.9602 18.4864ZM21.2859 39.2007L31.4996 19.7978L41.7203 39.2007H21.2859Z" fill="#5063F4"/>
                </svg>
                <div style="width: 393px; color: #2A2A2A; font-size: 28px; font-weight: 700; line-height: 32px; word-wrap: break-word">{{ title }}</div>
            </div>
            <img src="/assets/dashboard/icons/487_online_protection_outline.svg" />
            <div style="width: 581px; text-align: center; color: #252525; font-size: 22px; font-weight: 400; text-transform: capitalize; line-height: 26px; word-wrap: break-word">{{ message }}</div>
            <div style="padding-left: 90px; padding-right: 90px; justify-content: center; align-items: flex-start; gap: 50px; display: inline-flex">
                <div @click="$emit('cancelled',true)" style="padding: 15px 41px;border-radius: 20px; overflow: hidden; border: 1px #5063F4 solid; justify-content: center; align-items: center; gap: 4px; display: flex; cursor: pointer">
                    <div style="padding-left: 10px; padding-right: 10px; justify-content: center; align-items: flex-start; gap: 10px; display: flex">
                        <div style="color: #5063F4; font-size: 16px;font-weight: 500; text-transform: capitalize; line-height: 20px; word-wrap: break-word">Report Bug</div>
                    </div>
                </div>
                <div @click="$emit('cancelled',true)" style="padding: 15px 41px;background: #5063F4; border-radius: 20px; overflow: hidden; justify-content: center; align-items: center; gap: 4px; display: flex; cursor: pointer">
                    <div style="padding-left: 10px; padding-right: 10px; justify-content: center; align-items: flex-start; gap: 10px; display: flex">
                        <div style="color: white; font-size: 16px;font-weight: 600; text-transform: capitalize; line-height: 20px; word-wrap: break-word">Previous</div>
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
        props: ['title','message','show']
    }
</script>