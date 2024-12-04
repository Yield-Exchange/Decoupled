<template>
    <div>
        <TableActionButton v-if="!bt" @click="deleteStep = 1" variantColor="#FFEBEB" text-color="#FF2E2E;">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                <path
                    d="M6.74845 6L8.84339 3.90505C8.94282 3.8058 8.99875 3.67112 8.99887 3.53064C8.99899 3.39015 8.94331 3.25537 8.84406 3.15594C8.74481 3.05652 8.61012 3.00059 8.46964 3.00047C8.32915 3.00034 8.19437 3.05603 8.09495 3.15528L6 5.25023L3.90505 3.15528C3.80563 3.05586 3.67078 3 3.53017 3C3.38956 3 3.25471 3.05586 3.15528 3.15528C3.05586 3.25471 3 3.38956 3 3.53017C3 3.67078 3.05586 3.80563 3.15528 3.90505L5.25023 6L3.15528 8.09495C3.05586 8.19437 3 8.32922 3 8.46983C3 8.61044 3.05586 8.74529 3.15528 8.84472C3.25471 8.94414 3.38956 9 3.53017 9C3.67078 9 3.80563 8.94414 3.90505 8.84472L6 6.74977L8.09495 8.84472C8.19437 8.94414 8.32922 9 8.46983 9C8.61044 9 8.74529 8.94414 8.84472 8.84472C8.94414 8.74529 9 8.61044 9 8.46983C9 8.32922 8.94414 8.19437 8.84472 8.09495L6.74845 6Z"
                    fill="#FF2E2E" />
            </svg>
            Remove
        </TableActionButton>
        <Button :type="bt" v-else @click="deleteStep = 1">
            Remove
        </Button>
        <ConfirmDeletionPrompt :size="successModalSize" @closedSuccessModal="cancelIt" btnOneText="Previous"
            btnTwoText="Remove" :title="`Confirm Removal of ${actionObjectDetails[1]}`" :showm="deleteStep === 1"
            @btnOneClicked="cancelIt" @btnTwoClicked="deleteIt()" />

        <DeleteProductSuccess :size="successModalSize" @closedSuccessModal="seeMyProducts()"
            btnOneText="Add New Product" btnTwoText="My Products" :title="` Removed successfully`"
            :titlespan="actionObjectDetails[1]" :showm="showSuccessModal" @okClicked="seeMyProducts()" />
        <!-- <ApiError @cancelled="response_error=false" :title="'Remove Product'" :message="response_error" :show="response_error!==null" /> -->
    </div>
</template>
<script>
    import Button from "../../shared/Buttons/Button";
    import TableActionButton from "../../shared/Buttons/TableActionButton";
    import ApiError from "./ApiError";
    import DeleteProductSuccess from "../../shared/messageboxes/OKButtonActionMessageBox.vue";
    import ConfirmDeletionPrompt from "../../shared/messageboxes/ConfirmDeletionPrompt.vue";
    // import DeleteProductPrompt from "./DeleteProductPrompt";
    // import DeleteProductSuccess from "./DeleteProductSuccess";
    export default {
        components: {
            TableActionButton,
            Button,
            ApiError,
            DeleteProductSuccess,
            ConfirmDeletionPrompt
        },
        props: ['actionId', 'bt', 'actionObjectDetails'],
        data() {
            // console.log(this.actionObjectDetails);
            return {
                successTitle: "",
                showSuccessModal: false,
                successModalSize: "md",
                response_error: null,
                deleteStep: 0,
                submitted: false,
            }
        },
        methods: {
            addNewProduct() {
                this.$emit("productDeletedAddNew", new Date());
                this.showSuccessModal = false;
                window.location = "/campaigns/products";
            },
            seeMyProducts() {
                this.showSuccessModal = false;
                this.$emit('reloadData', this.$event);
                // window.location="/campaigns/products";
            },
            cancelIt() {
                this.deleteStep = 0;
                if (this.bt) {
                    window.location.href = ('/campaigns/products');
                } else {
                    this.$emit('reloadData', true);
                }
            },
            deleteIt() {
                this.submitted = true;
                axios.post("/campaigns/fi/delete-product", {
                    product: this.actionId,
                    status: 'INACTIVE'
                }).then(response => {

                    if (response?.data?.success) {
                        this.response_error = null;
                        this.showSuccessModal = true;
                        this.successbtnOneText = "Add New Product";
                        this.successbtnTwoText = "Products";
                        this.successTitle = actionObjectDetails[2] + response?.data?.message;
                        // window.location="/campaigns/products";
                        this.deleteStep = 2;
                    } else {
                        new Error(response?.data?.message);
                    }

                    this.submitted = true;
                }).catch(error => {
                    this.submitted = false;
                    this.response_error = error;
                    this.deleteStep = 0;
                });

            }
        }
    }
</script>