<template>
    <div
        style="width: 100% !important; height: 100%; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 33px; display: inline-flex">
        <div
            style="border-radius: 10px; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 30px; display: flex;width: 100%">
            <div
                style="width: 100%; height: 60px; padding-top: 10px; padding-bottom: 10px; background: #EFF2FE; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 30px; display: flex">
                <div style="justify-content: center; align-items: flex-start; gap: 520px; display: inline-flex">
                    <div
                        style="width: 100%; align-self: stretch; justify-content: flex-start; align-items: center; gap: 10px; display: flex">
                        <div style="width: 40px; height: 40px; position: relative">
                            <img src="/assets/dashboard/icons/Setting - 4.svg" />
                        </div>
                        <div
                            style="color: #252525; font-size: 28px; font-weight: 700; line-height: 32px; word-wrap: break-word; ">
                            Add products to your {{ collected_details_data?.campaign_name }} campaign </div>
                    </div>
                </div>
            </div>
            <!-- <div>{{JSON.stringify(getCampaignSelectedProducts)}}</div>
            <div>{{JSON.stringify(getCampaignSelectedProductIDS)}}</div>
            <div>{{JSON.stringify(getAllActiveProducts)}}</div> -->

            <div style="width: 100%;display: flex; justify-content: flex-end">
                <AddProductForm :products="products" @productUpdated="refreshData = $event" />
            </div>
            <div
                style="flex-direction: column; justify-content: center; align-items: flex-start; ; display: flex; width:100%">
                <AddProductList :hideView="true" :hideAllActions="true" :sync="refreshData" :allselectablee="true"
                    :selected_items="checked_data_products" :selectable="true" @selectedItems="selectedItems"
                    :products="products" @selectAllR="selectAllR" />
            </div>
        </div>
        <div style="width:100%; justify-content: flex-end; align-items: center; display: inline-flex">
            <div
                style="width:100%; align-self: stretch; justify-content: flex-end; align-items: flex-start; gap: 160px; display: inline-flex">
                <div style="width:100%; justify-content: flex-end; align-items: flex-start; gap: 50px; display: flex">
                    <!-- <b-button @click="$emit('back-step',2)" -->
                    <b-button v-if="multstepEdit" @click="previousBtn"
                        style="width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: #9CA1AA 2px solid !important;background-color: #EFF2FE !important;color:#9CA1AA  !important;">
                        Previous
                    </b-button>
                    <!-- <b-button @click="saveAsDraftSteps = 1"
                        style="width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: #5063F4 2px solid !important;background-color: #EFF2FE !important;color:#5063F4;  !important;">
                        Save as Draft
                    </b-button> -->
                    <b-button @click="nextStep"
                        style=" width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: 2px !important;background-color: #5063F4 !important;color: white !important;">
                        Next
                    </b-button>
                </div>
            </div>
        </div>
        <GeneralNoInteractionError :size="errorModalSize" @closedModal="showErrorModal = false" :title="errorModalTitle"
            :show="showErrorModal" :message="errorModalMessage" />
        <!-- <SaveAsDraftPrompt :submitted-save-draft="submittedSaveDraft" v-if="saveAsDraftSteps===1" @cancel="saveAsDraftSteps=0" @save="saveAsDraft()" />
        <SaveAsDraftSuccess v-if="saveAsDraftSteps===2" /> -->
        <TwoButtonActionMessageBox1 :size="successModalSize" @closedSuccessModal="showSaveAsDraftConfirmation = false"
            btnOneText="Cancel" btnTwoText="Save" title="Would You Like To Save This Campaign To Drafts."
            :showm="showSaveAsDraftConfirmation" @btnOneClicked="showSaveAsDraftConfirmation = false"
            @btnTwoClicked="saveAsDraft(false, false)" />

        <TwoButtonActionMessageBox :size="successModalSize" @closedSuccessModal="showSuccessModal = false"
            :btnOneText="successbtnOneText" :btnTwoText="successbtnTwoText" :title="successModalTitle"
            :showm="showSuccessModal" @btnOneClicked="backToCampaign()" @btnTwoClicked="addProductsToCampaign()" />
        <!-- <GeneralNoInteractionError :size="errorModalSize" @closedModal="showErrorModal=false" :title="errorModalTitle"
            :show="showErrorModal" :message="errorModalMessage" /> -->
        <TwoButtonActionMessageBox2 size="md" @closedSuccessModal="goingPrevious = false" btnOneText="No"
            btnTwoText="Yes" :title="confirmdraftmsg" :showm="goingPrevious" @btnOneClicked="handleProceed"
            @btnTwoClicked="handleSaveAndProceed" />
    </div>
