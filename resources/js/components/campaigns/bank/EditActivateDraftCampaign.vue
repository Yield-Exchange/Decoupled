<template>
    <TableActionButton variant-color="" text-color="" type="table-action-btn" @click="validateCampaignDetails">
        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M7.98987 1.90362C8.25777 1.6358 8.62106 1.48535 8.99987 1.48535C9.37868 1.48535 9.74197 1.6358 10.0099 1.90362L10.0959 1.98966C10.3637 2.25756 10.5142 2.6209 10.5142 2.99971C10.5142 3.37852 10.3638 3.74181 10.096 4.00971L4.30292 9.80276C4.24799 9.85768 4.17917 9.89665 4.10381 9.91549L2.10381 10.4155C1.95777 10.452 1.80327 10.4092 1.69682 10.3028C1.59038 10.1963 1.54758 10.0418 1.58409 9.89577L2.08409 7.89577C2.10293 7.82041 2.1419 7.75159 2.19682 7.69666L7.19656 2.69693L7.98987 1.90362ZM7.49987 3.6058L2.88685 8.21882L2.58888 9.4107L3.78076 9.11273L8.39378 4.49971L7.49987 3.6058ZM8.99987 3.89362L8.10596 2.99971L8.59587 2.5098C8.59586 2.50982 8.59589 2.50979 8.59587 2.5098C8.70302 2.4027 8.84837 2.34249 8.99987 2.34249C9.15137 2.34249 9.29667 2.40266 9.40382 2.50976C9.40381 2.50974 9.40384 2.50977 9.40382 2.50976L9.48978 2.59571C9.48976 2.59569 9.48979 2.59573 9.48978 2.59571M6.0713 9.99971C6.0713 9.76302 6.26318 9.57114 6.49987 9.57114H10.4999C10.7366 9.57114 10.9284 9.76302 10.9284 9.99971C10.9284 10.2364 10.7366 10.4283 10.4999 10.4283H6.49987C6.26318 10.4283 6.0713 10.2364 6.0713 9.99971Z"
                fill="#5063F4" />
        </svg>
        Edit
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
                this.needToUpdate.push("campaign_details");
                return campain_details_done;
            },
            checkCampaignDepositorsDetails(depositors) {
                let campaign_depositors_done = false;
                this.needToUpdate.push("campaign_depositors");
                return campaign_depositors_done;
            },

            checkCampaignProductDetails(products) {
                let camapign_products_done = false;
                this.needToUpdate.push("campaign_products");
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