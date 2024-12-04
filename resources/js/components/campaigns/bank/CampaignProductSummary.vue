<template>
    <div>
        <div
            style="width: 99%; background: #EFF2FE; padding-left: 2px; justify-content: flex-start; align-items: center; display: inline-flex">
            <div style="justify-content: space-between; display: inline-flex; width: 99%;">
                <div class="page-title">
                    <div class="title-icon">
                        <img src="/assets/dashboard/icons/Setting__3.svg" />
                    </div>
                    <!-- <div class="text-div">{{ prod }}</div> -->
                    <div class="text-div">{{ hasNoticePeriod ? prod?.lockout_period + ' Day ' +
                        prod.description : prod.description }} GIC</div>
                </div>
                <div @click="toggleView(1)"
                    style="justify-content: flex-start; align-items: center; gap: 9px; display: flex;cursor: pointer">
                    <div
                        style="text-align: center; color: #252525; font-size: 14px; font-weight: 500; line-height: 18px; word-wrap: break-word">
                        View {{ viewMore1 ? 'Less' : 'More' }}</div>
                    <img v-if="viewMore1" src="/assets/dashboard/icons/Polygon.svg" />
                    <img v-else src="/assets/dashboard/icons/Polygon 2.svg" />
                </div>
            </div>
        </div>
        <div v-if="viewMore1"
            style=" width: 100%;flex-direction: column;justify-content: flex-start; align-items: flex-start;gap: 30px; display: inline-flex; ">
            <!-- <accordion :is_open="is_open" :title="" width="100"
                title_image="/assets/dashboard/icons/Setting__3.svg" /> -->


            <div
                style="flex-direction: column;justify-content: flex-start;align-items: flex-start; gap: 20px; display: flex;">
                <div
                    style="width: 100%; min-width:250px; justify-content: flex-start; gap: 20px; display: inline-flex;  margin-top: 20px;">
                    <div
                        style="width: 25%; max-height:100%; min-width:250px;  max-width:250px; justify-content: space-between; display: flex;flex-direction: column;">
                        <div
                            style="height: 100%;padding: 60px 40px;background: white;box-shadow: 0 4px 4px #d9d9d9;flex-direction: column;justify-content: center;align-items: center;gap: 12px; display: inline-flex;">
                            <div
                                style=" width: 100%;color: #5063f4; font-size: 68px;display: inline-flex;justify-content: center;font-weight: 700; ">
                                {{ prod?.rate }}%
                            </div>
                            <div style="width: 100%; height: 0; border: 0.25px #9ca1aa solid;margin-top:-10px;"></div>
                            <div style=" flex-direction: column; justify-content: flex-start; align-items: flex-start;
                                    display: flex; ">
                                <div style=" width: 100%; text-align: center; color: #252525; font-size: 18px; font-weight:
                                700;word-wrap: break-word; ">
                                    Term Length
                                </div>
                                <div style=" width: 100%;text-align: center;color: #5063f4;font-size: 28px; font-weight:
                                700; word-wrap: break-word; text-transform: capitalize; ">
                                    {{ prod?.term_length + ' ' + capitalize(prod?.term_length_type) }}
                                </div>
                            </div>
                        </div>
                    </div>


                    <div style=" width: 100%;flex-direction: column;justify-content: flex-start; align-items:
                                flex-start;display: inline-flex; ">
                        <div style=" min-height: 240px;flex-direction: column;justify-content: flex-start; align-items:
                                flex-start;gap: 30px; display: flex; ">
                            <div style=" width: 100%; padding: 0.3rem; ">
                                <span
                                    style=" color: #252525; font-size: 16px; font-weight: 700;line-height: 20px;word-wrap: break-word;">About
                                    This GIC<br /></span>
                                <span
                                    style="color: #5063f4; font-size: 16px; font-weight: 700;line-height: 20px;word-wrap: break-word;"><br /></span><span
                                    style="color: #252525; font-size: 16px;  font-weight: 500;word-wrap: break-word;">
                                    {{ prod.definition }}
                                    <!-- {{aboutGIC(prod?.description) }}. -->
                                </span>
                            </div>

                            <ProductDetails :earning_text="prod.earning_text" :earning_rate="prod.earning_rate"
                                :flexibility_rate="prod.flexibility_rate" :flexibility_text="prod.flexibility_text" />
                            <div
                                style="flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 10px; display: flex;">
                                <div
                                    style="justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex;">
                                    <div
                                        style="width: 80px; color: #252525; font-size: 16px; font-weight: 500; word-wrap: break-word;">
                                        Currency</div>
                                    <div
                                        style="width: 90px; color: #252525; font-size: 16px; font-weight: 500; word-wrap: break-word;">
                                        Minimum</div>
                                    <div
                                        style="width: 90px; color: #252525; font-size: 16px; font-weight: 500; word-wrap: break-word;">
                                        Maximum</div>
                                    <div
                                        style="width: 130px; color: #252525; font-size: 16px; font-weight: 500; word-wrap: break-word;">
                                        Locked in Period</div>
                                    <div
                                        style="width: 150px; color: #252525; font-size: 16px; font-weight: 500; word-wrap: break-word;">
                                        Interest Paid</div>
                                    <div
                                        style="width: 180px; color: #252525; font-size: 16px; font-weight: 500; word-wrap: break-word;">
                                        Compound Frequency</div>
                                </div>
                                <div
                                    style="justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex;">
                                    <div
                                        style="width: 80px; color: #5063F4; font-size: 17px; font-weight: 800; word-wrap: break-word;">
                                        {{ prod?.currency }}</div>
                                    <div
                                        style="width: 90px; color: #5063F4; font-size: 17px; font-weight: 800; word-wrap: break-word;">
                                        {{ formatNumberAbbreviated(prod?.minimum) }}</div>
                                    <div
                                        style="width: 90px; color: #5063F4; font-size: 17px; font-weight: 800; word-wrap: break-word;">
                                        {{ formatNumberAbbreviated(prod?.maximum) }}</div>
                                    <div
                                        style="width: 130px; color: #5063F4; font-size: 17px; font-weight: 800; word-wrap: break-word;">
                                        {{ prod?.lockout_period ? prod?.lockout_period + ' ' +
                                        capitalize(prod?.term_length_type)
                                        :
                                        '-'
                                        }}</div>
                                    <div
                                        style="width: 150px; color: #5063F4; font-size: 17px; font-weight: 800; word-wrap: break-word;">
                                        {{ capitalize(prod?.interest_paid) }}</div>
                                    <div
                                        style="width: 180px; color: #5063F4; font-size: 17px; font-weight: 800; word-wrap: break-word;">
                                        {{ capitalize(prod?.compound_frequency) }}</div>
                                </div>
                            </div>



                        </div>

                    </div>
                </div>
            </div>
            <div style="justify-content: flex-end;align-items: center;display: inline-flex;width: 100%; gap: 20px ">
                <div
                    style="justify-content: flex-end;align-items: flex-start;gap: 20px;display: inline-flex;margin-right: 1rem;">
                    <b-button @click="toMyCampaigns" class="btnbtn1">
                        Previous
                    </b-button>
                    <b-button @click="popUpToEdit()" class="btnbtn1" style="border: 2px #5063F4 solid !important;">
                        Edit
                    </b-button>
                    <b-button class="btnbtn1" style=" background-color: #5063f4 !important; color: white !important;"
                        @click="setFeatureUnFeature()">
                        <!-- <FeatureUnfeatureProduct :productName="prod?.default_product_name"
                            :actionObjectDetails="['Camp Name', capitalize('1', prod?.custom_name), prod?.product_type?.description, prod?.custom_product_name, prod?.term_length + ' ' + pprod?.term_length_type]"
                            :variant-color="prod?.isFeatured === 1 ? 'rgba(68, 224, 170, 0.60);' : ''"
                            :text-color="prod?.isFeatured === 1 ? '#252525' : ''" campaign_id=1
                            :action="prod?.isFeatured === 1 ? 'Un Feature' : 'Feature'" :action-id="prod?.id" /> -->
                        {{ (prod?.isFeatured == 1) ? 'Unfeature' : 'Feature' }}
                    </b-button>
                </div>
            </div>
        </div>

        <div v-if="userCan(JSON.parse(this.log),'bank/my-campaigns/products-insights')"
            style="width: 99%; background: #EFF2FE; padding-left: 2px; justify-content: flex-start; align-items: center; display: inline-flex;margin-top: 30px;">
            <div style="justify-content: space-between; display: inline-flex; width: 99%;">
                <div class="page-title">
                    <div class="title-icon">
                        <img src="/assets/dashboard/icons/Setting - 6.svg" />
                    </div>
                    <div class="text-div">Products Insights</div>
                </div>
                <div @click="toggleView(2)"
                    style="justify-content: flex-start; align-items: center; gap: 9px; display: flex;cursor: pointer">
                    <div
                        style="text-align: center; color: #252525; font-size: 14px; font-weight: 500; line-height: 18px; word-wrap: break-word">
                        View {{ viewMore2 ? 'Less' : 'More' }}</div>
                    <img v-if="viewMore2" src="/assets/dashboard/icons/Polygon.svg" />
                    <img v-else src="/assets/dashboard/icons/Polygon 2.svg" />
                </div>
            </div>
        </div>

        <div v-if="viewMore2"
            style=" width: 100%; margin-top:30px; flex-direction: column;justify-content: flex-start; align-items: flex-start;gap: 30px; display: inline-flex; ">
            <!-- <accordion :is_open="is_open" :title="" width="100" title_image="/assets/dashboard/icons/Setting__3.svg" /> -->
            <div v-if="hasProductInsights"
                style="width: 100%; height: 100%; justify-content: flex-start; align-items: flex-start; gap: 9px; display: inline-flex">
                <div style="width: 70%; height: 375px; position: relative;margin-right:.5rem ;">
                    <div
                        style="width: 100%; height: 433px; left: 0px; top: 0px; position: absolute; background: white; box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.10); border-radius: 10px">
                    </div>
                    <div
                        style="width: 478px; padding-left: 54px; padding-right: 54px; left: 253px; top: 87px; position: absolute; justify-content: flex-start; align-items: flex-end; gap: 53px; display: inline-flex">
                        <div
                            style="width: 201px; height: 0px; transform: rotate(90deg); transform-origin: 0 0; border: 1px #D9D9D9 dotted">
                        </div>
                        <div
                            style="width: 201px; height: 0px; transform: rotate(90deg); transform-origin: 0 0; border: 1px #D9D9D9 dotted">
                        </div>
                        <div
                            style="width: 201px; height: 0px; transform: rotate(90deg); transform-origin: 0 0; border: 1px #D9D9D9 dotted">
                        </div>
                        <div
                            style="width: 201px; height: 0px; transform: rotate(90deg); transform-origin: 0 0; border: 1px #D9D9D9 dotted">
                        </div>
                        <div
                            style="width: 201px; height: 0px; transform: rotate(90deg); transform-origin: 0 0; border: 1px #D9D9D9 dotted">
                        </div>
                        <div
                            style="width: 201px; height: 0px; transform: rotate(90deg); transform-origin: 0 0; border: 1px #D9D9D9 dotted">
                        </div>
                        <div
                            style="width: 201px; height: 0px; transform: rotate(90deg); transform-origin: 0 0; border: 1px #D9D9D9 dotted">
                        </div>
                        <div
                            style="width: 201px; height: 0px; transform: rotate(90deg); transform-origin: 0 0; border: 1px #D9D9D9 dotted">
                        </div>
                        <div
                            style="width: 201px; height: 0px; transform: rotate(90deg); transform-origin: 0 0; border: 1px #D9D9D9 dotted">
                        </div>
                    </div>
                    <div style="margin-top:30px;">

                    </div>
                    <UniqueClicksProduct v-if="marketTypesClicks.length >= 1" height="350" seriesName="Clicks"
                        :labels="marketTypesLabels" :values="marketTypesClicks" />
                    <UniqueClicksProduct v-else height="350" seriesName="Clicks"
                        :labels="['Finance', 'Municipality', 'Real Estate', 'Small Business', 'Non-Profits']"
                        :values="[0, 0, 0, 0, 0]" />
                    <div
                        style="padding: 10px; margin-left: 20px; top: 7px; position: absolute; justify-content: flex-start; align-items: center; gap: 168px; flex-direction:column; ">
                        <div
                            style="color: #5063F4; font-size: 16px; padding-left: 20px; font-family: Montserrat; font-weight: 700; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                            Market Sector Reach</div>

                    </div>
                </div>
                <div
                    style="width: 30%; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex">
                    <div style="width: 100%; height: 230px; position: relative;">
                        <div
                            style="width: 100%; height: 230px; left: 0px; top: 0px; position: absolute; background: white; box-shadow: 0px 2px 6px rgba(13, 10, 44, 0.08); border-radius: 10px">
                        </div>
                        <div style="width: 121.22px; height:97px;  left: 163.68px; top: 76.30px; position: absolute;">
                            <div style="width: 114.88px; height: 92.70px;">
                                <img src="/assets/dashboard/icons/linechart.svg" />
                            </div>
                        </div>
                        <div
                            style="padding-left: 30px; padding-right: 30px; left: 10.24px; top: 65px; position: absolute; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 30px; display: inline-flex">
                            <div
                                style="flex-direction: column; justify-content: flex-start; align-items: flex-start; display: flex">
                                <div
                                    style="color: #1E1B39; font-size: 40px; font-family: Montserrat; font-weight: 800; word-wrap: break-word">
                                    {{ productReach }}</div>
                                <div
                                    style="color: #9CA1AA; font-size: 20px; font-family: Montserrat; font-weight: 500; word-wrap: break-word">
                                    Depositors</div>
                            </div>
                            <div style="width: 58px; height: 16px; position: relative; margin-bottom: 50px;">
                                <!-- <div
                                    style="left: 0px; top: 0px; position: absolute; color: #2A9928; font-size: 14px; font-family: Inter; font-weight: 500; line-height: 16px; word-wrap: break-word">
                                    +18.34%</div> -->

                            </div>
                        </div>
                        <div
                            style="padding-left: 22px; padding-right: 22px; left: 7.46px; top: 15px; position: absolute; justify-content: flex-start; align-items: center; gap: 6px; display: inline-flex">
                            <div style="width: 40px; height: 40px; position: relative">



                                <img src="/assets/dashboard/icons/Setting - user.svg" />

                            </div>
                            <div
                                style="color: #5063F4; font-size: 20px; font-family: Montserrat; font-weight: 800; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                                Product Reach</div>
                        </div>
                    </div>
                    <div style="width: 100%; height: 190px; position: relative">
                        <div
                            style="width: 100%; height: 190px; left: 0px; top: 0.38px; position: absolute; background: white !important; box-shadow: 0px 2px 6px rgba(13, 10, 44, 0.08); border-radius: 10px">
                        </div>
                        <div style="width: 121.22px; height:97px;  left: 163.68px; top: 76.30px; position: absolute;">
                            <div style="width: 114.88px; height: 92.70px;">
                                <img src="/assets/dashboard/icons/linechart.svg" />
                            </div>
                        </div>
                        <div
                            style="padding-left: 30px; padding-right: 30px; padding-bottom: 30px; left: 5.55px; top: 52.25px; position: absolute; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 20px; display: inline-flex">
                            <div
                                style="flex-direction: column; justify-content: flex-start; align-items: flex-start; display: flex">
                                <div
                                    style="color: #1E1B39; font-size: 40px; font-family: Montserrat; font-weight: 800; word-wrap: break-word">
                                    {{ uniqueProductViews }}</div>
                                <div
                                    style="color: #9CA1AA; font-size: 20px; font-family: Montserrat; font-weight: 500; word-wrap: break-word">
                                    New Clicks</div>
                            </div>
                            <!-- <div
                                style="color: #2A9928; margin-bottom:10px; font-size: 14px; font-family: Inter; font-weight: 500; line-height: 16px; word-wrap: break-word">
                                +1.04%</div> -->
                        </div>
                        <div
                            style="padding-left: 22px; padding-right: 22px; left: 8.53px; top: 0.44px; position: absolute; justify-content: flex-start; align-items: center; gap: 6px; display: inline-flex">
                            <div style="width: 40px; height: 40px; position: relative">
                                <img src="/assets/dashboard/icons/Setting - 3.svg" />
                            </div>
                            <div
                                style="color: #5063F4; font-size: 20px; font-family: Montserrat; font-weight: 800; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
                                Unique User Clicks</div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else
                style=" width: 100%; margin-top:30px; flex-direction: column;justify-content: flex-start; align-items: flex-start;gap: 30px; display: inline-flex; ">
                <NoProducstInsights maxheight="150" image="marketinsightsnodat.svg" title="No Product Insight Yet"
                    desc="Your insights for this product will populate once depositors start engaging with it">
                </NoProducstInsights>
            </div>
        </div>

        <FeatureProductPrompt :size="successModalSize" @closedSuccessModal="featureUnfeature = 0" btnOneText="No"
            :btnTwoText="(featureUnfeature == 1) ? 'Yes' : 'Yes'"
            :title="(prod?.isFeatured === 1) ? `Do you want to Unfeature ${prod?.custom_product_name}? ` : `Do you want to Feature ${prod?.custom_product_name}? `"
            :showm="featureUnfeature > 0" @btnOneClicked="featureUnfeature = 0" @btnTwoClicked="featureIt()"
            message="" />
        <OKButtonActionMessageBoxVue :size="successModalSize" @closedSuccessModal="showSuccessModal = false"
            :title="successModalTitle" :showm="showSuccessModal" @okClicked="showSuccessModal = false" />

        <DeactivateActivatePropmt :size="successModalSize" @closedSuccessModal="deactivateActivate = 0"
            btnOneText="Cancel" btnTwoText="Delete" title='Delete Campaign Product' :showm="deactivateActivate > 0"
            @btnOneClicked="deactivateActivate = 0" @btnTwoClicked="deleteIt()"
            message="This operation cannot be undone." />
        <Modal :visible.sync="show" modalsize="md" :show="show" @closemodal="closeAddProductModal()">
            <b-row
                style="width:100%;padding: 0px !important; margin-top: 15px;margin-left:0px !important; margin-right:0px !important;">
                <b-col md="12" style="width:100%;padding: 0px !important;">
                    <MessageHeaderIconized title="Edit product" width="100"
                        title_image="/assets/dashboard/icons/Promo-pencil.svg" />
                </b-col>
            </b-row>
            <b-row
                style="width:100%;padding: 0px !important;margin-top: 15px;margin-left:0px !important; margin-right:0px !important;">
                <b-col md="4" style="padding: 4px !important;">
                    <FormLabelRequired style="padding: 4px;" labelText="Interest Rate" required="true"
                        :showHelperText="true" helperText="" helperId="productTypeHId" />
                    <CustomInput c-style="font-weight: 400;width:100%;" p-style="width:100%" id="interest_rate"
                        name="Rate*" :has-validation="true" @inputChanged="productInterestRate = $event"
                        input-type="number" :default-value="productInterestRate" />

                </b-col>
                <b-col md="4" style="padding: 4px; !important;">
                    <FormLabelRequired style="padding: 4px;" labelText="Minimum" required="true" :showHelperText=true
                        helperText="" helperId="termLengthHId" />
                    <CustomInput c-style="font-weight: 400;width:100%;" p-style="width:100%" id="minimum"
                        name="Minimum *" :has-validation="true" @inputChanged="productMin = $event" input-type="number"
                        validation-error="Lock out Period" :default-value="productMin" />
                </b-col>
                <b-col md="4" style="padding: 4px !important;">
                    <FormLabelRequired style="padding: 4px;" labelText="Maximum" required="false" showHelperText="true"
                        helperText="" helperId="lockoutHId" />
                    <CustomInput c-style="font-weight: 400;width:100%;" p-style="width:100%" id="maximum"
                        name="Maximum *" :has-validation="true" @inputChanged="productMax = $event" input-type="number"
                        validation-error="Lock out Period" :default-value="productMax" />

                </b-col>
            </b-row>
            <b-row>
                <b-col md="12" style="padding: 4px !important;">
                    <div
                        style="color: #252525; margin-left: 20px; font-size: 14px;margin-top: 5px; font-family: Montserrat; font-weight: 700; word-wrap: break-word">
                        Product Description
                    </div>
                    <div
                        style="width: 100%;padding-left: 20px; padding-right:10px; margin-top: 5px; color: #252525; font-size: 14px; font-family: Montserrat; font-weight: 300; word-wrap: break-word">
                        {{ prod?.definition }}</div>
                    <div
                        style="width: 100%;margin-left: 20px; color: #252525; margin-top: 8px; font-size: 14px; font-family: Montserrat; font-weight: 300; word-wrap: break-word">
                        <ProductDetailsModal :earning_text="prod.earning_text" :earning_rate="prod.earning_rate"
                            :flexibility_rate="prod.flexibility_rate" :flexibility_text="prod.flexibility_text" />
                    </div>





                </b-col>
            </b-row>
            <b-row
                style="width:100%; padding: 0px !important;margin-top: 15px;margin-left:0px !important; margin-right:0px !important;">
                <b-col md="5" sm="12" style="padding: 4px !important;">
                    &nbsp;<br>
                    <span style="color:#5063F4;"> *(Mandatory Fields) </span>
                </b-col>
                <b-col md="7" sm="12" style="padding: 5px; padding-right: 4px; !important;">
                    <div style="display: flex; align-items: center; justify-content: end;">
                        <b-button @click="submitAndUpload()"
                            style=" width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: 2px !important;background-color: #5063F4 !important;color: white !important;">
                            Submit
                        </b-button>
                    </div>
                </b-col>
            </b-row>
        </Modal>
        <GeneralNoInteractionError size="md" @cancelled="() => showgeneralmessagebox = false" :title="box_title"
            :show="showgeneralmessagebox" :message="box_message" />

    </div>
