<template>
    <Modal :visible.sync="show" :show="show">
        <div style="width: 100%; height: 100%; flex-direction: column; justify-content: center; align-items: center; gap: 20px; display: inline-flex">
            <div style="flex-direction: column; justify-content: flex-start; align-items: center; gap: 31px; display: flex">
                <div style="justify-content: center; align-items: center; gap: 10px; display: inline-flex">
                    <div style="width: 60px; height: 60px; position: relative">
                        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="61" viewBox="0 0 60 61" fill="none">
                            <path d="M8.88219 23.3761C10.5109 16.4325 15.9325 11.0109 22.8761 9.38219C27.8906 8.20594 33.1094 8.20594 38.1239 9.38219C45.0675 11.0109 50.4891 16.4325 52.1178 23.3761C53.2941 28.3906 53.2941 33.6094 52.1178 38.6239C50.4891 45.5675 45.0675 50.9891 38.1239 52.6178C33.1094 53.7941 27.8906 53.7941 22.8761 52.6178C15.9325 50.9891 10.5109 45.5675 8.88219 38.6239C7.70594 33.6094 7.70594 28.3906 8.88219 23.3761Z" fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35"/>
                            <path d="M22 31.5L28 37.5L40 25.5" stroke="#5063F4" stroke-width="3.33" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div><span style="color: #5063F4; font-size: 28px; font-weight: 700; line-height: 32px; word-wrap: break-word">B.C Muni’s Campaign <br/></span><span style="color: #2A2A2A; font-size: 28px; font-weight: 700; line-height: 32px; word-wrap: break-word">successfully saved to draft</span></div>
                </div>
                <div style="width: 208px; height: 208px; position: relative">
                    <svg xmlns="http://www.w3.org/2000/svg" width="208" height="209" viewBox="0 0 208 209" fill="none">
                        <path d="M104 208.5C161.438 208.5 208 161.938 208 104.5C208 47.0624 161.438 0.5 104 0.5C46.5624 0.5 0 47.0624 0 104.5C0 161.938 46.5624 208.5 104 208.5Z" fill="#EAF5EA"/>
                        <path d="M166.4 208.5H41.5996V73.9933C47.4819 73.9867 53.1213 71.6471 57.2807 67.4877C61.44 63.3283 63.7797 57.6889 63.7863 51.8066H144.213C144.207 54.7206 144.778 57.6068 145.895 60.2983C147.012 62.9898 148.651 65.4332 150.718 67.487C152.772 69.5547 155.215 71.1945 157.907 72.3113C160.599 73.4281 163.485 73.9998 166.4 73.9933V208.5Z" fill="white"/>
                        <path d="M104 141.94C122.38 141.94 137.28 127.04 137.28 108.66C137.28 90.2798 122.38 75.3799 104 75.3799C85.6197 75.3799 70.7197 90.2798 70.7197 108.66C70.7197 127.04 85.6197 141.94 104 141.94Z" fill="#44E0AA"/>
                        <path d="M97.7251 123.513L83.5 109.288L87.0563 105.732L97.7251 116.4L120.623 93.5029L124.179 97.0592L97.7251 123.513Z" fill="white"/>
                        <path d="M122.027 150.26H85.9735C83.676 150.26 81.8135 152.122 81.8135 154.42C81.8135 156.717 83.676 158.58 85.9735 158.58H122.027C124.324 158.58 126.187 156.717 126.187 154.42C126.187 152.122 124.324 150.26 122.027 150.26Z" fill="#DFEAFB"/>
                        <path d="M134.506 166.9H73.493C71.1955 166.9 69.333 168.763 69.333 171.06C69.333 173.358 71.1955 175.22 73.493 175.22H134.506C136.804 175.22 138.666 173.358 138.666 171.06C138.666 168.763 136.804 166.9 134.506 166.9Z" fill="#DFEAFB"/>
                    </svg>
                </div>
            </div>
            <div style="padding-left: 71px; padding-right: 71px; justify-content: center; align-items: flex-start; gap: 30px; display: inline-flex; cursor: pointer">
                <div @click="viewDrafts" style="padding: 15px 41px;background: #5063F4; border-radius: 20px; overflow: hidden; justify-content: center; align-items: center; gap: 4px; display: flex">
                    <div style="padding-left: 10px; padding-right: 10px; justify-content: center; align-items: flex-start; gap: 10px; display: flex">
                        <div style="color: white; font-size: 16px; font-weight: 600; text-transform: capitalize; line-height: 20px; word-wrap: break-word">View Drafts</div>
                    </div>
                </div>
            </div>
        </div>
    </Modal>
</template>
<script>
    import Modal from "../../shared/Modal";

    export default {
        components:{
            Modal
        },
        data(){
            return {
                show: true,
            }
        },
        methods:{
            viewDrafts(){
                window.location.href="/campaigns/drafts";
            }
        }
    }
</script>