<template>
    <div>
        <div v-if="choice === 'existing' && step === 2 && !cloned_existing || step > 2"
            style="width: 100%; height: 100px; min-width:1013px; position: relative">
            <div style="display: flex; flex-direction: row; ">
                <!-- step 1 -->
                <div style="width:21%;  height:40px; display: flex; flex-direction: column; ">
                    <div
                        style="display: flex; margin-left:15%; flex-direction: row; padding:3px 0px 3px 0px; align-items:center;">
                        <div style="width:20%; padding:0% !important; margin:0% !important;">
                            <svg v-if="step > 2" xmlns="http://www.w3.org/2000/svg" width="26" height="25"
                                viewBox="0 0 26 25" fill="none" style="margin-left: 20% !important;">
                                <circle cx="13" cy="12.5" r="12.5" :fill="activeStep(2)" />
                                <path
                                    d="M18.6044 7.56973C18.9159 7.67921 19.1858 7.88273 19.3767 8.15217C19.5675 8.42161 19.67 8.74367 19.67 9.07387V16.1589C19.67 16.9575 19.3528 17.7234 18.7881 18.2881C18.2234 18.8528 17.4575 19.17 16.6589 19.17H9.57387C9.24367 19.17 8.92161 19.0675 8.65217 18.8767C8.38273 18.6858 8.17921 18.4159 8.06973 18.1044L9.55545 18.1072H16.6589C17.1756 18.1072 17.6712 17.902 18.0366 17.5366C18.402 17.1712 18.6072 16.6756 18.6072 16.1589V9.07387L18.6044 9.03774V7.56973ZM16.3018 5C16.7246 5 17.1301 5.16795 17.429 5.46691C17.728 5.76586 17.8959 6.17134 17.8959 6.59412V15.8025C17.8959 16.2253 17.728 16.6308 17.429 16.9297C17.1301 17.2287 16.7246 17.3966 16.3018 17.3966H7.09412C6.67134 17.3966 6.26586 17.2287 5.96691 16.9297C5.66795 16.6308 5.5 16.2253 5.5 15.8025V6.59412C5.5 6.17134 5.66795 5.76586 5.96691 5.46691C6.26586 5.16795 6.67134 5 7.09412 5H16.3018ZM16.3018 6.06275H7.09412C6.9532 6.06275 6.81804 6.11873 6.71839 6.21839C6.61873 6.31804 6.56275 6.4532 6.56275 6.59412V15.8025C6.56275 16.0958 6.80081 16.3339 7.09412 16.3339H16.3025C16.4434 16.3339 16.5786 16.2779 16.6782 16.1782C16.7779 16.0786 16.8339 15.9434 16.8339 15.8025V6.59412C16.8339 6.4532 16.7779 6.31804 16.6782 6.21839C16.5786 6.11873 16.4427 6.06275 16.3018 6.06275ZM10.8711 11.6301L13.6265 8.87549C13.7214 8.78129 13.8484 8.72636 13.9821 8.72165C14.1157 8.71695 14.2462 8.76281 14.3476 8.8501C14.4489 8.93739 14.5136 9.05968 14.5287 9.19256C14.5439 9.32545 14.5083 9.45915 14.4292 9.56699L14.3775 9.6265L11.1893 12.8148C11.0901 12.9138 10.9559 12.9696 10.8157 12.9702C10.6756 12.9707 10.541 12.9159 10.4411 12.8176L10.3886 12.7581L9.3259 11.3411C9.24506 11.2338 9.20783 11.0999 9.22172 10.9663C9.23561 10.8327 9.29958 10.7093 9.40076 10.621C9.50195 10.5326 9.63283 10.4859 9.76708 10.4901C9.90133 10.4944 10.029 10.5493 10.1244 10.6439L10.1761 10.7034L10.8711 11.6301L13.6265 8.87549L10.8704 11.6301H10.8711Z"
                                    fill="#FDFDFD" />
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25"
                                fill="none">
                                <circle cx="12.5" cy="12.5" r="12.5" :fill="activeStep(2)" />
                                <path
                                    d="M8.375 7.4H7V6H18V7.4H16.625V8.8C16.625 9.9305 16.064 10.8405 15.3573 11.5839C14.8739 12.0921 14.2861 12.5604 13.6901 13C14.2861 13.4396 14.8739 13.9079 15.3573 14.4161C16.064 15.1595 16.625 16.0695 16.625 17.2V18.6H18V20H7V18.6H8.375V17.2C8.375 16.0695 8.936 15.1595 9.64275 14.4161C10.1261 13.9079 10.7139 13.4396 11.3099 13C10.7139 12.5604 10.1261 12.0921 9.64275 11.5839C8.936 10.8405 8.375 9.9305 8.375 8.8V7.4ZM9.75 7.4V8.8C9.75 9.2795 9.92875 9.7345 10.2801 10.2H14.7199C15.0706 9.7345 15.25 9.2795 15.25 8.8V7.4H9.75ZM12.5 13.8554C11.7816 14.372 11.1305 14.8641 10.6307 15.3898C10.5063 15.5197 10.3892 15.6566 10.2801 15.8H14.7199C14.6108 15.6566 14.4937 15.5197 14.3693 15.3898C13.8695 14.8641 13.2184 14.372 12.5 13.8554Z"
                                    fill="#FDFDFD" />
                            </svg>
                        </div>
                        <div v-bind:class="step > 2 ? 'active-step--' : 'inactive-step--'"
                            style="width: 80%; height:10%; font-size: 10px;  font-weight: 400; word-wrap: break-word; margin-left: %;">

                        </div>
                    </div>
                    <div v-bind:class="step == 2 ? 'activeStepText' : step > 2 ? 'completedStepText' : 'inactiveStepText'"
                        style="display: flex; justify-content: flex-start;">
                        {{ choice ===
                        "new" ?
                        "Choose Campaign type" : "Select existing campaigns" }}
                    </div>
                </div>
                <!-- close step 1 -->
                <!-- step 2 -->
                <div style="width:21%;  height:40px; display: flex; flex-direction: column; ">
                    <div style="display: flex; align-items:center; flex-direction: row; padding:3px 0px 3px 0px;">
                        <div v-bind:class="step > 2 ? 'active-step--' : 'inactive-step--'"
                            style="width: 20%; height:10%; font-size: 10px;  font-weight: 400; word-wrap: break-word; margin-left: 0%; margin-right: 0%;">

                        </div>
                        <div
                            style="width:20%;padding:0% !important; margin:0% !important; display: flex; justify-content: center;">
                            <svg v-if="step > 3" xmlns="http://www.w3.org/2000/svg" width="26" height="25"
                                viewBox="0 0 26 25" fill="none" style="margin-left: 20% !important;">
                                <circle cx="13" cy="12.5" r="12.5" :fill="activeStep(3)" />
                                <path
                                    d="M18.6044 7.56973C18.9159 7.67921 19.1858 7.88273 19.3767 8.15217C19.5675 8.42161 19.67 8.74367 19.67 9.07387V16.1589C19.67 16.9575 19.3528 17.7234 18.7881 18.2881C18.2234 18.8528 17.4575 19.17 16.6589 19.17H9.57387C9.24367 19.17 8.92161 19.0675 8.65217 18.8767C8.38273 18.6858 8.17921 18.4159 8.06973 18.1044L9.55545 18.1072H16.6589C17.1756 18.1072 17.6712 17.902 18.0366 17.5366C18.402 17.1712 18.6072 16.6756 18.6072 16.1589V9.07387L18.6044 9.03774V7.56973ZM16.3018 5C16.7246 5 17.1301 5.16795 17.429 5.46691C17.728 5.76586 17.8959 6.17134 17.8959 6.59412V15.8025C17.8959 16.2253 17.728 16.6308 17.429 16.9297C17.1301 17.2287 16.7246 17.3966 16.3018 17.3966H7.09412C6.67134 17.3966 6.26586 17.2287 5.96691 16.9297C5.66795 16.6308 5.5 16.2253 5.5 15.8025V6.59412C5.5 6.17134 5.66795 5.76586 5.96691 5.46691C6.26586 5.16795 6.67134 5 7.09412 5H16.3018ZM16.3018 6.06275H7.09412C6.9532 6.06275 6.81804 6.11873 6.71839 6.21839C6.61873 6.31804 6.56275 6.4532 6.56275 6.59412V15.8025C6.56275 16.0958 6.80081 16.3339 7.09412 16.3339H16.3025C16.4434 16.3339 16.5786 16.2779 16.6782 16.1782C16.7779 16.0786 16.8339 15.9434 16.8339 15.8025V6.59412C16.8339 6.4532 16.7779 6.31804 16.6782 6.21839C16.5786 6.11873 16.4427 6.06275 16.3018 6.06275ZM10.8711 11.6301L13.6265 8.87549C13.7214 8.78129 13.8484 8.72636 13.9821 8.72165C14.1157 8.71695 14.2462 8.76281 14.3476 8.8501C14.4489 8.93739 14.5136 9.05968 14.5287 9.19256C14.5439 9.32545 14.5083 9.45915 14.4292 9.56699L14.3775 9.6265L11.1893 12.8148C11.0901 12.9138 10.9559 12.9696 10.8157 12.9702C10.6756 12.9707 10.541 12.9159 10.4411 12.8176L10.3886 12.7581L9.3259 11.3411C9.24506 11.2338 9.20783 11.0999 9.22172 10.9663C9.23561 10.8327 9.29958 10.7093 9.40076 10.621C9.50195 10.5326 9.63283 10.4859 9.76708 10.4901C9.90133 10.4944 10.029 10.5493 10.1244 10.6439L10.1761 10.7034L10.8711 11.6301L13.6265 8.87549L10.8704 11.6301H10.8711Z"
                                    fill="#FDFDFD" />
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25"
                                fill="none">
                                <circle cx="12.5" cy="12.5" r="12.5" :fill="activeStep(3)" />
                                <path
                                    d="M8.375 7.4H7V6H18V7.4H16.625V8.8C16.625 9.9305 16.064 10.8405 15.3573 11.5839C14.8739 12.0921 14.2861 12.5604 13.6901 13C14.2861 13.4396 14.8739 13.9079 15.3573 14.4161C16.064 15.1595 16.625 16.0695 16.625 17.2V18.6H18V20H7V18.6H8.375V17.2C8.375 16.0695 8.936 15.1595 9.64275 14.4161C10.1261 13.9079 10.7139 13.4396 11.3099 13C10.7139 12.5604 10.1261 12.0921 9.64275 11.5839C8.936 10.8405 8.375 9.9305 8.375 8.8V7.4ZM9.75 7.4V8.8C9.75 9.2795 9.92875 9.7345 10.2801 10.2H14.7199C15.0706 9.7345 15.25 9.2795 15.25 8.8V7.4H9.75ZM12.5 13.8554C11.7816 14.372 11.1305 14.8641 10.6307 15.3898C10.5063 15.5197 10.3892 15.6566 10.2801 15.8H14.7199C14.6108 15.6566 14.4937 15.5197 14.3693 15.3898C13.8695 14.8641 13.2184 14.372 12.5 13.8554Z"
                                    fill="#FDFDFD" />
                            </svg>

                        </div>
                        <div v-bind:class="step > 3 ? 'active-step--' : 'inactive-step--'"
                            style=" width: 60%; height:10%; font-size: 10px;  font-weight: 400; word-wrap: break-word; margin-left: 0%;">

                        </div>
                    </div>
                    <div style="display: flex; justify-content: flex-start;margin-left: 11%;"
                        v-bind:class="step == 3 ? 'activeStepText' : step > 3 ? 'completedStepText' : 'inactiveStepText'">
                        {{ choice ===
                        "new" ?
                        "Add products" : "Add / Update products" }}
                    </div>
                </div>
                <!-- close step 2 -->
                <!-- step 2 -->
                <div style="width:21%;  height:40px; display: flex; flex-direction: column; ">
                    <div style="display: flex; align-items:center; flex-direction: row; padding:3px 0px 3px 0px;">
                        <div v-bind:class="step > 3 ? 'active-step--' : 'inactive-step--'"
                            style="width: 20%; height:10%; font-size: 10px;  font-weight: 400; word-wrap: break-word; margin-left: 0%;">

                        </div>
                        <div
                            style="width:20%;padding:0% !important; margin:0% !important; display: flex; justify-content: center;">
                            <svg v-if="step > 4" xmlns="http://www.w3.org/2000/svg" width="26" height="25"
                                viewBox="0 0 26 25" fill="none" style="margin-left: 20% !important;">
                                <circle cx="13" cy="12.5" r="12.5" :fill="activeStep(4)" />
                                <path
                                    d="M18.6044 7.56973C18.9159 7.67921 19.1858 7.88273 19.3767 8.15217C19.5675 8.42161 19.67 8.74367 19.67 9.07387V16.1589C19.67 16.9575 19.3528 17.7234 18.7881 18.2881C18.2234 18.8528 17.4575 19.17 16.6589 19.17H9.57387C9.24367 19.17 8.92161 19.0675 8.65217 18.8767C8.38273 18.6858 8.17921 18.4159 8.06973 18.1044L9.55545 18.1072H16.6589C17.1756 18.1072 17.6712 17.902 18.0366 17.5366C18.402 17.1712 18.6072 16.6756 18.6072 16.1589V9.07387L18.6044 9.03774V7.56973ZM16.3018 5C16.7246 5 17.1301 5.16795 17.429 5.46691C17.728 5.76586 17.8959 6.17134 17.8959 6.59412V15.8025C17.8959 16.2253 17.728 16.6308 17.429 16.9297C17.1301 17.2287 16.7246 17.3966 16.3018 17.3966H7.09412C6.67134 17.3966 6.26586 17.2287 5.96691 16.9297C5.66795 16.6308 5.5 16.2253 5.5 15.8025V6.59412C5.5 6.17134 5.66795 5.76586 5.96691 5.46691C6.26586 5.16795 6.67134 5 7.09412 5H16.3018ZM16.3018 6.06275H7.09412C6.9532 6.06275 6.81804 6.11873 6.71839 6.21839C6.61873 6.31804 6.56275 6.4532 6.56275 6.59412V15.8025C6.56275 16.0958 6.80081 16.3339 7.09412 16.3339H16.3025C16.4434 16.3339 16.5786 16.2779 16.6782 16.1782C16.7779 16.0786 16.8339 15.9434 16.8339 15.8025V6.59412C16.8339 6.4532 16.7779 6.31804 16.6782 6.21839C16.5786 6.11873 16.4427 6.06275 16.3018 6.06275ZM10.8711 11.6301L13.6265 8.87549C13.7214 8.78129 13.8484 8.72636 13.9821 8.72165C14.1157 8.71695 14.2462 8.76281 14.3476 8.8501C14.4489 8.93739 14.5136 9.05968 14.5287 9.19256C14.5439 9.32545 14.5083 9.45915 14.4292 9.56699L14.3775 9.6265L11.1893 12.8148C11.0901 12.9138 10.9559 12.9696 10.8157 12.9702C10.6756 12.9707 10.541 12.9159 10.4411 12.8176L10.3886 12.7581L9.3259 11.3411C9.24506 11.2338 9.20783 11.0999 9.22172 10.9663C9.23561 10.8327 9.29958 10.7093 9.40076 10.621C9.50195 10.5326 9.63283 10.4859 9.76708 10.4901C9.90133 10.4944 10.029 10.5493 10.1244 10.6439L10.1761 10.7034L10.8711 11.6301L13.6265 8.87549L10.8704 11.6301H10.8711Z"
                                    fill="#FDFDFD" />
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25"
                                fill="none">
                                <circle cx="12.5" cy="12.5" r="12.5" :fill="activeStep(4)" />
                                <path
                                    d="M8.375 7.4H7V6H18V7.4H16.625V8.8C16.625 9.9305 16.064 10.8405 15.3573 11.5839C14.8739 12.0921 14.2861 12.5604 13.6901 13C14.2861 13.4396 14.8739 13.9079 15.3573 14.4161C16.064 15.1595 16.625 16.0695 16.625 17.2V18.6H18V20H7V18.6H8.375V17.2C8.375 16.0695 8.936 15.1595 9.64275 14.4161C10.1261 13.9079 10.7139 13.4396 11.3099 13C10.7139 12.5604 10.1261 12.0921 9.64275 11.5839C8.936 10.8405 8.375 9.9305 8.375 8.8V7.4ZM9.75 7.4V8.8C9.75 9.2795 9.92875 9.7345 10.2801 10.2H14.7199C15.0706 9.7345 15.25 9.2795 15.25 8.8V7.4H9.75ZM12.5 13.8554C11.7816 14.372 11.1305 14.8641 10.6307 15.3898C10.5063 15.5197 10.3892 15.6566 10.2801 15.8H14.7199C14.6108 15.6566 14.4937 15.5197 14.3693 15.3898C13.8695 14.8641 13.2184 14.372 12.5 13.8554Z"
                                    fill="#FDFDFD" />
                            </svg>
                        </div>

                        <div v-bind:class="step > 4 ? 'active-step--' : 'inactive-step--'"
                            style="width: 60%; height:10%; font-size: 10px;  font-weight: 400; word-wrap: break-word; margin-left: 0%;">

                        </div>
                    </div>
                    <div style="display: flex; justify-content: flex-start;"
                        v-bind:class="step == 4 ? 'activeStepText' : step > 4 ? 'completedStepText' : 'inactiveStepText'">
                        {{ choice ===
                        "new" ?
                        "Select target Depositors" : "Add new campaign participants" }}
                    </div>
                </div>
                <!-- close step 2 -->
                <!-- step 2 -->
                <div style="width:21%;  height:40px; display: flex; flex-direction: column; ">
                    <div style="display: flex; align-items:center; flex-direction: row; padding:3px 0px 3px 0px;">
                        <div v-bind:class="step > 4 ? 'active-step--' : 'inactive-step--'"
                            style="width: 20%; height:10%; font-size: 10px;  font-weight: 400; word-wrap: break-word; margin-left: 0%;">

                        </div>
                        <div
                            style="width:20%;padding:0% !important; margin:0% !important; display: flex; justify-content: center;">
                            <svg v-if="step > 5" xmlns="http://www.w3.org/2000/svg" width="26" height="25"
                                viewBox="0 0 26 25" fill="none" style="margin-left: 20% !important;">
                                <circle cx="13" cy="12.5" r="12.5" :fill="activeStep(5)" />
                                <path
                                    d="M18.6044 7.56973C18.9159 7.67921 19.1858 7.88273 19.3767 8.15217C19.5675 8.42161 19.67 8.74367 19.67 9.07387V16.1589C19.67 16.9575 19.3528 17.7234 18.7881 18.2881C18.2234 18.8528 17.4575 19.17 16.6589 19.17H9.57387C9.24367 19.17 8.92161 19.0675 8.65217 18.8767C8.38273 18.6858 8.17921 18.4159 8.06973 18.1044L9.55545 18.1072H16.6589C17.1756 18.1072 17.6712 17.902 18.0366 17.5366C18.402 17.1712 18.6072 16.6756 18.6072 16.1589V9.07387L18.6044 9.03774V7.56973ZM16.3018 5C16.7246 5 17.1301 5.16795 17.429 5.46691C17.728 5.76586 17.8959 6.17134 17.8959 6.59412V15.8025C17.8959 16.2253 17.728 16.6308 17.429 16.9297C17.1301 17.2287 16.7246 17.3966 16.3018 17.3966H7.09412C6.67134 17.3966 6.26586 17.2287 5.96691 16.9297C5.66795 16.6308 5.5 16.2253 5.5 15.8025V6.59412C5.5 6.17134 5.66795 5.76586 5.96691 5.46691C6.26586 5.16795 6.67134 5 7.09412 5H16.3018ZM16.3018 6.06275H7.09412C6.9532 6.06275 6.81804 6.11873 6.71839 6.21839C6.61873 6.31804 6.56275 6.4532 6.56275 6.59412V15.8025C6.56275 16.0958 6.80081 16.3339 7.09412 16.3339H16.3025C16.4434 16.3339 16.5786 16.2779 16.6782 16.1782C16.7779 16.0786 16.8339 15.9434 16.8339 15.8025V6.59412C16.8339 6.4532 16.7779 6.31804 16.6782 6.21839C16.5786 6.11873 16.4427 6.06275 16.3018 6.06275ZM10.8711 11.6301L13.6265 8.87549C13.7214 8.78129 13.8484 8.72636 13.9821 8.72165C14.1157 8.71695 14.2462 8.76281 14.3476 8.8501C14.4489 8.93739 14.5136 9.05968 14.5287 9.19256C14.5439 9.32545 14.5083 9.45915 14.4292 9.56699L14.3775 9.6265L11.1893 12.8148C11.0901 12.9138 10.9559 12.9696 10.8157 12.9702C10.6756 12.9707 10.541 12.9159 10.4411 12.8176L10.3886 12.7581L9.3259 11.3411C9.24506 11.2338 9.20783 11.0999 9.22172 10.9663C9.23561 10.8327 9.29958 10.7093 9.40076 10.621C9.50195 10.5326 9.63283 10.4859 9.76708 10.4901C9.90133 10.4944 10.029 10.5493 10.1244 10.6439L10.1761 10.7034L10.8711 11.6301L13.6265 8.87549L10.8704 11.6301H10.8711Z"
                                    fill="#FDFDFD" />
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25"
                                fill="none">
                                <circle cx="12.5" cy="12.5" r="12.5" :fill="activeStep(5)" />
                                <path
                                    d="M8.375 7.4H7V6H18V7.4H16.625V8.8C16.625 9.9305 16.064 10.8405 15.3573 11.5839C14.8739 12.0921 14.2861 12.5604 13.6901 13C14.2861 13.4396 14.8739 13.9079 15.3573 14.4161C16.064 15.1595 16.625 16.0695 16.625 17.2V18.6H18V20H7V18.6H8.375V17.2C8.375 16.0695 8.936 15.1595 9.64275 14.4161C10.1261 13.9079 10.7139 13.4396 11.3099 13C10.7139 12.5604 10.1261 12.0921 9.64275 11.5839C8.936 10.8405 8.375 9.9305 8.375 8.8V7.4ZM9.75 7.4V8.8C9.75 9.2795 9.92875 9.7345 10.2801 10.2H14.7199C15.0706 9.7345 15.25 9.2795 15.25 8.8V7.4H9.75ZM12.5 13.8554C11.7816 14.372 11.1305 14.8641 10.6307 15.3898C10.5063 15.5197 10.3892 15.6566 10.2801 15.8H14.7199C14.6108 15.6566 14.4937 15.5197 14.3693 15.3898C13.8695 14.8641 13.2184 14.372 12.5 13.8554Z"
                                    fill="#FDFDFD" />
                            </svg>
                        </div>
                        <div v-bind:class="step > 5 ? 'active-step--' : 'inactive-step--'"
                            style=" width: 60%; height:10%; font-size: 10px;  font-weight: 400; word-wrap: break-word; margin-left: 0%;">

                        </div>
                    </div>
                    <div style="display: flex; justify-content: flex-start; margin-left: 15%;"
                        v-bind:class="step == 5 ? 'activeStepText' : step > 5 ? 'completedStepText' : 'inactiveStepText'">
                        Set your rates
                    </div>
                </div>
                <!-- close step 2 -->
                <!-- step 2 -->
                <div style="width:17%;  height:40px; display: flex; flex-direction: column; ">
                    <div
                        style="display: flex; align-items:center; justify-content: flex-end; margin-right: 50%; flex-direction: row; padding:3px 0px 3px 0px;">
                        <div v-bind:class="step > 5 ? 'active-step--' : 'inactive-step--'"
                            style="width: 80%; height:10%; font-size: 10px;  font-weight: 400; word-wrap: break-word; margin-right:20%; ">

                        </div>
                        <div
                            style="width:20%;padding:0% !important; margin:0% !important; display: flex; justify-content: center;">
                            <svg v-if="step > 6" xmlns="http://www.w3.org/2000/svg" width="26" height="25"
                                viewBox="0 0 26 25" fill="none">
                                <circle cx="13" cy="12.5" r="12.5" :fill="activeStep(6)" />
                                <path
                                    d="M18.6044 7.56973C18.9159 7.67921 19.1858 7.88273 19.3767 8.15217C19.5675 8.42161 19.67 8.74367 19.67 9.07387V16.1589C19.67 16.9575 19.3528 17.7234 18.7881 18.2881C18.2234 18.8528 17.4575 19.17 16.6589 19.17H9.57387C9.24367 19.17 8.92161 19.0675 8.65217 18.8767C8.38273 18.6858 8.17921 18.4159 8.06973 18.1044L9.55545 18.1072H16.6589C17.1756 18.1072 17.6712 17.902 18.0366 17.5366C18.402 17.1712 18.6072 16.6756 18.6072 16.1589V9.07387L18.6044 9.03774V7.56973ZM16.3018 5C16.7246 5 17.1301 5.16795 17.429 5.46691C17.728 5.76586 17.8959 6.17134 17.8959 6.59412V15.8025C17.8959 16.2253 17.728 16.6308 17.429 16.9297C17.1301 17.2287 16.7246 17.3966 16.3018 17.3966H7.09412C6.67134 17.3966 6.26586 17.2287 5.96691 16.9297C5.66795 16.6308 5.5 16.2253 5.5 15.8025V6.59412C5.5 6.17134 5.66795 5.76586 5.96691 5.46691C6.26586 5.16795 6.67134 5 7.09412 5H16.3018ZM16.3018 6.06275H7.09412C6.9532 6.06275 6.81804 6.11873 6.71839 6.21839C6.61873 6.31804 6.56275 6.4532 6.56275 6.59412V15.8025C6.56275 16.0958 6.80081 16.3339 7.09412 16.3339H16.3025C16.4434 16.3339 16.5786 16.2779 16.6782 16.1782C16.7779 16.0786 16.8339 15.9434 16.8339 15.8025V6.59412C16.8339 6.4532 16.7779 6.31804 16.6782 6.21839C16.5786 6.11873 16.4427 6.06275 16.3018 6.06275ZM10.8711 11.6301L13.6265 8.87549C13.7214 8.78129 13.8484 8.72636 13.9821 8.72165C14.1157 8.71695 14.2462 8.76281 14.3476 8.8501C14.4489 8.93739 14.5136 9.05968 14.5287 9.19256C14.5439 9.32545 14.5083 9.45915 14.4292 9.56699L14.3775 9.6265L11.1893 12.8148C11.0901 12.9138 10.9559 12.9696 10.8157 12.9702C10.6756 12.9707 10.541 12.9159 10.4411 12.8176L10.3886 12.7581L9.3259 11.3411C9.24506 11.2338 9.20783 11.0999 9.22172 10.9663C9.23561 10.8327 9.29958 10.7093 9.40076 10.621C9.50195 10.5326 9.63283 10.4859 9.76708 10.4901C9.90133 10.4944 10.029 10.5493 10.1244 10.6439L10.1761 10.7034L10.8711 11.6301L13.6265 8.87549L10.8704 11.6301H10.8711Z"
                                    fill="#FDFDFD" />
                            </svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25"
                                fill="none">
                                <circle cx="12.5" cy="12.5" r="12.5" :fill="activeStep(6)" />
                                <path
                                    d="M8.375 7.4H7V6H18V7.4H16.625V8.8C16.625 9.9305 16.064 10.8405 15.3573 11.5839C14.8739 12.0921 14.2861 12.5604 13.6901 13C14.2861 13.4396 14.8739 13.9079 15.3573 14.4161C16.064 15.1595 16.625 16.0695 16.625 17.2V18.6H18V20H7V18.6H8.375V17.2C8.375 16.0695 8.936 15.1595 9.64275 14.4161C10.1261 13.9079 10.7139 13.4396 11.3099 13C10.7139 12.5604 10.1261 12.0921 9.64275 11.5839C8.936 10.8405 8.375 9.9305 8.375 8.8V7.4ZM9.75 7.4V8.8C9.75 9.2795 9.92875 9.7345 10.2801 10.2H14.7199C15.0706 9.7345 15.25 9.2795 15.25 8.8V7.4H9.75ZM12.5 13.8554C11.7816 14.372 11.1305 14.8641 10.6307 15.3898C10.5063 15.5197 10.3892 15.6566 10.2801 15.8H14.7199C14.6108 15.6566 14.4937 15.5197 14.3693 15.3898C13.8695 14.8641 13.2184 14.372 12.5 13.8554Z"
                                    fill="#FDFDFD" />
                            </svg>
                        </div>
                    </div>
                    <div style="display: flex; justify-content: flex-end;"
                        v-bind:class="step == 6 ? 'activeStepText' : step > 6 ? 'completedStepText' : 'inactiveStepText'">
                        Review and save the Campaign
                    </div>
                </div>
                <!-- close step 2 -->

            </div>

        </div>
        <OKButtonActionMessageBox @okClicked="backToCampaign" :showm="showSuccessModal" :title="successModalTitle"
            size="md" @closedSuccessModal="backToCampaign" :showokbtn="false">
        </OKButtonActionMessageBox>
        <!-- edit side -->
        <!-- <div v-if="ready_fo_edit"> -->
        <Modal md-width="300px" :confirm_close_modal="true" :show="edit_modal_show">
            <ChooseEditStep @updateStepsClick="goToStep" :ready_fo_edit="ready_fo_edit" :userloggedin="userloggedin" />
        </Modal>
        <div v-if="editSteps">
            <Modal md-width="300px" :confirm_close_modal="true" :visible.sync="show"
                :show="show && !edit_modal_show && editSteps && ((step === 2 && choice === 'edit'))">
                <EditCampaignDetails :data="campaign_data" :collected_details_data="editInfo1"
                    :campaign_details_data.sync="editInfo1" v-if="(step === 2 && choice === 'edit')" :choice="choice"
                    :unformattedusertimezone="unformattedusertimezone" :formattedtimezone="formattedtimezone" />
            </Modal>
            <EditCampaignProduct @back-step="step = $event" :selected_products="selected_products" :data="campaign_data"
                :collected_details_data="editInfo1 ? editInfo1 : campaign_data" :products="products"
                @selectedItems="selectedItems" v-if="step == 3 && editSteps" :typeOfInvite="typeOfInvite"
                :doNotUseBackendProducts="doNotUseBackendProducts" @collected_rates="collectRates" />
            <EditTargetDepositors :selected_groups="selected_groups" @back-step="step = $event" :data="campaign_data"
                v-if="(typeOfInvite === '2') && step == 4 && editSteps" :depositors="depositors" :has_navigation="true"
                @selectedGroups="selectedGroups" />
            <EditBlanketedDepositors :selected_groups="selected_groups" @back-step="step = $event" :data="campaign_data"
                v-if="(typeOfInvite === '1') && step == 4 && editSteps" :depositors="depositors" :has_navigation="true"
                @selectedOrganizations="selectedOrganizations($event)" :typeOfInvite="typeOfInvite"
                :collected_details_data="info1" />
            <EditProductRates @back-step="step = $event" :data="campaign_data" :selected_products="selected_products"
                :collected_details_data="campaign_data" :selected_groups="selected_groups"
                @collected_rates="collectRates" v-if="step == 5 && editSteps"
                @updateDoNotUseBackendProductts="upDoNotUseBackendProductts"
                :doNotUseBackendProducts="doNotUseBackendProducts" />
            <EditProductRatesDirect @back-step="step = $event" :data="campaign_data"
                :selected_products="selected_products" :collected_details_data="campaign_data"
                :selected_groups="selected_groups" @collected_rates="collectRates" v-if="step == 7 && editSteps" />
        </div>
        <div v-if="multstepEdit">
            <Modal md-width="300px" :confirm_close_modal="true" :visible.sync="show"
                :show="multstepEdit && ((step === 2 && choice === 'edit'))">
                <EditCampaignDetails :data="campaign_data" :collected_details_data="editInfo1"
                    :campaign_details_data.sync="editInfo1" v-if="(step === 2 && choice === 'edit')" :choice="choice"
                    :unformattedusertimezone="unformattedusertimezone" :formattedtimezone="formattedtimezone"
                    :multstepEdit="multstepEdit" @goToEditStep="goToEditStep" />
            </Modal>
            <EditCampaignProduct @back-step="step = $event" :selected_products="selected_products" :data="campaign_data"
                :collected_details_data="editInfo1 ? editInfo1 : campaign_data" :products="products"
                @selectedItems="selectedItems" v-if="step == 3 && multstepEdit" :typeOfInvite="typeOfInvite"
                :doNotUseBackendProducts="doNotUseBackendProducts" :multstepEdit="multstepEdit"
                @goToEditStep="goToEditStep" />
            <EditTargetDepositors :selected_groups="selected_groups" @back-step="step = $event" :data="campaign_data"
                v-if="(typeOfInvite === '2') && multstepEdit && step==4" :depositors="depositors" :has_navigation="true"
                @selectedGroups="selectedGroups" :multstepEdit="multstepEdit" @goToEditStep="goToEditStep"
                @selectedGroupDepositors="selectedGroupDepositorsCount($event)" />
            <EditBlanketedDepositors :selected_groups="selected_groups" @back-step="step = $event" :data="campaign_data"
                v-if="(typeOfInvite === '1') && multstepEdit && step== 4" :depositors="depositors"
                :has_navigation="true" @selectedOrganizations="selectedOrganizations($event)"
                :typeOfInvite="typeOfInvite" :collected_details_data="info1" :multstepEdit="multstepEdit"
                @goToEditStep="goToEditStep" />
            <EditProductRates @back-step="step = $event" :data="campaign_data" :selected_products="selected_products"
                :collected_details_data="campaign_data" :selected_groups="selected_groups"
                @collected_rates="collectRates" v-if=" multstepEdit && step== 5"
                @updateDoNotUseBackendProductts="upDoNotUseBackendProductts"
                :doNotUseBackendProducts="doNotUseBackendProducts" :multstepEdit="multstepEdit"
                @goToEditStep="goToEditStep" :depos_count="depositorsCount" />
            <ReviewSummary @back-step="step = $event" :selected_products="selected_products"
                :collected_details_data="campaign_data" :selected_groups="selected_groups"
                :selected_organizations="selected_organizations" v-if="multstepEdit && step === 6"
                :type_of_invite="typeOfInvite" :isSavedDraftAlready="isDraftAlready" :multstepEdit="multstepEdit"
                :depos_count="depositorsCount" />
        </div>

        <!-- </div> -->


        <!-- <div class="bg-success">Hello {{ selected_products }}</div> -->


        <!-- end edi sectios -->

        <!-- creation side -->
        <div v-if="!multstepEdit && !editSteps">
            <Modal md-width="300px" :confirm_close_modal="true" :visible.sync="show"
                :show="show && !edit_modal_show && (step === 1 || (step === 2 && choice === 'new') || (choice === 'existing' && step === 2 && cloned_existing))">
                <Choose :choice.sync="choice" :show_modal.sync="show" v-if="step === 1" />
                <ChooseTypeOfInvite :choice.sync="choice" :show_modal.sync="show"
                    v-if="(typeOfInvite===0 )&& step === 2"
                    @Update_type_of_camapign="updateTypeOfCamapignInvite($event)" />
                <NewCampaignDetails :data="campaign_data" :collected_details_data="info1"
                    :campaign_details_data.sync="info1"
                    v-if="(typeOfInvite > 0 && step === 2 && choice === 'new') || (choice === 'existing' && step === 2 && cloned_existing)"
                    :choice="choice" :unformattedusertimezone="unformattedusertimezone"
                    :formattedtimezone="formattedtimezone" />
            </Modal>
            <CloneExistingCampaign @choice="choice = $event" @back-step="step = $event"
                v-if="(typeOfInvite === 0) &&  step === 2 && choice === 'existing' && !cloned_existing"
                @selectedExistingCampaign="selectedExistingCampaign1" />
            <AddProduct @back-step="step = $event" :selected_products="selected_products" :data="campaign_data"
                :collected_details_data="info1" :products="products"
                v-if="(typeOfInvite > 0) && step === 3 && !editSteps" @selectedItems="selectedItems"
                @persistDraftData="persistCamapignData" :selected_groups="selected_groups"
                :type_of_invite="typeOfInvite" :selected_organizations="selected_organizations"
                :isSavedDraftAlready="isDraftAlready" />
            <TargetDepositors :selected_groups="selected_groups" @back-step="step = $event" :data="campaign_data"
                v-if="(typeOfInvite ==='2' )&& step == 4 && !editSteps" :depositors="depositors" :has_navigation="true"
                @selectedGroups="selectedGroups" :collected_details_data="info1"
                @selectedGroupDepositors="selectedGroupDepositorsCount($event)" />
            <BlanketedDepositors :selected_items="selected_organizations" @back-step="step = $event"
                :data="campaign_data" v-if="(typeOfInvite === '1') && step == 4 && !editSteps" :depositors="depositors"
                :has_navigation="true" @selectedOrganizations="selectedOrganizations($event)"
                :typeOfInvite="typeOfInvite" :collected_details_data="info1" />
            <AddProductRates @back-step="step = $event" :data="campaign_data" :selected_products="selected_products"
                :collected_details_data="info1" :selected_groups="selected_groups" @collected_rates="collectRates"
                v-if="(typeOfInvite > 0) && step == 5 && !editSteps" :type_of_invite="typeOfInvite"
                :selected_organizations="selected_organizations" @persistDraftData="persistCamapignData"
                :isSavedDraftAlready="isDraftAlready" :depos_count="depositorsCount" />
            <ReviewSummary @back-step="step = $event" :selected_products="selected_products"
                :collected_details_data="info1" :selected_groups="selected_groups"
                :selected_organizations="selected_organizations" v-if="(typeOfInvite > 0) && step === 6"
                :type_of_invite="typeOfInvite" :isSavedDraftAlready="isDraftAlready" :depos_count="depositorsCount" />
        </div>

    </div>
