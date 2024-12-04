<template>
    <div>
        <template>
            <div class="bg-white mt-2" style="padding: 40px !important;">
                <div class="w-100 d-flex justify-content-center flex-column ">
                    <div class="w-100 d-flex justify-content-center ">
                        <p class="m-0 p-0 transfer-header">Upload documents</p>
                    </div>
                    <div class="w-100 d-flex justify-content-center ">
                        <img src="/assets/dashboard/images/documentsupload.svg" alt="">
                    </div>

                    <div class="w-100">
                        <p class="transfer-desc text-center">Upload once for all your investments
                        </p>
                        <div class="w-100">
                            <div class="d-flex justify-content-center gap-3 ">
                                <div class="d-flex justify-content-center align-items-center gap-2 uploaded-document">
                                    <p class="p-0 m-0">1. Articles of Incorporation</p>
                                    <img src="/assets/dashboard/icons/file-uploaded.png" alt="">
                                    <!-- <img src="/ass" alt="" srcset=""> -->
                                </div>
                                <div class="d-flex justify-content-center align-items-center gap-2 uploaded-document">
                                    <p class="p-0 m-0">2. Certificate of Incorporation</p>
                                    <img src="/assets/dashboard/icons/file-uploaded.png" alt="">
                                    <!-- <img src="/ass" alt="" srcset=""> -->
                                </div>


                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <div class="w-100 d-flex justify-content-between mt-4 gap-3">
                <Button :previous="true" @action="goBack" title="Previous" />


                <div class="d-flex justify-content-end gap-3">
                    <Button :outline="true" @action="goNext" title="Skip" />
                    <Button @action="upload = true" title="Upload Documents" />
                </div>
            </div>
        </template>
        <!-- <ActionMessageBox></ActionMessageBox> -->
        <ActionMessage style="width: 600px;" @closedSuccessModal="upload = false" @btnTwoClicked="uploadDocuments"
            icon="/assets/dashboard/icons/upload-file.png" title="Drag & drop files or Browse" :showm="upload"
            btnOneText="" btnTwoText="Submit">

            <CertificateFile @assignFile="assignFile($event, 'certificate')" class="my-2"
                v-if="certificateincorporation" refkey="certificate" filename="Certificate of Incorporation">
            </CertificateFile>

            <ArticleFile @assignFile="assignFile($event, 'articles')" class="my-2" v-if="articleofincorporation"
                refkey="articles" filename="Articles of Incorporation"></ArticleFile>
            <span class="text-danger" v-if="error">{{ errorMessage }}</span>
        </ActionMessage>
        <ActionMessage style=" width: 600px;" @closedSuccessModal="uploadsuccess = false"
            icon="/assets/dashboard/icons/success_promo.svg" title="Files Uploaded Successfully" :showm="uploadsuccess"
            btnOneText="" btnTwoText="">
        </ActionMessage>
        <ActionMessage size="lg" style=" width: 600px;" @closedSuccessModal="hideFile"
            icon="/assets/dashboard/icons/success_promo.svg" title="Uploaded Document" :showm="showfile" btnOneText=""
            btnTwoText="">
            <!-- <h1>Hello Worl</h1> -->
            <div style="height: 500px;">
                <embed :src="`/${file}#toolbar=0`" width="100%" type="application/pdf" height="100%" />
            </div>
        </ActionMessage>
        <ActionMessage style=" width: 600px;" @closedSuccessModal="redirect = false"
            icon="/assets/dashboard/icons/success_promo.svg" title="Process completed Successfully" :showm="redirect"
            btnOneText="" btnTwoText="" message="We are now redirecting you to the dashboard">
        </ActionMessage>
    </div>
</template>

<script>

import Button from '../shared/CustomSubmit'
import ActionMessage from '../../../shared/messageboxes/ActionMessageBox.vue';

