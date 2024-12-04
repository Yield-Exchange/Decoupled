<template>
    <div class="tab-pane fade active show chat-container">
        <ul class="media-list media-chat mb-3" id="show">
            <li v-for="message in messages" class="media" v-bind:class="{'media-chat-item-reverse':message.is_mine}" v-bind:key="'media-chat-li'+message.id">
                <div class="mr-3" v-if="!message.is_mine">
                    <img v-if="message.sent_by.profile_url!==null" v-bind:src="message.sent_by.profile_url" :load="log(message)" class="rounded-circle" width="40" height="40" alt="Profile"/>
                    <div v-else class="i-initial-inverse">{{ message.sent_by.name[0] }}</div>
                </div>
                <div class="media-body">
                    <div class="media-chat-item" style="max-width: 70%;min-width: 40%">
                        <h5 :style="'width: 100%; text-align: left;font-size: 13px;'+(message.is_mine ? 'color:white;' : 'color:black')">
                            <b>{{ message.sent_by.name }}</b>
                            <small>{{ message.sent_by.demographic_data ? '('+message.sent_by.demographic_data.job_title+')' : '' }}</small>
                            <small style="float: right; margin-left: 10px">{{ message.created_at }}</small>
                        </h5>
                        <div style="font-weight: normal">
                            {{ message.text }}
                            <div style="margin-top: 5px"  v-if="message.file">
                                <img style="height: 200px;cursor:pointer" @click="viewFile(message.file)"  v-if="isAnImage(message.file)" v-bind:src="message.file"/>
                                <iframe @click="viewFile(message.file)" class="chat-embed-iframe" v-if="message.file && !isAnImage(message.file)" v-bind:src="message.file" allowTransparency="true" frameborder="0" style="width: 100%;height: 200px"></iframe>
                                <br>
                                <a target="_blank" :style="'padding: 0;'+(message.is_mine ? 'color:white':'color:#42a5f5')" v-if="message.file" class="btn btn-link" :href="message.file">
                                    <i class="fa fa-link"></i> View file
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ml-3" v-if="message.is_mine">
                    <img v-if="message.sent_by.profile_url!==null" v-bind:src="message.sent_by.profile_url" class="rounded-circle" width="40" height="40" alt="Profile"/>
                    <div v-else class="i-initial-inverse">{{ message.sent_by.name[0] }}</div>
                </div>
            </li>
        </ul>
    </div>
</template>
<script>
    export default {
        props: ['messages','user'],
        watch: {
            // messages (n, o) {
            //     console.log(n) // n is the new value, o is the old value.
            // }
        },
        methods: {
            log(item) {
                // console.log(item)
            },
            isAnImage(fileName){
                // console.log(fileName);
                if(fileName) {
                    let files = fileName.split('.');
                    return files && files[files.length-1] && ['jpeg', 'jpg', 'png', 'gif'].includes(files[files.length-1]);
                }
                return false;
            },
            viewFile(fileName){
                if (fileName) {
                    window.open(fileName, '_blank').focus();
                }
            }
        }
    };
</script>
