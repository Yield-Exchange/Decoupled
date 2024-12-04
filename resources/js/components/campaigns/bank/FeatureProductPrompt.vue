<template>
    <Modal :show="show" :hide_close="true">
        <div style="width: 100%; height: 100%; flex-direction: column; justify-content: center; align-items: center; gap: 20px; display: inline-flex">
            <div style="flex-direction: column; justify-content: center; align-items: center; gap: 31px; display: flex">
                <div style="justify-content: flex-start; align-items: center; gap: 10px; display: inline-flex">
                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 60 60" fill="none">
                        <path d="M9.38219 22.8761C11.0109 15.9325 16.4325 10.5109 23.3761 8.88219C28.3906 7.70594 33.6094 7.70594 38.6239 8.88219C45.5675 10.5109 50.9891 15.9325 52.6178 22.8761C53.7941 27.8906 53.7941 33.1094 52.6178 38.1239C50.9891 45.0675 45.5675 50.4891 38.6239 52.1178C33.6094 53.2941 28.3906 53.2941 23.3761 52.1178C16.4325 50.4891 11.0109 45.0675 9.38219 38.1239C8.20594 33.1094 8.20594 27.8906 9.38219 22.8761Z" fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M19.205 24.6367C19.4103 24.4156 19.6984 24.29 20 24.29H41.5C41.8017 24.29 42.0898 24.4156 42.2951 24.6367C42.5004 24.8578 42.6044 25.1543 42.5821 25.4552L41.3876 41.5802C41.3457 42.1468 40.8737 42.585 40.3056 42.585H21.1945C20.6264 42.585 20.1544 42.1468 20.1125 41.5802L18.918 25.4552C18.8957 25.1543 18.9997 24.8578 19.205 24.6367ZM21.1684 26.46L22.2021 40.415H39.298L40.3317 26.46H21.1684Z" fill="#5063F4"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M24.8877 20C24.8877 19.4008 25.3735 18.915 25.9727 18.915H35.5283C36.1275 18.915 36.6133 19.4008 36.6133 20V27.7639C36.6133 28.3632 36.1275 28.8489 35.5283 28.8489C34.929 28.8489 34.4433 28.3632 34.4433 27.7639V21.085H27.0577V27.7639C27.0577 28.3632 26.5719 28.8489 25.9727 28.8489C25.3735 28.8489 24.8877 28.3632 24.8877 27.7639V20Z" fill="#5063F4"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M24.8877 36.7246C24.8877 36.1254 25.3735 35.6396 25.9727 35.6396H35.5283C36.1275 35.6396 36.6133 36.1254 36.6133 36.7246C36.6133 37.3239 36.1275 37.8096 35.5283 37.8096H25.9727C25.3735 37.8096 24.8877 37.3239 24.8877 36.7246Z" fill="#5063F4"/>
                    </svg>
                    <div style="min-width: 498px; color: #2A2A2A; font-size: 28px; font-weight: 700; line-height: 32px; word-wrap: break-word">Do you want to {{ action}} {{ name}}?</div>
                </div>
                <div style="min-width: 410px; height: 305px; flex-direction: column; justify-content: flex-start; align-items: center; gap: 16px; display: flex">
                    <img src="/assets/dashboard/icons/Mask Group 2.svg" />
                    <div style="width: 410px; height: 41px; text-align: center; color: #252525; font-size: 14px; font-weight: 400; word-wrap: break-word">A featured product is ranked high on the investors dashboard.<br/></div>
                </div>
            </div>
            <div style="padding-left: 71px; padding-right: 71px; justify-content: center; align-items: flex-start; gap: 30px; display: inline-flex">
                <div @click="$emit('cancelIt',true)" style="padding: 15px 41px;background: #9CA1AA; border-radius: 20px; overflow: hidden; justify-content: center; align-items: center; gap: 4px; display: flex;cursor: pointer">
                    <div style="padding-left: 10px; padding-right: 10px; justify-content: center; align-items: flex-start; gap: 10px; display: flex">
                        <div style="color: white; font-size: 16px; font-weight: 600; text-transform: capitalize; line-height: 20px; word-wrap: break-word">Cancel</div>
                    </div>
                </div>
                <div @click="$emit('submit',true)" style="padding: 15px 41px;background: #5063F4; border-radius: 20px; overflow: hidden; justify-content: center; align-items: center; gap: 4px; display: flex; cursor: pointer">
                    <div style="padding-left: 10px; padding-right: 10px; justify-content: center; align-items: flex-start; gap: 10px; display: flex">
                        <div style="color: white; font-size: 16px; font-weight: 600; text-transform: capitalize; line-height: 20px; word-wrap: break-word">Submit
                            <b-spinner variant="primary" label="Loading" v-if="submitted"
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
        components: {
            Modal
        },
        props: ['show','submitted','name','action']
    }
</script>