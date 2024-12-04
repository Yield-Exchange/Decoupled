<template>
    <Modal :show="show" :hide_close="true">
        <div style="width: 100%; height: 100%; flex-direction: column; justify-content: center; align-items: center; gap: 31px; display: inline-flex">
            <div style="justify-content: flex-start; align-items: center; gap: 10px; display: inline-flex">
                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="61" viewBox="0 0 60 61" fill="none">
                    <path d="M8.88219 23.3507C10.5109 16.4071 15.9325 10.9855 22.8761 9.3568C27.8906 8.18054 33.1094 8.18055 38.1239 9.3568C45.0675 10.9855 50.4891 16.4071 52.1178 23.3507C53.2941 28.3653 53.2941 33.584 52.1178 38.5985C50.4891 45.5421 45.0675 50.9637 38.1239 52.5924C33.1094 53.7687 27.8906 53.7687 22.8761 52.5924C15.9325 50.9637 10.5109 45.5421 8.88219 38.5985C7.70594 33.584 7.70594 28.3652 8.88219 23.3507Z" fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35"/>
                    <path d="M39.0241 24.6651C39.0257 23.0271 38.4862 21.4343 37.4896 20.1343C36.4929 18.8342 35.0949 17.8997 33.5126 17.4759C31.9303 17.052 30.2523 17.1626 28.7392 17.7903C27.2262 18.4181 25.9628 19.5279 25.1453 20.9474C24.8636 21.4356 24.7874 22.0158 24.9334 22.5602C25.0794 23.1046 25.4357 23.5687 25.9239 23.8504C26.4121 24.1322 26.9923 24.2084 27.5367 24.0624C28.0811 23.9164 28.5452 23.5601 28.8269 23.0718C29.1073 22.5875 29.51 22.1854 29.9947 21.9058C30.4794 21.6262 31.0291 21.4788 31.5887 21.4785C32.4338 21.4785 33.2443 21.8143 33.842 22.4119C34.4396 23.0095 34.7753 23.82 34.7753 24.6651C34.7753 25.5103 34.4396 26.3208 33.842 26.9184C33.2443 27.516 32.4338 27.8518 31.5887 27.8518H31.5823C31.4453 27.8655 31.31 27.8933 31.1787 27.9346C31.036 27.9491 30.8951 27.9776 30.758 28.0196C30.6414 28.0833 30.5312 28.158 30.4287 28.2427C30.3067 28.3092 30.1914 28.3875 30.0846 28.4763C29.9891 28.5906 29.9058 28.7144 29.836 28.846C29.7584 28.9417 29.6894 29.044 29.63 29.1519C29.5837 29.2984 29.5531 29.4494 29.5386 29.6023C29.5021 29.7243 29.4772 29.8495 29.4643 29.9762V32.1006L29.4685 32.124V33.167C29.4696 33.7297 29.694 34.269 30.0922 34.6665C30.4905 35.064 31.0302 35.2872 31.5929 35.2872H31.5993C31.8783 35.2867 32.1544 35.2312 32.4119 35.1239C32.6695 35.0166 32.9034 34.8596 33.1002 34.662C33.2971 34.4643 33.4531 34.2298 33.5594 33.9719C33.6656 33.7139 33.72 33.4375 33.7195 33.1586L33.7152 31.7522C35.2441 31.298 36.586 30.3635 37.5422 29.087C38.4984 27.8105 39.018 26.26 39.0241 24.6651ZM30.0952 39.0899C29.7969 39.3858 29.593 39.7635 29.5093 40.1752C29.4257 40.5869 29.466 41.0142 29.6251 41.4031C29.7843 41.7919 30.0552 42.1248 30.4036 42.3597C30.752 42.5946 31.1621 42.7209 31.5823 42.7227C32.1454 42.7178 32.6852 42.4973 33.0906 42.1066C33.4851 41.7036 33.706 41.1621 33.706 40.5982C33.706 40.0343 33.4851 39.4929 33.0906 39.0899C32.684 38.7109 32.1488 38.5002 31.5929 38.5002C31.037 38.5002 30.5018 38.7109 30.0952 39.0899Z" fill="#5063F4"/>
                </svg>
                <div style="width: 393px; color: #2A2A2A; font-size: 28px; font-weight: 700; line-height: 32px; word-wrap: break-word">Are you sure you want to deactivate this campaign?</div>
            </div>
            <img src="/assets/dashboard/icons/487_online_protection_outline_deactivate.png" />
            <div style="min-width: 581px; text-align: center; color: #252525; font-size: 22px; font-weight: 400; text-transform: capitalize; line-height: 26px; word-wrap: break-word">You will see it on “Campaign History”</div>
            <div style="padding-left: 90px; padding-right: 90px; justify-content: center; align-items: flex-start; gap: 50px; display: inline-flex">
                <div  @click="$emit('cancelIt',true)" style="padding: 15px 41px;border-radius: 20px; overflow: hidden; border: 1px #5063F4 solid; justify-content: center; align-items: center; gap: 4px; display: flex; cursor: pointer">
                    <div style="padding-left: 10px; padding-right: 10px; justify-content: center; align-items: flex-start; gap: 10px; display: flex">
                        <div style="color: #5063F4; font-size: 16px; font-weight: 500; text-transform: capitalize; line-height: 20px; word-wrap: break-word">Cancel</div>
                    </div>
                </div>
                <div @click="$emit('deactivateIt',true)" style="padding: 15px 41px;background: #5063F4; border-radius: 20px; overflow: hidden; justify-content: center; align-items: center; gap: 4px; display: flex;cursor: pointer">
                    <div style="padding-left: 10px; padding-right: 10px; justify-content: center; align-items: flex-start; gap: 10px; display: flex">
                        <div style="color: white; font-size: 16px; font-weight: 600; text-transform: capitalize; line-height: 20px; word-wrap: break-word">Deactivate
                            <b-spinner variant="primary" label="Loading" v-if="deleting_submitted"
                                       style="width: 1.3rem; height: 1.3rem;margin-right:5px">
                            </b-spinner>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Modal>
</template>
<script>
    import Modal from "../../shared/Modal";
    export default {
        props: ['show','deleting_submitted'],
        components:{
            Modal
        }
    }
</script>