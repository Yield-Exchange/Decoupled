<template>
    <div>
        <Modal :show="show" :hide_close="true">
            <div style="width: 100%; height: 100%; flex-direction: column; justify-content: center; align-items: center; gap: 50px; display: inline-flex">
                <div style="justify-content: flex-start; align-items: center; gap: 10px; display: inline-flex">
                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="61" viewBox="0 0 60 61" fill="none">
                        <path d="M8.88219 23.3761C10.5109 16.4325 15.9325 11.0109 22.8761 9.38219C27.8906 8.20594 33.1094 8.20594 38.1239 9.38219C45.0675 11.0109 50.4891 16.4325 52.1178 23.3761C53.2941 28.3906 53.2941 33.6094 52.1178 38.6239C50.4891 45.5675 45.0675 50.9891 38.1239 52.6178C33.1094 53.7941 27.8906 53.7941 22.8761 52.6178C15.9325 50.9891 10.5109 45.5675 8.88219 38.6239C7.70594 33.6094 7.70594 28.3906 8.88219 23.3761Z" fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35"/>
                        <path d="M39.0241 24.6905C39.0257 23.0524 38.4862 21.4597 37.4896 20.1597C36.4929 18.8596 35.0949 17.9251 33.5126 17.5013C31.9303 17.0774 30.2523 17.1879 28.7392 17.8157C27.2262 18.4435 25.9628 19.5533 25.1453 20.9728C24.8636 21.461 24.7874 22.0412 24.9334 22.5856C25.0794 23.13 25.4357 23.5941 25.9239 23.8758C26.4121 24.1575 26.9923 24.2338 27.5367 24.0878C28.0811 23.9417 28.5452 23.5854 28.8269 23.0972C29.1073 22.6129 29.51 22.2108 29.9947 21.9312C30.4794 21.6516 31.0291 21.5042 31.5887 21.5039C32.4338 21.5039 33.2443 21.8397 33.842 22.4373C34.4396 23.0349 34.7753 23.8454 34.7753 24.6905C34.7753 25.5357 34.4396 26.3462 33.842 26.9438C33.2443 27.5414 32.4338 27.8772 31.5887 27.8772H31.5823C31.4453 27.8909 31.31 27.9187 31.1787 27.96C31.036 27.9745 30.8951 28.003 30.758 28.045C30.6414 28.1087 30.5312 28.1834 30.4287 28.2681C30.3067 28.3346 30.1914 28.4129 30.0846 28.5017C29.9891 28.6159 29.9058 28.7398 29.836 28.8714C29.7584 28.967 29.6894 29.0694 29.63 29.1773C29.5837 29.3238 29.5531 29.4747 29.5386 29.6277C29.5021 29.7497 29.4772 29.8749 29.4643 30.0016V32.126L29.4685 32.1494V33.1924C29.4696 33.7551 29.694 34.2944 30.0922 34.6919C30.4905 35.0894 31.0302 35.3126 31.5929 35.3126H31.5993C31.8783 35.312 32.1544 35.2565 32.4119 35.1493C32.6695 35.042 32.9034 34.885 33.1002 34.6874C33.2971 34.4897 33.4531 34.2552 33.5594 33.9972C33.6656 33.7393 33.72 33.4629 33.7195 33.1839L33.7152 31.7776C35.2441 31.3234 36.586 30.3889 37.5422 29.1124C38.4984 27.8359 39.018 26.2854 39.0241 24.6905ZM30.0952 39.1153C29.7969 39.4112 29.593 39.7889 29.5093 40.2006C29.4257 40.6123 29.466 41.0396 29.6251 41.4285C29.7843 41.8173 30.0552 42.1502 30.4036 42.3851C30.752 42.62 31.1621 42.7463 31.5823 42.748C32.1454 42.7432 32.6852 42.5227 33.0906 42.132C33.4851 41.729 33.706 41.1875 33.706 40.6236C33.706 40.0597 33.4851 39.5183 33.0906 39.1153C32.684 38.7363 32.1488 38.5256 31.5929 38.5256C31.037 38.5256 30.5018 38.7363 30.0952 39.1153Z" fill="#5063F4"/>
                    </svg>
                    <div style="width: 535px; color: #2A2A2A; font-size: 28px; font-weight: 700; line-height: 32px; word-wrap: break-word">Are you sure you want to delete this group?</div>
                </div>
                <img src="/assets/dashboard/icons/Group 1707478165.svg" />
                <div style="width: 516px; text-align: center; color: #252525; font-size: 22px; font-weight: 400; text-transform: capitalize; line-height: 26px; word-wrap: break-word">This operation is irreversible</div>
                <div style="padding-left: 90px; padding-right: 90px; justify-content: center; align-items: flex-start; gap: 50px; display: inline-flex">
                    <div @click="$emit('cancelIt',true)" style="padding: 15px 41px;border-radius: 20px; overflow: hidden; border: 1px #5063F4 solid; justify-content: center; align-items: center; gap: 4px; display: flex; cursor: pointer">
                        <div style="padding-left: 10px; padding-right: 10px; justify-content: center; align-items: flex-start; gap: 10px; display: flex">
                            <div style="color: #5063F4; font-size: 16px; font-weight: 500; text-transform: capitalize; line-height: 20px; word-wrap: break-word">Cancel</div>
                        </div>
                    </div>
                    <div @click="$emit('deleteIt',true)" style="padding: 15px 41px;background: #5063F4; border-radius: 20px; overflow: hidden; justify-content: center; align-items: center; gap: 4px; display: flex;cursor: pointer">
                        <div style="padding-left: 10px; padding-right: 10px; justify-content: center; align-items: flex-start; gap: 10px; display: flex">
                            <div style="color: white; font-size: 16px; font-weight: 600; text-transform: capitalize; line-height: 20px; word-wrap: break-word">Delete
                                <b-spinner variant="primary" label="Loading" v-if="deleting_submitted"
                                           style="width: 1.3rem; height: 1.3rem;margin-right:5px">
                                </b-spinner>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Modal>
    </div>
</template>
<script>
    import Modal from "../../shared/Modal";
    export default {
        components: {
            Modal
        },
        props: ['show','deleting_submitted']
    }
</script>