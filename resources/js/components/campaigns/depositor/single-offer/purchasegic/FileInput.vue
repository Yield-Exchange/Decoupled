<template>
    <div class="main">
        <div class="dropzone-container d-flex" @dragover="dragover" @dragleave="dragleave" @drop="drop">

            <label :for="refkey" class="file-label w-100">
                <div v-if="isDragging">Release to drop files here.</div>
                <div v-else class="label-file d-flex justify-content-between gap-2 w-100">
                    <div class=" d-flex justify-content-start gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M3.81211 2.55087C2.93311 3.42912 2.93311 4.84363 2.93311 7.67188V10.6719C2.93311 13.5001 2.93311 14.9146 3.81211 15.7929C4.69036 16.6719 6.10486 16.6719 8.93311 16.6719H10.4331C13.2614 16.6719 14.6759 16.6719 15.5541 15.7929C16.4331 14.9146 16.4331 13.5001 16.4331 10.6719V7.67188C16.4331 4.84363 16.4331 3.42912 15.5541 2.55087C14.6759 1.67187 13.2614 1.67188 10.4331 1.67188H8.93311C6.10486 1.67188 4.69036 1.67187 3.81211 2.55087ZM6.68311 7.10938C6.53392 7.10938 6.39085 7.16864 6.28536 7.27413C6.17987 7.37962 6.12061 7.52269 6.12061 7.67188C6.12061 7.82106 6.17987 7.96413 6.28536 8.06962C6.39085 8.17511 6.53392 8.23438 6.68311 8.23438H12.6831C12.8323 8.23438 12.9754 8.17511 13.0809 8.06962C13.1863 7.96413 13.2456 7.82106 13.2456 7.67188C13.2456 7.52269 13.1863 7.37962 13.0809 7.27413C12.9754 7.16864 12.8323 7.10938 12.6831 7.10938H6.68311ZM6.68311 10.1094C6.53392 10.1094 6.39085 10.1686 6.28536 10.2741C6.17987 10.3796 6.12061 10.5227 6.12061 10.6719C6.12061 10.8211 6.17987 10.9641 6.28536 11.0696C6.39085 11.1751 6.53392 11.2344 6.68311 11.2344H10.4331C10.5823 11.2344 10.7254 11.1751 10.8309 11.0696C10.9363 10.9641 10.9956 10.8211 10.9956 10.6719C10.9956 10.5227 10.9363 10.3796 10.8309 10.2741C10.7254 10.1686 10.5823 10.1094 10.4331 10.1094H6.68311Z"
                                fill="#5063F4" />
                        </svg>
                        <p class="m-0 p-0 text-align-start">{{ filename }} <span> <br><b>*Supported formats:.pdf</b> </span></p>
                    </div>

                    <p class="select-file m-0 p-0">Select File</p>
                </div>
                <div class="d-flex align-items-center gap-2 pl-2" v-for="(file, index)  in files" :key="index">
                    <p class="m-0 p-0">
                        {{ file.name }}
                    </p>
                    <svg style="cursor: pointer;" @click="remove(index)" xmlns="http://www.w3.org/2000/svg" width="20"
                        height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M15 5L5 15M5 5L15 15" stroke="#FF2E2E" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>

                </div>
            </label>
            <input type="file" :name="refkey" :id="refkey" class="hidden-input" @change="onChange" ref="file"
                accept=".pdf" />
        </div>
    </div>
</template>
  
<script>
export default {
    props: ['filename', 'refkey'],
    data() {
        return {
            isDragging: false,
            files: []
        };
    },
    methods: {
        onChange() {
            this.files = []
            this.files.push(...this.$refs.file.files);
            if (this.files.length >= 1)
                this.$emit('assignFile', this.files)
            else
                this.$emit('assignFile', null)
            // console.log(this.filename)

        },
        dragover(e) {
            e.preventDefault();
            // this.isDragging = true;
        },
        dragleave() {
            this.isDragging = false;
        },
        drop(e) {
            e.preventDefault();
            this.$refs.file.files = e.dataTransfer.files;
            this.onChange();
            this.isDragging = false;
        },
        remove(i) {
            this.files = []
            this.$emit('assignFile', null)
            // this.files.splice(i, 1);
        },
    },
};
</script>
<style scoped>
.main {
    display: flex;
    flex-grow: 1;
    align-items: center;
    justify-content: flex-start;
    text-align: center;
    width: 100%;
}

.dropzone-container {
    /* padding: 4rem; */
    /* display: flex;
    padding: 20px 10px;
    flex-direction: column;
    justify-content: center;
    align-items: flex-start;
    height: 60px;
    gap: 5px;
    align-self: stretch;*/
    background: #EFF2FE;
    padding: 20px;
    width: 100%;
}

.label-file p {
    color: #252525;
    text-align: start;
    font-family: Montserrat !important;
    font-size: 18px;
    font-style: normal;
    font-weight: 700;
    line-height: 19.371px;
    /* 107.616% */
    /* 193.708% */
}

.label-file p span {
    color: 252525;
    font-family: Montserrat !important;
    font-size: 10px;
    font-style: normal;
    font-weight: 400;
    line-height: 19.371px;
    /* 193.708% */
}

.label-file p span b {
    font-weight: 700;
}

.select-file {
    color: #5063F4 !important;
    text-align: center;
    font-family: Montserrat !important;
    font-size: 14px !important;
    font-style: normal;
    font-weight: 700;
    line-height: 19.371px;
    /* 138.363% */
    text-decoration-line: underline;
}

.hidden-input {
    opacity: 0;
    overflow: hidden;
    position: absolute;
    width: 1px;
    height: 1px;
}

.file-label {}

.preview-container {
    display: flex;
    /* margin-top: 2rem; */
}

/* .preview-card {
    display: flex;
    border: 1px solid #a2a2a2;
    padding: 5px;
    margin-left: 5px;
} */

.preview-img {
    width: 50px;
    height: 50px;
    border-radius: 5px;
    border: 1px solid #a2a2a2;
    background-color: #a2a2a2;
}
</style>  