<template>
    <div style="width: 100%; justify-content: space-between;  display: inline-flex;">
        <div style="min-width:100px">
            <div @click="page(data?.prev_page_url)" v-if="data?.prev_page_url" style="padding: 8px 16px;background: white; border-radius: 8px; border: 0.50px #E0E0E0 solid; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex;cursor: pointer">
                <div style="justify-content: flex-start; align-items: center; gap: 8px; display: inline-flex">
                    <div style="text-align: center; color: #2A2A2A; font-size: 14px; font-weight: 400; line-height: 18px; word-wrap: break-word">Previous</div>
                </div>
            </div>
        </div>
        <div style="min-width: 100px; position: relative; display: inline-flex; gap: 10px" v-if="links.length > 1">
            <div style="background-color: #EFF2FE;width: 32px;" v-for="(datum, index) in links" :key="index" v-if="datum.url">
                <div v-if="datum.active" @click="page(datum.url)" style="justify-content: center; align-items: center; display: inline-flex;cursor: pointer">
                    <div style="width: 32px; height: 32px; padding: 5px 4px;background: white; border-radius: 4px; overflow: hidden; border: 0.50px #5063F4 solid; justify-content: center; align-items: center; display: inline-flex;cursor: pointer">
                        <div style="width: 24px; height: 22px; text-align: center; color: #5063F4; font-size: 14px; font-weight: 700; line-height: 20px; word-wrap: break-word">{{ datum.label }}</div>
                    </div>
                </div>
                <div v-else @click="page(datum.url)" style="padding: 5px 4px; background: white; border-radius: 4px; border: 0.50px #DFE3E8 solid; justify-content: center; align-items: center; display: inline-flex; cursor: pointer">
                    <div style="width: 24px; height: 22px; text-align: center; color: #212B36; font-size: 14px; font-weight: 400; line-height: 20px; word-wrap: break-word">{{ datum.label }}</div>
                </div>
            </div>
        </div>
        <div style="min-width: 100px">
            <div @click="page(data?.next_page_url)" v-if="data?.next_page_url" style="padding: 8px 16px;background: white; border-radius: 8px; border: 0.50px #E0E0E0 solid; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex;cursor: pointer">
                <div style="justify-content: flex-start; align-items: center; gap: 8px; display: inline-flex">
                    <div style="text-align: center; color: #2A2A2A; font-size: 14px; font-weight: 400; line-height: 18px; word-wrap: break-word">Next</div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default{
        mounted() {
        },
        components: {

        },
        created() {
        },
        props: ['data'],
        data() {
            return {
                links: this.verifyLinks()
            }
        },
        watch:{
            data(newVal, oldVal){
                this.links = this.verifyLinks();
            }
        },
        methods: {
            page(url){
                this.$emit("click-next-page",url);
            },
            validLink(label,is_next=false, is_previous=false){
                if(label.includes('Next')){
                    if(is_next){
                        return true;
                    }
                    return false;
                }

                if(label.includes('Previous')){
                    if(is_previous){
                        return true;
                    }
                    return false;
                }
                return label;
            },
            hasPrevious(){
                let index1=this.data?.links?.length > 0 ? this.data?.links[0] : null;
                if(!index1){
                    return false
                }

                return this.validLink(index1.label,false,true);
            },
            hasNext(){
                let index1=this.data?.links?.length > 0 ? this.data?.links[this.data?.links.length-1] : null;
                if(!index1){
                    return false
                }
                return this.validLink(index1.label,true);
            },
            verifyLinks(){
                let links_ = this.data.links.slice(); //clones the array

                if(this.hasNext()){
                    links_.splice(this.data.links.length-1,1);
                }

                if(this.hasPrevious()){
                    links_.splice(0,1);
                }
                return links_;
            }
        }
    }
</script>