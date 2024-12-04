<template>
  <Modal :show="show" @isVisible="$emit('closeModal', $event)" modalsize="xl">
    <div>
      <div class="counter-offer-container">
        <div class="original_offer">
          <div class="header_section">Current</div>
          <div class="original_info">
            <div class="sub_header">
              {{ hasNoticePeriod ? datum?.lockout_period + ' Day ' + datum?.description : datum?.description }}
            </div>
            <div class="original_rate">{{ datum?.rate }}%</div>
            <div class="original_bank">{{ datum?.campaign?.organization?.name }}</div>
            <div class="original_deposit">Interest Earned: CAD
              {{ addCommas(interestEarned) }} </div>
            <div class="original_dates">
              <div class="sub_dates"><span style="color: #5063F4;">Date of Deposit</span> {{
    this.formatDateToCustomFormat(datum?.expiry_date) }}
              </div>
              <div class="sub_dates"><span style="color: #5063F4;">Rate Held Until </span>
                <TimerClock variant="success" :timezone="offer?.invited?.deposit_request?.user?.formatted_timezone"
                  :target-time="datum?.campaign?.expiry_date" />
              </div>
            </div>
          </div>
        </div>
        <div class="counter_offer">
          <div class="header_section">Counter</div>
          <div style="margin-top: 20px;">
            <b-row>
              <FormLabelRequired style="padding: 4px;" labelText="Deposit Amount" required="true"
                :showHelperText="false" helperText="Deposit Amount" helperId="depositAmount" />
              <CustomCurrencyValueInput @selectedCurrency="selectedCurrency = $event" :selectedCurrency="selectedCurrency"
                :currencyOptions="currencyOptions" inputType="number" :defaultValue="depositAmount"
                @inputChanged="depositAmountChange($event)" :hasError="depositAmountError" />
              <div v-if="depositAmountError" class="error-message">
                {{ depositAmountError }}
              </div>
            </b-row>

            <b-row>
              <div style="display: flex; flex-direction:row;">
                <div
                  style="display: flex; width:50%; padding-top:15px; flex-direction:column;justify-content:flex-start;">
                  <FormLabelRequired labelText="Interest Rate(%)" required="true" :showHelperText="false"
                    helperText="Interest Rate" helperId="interestRate" />

                  <CustomInput :attributes="{ 'value_field': 'id', 'text_field': 'description' }"
                    p-style="width: 100%;  height:50px; mergin-right:20px;"
                    c-style="font-weight: 400;width:100%;background:white" id="interestRate" name="Rate*"
                    :has-validation="true" :defaultValue="interestRate" @inputChanged="InterestRateChange($event)"
                    inputType='number' :hasSpecificError="interestRateError" v-model="interestRate" />
                  <div v-if="interestRateError" class="error-message">
                    {{ interestRateError }}
                  </div>

                </div>
                <div
                  style="display: flex; width:50%; padding-left:5px;  flex-direction:column; justify-content:flex-start; margin-top: 16px">

                  <FormLabelRequired labelText="Rate Held Until" required="true" :showHelperText="false"
                    helperText="Interest Rate" helperId="interestRate" />
                  <CustomSystemDate :timezone="timezone" @input="setRateHeld($event)" :hasError="rateHeldUntilError" />
                  <span class="error-message" v-if="rateHeldUntilError?.length > 0">{{ rateHeldUntilError }}</span>
                </div>
              </div>

            </b-row>

            <b-row>
              <div style="display: flex; flex-direction:row;">
                <div
                  style="display: flex; width:50%; padding-top:15px; flex-direction:column;justify-content:flex-start;">
                  <FormLabelRequired style="padding: 4px;" labelText="Interest Rate Changed by" required="true"
                    :showHelperText="false" helperText="Interest Rate Changed by(%)" helperId="interestRateChangedby" />

                  <CustomInput :attributes="{ 'value_field': 'id', 'text_field': 'description' }" p-style="width: 100%;"
                    c-style="font-weight: 400;width:100%;background:white" id="interestRateChangedby" name="0.25%"
                    :has-validation="false" :default-value="interestRateChangedby" :disabled="true" />
                </div>
                <div
                  style="display: flex; width:50%; padding-left:5px;  flex-direction:column; justify-content:flex-start; margin-top: 16px">
                  <FormLabelRequired style="padding: 4px;" labelText="Additional interest earned" required="true"
                    :showHelperText="false" helperText="Additional interest earned"
                    helperId="additionalInterestEarned" />

                  <CustomInput :attributes="{ 'value_field': 'id', 'text_field': 'description' }" p-style="width: 100%;"
                    c-style="font-weight: 400;width:100%;background:white" id="additionalInterestEarned" name=" "
                    :has-validation="false" :default-value="additionalInterestEarned" :disabled="true" />
                </div>
              </div>
            </b-row>

          </div>
        </div>

      </div>
      <b-row>
        <FormLabelRequired style="padding: 4px;" labelText="Special Instructions" required="false"
          :showHelperText="false" helperText="Special Instructions" helperId="specialInstructions" />
        <CustomInput :attributes="{ 'value_field': 'id', 'text_field': 'description' }" p-style="width: 100%;"
          c-style="font-weight: 400;width:100%;background:white" id="specialInstructions" name="Special Instructions"
          :has-validation="true" :default-value="specialInstructions" v-model="specialInstructions"
          input-type="textareanew" />
      </b-row>

      <div style="width: 100%;padding:25px;">
        <table class="table" style="width: 100%;">
          <thead class="customHeader">
            <tr>
              <th>#</th>
              <th>Counter Offer Date</th>
              <th>Deposit Amount</th>
              <th>Counter Rate</th>
              <th>Interest Rate Change</th>
              <th>Counter</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(value, index) in counterOffers" :key="index" v-if="index < 2">
              <td>{{ index + 1 }}</td>
              <td>
                <div class="textContainer">
                  {{ formatDateToCustomFormat(value.created_at) }}</div>
              </td>
              <td>
                <div class="textContainer">CAD {{ addCommas(value.maximum_amount) }}</div>
              </td>
              <td>
                <div class="textContainer"> {{ value.offered_interest_rate.toFixed(2) }} %</div>
              </td>
              <td>
                <div class="textContainer"> {{ (value.offered_interest_rate -
    datum?.rate).toFixed(2) }}
                  %</div>
              </td>
              <td>
                <CustomInvitedStatusBadge :text="value.status" />
              </td>
            </tr>
          </tbody>


        </table>

      </div>
      <b-row>
        <div class="button-layout">
          <Button @click="submit" :loading="loading" type="primary" text="Submit" xclass="button-action" />
        </div>
      </b-row>

    </div>
  </Modal>
