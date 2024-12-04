<template>
    <Modal :show="show" :hide_close="true">
        <div style="width: 100%; height: 100%; flex-direction: column; justify-content: center; align-items: center; gap: 40px; display: inline-flex">
            <div style="flex-direction: column; justify-content: flex-start; align-items: center; gap: 31px; display: flex">
                <div style="justify-content: center; align-items: center; gap: 10px; display: inline-flex">
                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 60 60" fill="none">
                        <path d="M8.88219 22.8761C10.5109 15.9325 15.9325 10.5109 22.8761 8.88219C27.8906 7.70594 33.1094 7.70594 38.1239 8.88219C45.0675 10.5109 50.4891 15.9325 52.1178 22.8761C53.2941 27.8906 53.2941 33.1094 52.1178 38.1239C50.4891 45.0675 45.0675 50.4891 38.1239 52.1178C33.1094 53.2941 27.8906 53.2941 22.8761 52.1178C15.9325 50.4891 10.5109 45.0675 8.88219 38.1239C7.70594 33.1094 7.70594 27.8906 8.88219 22.8761Z" fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35"/>
                        <path d="M22 31L28 37L40 25" stroke="#5063F4" stroke-width="3.33" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <div><span style="color: #5063F4; font-size: 28px;font-weight: 700; line-height: 32px; word-wrap: break-word">{{ name }} </span><span style="color: #2A2A2A; font-size: 28px; font-weight: 700; line-height: 32px; word-wrap: break-word">successfully {{ action }}</span></div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="238" height="238" viewBox="0 0 238 238" fill="none">
                    <path d="M119 238C184.722 238 238 184.722 238 119C238 53.2781 184.722 0 119 0C53.2781 0 0 53.2781 0 119C0 184.722 53.2781 238 119 238Z" fill="#EAF5EA"/>
                    <path d="M190.4 238H47.5996V84.0937C54.3303 84.0861 60.7831 81.4091 65.5423 76.6498C70.3016 71.8905 72.9787 65.4377 72.9863 58.707H165.013C165.006 62.0412 165.66 65.3437 166.938 68.4234C168.215 71.5032 170.091 74.299 172.456 76.649C174.806 79.0149 177.602 80.8912 180.682 82.1691C183.762 83.4469 187.065 84.1011 190.4 84.0937V238Z" fill="white"/>
                    <path d="M119.002 161.839C140.033 161.839 157.082 144.79 157.082 123.759C157.082 102.728 140.033 85.6787 119.002 85.6787C97.9709 85.6787 80.9219 102.728 80.9219 123.759C80.9219 144.79 97.9709 161.839 119.002 161.839Z" fill="#44E0AA"/>
                    <path d="M111.822 140.756L95.5449 124.48L99.6141 120.41L111.822 132.618L138.022 106.418L142.091 110.487L111.822 140.756Z" fill="white"/>
                    <path d="M139.629 171.36H98.3752C95.7464 171.36 93.6152 173.491 93.6152 176.12C93.6152 178.749 95.7464 180.88 98.3752 180.88H139.629C142.257 180.88 144.389 178.749 144.389 176.12C144.389 173.491 142.257 171.36 139.629 171.36Z" fill="#DFEAFB"/>
                    <path d="M153.909 190.4H84.0959C81.4671 190.4 79.3359 192.532 79.3359 195.16C79.3359 197.789 81.4671 199.92 84.0959 199.92H153.909C156.538 199.92 158.669 197.789 158.669 195.16C158.669 192.532 156.538 190.4 153.909 190.4Z" fill="#DFEAFB"/>
                </svg>
            </div>
            <div style="padding-left: 71px; padding-right: 71px; justify-content: center; align-items: flex-start; gap: 30px; display: inline-flex">
                <div style="padding-left: 71px; padding-right: 71px; justify-content: center; align-items: flex-start; gap: 30px; display: flex">
                    <div @click="$emit('viewSummary',true)" style="padding: 15px 41px;border-radius: 20px; overflow: hidden; border: 1px #5063F4 solid; justify-content: center; align-items: center; gap: 4px; display: flex; cursor: pointer">
                        <div style="padding-left: 10px; padding-right: 10px; justify-content: center; align-items: flex-start; gap: 10px; display: flex">
                            <div style="color: #5063F4; font-size: 16px; font-weight: 500; text-transform: capitalize; line-height: 20px; word-wrap: break-word">View Summary</div>
                        </div>
                    </div>
                    <div @click="$emit('myCampaigns',true)" style="padding: 15px 41px;background: #5063F4; border-radius: 20px; overflow: hidden; justify-content: center; align-items: center; gap: 4px; display: flex; cursor: pointer">
                        <div style="padding-left: 10px; padding-right: 10px; justify-content: center; align-items: flex-start; gap: 10px; display: flex">
                            <div style="color: white; font-size: 16px;font-weight: 600; text-transform: capitalize; line-height: 20px; word-wrap: break-word">My Campaigns</div>
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
        props: ['show','action','name']
    }
</script>