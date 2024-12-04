<template>
    <div class="w-100 d-flex flex-column justify-content-end">
        <div class="w-100 d-flex flex-column justify-content-center bg-white align-items-center p-4">
            <p class="title">Welcome Elon Tusk, Please complete your registration</p>
            <div class="d-flex justify-ontent-center w-100">
                <img src="/assets/signup/depositor-regimage.png" style="max-height: 200px; margin: 20px auto;" alt=""
                    srcset="">
            </div>
            <div class="row w-100">
                <div class="col-md-12 mt-3">
                    <p class="section-header">
                        Registered business address
                    </p>
                </div>

                <div class="col-md-3 my-1">
                    <CustomTextInput label="Street Address" placeholder="lome" />
                </div>
                <div class="col-md-3">
                    <CustomTextInput label="City/Town" :required="true" placeholder="67576676" input_type="text" />
                </div>
                <div class="col-md-3">
                    <CustomTextInput label="Province" :required="true" placeholder="R67576676" input_type="text" />
                </div>
                <div class="col-md-3">
                    <CustomTextInput label="Postal Code" :required="true" placeholder="2012/2/23" input_type="text" />
                </div>

                <div class="col-md-4">
                    <CustomTextInput label="Phone Number" :required="true" placeholder="+1 2543 3243 " input_type="text" />
                </div>
                <div class="col-md-4">
                    <CustomTextInput label="Email" :required="true" placeholder="biz@example.com" input_type="email" />
                </div>
                <div class="col-md-4">
                    <CustomTextInput label="Webiste " :required="true" placeholder="investments.ca" input_type="text" />
                </div>

                <!-- Differnt Business Adress -->

                <div class="col-md-12 py-1 mt-2">
                    <p class="subheading">Mailing Address <span> (if different from Business Address)</span></p>
                </div>
                <div class="col-md-3 my-1">
                    <CustomTextInput label="Street Address" placeholder="lome" />
                </div>
                <div class="col-md-3">
                    <CustomTextInput label="City/Town" :required="true" placeholder="67576676" input_type="text" />
                </div>
                <div class="col-md-3">
                    <CustomTextInput label="Province" :required="true" placeholder="R67576676" input_type="text" />
                </div>
                <div class="col-md-3">
                    <CustomTextInput label="Postal Code" :required="true" placeholder="2012/2/23" input_type="text" />
                </div>

                <div class="col-md-12 my-2 mb-3">
                    <label for="" class="textarea-label">Company Description</label>
                    <textarea class="textarea w-100" name="" id="" cols="" rows="5"></textarea>

                </div>
            </div>
        </div>
        <div class="w-100 d-flex justify-content-end mt-3 gap-2">
            <CustomSubmit @action="goNext" :outline="true" title="Previous" />
            <CustomSubmit @action="goNext" :outline="false" title="submit" />
        </div>
    </div>
</template>

<script>


import CustomSubmit from '../shared/CustomSubmit.vue';
import CustomTextInput from '../shared/CustomTextInput.vue';
import CustomSelectInput from '../shared/CustomSelectInput.vue';
import CustomMultiSelect from '../shared/CustomMultiSelect.vue';

import PopUpModal from '../shared/PopUpModal.vue';
import TitleWithIcon from '../shared/TitleWithIcon.vue';

export default {
    components: {
        CustomSubmit, CustomTextInput, CustomSelectInput, CustomMultiSelect, PopUpModal,
        TitleWithIcon
    },
    methods: {
        goNext() {

        }
    }
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

    font-family: Montserrat !important;
    font-size: 20px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 130% */
    text-transform: capitalize;
}

.subheading {
    color: #252525;
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;

}

.textarea-label {
    color: #252525;
    font-family: Montserrat !important;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;

}

.subheading span {
    color: #5063F4
}

.title {
    color: #5063F4;
    font-family: Montserrat !important;
    font-size: 20px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 130% */
    text-transform: capitalize;

}

.logo_upload_title {
    color: #252525;
    font-feature-settings: 'clig' off, 'liga' off;
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    text-transform: capitalize;
    padding: 0;
    margin: 0;
}

.logo_upload_desc {
    padding: 0;
    font-weight: 400;
    color: #252525;
    font-feature-settings: 'clig' off, 'liga' off;
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    line-height: 26px;
    /* 162.5% */
    text-transform: capitalize;
}

.section-header {
    color: #252525;
    /* font-feature-settings: 'clig' off, 'liga' off; */
    font-family: Montserrat !important;
    font-size: 24px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 108.333% */
    text-transform: capitalize;
}
</style>