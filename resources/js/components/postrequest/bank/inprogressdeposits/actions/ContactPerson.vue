<template>
    <div v-if="deposit_request"
        style="justify-content: flex-start; align-items: flex-start; gap: 11px; display: inline-flex; width: 100%">

        <div style="justify-content: flex-start; align-items: flex-start; gap: 30px; display: inline-flex; width: 100%">
            <div class="about_avator">
                <avatar v-if="!deposit_request?.user?.profile_pic" :size="100" :color="'white'"
                    :backgroundColor="'#4975E3'" :initials="deposit_request?.user?.name[0]"></avatar>
                <avatar v-else :size="100" :color="'white'" :backgroundColor="'#4975E3'"
                    :src="'/image/' + deposit_request?.user?.profile_pic"></avatar>
                <p
                    style="color:  #5063F4;text-align: center;font-family: Montserrat;font-size: 24px;font-style: normal;font-weight: 700;line-height: 26px; text-transform: capitalize;">
                    {{ deposit_request?.user?.name }}</p>
            </div>
            <div class="row w-100 " style="gap: 20px 0px !important;">
                <div class="col-md-4">
                    <div style="justify-content: flex-start; align-items: center; gap: 10px; display: flex;flex: 1;">
                        <div style="width: 30px; height: 30px; position: relative">
                            <svg width="25" height="25" viewBox="0 0 25 25" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M1.441 8.438a9.39 9.39 0 0 1 6.997-6.997 16.7 16.7 0 0 1 7.624 0 9.39 9.39 0 0 1 6.997 6.997 16.7 16.7 0 0 1 0 7.624 9.39 9.39 0 0 1-6.997 6.997 16.7 16.7 0 0 1-7.624 0 9.39 9.39 0 0 1-6.997-6.997 16.7 16.7 0 0 1 0-7.624Z"
                                    fill="#FDFDFD" stroke="#5063F4" stroke-width="1.35" stroke-linecap="round" />
                                <g clip-path="url(#a)" fill="#5063F4">
                                    <path
                                        d="M15.125 17.73h-1.083a1.084 1.084 0 0 1 0-2.166h1.083V14.48h-1.083a2.166 2.166 0 1 0 0 4.334h1.083zm4.333-1.083a2.166 2.166 0 0 1-2.166 2.167h-1.084V17.73h1.084a1.084 1.084 0 0 0 0-2.166h-1.084V14.48h1.084a2.166 2.166 0 0 1 2.166 2.167" />
                                    <path
                                        d="M14.583 16.106h2.167v1.083h-2.167zm-3.791.541q0-.272.048-.541H8.625v-.759c0-1.083 2.167-1.679 3.25-1.679q.368.005.731.068a3.2 3.2 0 0 1 1.436-.339h2.708V8.522a1.087 1.087 0 0 0-1.083-1.083h-2.264a1.619 1.619 0 0 0-3.056 0H8.084A1.087 1.087 0 0 0 7 8.522v7.584a1.087 1.087 0 0 0 1.083 1.083h2.757a3 3 0 0 1-.048-.542m1.083-9.208a.541.541 0 1 1 0 1.083.541.541 0 0 1 0-1.083m0 2.167a1.625 1.625 0 1 1 0 3.25 1.625 1.625 0 0 1 0-3.25" />
                                </g>
                                <defs>
                                    <clipPath id="a">
                                        <path fill="#fff" d="M7 6.355h13v13H7z" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </div>
                        <div
                            style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                            Job Title: </div>
                        <div style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">
                            {{ deposit_request?.user?.demographic_data?.job_title }}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div style="justify-content: flex-start; align-items: center; gap: 10px; display: flex;flex: 1;">
                        <div style="width: 25px; height: 25px; position: relative">
                            <svg width="25" height="25" viewBox="0 0 25 25" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M1.441 8.438a9.39 9.39 0 0 1 6.997-6.997 16.7 16.7 0 0 1 7.624 0 9.39 9.39 0 0 1 6.997 6.997 16.7 16.7 0 0 1 0 7.624 9.39 9.39 0 0 1-6.997 6.997 16.7 16.7 0 0 1-7.624 0 9.39 9.39 0 0 1-6.997-6.997 16.7 16.7 0 0 1 0-7.624Z"
                                    fill="#FDFDFD" stroke="#5063F4" stroke-width="1.35" stroke-linecap="round" />
                                <g clip-path="url(#a)" fill="#5063F4">
                                    <path d="M17.585 7.168a.7.7 0 0 0-.148 0H7.327a.7.7 0 0 0-.192.029l5.218 5.196z" />
                                    <path
                                        d="m18.12 7.668-5.258 5.236a.72.72 0 0 1-1.018 0L6.633 7.708a.7.7 0 0 0-.026.18v7.223a.72.72 0 0 0 .723.722h10.11a.72.72 0 0 0 .723-.723V7.889a.7.7 0 0 0-.043-.22M7.824 15.111h-.502v-.517l2.626-2.603.509.509zm9.61 0h-.506L14.295 12.5l.51-.51 2.625 2.604z" />
                                </g>
                                <defs>
                                    <clipPath id="a">
                                        <path fill="#fff" d="M5.91 5h13v13h-13z" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </div>
                        <div
                            style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                            Email:</div>
                        <div style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">
                            <a v-if="deposit_request?.user?.email" style="color:  #5063F4;"
                                :href="`mailto:${deposit_request?.user?.email}`">
                                {{ deposit_request?.user?.email }}</a>
                            <span v-else>-</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div style="justify-content: flex-start; align-items: center; gap: 10px; display: flex;flex: 1;">
                        <div style="width: 25px; height: 25px; position: relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="26" viewBox="0 0 25 26"
                                fill="none">
                                <g clip-path="url(#clip0_11409_154080)">
                                    <path
                                        d="M1.4411 8.93804C2.25547 5.46626 4.96626 2.75546 8.43804 1.9411C10.9453 1.35297 13.5547 1.35297 16.062 1.9411C19.5337 2.75547 22.2445 5.46626 23.0589 8.93805C23.647 11.4453 23.647 14.0547 23.0589 16.562C22.2445 20.0337 19.5337 22.7445 16.062 23.5589C13.5547 24.147 10.9453 24.147 8.43804 23.5589C4.96626 22.7445 2.25547 20.0337 1.4411 16.562C0.852968 14.0547 0.852968 11.4453 1.4411 8.93804Z"
                                        fill="#FDFDFD" />
                                    <path
                                        d="M1.4411 16.562L0.783934 16.7161L1.4411 16.562ZM1.4411 8.93804L0.783934 8.78389L1.4411 8.93804ZM23.0589 8.93805L22.4017 9.09219L23.0589 8.93805ZM23.0589 16.562L22.4017 16.4078L23.0589 16.562ZM16.062 23.5589L15.9078 22.9017H15.9078L16.062 23.5589ZM8.43804 23.5589L8.59219 22.9017L8.43804 23.5589ZM8.43804 1.9411L8.59219 2.59826L8.43804 1.9411ZM16.062 1.9411L15.9078 2.59826L16.062 1.9411ZM2.09826 16.4078C1.53391 14.0019 1.53391 11.4981 2.09826 9.09219L0.783934 8.78389C0.172022 11.3926 0.172022 14.1074 0.783934 16.7161L2.09826 16.4078ZM22.4017 9.09219C22.9661 11.4981 22.9661 14.0019 22.4017 16.4078L23.7161 16.7161C24.328 14.1074 24.328 11.3926 23.7161 8.7839L22.4017 9.09219ZM15.9078 22.9017C13.5019 23.4661 10.9981 23.4661 8.59219 22.9017L8.2839 24.2161C10.8926 24.828 13.6074 24.828 16.2161 24.2161L15.9078 22.9017ZM8.59219 2.59826C10.9981 2.03391 13.5019 2.03391 15.9078 2.59826L16.2161 1.28393C13.6074 0.672022 10.8926 0.672023 8.28389 1.28393L8.59219 2.59826ZM8.59219 22.9017C5.37 22.1459 2.85408 19.63 2.09826 16.4078L0.783934 16.7161C1.65685 20.4375 4.56253 23.3432 8.2839 24.2161L8.59219 22.9017ZM16.2161 24.2161C19.9375 23.3432 22.8432 20.4375 23.7161 16.7161L22.4017 16.4078C21.6459 19.63 19.13 22.1459 15.9078 22.9017L16.2161 24.2161ZM15.9078 2.59826C19.13 3.35408 21.6459 5.87 22.4017 9.09219L23.7161 8.7839C22.8432 5.06253 19.9375 2.15685 16.2161 1.28393L15.9078 2.59826ZM8.28389 1.28393C4.56252 2.15685 1.65685 5.06252 0.783934 8.78389L2.09826 9.09219C2.85408 5.87 5.37 3.35408 8.59219 2.59826L8.28389 1.28393Z"
                                        fill="#5063F4" />
                                    <path
                                        d="M9.58583 12.3446C10.3658 13.8775 11.6225 15.1342 13.1554 15.9142L14.3471 14.7225C14.4988 14.5708 14.71 14.5275 14.8996 14.5871C15.5063 14.7875 16.1562 14.8958 16.8333 14.8958C16.977 14.8958 17.1148 14.9529 17.2164 15.0545C17.3179 15.1561 17.375 15.2938 17.375 15.4375V17.3333C17.375 17.477 17.3179 17.6148 17.2164 17.7164C17.1148 17.8179 16.977 17.875 16.8333 17.875C14.3911 17.875 12.049 16.9048 10.3221 15.1779C8.59516 13.451 7.625 11.1089 7.625 8.66667C7.625 8.52301 7.68207 8.38523 7.78365 8.28365C7.88523 8.18207 8.02301 8.125 8.16667 8.125H10.0625C10.2062 8.125 10.3439 8.18207 10.4455 8.28365C10.5471 8.38523 10.6042 8.52301 10.6042 8.66667C10.6042 9.34375 10.7125 9.99375 10.9129 10.6004C10.9725 10.79 10.9292 11.0013 10.7775 11.1529L9.58583 12.3446Z"
                                        fill="#5063F4" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_11409_154080">
                                        <rect width="25" height="25" fill="white" transform="translate(0 0.5)" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </div>
                        <div
                            style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                            Telephone Number:</div>
                        <div style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">
                            <a v-if="deposit_request?.user?.demographic_data?.phone" style="color:  #5063F4;"
                                :href="`tel:${deposit_request?.user?.demographic_data?.phone}`">
                                {{ deposit_request?.user?.demographic_data?.phone }}</a>
                            <span v-else>-</span>
                        </div>

                    </div>
                </div>
                <div class="col-md-4">
                    <div style="justify-content: flex-start; align-items: center; gap: 10px; display: flex;flex: 1;">
                        <div style="width: 25px; height: 25px; position: relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30"
                                fill="none">
                                <path
                                    d="M4.4411 11.438C5.25547 7.96626 7.96626 5.25546 11.438 4.4411C13.9453 3.85297 16.5547 3.85297 19.062 4.4411C22.5337 5.25547 25.2445 7.96626 26.0589 11.438C26.647 13.9453 26.647 16.5547 26.0589 19.062C25.2445 22.5337 22.5337 25.2445 19.062 26.0589C16.5547 26.647 13.9453 26.647 11.438 26.0589C7.96626 25.2445 5.25547 22.5337 4.4411 19.062C3.85297 16.5547 3.85297 13.9453 4.4411 11.438Z"
                                    fill="#FDFDFD" />
                                <path
                                    d="M4.4411 19.062L3.78393 19.2161L4.4411 19.062ZM4.4411 11.438L5.09826 11.5922L4.4411 11.438ZM26.0589 11.438L25.4017 11.5922L26.0589 11.438ZM26.0589 19.062L25.4017 18.9078L26.0589 19.062ZM19.062 26.0589L18.9078 25.4017L19.062 26.0589ZM11.438 26.0589L11.5922 25.4017L11.438 26.0589ZM11.438 4.4411L11.2839 3.78393L11.438 4.4411ZM19.062 4.4411L18.9078 5.09826L19.062 4.4411ZM5.09826 18.9078C4.53391 16.5019 4.53391 13.9981 5.09826 11.5922L3.78393 11.2839C3.17202 13.8926 3.17202 16.6074 3.78393 19.2161L5.09826 18.9078ZM25.4017 11.5922C25.9661 13.9981 25.9661 16.5019 25.4017 18.9078L26.7161 19.2161C27.328 16.6074 27.328 13.8926 26.7161 11.2839L25.4017 11.5922ZM18.9078 25.4017C16.5019 25.9661 13.9981 25.9661 11.5922 25.4017L11.2839 26.7161C13.8926 27.328 16.6074 27.328 19.2161 26.7161L18.9078 25.4017ZM11.5922 5.09826C13.9981 4.53391 16.5019 4.53391 18.9078 5.09826L19.2161 3.78393C16.6074 3.17202 13.8926 3.17202 11.2839 3.78393L11.5922 5.09826ZM11.5922 25.4017C8.37 24.6459 5.85408 22.13 5.09826 18.9078L3.78393 19.2161C4.65685 22.9375 7.56253 25.8432 11.2839 26.7161L11.5922 25.4017ZM19.2161 26.7161C22.9375 25.8432 25.8432 22.9375 26.7161 19.2161L25.4017 18.9078C24.6459 22.13 22.13 24.6459 18.9078 25.4017L19.2161 26.7161ZM18.9078 5.09826C22.13 5.85408 24.6459 8.37 25.4017 11.5922L26.7161 11.2839C25.8432 7.56253 22.9375 4.65685 19.2161 3.78393L18.9078 5.09826ZM11.2839 3.78393C7.56252 4.65685 4.65685 7.56252 3.78393 11.2839L5.09826 11.5922C5.85408 8.37 8.37 5.85408 11.5922 5.09826L11.2839 3.78393Z"
                                    fill="#5063F4" />
                                <path
                                    d="M15 11.15C15.4736 11.15 15.9278 11.3344 16.2627 11.6626C16.5976 11.9908 16.7857 12.4359 16.7857 12.9C16.7857 13.1298 16.7395 13.3574 16.6498 13.5697C16.56 13.782 16.4285 13.9749 16.2627 14.1374C16.0969 14.2999 15.9 14.4288 15.6834 14.5168C15.4667 14.6047 15.2345 14.65 15 14.65C14.5264 14.65 14.0722 14.4656 13.7373 14.1374C13.4024 13.8092 13.2143 13.3641 13.2143 12.9C13.2143 12.4359 13.4024 11.9908 13.7373 11.6626C14.0722 11.3344 14.5264 11.15 15 11.15ZM15 8C16.3261 8 17.5979 8.51625 18.5355 9.43518C19.4732 10.3541 20 11.6004 20 12.9C20 16.575 15 22 15 22C15 22 10 16.575 10 12.9C10 11.6004 10.5268 10.3541 11.4645 9.43518C12.4021 8.51625 13.6739 8 15 8ZM15 9.4C14.0528 9.4 13.1444 9.76875 12.4746 10.4251C11.8048 11.0815 11.4286 11.9717 11.4286 12.9C11.4286 13.6 11.4286 15 15 19.697C18.5714 15 18.5714 13.6 18.5714 12.9C18.5714 11.9717 18.1952 11.0815 17.5254 10.4251C16.8556 9.76875 15.9472 9.4 15 9.4Z"
                                    fill="#5063F4" />
                            </svg>
                        </div>
                        <div
                            style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                            Address:</div>
                        <div style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">
                            {{ deposit_request?.user?.demographic_data?.address1 }}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div style="justify-content: flex-start; align-items: center; gap: 10px; display: flex;flex: 1;">
                        <div style="width: 25px; height: 25px; position: relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="26" viewBox="0 0 25 26"
                                fill="none">
                                <g clip-path="url(#clip0_11409_154100)">
                                    <path
                                        d="M1.4411 8.93804C2.25547 5.46626 4.96626 2.75546 8.43804 1.9411C10.9453 1.35297 13.5547 1.35297 16.062 1.9411C19.5337 2.75547 22.2445 5.46626 23.0589 8.93805C23.647 11.4453 23.647 14.0547 23.0589 16.562C22.2445 20.0337 19.5337 22.7445 16.062 23.5589C13.5547 24.147 10.9453 24.147 8.43804 23.5589C4.96626 22.7445 2.25547 20.0337 1.4411 16.562C0.852968 14.0547 0.852968 11.4453 1.4411 8.93804Z"
                                        fill="#FDFDFD" />
                                    <path
                                        d="M1.4411 16.562L0.783934 16.7161L1.4411 16.562ZM1.4411 8.93804L0.783934 8.78389L1.4411 8.93804ZM23.0589 8.93805L22.4017 9.09219L23.0589 8.93805ZM23.0589 16.562L22.4017 16.4078L23.0589 16.562ZM16.062 23.5589L15.9078 22.9017H15.9078L16.062 23.5589ZM8.43804 23.5589L8.59219 22.9017L8.43804 23.5589ZM8.43804 1.9411L8.59219 2.59826L8.43804 1.9411ZM16.062 1.9411L15.9078 2.59826L16.062 1.9411ZM2.09826 16.4078C1.53391 14.0019 1.53391 11.4981 2.09826 9.09219L0.783934 8.78389C0.172022 11.3926 0.172022 14.1074 0.783934 16.7161L2.09826 16.4078ZM22.4017 9.09219C22.9661 11.4981 22.9661 14.0019 22.4017 16.4078L23.7161 16.7161C24.328 14.1074 24.328 11.3926 23.7161 8.7839L22.4017 9.09219ZM15.9078 22.9017C13.5019 23.4661 10.9981 23.4661 8.59219 22.9017L8.2839 24.2161C10.8926 24.828 13.6074 24.828 16.2161 24.2161L15.9078 22.9017ZM8.59219 2.59826C10.9981 2.03391 13.5019 2.03391 15.9078 2.59826L16.2161 1.28393C13.6074 0.672022 10.8926 0.672023 8.28389 1.28393L8.59219 2.59826ZM8.59219 22.9017C5.37 22.1459 2.85408 19.63 2.09826 16.4078L0.783934 16.7161C1.65685 20.4375 4.56253 23.3432 8.2839 24.2161L8.59219 22.9017ZM16.2161 24.2161C19.9375 23.3432 22.8432 20.4375 23.7161 16.7161L22.4017 16.4078C21.6459 19.63 19.13 22.1459 15.9078 22.9017L16.2161 24.2161ZM15.9078 2.59826C19.13 3.35408 21.6459 5.87 22.4017 9.09219L23.7161 8.7839C22.8432 5.06253 19.9375 2.15685 16.2161 1.28393L15.9078 2.59826ZM8.28389 1.28393C4.56252 2.15685 1.65685 5.06252 0.783934 8.78389L2.09826 9.09219C2.85408 5.87 5.37 3.35408 8.59219 2.59826L8.28389 1.28393Z"
                                        fill="#5063F4" />
                                    <path
                                        d="M12.2293 7.66536C11.7265 7.66536 11.2443 7.8651 10.8888 8.22064C10.5332 8.57618 10.3335 9.05839 10.3335 9.5612C10.3335 10.064 10.5332 10.5462 10.8888 10.9018C11.2443 11.2573 11.7265 11.457 12.2293 11.457C12.7321 11.457 13.2143 11.2573 13.5699 10.9018C13.9254 10.5462 14.1252 10.064 14.1252 9.5612C14.1252 9.05839 13.9254 8.57618 13.5699 8.22064C13.2143 7.8651 12.7321 7.66536 12.2293 7.66536ZM9.25016 9.5612C9.25016 8.77107 9.56404 8.01331 10.1227 7.45461C10.6814 6.89591 11.4392 6.58203 12.2293 6.58203C13.0195 6.58203 13.7772 6.89591 14.3359 7.45461C14.8946 8.01331 15.2085 8.77107 15.2085 9.5612C15.2085 10.3513 14.8946 11.1091 14.3359 11.6678C13.7772 12.2265 13.0195 12.5404 12.2293 12.5404C11.4392 12.5404 10.6814 12.2265 10.1227 11.6678C9.56404 11.1091 9.25016 10.3513 9.25016 9.5612ZM13.7904 12.8112H17.7099L18.8371 15.0656L15.7502 18.496L12.6627 15.0656L13.7904 12.8112ZM14.4599 13.8945L13.9621 14.8901L15.7502 16.8764L17.5377 14.8901L17.0404 13.8945H14.4599ZM10.3335 14.1654C9.75886 14.1654 9.20776 14.3936 8.80143 14.8C8.3951 15.2063 8.16683 15.7574 8.16683 16.332H11.9856V17.4154H7.0835V16.332C7.0835 15.4701 7.42591 14.6434 8.0354 14.0339C8.64489 13.4244 9.47154 13.082 10.3335 13.082H11.9585V14.1654H10.3335Z"
                                        fill="#5063F4" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_11409_154100">
                                        <rect width="25" height="25" fill="white" transform="translate(0 0.5)" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </div>
                        <div
                            style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                            User Role:</div>
                        <div style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">
                            {{ deposit_request?.user?.role_name }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>
<script>
import Avatar from 'vue-avatar';
export default {
    components: {
        Avatar
    },
    mounted() {
        if (this.organization_data) {
            this.organization = JSON.parse(this.organization_data)
        }
        if (this.depositRequest) {
            this.deposit_request = JSON.parse(this.depositRequest);
        }
    },
    props: ['datum', 'organization_data', 'depositRequest'],
    methods: {
        closeErrorModal() {
            this.showErrorModal = false;
        },
        addCommas(newvalue) {
            if (newvalue != undefined) {
                return newvalue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            } else {
                return "";
            }

        },
        showWarning() {
            this.errorModalTitle = "Financial Statement.";
            this.errorModalMessage = "Financial Statement are not available.";
            this.showErrorModal = true;
        },
        formatNumberAbbreviated(number) {
            const SI_SYMBOL = ["", "K", "M", "G", "T", "P", "E"];

            const tier = (Math.log10(number) / 3) | 0;

            if (tier === 0) return number;

            const suffix = SI_SYMBOL[tier];
            const scale = Math.pow(10, tier * 3);

            const scaledNumber = number / scale;

            return scaledNumber.toFixed(0) + suffix;
        },
    },
    data() {
        return {
            errorModalTitle: "",
            errorModalMessage: "",
            showErrorModal: false,
            errorModalSize: "md",
            organi: null,
            organization: null,
            deposit_request: null
        }

    },
    computed: {
        creditrating() {
            let rate = this.organization?.deposit_credit_rating?.credit_rating?.description
            if (rate.toLowerCase() == "strong") {
                return 4
            } else if (rate.toLowerCase() == "very strong") {
                return 5
            } else if (rate.toLowerCase() == "adequate") {
                return 3
            } else {
                return 1
            }
        }
    },

}
</script>
