<template>
    <div class="pr-start-chat" @click="viewSummary">
        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="13" viewBox="0 0 12 13" fill="none">
            <path
                d="M2.005 3.50391H10.005V4.50391H2.005V3.50391ZM3.005 1.50391H9.005V2.50391H3.005V1.50391ZM10 5.49891H2C1.73478 5.49891 1.48043 5.60426 1.29289 5.7918C1.10536 5.97934 1 6.23369 1 6.49891V10.4989C1 10.7641 1.10536 11.0185 1.29289 11.206C1.48043 11.3935 1.73478 11.4989 2 11.4989H10C10.2652 11.4989 10.5196 11.3935 10.7071 11.206C10.8946 11.0185 11 10.7641 11 10.4989V6.49891C11 6.23369 10.8946 5.97934 10.7071 5.7918C10.5196 5.60426 10.2652 5.49891 10 5.49891ZM5.15 10.4989L3.5 8.87891L4.2 8.17891L5.15 9.12891L7.8 6.47891L8.5 7.17891L5.15 10.4989Z"
                fill="#5063F4" />
        </svg>
        <p class="p-0 m-0"> Create GIC </p>
    </div>
</template>
<script>
import TableActionButton from "../../../../shared/Buttons/TableActionButton";
export default {
    components: {
        TableActionButton,
    },
    props: ['deposit_id', 'actionObjectDetails'],

    methods: {
        viewSummary() {

            window.location.href = '/bank/create-gic/' + this.deposit_id + '?fromPage=bank-pending-deposits'
            console.log("Mike", this.deposit_id)

        }
    },
}
</script>

<style scoped>
.pr-start-chat {
    background-color: #EFF2FE;
    display: flex;
    /* width: 97px; */
    padding: 2px 10px;
    align-items: center;
    justify-content: center;
    gap: 4px;
    border-radius: 91px;
    cursor: pointer;
    /* border: 1px solid var(--Yield-Exchange-Colors-Yield-Exchange-Purple, #5063F4); */
}
</style>
