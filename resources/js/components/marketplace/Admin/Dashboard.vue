<template>
    <div>
        <b-card no-body class="text-center pt-2 pb-3 card-wrapper">
            <b-row>
                <h3 style="font-size: 20px;font-weight: 600;color: black">Filter Marketplace Offer.</h3>
            </b-row>
            <b-row>
                <b-container>
                    <b-row>
                        <b-col cols="6">
                            <CustomSelect :data="['ACTIVE', 'INACTIVE']" :default-value="status" id="status"
                                @selectChanged="filter('status', $event, true)"
                                c-style="font-weight: 400;border-radius: 10px" name="Bank" :has-validation="false" />
                        </b-col>
                        <b-col cols="6">
                            <CustomSelect :data="parsedAllBanks"
                                :attributes="{ 'value_field': 'id', 'text_field': 'name' }" :default-value="bank_id"
                                id="bank_id" @selectChanged="filter('bank_id', $event, true)"
                                c-style="font-weight: 400;border-radius: 10px" name="Bank" :has-validation="false"
                                firstValue="Select Bank" />
                        </b-col>
                    </b-row>
                </b-container>
            </b-row>
        </b-card>

        <b-card no-body class="text-center p-3 pb-3 card-wrapper">
            <b-table :items="parsedActiveBanks" :fields="bankslist" small hover responsive="sm">

                <template #cell(id)="row">
                    {{ row.index + 1 }}
                </template>

                <template #cell(bank)="row">
                    <div class="d-flex  align-items-center">
                        <OrganizationAvatar :pStyle="'display: flex; justify-content: flex-end'"
                            :organization="row.item" :size="30" />
                        <div class="mx-3">{{ row.item.name }}</div>
                    </div>


                </template>

                <template #cell(active_offers)="row">
                    {{ countActiveOffer({ offers: row.item.market_place_offer }) }} Offer(s)
                    <b-icon
                        :icon="(row.detailsShowing && row.item.list_type == 'ACTIVE') ? 'arrow-up-circle-fill' : 'arrow-down-circle-fill'"
                        font-scale="1.5"
                        :variant="(row.detailsShowing && row.item.list_type == 'ACTIVE') ? 'success' : 'primary'"
                        class="mt-2 ml-2 pe-auto" aria-hidden="true"
                        @click="listOffers(row, { offers: row.item.market_place_offer, status: 'ACTIVE' })"></b-icon>
                </template>

                <template #cell(expired_offers)="row">
                    {{ row.item.market_place_offer_count - countActiveOffer({ offers: row.item.market_place_offer }) }}
                    Offer(s)
                    <b-icon
                        :icon="(row.detailsShowing && row.item.list_type == 'EXPIRED') ? 'arrow-up-circle-fill' : 'arrow-down-circle-fill'"
                        font-scale="1.5"
                        :variant="(row.detailsShowing && row.item.list_type == 'EXPIRED') ? 'success' : 'primary'"
                        class="mt-2 ml-2 pe-auto" aria-hidden="true"
                        @click="listOffers(row, { offers: row.item.market_place_offer, status: 'EXPIRED' })"></b-icon>
                </template>

                <template #cell(total_offers)="row">
                    {{ row.item.market_place_offer_count }} Offer(s)
                    <b-icon
                        :icon="(row.detailsShowing && row.item.list_type == 'ALL') ? 'arrow-up-circle-fill' : 'arrow-down-circle-fill'"
                        font-scale="1.5"
                        :variant="(row.detailsShowing && row.item.list_type == 'ALL') ? 'success' : 'primary'"
                        class="mt-2 ml-2 pe-auto" aria-hidden="true"
                        @click="listOffers(row, { offers: row.item.market_place_offer, status: 'ALL' })"></b-icon>
                </template>

                <template #cell(action)="row">
                    <b-dropdown id="dropdown-1" text="Action" variant="primary" class="m-md-1 round">
                        <b-dropdown-item href="#"   @click="$bvModal.show('actionModel'+row.index)">Import Offer(s)</b-dropdown-item>
                    </b-dropdown>


                    <b-modal :id="'actionModel'+row.index" centered :title="'Import Offer for '+ row.item.name"  ref="modal" @ok="handleOk(row.item)">
                        <!-- <input type="hidden" v-bind="orgId" :value="row.item.id" />  -->
                        <b-form ref="form" @submit.stop.prevent="handleSubmit" enctype="multipart/form-data">
                            <b-form-group
                            label="Import CSV"
                            label-for="import-csv"
                            invalid-feedback="CSV File is required"
                            :state="Boolean(csvState)"
                            >
                            
                            <b-form-file
                                v-model="csv"
                                :state="Boolean(csvState)"
                                placeholder="Choose a file or drop it here..."
                                drop-placeholder="Drop file here..."
                                accept=".csv,.xlsx"
                                ></b-form-file>
                            </b-form-group>
                        </b-form>
                    </b-modal>
                </template>

                <template #row-details="row">
                    <b-container>
                        <b-row class="p-2 pt-0">

                            <b-col sm="12" cols="12">

                                <b-table :items="row.item.sort_offers" :fields="market_offers" small responsive="sm">

                                    <template #cell(is_featured)="row">
                                        {{ row.item.is_featured ? "YES" : "NO"
                                        }}
                                    </template>
                                    <template #cell(interest_rate)="row">
                                        {{ row.item.interest_rate + "%"
                                        }}
                                    </template>

                                    <template #cell(featured)="row">
                                        <b-badge :variant="(row.item.is_featured) ? 'success' : 'secondary'">{{(row.item.is_featured) ? 'Yes' : 'No'}}</b-badge>
                                    </template>

                                    <template #cell(minimum_amount)="row">
                                        {{ row.item.currency + " " +
                                                parseFloat(row.item.minimum_amount).toLocaleString()
                                        }}
                                    </template>
                                    <template #cell(maximum_amount)="row">
                                        {{ row.item.currency + " " +
                                                parseFloat(row.item.maximum_amount).toLocaleString()
                                        }}
                                    </template>
                                    <template #cell(interest_earned)="row">
                                        {{ row.item.currency + " " +
                                                parseFloat(row.item.interest_earned).toLocaleString()
                                        }}
                                    </template>
                                    <template #cell(term_length)="row">
                                        {{ parseInt(row.item.term_length) + " " + row.item.term_length_type }}
                                    </template>

                                </b-table>

                            </b-col>
                        </b-row>
                    </b-container>
                </template>
            </b-table>
        </b-card>
    </div>
