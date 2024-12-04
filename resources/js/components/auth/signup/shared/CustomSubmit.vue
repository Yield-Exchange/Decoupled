<template>
    <button @click="submit" class="custom-button" :disabled="isLoading"
        :class="[!outline ? 'submit-button-signup' : 'outline-submit-signup', previous ? 'previous-button' : '', prevoutline ? 'previous-outline' : '']"
        type="button">{{ title
        }} <span v-if="isLoading" class="spinner-border" :class="!outline ? 'spinner-white' : 'spinner-blue'"
            role="status">
        </span>
    </button>
</template>


<script>
export default {
    props: ['title', 'outline', 'isLoading', 'previous', 'prevoutline'],
    methods: {
        submit() {
            this.$emit('action')
        }
    }

}

</script>


<style scoped>
.spinner-white {
    color: white;
    height: 20px;
    width: 20px;
}

.spinner-blue {
    height: 20px;
    width: 20px;
    color: #5063F4;
}

.spinner-border {
    /* border: 2px; */
}

.previous-button {
    border-radius: 32px;
    background: #D9D9D9 !important;
    border: #D9D9D9 !important;
}

.previous-outline {
    border-radius: 32px;
    background: white !important;
    color: #9CA1AA !important;
    border: 2px solid #9CA1AA !important;
}

.custom-button {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 15px;

    white-space: nowrap;

}

.submit-button-signup {

    border-radius: 32px;
    background: #5063F4;
    color: #FFF;

    /* Yield Exchange Text Styles/Buttons Bold */
    font-family: Montserrat;
    font-size: 16px;
    font-style: normal;
    font-weight: 600;
    line-height: 20px;
    /* 125% */
    text-transform: capitalize;
    padding: 10px 30px;
    border: none;
    cursor: pointer;
    border: 2px solid #5063F4;
}

.outline-submit-signup {
    border-radius: 32px;
    border: 2px solid #5063F4;
    background: transparent;
    color: #5063F4;

    /* Yield Exchange Text Styles/Buttons Bold */
    font-family: Montserrat;
    font-size: 16px;
    font-style: normal;
    font-weight: 600;
    line-height: 20px;
    /* 125% */
    text-transform: capitalize;
    padding: 10px 30px;
    cursor: pointer;

}
</style>