</template>


<script>
import Modal from "../../../shared/Modal";
import CustomInput from "../../../shared/CustomInput.vue";
import CustomDateInput from '../../../../components/auth/signup/shared/CustomDateInput.vue';
import FormLabelRequired from "../../../shared/formLabels/FormLabelRequired.vue";
import CustomCurrencyValueInput from "../../../shared/CustomCurencyAmount.vue";
import CustomDate from "../../../shared/CustomDate.vue";
import Button from "../../../shared/Button.vue";
import CustomInvitedStatusBadge from "./CustomInvitedStatusBadge.vue";
import CustomSystemDate from "../../../post-requests/sharedComponents/CustomSystemDate.vue";

import TimerClock from "../../../post-requests/sharedComponents/TimerClock.vue";

export default {
  components: {
    Modal,
    FormLabelRequired,
    CustomInput,
    CustomDateInput,
    CustomCurrencyValueInput,
    //selectedCurrency: 'CAD',
    CustomDate,
    Button,
    CustomInvitedStatusBadge,
    CustomSystemDate,
    TimerClock
  },
  props: ['show', 'datum', 'investment_amount', 'hasCounterOffer', 'offer', 'timezone'],
  created() {
    //console.log(this.datum, " datum");
    // console.log('test props392:', this.counterOffers);
  },
  data() {

    return {
      depositAmount: 0,
      interestRate: 0,
      specialInstructions: '',
      rate_held_until: '',
      selectedCurrency: "CAD",
      depositAmountError: "",
      interestRateError: "",
      loading: false,
      rateHeldUntilError: "",
      counteroffersub: true
    }
  },
  methods: {
    depositAmountChange(value) {
      //console.log('data',this.datum)
      if (value == '') {
        this.depositAmountError = "Required"
      } else {
        let valcheck = Number.parseFloat(value.replace(/,/g, ''))
        if (valcheck <= 0 || valcheck > 9999999999999) {
          this.depositAmountError = "Invalid"
        } else {
          this.depositAmountError = ""
          this.depositAmount = value;
        }

      }

      //console.log(this.depositAmount, "deposit Mount")
    },
    setRateHeld(date) {
      this.rate_held_until = date;
      this.rateHeldUntilError = ""
    },
    InterestRateChange(value) {
      this.interestRate = value
      if (this.interestRate > 100)
        this.interestRateError = "Rate is greater than 100%"
      else
        this.interestRateError = null
    },
    selectColor(value) {
      if (value === 'ACCEPTED') {
        return '#44E0AA';
      } else if (value === 'COUNTERED') {
        return '#5063F4';
      }
      else {
        return '';
      }
    },
    sanitizeAmount(val) {
      try {
        // return parseFloat(val.replace(",", "", val).replace(" ", "", val));
        return parseFloat(val.replace(/,/g, "").replace(/ /g, ""));
      } catch (e) {
        return val;
      }
    },
    formatDateToCustomFormat(inputDate) {
      // Create a Date object from the inputDate parameter
      const options = { month: 'short', day: '2-digit', year: 'numeric' };
      const date = new Date(inputDate);
      const formattedDate = date.toLocaleDateString('en-US', options);

      return formattedDate;
    },
    calaculateInterestRate(term_length_type, amount_offered, term_length, rate) {
      // console.log(term_length_type + "," + amount_offered + "," + term_length + "," + rate);
      let cal_interest = 0;
      switch (term_length_type) {
        case "DAYS":
          cal_interest = Math.round(
            (((amount_offered * rate) / 100) * term_length) / 365
          );
          break;
        case "MONTHS":
          cal_interest = Math.round(
            (((amount_offered * rate) / 100) * term_length) / 12
          );
        case "days":
          cal_interest = Math.round(
            (((amount_offered * rate) / 100) * term_length) / 365
          );
          break;
        case "months":
          cal_interest = Math.round(
            (((amount_offered * rate) / 100) * term_length) / 12
          );
          break;
      }
      return cal_interest;


    },
    submit() {
      this.counteroffersub = true
      if (this.rate_held_until === '') {
        this.rateHeldUntilError = 'Required';
        this.counteroffersub = false;
      }
      if (this.depositAmountError != ""){
        
      }else if (this.depositAmount === '' || this.depositAmount === 0) {
        this.depositAmountError = 'Required';
        this.counteroffersub = false;
      }
      if (this.interestRate === '' || this.interestRate === 0) {
        this.interestRateError = 'Required';
        this.counteroffersub = false;
      }
      if (this.counteroffersub) {
        this.loading = true;
        const formData = new FormData();
        formData.append("amount", this.removeCommas(this.depositAmount));
        formData.append("min_amount", this.removeCommas(this.depositAmount));
        formData.append("max_amount", this.removeCommas(this.depositAmount));
        formData.append("offer_id", this.datum?.offer?.[0]?.id);
        formData.append('campaign_id', this.datum?.campaign_id);
        formData.append('campaign_prod_id', this.datum?.id);
        formData.append('rate_held_until', this.rate_held_until);
        formData.append('currency', this.datum?.currency);
        formData.append('nir', this.interestRate);
        formData.append("rate_type", 'prime_rate');
        formData.append('from_campaign', true)
        axios.post('/counter-offer', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        }).then(response => {
          this.loading = false;
          this.$emit('visible', false);
          this.successModalTitle = response?.data.message;
          this.showResponseModal = true;
          setTimeout(() => {
            window.location.reload()
          }, 2000)

        }).catch(error => {
          this.$emit('visible', false);
          this.transError = error;
          this.submitted = false;
          this.hasTransError = true;
          this.loading = false
          setTimeout(() => {
            window.location.reload()
          }, 2000)
          //// show error
        });
      }

    },
    removeCommas(value) {
      return value?.replace(/,/g, '');
    },
    addCommas(value) {
      return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
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
  },
  computed: {
    hasNoticePeriod() {
      // Using the includes method
      return (this.datum?.description?.includes("Notice deposit") || this.datum?.description?.includes("Cashable"));
    },
    interestRateChangedby() {
      return this.interestRate - this.datum?.rate
    },
    interestEarned() {
      return this.addCommas(this.calaculateInterestRate(this.datum?.term_length_type, this.sanitizeAmount(this.depositAmount), this.datum?.term_length, this.sanitizeAmount(this.datum?.rate)))
    },
    additionalInterestEarned() {
      let interest = this.calaculateInterestRate(this.datum?.term_length_type, this.sanitizeAmount(this.depositAmount), this.datum?.term_length, this.sanitizeAmount(this.interestRate));
      let sub = interest - this.sanitizeAmount(this.interestEarned)
      return this.addCommas(sub)
    },
    currencyOptions() {
      return ['CAD', 'USD']
    },
    counterOffers() {
      let counter_offers = [];
      this.offer.map(item => {
        item?.counter_offers?.map(counter => {
          counter_offers.push(counter)
        })
      })
      return counter_offers;
    },
    investmentAmount() {
      if (this.counterOffers.length > 0) {
        this.depositAmount = this.counterOffers[0].maximum_amount;
      } else {
        this.depositAmount = this.investment_amount;
      }
      this.interestRate = this.datum?.rate;

      return this.depositAmount;
    }

  },
  watch: {
    counter_offer_expiry: function (newExpiryDate) {
      $("#datepicker").datetimepicker("setOptions", {
      });
    },
  },
  mounted() {
    if (this.datum && typeof (this.datum) == 'string') {
      this.datum = JSON.parse(this.datum);
    }
  },
}
</script>

