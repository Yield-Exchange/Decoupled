<template>
    <div>
        <accordion :is_open="is_open" title="Organization Settings" width="95"
            title_image="/assets/dashboard/icons/orgsettings.svg" />
        <div style="margin-bottom: 50px;"></div>
        <div style="width: 95%;margin-top:20px;" class="row">

            <b-tabs content-class="mt-3" nav-wrapper-class="custom-tab-nav-class custom-tab-nav-class-border-bottom">
                <b-tab title="General Info" :active="activeTab === 'generalinfo'" @click="activeTab='generalinfo'">
                    <b-row style=" margin-top:50px;">
                        <b-col md="2"
                            style="max-width:200px !important; min-width:200px !important; margin-right: 10px; ">
                            <div class="dropzone" @dragover.prevent @dragenter.prevent @dragstart.prevent
                                @drop.prevent="handleFileChange($event.dataTransfer)"
                                style="width: 100%; max-width:200px; min-width:200px; padding: 25px; background: #EFF2FE; border: 0.50px #9CA1AA solid; display:flex; flex-direction: column; justify-content: center; align-items: center; gap: 10px; display: inline-flex">

                                <input id="file-input" type="file" accept="image/png, image/jpeg"
                                    @change="handleFileChange($event.target)" required title="" />
                                <img v-bind:src="preview" />
                                <p style="width:100%; text-align:center;"> Click to upload logo or drag and drop </p>

                            </div>
                        </b-col>
                        <b-col md="10">
                            <b-row style="margin: 0 px !important;">
                                <b-col md="6" style="margin: 0 px !important;" id="seleccont">

                                    <FormLabelRequired style="padding: 4px;" labelText="Organization Name"
                                        required="true" :showHelperText="false" helperText="Organization Name"
                                        helperId="organizationname" />
                                    <CustomInput :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                                        p-style="width: 100%;" c-style="font-weight: 400;width:100%;background:white"
                                        id="organization_name" name="Organization Name*" :has-validation="true"
                                        :default-value="institution_name" @inputChanged="setInstitutionName($event)"
                                        :hasSpecificError="(generailInfoErrors['institution_name'])" />
                                    <div class="error-message" v-if="generailInfoErrors['institution_name']">
                                        {{ generailInfoErrors['institution_name'] }}
                                    </div>
                                    <div id="my-form ">
                                        <!-- <Select2 :options="orgtypes" id="selectorgtype" /> -->
                                    </div>

                                </b-col>
                                <b-col md="6" style="margin: 0 px !important;">
                                    <FormLabelRequired style="padding: 4px;" labelText="Organization Type"
                                        required="true" :showHelperText="false" helperText="Organization Type"
                                        helperId="organizationtype" />


                                    <CustomSelect :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                                        p-style="width: 100%;" c-style="font-weight: 400;width:100%;background:white"
                                        :data="orgtypes" id="organization_type" :has-validation="true"
                                        :default-value="institution_type" @selectChanged="institution_type = $event" />
                                    <!-- <div class="error-message" v-if="(generailInfoErrors['institution_type'])">
                                        {{ generailInfoErrors['institution_type'] }}
                                    </div> -->
                                </b-col>
                            </b-row>
                            <b-row style="margin-top: 20px;">
                                <b-col md="3">
                                    <FormLabelRequired style="padding: 4px;" labelText="Year of Establishment"
                                        required="false" :showHelperText="false" helperText="Year of establishment"
                                        helperId="yearofestablishment" />
                                    <CustomInput :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                                        p-style="width: 100%;" c-style="font-weight: 400;width:100%;background:white"
                                        inputType="text" id="year_of_establishment" name="Year of Establishment*"
                                        :has-validation="true" :default-value="year_of_establishment"
                                        v-model="year_of_establishment" @inputChanged="validateYear($event)"
                                        :hasSpecificError="yearErrorMessage" />
                                    <div class="error-message" v-if="yearError">
                                        {{ yearError }}
                                    </div>
                                </b-col>
                                <b-col md="3">
                                    <FormLabelRequired style="padding: 4px;" labelText="Branches" required="true"
                                        :showHelperText="true" helperText="No of Branches" helperId="branches" />
                                    <CustomInput :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                                        p-style="width: 100%;" c-style="font-weight: 400;width:100%;background:white"
                                        id="branches" inputType="number" name="Branches*" :has-validation="true"
                                        :default-value=no_of_branches v-model="no_of_branches"
                                        @inputChanged="setInstitutionBranches($event)"
                                        :hasSpecificError="generailInfoErrors['no_of_branches']" />
                                    <div class="error-message" v-if="(generailInfoErrors['no_of_branches'])">
                                        {{ generailInfoErrors['no_of_branches'] }}
                                    </div>
                                </b-col>
                                <b-col md="3">
                                    <FormLabelRequired style="padding: 4px;" labelText="Province" required="true"
                                        :showHelperText="false" helperText="Province" helperId="Province" />

                                    <CustomSelect :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                                        p-style="width: 100%;" c-style="font-weight: 400;width:100%;background:white"
                                        :data="provincess" id="Province" name="Province" :has-validation="false"
                                        :default-value="capitalize(province)" @selectChanged="province = $event"
                                        :hasSpecificError="(generailInfoErrors['province'])" />
                                    <div class="error-message" v-if="(generailInfoErrors['province'])">
                                        {{ generailInfoErrors['province'] }}
                                    </div>
                                </b-col>
                                <b-col md="3">
                                    <FormLabelRequired style="padding: 4px;" labelText="City" required="true"
                                        :showHelperText="false" helperText="City" helperId="cityhelperid" />
                                    <!-- {{ short_term_rating }} -->
                                    <!-- <CustomSelect :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                                        p-style="width: 100%;" c-style="font-weight: 400;width:100%;background:white"
                                        :data="citieslist" id="city" name="City" :has-validation="false"
                                        @selectChanged="city = $event" /> -->
                                    <b-form-select class="mt-1" id="termlengthid" ref="termLengthSelect"
                                        :options="citieslist"
                                        style="width:100%;font-weight: 400;width:100%;font-size: 16px !important; height:40px; margin-left:15px;outline:none; box-shadow: none; border-radius: 999px;"
                                        v-model="city">
                                    </b-form-select>
                                    <span style="color:red;margin-left: 40px;"></span>
                                </b-col>
                            </b-row>
                        </b-col>
                        <!-- </div> -->
                    </b-row>
                    <b-row style="margin-top:70px;">
                        <b-col md="12">
                            <div
                                style="display: flex; align-items: center; flex-direction: row; justify-content: end; gap: 30px; ">
                                <b-button @click="goToTab('additionalinfo')"
                                    style=" width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: 2px !important;background-color: #5063F4 !important;color: white !important;">
                                    <b-spinner label="Loading" v-if="gensubmitted"
                                        style="width: 1.3rem; height: 1.3rem;margin-right:5px">
                                    </b-spinner>
                                    Next
                                </b-button>
                            </div>
                        </b-col>
                    </b-row>
                </b-tab>
                <b-tab :active="activeTab === 'additionalinfo'" title="Additional Info" @click="activeTab='generalinfo'">
                    <!-- <b-tab v-if="(activeTab!='additionalinfo')" title="Additional Info"> -->
                    <b-row style=" margin-top:50px;">

                        <b-col md="12">

                            <b-row style="margin-top: 20px;">
                                <b-col md="4">
                                    <FormLabelRequired style="padding: 4px;" labelText="Total Asset Size"
                                        required="true" showHelperText="true" helperText="Total Asset Size"
                                        helperId="totalassetsize" />
                                    <CustomInput inputType="number" id="total_asset_size" name="Total Asset Size *"
                                        :has-validation="true" @inputChanged="setTotalAsset($event)"
                                        :default-value="addCommas(total_asset_size)" v-model="total_asset_size"
                                        input-type="number" :defaultValue="total_asset_size"
                                        :hasSpecificError="(additionalInfoErrors['total_asset_size'])" />

                                    <div class="error-message" v-if="(additionalInfoErrors['total_asset_size'])">
                                        {{ additionalInfoErrors['total_asset_size'] }}
                                    </div>
                                </b-col>
                                <b-col md="4">

                                    <FormLabelRequiredImageHelper style="padding: 4px;" labelText="Credit Rating"
                                        required="true" :showHelperText="true"
                                        helperImage="assets/img/credit_rating.png" helperId="credit_rating" />
                                    <!-- <a data-toggle="tooltip" class="no-page-exit-alert"
                                        title="<img src='assets/img/credit_rating.png' style='width:100%' />" <i
                                        class="fa fa-info-circle" style="font-size: 20px"></i>
                                    </a> -->
                                    <CustomSelect :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                                        p-style="width: 100%;" c-style="font-weight: 400;width:100%;background:white"
                                        :data="credit_rating_types" id="credit_rating" name="Credit Rating"
                                        :has-validation="false" :default-value="credit_rating" v-model="credit_rating"
                                        @selectChanged="credit_rating = $event" />
                                    <span style="color:red;margin-left: 40px;"></span>
                                </b-col>
                                <b-col md="4">
                                    <FormLabelRequired style="padding: 4px;" labelText="Deposit Insurance"
                                        required="true" showHelperText="true" helperText="Deposit Insurance"
                                        helperId="deposit_insurence" />
                                    <CustomSelect :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                                        p-style="width: 100%;" c-style="font-weight: 400;width:100%;background:white"
                                        :data="deposit_insurances" id="deposit_insurence" name="Deposit Insurance"
                                        :has-validation="false" :default-value="deposit_insurance"
                                        v-model="deposit_insurance" @selectChanged="deposit_insurance = $event" />
                                    <span style="color:red;margin-left: 40px;"></span>
                                </b-col>
                                <!-- <b-col md="3">
                                    <FormLabelRequired style="padding: 4px;" labelText="Organization Type"
                                        required="true" :showHelperText="false" helperText="NAICS Code"
                                        helperId="naics_code" />

                                    <CustomSelect :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                                        p-style="width: 100%;" c-style="font-weight: 400;width:100%;background:white"
                                        :data="fiTypes" id="naics_code" name="naics_code" :has-validation="false"
                                        :default-value="naics_code" v-model="naics_code"
                                        @selectChanged="naics_code = $event" />
                                    <span style="color:red;margin-left: 40px;"></span>
                                </b-col> -->
                            </b-row>
                            <b-row style="margin: 0 px !important;">
                                <b-col md="6" style="margin: 0 px !important;">
                                    <FormLabelRequired style="padding: 4px;" labelText="Digital Account Opening"
                                        required="true" showHelperText="true" helperText="Digital Account Opening"
                                        helperId="digital_account_openingid" />
                                    <CustomSelect :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                                        p-style="width: 100%;" c-style="font-weight: 400;width:100%;background:white"
                                        :data="[{ 'id': 'Available', 'description': 'Available' }, { 'id': 'Not Available', 'description': 'Not Available' }]"
                                        id="digital_account_opening" name="Digital Account Opening
 *" :has-validation="false" :default-value="digital_account_opening" v-model="digital_account_opening"
                                        @selectChanged="digital_account_opening = $event" />
                                    <span style="color:red;margin-left: 40px;"></span>
                                </b-col>
                                <b-col md="6" style="margin: 0 px !important;">
                                    <FormLabelRequired style="padding: 4px;" labelText="Wholesale Deposit Portfolio"
                                        required="true" showHelperText="true" helperText="Wholesale Deposit Portfolio"
                                        helperId="wholesale_deposit_portfolio" />
                                    <!-- {{ wholesale_deposit_portfolio_id }} -->
                                    <CustomSelect :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                                        p-style="width: 100%;" c-style="font-weight: 400;width:100%;background:white"
                                        :data="depositPortfolioList" id="wholesale_deposit_portfolio"
                                        name="Wholesale Deposit Portfolio *" :has-validation="false"
                                        :default-value="wholesale_deposit_portfolio_id"
                                        v-model="wholesale_deposit_portfolio_id"
                                        @selectChanged="wholesale_deposit_portfolio_id = $event" />
                                    <span style="color:red;margin-left: 40px;"></span>
                                </b-col>
                                <!-- <b-col md="4" style="margin: 0 px !important;">
                                    <FormLabelRequired style="padding: 4px;" labelText="Short term DBRS rating"
                                        required="true" showHelperText="true" helperText="Short term DBRS rating"
                                        helperId="short_term_DBRS_rating" />
                                    <CustomSelect :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                                        p-style="width: 100%;" c-style="font-weight: 400;width:100%;background:white"
                                        :data="credit_rating_types" id="short_term_DBRS_rating"
                                        name="Short term DBRS rating *" :has-validation="false"
                                        :default-value="short_term_rating" v-model="credit_rating"
                                        @selectChanged="short_term_rating = $event" />
                                    <span style="color:red;margin-left: 40px;"></span>
                                </b-col> -->
                            </b-row>
                        </b-col>
                    </b-row>

                    <b-row style="margin-top:70px;">
                        <b-col md="12">
                            <div
                                style="display: flex; align-items: center; flex-direction: row; justify-content: end; gap: 30px; ">
                                <b-button @click="goToTab('generalinfo')"
                                    style=" width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: 2px !important;background-color: #D9D9D9 !important;color: white !important;">
                                    Previous
                                </b-button>
                                <!-- <b-button @click="updateGeneralInfo()"
                                    style=" width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: 2px !important;background-color: #5063F4 !important;color: white !important;">
                                    <b-spinner label="Loading" v-if="gensubmitted"
                                        style="width: 1.3rem; height: 1.3rem;margin-right:5px">
                                    </b-spinner>
                                    Submit
                                </b-button> -->
                                <b-button @click="goToTab('contactinfo')"
                                    style=" width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: 2px !important;background-color: #5063F4 !important;color: white !important;">
                                    <b-spinner label="Loading" v-if="addtionalsubmitted"
                                        style="width: 1.3rem; height: 1.3rem;margin-right:5px">
                                    </b-spinner>
                                    Next
                                </b-button>
                            </div>
                        </b-col>
                    </b-row>
                </b-tab>
                <b-tab :active="activeTab === 'contactinfo'" title="Contact Info" @click="activeTab='contactinfo'">
                    <b-row style=" margin-top:50px;">

                        <b-col md="12">

                            <b-row style="margin-top: 20px;">
                                <b-col md="4">
                                    <FormLabelRequired style="padding: 4px;" labelText="Address Line 1 " required="true"
                                        showHelperText="true" helperText="Address Line 1 "
                                        helperId="address_line_1_1HelperId" />
                                    <CustomInput :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                                        p-style="width: 100%;" c-style="font-weight: 400;width:100%;background:white"
                                        id="address_line_1" name="Address Line 1  *" :has-validation="true"
                                        :default-value="address_line_1" v-model="address_line_1"
                                        @inputChanged="setAddOne($event)"
                                        :hasSpecificError="contactInfoErrors['address_line_1']" />
                                    <span
                                        style="color:red;margin-left: 3px">{{contactInfoErrors['address_line_1']}}</span>
                                </b-col>
                                <b-col md="4">
                                    <FormLabelRequired style="padding: 4px;" labelText="Address Line 2 "
                                        showHelperText="true" helperText="Address Line 2 "
                                        helperId="address_line_2_1HelperId" />
                                    <CustomInput :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                                        p-style="width: 100%;" c-style="font-weight: 400;width:100%;background:white"
                                        id="address_line_2" name="Address Line 2  *" :has-validation="true"
                                        :default-value="address_line_2" v-model="address_line_2"
                                        @inputChanged="address_line_2 = $event" />
                                    <span style="color:red;margin-left: 3px;"></span>
                                </b-col>
                                <b-col md="4">
                                    <FormLabelRequired style="padding: 4px;" labelText="Postal Code " required="true"
                                        showHelperText="true" helperText="Postal Code"
                                        helperId="postal_code_helper_id" />
                                    <CustomInput :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                                        p-style="width: 100%;" c-style="font-weight: 400;width:100%;background:white"
                                        id="postal_code" name="Postal Code" :has-validation="true"
                                        :default-value="postal_code" v-model="postal_code"
                                        @inputChanged="setPostalCode($event)"
                                        :hasSpecificError="contactInfoErrors['postal_code']" />
                                    <span v-if="contactInfoErrors['postal_code']" style="color:red;margin-left: 3px;">
                                        {{contactInfoErrors['postal_code']}}</span>
                                </b-col>

                            </b-row>
                            <b-row style="margin: 0 px !important;">
                                <b-col md="4" style="margin: 0 px !important;">
                                    <FormLabelRequired style="padding: 4px;" labelText="Phone number" required="true"
                                        showHelperText="true" helperText="Phone number"
                                        helperId="phone_number_helper_id" />
                                    <vue-phone-number-input :value=telephone v-model="telephone"
                                        :only-countries="['CA']" :preferred-countries="['CA']" default-country-code="CA"
                                        :border-radius="20" size="md" />
                                    <!-- <b-alert v-if="telephoneErrors" show variant="danger" class="form-alert">{{
                                        telephoneErrors }}</b-alert> -->
                                    <!-- <CustomInput :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                                        p-style="width: 100%;" c-style="font-weight: 400;width:100%;background:white"
                                        id="phone_number" name="Phone Number " :has-validation="false"
                                        :default-value="telephone" v-model="telephone" @inputChanged="telephone = $event" />
                                    <span style="color:red;margin-left: 40px;"></span> -->
                                </b-col>

                                <b-col md="4" style="margin: 0 px !important;">
                                    <FormLabelRequired style="padding: 4px;" labelText="Email" required="true"
                                        showHelperText="true" helperText="Organization Email"
                                        helperId="email_helper_id" />
                                    <CustomInput :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                                        p-style="width: 100%;" c-style="font-weight: 400;width:100%;background:white"
                                        id="email" name="Email" :has-validation="true"
                                        :default-value="institution_email" v-model="email"
                                        @inputChanged="assignEmail($event)" :hasSpecificError="(emailError!='')" />
                                    <span style="color:red;margin-left: 40px;"></span>
                                    <p class="m-0 p-0 text-danger" v-if="emailError !='' ">{{emailError}}
                                    </p>
                                </b-col>
                                <b-col md="4" style="margin: 0 px !important;">
                                    <FormLabelRequired style="padding: 4px;" labelText="Website" required="true"
                                        showHelperText="true" helperText="Organization Website"
                                        helperId="website_helper_id" />
                                    <CustomInput :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                                        p-style="width: 100%;" c-style="font-weight: 400;width:100%;background:white"
                                        id="website" name="Website" :has-validation="true" :default-value="website"
                                        v-model="website" @inputChanged="assignWebsite($event)"
                                        :hasSpecificError="(websiteError!='')" />
                                    <p class="m-0 p-0 text-danger" v-if="websiteError !='' ">{{websiteError}}
                                    </p>
                                    <span style="color:red;margin-left: 40px;"></span>
                                </b-col>
                            </b-row>
                        </b-col>
                        <!-- </div> -->
                    </b-row>
                    <b-row style="margin-top:70px;">
                        <b-col md="12">
                            <div
                                style="display: flex; align-items: center; flex-direction: row; justify-content: end; gap: 30px; ">
                                <b-button @click="goToTab('additionalinfo')"
                                    style=" width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: 2px !important;background-color: #D9D9D9 !important;color: white !important;">
                                    Previous
                                </b-button>
                                <!-- <b-button @click="updateContactInfo()"
                                    style=" width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: 2px !important;background-color: #5063F4 !important;color: white !important;">
                                    <b-spinner label="Loading" v-if="contactsubmitted"
                                        style="width: 1.3rem; height: 1.3rem;margin-right:5px">
                                    </b-spinner>

                                    Submit
                                </b-button> -->
                                <b-button @click="goToTab('biotab')"
                                    style=" width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: 2px !important;background-color: #5063F4 !important;color: white !important;">
                                    <b-spinner label="Loading" v-if="contactsubmitted"
                                        style="width: 1.3rem; height: 1.3rem;margin-right:5px">
                                    </b-spinner>
                                    Next
                                </b-button>
                            </div>
                        </b-col>
                    </b-row>
                </b-tab>
                <b-tab :active="activeTab === 'biotab'" title="Bio" @click="activeTab='biotab'">
                    <b-row style=" margin-top:50px;">
                        <b-col md="12">

                            <b-textarea v-model=org_bio rows="10" @keyup="countCharaters"
                                style="font-weight: 400;width:100%;background:white;border-radius: 10px;outline:none; box-shadow: none;">

                            </b-textarea>
                            <div class="w-100 d-flex justify-content-end gap-3">
                                <p class="m-0 p-0 text-danger" v-if="companyDescError">Please keep the description
                                    length within the
                                    0 to {{
                                    maxCount }} character
                                    range.</p>
                                <p class="m-0 p-0 ">{{ currentCount }}/{{ maxCount }}</p>
                            </div>
                            <!-- <FormLabelRequired style="padding: 4px;" labelText="Organization Bio" required="true"
                                showHelperText="true" helperText="Organization Bio" helperId="totalassetsize" /> -->
                            <!-- <CustomInput :attributes="{ 'value_field': 'id', 'textarea': 'description' }"
                                inputType="textareanew" p-style="width: 100%;"
                                c-style="font-weight: 400;width:100%;background:white" id="organization_bio"
                                name="Organization Bio" :has-validation="false" rows="20" :default-value="org_bio"
                                v-model="org_bio" @inputChanged="org_bio = $event" /> -->
                        </b-col>
                    </b-row>
                    <b-row style="margin-top:70px;">
                        <b-col md="12">
                            <div
                                style="display: flex; align-items: center; flex-direction: row; justify-content: end; gap: 30px; ">
                                <b-button @click="goToTab('contactinfo')"
                                    style=" width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: 2px !important;background-color: #D9D9D9 !important;color: white !important;">
                                    Previous
                                </b-button>
                                <!-- <b-button @click="updateBioInfo()"
                                    style=" width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: 2px !important;background-color: #5063F4 !important;color: white !important;">
                                    <b-spinner label="Loading" v-if="biosubmitted"
                                        style="width: 1.3rem; height: 1.3rem;margin-right:5px">
                                    </b-spinner>
                                    Submit
                                </b-button> -->
                                <b-button @click="goToTab('documents')"
                                    style=" width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: 2px !important;background-color: #5063F4 !important;color: white !important;">
                                    <b-spinner label="Loading" v-if="biosubmitted"
                                        style="width: 1.3rem; height: 1.3rem;margin-right:5px">
                                    </b-spinner> <b-spinner label="Loading" v-if="biosubmitted"
                                        style="width: 1.3rem; height: 1.3rem;margin-right:5px">
                                    </b-spinner>
                                    Next
                                </b-button>
                            </div>
                        </b-col>
                    </b-row>
                </b-tab>
                <b-tab :active="activeTab === 'documents'" title="Documents" @click="activeTab='documents'">
                    <b-row style=" margin-top:50px;">
                        <b-col md="6">
                            <!-- <FormLabelRequired labelText="Transfer Details" required="true" showHelperText="true"
                                helperText="" helperId="PDSHId" />
                            <FileUpload :file_selected.sync="transfer_doc_file" />
                            <span v-if="isInvalidTermLength" style="color:red;margin-left: 40px;">Invalid.</span> -->
                            <FormLabelRequired labelText="Transfer Details" required="true" showHelperText="true"
                                helperText="Your transfer details in PDF format." helperId="transferpdf" />

                            <br />
                            <UploadFile @fileSelected="getTransferDetailsFile($event)" :defaultfile="transferdetails"
                                @fileError="fileError($event,'transfer')" :useruploadname="transferdetailsfilename"
                                filelabel="transferfile" @deleteFile="deleteFile($event)" />
                            <span v-if="isValidTransferdocument"
                                style="color:red;">{{transferDetailsStatementError}}</span>
                        </b-col>
                        <b-col md="6">
                            <FormLabelRequired labelText="Financial Statement" required="true" showHelperText="true"
                                helperText="Your Latest Financial statement in pdf format." helperId="finstatpdf" />

                            <br />
                            <UploadFile @fileSelected="getFinancialStatementFile($event)"
                                @fileError="fileError($event,'financialstatement')" :defaultfile="financialstatement"
                                :useruploadname="financialstatementfilename" filelabel="financialfile"
                                @deleteFile="deleteFile($event)" />
                            <span v-if="isValidFinancialStatementdocument"
                                style="color:red;">{{financialStatementError}}</span>
                        </b-col>


                    </b-row>
                    <b-row style="margin-top:70px;">
                        <b-col md="12">
                            <div
                                style="display: flex; align-items: center; flex-direction: row; justify-content: end; gap: 30px; ">
                                <b-button @click="goToTab('biotab')"
                                    style=" width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: 2px !important;background-color: #D9D9D9 !important;color: white !important;">
                                    Previous
                                </b-button>
                                <b-button @click="goToTab('tradesettings')"
                                    style=" width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: 2px !important;background-color: #5063F4 !important;color: white !important;">
                                    <b-spinner label="Loading" v-if="docsubmitted"
                                        style="width: 1.3rem; height: 1.3rem;margin-right:5px">
                                    </b-spinner>
                                    Next
                                </b-button>
                            </div>
                        </b-col>
                    </b-row>
                </b-tab>
                <b-tab :active="activeTab === 'tradesettings'" title="Trade Settings">
                    <b-row style=" margin-top:50px;">
                        <b-col md="6">
                            <label>Auto Settle</label>
                            <b-button type="button" variant="primary" @click="toggleAutoSettle">{{ autoSettle ? 'ON' : 'OFF' }}</b-button>
                        </b-col>
                    </b-row> 
                    <b-row style="margin-top:70px;">
                        <b-col md="12">
                            <div
                                style="display: flex; align-items: center; flex-direction: row; justify-content: end; gap: 30px; ">
                                <b-button @click="goToTab('documents')"
                                    style=" width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: 2px !important;background-color: #D9D9D9 !important;color: white !important;">
                                    Previous
                                </b-button>
                                <b-button @click="updateTransferDetails()"
                                    style=" width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: 2px !important;background-color: #5063F4 !important;color: white !important;">
                                    <b-spinner label="Loading" v-if="docsubmitted"
                                        style="width: 1.3rem; height: 1.3rem;margin-right:5px">
                                    </b-spinner>
                                    Submit
                                </b-button>
                            </div>
                        </b-col>
                    </b-row>
                    
                </b-tab>
            </b-tabs>

            <OKButtonActionMessageBoxVue :size="successModalSize" @closedSuccessModal="showSuccessModal = false"
                :title="successModalTitle" :showm="showSuccessModal" @okClicked="showSuccessModal = false" />

            <GeneralNoInteractionError :size="errorModalSize" @closedModal="closeErrorModal()" :title="errorModalTitle"
                :show="showErrorModal" :message="errorModalMessage" />

            <ConfirmDeletionPrompt :size="successModalSize" @closedSuccessModal="deleteFilePrompt = 0" btnOneText="No"
                btnTwoText="Yes" title='Are you sure you want to delete this file?' :showm="deleteFilePrompt === 1"
                @btnOneClicked="deleteFilePrompt = 0" @btnTwoClicked="processDeleteFile()"
                message="This operation cannot be undone." />
        </div>

    </div>
