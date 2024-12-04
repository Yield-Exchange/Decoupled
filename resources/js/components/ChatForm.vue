<template>
    <form id="sendChat" v-if="canChat">
        <textarea id="msg" name="message" class="form-control mb-3" rows="3" cols="1"
                  placeholder="Enter your message..." v-model="newMessage" @keyup.enter="sendMessage"/>
<!--        <div class="alert alert-warning" v-if="submitted && !newMessage">Message is required</div>-->
        <br/>
        <div class="image-upload">
            <input name="file" ref="fileAttach" @change="onFileChange" id="file-upload" accept="application/pdf, image/png, image/gif, image/jpeg" type="file" />
            <br/>
            <div class="alert alert-warning" v-if="file && (file.size/1000/1000) > 25">The file size is greater than 25mb which is not allowed</div>
        </div>
        <div class="d-flex align-items-center">
            <button id="save" type="button" class="btn bg-primary btn-labeled btn-labeled-right ml-auto"  @click="sendMessage"><b><i
                    class="icon-paperplane"/></b> Send</button>
        </div>
    </form>
</template>
<style>
    .alert{
        padding: 0;
        margin: 0;
    }
</style>
<script>
    export default {
        props: ['user','deposit','token','canChat'],

        data() {
            return {
                newMessage: '',
                file: null,
                submitted: false
            }
        },

        methods: {
            sendMessage() {
                this.submitted=true;
                // console.log(this.file);
                const formData = new FormData();
                formData.append("user", this.user);
                formData.append("message", this.newMessage);
                formData.append("deposit_id", this.deposit.id);
                formData.append("_token", this.token);
                formData.append("file", this.file);

                if(!this.newMessage && !this.file){
                    return;
                }

                if (this.file && (this.file.size/1000/1000) > 25){
                    return;
                }

                this.$emit('messagesent', formData);

                this.newMessage = '';
                this.file = null;
                this.$refs.fileAttach.value=null;
                this.submitted=false;
            },
            onFileChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length) {
                    return;
                }
                this.file = files[0];
                // console.log(this.file);
            },
            createImage(fl) {
                // let reader = new FileReader();
                // let this_ = this;
                //
                // reader.onload = (e) => {
                //     this_.file = e.target.result;
                //     console.log(this_.file);
                // };
                // reader.readAsArrayBuffer(fl);
            },
        }
    }
</script>