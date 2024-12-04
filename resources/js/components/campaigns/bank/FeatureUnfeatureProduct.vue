<template>
    <div>
        <TableActionButton v-if="!bt" @click="deleteStep = 1" :variantColor="variantColor" :textColor="textColor">
            <span v-if="action && action != 'Feature'">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                    <path
                        d="M6.74845 6L8.84339 3.90505C8.94282 3.8058 8.99875 3.67112 8.99887 3.53064C8.99899 3.39015 8.94331 3.25537 8.84406 3.15594C8.74481 3.05652 8.61012 3.00059 8.46964 3.00047C8.32915 3.00034 8.19437 3.05603 8.09495 3.15528L6 5.25023L3.90505 3.15528C3.80563 3.05586 3.67078 3 3.53017 3C3.38956 3 3.25471 3.05586 3.15528 3.15528C3.05586 3.25471 3 3.38956 3 3.53017C3 3.67078 3.05586 3.80563 3.15528 3.90505L5.25023 6L3.15528 8.09495C3.05586 8.19437 3 8.32922 3 8.46983C3 8.61044 3.05586 8.74529 3.15528 8.84472C3.25471 8.94414 3.38956 9 3.53017 9C3.67078 9 3.80563 8.94414 3.90505 8.84472L6 6.74977L8.09495 8.84472C8.19437 8.94414 8.32922 9 8.46983 9C8.61044 9 8.74529 8.94414 8.84472 8.84472C8.94414 8.74529 9 8.61044 9 8.46983C9 8.32922 8.94414 8.19437 8.84472 8.09495L6.74845 6Z"
                        fill="#252525" />
                </svg>
            </span>
            <span v-else>
                <svg xmlns="http://www.w3.org/2000/svg" width="9" height="6" viewBox="0 0 9 6" fill="none">
                    <path d="M1 2.99984L3.33333 5.33317L8 0.666504" stroke="#5063F4" stroke-width="1.33333"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>

            {{ capitalizeFirstLetter(action) }}
        </TableActionButton>
        <Button :type="bt" v-else @click="deleteStep = 1">
            {{ capitalizeFirstLetter(action) }}
        </Button>
        <FeatureProductPrompt :size="successModalSize" @closedSuccessModal="cancelIt" btnOneText="No"
            :btnTwoText="action && action != 'Feature' ? 'Yes' : 'Yes'"
            :title="action && action != 'Feature' ? `Do you want to Unfeature ${actionObjectDetails[2]}? ` : `Do you want to Feature ${actionObjectDetails[2]}? `"
            :showm="deleteStep === 1" @btnOneClicked="cancelIt" @btnTwoClicked="featureIt()" message="" />
        <FeatureProductSuccess :size="successModalSize" @closedSuccessModal="viewSummary"
            :title="action && action != 'Feature' ? `${actionObjectDetails[2]} UnFeatured Successfully ` : ` ${actionObjectDetails[2]} Featured Successfully`"
            :showm="showSuccessModal" @btnOneClicked="myCampaigns()" @btnTwoClicked="showSuccessModal = false" />



        <ConfirmFeaturePrompt :size="successModalSize" @closedSuccessModal="closeFeatureHire" btnOneText="Yes"
            btnTwoText="No" title='Feature campaign warning' :message="titleforconfirminghigherProductfeature"
            :showm="showFeatureLower === 1" @btnOneClicked="featureLowerHigh(1)" @btnTwoClicked="featureLowerHigh(0)" />
        <!-- <FeatureProductPrompt :action="action && action!='Feature' ? 'Un Feature' :'Feature'" :name="productName" :submitted="submitted"  @cancelIt="cancelIt" @submit="featureIt" :show="deleteStep === 1"/>
        <FeatureProductSuccess :action="action && action!='Feature' ? 'Un Feature' :'Feature'" :name="productName" @viewSummary="viewSummary" @myCampaigns="myCampaigns" :show="deleteStep === 2" />
        <ApiError @cancelled="response_error=false" :title="(action && action!='Feature' ? 'Un Feature' :'Feature') +' Product'" :message="response_error" :show="response_error!==null" /> -->
    </div>
