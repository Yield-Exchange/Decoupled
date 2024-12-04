<template>
    <div>
        <div
            style="width: 100%; padding-left: 3px; background: #EFF2FE; justify-content: flex-start; align-items: center; display: inline-flex">
            <div style="justify-content: space-between; display: inline-flex; width: 99%">
                <div class="page-title">
                    <div class="title-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
                            <path
                                d="M5.58813 15.2875C6.67396 10.6585 10.2883 7.04407 14.9174 5.95825C18.2604 5.17408 21.7396 5.17407 25.0826 5.95824C29.7117 7.04407 33.326 10.6585 34.4119 15.2875C35.196 18.6305 35.196 22.1097 34.4119 25.4527C33.326 30.0818 29.7117 33.6962 25.0826 34.782C21.7396 35.5662 18.2604 35.5662 14.9174 34.782C10.2884 33.6962 6.67396 30.0818 5.58813 25.4527C4.80396 22.1097 4.80396 18.6305 5.58813 15.2875Z"
                                fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35" stroke-linecap="round" />
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M22.4972 20.2614C22.4972 20.4824 22.585 20.6944 22.7413 20.8506C22.8976 21.0069 23.1096 21.0947 23.3306 21.0947C23.5516 21.0947 23.7635 21.0069 23.9198 20.8506C24.0761 20.6944 24.1639 20.4824 24.1639 20.2614V18.178H25.6581C26.7714 18.178 27.3289 16.8314 26.5414 16.0447L22.5472 12.048C22.4312 11.9319 22.2933 11.8397 22.1416 11.7769C21.9899 11.714 21.8273 11.6816 21.6631 11.6816C21.4988 11.6816 21.3362 11.714 21.1845 11.7769C21.0328 11.8397 20.895 11.9319 20.7789 12.048L16.7864 16.0447C15.9972 16.8314 16.5556 18.178 17.6689 18.178H19.1639V24.0114C19.1639 24.2324 19.2517 24.4444 19.408 24.6006C19.5643 24.7569 19.7762 24.8447 19.9972 24.8447H21.3197L18.3306 27.833L15.3422 24.8447H16.6639C16.8849 24.8447 17.0969 24.7569 17.2532 24.6006C17.4094 24.4444 17.4972 24.2324 17.4972 24.0114V21.0947C17.4972 20.8737 17.4094 20.6617 17.2532 20.5055C17.0969 20.3492 16.8849 20.2614 16.6639 20.2614C16.4429 20.2614 16.2309 20.3492 16.0746 20.5055C15.9184 20.6617 15.8306 20.8737 15.8306 21.0947V23.178H14.3364C13.2231 23.178 12.6647 24.5239 13.4531 25.3114L17.4464 29.3064C17.5625 29.4225 17.6003 29.5147 17.852 29.5775C18.0037 29.6404 18.1663 29.6728 18.3306 29.6728C18.4948 29.6728 18.6574 29.6404 18.8091 29.5775C18.9608 29.5147 19.0987 29.4225 19.2147 29.3064L23.2089 25.3114C23.9964 24.5239 23.4381 23.178 22.3247 23.178H20.8306V20.678C20.8307 20.6705 20.8307 20.663 20.8306 20.6555V17.3447C20.8306 17.1237 20.7428 16.9117 20.5865 16.7555C20.4302 16.5992 20.2182 16.5114 19.9972 16.5114H18.6747L21.6639 13.5222L24.6522 16.5114H23.3306C23.1096 16.5114 22.8976 16.5992 22.7413 16.7555C22.585 16.9117 22.4972 17.1237 22.4972 17.3447V20.2614Z"
                                fill="#5063F4" />
                        </svg>
                    </div>
                    <div class="text-div">Checklist</div>
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
        <template v-if="documentsectionA">
            <div class="bg-white mt-2" style="padding: 40px !important;">
                <div class="w-100 d-flex justify-content-center flex-column ">
                    <div class="w-100 d-flex justify-content-center ">
                        <p class="m-0 p-0 transfer-header">Required document update</p>
                    </div>
                    <div class="w-100 d-flex justify-content-center ">
                        <img src="/assets/dashboard/images/documentsupload.svg" alt="">
                    </div>

                    <div class="w-100">
                        <p class="transfer-desc text-center">Please Upload the required documentation to complete your
                            registration
                        </p>
                        <div class="w-100">
                            <table class="w-100 transfer-table">
                                <thead>
                                    <tr>
                                        <th class="transfer-table-s2">
                                            Required Document
                                        </th>
                                        <th class="transfer-table-s2">
                                            Last Updated
                                        </th>
                                        <th class="transfer-table-s2">
                                            View
                                        </th>
                                        <th class="transfer-table-s2">
                                            Good to Go?
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            Certificate of Incorporation
                                        </td>
                                        <td>{{ certfile.moddate != '' ? certfile.moddate : "Not yet updated" }}</td>
                                        <td v-if="certfile.moddate != ''">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                @click="viewFile(certfile.filename)" viewBox="0 0 20 20" fill="none">
                                                <path
                                                    d="M10 12.5C11.3807 12.5 12.5 11.3807 12.5 10C12.5 8.61929 11.3807 7.5 10 7.5C8.61929 7.5 7.5 8.61929 7.5 10C7.5 11.3807 8.61929 12.5 10 12.5Z"
                                                    fill="#44E0AA" />
                                                <path
                                                    d="M19.3375 9.7875C18.6024 7.88603 17.3262 6.24164 15.6668 5.05755C14.0073 3.87347 12.0372 3.20161 10 3.125C7.96283 3.20161 5.99275 3.87347 4.33326 5.05755C2.67377 6.24164 1.39761 7.88603 0.662509 9.7875C0.612863 9.92482 0.612863 10.0752 0.662509 10.2125C1.39761 12.114 2.67377 13.7584 4.33326 14.9424C5.99275 16.1265 7.96283 16.7984 10 16.875C12.0372 16.7984 14.0073 16.1265 15.6668 14.9424C17.3262 13.7584 18.6024 12.114 19.3375 10.2125C19.3872 10.0752 19.3872 9.92482 19.3375 9.7875ZM10 14.0625C9.19652 14.0625 8.41108 13.8242 7.743 13.3778C7.07493 12.9315 6.55423 12.297 6.24675 11.5547C5.93927 10.8123 5.85882 9.99549 6.01557 9.20745C6.17232 8.4194 6.55924 7.69553 7.12739 7.12738C7.69554 6.55923 8.41941 6.17231 9.20745 6.01556C9.9955 5.85881 10.8123 5.93926 11.5547 6.24674C12.297 6.55422 12.9315 7.07492 13.3779 7.743C13.8242 8.41107 14.0625 9.19651 14.0625 10C14.0609 11.0769 13.6323 12.1093 12.8708 12.8708C12.1093 13.6323 11.0769 14.0608 10 14.0625Z"
                                                    fill="#44E0AA" />
                                            </svg>
                                        </td>
                                        <td v-else>
                                            <svg xmlns="http://www.w3.org/2000/svg" data-toggle="tooltip"
                                                title="Please upload this document!" width="20" height="20"
                                                viewBox="0 0 20 20" fill="none">
                                                <path
                                                    d="M10 12.5C11.3807 12.5 12.5 11.3807 12.5 10C12.5 8.61929 11.3807 7.5 10 7.5C8.61929 7.5 7.5 8.61929 7.5 10C7.5 11.3807 8.61929 12.5 10 12.5Z"
                                                    fill="#9CA1AA" />
                                                <path
                                                    d="M19.3375 9.7875C18.6024 7.88603 17.3262 6.24164 15.6668 5.05755C14.0073 3.87347 12.0372 3.20161 10 3.125C7.96283 3.20161 5.99275 3.87347 4.33326 5.05755C2.67377 6.24164 1.39761 7.88603 0.662509 9.7875C0.612863 9.92482 0.612863 10.0752 0.662509 10.2125C1.39761 12.114 2.67377 13.7584 4.33326 14.9424C5.99275 16.1265 7.96283 16.7984 10 16.875C12.0372 16.7984 14.0073 16.1265 15.6668 14.9424C17.3262 13.7584 18.6024 12.114 19.3375 10.2125C19.3872 10.0752 19.3872 9.92482 19.3375 9.7875ZM10 14.0625C9.19652 14.0625 8.41108 13.8242 7.743 13.3778C7.07493 12.9315 6.55423 12.297 6.24675 11.5547C5.93927 10.8123 5.85882 9.99549 6.01557 9.20745C6.17232 8.4194 6.55924 7.69553 7.12739 7.12738C7.69554 6.55923 8.41941 6.17231 9.20745 6.01556C9.9955 5.85881 10.8123 5.93926 11.5547 6.24674C12.297 6.55422 12.9315 7.07492 13.3779 7.743C13.8242 8.41107 14.0625 9.19651 14.0625 10C14.0609 11.0769 13.6323 12.1093 12.8708 12.8708C12.1093 13.6323 11.0769 14.0608 10 14.0625Z"
                                                    fill="#9CA1AA" />
                                            </svg>
                                        </td>
                                        <td>
                                            No &nbsp;<input v-model="certificateincorporation" :value="true"
                                                class="my-radio" checked type="radio" name="certificate">
                                            &nbsp;
                                            Yes &nbsp;<input :disabled="modifycert" v-model="certificateincorporation"
                                                :value="false" class="my-radio" type="radio" name="certificate">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Articles of Incorporation
                                        </td>
                                        <td>{{ articlefile.moddate != '' ? articlefile.moddate : "Not yet updated" }}</td>
                                        <td v-if="articlefile.moddate != ''">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                @click="viewFile(articlefile.filename)" viewBox="0 0 20 20" fill="none">
                                                <path
                                                    d="M10 12.5C11.3807 12.5 12.5 11.3807 12.5 10C12.5 8.61929 11.3807 7.5 10 7.5C8.61929 7.5 7.5 8.61929 7.5 10C7.5 11.3807 8.61929 12.5 10 12.5Z"
                                                    fill="#44E0AA" />
                                                <path
                                                    d="M19.3375 9.7875C18.6024 7.88603 17.3262 6.24164 15.6668 5.05755C14.0073 3.87347 12.0372 3.20161 10 3.125C7.96283 3.20161 5.99275 3.87347 4.33326 5.05755C2.67377 6.24164 1.39761 7.88603 0.662509 9.7875C0.612863 9.92482 0.612863 10.0752 0.662509 10.2125C1.39761 12.114 2.67377 13.7584 4.33326 14.9424C5.99275 16.1265 7.96283 16.7984 10 16.875C12.0372 16.7984 14.0073 16.1265 15.6668 14.9424C17.3262 13.7584 18.6024 12.114 19.3375 10.2125C19.3872 10.0752 19.3872 9.92482 19.3375 9.7875ZM10 14.0625C9.19652 14.0625 8.41108 13.8242 7.743 13.3778C7.07493 12.9315 6.55423 12.297 6.24675 11.5547C5.93927 10.8123 5.85882 9.99549 6.01557 9.20745C6.17232 8.4194 6.55924 7.69553 7.12739 7.12738C7.69554 6.55923 8.41941 6.17231 9.20745 6.01556C9.9955 5.85881 10.8123 5.93926 11.5547 6.24674C12.297 6.55422 12.9315 7.07492 13.3779 7.743C13.8242 8.41107 14.0625 9.19651 14.0625 10C14.0609 11.0769 13.6323 12.1093 12.8708 12.8708C12.1093 13.6323 11.0769 14.0608 10 14.0625Z"
                                                    fill="#44E0AA" />
                                            </svg>
                                        </td>
                                        <td v-else>
                                            <svg xmlns="http://www.w3.org/2000/svg" data-toggle="tooltip"
                                                title="Please upload this document!" width="20" height="20"
                                                viewBox="0 0 20 20" fill="none">
                                                <path
                                                    d="M10 12.5C11.3807 12.5 12.5 11.3807 12.5 10C12.5 8.61929 11.3807 7.5 10 7.5C8.61929 7.5 7.5 8.61929 7.5 10C7.5 11.3807 8.61929 12.5 10 12.5Z"
                                                    fill="#9CA1AA" />
                                                <path
                                                    d="M19.3375 9.7875C18.6024 7.88603 17.3262 6.24164 15.6668 5.05755C14.0073 3.87347 12.0372 3.20161 10 3.125C7.96283 3.20161 5.99275 3.87347 4.33326 5.05755C2.67377 6.24164 1.39761 7.88603 0.662509 9.7875C0.612863 9.92482 0.612863 10.0752 0.662509 10.2125C1.39761 12.114 2.67377 13.7584 4.33326 14.9424C5.99275 16.1265 7.96283 16.7984 10 16.875C12.0372 16.7984 14.0073 16.1265 15.6668 14.9424C17.3262 13.7584 18.6024 12.114 19.3375 10.2125C19.3872 10.0752 19.3872 9.92482 19.3375 9.7875ZM10 14.0625C9.19652 14.0625 8.41108 13.8242 7.743 13.3778C7.07493 12.9315 6.55423 12.297 6.24675 11.5547C5.93927 10.8123 5.85882 9.99549 6.01557 9.20745C6.17232 8.4194 6.55924 7.69553 7.12739 7.12738C7.69554 6.55923 8.41941 6.17231 9.20745 6.01556C9.9955 5.85881 10.8123 5.93926 11.5547 6.24674C12.297 6.55422 12.9315 7.07492 13.3779 7.743C13.8242 8.41107 14.0625 9.19651 14.0625 10C14.0609 11.0769 13.6323 12.1093 12.8708 12.8708C12.1093 13.6323 11.0769 14.0608 10 14.0625Z"
                                                    fill="#9CA1AA" />
                                            </svg>
                                        </td>
                                        <td>
                                            No &nbsp;<input v-model="articleofincorporation" :value="true" class="my-radio"
                                                checked type="radio" name="address"> &nbsp;
                                            Yes &nbsp;<input :disabled="modifyarticle" v-model="articleofincorporation"
                                                :value="false" class="my-radio" type="radio" name="address">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>

            <div class="w-100 d-flex justify-content-end mt-4 gap-3">
                <Button type="outlined" @click="goBack">Previous</Button>
                <Button @click="goNext" type="primary">{{ gonext ? "Next" : "Upload Documents" }}</Button>

            </div>
        </template>
        <template v-else>
            <div class="bg-white mt-2" style="padding: 40px !important;">
                <div class="w-100 d-flex justify-content-center flex-column ">
                    <div class="w-100 d-flex justify-content-center ">
                        <p class="m-0 p-0 transfer-header">Updated documents</p>
                    </div>
                    <div class="w-100 d-flex justify-content-center ">
                        <img src="/assets/dashboard/images/documentsupload.svg" alt="">
                    </div>

                    <div class="w-100">
                        <!-- <p class="transfer-desc text-center">Please Upload the required documentation to complete your
                            registration
                        </p> -->
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

            <div class="w-100 d-flex justify-content-end mt-4 gap-3">
                <Button type="outlined" @click="documentsectionA = true">Previous</Button>
                <Button type="primary" @click="updateFactory">Next</Button>
            </div>
        </template>
        <!-- <ActionMessageBox></ActionMessageBox> -->
        <ActionMessage style="width: 600px;" @closedSuccessModal="upload = false" @btnTwoClicked="uploadDocuments"
            icon="/assets/dashboard/icons/upload-file.png" title="Drag & drop files or Browse" :showm="upload" btnOneText=""
            btnTwoText="Submit">

            <CertificateFile @assignFile="assignFile($event, 'certificate')" class="my-2" v-if="certificateincorporation"
                refkey="certificate" filename="Certificate of Incorporation">
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
    </div>
