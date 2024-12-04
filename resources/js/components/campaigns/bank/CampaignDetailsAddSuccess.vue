<template>
    <div style="width: 100%; height: 100%; flex-direction: column; justify-content: center; align-items: center; gap: 40px; display: inline-flex">
        <div style="width: 486px; justify-content: flex-start; align-items: center; gap: 10px; display: inline-flex">
            <div style="width: 60px; height: 60px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="61" height="60" viewBox="0 0 61 60" fill="none">
                    <path d="M9.38219 22.8761C11.0109 15.9325 16.4325 10.5109 23.3761 8.88219C28.3906 7.70594 33.6094 7.70594 38.6239 8.88219C45.5675 10.5109 50.9891 15.9325 52.6178 22.8761C53.7941 27.8906 53.7941 33.1094 52.6178 38.1239C50.9891 45.0675 45.5675 50.4891 38.6239 52.1178C33.6094 53.2941 28.3906 53.2941 23.3761 52.1178C16.4325 50.4891 11.0109 45.0675 9.38219 38.1239C8.20594 33.1094 8.20594 27.8906 9.38219 22.8761Z" fill="#EFF2FE" stroke="#F8FAFF" stroke-width="1.35"/>
                    <path d="M22.5 31L28.5 37L40.5 25" stroke="#5063F4" stroke-width="3.33" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div style="width: 416px; color: #2A2A2A; font-size: 28px; font-weight: 700; line-height: 32px; word-wrap: break-word">Campaign Details captured</div>
        </div>
        <div style="width: 208px; height: 208px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="209" height="208" viewBox="0 0 209 208" fill="none">
                <path d="M104.5 208C161.938 208 208.5 161.438 208.5 104C208.5 46.5624 161.938 0 104.5 0C47.0624 0 0.5 46.5624 0.5 104C0.5 161.438 47.0624 208 104.5 208Z" fill="#EFF2FE"/>
                <path d="M166.9 207.999H42.1001V73.4928C47.9823 73.4862 53.6218 71.1466 57.7811 66.9872C61.9405 62.8279 64.2802 57.1884 64.2868 51.3062H144.713C144.707 54.2201 145.279 57.1063 146.395 59.7978C147.512 62.4893 149.151 64.9327 151.218 66.9866C153.272 69.0542 155.716 70.694 158.407 71.8108C161.099 72.9276 163.986 73.4993 166.9 73.4928V207.999Z" fill="white"/>
                <path d="M104.5 141.44C122.88 141.44 137.78 126.54 137.78 108.16C137.78 89.7798 122.88 74.8799 104.5 74.8799C86.1199 74.8799 71.22 89.7798 71.22 108.16C71.22 126.54 86.1199 141.44 104.5 141.44Z" fill="#44E0AA"/>
                <path d="M98.2254 123.012L84.0002 108.787L87.5565 105.231L98.2254 115.9L121.123 93.0024L124.679 96.5587L98.2254 123.012Z" fill="white"/>
                <path d="M122.527 149.76H86.4737C84.1762 149.76 82.3137 151.622 82.3137 153.92C82.3137 156.217 84.1762 158.08 86.4737 158.08H122.527C124.825 158.08 126.687 156.217 126.687 153.92C126.687 151.622 124.825 149.76 122.527 149.76Z" fill="#DFEAFB"/>
                <path d="M135.007 166.4H73.9933C71.6957 166.4 69.8333 168.262 69.8333 170.56C69.8333 172.857 71.6957 174.72 73.9933 174.72H135.007C137.304 174.72 139.167 172.857 139.167 170.56C139.167 168.262 137.304 166.4 135.007 166.4Z" fill="#DFEAFB"/>
            </svg>
        </div>
        <div style="width: 482px; text-align: center; color: #2A2A2A; font-size: 20px; font-weight: 600; text-transform: capitalize; line-height: 26px; word-wrap: break-word">
            Proceed to add details to your current campaign</div>
        <div style="padding-left: 90px; padding-right: 90px; justify-content: center; align-items: flex-start; gap: 50px; display: inline-flex">
            <div @click="previous" style="padding: 15px 41px;border-radius: 20px; overflow: hidden; border: 1px #5063F4 solid; justify-content: center; align-items: center; gap: 4px; display: flex; cursor: pointer">
                <div style="padding-left: 10px; padding-right: 10px; justify-content: center; align-items: flex-start; gap: 10px; display: flex">
                    <div style="color: #5063F4; font-size: 16px; font-weight: 500; text-transform: capitalize; line-height: 20px; word-wrap: break-word">Previous</div>
                </div>
            </div>
            <div @click="next" style="padding: 15px 41px;background: #5063F4; border-radius: 20px; overflow: hidden; justify-content: center; align-items: center; gap: 4px; display: flex; cursor: pointer">
                <div style="padding-left: 10px; padding-right: 10px; justify-content: center; align-items: flex-start; gap: 10px; display: flex" >
                    <div style="color: white; font-size: 16px;  font-weight: 600; text-transform: capitalize; line-height: 20px; word-wrap: break-word">Add Products</div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        components:{
        },
        props: [],
        data(){
            return {
            }
        },
        methods:{
            next(){
                this.$emit('next',true);
            },
            previous(){
                this.$emit('previous',true);
            }
        }
    }
</script>