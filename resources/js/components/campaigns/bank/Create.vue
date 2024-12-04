<template>
    <div @click="clicked" style="height: 280px">
        <div class="create-card">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="61" height="61" viewBox="0 0 61 61" fill="none">
                    <path
                        d="M8.98229 22.9259C10.611 15.9823 16.0326 10.5607 22.9762 8.932C27.9907 7.75574 33.2095 7.75574 38.224 8.932C45.1676 10.5607 50.5892 15.9823 52.2179 22.9259C53.3942 27.9404 53.3942 33.1592 52.2179 38.1737C50.5892 45.1173 45.1676 50.5389 38.224 52.1676C33.2095 53.3439 27.9907 53.3439 22.9762 52.1676C16.0326 50.5389 10.611 45.1173 8.98229 38.1737C7.80603 33.1592 7.80603 27.9404 8.98229 22.9259Z"
                        fill="#EFF2FE" fill-opacity="0.5" stroke="#F8FAFF" stroke-width="1.35" />
                    <path d="M23 19.9497V41.0297L39.5629 30.4897L23 19.9497Z" fill="#FDFDFD" />
                </svg>
                <h3 style="color: white;font-size: 30px;">Build New <br /> Campaign</h3>
            </div>
        </div>
    </div>
</template>
<style>
.create-card {
    /* width: 200px; */
    cursor: pointer;
    padding: 0;
}

.create-card div {
    display: flex;
    /* width: 189.2px; */
    height: 249px;
    padding: 21.6px;
    flex-direction: column;
    justify-content: center;
    align-items: flex-start;
    gap: 9.9px;
    flex-shrink: 0;
    border-radius: 10.8px;
    border: 0.9px solid #3BCBB2;
    background: #3BCBB2;
    min-width: 150px;
}
</style>
<script>
export default {
    mounted() {
    },
    created() {
    },
    props: ['click'],
    data() {
        return {

        }
    },
    methods: {
        clicked() {
            this.$emit('click', true);
        }
    }
}
</script>