</template>

<script>
import CustomSelect from "../../shared/CustomSelect";
import OrganizationAvatar from "../../shared/OrganizationAvatar";
import CustomInput from "../../shared/CustomInput";
export default {
    components: {
        CustomSelect,
        OrganizationAvatar,
        CustomInput
    },
    props: [
        'products', 'banks_with_offer', 'all_banks', 'errors'
    ],

    data() {
        return {
            parsedProducts: JSON.parse(this.products),
            parsedAllBanks: JSON.parse(this.all_banks),
            product_id: "",
            bank_id: "",
            status: "ACTIVE",
            start_date: "",
            end_date: "",
            csv: null,
            csvState: true,
            organization: "", 
            bankslist: [
                { key: 'id', sortable: true },
                { key: 'bank', sortable: true },
                { key: 'active_offers', sortable: true },
                { key: 'expired_offers' },
                { key: 'total_offers' },
                { key: 'action' },
            ],
            parsedActiveBanks: JSON.parse(this.banks_with_offer),
            market_offers: [
                { key: 'product_name', label: 'Product', sortable: true },
                { key: 'is_featured', label: 'Featured', sortable: true },
                { key: 'interest_rate', sortable: true },
                { key: 'featured', sortable: true },
                { key: 'minimum_amount', sortable: true },
                { key: 'maximum_amount', sortable: true },
                { key: 'interest_earned', sortable: true },
                { key: 'status', sortable: true },
                { key: 'term_length', label: 'Term', sortable: true },
            ],
        };
    },

    mounted() {

    },

    methods: {
        clearFilters() {

        },
        openOffer(row) {
            row.toggleDetails();
        },
        countActiveOffer(offers) {
            var active = offers.offers.filter((offer) => { if (offer.status == 'ACTIVE') { return offer } });
            return active.length;
        },
        listOffers(row, offers) {
            if (offers.status == "ALL") {
                row.item.sort_offers = row.item.market_place_offer;
            } else {
                row.item.sort_offers = offers.offers.filter((offer) => {
                    if (offer.status == offers.status) {
                        return offer;
                    }
                });
            }
            row.item.list_type = offers.status;
            row.toggleDetails();
        },
        filter(field, value, fetch = false) {
            switch (field) {
                case 'status':
                    this.status = value;
                    this.filterByStatus();
                    break;
                case 'bank_id':
                    this.bank_id = value;
                    this.filterByBank();
                    break;
            }
        },
        filterByBank() {
            axios.post(route('admin.marketplace.filterByBank'), {
                bank_id: this.bank_id
            }).then(response => {
                this.parsedActiveBanks = this.parsedAllBanks = response?.data?.bank;
            }).catch(error => {
                this.$swal({
                    title: 'Search failed',
                    text: "Market Offer not found",
                    confirmButtonText: 'Close'
                });
            });
        },
        filterByStatus() {
            if (this.status == "ACTIVE") {
                this.parsedActiveBanks = JSON.parse(this.banks_with_offer)
                return;
            }

            axios.post(route('admin.marketplace.filterByStatus'), {
                status: "EXPIRED"
            }).then(response => {
                this.parsedActiveBanks = this.parsedAllBanks = response?.data?.bank;
            }).catch(error => {
                this.$swal({
                    title: 'Search failed',
                    text: "Market Offer not found",
                    confirmButtonText: 'Close'
                });
            });
        },
        handleOk(organization) {
            if (!this.csv){
                this.csvState = !this.csvState;
                return;
            }
            this.organization = organization;
            this.handleSubmit();
        },
        handleSubmit(model) {

            const form_data = new FormData()
            form_data.set("file", this.csv);
            form_data.set("organizationId", this.organization.id);
            form_data.set("userId", this.organization.demographic_data.user_id);
            axios.post(route('admin.marketplace.importOffers'),form_data)
            .then(response => {
                
                if(response.success == false) {
                    this.$swal({
                        title: 'Failed',
                        text: response?.data?.message ,
                        confirmButtonText: 'Close'
                    });
                }else {
                    this.$swal({
                        title: 'Success',
                        text: "Market Offer Created Successfully",
                        confirmButtonText: 'Close'
                    });
                    window.location.reload()
                }
            }).catch(error => {

                if ( error?.response?.status === 422) {
                    var errorMessage = error?.response?.data?.message;
                } else {
                    var errorMessage = error?.response?.data?.message+ " - "+ error?.response?.data?.data[0];
                }
                this.$swal({
                    title: 'Failed',
                    text: errorMessage,
                    confirmButtonText: 'Close'
                });
            });

            // this.$nextTick(() => {
            //     this.$bvModal.hide(model)
            // })
        }


    },
};
</script>

<style scoped>

</style>