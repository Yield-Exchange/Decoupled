<template>
    <TableActionButton type="table-action-btn" @click="validateCampaignDetails()" variantColor="#EAF5EA"
        text-color="#2A9928">
        <img src="/assets/dashboard/icons/tick.svg" />
        Activate
    </TableActionButton>
</template>
<script>
    import TableActionButton from "../../shared/Buttons/TableActionButton";
    export default {
        components: {
            TableActionButton,
        },
        methods: {
            validateCampaignDetails() {
                axios.get(`/campaigns/fi/get-campaign-details/?campaign=${this.actionId}`)
                    .then(response => {
                        let campaign_details = response.data;
                        console.log(campaign_details, "campaign_detailscampaign_details");
                        this.checkAllCampaignDetails(campaign_details);
                        this.checkCampaignProductDetails(campaign_details?.campaign_products);
                        this.checkCampaignDepositorsDetails(campaign_details?.campaign_invite_depositors);
                        if (this.needToUpdate.length > 0) {
                            this.camapigType = campaign_details?.campaign_depositors_invite_type;
                            this.editIt();
                        } else {
                            window.location.href = `/campaigns/draft-summary/${this.actionId}`;
                        }

                        console.log(this.needToUpdate, "needToUpdate");


                    }).catch(error => {
                        console.log("error > " + error);
                    });

            },
            checkAllCampaignDetails(campaign) {
                let campain_details_done = false;
                if (campaign.campaign_name == "" || campaign.start_date == "" || campaign.expiry_date == "" || campaign.subscription_amount == "" || campaign.currency == "") {

                    if (!this.needToUpdate.includes("campaign_details")) {
                        this.needToUpdate.push("campaign_details");
                    }
                    campain_details_done = false;
                } else {
                    campain_details_done = true;
                }
                return campain_details_done;
            },
            checkCampaignDepositorsDetails(depositors) {
                let campaign_depositors_done = false;
                if (depositors.length <= 0) {
                    if (!this.needToUpdate.includes("campaign_depositors")) {
                        this.needToUpdate.push("campaign_depositors");
                    }
                    campaign_depositors_done = true;
                }
                return campaign_depositors_done;
            },
            checkCampaignProductDetails(products) {
                let camapign_products_done = false;
                if (products.length <= 0) {

                    if (!this.needToUpdate.includes("campaign_products")) {
                        this.needToUpdate.push("campaign_products");
                    }
                    camapign_products_done = true;
                }
                products.forEach((value, key) => {
                    console.log(value);
                    if (value.maximum == 0 || value.minimum == 0 || value.rate == 0.00 || (value.maximum < value.minimum)) {
                        if (!this.needToUpdate.includes("campaign_products")) {
                            this.needToUpdate.push("campaign_products");
                        }
                        camapign_products_done = false;
                        return value;
                    }
                })
                return camapign_products_done;
            }, editIt() {
                window.location.href = `/campaigns/edit-campaign/${this.actionId}?isDraft=1&steps=${this.needToUpdate}&&camptype=${this.camapigType}`;
            }
        },
        data() {
            return {
                camapigType: 0,
                needToUpdate: []
            }
        },
        props: ['actionId']
    }
</script>