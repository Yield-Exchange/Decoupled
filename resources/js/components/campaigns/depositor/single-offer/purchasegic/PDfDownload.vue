<template>
    <div>

        <!-- <vue-html2pdf :show-layout="false" :float-layout="true" :enable-download="true" :preview-modal="true"
            :paginate-elements-by-height="1400" :filename="filename" :pdf-quality="2" :manual-pagination="false"
            pdf-format="a4" pdf-orientation="portrait" pdf-content-width="800px" ref="html2Pdf">
            <section slot="pdf-content">
                <FIDetails :bankDetails="bankDetails" :depositorDetails="depositorDetails"></FIDetails>
            </section>
        </vue-html2pdf> -->
        <!-- <button @click="generateReport">Download</button> -->

        <ActionMessageModal @close="fail = false" :show="fail" width="600" title="Document Download error"
            icon="signup/danger.svg" primarybuttontext="" outlinedbuttontext=""
            :message="`${bankDetails.bankName} has not shared their funds transfer documents yet.`"
            @outlinedClicked="success = false" @primaryClicked="">
        </ActionMessageModal>
        <ActionMessage size="lg" style=" width: 600px;" @closedSuccessModal="showfile = false"
            icon="/assets/dashboard/icons/success_promo.svg" :title="`${bankDetails.bankName} wire tranfer documents`"
            :showm="showfile" btnOneText="" btnTwoText="">
            <!-- <h1>Hello Worl</h1> -->
            <div style="height: 500px;">
                <embed :src="`/${file}#toolbar=0`" width="100%" type="application/pdf" height="100%" />
            </div>
        </ActionMessage>
        <a ref="downloadLink" style="display: none;"></a>
        <!-- <PDfDownload ref="downloadpdf" :bankDetails="bankdetails" :depositorDetails="depositdetails" /> -->


    </div>
</template>

<script>
import VueHtml2pdf from 'vue-html2pdf'
import ActionMessage from '../../../../shared/messageboxes/ActionMessageBox.vue'
import ActionMessageModal from '../../../../auth/signup/shared/ActionMessageModal.vue';


import FIDetails from './FIDetails.vue'
export default {
    props: ['bankDetails', 'depositorDetails'],
    components: {
        VueHtml2pdf,
        FIDetails,
        ActionMessage,
        ActionMessageModal
    },
    data() {
        return {
            fail: false,
            showfile: false,
            file: null
        }
    },
    computed: {
        filename() {
            return this.bankDetails.bankName + " " + this.depositorDetails.requestID + " funds transfer "
        }
    },
    methods: {
        async downloadPdf() {
            // this.$refs.html2Pdf.generatePdf()
            await axios.get(`/get-wire-tranfer-details/${this.bankDetails.fiorganization_id}`).then(
                response => {
                    if (response.data.success) {
                        this.file = response.data.data
                        this.showfile = true

                        const downloadLink = this.$refs.downloadLink;
                        // Set href attribute to the file URL
                        downloadLink.href = `/${this.file}`;

                        // Set download attribute to force download
                        downloadLink.download = '';

                        // Trigger click event on the hidden <a> element
                        downloadLink.click();
                    } else {
                        this.fail = true
                    }
                }
            ).catch(err => {
                console.log(err);
            })
        },
    }
}
</script>


<style scoped></style>