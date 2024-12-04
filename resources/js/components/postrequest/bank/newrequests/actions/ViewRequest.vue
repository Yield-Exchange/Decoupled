<template>
    <div class="view-button" @click="goToChat">
        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13" fill="none">
            <path
                d="M6.5 5C6.89782 5 7.27936 5.15804 7.56066 5.43934C7.84196 5.72064 8 6.10218 8 6.5C8 6.89782 7.84196 7.27936 7.56066 7.56066C7.27936 7.84196 6.89782 8 6.5 8C6.10218 8 5.72064 7.84196 5.43934 7.56066C5.15804 7.27936 5 6.89782 5 6.5C5 6.10218 5.15804 5.72064 5.43934 5.43934C5.72064 5.15804 6.10218 5 6.5 5ZM6.5 2.75C9 2.75 11.135 4.305 12 6.5C11.135 8.695 9 10.25 6.5 10.25C4 10.25 1.865 8.695 1 6.5C1.865 4.305 4 2.75 6.5 2.75ZM2.09 6.5C2.49413 7.32515 3.12165 8.02037 3.90124 8.50663C4.68083 8.99288 5.5812 9.25066 6.5 9.25066C7.4188 9.25066 8.31917 8.99288 9.09876 8.50663C9.87835 8.02037 10.5059 7.32515 10.91 6.5C10.5059 5.67485 9.87835 4.97963 9.09876 4.49337C8.31917 4.00712 7.4188 3.74934 6.5 3.74934C5.5812 3.74934 4.68083 4.00712 3.90124 4.49337C3.12165 4.97963 2.49413 5.67485 2.09 6.5Z"
                fill="#2A9928" />
        </svg>
        <p class="p-0 m-0"> View </p>
    </div>
</template>
<script>
export default {
    components: {},
    methods: {
        goToChat() {
            window.location.href = '/place-offer/' + this.request_id + '?fromPage=new-requests';
        }
    },
    props: ['request_id']
}
</script>

<style scoped>
.view-button {
    display: flex;
    /* width: 97px; */
    padding: 2px 5px;
    align-items: center;
    justify-content: center;
    gap: 4px;
    border-radius: 91px;
    cursor: pointer;
    background: #EAF5EA;
}

.view-button p {

    color: #2A9928 !important;
}
</style>