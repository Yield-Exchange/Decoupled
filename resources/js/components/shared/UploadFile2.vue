<template>
  <div style="width: 100%; min-height: 50px; padding-left: 10px;
     background: white; box-shadow: 0px 2px 6px rgba(80, 99, 244, 0.15); 
     justify-content: flex-start; align-items: center; gap: 10px; display: inline-flex; align-content:center;">

    <div @click="openFileInput" class="custom-input">
      <input type="file" @change="handleFileUpload" ref="fileInput" style="display: none;" />
      <span v-if="fileName" class="file-name">{{ fileName }}</span>
    </div>
    <div class="button-group">
      <img @click="viewFile" src="/assets/dashboard/icons/viewunploaded.svg" />
      &nbsp; &nbsp;
      <img @click="downloadFile" src="/assets/dashboard/icons/download.svg" />
      &nbsp; &nbsp;
      <img @click="deleteFile(filelabel)" src="/assets/dashboard/icons/delete.svg" />
      &nbsp; &nbsp;
    </div>
    <b-modal v-model="modalVisible" title="Preview" hide-footer :no-close-on-backdrop="true" centered
      :no-close-on-esc="true">
      <div class="pdf-container">
        <embed :src="pdfUrl" type="application/pdf" width="100%" height="600px" style="background-color: white;">
      </div>
    </b-modal>
    <GeneralNoInteractionError :size="errorModalSize" @closedModal="closeErrorModal()" :title="errorModalTitle"
      :show="showErrorModal" :message="errorModalMessage" />
  </div>
  <!-- <div style="display:flex; flex-direction:row;">
    <div @click="openFileInput" class="custom-input">
      <input type="file" @change="handleFileUpload" ref="fileInput" style="display: none;" />
      <span v-if="fileName" class="file-name">{{ fileName }}</span>
    </div>
    <div>
      <div v-if="fileUploaded" class="button-group">
        <b-button @click="viewFile" variant="success">View</b-button> &nbsp; &nbsp; &nbsp;
        <b-button @click="downloadFile" variant="success">Download</b-button>
      </div>
    </div>
  </div> -->
</template>

<script>

  import GeneralNoInteractionError from "../shared/messageboxes/GeneralNoInteractionError.vue";
  export default {
    props: ['defaultfile', 'useruploadname', 'filelabel'],
    components: {
      GeneralNoInteractionError,
    },
    data() {
      console.log(this.defaultfile, "this.defaultfile");
      return {
        errorModalTitle: "",
        errorModalMessage: "",
        showErrorModal: false,
        errorModalSize: "md",
        fileName: (this.useruploadname != null) ? this.useruploadname : 'Select File',
        fileUploaded: false,
        uploadedFile: null,
        pdfVisible: false,
        pdfUrl: (this.defaultfile === null) ? "" : '/' + this.defaultfile,
        modalVisible: false,

      };
    },
    methods: {
      closeErrorModal() {
        this.showErrorModal = false;
      },
      openFileInput() {
        this.$refs.fileInput.click();
      },
      handleFileUpload(event) {
        const file = event.target.files[0];
        this.uploadFile(file);
      },
      handleDrop(event) {
        event.preventDefault();
        const file = event.dataTransfer.files[0];
        this.uploadFile(file);
      },
      uploadFile(file) {
        if (file instanceof File) {
          this.$emit('fileError', "");
          this.fileName = file.name;
          this.fileUploaded = true;
          this.uploadedFile = file;
          if (this.isPDF()) {
            this.pdfVisible = true;
            this.pdfUrl = URL.createObjectURL(this.uploadedFile);

          }
          this.$emit('fileSelected', file);
          this.$emit('fileError', "");
          if (file.size <= 50 * 1024 * 1024) {
            this.$emit('fileError', "");
            if (file.type === 'application/pdf') {

            } else {
              this.$emit('fileError', "Invalid File Format.Allowed file type is PDF.");
            }
          } else {
            this.$emit('fileError', "Too large file.The maximum allowed size is 50 MB.");
          }
        } else {
          if (this.isPDF()) {
            this.pdfVisible = true;
            this.pdfUrl = URL.createObjectURL(this.uploadedFile);

          } else {
            this.uploadedFile = null;
            this.pdfVisible = false;
            this.pdfUrl = "";
            this.$emit('fileError', "Invalid File Format.Allowed file type is PDF.");
          }

        }
      },
      deleteFile(filelabel) {
        if (this.fileName) {

        }
        this.uploadedFile = null;
        this.pdfUrl = "";
        this.fileName = "Select a File";
        this.$emit("deleteFile", this.filelabel);
      },
      downloadFile() {
        if (this.uploadedFile !== null) {
          if (this.uploadedFile instanceof File) {
            if (!this.isPDF()) {
              this.errorModalTitle = "Document Preview.";
              this.errorModalMessage = "Please upload a valid document.";
              this.showErrorModal = true;
              return;

            }
            const fileUrl = URL.createObjectURL(this.uploadedFile);
            const link = document.createElement('a');
            link.href = fileUrl;
            link.setAttribute('download', this.fileName);
            document.body.appendChild(link);
            link.click();
          } else {

            if (this.defaultfile != null && this.defaultfile != undefined) {
              const link = document.createElement('a');
              link.href = '/' + this.defaultfile;
              link.setAttribute('download', this.useruploadname);
              document.body.appendChild(link);
              link.click();
            } else {
              this.errorModalTitle = "Downloading Document.";
              this.errorModalMessage = "Please Upload a document first by clicking select file.34";
              this.showErrorModal = true;
            }

          }
        } else {

          if (this.pdfUrl != null && this.pdfUrl != "") {
            const link = document.createElement('a');
            link.href = '/' + this.defaultfile;
            link.setAttribute('download', this.useruploadname);
            document.body.appendChild(link);
            link.click();
          } else {
            this.errorModalTitle = "Document Preview.";
            this.errorModalMessage = "Please upload a valid document.dd";
            this.showErrorModal = true;
          }

        }

      },
      closeModal() {
        this.modalVisible = false;
      },
      viewFile() {
        if (this.pdfUrl === "") {
          this.errorModalTitle = "Document Preview.";
          this.errorModalMessage = "Please upload a valid document.";
          this.showErrorModal = true;
        } else {
          this.modalVisible = true;
        }

      },
      isPDF() {
        if (this.uploadedFile.type === "application/pdf" || this.uploadedFile.type === "pdf") {
          return true;
        } else {
          return false;
        }

      }
    }
  };
</script>

<style scoped>
  .custom-input {
    background-color: white;
    border-radius: 5px;
    cursor: pointer;
    width: 85%;
    height: 100%;
    display: flex;
    align-items: center;
    border: none;

    /* Make the div clickable */
  }

  .file-name {
    margin-left: 10px;
  }

  .button-group {
    display: flex;
    flex-direction: row;
    justify-content: flex-end;
    align-items: center;
    padding-left: 10px;
    /* Add margin-top for spacing */
  }
</style>