import PopUpModals from '../shared/PopUpModal.vue';
import FileInput from '../shared/FileInput.vue'
// import ArticleFile from './FileInput.vue'
export default {
    components: {
        Button,
        ActionMessage,
        'CertificateFile': FileInput,
        'ArticleFile': FileInput
    },
    mounted() {
        this.$store.dispatch('setStageTitle', 'Complete your Yield Exchange Profile!')
        this.$store.dispatch('setProgress', 90)
    },
    data() {
        return {
            documentsectionA: true,
            upload: false,
            uploadsuccess: false,
            articleofincorporation: true,
            certificateincorporation: true,
            modifycert: true,
            showfile: false,
            certfile: {
                filename: '',
                moddate: '',
            },
            articlefile: {
                filename: '',
                moddate: '',
            },
            organization_id: 12,
            modifyarticle: true,
            documentToUpload: [],
            articlemodel: null,
            certificatemodel: null,
            insertedfile: null,
            gonext: false,
            file: null,
            // organization_id: 11,
            files: [],
            error: false,
            redirect: false,
            errorMessage: "Please ensure all files are uploaded"
        }
    },
    computed: {
        getOrgDetails() {
            return this.$store.getters.getOrgDetails
        },
        getIsConference() {
            return this.$store.getters.getIsConference;
        },
    },
    methods: {

        goBack() {
            this.$store.dispatch('setCurrentStep', 'individualandentitysummary')
        },
        viewFile(file) {
            console.log(file)
            this.file = file
            this.showfile = true
        },
        hideFile() {
            this.file = null
            this.showfile = false
        },
        goNext() {
            if (this.getIsConference) {
                this.redirect = true
                setTimeout(() => {
                    this.redirect = false
                    window.location.href = "/dashboard"
                }, 3000)
            } else {
                this.$store.dispatch('setCurrentStep', 'aftertermswaitingbay')
            }
        },
        assignFile(file, doctype) {
            if (doctype === "certificate") {

                this.certificatemodel = file
            }
            if (doctype === "articles") {

                this.articlemodel = file
            }
            // console.log("File Name", file)

        },
        getIsConference() {
            return this.$store.getters.getIsConference;
        },
        // async getDocuments() {
        //     await axios.get(`/organization-data/${this.organization_id}`).then(response => {
        //         let responsedata = response.data.data
        //         console.log(responsedata.document.length)
        //         if (responsedata.document.length != 0) {
        //             responsedata.document.forEach(element => {
        //                 if (element.organization_id == this.organization_id && element.type_id == 1) {
        //                     this.certificateincorporation = false
        //                     this.modifycert = false
        //                     this.certfile.filename = element.file_name
        //                     this.certfile.moddate = this.formatDateToCustomFormat(element.updated_at)
        //                 }
        //                 else if (element.organization_id == this.organization_id && element.type_id == 2) {
        //                     this.articleofincorporation = false
        //                     this.modifyarticle = false
        //                     this.articlefile.filename = element.file_name
        //                     this.articlefile.moddate = this.formatDateToCustomFormat(element.updated_at)
        //                 }
        //                 if (!this.articleofincorporation && !this.certificateincorporation)
        //                     this.gonext = true
        //             });
        //         }
        //     }).catch(err => {

        //     })
        // },
        formatDateToCustomFormat(inputDate) {
            // Create a Date object from the inputDate parameter
            const options = { month: 'short', day: '2-digit', year: 'numeric' };
            const date = new Date(inputDate);
            const formattedDate = date.toLocaleDateString('en-US', options);

            return formattedDate;
        },
        async uploadDocuments() {
            this.error = false
            if (this.certificateincorporation && this.certificatemodel == null) {
                this.errorMessage = "Please ensure all files are uploaded"
                this.error = true
            }
            if (this.articleofincorporation && this.articlemodel == null) {
                this.errorMessage = "Please ensure all files are uploaded"
                this.error = true
            }
            if (this.certificatemodel || this.articlemodel && !this.error) {
                const formData = new FormData();

                if (this.certificatemodel) {
                    formData.append('files[]', this.certificatemodel[0]);
                    formData.append('types_id[]', 1); // Assuming type_id for certificate is 1
                }

                if (this.articlemodel) {
                    formData.append('files[]', this.articlemodel[0]);
                    formData.append('types_id[]', 2); // Assuming type_id for article is 2
                }

                formData.append('organization_id', this.getOrgDetails.organization_id);
                console.log(formData)

                await axios.post('/create-document-on-signup', formData).then(response => {
                    if (response.data.success) {
                        this.upload = false
                        this.uploadsuccess = true
                        setTimeout(() => {
                            this.uploadsuccess = false
                            this.goNext()
                            // this.getDocuments()
                        }, 6000)
                    } else {
                        this.error = true
                        this.errorMessage = "Oops, files not uploaded please try again"
                    }
                }).catch(error => {
                    this.error = true
                    this.errorMessage = "Oops, files not uploaded please try again"
                    // Handle error
                })
            }
        },

        fileUploaded() {

        },
        initiateUpload() {
            this.documentToUpload = []
            if (this.articleofincorporation) {
                this.documentToUpload.push({ 'filename': "Articles of Incorporation", 'model': 'articlemodel', 'id': 2 })
            }
            if (this.certificateincorporation)
                this.documentToUpload.push({ 'filename': "Certificate of Incorporation", 'model': 'certificate', 'id': 1 })
            if (!this.certificateincorporation && !this.articleofincorporation) {
                console.log('you have not selected any file')
            } else {
                this.upload = true
            }
        }
    },
    watch: {
        certificateincorporation() {
            console.log(this.certificateincorporation)
        }
    }

}
</script>