</template>
<script>
    import GeneralNoInteractionError from "../../shared/messageboxes/GeneralNoInteractionError.vue";

    import TwoButtonActionMessageBox from "../../shared/messageboxes/TwoButtonActionMessageBox.vue";
    import TwoButtonActionMessageBox1 from "../../shared/messageboxes/TwoButtonActionMessageBox.vue";
    import TwoButtonActionMessageBox2 from "../../shared/messageboxes/TwoButtonActionMessageBox.vue";

    import AddProductList from "./AddProductList";
    import AddProductForm from "./AddProductForm";
    import Button from "../../shared/Buttons/Button";
    import SaveAsDraftPrompt from "./SaveAsDraftPrompt";
    import SaveAsDraftSuccess from "./SaveAsDraftSuccess";

    import { mapGetters } from 'vuex';
    import * as types from '../../../store/modules/campaigns/mutation-types.js';

    export default {
        mounted() {
            // console.log(this.selected_products, " in edit products selected products")
        },
        computed: {
            ...mapGetters('campaign', ['getCampaignSelectedProducts', 'getCampaignSelectedProductIDS', 'getAllActiveProducts']),
        },
        components: {
            GeneralNoInteractionError,
            TwoButtonActionMessageBox1,
            TwoButtonActionMessageBox,
            TwoButtonActionMessageBox2,
            Button,
            AddProductList,
            AddProductForm,
            SaveAsDraftPrompt,
            SaveAsDraftSuccess
        },
        created() {
        },
        props: ['products', 'collected_details_data', 'data', 'selected_products', 'doNotUseBackendProducts', 'multstepEdit'],
        data() {

            let checked_data_products_ids = [];
            let beforeeditproducts = [];


            if (this.data?.campaign_products && this.doNotUseBackendProducts === false) {
                this.data.campaign_products.forEach(e => {
                    let p = e?.fi_campaign_product_id;
                    // console.log(p, "Value of p")
                    if (p === undefined) {
                        p = e?.product_id;
                    }

                    if (p === undefined) {
                        p = e;
                    }


                    if (p) {
                        checked_data_products_ids.push(p);
                        beforeeditproducts.push(p);
                    }
                });
            }

            if (this.selected_products && this.doNotUseBackendProducts === true) {
                this.selected_products.forEach(e => {
                    let p = e?.fi_campaign_product_id;
                    if (p === undefined) {
                        p = e?.product_id;
                    }

                    if (p === undefined) {
                        p = e;
                    }

                    if (p) {
                        checked_data_products_ids.push(p);
                    }
                });
            }

            // console.log("<> checked_data_products_ids <>",checked_data_products_ids);
            // console.log("<> this.products_selected <>",this.selected_products);

            let new_checked_data_products_ids = []; // to remove observers
            if (checked_data_products_ids) {
                checked_data_products_ids.forEach(e => {
                    // console.log("<> new_checked_data_products_ids e <>",e.length > 0 ? e[0] : e);
                    new_checked_data_products_ids.push(e.length > 0 ? e[0] : e);
                });
            }
            // console.log(this.data?.campaign_products, "this.data?.campaign_products this.data?.campaign_products ");

            // console.log("<> new_checked_data_products_ids <>",new_checked_data_products_ids);
            let checked_prev_campaign_product = [];
            if (this.data?.campaign_products && this.data?.campaign_products != null) {
                checked_prev_campaign_product = this.data.campaign_products.map((item, key) => {
                    return { ...item, 'product_id': item.fi_campaign_product_id };
                });
            }


            return {
                showSaveAsDraftConfirmation: false,
                successModalSize: "md",
                successbtnOneText: "",
                successbtnTwoText: "",
                successModalTitle: "",
                showSuccessModal: false,
                errorModalSize: "md",
                goingPrevious: false,
                confirmdraftmsg: "Do you wish to save the changes as Draft?.",
                showErrorModal: false,
                errorModalMessage: "",
                errorModalTitle: "",
                submittedSaveDraft: false,
                products_selected: this.selected_products === undefined || !this.selected_products ? checked_prev_campaign_product : this.selected_products,
                data_products: this.selected_products === undefined || !this.selected_products ? checked_prev_campaign_product : this.selected_products,
                refreshData: null,
                checked_data_products: new_checked_data_products_ids,
                saveAsDraftSteps: 0,
                setCampaignId: "",
                allProductsSelected: false
            }
        },
        methods: {
            selectAllR() {
                this.allProductsSelected = !this.allProductsSelected;
            },
            previousBtn() {
                let productsseleted = this.products_selected;
                if (productsseleted?.length > 0) {

                    this.$emit('back-step', 2);

                } else {
                    this.$emit('back-step', 2);
                }


            },
            handleProceed() {
                this.goingPrevious = false;
                let products__ = this.products_selected;
                if (products__) {
                    this.$emit("back-step", 2);

                }
                this.goingPrevious = false;
            },
            handleSaveAndProceed() {
                this.goingPrevious = false;
                this.isPreviousBTN = true;
                this.saveAsDraft(true);

            },
            nextStep() {
                this.products_selected = this.getCampaignSelectedProducts;
                if (!this.products_selected || this.products_selected?.length === 0) {

                    this.showErrorModal = true;
                    this.errorModalTitle = "Campaign Product";
                    this.errorModalMessage = "Select at least 1 campaign product";

                    return;
                }
                let allSelected = this.products_selected;
                this.$emit('selectedItems', allSelected);
                this.$emit("collected_rates", this.products_selected);
                this.allProductsSelected = false;

                this.$emit("goToEditStep", 3);

            },
            selectedItems(newVal) {
                // this.$emit('selectedItems',newVal);
                // this.products_selected = newVal;

                let allSelected = [];
                if (this.allProductsSelected) {
                    newVal.map((item, key) => {

                        if (this.data_products != null) {
                            console.log(this.data_products, "this.data_products");
                            let thisproduct = this.data_products.find((value, key) => {
                                console.log(value);
                                console.log(item, "item");
                                return value?.product_id === item;
                            });
                            console.log(thisproduct?.product_id, "thisproduct");
                            if (thisproduct) {
                                allSelected.push({ 'product_id': item, 'rate': thisproduct?.rate, 'maximum': thisproduct?.maximum, 'minimum': thisproduct?.minimum });
                            } else {
                                allSelected.push({ 'product_id': item });
                            }
                        } else {
                            allSelected.push({ 'product_id': item });
                        }

                    })
                } else {
                    if (this.products_selected) {

                        newVal.forEach(t => {
                            let d = this.products_selected.filter(e => {
                                let p = e?.fi_campaign_product_id;
                                if (p === undefined) {
                                    p = e?.product_id;
                                }

                                if (p === undefined) {
                                    p = e;
                                }

                                return p == t;
                            });

                            if (d.length > 0) {
                                allSelected.push(d[0]);
                            } else {
                                allSelected.push({
                                    product_id: t
                                });
                            }
                        });



                    } else {

                        allSelected.push(newVal);


                    }

                }
                this.products_selected = allSelected;

            },
            saveAsDraft(goingprevius = false) {
                if (this.submittedSaveDraft) {
                    return;
                }

                const urlParams = new URLSearchParams(window.location.search);
                let campaign_id = urlParams.get('campaign_id');

                this.submittedSaveDraft = true;
                let products_ = [];
                if (this.products_selected) {
                    this.products_selected.forEach((e) => {
                        let p = e?.fi_campaign_product_id;
                        if (p === undefined) {
                            p = e;
                        }

                        if (p) {
                            products_.push({
                                'product_id': p
                            });
                        }
                    });
                }
                console.log(products_, "products_");
                let productstosave = [];
                //create array

                this.products_selected.forEach((prod) => {
                    productstosave.push(prod);
                })
                //create array

                axios.post(campaign_id ? '/campaigns/fi/update-campaign' : '/campaigns/fi/create-campaign', {
                    'campaignName': this.collected_details_data.campaign_name,
                    'expiryDate': this.collected_details_data.expiry_date,
                    'startDate': this.collected_details_data.start_date,
                    'currency': this.collected_details_data.currency,
                    'subscriptionAmount': this.collected_details_data.subscription_amount,
                    'status': 'DRAFT',
                    'products': productstosave,
                    'campaign': campaign_id
                })
                    .then(response => {
                        this.submittedSaveDraft = false;
                        if (response?.data?.success) {
                            this.saveAsDraftSteps = 2;
                            if (goingprevius) {
                                let currentUrl = window.location.href;


                                let paramName = 'campaign_id';
                                let paramValue = response?.data?.data?.id;


                                if (currentUrl.indexOf('?') !== -1) {

                                    currentUrl += `&${paramName}=${paramValue}`;
                                } else {

                                    currentUrl += `?${paramName}=${paramValue}`;
                                }
                                this.selected_products = this.products_selected;
                                window.location.href = currentUrl;
                                // Update the URL in the browser
                                window.history.replaceState({}, '', currentUrl);
                                this.$emit("back-step", 2);
                            }
                            // this.$swal({
                            //     title: 'Save as draft.',
                            //     text: response?.data?.message,
                            //     confirmButtonText: 'Close'
                            // }).then(res=>{
                            // if(response?.data?.data?.id != campaign_id) {
                            //     window.location.href = '/campaigns?campaign_id=' + response?.data?.data?.id
                            // }
                            // });
                        } else {
                            throw new Error(response?.data?.message);
                        }
                    }).catch(error => {
                        this.$swal({
                            title: 'Save as draft.',
                            text: error,
                            confirmButtonText: 'Close'
                        });
                        this.submittedSaveDraft = false;
                    });
            },
        },
        watch: {
            products_selected() {
                console.log("hello");
            }
        }
    }
</script>