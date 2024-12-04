<template>
   <div class="w-100">
      <div
         style="width: 100%; height: 70px; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 30px; display: inline-flex">
         <div
            style="width: 100%; padding-left: 3px; background: #EFF2FE; justify-content: flex-start; align-items: center; display: inline-flex">
            <div style="justify-content: space-between; display: inline-flex; width: 99%">
               <div class="page-title">
                  <div class="title-icon">
                     <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
                        <path
                           d="M5.58807 15.2885C6.6739 10.6594 10.2883 7.04505 14.9173 5.95922C18.2604 5.17505 21.7395 5.17505 25.0826 5.95922C29.7116 7.04505 33.326 10.6594 34.4118 15.2885C35.196 18.6315 35.196 22.1107 34.4118 25.4537C33.326 30.0828 29.7116 33.6971 25.0825 34.783C21.7395 35.5671 18.2604 35.5671 14.9173 34.783C10.2883 33.6971 6.6739 30.0828 5.58807 25.4537C4.8039 22.1107 4.8039 18.6315 5.58807 15.2885Z"
                           fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35" stroke-linecap="round" />
                        <path fill-rule="evenodd" clip-rule="evenodd"
                           d="M16.3033 18.3716H24.2433C24.9014 18.3716 25.5325 18.633 25.9978 19.0983C26.4631 19.5636 26.7246 20.1947 26.7246 20.8528C26.7246 21.5109 26.4631 22.142 25.9978 22.6073C25.5325 23.0726 24.9014 23.3341 24.2433 23.3341H16.3033C15.6452 23.3341 15.0141 23.0726 14.5488 22.6073C14.0835 22.142 13.8221 21.5109 13.8221 20.8528C13.8221 20.1947 14.0835 19.5636 14.5488 19.0983C15.0141 18.633 15.6452 18.3716 16.3033 18.3716ZM12.3333 20.8528C12.3333 19.7999 12.7516 18.7901 13.4961 18.0456C14.2406 17.3011 15.2504 16.8828 16.3033 16.8828H24.2433C25.2962 16.8828 26.306 17.3011 27.0505 18.0456C27.795 18.7901 28.2133 19.7999 28.2133 20.8528C28.2133 21.9057 27.795 22.9155 27.0505 23.66C26.306 24.4045 25.2962 24.8228 24.2433 24.8228H16.3033C15.2504 24.8228 14.2406 24.4045 13.4961 23.66C12.7516 22.9155 12.3333 21.9057 12.3333 20.8528ZM16.3033 19.8603C16.0401 19.8603 15.7876 19.9649 15.6015 20.151C15.4154 20.3371 15.3108 20.5896 15.3108 20.8528C15.3108 21.116 15.4154 21.3685 15.6015 21.5546C15.7876 21.7407 16.0401 21.8453 16.3033 21.8453H21.2658C21.529 21.8453 21.7815 21.7407 21.9676 21.5546C22.1537 21.3685 22.2583 21.116 22.2583 20.8528C22.2583 20.5896 22.1537 20.3371 21.9676 20.151C21.7815 19.9649 21.529 19.8603 21.2658 19.8603H16.3033Z"
                           fill="#5063F4" />
                     </svg>
                  </div>
                  <div class="text-div" style="font-size: 28px; line-height: 32px">In Progress</div>
               </div>
            </div>
         </div>
      </div>
      <div style="margin-top: 30px;">
         <FilterBox @clear_filters="getData" @apply_filters="applyFilters" filterType="bank">
         </FilterBox>
         <Table @reloadData="getData()" :columns="columns" no-data-message="" no-data-title="No In Progress Deposits"
            :data="table_data" :has_action='true' :actions='actions' :is_loading="is_fetching_data" />
         <Pagination @click-next-page="getData" v-if="data && data.links" :data="data" />
      </div>
   </div>
</template>
<script>
import Table from '../../../shared/Table.vue';
import Pagination from '../../../shared/Table/Pagination.vue';
import WithDrawOffer from './actions/WithdrawOffer.vue';
import ViewOffer from './actions/ViewOffer.vue';
import FilterBox from '../../shared/FilterBox.vue';
import { formatTimestamp } from '../../../../utils/commonUtils';
export default {
   components: {
      Table,
      Pagination,
      ViewOffer,
      FilterBox
   },
   mounted() {
      this.getData();
   },
   data() {
      let columns = ['Request ID', 'Depositor', 'Province', 'Request Amount', 'Product', 'Duration', 'Rate', 'Rate Held Until', 'Action']
      let actions = [
         {
            name: "View",
            component: ViewOffer
         },
         {
            name: 'Withdraw offer',
            component: WithDrawOffer
         },

      ]
      return {
         columns: columns,
         is_loading: true,
         is_fetching_data: false,
         actions: actions,
         table_data: [],
         data: null
      }
   },
   methods: {
      getData(url) {
         this.is_fetching_data = true;
         let getpath = url ? url : "/get-inprogress-data";
         let inProgressDeposits = []
         axios.get(getpath).then(response => {
            let data = response.data.data;
            this.data = response.data
            this.is_fetching_data = false
            if (data.length > 0) {
               data.forEach(deposit => {
                  let inprogressdeposit = [
                     deposit.offer_id_encoded,
                     deposit.invited.deposit_request.reference_no,
                     deposit.depositor,
                     deposit.province,
                     deposit.invited.deposit_request.currency + " " + this.addCommas(deposit.invited.deposit_request.amount),
                     deposit.invited.deposit_request.product_name,
                     deposit.invited.deposit_request.term_length_type == "HISA" ? "-" : deposit.invited.deposit_request.term_length + " " + this.capitalize(deposit.invited.deposit_request.term_length_type),
                     parseFloat(deposit.interest_rate_offer).toFixed(2) + " %",
                     deposit.rate_held_until ? formatTimestamp(deposit.rate_held_until, false) :"-"
                  ]
                  inProgressDeposits.push(inprogressdeposit)

               });

               this.table_data = inProgressDeposits
            } else {
               this.table_data = []
            }
         }).catch(error => {
            console.log(error)
         })

      },
      capitalize(thestring) {
         if (thestring !== undefined) {
            return thestring
               .toLowerCase()
               .split(' ')
               .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
               .join(' ');
         }
      },
      formatDateToCustomFormat(inputDate) {
         const options = { month: 'short', day: '2-digit', year: 'numeric' };
         const date = new Date(inputDate);
         const formattedDate = date.toLocaleDateString('en-US', options);
         return formattedDate;
      },
      addCommas(newvalue) {
         return newvalue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      },
      applyFilters(value) {
         let url = '/get-inprogress-data?'
         this.getData(url + value)
      }
   },
}

</script>
<style></style>
