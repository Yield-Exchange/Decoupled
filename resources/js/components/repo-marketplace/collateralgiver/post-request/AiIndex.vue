<template>
    <div class="w-100 mb-20">
        <div class="mb-20"
            style="width: 100%;  padding-top: 10px; padding-bottom: 10px; background: #EFF2FE; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 30px; display: inline-flex">
            <div style="justify-content: center; align-items: flex-start; display: inline-flex">
                <div style="display: flex; flex-direction: column;">
                    <div
                        style="width: 100%; align-self: stretch; justify-content: flex-start; align-items: center; gap: 10px; display: flex">
                        <div style="width: 40px; height: 40px; position: relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40"
                                fill="none">
                                <path
                                    d="M5.58813 14.9174C6.67396 10.2883 10.2883 6.67395 14.9174 5.58813C18.2604 4.80396 21.7396 4.80396 25.0826 5.58813C29.7117 6.67395 33.3261 10.2884 34.4119 14.9174C35.196 18.2604 35.196 21.7396 34.4119 25.0826C33.3261 29.7117 29.7117 33.3261 25.0826 34.4119C21.7396 35.1961 18.2604 35.1961 14.9174 34.4119C10.2884 33.3261 6.67396 29.7117 5.58813 25.0826C4.80396 21.7396 4.80396 18.2604 5.58813 14.9174Z"
                                    fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35" />
                                <path
                                    d="M25.699 13H14.411C13.6349 13 13.0071 13.6349 13.0071 14.411L13 27.11L15.822 24.288H25.699C26.475 24.288 27.11 23.6531 27.11 22.877V14.411C27.11 13.6349 26.475 13 25.699 13ZM25.699 22.877H15.2364L14.8202 23.2932L14.411 23.7024V14.411H25.699V22.877ZM18.9967 21.466H24.288V20.055H20.4078L18.9967 21.466ZM21.72 17.3247C21.8611 17.1836 21.8611 16.9649 21.72 16.8238L20.4712 15.5751C20.3301 15.434 20.1114 15.434 19.9703 15.5751L15.822 19.7234V21.466H17.5646L21.72 17.3247Z"
                                    fill="#5063F4" />
                            </svg>
                        </div>
                        <div
                            style="color: #252525; font-size: 30px;  font-weight: 800; line-height: 32px; word-wrap: break-word">
                            Publish Rate Offers
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="w-100 d-flex justify-content-center align-items-center flex-column" v-if="step == 'intro'">
            <div class="ai-introductiion-yei mt-5">
                <div class="d-flex justify-content-center gap-2">
                    <img src="/assets/dashboard/icons/aiicons/aiicon.svg" alt="">
                    <p class="p-0 m-0 ai-assisted-header">Welcome to the Yield Exchange AI Assisted Publish Rate Offers
                    </p>
                </div>
                <p class="p-0 m-0 content-style-description">We're here to make the repo request process faster and
                    easier
                    for you. Simply answer a few questions, and
                    we'll handle the rest.</p>
                <h4 class="content-style-header">
                    Here’s what to expect from our model
                </h4>
                <div>
                    <h4 class="content-style-header">1. Step-by-Step Guidance: </h4>
                    <p class="p-0 m-0 content-style-description">We'll ask you a few simple questions about your
                        investment
                        details and preferences.</p>
                </div>
                <div>
                    <h4 class="content-style-header">2. Auto Suggestions </h4>
                    <p class="p-0 m-0 content-style-description">The model suggest relevant options based on your inputs
                        to
                        help you fill the form faster.</p>
                </div>
                <div>
                    <h4 class="content-style-header">3. Review & Confirm: </h4>
                    <p class="p-0 m-0 content-style-description">You'll then get a final chance to review everything
                        before
                        submitting your request.</p>
                </div>
                <div class="ai-notification d-flex gap-2 p-2 align-items-center justify-content-center"
                    style="border-radius: 10px;background: rgba(244, 182, 60, 0.10);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path opacity="0.3"
                            d="M10 3.3335C6.32504 3.3335 3.33337 6.32516 3.33337 10.0002C3.33337 13.6752 6.32504 16.6668 10 16.6668C13.675 16.6668 16.6667 13.6752 16.6667 10.0002C16.6667 6.32516 13.675 3.3335 10 3.3335ZM10.8334 14.1668H9.16671V9.16683H10.8334V14.1668ZM10.8334 7.50016H9.16671V5.8335H10.8334V7.50016Z"
                            fill="#C2850D" />
                        <path
                            d="M9.16663 5.83317H10.8333V7.49984H9.16663V5.83317ZM9.16663 9.1665H10.8333V14.1665H9.16663V9.1665ZM9.99996 1.6665C5.39996 1.6665 1.66663 5.39984 1.66663 9.99984C1.66663 14.5998 5.39996 18.3332 9.99996 18.3332C14.6 18.3332 18.3333 14.5998 18.3333 9.99984C18.3333 5.39984 14.6 1.6665 9.99996 1.6665ZM9.99996 16.6665C6.32496 16.6665 3.33329 13.6748 3.33329 9.99984C3.33329 6.32484 6.32496 3.33317 9.99996 3.33317C13.675 3.33317 16.6666 6.32484 16.6666 9.99984C16.6666 13.6748 13.675 16.6665 9.99996 16.6665Z"
                            fill="#C2850D" />
                    </svg>
                    <p class="p-0 m-0">All chats are confidential and not shared with external models or third parties.
                    </p>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-3 gap-2" style="width: 50%;">
                <CustomSubmit :outline="true" @action="goBack" title="Previous"></CustomSubmit>
                <CustomSubmit title="Next" @action="setNext('limits')"></CustomSubmit>
            </div>
        </div>


        <div class="w-100 d-flex gap-3 limitations justify-content-center mt-5" v-if="step == 'limits'">
            <div class="limitation-wrapper">
                <div class="w-100 d-flex gap-2 p-2 flex-column align-items-center justify-content-center">
                    <img src="/assets/dashboard/icons/aiicons/examples.png" alt="">
                    <p class="p-0 m-0 content-style-header">Examples</p>
                </div>
                <p class="p-0 m-0 content-style-description">I want to invest 100M in a Repo for 12 months</p>
                <p class="p-0 m-0 content-style-description">What type of Repo’s can I invest in today with a sum of
                    100M</p>
                <p class="p-0 m-0 content-style-description">
                    How do I maximise my Repo investment portfolio
                </p>
            </div>
            <div class="limitation-wrapper">
                <div class="w-100 d-flex gap-2 p-2 flex-column align-items-center justify-content-center">
                    <img src="/assets/dashboard/icons/aiicons/capabilities.png" alt="">
                    <p class="p-0 m-0 content-style-header">Capabilities</p>
                </div>
                <p class="p-0 m-0 content-style-description">Remembers what user said earlier in the conversation.</p>
                <p class="p-0 m-0 content-style-description">Allows user to provide follow-up corrections.
                </p>
                <p class="p-0 m-0 content-style-description">
                    Trained to decline inappropriate requests.
                </p>
            </div>
            <div class="limitation-wrapper">
                <div class="w-100 d-flex gap-2 p-2 flex-column align-items-center justify-content-center">
                    <img src="/assets/dashboard/icons/aiicons/limitations.png" alt="">
                    <p class="p-0 m-0 content-style-header">Limitations</p>
                </div>
                <p class="p-0 m-0 content-style-description">May occasionally generate incorrect information.</p>
                <p class="p-0 m-0 content-style-description">May occasionally produce harmful instructions or biased
                    content.</p>
                <p class="p-0 m-0 content-style-description">
                    Limited knowledge of world andevents after 2021.
                </p>
            </div>
        </div>
        <div class="d-flex justify-content-end mt-3 gap-2" v-if="step == 'limits'" style="width: 80%;">
            <CustomSubmit :outline="true" @action="setNext('intro')" title="Previous"></CustomSubmit>
            <CustomSubmit title="Start" @action="setNext('giveoffers')"></CustomSubmit>
        </div>
        <div class="row" style="height: 70vh; margin-top: 20px" v-if="step == 'giveoffers'">
            <div class="col-md-8">
                <div class="chat-box chat-container">
                    <div class="chat-messages" ref="messagesContainer" id="chat-messages">
                        <!-- Chat messages will go here -->
                        <div class="messagebox d-flex gap-2 mb-2">
                            <img src="/assets/dashboard/icons/aiicons/ai-icon-logo.svg"
                                style="width: 30px;height: 30px;" alt="">

                            <div class="message-box-message">Hi! I'm here to help you with your Repo investments. To
                                start, can you tell me your investment amount?
                            </div>
                        </div>
                        <!-- {{ messages }} -->
                        <template v-if="messages">
                            <!-- <template v-if="message.role=='system'">
                            </template> -->
                            <div class="messagebox d-flex gap-2 mb-2" v-for="message in  messages "
                                v-if="message.role != 'system'">

                                <template v-if="message.role == 'user'">
                                    <div class="d-flex flex-column justify-content-start align-items-start">
                                        <div class="d-flex gap-2">
                                            <div style="width: 30px;height: 30px;flex-shrink: 0; border-radius: 100%; padding: 5px ;border: 2px solid #D9D9D9; cursor: pointer;"
                                                class="d-flex justify-content-center align-items-center">
                                                <avatar v-if="!organization?.logo" :size="30" :color="'white'"
                                                    :backgroundColor="'#4975E3'" :initials="organization?.name[0]">
                                                </avatar>
                                                <img v-else style="width: 30px;height: 30px; border-radius: 100%"
                                                    :src="'/image/' + organization?.logo" />
                                            </div>
                                            <!-- <img src="/assets/dashboard/icons/aiicons/ai-icon-logo.svg"
                                                style="width: 30px;height: 30px;" alt=""> -->
                                            <div class="message-box-message">{{ message.content }}
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2 mt-2" style="margin-left: 40px;"> <span
                                                class="curseor-stylers" @click="copyToClipboard(message.content)"><i
                                                    class="fa fa-clone" style="font-size: 12px" aria-hidden="true"></i>
                                            </span>
                                            <span class="curseor-stylers" @click="readAloud(message.content)">
                                                <i class="fa fa fa-volume-up" style="font-size: 12px"
                                                    aria-hidden="true"></i>
                                            </span>
                                        </div>
                                    </div>
                                </template>


                                <template v-else-if="message.role == 'assistant'">

                                    <div class="d-flex flex-column justify-content-start align-items-start">
                                        <div class="d-flex gap-2">
                                            <img src="/assets/dashboard/icons/aiicons/ai-icon-logo.svg"
                                                style="width: 30px;height: 30px;" alt="">

                                            <div class="messagebox d-flex gap-2 flex-column">

                                                <div class="message-box-message">
                                                    {{ JSON.parse(message.content)["Your message"] }}
                                                </div>
                                                <div class="message-box-message"
                                                    v-if="JSON.parse(message.content)['next_question']">
                                                    {{ JSON.parse(message.content)["next_question"] }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2 mt-2" style="margin-left: 40px;"> <span
                                                class="curseor-stylers"
                                                @click="copyToClipboard(JSON.parse(message.content)['Your message'])"><i
                                                    class="fa fa-clone" style="font-size: 12px" aria-hidden="true"></i>
                                            </span>
                                            <span class="cursor-stylers"
                                                @click="readAloud(`${JSON.parse(message.content)['Your message']} ${JSON.parse(message.content)['next_question']}`)">

                                                <i class="fa fa fa-volume-up" style="font-size: 12px"
                                                    aria-hidden="true"></i>

                                            </span>

                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>


                    </div>
                    <div class="w-100 d-flex flex-wrap gap-2 my-2 justify-content-center"
                        v-if="suggestions && suggestions.length > 0">
                        <div class="ai-suggest" @click="aiSuggest(suggestion)" v-for=" suggestion  in  suggestions "
                            :key="suggestion">
                            {{ suggestion }}
                        </div>


                    </div>
                    <div class="d-flex justify-content-center align-items-center gap-2 chat-send-message-box">
                        <div class="spinner-grow text-primary" role="status" v-if="islistening"
                            style="width: 20px; height: 20px">
                            <!-- <span class="sr-only">Loading...</span> -->
                        </div>
                        <img v-else src="/assets/dashboard/icons/aiicons/microphone.svg" style="cursor: pointer" alt=""
                            @click="listenToCommand()">
                        <i class="fa fa-volume-off" style="font-size: 22px" v-if="isMuted" @click="isMuted = !isMuted"
                            aria-hidden="true"></i>
                        <i class="fa fa fa-volume-up" style="font-size: 22px" v-else @click="isMuted = !isMuted"
                            aria-hidden="true"></i>
                        <!-- <img src="/assets/dashboard/icons/aiicons/image-upload.svg" alt=""> -->

                        <textarea v-model="user_input" class="chat-input" id="chat-input"
                            placeholder="Type a message..."></textarea>

                        <div v-if="subbmitting" class="spinner-border text-primary" style="width: 20px; height: 20px"
                            role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <img v-else style="cursor: pointer" @click="doSumbit"
                            src="/assets/dashboard/icons/aiicons/submit.svg" alt="">

                    </div>

                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex mb-20"
                    style="display: flex;padding: 10px;justify-content: center;align-items: center;gap: 10px;align-self: stretch; border-radius: 10px;background: #EFF2FE; width: 300px;">
                    <img src="/assets/dashboard/icons/aiicons/ai-icon-logo.svg">
                    <p class="p-0 m-0"
                        style="color:  #5063F4;font-family: Montserrat;font-size: 14px;font-style: normal;font-weight: 300;line-height: normal;">
                        Your offer summary will be updated below as you continue to prompt the model</p>

                </div>
                <template v-if="captured_data">


                    <AiSummaryScreenCards v-for="( offer, index ) in  captured_data " :key="index" :index="index"
                        :repo_details="offer">
                    </AiSummaryScreenCards>
                </template>
                <template v-else>
                    <!-- <AiSummaryScreenCards></AiSummaryScreenCards> -->
                </template>
            </div>
        </div>


    </div>
</template>
<script>
    import { capitalize } from '../../../../utils/commonUtils';
    import AiSummaryScreenCards from './shared/AiSummaryScreenCards.vue';


    import CustomSubmit from '../../../auth/signup/shared/CustomSubmit.vue'
    import ShowCG from '../../shared/ShowCG.vue';
    import Avatar from 'vue-avatar';


    export default {
        props: ['organization', 'apikey'],
        components: { AiSummaryScreenCards, CustomSubmit, ShowCG, Avatar },
        beforeMount() {
            this.getCollateralGivers()
            this.getAllDayCounts()
            this.getPrefferedCollateralTypes()
            if (window.localStorage.getItem('step')) {
                this.step = window.localStorage.getItem('step')
            }
            // console.log(this.organization)

        },
        mounted() {
            console.log(this.apikey, "API Key")
            window.localStorage.setItem('conversationHistory', null)
            if (this.step == 'giveoffers') {
                this.$nextTick(() => {
                    const messagesContainer = this.$refs.messagesContainer;
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                });
            }
        },
        beforeUpdate() {
            let messages = window.localStorage.getItem('conversationHistory', null)
        },
        created() {
            document.addEventListener('keydown', (e) => {
                if (e.key == 'Enter') {
                    if (!this.subbmitting) {
                        this.doSumbit()
                    }
                }
            });
        },
        data() {
            return {
                user_input: null,
                subbmitting: false,
                captured_data: null,
                next_question: null,
                messages: null,
                islistening: false,
                isMuted: true,
                possiblefis: null,
                cgs: null,
                daycounts: null,
                possibledcs: null,
                suggestions: null,
                step: 'intro',
                colaterals: null,
                colateralsobjs: [],
            }
        },
        methods: {
            setNext(step) {
                this.step = step
                window.localStorage.setItem('step', step)
            },
            goBack() {
                window.location.href = '/repos/post-request'
            },
            async getPrefferedCollateralTypes() {
                let colaterals = []
                await axios.get('/common/trade/get-preferred-collateral-list?status=ACTIVE').then(response => {
                    const collateral = response.data
                    colaterals.push({ "id": 0, 'text': 'No Preffered' })
                    // this.colateralsobjs.push('No Preffered')

                    collateral.forEach(element => {
                        colaterals.push({ "id": element.id, 'text': element.collateral_name })
                        this.colateralsobjs.push(element.collateral_name)

                    });
                });
                this.colaterals = colaterals
                // this.$store.commit('repopostrequest/' + types.ADD_PREFFERED_COLLATERAL, colaterals);
            },
            getAllDayCounts() {
                axios.get('/common/trade/get-all-interest-calculation-options?status=ACTIVE').then(res => {
                    //    this.holidays=res?.data?.holidays
                    if (res.data.length > 0) {
                        this.daycounts = res.data
                        // console.log(this.daycounts, "day count")
                        // this.possibledcs = this.daycounts.find(item)
                        this.possibledcs = this.daycounts.map(obj => obj.label);


                    }
                    // // console.log(res.data, "Holdays")
                    // this.formattedtimezone = JSON.stringify(res.data)
                })
            },
            async getCollateralGivers() {
                let invited = []
                await axios.get('/common/trade/get-collateral-givers').then(response => {
                    const FIs = response.data
                    const orgs = [...FIs]
                    // // console.log("Fis ", response.data.data)
                    this.cgs = FIs
                    orgs.forEach(element => {
                        invited.push(element.name)
                    });
                    this.possiblefis = invited
                });
            },
            aiSuggest(value) {
                this.user_input = value
                this.doSumbit()
            },
            doSumbit() {
                if (this.user_input.length > 1) {
                    this.subbmitting = true

                    const ai_response = this.fetchAIResponse(this.user_input)
                }

            },

            async fetchAIResponse(userMessage, role = 'user') {
                this.suggestions = null
                //const role = "system";
                const content = `You are "YieldGenie" a helpful assistant "https://yieldexchange.ca", developed and made for Yield Exchange, guiding a user through filling out a financial form in JSON format without explicitly mentioning it's a REPO (Repurchase Agreement) investment. Always reply in the **exact JSON format** shown below, regardless of the user input or data already gathered. **Please reflect on your answer for errors or ambiguity before responding.** 

Gather each piece of information one at a time, confirming each entry as follows:
- Dynamic Naming for REPO Headers: Each REPO request is labeled by collateral type and sequential number for clarity. New requests start as "repo"; after specifying the collateral, the label updates to reflect this type (e.g.${this.colateralsobjs}). This ensures each REPO is uniquely identifiable and easy to track.
- Investment Amount: Confirm the amount they wish to invest (e.g., $100 M, or B).
- Term Length: Ask for the duration (e.g., 7 days, 30 days, 12 months, 2years).
- Settlement Date: Get the specific settlement date, 'date's after today' ${new Date().toString()} (e.g., Dec 9 2024) and should be return in the format Month Day Year.
- Currency: Ask for the investment currency  (e.g., USD, CAD) and note we only support CAD and USD anyhting else prompt the user to renter.
- Day Count Convention: Determine the day count convention (e.g., ${this.possibledcs}).
- Day Count Convention: Ensure that all the options given for the day count convetion are in the list given here ${this.possibledcs}.
- Collateral Type: Ask for the type of collateral (e.g.${this.colateralsobjs}).
- Invited Collateral Givers: Confirm if specific collateral givers are invited (e.g.${this.possiblefis}) or all selecetd
- Select collateral givers from the lists given to you ie ${this.possiblefis} and if this list is provided only return it as the only option
- When the user indicates they want to select all collateral givers, capture and return the specific names of all collateral givers, and NOT a  general statement such as 'All' or 'All Selected'.
- Ensure that the collateral type returned is one of the following: ${this.collateralsobjs}.
- if the user suggest to invest amount equal or greater than 1 trillion please do not acept that, prompt them to change the amount
- if the user selects term length in years pleass convert it to months(Mandatory)
- Here's the rewritten version of your sentence:
- The term length should be a number eg (1,2,3...), not written in words (e.g., one,two,thress...) the term length value should be Months or Days only to return a value of format (2 Months).
- Restrict the user to only choosing one collateral type not more than that
-if user select more than one value in Collateral giver section return the organizations separated by comma
- follow the instructions given
User can add more than one investment, and you can prompt them to do that!

Output Format:
{
    "Your message": "A very brief friendly or confirmatory message (e.g., 'Got it!') or additional text like 'Form completed!'",
    "captured_data": [
        {
            "dynamic_REPO_headers": "Each REPO request is labeled by collateral type and sequential number for clarity. New requests start as just "repo", then "Repo 2"; after specifying the collateral, the label updates to reflect this type (e.g.${this.colateralsobjs}). **get the pattern of the naming convention, not just the examples**. This ensures each REPO is uniquely identifiable and easy to track,, **don't leave blank**",
            "investment_amount": "value or leave empty",
            "term_length": "value or leave empty",
            "settlement_date": "value or leave empty",
            "currency": "value or leave empty",
            "day_count_convention": "value or leave empty",
            "collateral_type": "value or leave empty",
            "invited_collateral_givers": "value or leave empty"
        }//if more than one use this comma, else don't,
        //if user adds another repo add them here
    ],
    "read_more_link": ["first link if needed", "second link needed"],
    "predictedNextAnsOrQuestion": ["First (most likely next answer only)", "Second (second most likely next answer only)", "Third (third most likely next answer only)"],Always have this filled in when necessary
    "progress": [
            "(Enter the number of filled fields, e.g., 1, 2, 4, 7, or 0 if none)",
            "(If there is more than one investment, include the next investment number; otherwise, do not include this value)"
        ],
    "next_question": "The very brief next question prompting for missing details with examples (or 'All details captured!' if complete)",
    "submit": "default false, but if done with fields input, ask if user wants to submit or add, if submit change this to true (don't set true if missing in any field)",
    "confirm_submit": "if system confirms data submitted make this true else false",
    "show_saved_data": "if user asks to see past data set this to true else false"
}
more info:
- know that today is on date ${new Date().toString()} and use it to ansure better
- Use a more interactive manner in conversations.
- be very brief
- if all field are filled ask if 'add another' or 'submit this' always
 - if system didn't confirm_submit or user did not ask for delete always keep the recorded info in json responce **to avoid user getting a bad experince** but if system confirm_submit submit or user says you delete, go ahead and also set the progress to back to [0]==0.
- If all the fields are empty (None) and user_input is empty, you have to start the conversation appropriately. Otherwise you should continue the conversation with the knowledge on previous dialogues until all the fields are filled. If all the fields are completed finalise the conversation.
**After collecting all necessary data for one repo investment, ask whether the user need to start a new repo investment and start a new conversation for the new repo investment.**
- You can prompt the user to ansure all questions at a go, or two, three takes but if they want one by one let them
- Do not rush to complete the fields by overwhelming user with multiple questions, but also allow those who want to do that.
- Clarify any doubts in the user's response.
- Use your knowledge on repo investment and provide recommendations and options when asking questions from the user.
- Provide examples if necessary to the user, and also feel free to provide feedback to the user about their decisions.


- If the user requires clarifications in anything related to the investment, provide sources related to the matter. Here are some of the examples.
e.g.: - ICMA (International Capital Market Association)
        - Investopedia
        For Canadian specific REPO information:
        - Bank of Canada - Securities Repo
        - IIAC - Repo Markets
        - CDS - Repo Clearing and Settlement
        - Clearstream’s Triparty Repo Service
        For North America specific cases:
        - Federal Reserve Bank of New York - Repurchase Agreement Operations
        - Securities Industry and Financial Markets Association (SIFMA)
        - U.S. Securities and Exchange Commission (SEC)
      Provide "Read more" links if possible.   Use your knowledge on REPO investment information sources specific to other regions such as Europe, UK etc.
** Strictly adhere to the following output format. Otherwise it might disrupt the parsing the response.**

End Format:

- **All fields** in "captured_data" must always appear in the response, even if they are empty.
- Do not add any extra text or variation outside of this JSON format.
- For casual greetings or simple phrases (e.g., 'Hi'), still respond in this JSON format, leaving all fields in "captured_data" empty if no data is available.
- Use "next_question" to prompt for missing information, or 'All details captured!' if the form is complete.

This JSON format is required for every response, without exceptions.`;

                this.subbmitting = true
                let conversationHistory = JSON.parse(localStorage.getItem("conversationHistory")) || [
                    {
                        role: "system",
                        content: content,
                    }
                ];
                if (!this.checkInternetConnection()) {
                    alert("Lost internet connection!");
                    return;
                }
                conversationHistory.push({ role: role, content: userMessage });

                const conversationLimit = 4;
                const recentHistory = conversationHistory.slice(-conversationLimit);

                const systemMessage = {
                    role: role,
                    content: content
                };
                const messages = [systemMessage, ...recentHistory];

                const response = await fetch("https://api.openai.com/v1/chat/completions", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Authorization": `Bearer ${this.apikey}`
                    },
                    body: JSON.stringify({
                        model: "gpt-4",
                        messages: messages
                    })
                });

                const data = await response.json();
                const assistantResponse = data.choices[0].message.content;

                conversationHistory.push({ role: "assistant", content: assistantResponse });
                localStorage.setItem("conversationHistory", JSON.stringify(conversationHistory));

                this.messages = conversationHistory
                let suggestions = []
                // displayResponseAsInfoCard(assistantResponse); // Display response in info card format
                if (assistantResponse) {
                    const responseObject = JSON.parse(assistantResponse);
                    // // console.log(responseObject)

                    this.captured_data = responseObject['captured_data']

                    this.next_question = responseObject['next_question']

                    this.suggestions = responseObject['predictedNextAnsOrQuestion']
                    if (responseObject['submit']) {
                        this.processSubmission()
                    }
                    // // console.log(this.captured_data)
                    // // console.log(this.next_question)
                }
                // }
                this.user_input = null
                this.subbmitting = false

                this.$nextTick(() => {
                    const messagesContainer = this.$refs.messagesContainer;
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                });
                return assistantResponse;
            },
            chooseRecommendation(data) {
                const escapedData = data.replace(/'/g, "\\\\'");
                const transcribedText = document.getElementById("userInput");
                const submitButton = document.getElementById("submitButton");

                // // console.log(escapedData);
                transcribedText.value = escapedData;
                submitButton.click();
            },
            checkInternetConnection() {
                if (!navigator.onLine) {
                    alert("No internet connection. Please check your network and try again.");
                    return false;
                }
                return true;
            },
            listenToCommand() {
                this.islistening = true;
                // console.log("Listen Initiated");
                this.speechRecognition();
            },

            speechRecognition() {
                // Check if the Web Speech API is available
                const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

                if (SpeechRecognition) {
                    // console.log("Speech is activated");
                    const recognition = new SpeechRecognition();
                    recognition.continuous = false; // Stop after a single result
                    recognition.interimResults = false; // Do not show partial results
                    recognition.start();

                    recognition.addEventListener('result', (event) => {
                        const transcript = event.results[0][0].transcript;
                        this.user_input = transcript;

                    });

                    // Listen for the 'end' event and set islistening to false when recognition stops
                    recognition.addEventListener('end', () => {
                        this.islistening = false; // Stop listening when recognition ends
                        // console.log("Microphone off, listening stopped.");
                        this.doSumbit()
                    });

                    // Handle error cases (e.g., no speech input)
                    recognition.addEventListener('error', (event) => {
                        // console.error("Speech recognition error", event);
                        this.islistening = false; // Stop listening on error
                    });

                } else {
                    // console.log("Speech recognition not supported in this browser.");
                }

            },

            processSubmission() {
                this.submitting = true
                let selected_organization = [];
                let collateral = [];

                // Split the string into an array of organizations
                let organizations = this.captured_data[0].invited_collateral_givers
                    .split(',')               // Split by commas
                    .map(item => item.trim());
                // console.log(organizations, "Organization")

                // Find the IDs of the selected organizations by matching the names
                selected_organization = organizations
                    .map(name => this.cgs.find(item => item.name === name)) // Find the organization by name
                    .filter(foundcg => foundcg) // Remove any undefined/null results where no match was found
                    .map(foundcg => foundcg.id); // Extract the ID
                // console.log(this.cgs, "CGS")

                let offers = []
                this.captured_data.forEach(element => {
                    let daycount = this.daycounts.find(item => item.label == element.day_count_convention)
                    let collateral_type = this.colaterals.find(item => item.text == element.collateral_type)
                    if (collateral_type) {
                        collateral.push(collateral_type.id)
                    } else {

                        collateral.push(0)
                    }
                    let term_length_type = element.term_length ? element.term_length.split(' ')[1].toLowerCase() : 'Days'
                    let term_length_type_return = "Days"
                    if (term_length_type == 'days' || term_length_type == 'day')
                        term_length_type_return = "Days"
                    else {
                        term_length_type_return = "Months"
                    }
                    let offer = {
                        'trade_date': this.formatDate(element.settlement_date),
                        'term_length': element.term_length.split(' ')[0],
                        'term_length_type': capitalize(term_length_type_return),
                        'currency': element?.currency,
                        'investment_amount': this.convertToNumber(element?.investment_amount),
                        'preferred_collateral': collateral,
                        'settlementDate': this.formatDate(element.settlement_date),
                        'convention_id': daycount ? daycount.id : 1
                    }
                    offers.push(offer)

                })


                var data = new FormData()
                data.append('tradeRequests', JSON.stringify(offers))
                data.append('invited', JSON.stringify(selected_organization))
                axios.post('/trade/CT/create-request', data).then(response => {
                    this.confirmsubmit = false
                    if (response.data.success) {
                        setTimeout(() => {
                            this.submitting = false
                            window.location.href = '/repos/repos-reviews'
                        }, 3000)
                    } else {
                        this.submitting = false

                    }
                }).catch(err => {
                    this.submitting = false

                })
            },
            formatDate(dateString) {
                const date = new Date(dateString); // Create a Date object from the string

                // Get the individual parts (year, month, day)
                const year = date.getFullYear();
                const month = date.getMonth() + 1; // Months are 0-indexed, so we add 1
                const day = date.getDate();

                // Return in the desired format "YYYY-M-D"
                return `${year}-${month}-${day}`;
            },
            convertToNumber(value) {
                // Trim the string and remove any dollar sign or unnecessary spaces
                value = value.trim().replace(/[^\d\.\,a-zA-Z]/g, '').toLowerCase(); // Remove all non-numeric and non-alphabet characters

                // Define a mapping of suffixes to multipliers (support 'k', 'm', 'b', 't', and full words)
                const multipliers = {
                    'k': 1000,
                    'm': 1000000,
                    'b': 1000000000,
                    't': 1000000000000,
                    'million': 1000000,
                    'billion': 1000000000,
                    'trillion': 1000000000000
                };

                // Regular expression to match numbers with optional decimal and suffix
                const validFormat = /^(\d+(\.\d*)?|\.\d+)([kmbt]|million|billion|trillion)?$/;

                // If the value doesn't match the valid format, return null (invalid input)
                if (!validFormat.test(value)) {
                    return null; // Invalid input
                }

                // Loop over the multiplier suffixes and check if the value ends with one
                for (let suffix in multipliers) {
                    if (value.endsWith(suffix)) {
                        // Extract the numeric part and convert it
                        let numPart = value.replace(suffix, '').trim();
                        // Handle numbers with commas (e.g., "1,000") or decimals (e.g., "1.5k")
                        let num = parseFloat(numPart.replace(/,/g, ''));

                        // Multiply the number by the appropriate multiplier
                        return num * multipliers[suffix];
                    }
                }

                // If no suffix is found, just return the parsed number
                return parseFloat(value);
            },

            copyToClipboard(text) {
                navigator.clipboard.writeText(text)
                    .then(() => {
                        // window.alert("Text copied to clipboard!", text);
                    })
                    .catch(err => {
                        // console.error("Failed to copy text to clipboard", err);
                    });
            },
            readAloud(value) {
                if (this.isMuted == true) {
                    return;
                }
                const escapedData = value.replace(/'/g, "\\\\'");
                // console.log(escapedData);

                // Create new utterance
                const utterance = new SpeechSynthesisUtterance(escapedData);

                // Get speech synthesis
                const synth = window.speechSynthesis;

                // Get available voices
                const voices = synth.getVoices();

                // Find a male voice (preferably English)
                const maleVoice = voices.find(voice =>
                    voice.gender === 'male' ||
                    //voice.name.includes('Male') ||
                    //voice.name.includes('David') ||
                    voice.name.includes('James')
                );

                // Set the voice if found
                if (maleVoice) {
                    utterance.voice = maleVoice;
                }

                // Cancel any ongoing speech
                synth.cancel();

                // Speak the new text
                synth.speak(utterance);

            }

        }
    }
</script>
<style scoped>
    .curseor-stylers {
        cursor: pointer;
    }

    .spinner-blue {
        height: 20px;
        width: 20px;
        color: #5063F4;
    }

    .message-box-message {
        color: var(--Yield-Exchange-Pallette-Yield-Exchange-Black, var(--Yield-Exchange-Colors-Darks, #252525));

        /* Yield Exchange Text Styles/Chat Text */
        font-family: Montserrat;
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
        border-radius: var(--8, 8px);
        background: var(--Yield-Exchange-Colors-Yield-Exchange-Light-Blue, #EFF2FE);

        display: flex;
        padding: 10px;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
        align-self: stretch;
    }

    .chat-container {
        height: 80vh;
        background-color: white;
        /* border: 1px solid #ccc; */
        border-radius: 5px;
        /* width: 400px; */
        /* height: 500px; */
        display: flex;
        flex-direction: column;
        /* box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); */
    }

    .chat-messages {
        flex: 1;
        padding: 10px;
        overflow-y: auto;
        /* border-bottom: 1px solid #ccc; */
    }

    .chat-send-message-box {
        border-radius: var(--16, 16px);
        border: 0.5px solid var(--Yield-Exchange-Pallette-Yield-Exchange-Light-Grey, #D9D9D9);
        background: var(--white-100, #FFF);
        box-shadow: 0px 6px 10px 2px rgba(80, 99, 244, 0.15);
        /* display: flex; */
        /* height: 56px; */
        padding: var(--16, 16px) var(--20, 20px);
        /* flex-direction: column; */
        justify-content: center;
        align-items: flex-start;
        gap: var(--4, 4px);
        align-self: stretch;
    }

    .chat-input {
        border: none;
        /* border-top: 1px solid #ccc; */
        padding: 10px;
        resize: none;
        width: 100%;
        box-sizing: border-box;
        /* color: var(--Yield-Exchange-Pallette-Yield-Exchange-Grey, #9CA1AA); */

        /* Yield Exchange Text Styles/Small Buttons */
        font-family: Montserrat;
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
        line-height: 18px;
        /* 128.571% */
    }

    .chat-input:focus {
        outline: none;
    }

    /* intoductsion */
    .ai-introductiion-yei {
        display: flex;
        width: 710px;
        padding: 40px;
        flex-direction: column;
        justify-content: flex-start;
        align-items: flex-start;
        gap: var(--20, 20px);
        border-radius: 14px;
        background: #FFF;
        box-shadow: 6px 2px 30px 10px rgba(112, 144, 176, 0.20);
    }

    .ai-assisted-header {
        color: var(--Yield-Exchange-Pallette-Yield-Exchange-Black, var(--Yield-Exchange-Colors-Darks, #252525));

        /* Yield Exchange Text Styles/Modal  & Blue BG Titles */
        font-family: Montserrat;
        font-size: 28px;
        font-style: normal;
        font-weight: 700;
        line-height: 32px;
        /* 114.286% */
        text-transform: capitalize;
    }

    .content-style-description {
        color: var(--Yield-Exchange-Pallette-Yield-Exchange-Black, var(--Yield-Exchange-Colors-Darks, #252525));
        font-feature-settings: 'liga' off, 'clig' off;
        font-family: Montserrat;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 18px;
        /* 112.5% */
    }

    .limitation-wrapper {
        display: flex;
        width: 280px;
        padding: 10px;
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
        flex-shrink: 0;
        border-radius: 8px;
        background: #EFF2FE;
    }

    .ai-notification p {
        color: #C2850D;

        /* Chat bot text */
        font-family: Montserrat;
        font-size: 14px;
        font-style: normal;
        font-weight: 300;
        line-height: normal;
    }

    .content-style-header {
        color: #5063F4;
        font-feature-settings: 'liga' off, 'clig' off;
        font-family: Montserrat;
        font-size: 20px;
        font-style: normal;
        font-weight: 500;
        line-height: 26px;
        /* 130% */
    }

    .ai-suggest {
        border-radius: 8px;
        background: #EFF2FE;
        color: #5063F4;
        font-feature-settings: 'liga' off, 'clig' off;
        font-family: Montserrat;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 26px;
        /* box-shadow: 0px 6px 10px 2px rgba(80, 99, 244, 0.15); */
        padding: 20px;
        cursor: pointer;
    }
</style>