</template>

<script>

import Button from '../../../../shared/Buttons/Button.vue';
import ActionMessage from '../../../../shared/messageboxes/ActionMessageBox.vue';
import FileInput from './FileInput.vue'
// import ArticleFile from './FileInput.vue'
export default {
    props: ['organization_id'],
    components: {
        Button,
        ActionMessage,
        'CertificateFile': FileInput,
        'ArticleFile': FileInput
    },
    mounted() {
        // this.$set(this.files, `incorperation`, null);
        this.getDocuments()
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
            errorMessage: "Please ensure all files are uploaded"
        }
    },
    methods: {
        updateFactory() {
            // console.log("set step clicked")
            this.$emit('updateFactory', 'documents')
        },
        goBack() {
            this.$emit('goBack', 'documents')
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
            if (this.gonext && !this.articleofincorporation && !this.certificateincorporation) {
                this.documentsectionA = false
            } else {
                this.initiateUpload()

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
        async getDocuments() {
            await axios.get(`/organization-data/${this.organization_id}`).then(response => {
                let responsedata = response.data.data
                console.log(responsedata.document.length)
                if (responsedata.document.length != 0) {
                    responsedata.document.forEach(element => {
                        if (element.organization_id == this.organization_id && element.type_id == 1) {
                            this.certificateincorporation = false
                            this.modifycert = false
                            this.certfile.filename = element.file_name
                            this.certfile.moddate = this.formatDateToCustomFormat(element.updated_at)
                        }
                        else if (element.organization_id == this.organization_id && element.type_id == 2) {
                            this.articleofincorporation = false
                            this.modifyarticle = false
                            this.articlefile.filename = element.file_name
                            this.articlefile.moddate = this.formatDateToCustomFormat(element.updated_at)
                        }
                        if (!this.articleofincorporation && !this.certificateincorporation)
                            this.gonext = true
                    });
                }
            }).catch(err => {

            })
        },
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

                formData.append('organization_id', this.organization_id);
                console.log(formData)

                await axios.post('/create-document', formData).then(response => {
                    if (response.data.success) {
                        this.upload = false
                        this.uploadsuccess = true
                        setTimeout(() => {
                            this.uploadsuccess = false
                            this.getDocuments()
                        }, 3000)
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
    font-size: 20px;
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