</template>
<style>
    .tabtitle {
        color: #5063F4;
        font-size: 16px !important;
        font-family: Montserrat !important;
        font-weight: 700 !important;
        text-transform: capitalize !important;
        line-height: 26px !important;
        word-wrap: break-word !important;
    }

    .swal-button-actions {
        flex-direction: row-reverse !important;
        display: flex !important;
    }

    .row123 .tooltip-inner {
        background: transparent !important;
    }
</style>
<style scoped>
    .dashboard-body .card-body {
        padding: 0;
    }

    .dashboard-body .card {
        border-radius: 10px;
    }

    .btn.btn-secondary[disabled] {
        background-color: #979797;
        color: black;
    }

    .verror {
        border: 1px solid red;
        border-radius: 5px;
    }

    #my-form .select2-container–default .select2-selection–single {
        border: none;
    }

    .dropzone {
        width: 100%;
        min-height: 200px;
        height: 100% !important;
    }

    input[type="file"] {
        position: absolute;
        opacity: 0;
        width: inherit;
        min-height: 200px;
        max-height: 500px;
        cursor: pointer;

    }

    img {
        width: 100px;
        height: 100px;
        cursor: pointer;
    }

    .error-message {
        color: #FF2E2E;
        height: 20px;
        font-family: Montserrat;
        font-size: 11px;
        font-weight: 400;
        line-height: 19px;
        letter-spacing: 0em;
        text-align: left;
        min-width: 200px;
    }
