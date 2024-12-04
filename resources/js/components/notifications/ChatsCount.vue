<template>
    <span v-if="chats > 0" class="badge badge-pill bg-warning-400 ml-auto ml-md-0">
        {{ chats }}
    </span>
</template>
<script>
    export default {
        props: ['chats','_token']
    };
</script>
