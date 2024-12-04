<template>
    <div>

        <div
            style="width: 100%; height: 60px; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex">
            <div
                style="width: 100%; padding-left: 3px; background: #EFF2FE; justify-content: flex-start; align-items: center; display: inline-flex">
                <div style="justify-content: space-between; display: inline-flex; width: 99%">
                    <div class="page-title">
                        <div class="title-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40"
                                fill="none">
                                <path
                                    d="M5.58813 14.9174C6.67396 10.2883 10.2883 6.67395 14.9174 5.58813C18.2604 4.80396 21.7396 4.80396 25.0826 5.58813C29.7117 6.67395 33.326 10.2883 34.4119 14.9174C35.196 18.2604 35.196 21.7396 34.4119 25.0826C33.326 29.7117 29.7117 33.326 25.0826 34.4119C21.7396 35.196 18.2604 35.196 14.9174 34.4119C10.2884 33.326 6.67396 29.7117 5.58813 25.0826C4.80396 21.7396 4.80396 18.2604 5.58813 14.9174Z"
                                    fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35" stroke-linecap="round" />
                                <path
                                    d="M24.875 13L13 24.8758L14.5802 26.456L26.4552 14.581L24.875 13ZM23.6891 21.3125C24.001 21.3128 24.3097 21.3746 24.5978 21.4942C24.8858 21.6138 25.1474 21.789 25.3678 22.0098C25.5881 22.2306 25.7628 22.4926 25.8818 22.7808C26.0009 23.0691 26.062 23.378 26.0617 23.6899C26.0614 24.0018 25.9997 24.3105 25.88 24.5986C25.7604 24.8866 25.5852 25.1482 25.3644 25.3686C25.1436 25.5889 24.8817 25.7636 24.5934 25.8826C24.3051 26.0017 23.9962 26.0628 23.6843 26.0625C23.0544 26.0619 22.4506 25.811 22.0057 25.3652C21.5607 24.9194 21.3111 24.315 21.3117 23.6851C21.3123 23.0552 21.5632 22.4514 22.009 22.0064C22.4549 21.5615 23.0592 21.3119 23.6891 21.3125ZM23.6867 22.8958C23.4767 22.8958 23.2754 22.9792 23.1269 23.1277C22.9784 23.2762 22.895 23.4775 22.895 23.6875C22.895 23.8975 22.9784 24.0988 23.1269 24.2473C23.2754 24.3958 23.4767 24.4792 23.6867 24.4792C23.8967 24.4792 24.098 24.3958 24.2465 24.2473C24.395 24.0988 24.4784 23.8975 24.4784 23.6875C24.4784 23.4775 24.395 23.2762 24.2465 23.1277C24.098 22.9792 23.8967 22.8958 23.6867 22.8958ZM15.7724 13.3958C16.4023 13.3965 17.0061 13.6473 17.4511 14.0931C17.8961 14.539 18.1457 15.1433 18.145 15.7732C18.1444 16.4031 17.8936 17.0069 17.4477 17.4519C17.0019 17.8968 16.3976 18.1465 15.7677 18.1458C15.1378 18.1452 14.5339 17.8944 14.089 17.4485C13.644 17.0027 13.3944 16.3983 13.395 15.7685C13.3957 15.1386 13.6465 14.5347 14.0923 14.0898C14.5382 13.6448 15.1425 13.3952 15.7724 13.3958ZM15.77 14.9792C15.5601 14.9792 15.3587 15.0626 15.2102 15.211C15.0618 15.3595 14.9784 15.5609 14.9784 15.7708C14.9784 15.9808 15.0618 16.1822 15.2102 16.3306C15.3587 16.4791 15.5601 16.5625 15.77 16.5625C15.98 16.5625 16.1814 16.4791 16.3298 16.3306C16.4783 16.1822 16.5617 15.9808 16.5617 15.7708C16.5617 15.5609 16.4783 15.3595 16.3298 15.211C16.1814 15.0626 15.98 14.9792 15.77 14.9792Z"
                                    fill="#5063F4" />
                            </svg>
                        </div>
                        <div class="text-div">Enter Rate and deposit details</div>
                    </div>
                    <!-- <div @click="toggleView(1)"
                        style="justify-content: flex-end !important; align-items: center; gap: 9px; display: flex; cursor: pointer;">
                        <div
                            style="text-align: center; color: #252525; font-size: 14px; font-weight: 500; line-height: 18px; word-wrap: break-word">
                            View {{ viewMore1 ? 'Less' : 'More' }}</div>
                        <img v-if="viewMore1" src="/assets/dashboard/icons/Polygon.svg" />
                        <img v-else src="/assets/dashboard/icons/Polygon 2.svg" />
                    </div> -->
                </div>
            </div>
        </div>


        <div class="mt-4">

            <table class="table" style="width: 100%; ">
                <thead class="customHeader">
                    <tr>
                        <th v-for="(column, index) in columns" :key="index">{{ column }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(value, index) in fi_data" :key="index">
                        <td>
                            <div class="textContainer">{{ index + 1 }}</div>
                        </td>
                        <td>
                            <div class="textContainer">{{ value.name }}</div>

                        </td>
                        <td>
                            <CustomInput inputType="number"
                                c-style="font-weight: 400;width:100%; margin-top:0px;height:90%;" p-style="width:100%"
                                class="demofirates" name="Rate*" :has-validation="true"
                                @inputChanged="InterestRateChange($event, value.id)" input-type="number"
                                :defaultValue="interest_rate[value.id]"
                                :hasSpecificError="interestRateError[value.id]" />
                            <div v-if="interestRateError[value.id]" class="error-message">
                                {{ interestRateError[value.id] }}
                            </div>
                        </td>
                        <td>
                            <div class="textContainer">{{ currency }}</div>
                        </td>
                        <td>
                            <CustomInput inputType="number"
                                c-style="font-weight: 400;width:100%; margin-top:0px;height:90%;" p-style="width:100%"
                                class="demofirates" name="Minimum Amount" :has-validation="true"
                                @inputChanged="minAmountChange($event, value.id)" input-type="number"
                                :defaultValue="min_amount[value.id]" :hasSpecificError="minAmountError[value.id]" />
                            <span v-if="minAmountError[value.id]" class="error-message">
                                {{ minAmountError[value.id] }}
                            </span>
                        </td>
                        <td>
                            <CustomInput inputType="number"
                                c-style="font-weight: 400;width:100%; margin-top:0px;height:90%;" p-style="width:100%"
                                class="demofirates" name="Maximum Amount" :has-validation="true"
                                @inputChanged="maxAmountChange($event, value.id)" input-type="number"
                                :defaultValue="max_amount[value.id]" :hasSpecificError="maxAmountError[value.id]" />
                            <span v-if="maxAmountError[value.id]" class="error-message">
                                {{ maxAmountError[value.id] }}
                            </span>
                        </td>
                    </tr>
                </tbody>

            </table>
            <!-- <Table :columns="columns" @selectedItems="$emit('selectedItems', $event)" @selectAllR="allselected"
                no-data-title="No Active Products" no-data-message="Start by adding a new product to populate data"
                :data="table_data" :has_action='false' @productDeletedAddNew="$emit('productDeletedAddNew')"
                :selectable="selectable" :selected_items="selected_items"
                :allselectable="(allselectablee) ? true : false" /> -->
            <!-- <Pagination @click-next-page="getData" v-if="data && data.links" :data="data" /> -->
        </div>
    </div>

</template>


<script>
import Pagination from '../../../shared/Table/Pagination.vue'
import Table from '../../../shared/Table'
import CustomInput from '../../../shared/CustomInput.vue';


export default {
    props: ['fi_data', 'selected_items', 'requests'],
    components: { Table, CustomInput },
    mounted() {
        // if (this.fi_data)
        if (this.requests) {
            let requestvals = Object.values(this.requests)
            let stripped = requestvals[0][1]
            this.maxamount = Number.parseFloat(stripped.deposit_amount)
            this.currency = stripped.deposit_currency
            console.log(this.currency, " curr ", this.maxamount, "requests")

        }
    },
    data() {
        return {
            table_data: null,
            columns: ['#', 'Financial Institution', 'Rate (%)', 'currency', 'Minimum Amount', 'Maximum Amount'],
            allselectablee: true,
            selectable: true,

            minAmountError: {},
            maxAmountError: {},
            interestRateError: {},
            min_amount: {},
            max_amount: {},
            interest_rate: {},
            haserror: false,
            rates_and_deposits: [],
            maxamount: null,
            currency: 'CAD'
        }
    },
    methods: {
        minAmountChange(value, index) {
            this.min_amount[index] = this.removeCommas(value)
            if (this.min_amount[index] <= this.maxamount && this.min_amount[index] > 0) {
                if (this.max_amount[index] != null && this.min_amount[index] > this.max_amount[index])
                    this.$set(this.minAmountError, index, "Amount cannot be greater than Maximum amount")
                else
                    this.$set(this.minAmountError, index, null)
            } else {
                this.$set(this.minAmountError, index, "Amount cannot be greater than requested amount")
            }
        },
        maxAmountChange(value, index) {
            this.max_amount[index] = this.removeCommas(value)
            if (this.max_amount[index] <= this.maxamount && this.max_amount[index] > 0) {
                if (this.min_amount[index] != null && this.max_amount[index] < this.min_amount[index])
                    this.$set(this.maxAmountError, index, "Amount Cannot Be less than minimum amount")
                else
                    this.$set(this.maxAmountError, index, null)
            } else {
                this.$set(this.maxAmountError, index, "Amount cannot be greater than requested amount")

            }
        },
        InterestRateChange(value, index) {
            this.interest_rate[index] = value
            if (this.interest_rate[index] > 100)
                this.$set(this.interestRateError, index, "Rate is greater than 100%")
            else
                this.$set(this.interestRateError, index, null)
        },
        validateValues() {
            this.haserror = false
            this.fi_data.forEach(element => {
                let indexchecker = element.id
                if (!this.min_amount[indexchecker]) {
                    this.haserror = true
                    this.$set(this.minAmountError, indexchecker, "This field is required")
                } else {
                    this.minAmountChange(this.min_amount[indexchecker], indexchecker)
                }
                if (!this.max_amount[indexchecker]) {
                    this.haserror = true
                    this.$set(this.maxAmountError, indexchecker, "This field is required")
                } else {
                    this.maxAmountChange(this.max_amount[indexchecker], indexchecker)

                }
                if (!this.interest_rate[indexchecker]) {
                    this.haserror = true
                    this.$set(this.interestRateError, indexchecker, "This field is required")
                } else {
                    this.InterestRateChange(this.interest_rate[indexchecker], indexchecker)
                }

            });

            if (!this.haserror) {
                const minnull = Object.values(this.minAmountError).every(minerror => minerror === null);
                const maxnull = Object.values(this.maxAmountError).every(maxerror => maxerror === null);
                const ratenull = Object.values(this.interestRateError).every(rateerror => rateerror === null);

                // [{ "organization_id": "7", "rate": "12", "min_amount": "40000", "max_amount": "222222" },
                // { "organization_id": "100", "rate": "22", "min_amount": "222", "max_amount": "22222" },
                // { "organization_id": "101", "rate": "22", "min_amount": "222", "max_amount": "22222" }]
                if (minnull && maxnull && ratenull) {
                    this.rates_and_deposits = this.fi_data.map(element => ({
                        organization_id: element.id,
                        rate: this.interest_rate[element.id],
                        min_amount: this.min_amount[element.id],
                        max_amount: this.max_amount[element.id]
                    }));
                    this.$emit('dosubmit', this.rates_and_deposits)
                    // return true;
                } else {
                    console.log("Not Ready for submit.");
                    this.rates_and_deposits = []
                    // return false;
                }

            }

        },
        removeCommas(newValue) {
            return newValue ? parseFloat(newValue.toString().replace(/,/g, '')) : 0;
        },

    }
}
</script>
<style>
.demofirates input {
    border-radius: 0px !important;
    outline: none !important;
    border: none !important;
    border-bottom: 1px solid #ccc !important;
    font-family: Montserrat !important;
}
</style>
<style scoped>
.textContainer {
    width: 100%;
    height: 100%;
    color: black;
    font-size: 15px;
    font-family: Montserrat;
    font-weight: 400;
    padding-top: 20px;
    word-wrap: break-word
}

thead.customHeader {
    background: #eff2fe !important;
    height: 51px;
}

thead.customHeader tr th span .custom-checkbox ::before {
    border-radius: 4px !important;
    border: 0.50px #5063F4 solid !important;
    padding-left: 2px;
}

thead.customHeader tr th span .custom-checkbox .custom-control-label {
    border: 0.50px #5063F4 solid !important;
    margin-top: 0 !important;
}

thead .custom-control-label {
    margin-top: 0 !important;
}

thead.customHeader tr {
    border-bottom: 0 solid #b3b2b2 !important;
}

thead.customHeader tr th {
    color: black;
    font-size: 16px !important;
    font-weight: 700;
    background: inherit !important;
    max-width: 300px;
    /* min-width: 150px; */
    padding-right: 0.75rem;
    padding-left: 0.55rem;
}

tbody tr td {
    padding-top: 5px !important;
    font-size: 13px !important;
    vertical-align: top !important;
}

@media screen and (max-width:1200px) {
    thead.customHeader tr th {
        font-size: .75em;
    }
}

.table tbody tr td {
    padding: none !important;
}

.error-message {
    color: red;
}
</style>