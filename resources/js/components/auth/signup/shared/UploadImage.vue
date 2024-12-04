<template>
    <div>
        <!-- <a class="btn" @click="toggleShow">set avatar</a> -->
        <my-upload ref="uploadimage" field="img" @crop-success="cropSuccess" @crop-upload-success="cropUploadSuccess"
            @crop-upload-fail="cropUploadFail" v-model="show" langType="en" :langExt="langText" :width="400"
            :height="400" url="" :params="params" :headers="headers" img-format="png"></my-upload>
        <!-- <img :src="imgDataUrl"> -->
    </div>
</template>



<script>
import myUpload from 'vue-image-crop-upload/upload-2.vue';
export default {
    props: ['uploadimage', 'isdeleted'],
    mounted() {
        this.createNode()
    },
    data() {
        return {
            show: false,
            params: {
                token: '123456798',
                name: 'avatar'
            },
            headers: {
                smail: '*_~'
            },
            imgDataUrl: '', // the datebase64 url of created image,
            langText: {
                hint: 'Click or drag the file here to upload',
                loading: 'Uploading…',
                noSupported: 'Browser is not supported, please use IE10+ or other browsers',
                success: 'Upload success',
                fail: 'Upload failed',
                preview: 'Preview',
                btn: {
                    off: 'Cancel',
                    close: 'Close',
                    back: 'Cancel',
                    save: 'Apply'
                },
                error: {
                    onlyImg: 'Image only',
                    outOfSize: 'Image exceeds size limit: ',
                    lowestPx: 'Image\'s size is too low. Expected at least: '
                }

            },
        }
    },
    components: {
        'my-upload': myUpload
    },
    methods: {
        toggleShow() {
            this.show = !this.show;
        },
        createNode() {
            // Create a new paragraph element
            const paragraph = document.createElement('p');
            // Set text content
            paragraph.textContent = 'Crop logo to 400 x 400 px';
            // Add CSS class
            paragraph.classList.add('my-upload-title');
            // Prepend the paragraph to an existing element with class 'vicp-wrap'
            this.$el.querySelector('.vicp-wrap').prepend(paragraph);
        },
        /**
         * crop success
         *
         * [param] imgDataUrl
         * [param] field
         */
        cropSuccess(imgDataUrl, field) {
            // console.log('-------- crop success --------');
            this.imgDataUrl = imgDataUrl;
            this.$emit('seeImage', this.imgDataUrl);
        },
        /**
         * upload success
         *
         * [param] jsonData  server api return data, already json encode
         * [param] field
         */
        cropUploadSuccess(jsonData, field) {
            // console.log('-------- upload success --------');
            // console.log(jsonData);
            // console.log('field: ' + field);
        },
        /**
         * upload fail
         *
         * [param] status    server api return error status, like 500
         * [param] field
         */
        cropUploadFail(status, field) {
            // console.log('-------- upload fail --------');
            // console.log(status);
            // console.log('field: ' + field);
        }
    },
    watch: {
        imgDataUrl() {
            // this.$emit('seeImage', this.imgDataUrl);
            // console.log(this.imgDataUrl)
        },
        uploadimage(newValue, oldVal) {
            if (newValue) {
                this.show = true
            }
        },
        isdeleted() {
            if (this.isdeleted) {
                this.$refs.uploadimage.step = 1
            }
        },
        show() {
            if (!this.show) {
                this.$emit("changeStatus", false)
            }
        }
    }

}

</script>

<style>
.vicp-close {
    display: none;
}

.my-upload-title {
    color: #5063F4;
    font-feature-settings: 'clig' off, 'liga' off;
    font-family: Montserrat;
    font-size: 22px;
    font-style: normal;
    font-weight: 700;
    line-height: 26px;
    /* 118.182% */
    /* text-transform: capitalize; */
}

.vicp-wrap {
    width: 500px !important;
    height: 400px !important;
    padding: 30px;
}

.vicp-operate a:nth-child(1) {
    display: flex !important;
    padding: 10px 30px;
    justify-content: center;
    align-items: center;
    border-radius: 20px !important;
    border: 2px solid #5063F4 !important;
    color: #5063F4 !important;
}

.vicp-operate a:nth-child(2) {
    display: flex !important;
    padding: 10px 30px;
    justify-content: center;
    align-items: center;
    border-radius: 20px !important;
    background-color: #5063F4 !important;
    color: #fff !important;
}

.vicp-crop-right {
    display: none;
}

.vicp-img-container {
    /* width: 378px !important;
    height: 378px !important; */
    /* flex-shrink: 0; */
}

.vicp-step2 {
    display: flex;
    justify-content: center;
    width: 100%;
    height: 100%;
}
</style>