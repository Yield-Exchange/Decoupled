<template>
  <Modal :show="show" @isVisible="$emit('visible', $event)" custom-modal-class="review-transaction-details-modal"
    modalsize="lg">
    <div class="review-transaction-container">
      <div class="header">
        <img src="/assets/dashboard/icons/Setting__3.svg" />
        <div class="title">Review Transaction Details</div>
      </div>

      <div class="info">
        <div class="info-section">
          <div class="info-label" style="white-space: nowrap;">Financial Institution</div>
          <div class="info-value">{{ datum?.campaign?.organization?.name }}</div>
        </div>
        <div class="info-section">
          <div class="info-label">Product Type</div>
          <div class="info-value">{{ datum?.description }}</div>
        </div>

        <div class="info-section">
          <div class="info-label">GIC Rate</div>
          <div class="info-value">{{ datum?.rate }}%</div>
        </div>

        <div class="info-section">
          <div class="info-label">Term Length</div>
          <div class="info-value text-capitalize">{{ datum?.term_length + ' ' + datum?.term_length_type }}</div>
        </div>


        <div class="info-section">
          <div class="info-label">Locked In Period </div>
          <div class="info-value text-capitalize">{{ datum?.lockout_period ? datum?.lockout_period + ' ' +
            datum?.term_length_type : '-'
            }}</div>
        </div>

        <div class="info-section">
          <div class="info-label">Compounding Frequency</div>
          <div class="info-value text-capitalize">{{ capitalize(datum?.compound_frequency) }}</div>
        </div>
        <div class="info-section">
          <div class="info-label">Interest Paid</div>
          <div class="info-value text-capitalize">{{ capitalize(datum?.interest_paid) }}</div>
        </div>


        <div class="info-section">
          <div class="info-label">Investment Amount</div>
          <div class="info-value">CAD {{ investment_amount }}</div>
        </div>

        <div class="info-section">
          <div class="info-label">Interest Earned</div>
          <div class="info-value">CAD {{ addCommas(calaculateInterestRate(datum?.term_length_type,
            sanitizeAmount(investment_amount),
            datum?.term_length,sanitizeAmount(datum?.rate))) }}</div>
        </div>
      </div>

      <div class="submit-button">
        <div class="action-button" @click="submit">
          <div class="button-text">Submit</div>
        </div>
      </div>
    </div>
  </Modal>
</template>

<style>
  .review-transaction-details-modal .modal-content {
    max-width: 750px !important;
    align-items: center;
    justify-content: center;
  }

  .review-transaction-container {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    padding: 20px;
    font-family: sans-serif;

  }

  .header {
    display: flex;
    align-items: center;
    gap: 5px;
    justify-content: space-between;
    /* margin-top: 20px; */
    font-size: 20px;
    font-weight: 800;
    color: black;
    margin-bottom: 30px;


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
    margin-right: -17px;
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

<script>
  import Modal from "../../../shared/Modal";

  export default {
    components: {
      Modal
    },
    props: ['show', 'investment_amount', 'datum', 'submitted'],
    methods: {
      sanitizeAmount(val) {
        try {
          // return parseFloat(val.replace(",", "", val).replace(" ", "", val));
          return parseFloat(val.replace(/,/g, "").replace(/ /g, ""));
        } catch (e) {
          return val;
        }
      },
      calaculateInterestRate(term_length_type, amount_offered, term_length, rate) {
        console.log(term_length_type + "," + amount_offered + "," + term_length + "," + rate);
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
        this.$emit('submitConfirmed', true);
      },
      addCommas(vaue) {
        return vaue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
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
    }
  }
</script>