</template>
<script>
    import TableActionButton from "../../shared/Buttons/TableActionButton";
    // import FeatureProductPrompt from "./FeatureProductPrompt";
    // import FeatureProductSuccess from "./FeatureProductSuccess";

    import FeatureProductSuccess from "../../shared/messageboxes/TwoButtonActionMessageBox.vue";
    import FeatureProductPrompt from "../../shared/messageboxes/ConfirmDeletionPrompt.vue";
    import ConfirmFeaturePrompt from "../../shared/messageboxes/ConfirmPrompt.vue";

    import ApiError from "./ApiError";
    import Button from "../../shared/Buttons/Button";

    export default {
        components: {
            ConfirmFeaturePrompt,
            TableActionButton,
            FeatureProductSuccess,
            FeatureProductPrompt,
            // FeatureProductSuccess,
            // FeatureProductPrompt,
            ApiError,
            Button
        },
        props: ['actionId', 'action', 'bl', 'reloadPage', 'variantColor', 'textColor', 'productName', 'bt', 'actionObjectDetails'],
        data() {
            console.log(this.actionObjectDetails);

            return {
                submittedTermLength: "",
                productType: "",
                showSuccessModal: false,
                successModalSize: "md",
                response_error: null,
                deleteStep: 0,
                submitted: false,
                campaign_id: null,
                isConfirmation: 0,
                showFeatureLower: 0,
                featureHigher: false,
                titleforconfirminghigherProductfeature: ""
            }
        },
        methods: {
            closeFeatureHire() {
                this.featureHigher = false;
                this.isConfirmation = 0;
                this.showFeatureLower = 0;
            },
            featureLowerHigh(feature) {
                if (feature === 1) {
                    this.featureHigher = true;
                } else {
                    this.featureHigher = false;
                }
                this.showFeatureLower = 0;
                this.featureIt();
            },
            generateTermLengthShort(term) {
                let splitted = term.split(" ");
                return splitted[0] + splitted[1][0];
            },
            generateProdNameProdTypeshortcut(prodname) {

                let prodnamesub = "";
                if (prodname === "Non-Redeemable") {
                    prodnamesub = "NR";
                } else if (prodname === "Short Term") {
                    prodnamesub = "ST";
                }
                else if (prodname === "Cashable") {
                    prodnamesub = "Cash";
                }
                else if (prodname === "Notice Deposit" || prodname === "Notice deposit") {
                    prodnamesub = "ND";
                }
                else if (prodname === "High Interest Savings") {
                    prodnamesub = "HISA";
                } else {
                    prodnamesub = prodnamesub;
                }
                return prodnamesub;

            },
            myCampaigns() {
                window.location.href = "/campaigns";
            },
            viewSummary() {
                window.location.href = "/campaigns/summary/" + this.campaign_id;
            },
            cancelIt() {
                this.deleteStep = 0;
            },
            capitalizeFirstLetter(str) {
                if (typeof str !== 'string' || str.length === 0) {
                    return str; // Return unchanged if not a string or an empty string
                }
                return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
            },
            featureIt() {

                this.deleteStep = 0;
                this.submitted = true;
                axios.post("/campaigns/fi/feature-unfeature-campaign", {
                    product: this.actionId,
                    action: this.action.toUpperCase(),
                    isConfirmation: this.isConfirmation,
                    featureHigher: this.featureHigher
                }).then(response => {

                    if (response?.data?.success) {
                        if (response?.data?.prompt) {
                            this.isConfirmation = 1;
                            this.showFeatureLower = 1;
                            this.titleforconfirminghigherProductfeature = response?.data?.message;
                        } else {
                            this.campaign_id = response?.data?.campaign_id;
                            this.response_error = null;
                            this.deleteStep = 2;
                            this.showSuccessModal = true;
                            this.featureHigher = false;
                            this.isConfirmation = 0;
                        }


                    } else {
                        new Error(response?.data?.message);
                    }

                    this.submitted = false;
                }).catch(error => {
                    this.submitted = false;
                    this.response_error = error;
                    this.deleteStep = 0;
                });
            }
        }
    }
</script>