</style>
<script>

    import Avatar from 'vue-avatar';
    import Vue from 'vue';
    import { confirmLeavePage } from "../../utils/GlobalUtils";
    import Accordion from "../shared/Accordion.vue";
    import CustomSelect from "../shared/CustomSelect";
    import CustomInput from "../shared/CustomInput";
    import FormLabelRequired from "../shared/formLabels/FormLabelRequired.vue";

    import FormLabelRequiredImageHelper from "../shared/formLabels/FormLabelRequiredImageHelper.vue";


    import Select2 from 'v-select2-component';

    import myUpload from 'vue-image-crop-upload/upload-2.vue';
    import OKButtonActionMessageBoxVue from "../shared/messageboxes/NoButtonActionMessageBox.vue";

    import GeneralNoInteractionError from "../shared/messageboxes/GeneralNoInteractionError.vue";

    import { MazPhoneNumberInput } from 'maz-ui';
    import FileUpload from "../shared/FileUpload";

    import UploadFile from "../shared/UploadFile2";
    import ConfirmDeletionPrompt from "../shared/messageboxes/ConfirmDeletionPrompt.vue";

    export default {
        mounted() {


            confirmLeavePage(this, document);


        },
        beforeMount() {
            this.loadCities(this.province);
        },
        components: {
            ConfirmDeletionPrompt,
            UploadFile,
            FormLabelRequiredImageHelper,
            GeneralNoInteractionError,
            MazPhoneNumberInput,
            OKButtonActionMessageBoxVue,
            'my-upload': myUpload,
            Select2,
            CustomSelect,
            FormLabelRequired,
            CustomInput,
            Accordion,
            Avatar,
            FileUpload
        },
        created() {
        },
        props: ['provinces', 'naicsCodes', 'potentialDeposits', 'depositPortfolio'
            , 'fiTypes', 'user', 'organization', 'redirectRoute', 'updateAccountSettingRoute', 'deposit_insurances', 'credit_rating_types', 'permittedSubmitButton', 'organizations_list'],
        data() {

            console.log(this.organization, "organizationorganizationorganization");
            let transferdetails = null;
            let financialstatement = null;
            let transferdetailsname = null;
            let financialstatementname = null;
            if (this.organization?.document != null) {
                this.organization?.document.forEach((value, key) => {
                    console.log((value.user_uploaded_file_name != null), "value.user_uploaded_file_name");
                    if (value.document_type == "Transfer Details") {
                        transferdetails = value.file_name;
                        if (value.user_uploaded_file_name != null) {
                            transferdetailsname = value.user_uploaded_file_name;
                        } else {
                            let splitfilename = value.file_name.split("/");
                            console.log(splitfilename, "splitfilenamesplitfilename");
                            if (splitfilename.length === 3) {
                                transferdetailsname = splitfilename[2];
                            }
                        }

                    } else if (value.document_type == "Financial Statement") {
                        financialstatement = value.file_name;
                        if (value.user_uploaded_file_name != null) {
                            financialstatementname = value.user_uploaded_file_name;
                        } else {
                            let splitfilename = value.file_name.split("/");
                            console.log(splitfilename, "splitfilenamesplitfilename");
                            if (splitfilename.length === 3) {
                                financialstatementname = splitfilename[2];
                            }
                        }
                    }
                })

            }

            const provinces_ = JSON.parse(this.provinces).map((item, index) => {
                return { id: this.capitalize(item), description: item };
            });

            const orgtypes = JSON.parse(this.fiTypes).map((item, index) => {
                return { id: item.id, description: item.description };
            });
            const depositPortfolioList = this.depositPortfolio.map((item, index) => {
                return { id: item.id, description: item.band };
            });

            return {
                hasGotTransferFilesAlready: (transferdetailsname != null) ? true : false,
                hasGotFinanceFilesAlready: (financialstatementname != null) ? true : false,
                financialStatementError: "",
                transferDetailsStatementError: "",
                isValidFinancialStatementdocument: false,
                isValidTransferdocument: false,
                transferdetails: transferdetails,
                financialstatement: financialstatement,
                transferdetailsfilename: transferdetailsname,
                financialstatementfilename: financialstatementname,
                maxCount: 300,
                currentCount: (this.organization?.demographic_data?.org_bio === null || this.organization?.demographic_data?.org_bio === "" || this.organization?.demographic_data?.org_bio === "null") ? 0 : this.organization?.demographic_data?.org_bio.length,
                companyDescError: false,
                isInvalidTermLength: false,
                transfer_doc_file: null,
                financial_statement_file: null,
                validyears: Array.from({ length: 2023 - 1800 + 1 }, (_, index) => 1800 + index),
                errorModalTitle: "",
                errorModalSize: "md",
                showErrorModal: false,
                errorModalMessage: "",
                showSuccessModal: false,
                successModalTitle: "",
                successModalSize: "md",
                fileName: "",
                preview: (this.organization?.logo != null) ? `image/${this.organization?.logo}` : 'images/custom_logo.jpg',
                preset: process.env.VUE_APP_UPLOAD_PRESET,
                formData: null,
                cloudName: process.env.VUE_APP_CLOUD_NAME,
                success: "",
                imgDataUrl: '',
                provincess: provinces_,
                orgtypes: orgtypes,
                depositPortfolioList: depositPortfolioList,
                is_open: true,
                email: this.organization?.demographic_data?.org_email,
                citieslist: [],
                formErrors: '',
                institution_name: this.organization.name,
                institution_email: this.organization?.demographic_data?.email,
                no_of_branches: this.organization?.demographic_data?.no_of_branches,
                year_of_establishment: this.organization?.demographic_data?.year_of_establishment,
                profile_image: '',
                recaptchaToken: '',
                province: this.organization?.demographic_data?.province,
                naics_code: this.organization?.naics_code_id,
                institution_type: this.organization.fi_type_id,
                address_line_1: (this.organization?.demographic_data?.address1 === null) ? "" : this.organization?.demographic_data?.address1,
                address_line_2: (this.organization?.demographic_data?.address2 === null) ? "" : this.organization?.demographic_data?.address2,
                city: this.organization?.demographic_data?.city,
                postal_code: (this.organization?.demographic_data?.postal_code === null) ? "" : this.organization?.demographic_data?.postal_code,
                telephone: (this.organization?.demographic_data?.telephone === null) ? "" : this.organization?.demographic_data?.telephone,
                website: (this.organization?.demographic_data?.website === null) ? "" : this.organization?.demographic_data?.website,
                potential_deposit: this.organization?.potential_yearly_deposit,
                wholesale_deposit_portfolio_id: this.organization?.wholesale_deposit_portfolio_id,
                digital_account_opening: this.organization?.digital_account_opening,
                fis: JSON.stringify([]),
                organizationType: this.capitalize(this.organization.type),
                submitButtonText: 'Save',
                submitButtonSpinner: false,
                showLabel: true,
                credit_rating: this.organization?.deposit_credit_rating?.credit_rating?.id,
                short_term_rating: this.organization?.demographic_data?.short_term_DBRS_rating_id,
                deposit_insurance: this.organization?.deposit_credit_rating?.deposit_insurance_id,
                organization_id: this.organization.id,
                creditRatingError: '',
                depositInsuranceError: '',
                total_asset_size: (this.organization?.demographic_data?.value_of_assets != null) ? this.addCommas(this.organization?.demographic_data?.value_of_assets) : 0,
                org_bio: (this.organization?.demographic_data?.org_bio === null || this.organization?.demographic_data?.org_bio === "null" || this.organization?.demographic_data?.org_bio === "") ? "" : this.organization?.demographic_data?.org_bio,
                superAdmin: this.user?.is_super_admin ?? false,
                yearError: false,
                yearErrorMessage: false,
                generailInfoErrors: {},
                additionalInfoErrors: {},
                contactInfoErrors: {},
                emailError: "",
                websiteError: "",
                addtionalsubmitted: false,
                gensubmitted: false,
                docsubmitted: false,
                biosubmitted: false,
                contactsubmitted: false,
                activeTab: 'generalinfo',
                deleteFilePrompt: 0,
                fileToDelete: "",
                generalErrors: false,
                autoSettle: false

            }
        },
        computed: {

            allowSubmit() {
                // return this.canSubmit();
                return true;
            },
            getDepositInsurances() {
                return this.deposit_insurances;
            },
            getShortTermDBRSRatings() {
                return this.credit_rating_types;
            },
            selectedOrganizationType: {
                get() {
                    return this.organizationType;
                },
                set(orgType) {
                    this.organizationType = orgType;

                }
            }
        },
        methods: {
            toggleAutoSettle() {
                this.autoSettle = !this.autoSettle;
            },

            setPostalCode(val) {
                this.postal_code = val;
                if (val != null && val != "") {
                    this.institution_name = val;
                    this.$delete(this.contactInfoErrors, 'postal_code', '');
                } else {
                    this.$set(this.contactInfoErrors, 'postal_code', 'Field is required');
                }
            },

            setInstitutionName(name) {
                this.institution_name = name;
                if (name != null && name != "") {
                    this.$delete(this.generailInfoErrors, 'institution_name', '');
                } else {
                    this.$set(this.generailInfoErrors, 'institution_name', 'Field is required');
                }

            },
            setInstitutionBranches(branches) {
                //   alert(name);
                if (branches != null && branches != "") {
                    this.no_of_branches = branches;
                    this.$delete(this.generailInfoErrors, 'no_of_branches', '');
                } else {
                    this.$set(this.generailInfoErrors, 'no_of_branches', 'Field is required');
                }

            },
            async goToTab(nextto) {

                if (nextto === "additionalinfo") {
                    await this.updateGeneralInfo(nextto);
                    if (Object.keys(this.generailInfoErrors).length === 0 && !this.yearErrorMessage) {
                        this.activeTab = nextto;
                    }

                } else if (nextto === "contactinfo") {
                    await this.updateAdditionalInfo(nextto);
                    if (Object.keys(this.additionalInfoErrors).length === 0) {
                        this.activeTab = nextto;
                    }

                } else if (nextto === "biotab") {
                    await this.updateContactInfo(nextto);
                    if (Object.keys(this.contactInfoErrors).length === 0) {
                        this.activeTab = nextto;
                    }

                } else if (nextto === "documents") {
                    await this.updateBioInfo(nextto);
                    this.activeTab = nextto;
                } else if (nextto === "generalinfo") {
                    await this.updateTransferDetails(nextto);
                    this.activeTab = nextto;
                }else if(nextto === "tradesettings"){
                    this.activeTab = nextto
                }




            },
            processDeleteFile() {
                let formData = new FormData();
                this.biosubmitted = true;
                formData.append("type", this.fileToDelete);
                formData.append("org", this.organization?.id);
                let url = this.superAdmin ? `yie-admin/delete-fi-file` : `delete-fi-file`;
                axios.post(url, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(response => {
                    this.deleteFilePrompt = 0;
                    this.biosubmitted = false;
                    let res = response;
                    this.showSuccessModal = true;
                    this.successModalTitle = res?.data.message;
                    this.organization = res?.data?.thisorg;

                }).catch(error => {
                    console.log(error, "error");
                    this.biosubmitted = false;
                    this.showErrorModal = true;
                    this.errorModalTitle = 'Failed';
                    this.errorModalMessage = "Server Error.Please contact admin.";
                });

            },
            deleteFile(file) {
                console.log(file);
                if (file == "transferfile" && this.hasGotTransferFilesAlready) {
                    this.deleteFilePrompt = 1;
                    this.fileToDelete = file;
                }
                if (file == "financialfile" && this.hasGotFinanceFilesAlready) {
                    this.deleteFilePrompt = 1;
                    this.fileToDelete = file;
                }

            },
            addCommas(newvalue) {
                return newvalue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            },
            fileError(errortype, filename) {
                if (filename === "transfer") {
                    if (errortype != "") {
                        this.isValidTransferdocument = true;
                        this.transferDetailsStatementError = errortype;
                    } else {
                        this.isValidTransferdocument = false;
                        this.transferDetailsStatementError = "";
                    }

                } else if (filename === "financialstatement") {
                    if (errortype != "") {
                        this.isValidFinancialStatementdocument = true;
                        this.financialStatementError = errortype;
                    } else {
                        this.isValidFinancialStatementdocument = false;
                        this.financialStatementError = "";
                    }

                }
            },
            getTransferDetailsFile(file) {

                if (this.isValidPDF(file)) {
                    this.transfer_doc_file = file;
                    this.isValidTransferdocument = false;
                } else {
                    this.isValidTransferdocument = true;
                    this.transferDetailsStatementError = "Invalid file format.Allowed file type is PDF"

                }
            },
            getFinancialStatementFile(file) {
                if (this.isValidPDF(file)) {
                    this.financial_statement_file = file;
                    this.isValidFinancialStatementdocument = false;

                } else {
                    this.isValidFinancialStatementdocument = true;
                    this.financialStatementError = "Invalid file format.Allowed file type is PDF"
                }

            },
            isValidPDF(file) {

                return file.type === 'application/pdf';
            },
            assignWebsite(website) {
                this.website = website;
                if (this.validateWebsite(this.website)) {
                    this.websiteError = "";
                } else {
                    this.websiteError = " Enter Valid wesite.";
                }


            },
            setAddOne(val) {

                this.address_line_1 = val;
                if (val === "" || val === null) {

                    this.$set(this.contactInfoErrors, 'address_line_1', 'Field is required.');
                } else {
                    this.$delete(this.contactInfoErrors, 'address_line_1');
                }
            },
            setTotalAsset(val) {

                this.total_asset_size = this.addCommas(val);

                if (this.sanitizeAmount(val) > 9000000000000 || this.sanitizeAmount(val) < 1 || val === "" || val === null) {
                    this.$set(this.additionalInfoErrors, 'total_asset_size', 'Must be between 1 and 9,000,000,000,000');
                } else {
                    Vue.delete(this.additionalInfoErrors, 'total_asset_size');
                }
            },
            assignEmail(email) {
                this.institution_email = email;
                if (this.validateEmail(this.institution_email)) {
                    this.emailError = "";
                } else {
                    this.emailError = "Invalid Email";
                }
            },
            countCharaters() {
                this.currentCount = this.org_bio.length
                if (this.maxCount < this.currentCount) {
                    this.companyDescError = true
                } else {
                    this.companyDescError = false
                }
                // console.log(this.companyDesc.length)
            },
            handleChange(value, name) {
                if (name === 'total_asset_size') {
                    this.total_asset_size = value;

                    if (this.sanitizeAmount(value) > 9000000000000 || this.sanitizeAmount(value) < 1 || value === "" || value === null) {
                        this.$set(this.additionalInfoErrors, 'total_asset_size', 'Must be up to 9,000,000,000,000');
                    } else {
                        Vue.delete(this.additionalInfoErrors, 'total_asset_size');
                    }
                }
            },
            closeErrorModal() {
                this.submitted = false;
                this.showErrorModal = false;
            },
            handleFileChange: function (event) {
                this.file = event.files[0];
                this.fileName = this.file.name;
                this.formData = new FormData();
                let reader = new FileReader();
                reader.readAsDataURL(this.file);
                reader.onload = (e) => {
                    this.preview = e.target.result;
                    this.formData.append("file", this.preview);
                };
            },
            validateYear(value) {
                this.year_of_establishment = value;
                const yearRegex = /^\d{4}$/;
                if (!yearRegex.test(value)) {
                    this.yearErrorMessage = true
                    this.yearError = 'Please enter a valid year (e.g., 2024)';
                } else {
                    const currentYear = new Date().getFullYear();
                    const enteredYear = parseInt(value, 10);
                    if (enteredYear < 1800 || enteredYear > currentYear) {
                        this.yearErrorMessage = true
                        this.yearError = `Please enter a year between 1800 and ${currentYear}`;
                    } else {
                        this.yearErrorMessage = false
                        this.yearError = '';
                    }
                    //this.yearErrorMessage =false
                }
            },
            provinceechanged(val) {

            },
            validateEmail(email) {
                if (/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    console.log(email, "Valid");
                    return (true)
                }
                console.log(email, "invalid");
                return false;
                //const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                // return emailRegex.test(email);
            },
            validateWebsite(url) {
                const urlRegex = /^(?:(?:(?:https?|ftp):)?\/\/)?(?:(?:[\w\-.])+\.)+[a-z]{2,}(?::\d{1,5})?(?:\/[^\s]*)?$/i;
                if (urlRegex.test(url)) {
                    let allexceptProtocol;
                    if (url.startsWith("http://")) {
                        allexceptProtocol = url.split("http://")[1];
                    } else if (url.startsWith("https://")) {
                        allexceptProtocol = url.split("https://")[1];
                    } else {
                        allexceptProtocol = url;
                    }

                    let wwwReplaced = allexceptProtocol.replace(/^www\./i, "");
                    const remainingUrlRegex = /^((?!www)[\w-]+\.)+[a-z]{2,}$/i;
                    if (remainingUrlRegex.test(wwwReplaced)) {
                        console.log(wwwReplaced, "Domain Without WWW");
                        return true; // URL is valid
                    } else {
                        return false; // URL is not valid
                    }
                } else {
                    return false;
                }
            },
            validateWebsite1(url) {
                const urlRegex = /^(https?:\/\/)([a-z0-9-]+\.)+[a-z]{2,}(\/[^\s]*)?$/i;
                if (urlRegex.test(url)) {
                    let allexceptProtocol = url.split(/https?:\/\//)[1];
                    let wwwReplaced = allexceptProtocol.replace(/^www\./i, "");
                    console.log(wwwReplaced, "Domain Without WWW");

                    const regx2 = /^([a-z0-9-]+\.)+[a-z]{2,}$/i;
                    return regx2.test(wwwReplaced);
                } else {
                    return false;
                }

                return
            },
            async updateGeneralInfo(nextto) {
                let hasErrors = false;
                this.gensubmitted = false
                if (this.yearErrorMessage) {

                    hasErrors = true;
                }
                if (this.no_of_branches === '' || this.no_of_branches === 0) {
                    this.$set(this.generailInfoErrors, 'no_of_branches', 'Field is required');
                    hasErrors = true;
                } else {
                    this.$delete(this.generailInfoErrors, 'no_of_branches');
                }
                if (this.province === '') {
                    this.$set(this.generailInfoErrors, 'province', 'Field is required');
                    hasErrors = true;
                } else {
                    this.$delete(this.generailInfoErrors, 'province');
                }
                if (this.institution_name === '' || this.institution_name === null) {
                    this.$set(this.generailInfoErrors, 'institution_name', 'Field is required');
                    hasErrors = true;
                } else {
                    this.$delete(this.generailInfoErrors, 'institution_name');
                }

                if (this.city === '') {
                    this.$set(this.generailInfoErrors, 'city', 'Field is required');
                    hasErrors = true;
                } else {
                    this.$delete(this.generailInfoErrors, 'city');
                }
                if (!hasErrors) {
                    this.gensubmitted = true;
                }
                let geninf = {
                    "institution_name": this.institution_name,
                    'org_type': this.organizationType,
                    'yr_of_establishment': this.year_of_establishment,
                    'no_of_branches': this.no_of_branches,
                    'province': this.province,
                    'city': this.city
                };

                let formData = new FormData();
                formData.append("type", "gen");
                formData.append("orgtype", this.organizationType);
                formData.append("logo", this.file);
                formData.append("org", this.organization?.id);
                formData.append("institution_name", this.institution_name);
                formData.append("institution_type", this.institution_type);
                formData.append("yr_of_establishment", this.year_of_establishment);
                formData.append("no_of_branches", this.no_of_branches);
                formData.append("province", this.province);
                formData.append("city", this.city);

                if (hasErrors) {
                    this.showErrorModal = true;
                    this.errorModalTitle = 'Failed';
                    this.errorModalMessage = "Please fill in all fields correctly.";
                    return;
                };

                let url = this.superAdmin ? `yie-admin/update-profile-info` : `update-profile-info`;
                axios.post(url, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(response => {
                    this.gensubmitted = false;
                    let res = response;

                    this.successModalTitle = res?.data.message;
                    //     this.organization = res?.data?.thisorg;
                    if (res?.data?.somerecordschanged) {
                        this.showSuccessModal = true;
                        setTimeout(() => {
                            this.showSuccessModal = false;

                        }, 1000);
                    }

                }).catch(error => {
                    this.gensubmitted = false;
                });

            },
            sanitizeAmount(val) {
                try {
                    return parseFloat(val.replace(/,/g, "").replace(/ /g, ""));
                } catch (e) {
                    return val;
                }
            },
            async updateAdditionalInfo(nextto) {

                let hasErrors = false;
                if (this.total_asset_size === null || this.total_asset_size === "" || this.sanitizeAmount(this.total_asset_size) > 9000000000000 || this.sanitizeAmount(this.total_asset_size) < 1) {
                    this.$set(this.additionalInfoErrors, 'total_asset_size', 'Must be between 1 and 9,000,000,000,000');
                    hasErrors = true;
                }
                if (hasErrors) {
                    this.showErrorModal = true;
                    this.errorModalTitle = 'Failed';
                    this.errorModalMessage = "Please fill in all fields correctly.";
                    return;
                };
                this.addtionalsubmitted = true;

                let formData = new FormData();
                formData.append("type", "addtional");
                formData.append("orgtype", this.organizationType);
                formData.append("org", this.organization?.id);
                formData.append("total_asset_size", this.sanitizeAmount(this.total_asset_size));
                formData.append("credit_rating", this.credit_rating);
                formData.append("deposit_insurance", this.deposit_insurance);
                formData.append("naics_code", this.naics_code);
                formData.append("digital_account_opening", this.digital_account_opening);
                formData.append("wholesale_deposit_portfolio_id", this.wholesale_deposit_portfolio_id);
                formData.append("short_term_rating", this.short_term_rating);

                let url = this.superAdmin ? `yie-admin/update-profile-info` : `update-profile-info`;
                axios.post(url, formData, {
                }).then(response => {
                    this.addtionalsubmitted = false;
                    let res = response;
                    this.successModalTitle = res?.data.message;
                    //  this.organization = res?.data?.thisorg;
                    if (res?.data?.recordsChanged) {
                        this.showSuccessModal = true;
                        setTimeout(() => {
                            this.showSuccessModal = false;
                        }, 1000);
                    }
                    //  alert(this.activeTab);
                }).catch(error => {
                    this.addtionalsubmitted = false;
                });
            },
            async updateContactInfo(nextto) {
                let hasErrors = false;
                if (this.institution_email === 'null' || this.institution_email === null || this.institution_email == '' || this.emailError != '') {
                    this.$set(this.contactInfoErrors, 'institution_email', 'Valid Email Required');
                    this.emailError = 'Valid Email Required';
                    hasErrors = true;
                } else {
                    this.$delete(this.contactInfoErrors, 'institution_email');
                }
                if (this.website === 'null' || this.website === null || this.website == '' || this.websiteError != '' || !this.validateWebsite(this.website)) {
                    this.$set(this.contactInfoErrors, 'website', 'Valid Website Required');
                    this.websiteError = 'Valid Website Required.';
                    hasErrors = true;
                } else {
                    this.$delete(this.contactInfoErrors, 'website');
                }
                if (this.address_line_1 === 'null' || this.address_line_1 === null || this.address_line_1 == '') {
                    this.$set(this.contactInfoErrors, 'address_line_1', 'Field is required.');
                    hasErrors = true;
                } else {
                    this.$delete(this.contactInfoErrors, 'address_line_1');
                }

                if (this.postal_code === 'null' || this.postal_code === null || this.postal_code == '') {
                    this.$set(this.contactInfoErrors, 'postal_code', 'Field is required');
                    hasErrors = true;
                } else {
                    this.$delete(this.contactInfoErrors, 'postal_code');
                }

                if (hasErrors) {
                    this.showErrorModal = true;
                    this.errorModalTitle = 'Failed';
                    this.errorModalMessage = "Please fill in all fields correctly.";
                    return;
                };
                this.contactsubmitted = true;
                console.log(this.contactInfoErrors);
                let formData = new FormData();
                formData.append("type", "contact");
                formData.append("org", this.organization?.id);
                formData.append("orgtype", this.organizationType);
                formData.append("address_line_1", this.address_line_1);
                formData.append("postal_code", this.postal_code);
                formData.append("address_line_2", this.address_line_2);
                formData.append("telephone", this.telephone);
                formData.append("institution_email", this.institution_email);
                formData.append("website", this.website);
                let url = this.superAdmin ? `yie-admin/update-profile-info` : `update-profile-info`;
                axios.post(url, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(response => {
                    this.contactsubmitted = false;
                    let res = response;
                    this.successModalTitle = res?.data.message;
                    //   this.organization = res?.data?.thisorg;
                    if (res?.data?.somerecordschanged) {
                        this.showSuccessModal = true;
                        setTimeout(() => {
                            this.showSuccessModal = false;
                        }, 1000);
                    }
                }).catch(error => {
                    this.contactsubmitted = false;
                    this.showErrorModal = true;
                    this.errorModalTitle = 'Failed';
                    this.errorModalMessage = "Server Error.Please contact admin.";
                });
            },
            async updateBioInfo(nextto) {
                if (this.companyDescError == false) {
                    let formData = new FormData();
                    this.biosubmitted = true;
                    formData.append("type", "bio");
                    formData.append("org", this.organization?.id);
                    formData.append("orgtype", this.organizationType);
                    formData.append("bio", this.org_bio);
                    let url = this.superAdmin ? `yie-admin/update-profile-info` : `update-profile-info`;
                    axios.post(url, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(response => {
                        this.biosubmitted = false;
                        let res = response;

                        this.successModalTitle = res?.data.message;

                        //  this.organization = res?.data?.thisorg;
                        if (res?.data?.somerecordschanged) {
                            this.showSuccessModal = true;
                            setTimeout(() => {
                                this.showSuccessModal = false;
                            }, 1000);
                        }
                    }).catch(error => {
                        this.biosubmitted = false;
                        this.showErrorModal = true;
                        this.errorModalTitle = 'Failed';
                        this.errorModalMessage = "Server Error.Please contact admin.";
                    });
                }

            }, async updateTransferDetails(nextto) {
                if (this.transfer_doc_file === null && this.financial_statement_file === null) {

                    console.log(this.transfer_doc_file, "this.transfer_doc_file");
                    this.showErrorModal = true;
                    this.errorModalTitle = 'Try Again.';

                    if (this.hasGotFinanceFilesAlready) {
                        this.errorModalMessage = "You have an existing documents.Please select new ones to update.";
                    } else {
                        this.errorModalMessage = "Please select a file to upload.";
                    }

                    return;
                }

                if (this.isValidFinancialStatementdocument || this.isValidTransferdocument) {
                    this.showErrorModal = true;
                    this.errorModalTitle = 'Try Again';
                    this.errorModalMessage = "Please use Pdf files which are not more that 50 MB.";
                    return;
                }
                this.docsubmitted = true;
                let formData = new FormData();
                formData.append("org", this.organization?.id);
                formData.append("type", "transfer");
                formData.append("transfer_doc", this.transfer_doc_file);
                formData.append("financial_statement_file", this.financial_statement_file);
                let url = this.superAdmin ? `yie-admin/update-profile-info` : `update-profile-info`;
                axios.post(url, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(response => {
                    this.docsubmitted = false;
                    let res = response;
                    this.successModalTitle = res?.data.message;
                    // this.activeTab = nextto;
                    //      this.organization = res?.data?.thisorg;
                    if (res?.data?.somerecordschanged) {
                        this.showSuccessModal = true;
                        setTimeout(() => {
                            this.showSuccessModal = false;
                        }, 1000);
                    } else {
                        this.showSuccessModal = true;
                    }
                }).catch(error => {
                    this.docsubmitted = false;
                    this.showErrorModal = true;
                    this.errorModalTitle = 'Failed';
                    this.errorModalMessage = "Server Error.Please contact admin.";
                });
            },
            validateGeneralInfo() {

            },
            validatedditionalInfo() {

            },
            validateContactInfo() {

            },
            loadCities(province) {
                let this_ = this;
                let url = this.superAdmin ? `yie-admin/load-province-cities?province=${province}` : `load-province-cities?province=${province}`
                axios.get(url, {
                }).then(response => {

                    let loadedcities = response?.data;

                    this_.citieslist = loadedcities.map((ct, index) => {
                        return { value: ct.city_name, text: ct.city_name };
                    });

                }).catch(error => {

                });
            },
            capitalize(thestring) {
                if (thestring != null || thestring != null) {
                    return thestring.charAt(0).toUpperCase() + thestring.slice(1).toLowerCase();
                }
            },
            canSubmit() {
                this.$emit('submit');
                this.checkCreditRating();
                this.checkDepositInsurance();
                if (this.organizationType && this.organizationType.toUpperCase() === 'BANK') {
                    return this.province && this.address_line_1 && this.city
                        && this.postal_code && this.telephone
                        && this.wholesale_deposit_portfolio_id
                        && this.credit_rating && this.deposit_insurance
                }
                return this.province && this.naics_code && this.address_line_1 && this.city
                    && this.postal_code && this.telephone && this.potential_deposit
            },
            checkCreditRating(e) {
                if (!this.credit_rating && e?.keyCode !== 9) {
                    this.creditRatingError = "Credit Rating is required.";
                } else {
                    this.creditRatingError = "";
                }
            },
            checkDepositInsurance(e) {
                if (!this.deposit_insurance && e?.keyCode !== 9) {
                    this.depositInsuranceError = "Deposit Insurance is required.";
                    // setTimeout(() => { this.depositInsuranceError = ""; }, 4000);
                } else {
                    this.depositInsuranceError = "";
                }
            },
        }, watch: {
            province: async function (val) {
                this.loadCities(val);
            }
        }
    }
</script>