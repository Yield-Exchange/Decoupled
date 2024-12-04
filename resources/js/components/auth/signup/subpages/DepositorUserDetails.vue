<template>
    <div class="w-100 d-flex flex-column justify-content-center bg-white align-items-center p-4">
        <p class="title">Welcome Elon Tusk, Please complete your registration</p>
        <div class="d-flex justify-ontent-center w-100">
            <img src="/assets/signup/depositor-regimage.png" style="max-height: 200px; margin: 20px auto;" alt="" srcset="">
        </div>


        <div class="row w-100">
            <div class="col-md-6">
                <CustomTextInput label="Organization Name *" placeholder="Elon Tusk Depositor" input_type="text" />
            </div>
            <div class="col-md-6">
                <CustomTextInput label="Business Registration or CRA Number *" placeholder="Enter Number"
                    input_type="number" />
            </div>
            <div class="col-md-6">
                <CustomSelectInput :options="['marketing', 'another']" label="Organisation Type" />
            </div>
            <div class="col-md-6">
                <CustomSelectInput label="Industry" />
            </div>
            <div class="col-md-6">
                <CustomTextInput label="Address" placeholder="Email Address" input_type="address" />
            </div>
            <div class="col-md-6">
                <CustomSelectInput label="City" />
            </div>
            <div class="col-md-6">
                <CustomSelectInput label="Province" />
            </div>
            <div class="col-md-6">
                <CustomTextInput label="Website" placeholder="Enter Website Link" input_type="url" />
            </div>
            <!-- <div class="col-md-6">
                <CustomTextInput label="LinkedIn Business Profile" placeholder="Enter LinkedIn Profile" input_type="url" />
            </div> -->
            <div class="col-md-12 mb-5">
                <div class="w-100 d-flex flex-column">
                    <label class="custom-label" for="">Company Description </label>
                    <textarea placeholder="enter descriptions" cols="20" class="textarea"></textarea>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import CustomSubmit from '../shared/CustomSubmit.vue';
import CustomTextInput from '../shared/CustomTextInput.vue';
import CustomSelectInput from '../shared/CustomSelectInput.vue';

export default {
    components: {
        CustomSubmit, CustomTextInput, CustomSelectInput
    },
}
</script>
<style scoped>
.top-title {
    margin-bottom: 30px;
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

    font-family: Montserrat;
    font-size: 20px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 130% */
    text-transform: capitalize;
}

.title {
    color: #5063F4;
    font-family: Montserrat;
    font-size: 20px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 130% */
    text-transform: capitalize;

}
</style>