<style scoped>
.transfer-header {
    color: #5063F4;
    font-feature-settings: 'clig' off, 'liga' off;
    font-family: Montserrat !important;
    font-size: 30px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 81.25% */
    /* text-transform: capitalize; */
}

.transfer-desc {
    color: #252525;
    font-feature-settings: 'clig' off, 'liga' off;

    /* Yield Exchange Text Styles/Drop Down Active Tiltes */
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 600;
    line-height: 26px;
    /* 130% */
    /* text-transform: capitalize; */
}

.uploaded-document {
    padding: 15px 10px;
    background: #FFF;
    box-shadow: 0px 2px 6px 0px rgba(80, 99, 244, 0.15);
}

.uploaded-document p {
    color: var(--Yield-Exchange-Pallette-Yield-Exchange-Black, var(--Yield-Exchange-Colors-Darks, #252525));

    /* Yield Exchange Text Styles/Body Text */
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 500;
    line-height: normal;
}

thead {
    background-color: #EFF2FE;
}


.transfer-table-s2 {
    color: #252525;
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 600;
    line-height: normal;
}

tbody tr td {
    color: #252525;
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 500;
    line-height: normal;
}

thead tr th,
tbody tr td {
    padding: 10px 10px !important;
}

tr td,
tr th {
    border-top: none !important;
    border-bottom: 1px solid #D9D9D9 !important;
}

tr {
    padding: 5px 20px;
}

/* tr:nth-child(odd) {
    background-color: #F4F5F6;
}

tr:nth-child(even) {
    background-color: #EFF2FE;

} */

input[type=radio] {
    appearance: none;
    background-color: #fff;
    width: 20px;
    height: 20px;
    border: 1px solid #ccc;
    border-radius: 6px;
    display: inline-grid;
    place-content: center;
}

input[type=radio]::before {
    content: "";
    width: 10px;
    height: 10px;
    transform: scale(0);
    transform-origin: bottom left;
    background-color: #fff;
    clip-path: polygon(13% 50%, 34% 66%, 81% 2%, 100% 18%, 39% 100%, 0 71%);
}

input[type=radio]:checked::before {
    transform: scale(1);
}

input[type=radio]:checked {
    background-color: #5063F4;
    border: 2px solid #5063F4;
}
</style>