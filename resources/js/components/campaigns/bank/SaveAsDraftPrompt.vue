<template>
    <Modal :visible.sync="show" :show="show" size="md">
        <div style="width: 100%; height: 100%; flex-direction: column; justify-content: center; align-items: center; gap: 20px; display: inline-flex">
            <div style="flex-direction: column; justify-content: center; align-items: center; gap: 31px; display: flex">
                <div style="justify-content: flex-start; align-items: center; gap: 10px; display: inline-flex">
                    <div style="width: 60px; height: 60px; position: relative">
                        <svg xmlns="http://www.w3.org/2000/svg" width="61" height="61" viewBox="0 0 61 61" fill="none">
                            <path d="M9.88219 23.3761C11.5109 16.4325 16.9325 11.0109 23.8761 9.38219C28.8906 8.20594 34.1094 8.20594 39.1239 9.38219C46.0675 11.0109 51.4891 16.4325 53.1178 23.3761C54.2941 28.3906 54.2941 33.6094 53.1178 38.6239C51.4891 45.5675 46.0675 50.9891 39.1239 52.6178C34.1094 53.7941 28.8906 53.7941 23.8761 52.6178C16.9325 50.9891 11.5109 45.5675 9.88219 38.6239C8.70594 33.6094 8.70594 28.3906 9.88219 23.3761Z" fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M19.705 25.1367C19.9103 24.9156 20.1984 24.79 20.5 24.79H42C42.3017 24.79 42.5898 24.9156 42.7951 25.1367C43.0004 25.3578 43.1044 25.6543 43.0821 25.9552L41.8876 42.0802C41.8457 42.6468 41.3737 43.085 40.8056 43.085H21.6945C21.1264 43.085 20.6544 42.6468 20.6125 42.0802L19.418 25.9552C19.3957 25.6543 19.4997 25.3578 19.705 25.1367ZM21.6684 26.96L22.7021 40.915H39.798L40.8317 26.96H21.6684Z" fill="#5063F4"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M25.3877 20.5C25.3877 19.9008 25.8735 19.415 26.4727 19.415H36.0283C36.6275 19.415 37.1133 19.9008 37.1133 20.5V28.2639C37.1133 28.8632 36.6275 29.3489 36.0283 29.3489C35.429 29.3489 34.9433 28.8632 34.9433 28.2639V21.585H27.5577V28.2639C27.5577 28.8632 27.0719 29.3489 26.4727 29.3489C25.8735 29.3489 25.3877 28.8632 25.3877 28.2639V20.5Z" fill="#5063F4"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M25.3877 37.2246C25.3877 36.6254 25.8735 36.1396 26.4727 36.1396H36.0283C36.6275 36.1396 37.1133 36.6254 37.1133 37.2246C37.1133 37.8239 36.6275 38.3096 36.0283 38.3096H26.4727C25.8735 38.3096 25.3877 37.8239 25.3877 37.2246Z" fill="#5063F4"/>
                        </svg>
                    </div>
                    <div><span style="color: #2A2A2A; font-size: 28px; font-weight: 700; line-height: 32px; word-wrap: break-word">Would you like to save <br/></span>
                        <span style="color: #5063F4; font-size: 28px; font-weight: 700; line-height: 32px; word-wrap: break-word">This Campaign</span>
                        <span style="color: #2A2A2A; font-size: 28px; font-weight: 700; line-height: 32px; word-wrap: break-word"> to draft?</span></div>
                </div>
                <div style="width: 208px; height: 208px; position: relative">
                    <svg xmlns="http://www.w3.org/2000/svg" width="208" height="209" viewBox="0 0 208 209" fill="none">
                        <path d="M104 208.5C161.438 208.5 208 161.938 208 104.5C208 47.0624 161.438 0.5 104 0.5C46.5624 0.5 0 47.0624 0 104.5C0 161.938 46.5624 208.5 104 208.5Z" fill="#E3ECFA"/>
                        <path d="M166.4 208.5H41.5996V73.9933C47.4819 73.9867 53.1213 71.6471 57.2807 67.4877C61.44 63.3283 63.7797 57.6889 63.7863 51.8066H144.213C144.207 54.7206 144.778 57.6068 145.895 60.2983C147.012 62.9898 148.651 65.4332 150.718 67.487C152.772 69.5547 155.215 71.1945 157.907 72.3113C160.599 73.4281 163.485 73.9998 166.4 73.9933V208.5Z" fill="white"/>
                        <path d="M104 141.94C122.38 141.94 137.28 127.04 137.28 108.66C137.28 90.2798 122.38 75.3799 104 75.3799C85.6197 75.3799 70.7197 90.2798 70.7197 108.66C70.7197 127.04 85.6197 141.94 104 141.94Z" fill="#5063F4"/>
                        <path d="M105.796 115.963C105.796 116.417 105.662 116.861 105.409 117.239C105.157 117.616 104.798 117.911 104.379 118.084C103.959 118.258 103.497 118.304 103.052 118.215C102.607 118.127 102.197 117.908 101.876 117.587C101.555 117.266 101.336 116.856 101.248 116.411C101.159 115.966 101.205 115.504 101.379 115.084C101.552 114.665 101.847 114.306 102.224 114.054C102.602 113.801 103.046 113.667 103.5 113.667C104.109 113.667 104.693 113.909 105.124 114.339C105.554 114.77 105.796 115.354 105.796 115.963ZM119 108.5C119 111.566 118.091 114.562 116.388 117.111C114.685 119.66 112.264 121.647 109.432 122.82C106.599 123.993 103.483 124.3 100.476 123.702C97.4694 123.104 94.7076 121.628 92.5399 119.46C90.3721 117.292 88.8959 114.531 88.2978 111.524C87.6998 108.517 88.0067 105.401 89.1799 102.568C90.353 99.7362 92.3397 97.3154 94.8887 95.6122C97.4376 93.9091 100.434 93 103.5 93C107.609 93.0046 111.549 94.6391 114.455 97.5449C117.361 100.451 118.995 104.391 119 108.5ZM115.556 108.5C115.556 106.116 114.849 103.785 113.524 101.802C112.199 99.8198 110.316 98.2746 108.113 97.3621C105.911 96.4497 103.487 96.2109 101.148 96.6761C98.8095 97.1413 96.6614 98.2894 94.9754 99.9754C93.2894 101.661 92.1413 103.81 91.6761 106.148C91.2109 108.487 91.4497 110.911 92.3621 113.113C93.2746 115.316 94.8198 117.199 96.8023 118.524C98.7848 119.849 101.116 120.556 103.5 120.556C106.696 120.552 109.761 119.281 112.021 117.021C114.281 114.761 115.552 111.696 115.556 108.5ZM103.5 99.3148C100.018 99.3148 97.1852 101.89 97.1852 105.056V105.63C97.1852 106.086 97.3666 106.524 97.6896 106.847C98.0126 107.17 98.4506 107.352 98.9074 107.352C99.3642 107.352 99.8022 107.17 100.125 106.847C100.448 106.524 100.63 106.086 100.63 105.63V105.056C100.63 103.79 101.921 102.759 103.5 102.759C105.079 102.759 106.37 103.79 106.37 105.056C106.37 106.321 105.079 107.352 103.5 107.352C103.043 107.352 102.605 107.533 102.282 107.856C101.959 108.179 101.778 108.617 101.778 109.074V110.222C101.777 110.648 101.933 111.059 102.217 111.375C102.501 111.692 102.892 111.893 103.315 111.939C103.738 111.984 104.163 111.872 104.508 111.623C104.853 111.374 105.094 111.006 105.183 110.59C107.85 109.918 109.815 107.692 109.815 105.056C109.815 101.89 106.982 99.3148 103.5 99.3148Z" fill="#FDFDFD"/>
                        <path d="M122.027 150.26H85.9735C83.676 150.26 81.8135 152.122 81.8135 154.42C81.8135 156.717 83.676 158.58 85.9735 158.58H122.027C124.324 158.58 126.187 156.717 126.187 154.42C126.187 152.122 124.324 150.26 122.027 150.26Z" fill="#DFEAFB"/>
                        <path d="M134.506 166.9H73.493C71.1955 166.9 69.333 168.763 69.333 171.06C69.333 173.358 71.1955 175.22 73.493 175.22H134.506C136.804 175.22 138.666 173.358 138.666 171.06C138.666 168.763 136.804 166.9 134.506 166.9Z" fill="#DFEAFB"/>
                    </svg>
                </div>
            </div>
            <div style="padding-left: 71px; padding-right: 71px; justify-content: center; align-items: flex-start; gap: 30px; display: inline-flex">
                <div @click="$emit('cancel',true)" style="padding: 15px 41px;background: #9CA1AA; border-radius: 20px; overflow: hidden; justify-content: center; align-items: center; gap: 4px; display: flex;cursor: pointer">
                    <div style="padding-left: 10px; padding-right: 10px; justify-content: center; align-items: flex-start; gap: 10px; display: flex">
                        <div style="color: white; font-size: 16px; font-weight: 600; text-transform: capitalize; line-height: 20px; word-wrap: break-word">Cancel</div>
                    </div>
                </div>
                <div  @click="$emit('save',true)" style="padding: 15px 41px;background: #5063F4; border-radius: 20px; overflow: hidden; justify-content: center; align-items: center; gap: 4px; display: flex; cursor: pointer">
                    <div style="padding-left: 10px; padding-right: 10px; justify-content: center; align-items: flex-start; gap: 10px; display: flex">
                        <div style="color: white; font-size: 16px; font-weight: 600; text-transform: capitalize; line-height: 20px; word-wrap: break-word">Save <b-spinner variant="primary" label="Loading" v-if="submittedSaveDraft"
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
        components:{
            Modal
        },
        props: ['submittedSaveDraft'],
        data(){
            return {
                show: true,
            }
        }
    }
</script>