</template>
<style>
    .btnbtn1 {

        min-width: 148px;
        height: 40px !important;
        border: 0.5px #d9d9d9 solid;
        padding: 10px 30px 10px 30px !important;
        border-radius: 20px !important;
        color: #5063f4 !important;
        background-color: #f8faff !important;
        display: flex;
        justify-content: center;
        /* Use flexbox for layout */
        align-items: center;
    }

    .btnbtn {

        min-width: 200px;
        height: 40px !important;
        border: 0.5px #d9d9d9 solid;
        padding: 10px 30px 10px 30px !important;
        border-radius: 20px !important;
        color: #f8faff !important;
        background-color: #5063f4 !important;
        display: flex;
        justify-content: center;
        /* Use flexbox for layout */
        align-items: center;
    }

    .form-input-text {
        font-size: 16px !important;
    }

    .custom-tab-nav-class>ul li .nav-link {
        font-size: 14px;
    }

    .custom-tab-nav-class4>ul {
        border-bottom: 1px solid #ccc;
        width: 93%;
    }

    #investment_amount123 {
        margin: 0 !important;
        height: 44px !important;
    }
</style>
<script>
    import FormLabelRequired from "../../shared/formLabels/FormLabelRequired.vue";
    import MessageHeaderIconized from "../../shared/messageboxes/MessageHeaderIconized.vue";
    import Modal from "../../shared/Modal";
    import CustomSelect from "../../shared/CustomSelect";
    import CustomInput from "../../shared/CustomInput";
    import OKButtonActionMessageBoxVue from "../../shared/messageboxes/OKButtonActionMessageBox.vue";
    import FeatureProductPrompt from "../../shared/messageboxes/ConfirmDeletionPrompt.vue";

    import Accordion from "../../shared/Accordion";
    import BarGraph from "../../shared/Graphs/bar/BarGraph.vue";
    import Tooltip from "../../shared/Tooltip";
    import Button from "../../shared/Buttons/Button";
    import FeatureUnfeatureProduct from "./FeatureUnfeatureProduct";
    import VueApexCharts from 'vue-apexcharts';
    import GeneralNoInteractionError from "../../shared/messageboxes/GeneralNoInteractionError.vue";
    import ProductDetails from "../../shared/ProductDetails.vue";
    import ProductDetailsModal from "../../shared/ProductDetailsModal.vue";
    import NoProducstInsights from "../../shared/Graphs/NoProducstInsights.vue";
    import { userCan } from "../../../utils/GlobalUtils";
    export default {
        components: {

            GeneralNoInteractionError,
            FormLabelRequired,
            MessageHeaderIconized,
            CustomSelect,
            CustomInput,
            Modal,
            UniqueClicksProduct: BarGraph,
            apexchart: VueApexCharts,
            OKButtonActionMessageBoxVue: OKButtonActionMessageBoxVue,
            FeatureProductPrompt: FeatureProductPrompt,
            DeactivateActivatePropmt: FeatureProductPrompt,
            Button,
            Accordion,
            Tooltip,
            ProductDetails,
            ProductDetailsModal,
            NoProducstInsights
        },
        mounted() {
            this.getCampaignInsighst()
            //console.log(this.log);

        },
        props: ['product', 'log'], /// key userLoggedIn gave undifined
        data() {

            let produ = JSON.parse(this.product);
            //console.log(produ?.flexibility_rate, "this.produ?.earning_rate");
            return {
                box_title: '',
                box_message: '',
                showgeneralmessagebox: false,
                show: false,
                productMin: this.addCommans(produ?.minimum),
                productMax: this.addCommans(produ?.maximum),
                productInterestRate: this.addCommans(produ?.rate),
                is_open: true,
                submitted: false,
                featureUnfeature: 0,
                deactivateActivate: 0,
                showSuccessModal: false,
                successModalTitle: '',
                successModalSize: "md",
                products: ['Finance', 'Municipality', 'Real Estate', 'Small Business', 'Non-Profits'],
                productsClicks: ['800', '700', '600', '500', '400'],
                prod: produ,
                earnings: Array.from({ length: produ?.earning_rate }, (_, index) => index),
                flexibility: Array.from({ length: produ?.flexibility_rate }, (_, index) => index),
                uniqueProductViews: 0,
                productReach: 0,
                marketTypesLabels: [],
                marketTypesClicks: [],
                hasProductInsights: false,
                viewMore1: true,
                viewMore2: false

            }
        },
        computed: {
            hasNoticePeriod() {
                // Using the includes method
                return (this.prod.description.includes("Notice deposit") || this.prod.description.includes("Cashable"));
            }

        },
        methods: {
            userCan(user, permission) {

                return userCan(user, permission);
            },
            toggleView(index) {
                if (index === 1)
                    this.viewMore1 = !this.viewMore1;
                else
                    this.viewMore2 = !this.viewMore2
            },
            popUpToEdit() {
                this.show = true;
            },
            removeCommas(number) {
                return number.replace(/,/g, '');
            },
            getCampaignInsighst() {

                //let url = 'https://mocki.io/v1/d6c93d20-db7b-448c-bc77-4c22238cca6e'
                let url = `/campaigns/fi/analytics/get-campaign-product-insights?product=${this.prod?.id}`


                let this_ = this
                axios.get(url)
                    .then(response => {
                        const data = response.data;
                        // console.log(data)
                        this_.uniqueProductViews = data.product.unique_product_views_count
                        this_.productReach = data.product.campaign_product_views_count

                        this.hasProductInsights = true
                        if (data.marketSectorClicks.length >= 1) {
                        }
                        if (data.marketSectorClicks) {
                            data.marketSectorClicks.forEach(item => {
                                this_.marketTypesLabels.push(item.name);
                                this_.marketTypesClicks.push(item.unique_count);
                            });
                        }

                    })
                    .catch(error => {
                        console.error(error);
                    });



            },

            submitAndUpload() {


                if (parseInt(this.removeCommas(this.productInterestRate)) > 100) {
                    this.box_title = 'Error';
                    this.box_message = 'Rate cannot be greater than 100';
                    this.showgeneralmessagebox = true;
                    return;
                }

                axios.post("/campaigns/fi/update-campaign-product-info", {
                    rate: this.removeCommas(this.productInterestRate),
                    minimum: this.removeCommas(this.productMin),
                    maximum: this.removeCommas(this.productMax),
                    product: this.prod.id
                }).then(response => {

                    if (response?.data?.success) {
                        this.show = false;
                        this.successModalTitle = 'Updated successfully';
                        this.showSuccessModal = true;
                        this.show = false;

                    } else {
                        this.box_title = 'Error';
                        this.box_message = response?.data?.message;
                        this.showgeneralmessagebox = true;
                    }

                    this.submitted = false;
                }).catch(error => {
                    console.log(error, "errorerror");
                });
            },
            setFeatureUnFeature() {
                if (this.prod.isFeatured === 1) {
                    this.featureUnfeature = 2;
                } else {
                    this.featureUnfeature = 1;
                }
            },
            setActivateDeactivate() {
                if (this.prod.status === "ACTIVE") {
                    this.deactivateActivate = 2;
                } else {
                    this.deactivateActivate = 1;
                }
            },
            featureIt() {
                this.featureUnfeature = false;
                this.submitted = true;
                axios.post("/campaigns/fi/feature-unfeature-campaign", {
                    product: this.prod.id,
                    action: (this.prod.isFeatured === 1) ? 'UN FEATURE' : 'FEATURE'
                }).then(response => {
                    if (response?.data?.success) {
                        this.deactivateActivate = 0;
                        this.successModalTitle = (this.prod.isFeatured === 1) ? 'Unfeatured Successfully' : 'Featured Successfully'
                        this.response_error = null;
                        this.showSuccessModal = true;
                        this.prod.isFeatured = (this.prod.isFeatured === 1) ? 0 : 1;
                        console.log(this.prod);

                    } else {
                        new Error(response?.data?.message);
                    }

                    this.submitted = false;
                }).catch(error => {
                    this.submitted = false;
                });
            },
            deleteIt() {
                axios.post("/campaigns/fi/my-campaigns/remove-product", {
                    campaign_fi_campaign_product_id: this.prod.id
                }).then(response => {
                    if (response?.data?.success) {

                        this.deactivateActivate = 0;
                        this.successModalTitle = (this.prod.status === "INACTIVE") ? 'Activated successfully' : 'Deactivated Successfully'
                        this.response_error = null;
                        this.showSuccessModal = true;
                        this.prod.status = (this.prod.status === "ACTIVE") ? "INACTIVE" : "ACTIVE";
                        console.log(this.prod);
                    } else {
                        new Error(response?.data?.message);
                    }
                    this.submitted = false;
                }).catch(error => {
                    this.submitted = false;
                    this.response_error = error;
                    this.deleteStep = 0;
                });

            },
            toMyCampaigns() {
                window.location.href = '/campaigns';
            },
            back() {
                window.location.href = '/campaigns/products';
            },
            viewLink(link) {
                window.open(link, '_blank')
            },
            capitalize(thestring) {
                if (thestring != null || thestring != null) {
                    return thestring
                        .toLowerCase()
                        .split(' ')
                        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
                        .join(' ');
                }

            },
            addCommans(newvalue) {
                return newvalue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            },
            formatNumberAbbreviated(number) {
                const SI_SYMBOL = ["", "K", "M", "G", "T", "P", "E"];

                const tier = (Math.log10(number) / 3) | 0;

                if (tier === 0) return number;

                const suffix = SI_SYMBOL[tier];
                const scale = Math.pow(10, tier * 3);

                const scaledNumber = number / scale;

                return scaledNumber.toFixed(0) + suffix;
            },
            aboutGIC(productType) {
                productType = productType.toLowerCase();
                return this.aboutArray()[productType]
            },
            aboutArray() {
                return {
                    'non-redeemable': ' is tailor-made for those who prioritize liquidity and flexibility in their investments. This financial product grants you the freedom to access your funds whenever you need them, without the worry of facing penalties. It\'s an ideal solution that strikes a harmonious balance between financial growth and accessibility. With the Non-Redeemable GIC, you\'re empowered with a versatile and potent financial tool, giving you the confidence to make your money work for you while still maintaining control over your assets.',
                    'short term': ' is designed for those seeking quick returns, the Short Term Deposit offers a swift investment opportunity. With a shorter maturity period, this product is perfect for those who prefer seeing their funds grow in a shorter timeframe. It\'s a dynamic choice that aligns with your need for a timely and impactful financial solution.',
                    'cashable': ' puts you in control of your investments. Offering the flexibility to access your funds without penalties, it provides a secure avenue for growth while ensuring your financial freedom remains intact. This product combines the best of both worlds—steady financial growth and the liberty to adapt to your evolving needs.',
                    'notice deposit': ' is built for strategic financial planning, the Notice Deposit empowers you with the ability to save and grow your money on your terms. With this product, you have the advantage of providing a notice period before accessing your funds. This encourages a disciplined approach to financial management, letting you reap the benefits of growth while maintaining the flexibility to fulfill your financial goals.',
                    'high interest savings': ' is your gateway to a higher rate of return on your savings. Tailored for those who value both security and growth, it offers a competitive interest rate while keeping your funds readily accessible. This account empowers you to maximize your savings potential without sacrificing your ability to manage your finances efficiently.',
                };
            }
        }
    }
</script>