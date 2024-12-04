<template>
    <div>
        <div class="row justify-content-center">

            <div class="w-75">
                <div class="top-title w-100 ">
                    <p>Enter your details To Finish Creating your Account as a depositor</p>
                </div>
                <div class="d-flex justify-content-end w-100 gap-2 mt-3">
                    <CustomSubmit :outline="true" title="Register Later" />
                    <CustomSubmit :outline="false" title="Next" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import CustomSubmit from './shared/CustomSubmit.vue';
import CustomTextInput from './shared/CustomTextInput.vue';
import CustomSelectInput from './shared/CustomSelectInput.vue';


export default {
    components: {
        CustomSubmit, CustomTextInput, CustomSelectInput,
    }
}
</script>
<style scoped>
.top-title {
    margin-bottom: 30px;
}

.top-title p {
    text-align: center;
    width: 100%;
}

.textarea {
    border-radius: 10px;
    border: 1px solid #D9D9D9;
    background: #FFF;
    box-shadow: 0px 1px 2px 0px rgba(16, 24, 40, 0.05);
    padding: 10px 14px;
    margin-top: 5px;
}

.top-title p {
    color: #252525;

    font-family: Montserrat !important;
    font-size: 20px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 130% */
    text-transform: capitalize;
}

.title {
    color: #5063F4;
    font-family: Montserrat !important;
    font-size: 26px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 130% */
    text-transform: capitalize;

}
</style>