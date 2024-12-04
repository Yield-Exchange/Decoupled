<template>
    <div>
        <div @click="redirectToDestination" class="acc-type-header w-100"
            :class="{ 'headerHover': hover, 'disabled_section': request_access }" @mouseleave="hover = false"
            style="min-width: 480px !important;" @mouseenter="hover = true">
            <div class="d-flex">
                <img :src="'/assets/signup/' + image" width="40" height="40" alt="">
                <div class="d-flex justify-content-between w-100 align-items-center">
                    <h2 class="acc-header-title" :class="(hover ? 'hovered-text ' : 'not-hovered-text')">{{
                        title }}
                    </h2>
                    <div v-if="request_access" @click="setRequestQuestion" class="request-access">
                        Request Access
                    </div>
                </div>
                <!-- <img src="assets/signup/yie-Logo.svg" alt=""> -->

            </div>
            <hr>
            <p class="acc-header-desc" :class="(hover ? 'hovered-text ' : 'not-hovered-text')"> {{ desc
                }} </p>
        </div>
        <ActionMessage style="width: 600px;" @closedSuccessModal="closeSuccess($event)" @btnTwoClicked=""
            @btnOneClicked="success = false" icon="/assets/signup/success_promo.svg"
            title="Request submitted succesfully" btnOneText="" btnTwoText="" :showm="success">
            <div class="ml-5 description-text-withdraw ">You will receive an onboarding email once approved
            </div>


        </ActionMessage>
        <ActionMessage style="width: 600px;" @closedSuccessModal="fail = false" @btnTwoClicked=""
            @btnOneClicked="fail = false" btnOneText="" btnTwoText="" icon="/assets/signup/danger.svg"
            :title="failTitle" :showm="fail">
            <div class="ml-5 description-text-withdraw ">{{ failDesc }}</div>
        </ActionMessage>

        <ActionMessage style="width: 600px;" @closedSuccessModal="question = false" @btnTwoClicked="requestAccess"
            @btnOneClicked="question = false" btnOneText="No" btnTwoText="Yes"
            icon="/assets/dashboard/icons/question-new.svg" :title="questionTitle" :showm="question">
            <div class="ml-5 description-text-withdraw " v-if="isorg_admin">Your request will be processed by the admin.
            </div>
            <div class="ml-5 description-text-withdraw " v-else>Your request will be sent to the Organization
                Administrator for validation
            </div>
        </ActionMessage>
    </div>
</template>

<script>
    import ActionMessage from '../../../shared/messageboxes/ActionMessageBox.vue';
    // import
    export default {
        props: ['title', 'desc', 'image', 'parentPermID', 'userType', 'type', 'request_access', 'destinationurl', 'isorg_admin'],
        components: { ActionMessage },
        data() {
            return {
                message: "choose Account",
                hover: false,
                question: false,
                success: false,
                fail: false,
                questionTitle: false,
                failTitle: 'Request has not been submitted!',
                failDesc: "Something's not right,please try again or contact info@yieldechange.ca"

            }
        },
        methods: {
            setRequestQuestion() {
                this.questionTitle = `would you like to opt into the ${this.title} feature?`
                this.question = true

            },
            async requestAccess() {
                let data = {
                    'parentPermID': this.parentPermID,
                    'itemLabel': this.title,
                    'isAdmin': this.isorg_admin
                }
                await axios.post('/common/account-management/request-access-to-launchpad-item', data).then(response => {
                    this.question = false
                    if (response.data.success) {
                        this.success = true
                        setTimeout(() => {
                            this.success = false
                        }, 3000)
                    } else {
                        this.fail = true
                    }
                }).catch(err => {
                    this.failDesc = "A pending request already exists for this user.";
                    this.question = false
                    this.fail = true

                    // console.log(err)
                })
            },
            redirectToDestination() {
                if (!this.request_access) {
                    window.location.href = '/' + this.destinationurl
                }
            }
        },
        watch: {
            hover() {
                // console.log(this.hover)
            }
        },
    }
</script>

<style scoped>
    .request-access {
        display: flex;
        padding: 10px;
        justify-content: center;
        align-items: center;
        gap: 10px;
        border-radius: 4px;
        background: var(--Yield-Exchange-Colors-Yield-Exchange-Blue, #5063F4);
        box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.10);
        color: var(--Yield-Exchange-Pallette-Yield-Exchange-White, #FFF);

        /* Yield Exchange Text Styles/Tooltip Buttons */
        font-family: Montserrat;
        font-size: 12px;
        font-style: normal;
        font-weight: 600;
        line-height: 11px;
        /* 91.667% */
    }

    .acc-type-header {
        background-color: white;
        border-radius: 4px;
        /* background: #FFF; */
        box-shadow: 0px 2px 6px 0px rgba(80, 99, 244, 0.15);
        padding: 10px;
        margin-bottom: 10px;
        cursor: pointer;
    }

    .headerHover {
        /* background-color: white; */
        border-radius: 4px;
        /* background: #FFF; */
        box-shadow: 0px 2px 6px 0px rgba(80, 99, 244, 0.15);
        padding: 10px;
        margin-bottom: 10px;
        background-color: #5063F4 !important;
        cursor: pointer;
    }

    .acc-header-title {

        /* Yield Exchange Text Styles/Modal  & Blue BG Titles */
        font-family: Montserrat;
        font-size: 28px;
        padding: 0 !important;
        margin: 0 !important;
        font-style: normal;
        font-weight: 700;
        line-height: 32px;
        /* 114.286% */
        text-transform: capitalize;
        margin-left: 10px;
    }

    .not-hovered-text {
        color: #252525;
    }

    .disabled_section .acc-header-title,
    .disabled_section .acc-header-desc {
        color: #9CA1AA !important;
        font-family: Montserrat;
        /* font-size: 28px; */
        font-style: normal;
        /* font-weight: 700; */
        /* line-height: 32px; */
        /* 114.286% */
        /* background: #FFF; */
        /* box-shadow: 0px 2px 6px 0px rgba(80, 99, 244, 0.15); */
    }

    .hovered-text {
        color: white;
    }

    .acc-header-desc {
        font-family: Montserrat;
        font-size: 16px;
        font-style: normal;
        font-weight: 300;
        line-height: normal;
    }
</style>