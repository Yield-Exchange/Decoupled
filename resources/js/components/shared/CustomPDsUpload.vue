<template>

    <div>

        <div class="combined-input" style="margin-top: 4px;">
            <b-form-select class="" id="termlengthid" v-model="defaultValue" ref="termLengthSelect" @change=""
                :options="['https://', 'http://']"
                style="border: none;width:25%;margin-left:15px;outline:none; box-shadow: none; text-transform: capitalize;">
            </b-form-select>
            <b-form-input
                style="border: none;min-width:100px !important;font-size:14px !important;width:79%;margin:0px; margin-right:13px;outline:none; box-shadow: none; padding:0px;padding-left:5px;border-left:0.5px solid  #ccc;border-top-left-radius: 0px ;border-bottom-left-radius: 0px "
                type="text" step='0.01' min="0" v-model.lazy="url" :class="{ 'validation-error': error }"
                placeholder="Add URL Here" @change="WebisteUrl" />

            <div style="display: flex; flex-direction: column;gap: 5px; height: 44px;">
                <label>
                    <input type="file" accept=".pdf" class="custom-file-input" @change="handleFileChange">
                    <div
                        style="width: 100%; height: 40px;  padding: 10px 14px;border: 1px solid  #D9D9D9;background: #EFF2FE; border-top-right-radius: 999px;border-bottom-right-radius: 999px; overflow: hidden; margin-top: 250px; border-left: 0.50px #D9D9D9 solid; justify-content: flex-start; align-items: center; gap: 8px; display: inline-flex; margin-top: -32px;cursor: pointer">
                        <div style="height: 24px; justify-content: flex-start; align-items: center; display: flex">
                            <div style="width: 26.67px; height: 24px; position: relative">
                                <svg xmlns="http://www.w3.org/2000/svg" width="27" height="24" viewBox="0 0 27 24"
                                    fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M8.24782 5.74428C7.61618 6.50471 7.28331 7.6616 7.75328 9.34863C7.81379 9.56586 7.75967 9.79833 7.60898 9.96843C7.45828 10.1385 7.23087 10.2239 7.00265 10.1959C4.62061 9.90443 3.44678 11.3234 3.38173 12.5971C3.34848 13.2481 3.59566 13.8804 4.13711 14.3234C4.67887 14.7667 5.59571 15.0838 7.01203 14.9298C7.39045 14.8886 7.73131 15.1556 7.77335 15.526C7.8154 15.8965 7.54271 16.2302 7.16429 16.2714C5.47828 16.4548 4.15454 16.097 3.25286 15.3591C2.35085 14.6211 1.95171 13.566 2.00464 12.5297C2.10069 10.649 3.65618 8.88464 6.22886 8.8105C5.98093 7.19614 6.36137 5.87586 7.17806 4.89264C8.12005 3.75858 9.55994 3.17276 10.9837 3.03338C12.4055 2.89418 13.9098 3.18934 15.0369 3.9372C15.9623 4.55128 16.626 5.47226 16.7488 6.65016C18.24 6.41097 19.4727 6.74305 20.3824 7.49071C21.4412 8.36091 21.948 9.70139 21.9962 11.0207C22.0444 12.3421 21.6381 13.7373 20.7458 14.7708C19.833 15.828 18.4438 16.465 16.6637 16.2714C16.2853 16.2302 16.0126 15.8965 16.0547 15.526C16.0967 15.1556 16.4376 14.8886 16.816 14.9298C18.1383 15.0736 19.0759 14.6138 19.6927 13.8994C20.33 13.1612 20.6562 12.1097 20.6182 11.0689C20.5802 10.026 20.1821 9.08857 19.4959 8.52455C18.837 7.98301 17.7984 7.6927 16.2486 8.1479C16.0253 8.21346 15.7833 8.16416 15.6054 8.01684C15.4274 7.86952 15.3375 7.64414 15.3664 7.41772C15.5028 6.3491 15.0445 5.57292 14.2633 5.05459C13.4514 4.51586 12.2843 4.26264 11.1209 4.37654C9.95929 4.49026 8.90008 4.95902 8.24782 5.74428ZM11.4833 9.67412C11.7351 9.47692 12.0929 9.47692 12.3447 9.67412L15.7917 12.3738C16.089 12.6067 16.1372 13.0314 15.8994 13.3225C15.6615 13.6136 15.2277 13.6607 14.9304 13.4279L12.6034 11.6054V20.3251C12.6034 20.6978 12.2948 21 11.914 21C11.5333 21 11.2246 20.6978 11.2246 20.3251V11.6054L8.89765 13.4279C8.60033 13.6607 8.16649 13.6136 7.92864 13.3225C7.69079 13.0314 7.73899 12.6067 8.03631 12.3738L11.4833 9.67412Z"
                                        fill="#5063F4" />
                                </svg>
                            </div>
                            <div
                                style="width: 100%; color: #5063F4; font-size: 16px; font-weight: 400; text-decoration: underline; line-height: 24px; word-wrap: break-word">
                                Upload</div>
                        </div>
                    </div>
                </label>
                <span style="color: #5063F4;width: 100%;margin-bottom: 15px;">{{ file ? (file.name.length <= 15) ?
                file.name : `${file.name.slice(0, 16)}...` : '' }}</span>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    components: {
    },
    props: [
        'default',

    ],
    data() {
        return {
            file: null,
            defaultValue: this.default,
            url: null,
            error: false

        }
    },
    methods: {
        handleFileChange(event) {
            // Handle the file change event here
            this.file = event.target.files[0];
            console.log('Selected file:', this.file);
            this.$emit("update:file_selected", this.file)
        },
        validateWebsite(url) {
            const urlRegex = /^(?:(?:(?:https?|ftp):)?\/\/)?(?:(?:[\w\-.])+\.)+[a-z]{2,}(?::\d{1,5})?(?:\/[^\s]*)?$/i;
            if (urlRegex.test(url)) {
                let allexceptProtocol;
                if (url.startsWith("http://")) {
                    allexceptProtocol = url.split("http://")[1];
                } else if (url.startsWith("https://")) {
                    allexceptProtocol = url.split("https://")[1];
                } else {
                    allexceptProtocol = url;
                }

                let wwwReplaced = allexceptProtocol.replace(/^www\./i, "");
                const remainingUrlRegex = /^((?!www)[\w-]+\.)+[a-z]{2,}$/i;
                if (remainingUrlRegex.test(wwwReplaced)) {
                    console.log(wwwReplaced, "Domain Without WWW");
                    return true; // URL is valid
                } else {
                    return false; // URL is not valid
                }
            } else {
                return false;
            }
        },

        WebisteUrl(value) {
            if (value.length < 0)
                this.$emit("url", null)
            else {
                if (this.validateWebsite(value)) {
                    if (this.asHttpOrHttps(value))
                        this.$emit("url", value)
                    else
                        this.$emit("url", this.defaultValue + value)
                } else {
                    this.error = true
                    this.$emit("url", null)
                }

            }
        },
        asHttpOrHttps(inputStr) {
            var pattern = /(https?):\/\//;
            return pattern.test(inputStr);
        }
    }
}
</script>