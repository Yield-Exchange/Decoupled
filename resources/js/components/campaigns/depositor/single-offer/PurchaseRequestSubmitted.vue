<template>
  <Modal :show="show" @isVisible="$emit('visible', $event)" custom-modal-class="purchaseRequestSubmitted"
    modalsize="lg">
    <div
      style="width: 100%; height: 100%; flex-direction: column; justify-content: center; align-items: center; gap: 20px; display: inline-flex">
      <div style="width: 90%;">
        <div style="justify-content: flex-start; align-items: center; gap: 10px; display: inline-flex">
          <img src="/assets/dashboard/icons/Promo (6).svg" />
          <div style="color: #2A2A2A; font-size: 30px; font-weight: 800; line-height: 32px; word-wrap: break-word">
            Purchase
            Request Submitted</div>
        </div>
        <!--            <img src="/assets/dashboard/icons/Mask Group 5 (1).svg" />-->
        <div
          style="width: 100%; height: 66px; text-align: left; color: black; font-size: 18px; font-weight: 500; word-wrap: break-word ;padding-top: 5px; margin-left: 12px;">
          Your request will be processed. Check the Pending deposit page to track progress</div>
      </div>
      <!-- <div style="justify-content: center; align-items: center; gap: 50px; display: inline-flex">
        <div
          style="padding: 10px 30px;border-radius: 20px; overflow: hidden; border: 1px #5063F4 solid; justify-content: center; align-items: center; gap: 4px; display: flex; cursor: pointer"
          @click="navigateTo('/request/offer/summary/' + depositId + '?fromPage=pending-deposits')">
          <div
            style="padding-left: 10px; padding-right: 10px; justify-content: center; align-items: flex-start; gap: 10px; display: flex">
            <div
              style="color: #5063F4; font-size: 16px;  font-weight: 500; text-transform: capitalize; line-height: 20px; word-wrap: break-word">
              View deposit</div>
          </div>
        </div>
        <div
          style="padding: 10px 30px;background: #5063F4; border-radius: 20px; overflow: hidden; justify-content: center; align-items: center; gap: 4px; display: flex; cursor: pointer"
          @click="navigateTo('/pending-deposits')">
          <div
            style="padding-left: 10px; padding-right: 10px; justify-content: center; align-items: flex-start; gap: 10px; display: flex">
            <div
              style="color: white; font-size: 16px;font-weight: 700; text-transform: capitalize; line-height: 20px; word-wrap: break-word">
              View pending deposits</div>
          </div>
        </div>
      </div> -->
    </div>
  </Modal>
</template>
<style>
  .purchaseRequestSubmitted .modal-content {
    max-width: 700px;
    padding-bottom: 20px;
    text-align: left;
  }
</style>
<script>
  import Modal from "../../../shared/Modal";
  export default {
    components: {
      Modal
    },
    props: ['show', 'depositId'],
    methods: {
      navigateTo(link) {
        if (!this.depositId) {
          // console.log("depositId > ",this.depositId);
          return;
        }
        // console.log("depositId <> ",this.depositId);
        window.location.href = link;
      }
    }
  }
</script>