</template>

<script>
    import EditCampaignProduct from "./EditCampaignProduct";
    import AddProduct from "./AddProduct";
    import TargetDepositors from "./TargetDepositors";
    import BlanketedDepositors from "./BlanketedDepositors";
    import AddProductRates from "./AddProductRates";
    import EditProductRates from "./EditProductRates";
    import EditTargetDepositors from "./EditTargetDepositors";
    import EditBlanketedDepositors from "./EditBlanketedDepositors";
    import ReviewSummary from "./ReviewSummary";
    import CampaignDetailAndSummary from "./CampaignDetailAndSummary";
    import Modal from "../../shared/Modal";
    import Choose from "./Choose";
    import ChooseTypeOfInvite from "./ChooseTypeOfInvite";

    import NewCampaignDetails from "./NewCampaignDetails";
    import EditCampaignDetails from "./EditCampaignDetails";
    import CloneExistingCampaign from "./CloneExistingCampaign";
    import { confirmLeavePage } from "../../../utils/GlobalUtils";
    import ChooseEditStep from './ChooseEditStep.vue'
    import EditProductRatesDirect from "./EditProductRatesDirect.vue";
    import OKButtonActionMessageBox from '../../shared/messageboxes/OKButtonActionMessageBox.vue';


    import { mapGetters } from 'vuex';
    import * as types from '../../../store/modules/campaigns/mutation-types.js';

    export default {

        created() {
            confirmLeavePage(this, document, "Remember to save your changes as draft", "Stay", "Leave");
            const urlParams = new URLSearchParams(window.location.search);
            let campaign_id = urlParams.get('campaign_id');

            if (campaign_id) {
                this.edit_modal_show = true
                this.step = 2;
                // this.show = false
                this.choice = 'edit';
                this.setCampaignId = campaign_id;
                // this.getCampaign(campaign_id);
                this.cloned_existing = false;
                this.editSteps = true

            }
        },
        components: {
            OKButtonActionMessageBox,
            ChooseTypeOfInvite,
            CampaignDetailAndSummary,
            AddProduct,
            TargetDepositors,
            BlanketedDepositors,
            AddProductRates,
            ReviewSummary,
            Modal,
            Choose,
            NewCampaignDetails,
            EditCampaignDetails,
            CloneExistingCampaign,
            ChooseEditStep,
            EditCampaignProduct,
            EditProductRates,
            EditTargetDepositors,
            EditProductRatesDirect,
            EditBlanketedDepositors

        },
        mounted() {
            this.getCampaign(this.setCampaignId)
            // console.log(this.campaign_data.campaign_products, "Slected Products")
        },
        props: ['products', 'depositors', 'timeZone', 'unformattedusertimezone', 'formattedtimezone', 'userloggedin'],
        data() {
            this.$emit('isModal', true);

            return {
                depositorsCount: 0,
                campaignEditSteps: [],
                doNotUseBackendProducts: false,
                ready_fo_edit: true,
                draftCamapignId: null,
                typeOfInvite: 0,
                isDraftAlready: 0,
                step: 1,
                show: true,
                choice: null,
                info1: null,
                campaign_data: {},
                selected_products: null,
                selected_organizations: null,
                selected_groups: null,
                setCampaignId: null,
                cloned_existing: false,
                editSteps: false,
                multstepEdit: false,
                edit_modal_show: false,
                selected_steps: null,
                editInfo1: null,
                successModalTitle: "",
                successbtnOneText: "",
                successbtnTwoText: "",
                showSuccessModal: false,
                csuccessModalTitle: "",
                csuccessbtnOneText: "",
                csuccessbtnTwoText: "",
                cshowSuccessModal: false,
                csuccessModalSize: "md",
                camp_id: "",
                alreadyCurrentProductsLoaded: false


            }
        },
        methods: {
            selectedGroupDepositorsCount(count) {

                this.depositorsCount = count;
            },
            backToCampaign() {
                window.location.href = "/campaigns/summary/" + this.camp_id;
            },
            upDoNotUseBackendProductts() {
                this.doNotUseBackendProducts = true;
            },
            selectedOrganizations(orgs) {
                console.log(orgs, "ret");
                this.selected_organizations = orgs;
                this.depositorsCount = orgs.length;

            },
            updateTypeOfCamapignInvite(val) {
                this.typeOfInvite = val;
            },
            persistCamapignData(camaping) {

                console.log(camaping, "persistcamapign");
                this.isDraftAlready = camaping?.id;
                this.draftCamapignId = camaping?.id;

                //this.selected_groups = camaping?.groups;
                // this.selected_products = camaping?.products;

            },
            goToEditStep(val) {

                console.log("to remove", val);
                const urlParams = new URLSearchParams(window.location.search);
                let campaign_id = urlParams.get('campaign_id');
                if (campaign_id) {
                    this.getCampaign(campaign_id);
                }
                if (this.campaignEditSteps.length > 0) {

                    let revisedSteps = this.campaignEditSteps.filter((v, k) => {
                        return parseInt(val) !== parseInt(v);
                    });
                    revisedSteps.sort((a, b) => a - b);
                    if (revisedSteps.length > 0) {


                        console.log("revised steps", revisedSteps);
                        this.step = revisedSteps[0];
                        this.campaignEditSteps = revisedSteps;

                    } else {

                        this.showSuccessModal = true;
                        this.successbtnOneText = "Close";
                        this.successModalTitle = "Campaign details updated successfully";
                        this.submitted = false
                        this.camp_id = campaign_id;
                        setTimeout(() => {
                            this.showSuccessModal = false;
                            window.location.href = "/campaigns/summary/" + this.camp_id;
                        }, 1000);

                    }
                }
            },
            goToStep(val) {

                if (val.length > 0) {

                    this.edit_modal_show = false;
                    this.editSteps = false;
                    this.multstepEdit = true;
                    this.choice = 'edit';
                    if (val.includes("3") && !val.includes("5")) {
                        val.push("5");
                    }
                    val.sort((a, b) => a - b);
                    this.campaignEditSteps = val;
                    this.step = parseInt(this.campaignEditSteps[0]);
                }


                // console.log(this.val)
            },

            collectRates(v) {

                this.selected_products = v;
                // console.log("collectRates > ",v);
            },
            selectedExistingCampaign1(val) {
                this.setCampaignId = val;
                this.getCampaign(val);
                this.cloned_existing = true;
                this.$emit('isModal', true);
            },
            removeCommas(numberWithCommas) {
                const numberWithoutCommas = numberWithCommas.toString().replace(/,/g, '');
                const result = parseFloat(numberWithoutCommas);

                return result;
            },
            selectedItems(val) {
                this.selected_products = val;
            },
            selectedGroups(val) {
                this.selected_groups = val;
            },
            activeStep(step) {
                if (step === 1) {
                    if (1 === this.step || 2 === this.step) {
                        return '#44E0AA';
                    } else if (this.step > 2) {
                        return '#5063F4';
                    }
                    return '#9CA1AA';
                }

                if (step === this.step) {
                    return '#44E0AA';
                } else if (this.step > step) {
                    return '#5063F4';
                }
                return '#9CA1AA';
            },
            getCampaign(campaign_id) {
                let this_ = this;
                axios.get("/campaigns/fi/my-campaigns?campaign_id=" + campaign_id)
                    .then(response => {
                        console.log("response?.data?.data?.campaign_products;", response?.data?.data.campaign_products);
                        this_.campaign_data = response?.data?.data;
                        if (!this_.campaign_data) {
                            this.$swal({
                                title: 'Campaign.',
                                text: "Campaign not found",
                                confirmButtonText: 'Close'
                            }).then(() => {
                                window.location.href = '/campaigns';
                            });
                        } else {
                            if (this_.campaign_data != null) {

                                let prodids = [];
                                let thiscampaignproducts = response?.data?.data.campaign_products.map((p, k) => {
                                    prodids.push(p.fi_campaign_product_id);
                                    return {
                                        product_id: p.fi_campaign_product_id,
                                        product_name: p.product.custom_product_name,
                                        maximum: p.maximum,
                                        minimum: p.minimum,
                                        rate: p.rate,
                                        term_length: p.product.term_length,
                                        term_length_type: p.product.term_length_type
                                    }
                                });
                                if (!this.alreadyCurrentProductsLoaded) {
                                    this.$store.commit('campaign/' + types.SET_CAMPAIGN_SELECTED_PRODUCTS, thiscampaignproducts);
                                    this.$store.commit('campaign/' + types.SET_CAMPAIGN_SELECTED_PRODUCTS_IDS, prodids);
                                    this.alreadyCurrentProductsLoaded = true;
                                }
                                console.log(thiscampaignproducts, "thiscampaignproductsthiscampaignproducts");

                                if (this_.campaign_data?.campaign_depositors_invite_type === "Blanket") {
                                    this.typeOfInvite = '2';
                                    this.depositorsCount = this_.campaign_data?.campaign_depositor_count?.invitees;
                                } else if (this_.campaign_data?.campaign_depositors_invite_type === "Targeted") {

                                    this.typeOfInvite = '1';
                                    this.depositorsCount = this_.campaign_data?.campaign_depositor_count?.invitees;
                                } else {
                                    this.typeOfInvite = 0;
                                }
                                this.ready_fo_edit = false;
                                // this.typeOfInvite = (this_.campaign_data?.campaign_depositors_invite_type == "Blanket" && this.choice === 'existing') ? '2' : '0';
                            }
                        }
                    }).catch(error => {
                        // console.log("error > "+ error);
                        this_.campaign_data = null;
                    });
            }
        },
        watch: {
            show(newValue, oldValue) {
                // console.log(newValue);
                if (!newValue) {
                    this.$emit('modal-closed', newValue);
                }
            },
            choice(newValue, oldValue) {
                // console.log(newValue);
                // if(newValue === "new"){            
                this.step = 2;

                // }
            },
            selected_products(newValue, oldValue) {
                console.log(newValue, "  Watch product type")
            },
            info1(newValue, oldValue) {
                if (newValue) {
                    this.step = 3;
                }
            },
            step(newValue, oldValue) {
                this.$emit('isModal', !(this.choice === 'existing' && newValue === 2 && !this.cloned_existing || newValue > 2));
            },
            // edit_modal_show(newValue, oldValue) {
            //     this.edit_modal_show = newValue
            // }

        }
    }
</script>

<style scoped>
    .activeStepText {
        color: #44E0AA;
    }

    .inactiveStepText {
        color: #9CA1AA;
    }

    .completedStepText {
        color: #5063F4;
    }

    .active-step-- {
        background-color: #5063F4;
    }

    .inactive-step-- {
        background-color: #9CA1AA;
    }

    .row {
        --bs-gutter-x: 0px !important;
        --bs-gutter-y: 0;
        display: flex;
        flex-wrap: wrap;
        margin-top: calc(-1 * var(--bs-gutter-y));
        margin-right: calc(-0.5 * var(--bs-gutter-x));
        margin-left: calc(-0.5 * var(--bs-gutter-x));
    }
</style>
