<template>
    <Modal :show="show" @isVisible="closeModal" modalsize="lg">
        <div class="w-100 p-4">
            <!-- header -->
            <div
                style="width: 100%; height: 70px; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex">
                <div
                    style="width: 100%; padding-left: 3px; background: #EFF2FE; justify-content: flex-start; align-items: center; display: inline-flex">
                    <div style="justify-content: space-between; display: inline-flex; width: 99%">
                        <div class="page-title">
                            <div class="">
                                <avatar v-if="!recipient.logo" :size="60" :color="'white'" :backgroundColor="'#4975E3'"
                                    :initials="recipient.name[0]">
                                </avatar>
                                <avatar v-else :size="60" :color="'white'" :backgroundColor="'#4975E3'"
                                    :src="'/image/' + recipient.logo"></avatar>
                            </div>
                            <div class="text-div">{{ recipient.name }}</div>
                            <!-- <div class="text-div">{{ recipient.name }}</div> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-100">
                <div class="container chat-container">
                    <div class="chat-messages " id="chatMessage" ref="chatMessage">
                        <!-- {{ messages }} -->
                        <div class="message"
                            :class="{ 'from-me': message.from === 'me', 'from-other': message.from === 'other' }"
                            v-for="(message, index) in messages" :key="index">
                            <div v-if="message.type == 'text'">
                                {{ message.content }}
                            </div>
                            <div v-if="message.type == 'file'">

                                <img v-if="getFileTypeFromFileName(message.file) != 'pdf'"
                                    :src="'/uploads/CTRequestsDepositsmessages/' + message.file"
                                    class="rounded float-right" style="max-width: 300px;max-height: 300px;" alt="">
                                <a class="d-flex p-2 gap-2" v-else
                                    :href="'./uploads/CTRequestsDepositsmessages/' + message.file" download>
                                    {{ message.file }} <img src="/assets/dashboard/icons/icons8-download-24.png"
                                        style="height: 20px;width: 20px;"> </a>
                            </div>
                        </div>
                    </div>
                    <div class="chat-input w-100">
                        <input ref="fileInput" accept=".png,.jpg,.pdf,.gif" type="file" @change="handleFileUpload">
                        <textarea class="form-control" row="4" v-model="newMessage"
                            placeholder="Type your message..."></textarea>
                        <button @click="sendMessage">Send</button>
                    </div>
                </div>

            </div>

        </div>
    </Modal>

</template>

<script>

import Modal from './../../shared/Modal.vue';
import { addDaysOrMonths, formatTimestamp, calculateIterestOnProduct, addCommasAndDecToANumber } from '../../../utils/commonUtils'
import { mapGetters } from 'vuex';
import Avatar from 'vue-avatar';



export default {
    props: ['deposit_id', 'recipient', 'sender', 'show', 'from', 'reference'],
    components: { Modal, Avatar },
    beforeMount() {
        this.getMessages()
        this.recipient_id = this.recipient?.id
    },
    mounted() {
    },
    updated() {
        this.scrollToBottom();
    },
    data() {
        return {
            messages: null,
            newMessage: '',
            files: [],
            fileToUpload: null,
            recipient_id: null
        };
    },
    methods: {
        closeModal() {
            this.$emit('closeModal', false)
        },
        handleFileUpload(event) {
            this.fileToUpload = event.target.files[0];
        },
        sendMessage() {
            let data = new FormData()
            data.append('depositId', this.deposit_id)
            data.append('message', this.newMessage)
            if (this.fileToUpload)
                data.append('file', this.fileToUpload)
            let url = null
            if (this.from == 'CG')
                url = '/trade/CG/send-deposit-message'
            else
                url = '/trade/CT/send-deposit-message'
            if ((this.newMessage != null && this.newMessage != '') || this.fileToUpload != null) {
                axios.post(url, data).then(response => {
                    if (response.data.success) {
                        this.getMessages()
                        this.newMessage = null
                        this.fileToUpload = null
                        this.$refs.fileInput.value = null
                    }
                }).catch(err => {
                    // console.log(err, "Error")
                })
            }

        },
        getFileTypeFromFileName(fileName) {
            const extension = fileName.split('.').pop().toLowerCase();
            // console.log(extension, " Extension")
            switch (extension) {
                case 'jpg':
                case 'jpeg':
                case 'png':
                case 'gif':
                    return 'image';
                case 'pdf':
                    return 'pdf';
                default:
                    return 'unknown';
            }
        },
        scrollToBottom() {
            let container = this.$refs.chatMessage;

            // Check if the user is already at the bottom of the container
            let isScrolledToBottom = container.scrollTop + container.clientHeight === container.scrollHeight;

            // Scroll to the bottom only if not already there
            if (!isScrolledToBottom) {
                container.scrollTop = container.scrollHeight;
            }
        },

        getMessages() {
            let url = null
            if (this.from == 'CG')
                url = '/trade/CG/get-deposit-messages?depositId=' + this.deposit_id
            else
                url = '/trade/CT/get-deposit-messages?depositId=' + this.deposit_id

            axios.get(url).then(response => {
                let messages = response.data
                let message_object = []
                if (messages.length > 0) {
                    response.data.forEach(element => {
                        // console.log(element.sent_to_organization_id == this.recipient_id, " Sent form", this.recipient_id)
                        if (element.sent_to_organization_id == this.recipient_id) {
                            if (element.message != null && element.message != '') {
                                message_object.push({ content: element.message, from: 'me', type: 'text' })
                            }
                            if (element.file != '') {
                                message_object.push({ content: element.message, file: element.file, from: 'me', type: 'file' })
                            }
                        } else {
                            if (element.message != null && element.message != '') {
                                message_object.push({ content: element.message, from: 'other', type: 'text' })
                            }
                            if (element.file != '') {
                                message_object.push({ content: element.message, file: element.file, from: 'other', type: 'file' })
                            }
                        }
                    })
                    this.message_object = null
                    this.messages = message_object
                }

            }).catch(err => {
                console.log(err, "Error")
            })

        }

    },
    created() {
        // let reference = "2405311101";
        Echo.private("CT_Trade_Request_Deposit_chat." + this.reference).listen(
            "CTTradeRequestOfferDepositChatEvent",
            (e) => {
                this.getMessages()
            }
        );
    },

    watch: {

    }
}

</script>

<style scoped>
.chat-container {
    height: 400px !important;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 12px;

}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    padding: 12px;
    scroll-snap-type: y mandatory
}

.chat-messages::-webkit-scrollbar {
    width: 5px;
    height: 5px;
}

.message {
    margin-bottom: 10px;
    padding: 8px;
    border-radius: 5px;
    max-width: 80%;
}

.from-me {
    align-self: flex-end;
    background-color: #007bff;
    color: white;
}

.from-me a {
    color: white;
}


.from-other {
    align-self: flex-start;
    background-color: #f0f0f0;
}

.from-other a {
    color: black;
}

.chat-input {
    display: flex;
    align-items: flex-end;
    gap: 5px;
}

.chat-input input[type="text"] {
    flex: 1;
    margin-right: 10px;
}

.chat-input button {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
}

.chat-input input[type="file"] {
    margin-right: 10px;
}
</style>