<style>
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

@media screen and (max-width:1200px) {
  thead.customHeader tr th {
    font-size: .75em;
  }
}

.table tbody tr td {
  padding: none !important;
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

@media screen and (max-width:1200px) {
  thead.customHeader tr th {
    font-size: .75em;
  }
}

.table tbody tr td {
  padding: none !important;
}

.counter_offer {
  flex-grow: 1;
}

.original_offer {
  flex-grow: 1;
}

.counter-values {
  color: #252525;
  font-family: Montserrat;
  font-size: 15px;
  font-style: normal;
  font-weight: 400;
  line-height: normal;
}

.counter-labels {
  color: #000;
  font-family: Montserrat;
  font-size: 14px;
  font-style: normal;
  font-weight: 700;
  line-height: normal;
}

.log-container {
  display: flex;
  padding: 10px;
  justify-content: center;
  align-items: center;
  gap: 10px;
  background: #EFF2FE;
  margin-bottom: 6px;
}

.counter_header {
  color: #5063F4;
  margin-bottom: -3px;
  font-family: Montserrat;
  font-size: 16px;
  font-style: normal;
  font-weight: 700;
  line-height: 26px;
  /* 162.5% */
  text-transform: capitalize;
}

.text-button {
  color: #FFF;
  /* Yield Exchange Text Styles/Buttons Bold */
  font-family: Montserrat;
  font-size: 16px;
  font-style: normal;
  font-weight: 600;
  line-height: 20px;
  /* 125% */
  text-transform: capitalize;
}

.button-action {
  cursor: pointer;
  border-radius: 20px;
  background: #5063F4;
  padding: 10px 30px;
}

.button-layout {
  margin-top: 20px;
  display: flex;
  justify-content: flex-end;
  align-items: center;
  gap: 30px;
  align-self: stretch;
}

.vertical_line {
  border-left: 0.5px solid #9CA1AA;
  height: 292px;
}

.sub_dates {
  display: flex;
  flex-direction: column;
  color: #252525;
  font-family: Montserrat;
  font-size: 18px;
  font-style: normal;
  font-weight: 700;
  line-height: normal;
}

.original_deposit {
  background-color: #EFF2FE;
  color: #252525;
  text-align: center;
  font-family: Montserrat;
  font-size: 18px;
  font-style: normal;
  font-weight: 700;
  line-height: normal;
  padding-left: 10px;
  padding-right: 10px;
}

.original_bank {
  color: #252525;
  text-align: center;
  font-family: Montserrat;
  font-size: 18px;
  font-style: normal;
  font-weight: 700;
  line-height: normal;
}

.original_rate {
  color: #5063F4;
  text-align: center;
  font-family: Montserrat;
  font-size: 55px;
  font-style: normal;
  font-weight: 700;
  line-height: normal;
}

.sub_header {
  color: #252525;
  text-align: center;
  font-feature-settings: 'clig' off, 'liga' off;

  /* Yield Exchange Text Styles/Promotion chart body */
  font-family: Montserrat;
  font-size: 16px;
  font-style: normal;
  font-weight: 400;
  line-height: 26px;
  /* 162.5% */
  text-transform: capitalize;
}

.original_dates {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 20px;
}


.original_info {
  background: #FFF;
  box-shadow: 0px 4px 6px 0px rgba(80, 99, 244, 0.20);
  display: flex;
  padding: 30px;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  gap: 10px;
  flex: 1 0 0;
  align-self: stretch;
}

.review-transaction-details-modal .modal-content {
  max-width: 750px !important;
  align-items: center;
  justify-content: center;
}

.counter-offer-container {
  display: flex;
  gap: 30px;
  flex-direction: row;
  align-items: flex-start;
  padding: 20px;
  font-family: sans-serif;

}

.header_section {
  color: #5063F4;

  /* Yield Exchange Text Styles/Modal  & Blue BG Titles */
  font-family: Montserrat;
  font-size: 28px;
  font-style: normal;
  font-weight: 700;
  line-height: 32px;
  /* 114.286% */
  text-transform: capitalize;
}

.title {
  font-weight: 800;
  font-size: 22px;
}

.info {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  max-width: 700px;
  /* Limit the width of the info section */
}

.info-section {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 10px;
  flex-basis: calc(33.33% - 20px);
  /* Accommodate only 3 elements per row with spacing */
}

.info-label {
  font-size: 16px;
  font-weight: 550;
  line-height: 1.5;
  color: black;
}

.info-value {
  font-size: 16px;
  font-weight: 550;
  line-height: 1.5;
  color: #5063F4;

}

.text-capitalize {
  text-transform: capitalize !important;
}

.submit-button {
  margin-top: 20px;

  align-self: flex-end;
}

.action-button {
  padding: 10px 30px;
  border-radius: 20px;
  overflow: hidden;
  display: flex;
  align-items: center;
  gap: 4px;
  cursor: pointer;
  background: #5063F4;
  font-size: 19px;
  font-weight: 700;
  margin-left: 200px;
  text-transform: capitalize;
  color: white;
}

.button-text {
  font-size: 19px;
  font-weight: 700;
  text-transform: capitalize;
  line-height: 1.2;
}
</style>