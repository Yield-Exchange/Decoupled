<template>
    <div style="justify-content: flex-start; align-items: flex-start; gap: 11px; display: inline-flex; width: 100%"
        v-if="organization_data">
        <div
            style="border-radius: 10px; border: 0.25px #D9D9D9 solid; flex-direction: column; justify-content: flex-start; align-items: flex-start; display: inline-flex">
            <div class="d-flex flex-column justify-content-center align-items-center"
                style="width: 185px; padding: 21.60px; background: white; border-top-left-radius: 10.80px; border-top-right-radius: 10.80px;">
                <div
                    style="flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 2px; display: flex;min-height: 50px">
                    <avatar v-if="organization_data?.logo == null" :size="80" :color="'white'" backgroundColor="#4975E3"
                        :initials="organization_data?.name[0]"></avatar>
                    <avatar v-else :size="80" :color="'white'" :backgroundColor="'#4975E3'"
                        :src="'/image/' + organization_data?.logo"></avatar>
                </div>

                <div
                    style="color: #5063F4;font-feature-settings: 'clig' off, 'liga' off;font-family: Montserrat;font-size: 16px;font-style: normal;text-align: center; font-weight: 700;line-height: 26px;text-transform: capitalize;">
                    {{ organization_data?.name }}
                </div>

                <div class="d-none">
                    <template v-for="i in 5">
                        <img v-if="i <= creditrating" style="width: 18.148px;height: 17.337px;"
                            src="/assets/dashboard/images/rating.svg" alt="">
                        <img v-else style="width: 18.148px;height: 17.337px;"
                            src="/assets/dashboard/images/no-color-rating.svg" alt="">
                    </template>
                </div>
            </div>
        </div>
        <div
            style="flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 30px; display: inline-flex; width: 75%">
            <div class="row w-100 " style="gap: 20px 0px !important;">
                <div class="col-md-4">
                    <div style="justify-content: flex-start; align-items: center; gap: 10px; display: flex;flex: 1;">
                        <div style="width: 30px; height: 30px; position: relative">
                            <img src="/assets/dashboard/icons/Setting - 6.svg" />
                        </div>
                        <div
                            style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                            type: </div>
                        <div style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">{{
            organization_data?.fi_type ? organization_data?.fi_type : '-' }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">

                    <div class="d-flex gap-2" style="">
                        <div style="width: 25px; height: 25px; position: relative">
                            <img src="/assets/dashboard/icons/Setting - 5.svg" />
                        </div>
                        <div
                            style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                            Total assets size: </div>
                        <div style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">CAD {{
            formatNumberAbbreviated(organization_data?.demographic_data?.value_of_assets) }}</div>
                    </div>
                </div>
                <div class="col-md-4 d-none">
                    <div
                        style="height: 25px; justify-content: flex-start; align-items: center; gap: 10px; display: flex;flex: 1;">
                        <div style="width: 25px; height: 25px; position: relative">
                            <img style="height: 25px" src="/assets/dashboard/icons/Settingdeer3.svg" />
                        </div>
                        <div style="justify-content: flex-start; align-items: center; gap: 10px; display: flex">
                            <div><span
                                    style="color: black; font-size: 14px;  font-weight: 700; text-transform: capitalize; line-height: 19.11px; word-wrap: break-word">No
                                    of Branches</span><span
                                    style="color: black; font-size: 14px; font-weight: 600; text-transform: capitalize; line-height: 19.11px; word-wrap: break-word">:
                                </span></div>
                            <div style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">{{
            organization_data?.demographic_data?.no_of_branches }}
                            </div>
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
                                    fill="#FDFDFD" stroke="#5063F4" stroke-width="1.35" stroke-linecap="round" />
                                <path
                                    d="M20.4947 13.3393L19.7242 14.4983C20.0958 15.2394 20.2777 16.0612 20.2536 16.89C20.2296 17.7188 20.0003 18.5286 19.5863 19.247H10.9032C10.3652 18.3136 10.1422 17.2317 10.2674 16.1616C10.3926 15.0915 10.8593 14.0903 11.5983 13.3063C12.3373 12.5223 13.3092 11.9973 14.37 11.8092C15.4309 11.621 16.5241 11.7797 17.4876 12.2617L18.6466 11.4911C17.4666 10.7345 16.0669 10.3954 14.6715 10.5282C13.276 10.6609 11.9654 11.2579 10.9493 12.2235C9.93314 13.1891 9.27021 14.4676 9.06651 15.8545C8.86281 17.2414 9.13012 18.6565 9.82567 19.8735C9.935 20.0629 10.092 20.2203 10.281 20.3302C10.4701 20.4401 10.6846 20.4987 10.9032 20.5H19.5801C19.8008 20.5009 20.0179 20.4434 20.2094 20.3334C20.4008 20.2234 20.5598 20.0647 20.6701 19.8735C21.2474 18.8736 21.5371 17.7336 21.5072 16.5794C21.4774 15.4252 21.1291 14.3017 20.501 13.333L20.4947 13.3393ZM14.3614 17.6244C14.4778 17.7409 14.616 17.8333 14.7681 17.8964C14.9202 17.9594 15.0833 17.9919 15.2479 17.9919C15.4126 17.9919 15.5756 17.9594 15.7277 17.8964C15.8798 17.8333 16.018 17.7409 16.1344 17.6244L19.6803 12.3056L14.3614 15.8515C14.2449 15.9678 14.1525 16.106 14.0895 16.2581C14.0264 16.4102 13.994 16.5733 13.994 16.7379C13.994 16.9026 14.0264 17.0656 14.0895 17.2178C14.1525 17.3699 14.2449 17.5081 14.3614 17.6244Z"
                                    fill="#5063F4" />
                            </svg>
                        </div>
                        <div style="justify-content: flex-start; align-items: center; gap: 10px; display: flex">
                            <div
                                style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                                Credit rating: </div>
                            <div style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">{{
            organization_data?.deposit_credit_rating ?
                organization_data?.deposit_credit_rating?.credit_rating?.description : '-'
        }}
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-4 d-none">
                    <div style="justify-content: flex-start; align-items: center; gap: 9px; display: flex;flex: 1;">
                        <div style="width: 25px; height: 25px; position: relative">
                            <img style="height: 25px" src="/assets/dashboard/icons/Setting_4.svg" />
                        </div>
                        <div
                            style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                            Deposit Insurance:</div>
                        <div style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">{{
                organization_data?.deposit_credit_rating ?
                    organization_data?.deposit_credit_rating?.insurance_rating?.description : '-' }}
                        </div>
                    </div>
                </div>

                <div class="col-md-4 d-none">
                    <div style="justify-content: flex-start; align-items: center; gap: 10px; display: flex;flex: 1;">
                        <div style="width: 25px; height: 25px; position: relative">
                            <img style="height: 25px" src="/assets/dashboard/icons/Settingyth4.svg" />
                        </div>
                        <div
                            style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                            Year of establishment:</div>
                        <div style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">{{
            organization_data?.demographic_data?.year_of_establishment }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div style="justify-content: flex-start; align-items: center; gap: 10px; display: flex;flex: 1;">
                        <div style="width: 25px; height: 25px; position: relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30"
                                fill="none">
                                <path
                                    d="M4.4411 11.438C5.25547 7.96626 7.96626 5.25546 11.438 4.4411C13.9453 3.85297 16.5547 3.85297 19.062 4.4411C22.5337 5.25547 25.2445 7.96626 26.0589 11.438C26.647 13.9453 26.647 16.5547 26.0589 19.062C25.2445 22.5337 22.5337 25.2445 19.062 26.0589C16.5547 26.647 13.9453 26.647 11.438 26.0589C7.96626 25.2445 5.25547 22.5337 4.4411 19.062C3.85297 16.5547 3.85297 13.9453 4.4411 11.438Z"
                                    fill="#FDFDFD" stroke="#5063F4" stroke-width="1.35" stroke-linecap="round" />
                                <path
                                    d="M15 11.15C15.4736 11.15 15.9278 11.3344 16.2627 11.6626C16.5976 11.9908 16.7857 12.4359 16.7857 12.9C16.7857 13.1298 16.7395 13.3574 16.6498 13.5697C16.56 13.782 16.4285 13.9749 16.2627 14.1374C16.0969 14.2999 15.9 14.4288 15.6834 14.5168C15.4667 14.6047 15.2345 14.65 15 14.65C14.5264 14.65 14.0722 14.4656 13.7373 14.1374C13.4024 13.8092 13.2143 13.3641 13.2143 12.9C13.2143 12.4359 13.4024 11.9908 13.7373 11.6626C14.0722 11.3344 14.5264 11.15 15 11.15ZM15 8C16.3261 8 17.5979 8.51625 18.5355 9.43518C19.4732 10.3541 20 11.6004 20 12.9C20 16.575 15 22 15 22C15 22 10 16.575 10 12.9C10 11.6004 10.5268 10.3541 11.4645 9.43518C12.4021 8.51625 13.6739 8 15 8ZM15 9.4C14.0528 9.4 13.1444 9.76875 12.4746 10.4251C11.8048 11.0815 11.4286 11.9717 11.4286 12.9C11.4286 13.6 11.4286 15 15 19.697C18.5714 15 18.5714 13.6 18.5714 12.9C18.5714 11.9717 18.1952 11.0815 17.5254 10.4251C16.8556 9.76875 15.9472 9.4 15 9.4Z"
                                    fill="#5063F4" />
                            </svg>
                        </div>
                        <div
                            style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                            <!-- Postal Code: -->
                            Country: Canada
                            <!-- {{
            organization_data?.demographic_data?.province + "," +
            organization_data?.demographic_data?.city }} -->

                        </div>
                        <div style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">
                        </div>
                    </div>
                </div>
                <div class="col-md-4 d-none">
                    <div style="justify-content: flex-start; align-items: center; gap: 10px; display: flex;flex: 1;">
                        <div style="width: 25px; height: 25px; position: relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="26" viewBox="0 0 25 26"
                                fill="none">
                                <g clip-path="url(#clip0_3938_32118)">
                                    <path
                                        d="M1.4411 9.43804C2.25547 5.96626 4.96626 3.25546 8.43804 2.4411C10.9453 1.85297 13.5547 1.85297 16.062 2.4411C19.5337 3.25547 22.2445 5.96626 23.0589 9.43805C23.647 11.9453 23.647 14.5547 23.0589 17.062C22.2445 20.5337 19.5337 23.2445 16.062 24.0589C13.5547 24.647 10.9453 24.647 8.43804 24.0589C4.96626 23.2445 2.25547 20.5337 1.4411 17.062C0.852968 14.5547 0.852968 11.9453 1.4411 9.43804Z"
                                        fill="#FDFDFD" stroke="#5063F4" stroke-width="1.35" stroke-linecap="round" />
                                    <path
                                        d="M17.75 9.75C17.8826 9.75 18.0098 9.69732 18.1036 9.60355C18.1973 9.50979 18.25 9.38261 18.25 9.25V7.75C18.25 7.61739 18.1973 7.49021 18.1036 7.39645C18.0098 7.30268 17.8826 7.25 17.75 7.25H16.25C16.1174 7.25 15.9902 7.30268 15.8964 7.39645C15.8027 7.49021 15.75 7.61739 15.75 7.75C15.75 7.8163 15.7237 7.87989 15.6768 7.92678C15.6299 7.97366 15.5663 8 15.5 8C15.4337 8 15.3701 7.97366 15.3232 7.92678C15.2763 7.87989 15.25 7.8163 15.25 7.75C15.25 7.61739 15.1973 7.49021 15.1036 7.39645C15.0098 7.30268 14.8826 7.25 14.75 7.25H13.25C13.1174 7.25 12.9902 7.30268 12.8964 7.39645C12.8027 7.49021 12.75 7.61739 12.75 7.75C12.75 7.8163 12.7237 7.87989 12.6768 7.92678C12.6299 7.97366 12.5663 8 12.5 8C12.4337 8 12.3701 7.97366 12.3232 7.92678C12.2763 7.87989 12.25 7.8163 12.25 7.75C12.25 7.61739 12.1973 7.49021 12.1036 7.39645C12.0098 7.30268 11.8826 7.25 11.75 7.25H10.25C10.1174 7.25 9.99021 7.30268 9.89645 7.39645C9.80268 7.49021 9.75 7.61739 9.75 7.75C9.75 7.8163 9.72366 7.87989 9.67678 7.92678C9.62989 7.97366 9.5663 8 9.5 8C9.4337 8 9.37011 7.97366 9.32322 7.92678C9.27634 7.87989 9.25 7.8163 9.25 7.75C9.25 7.61739 9.19732 7.49021 9.10355 7.39645C9.00979 7.30268 8.88261 7.25 8.75 7.25H7.25C7.11739 7.25 6.99021 7.30268 6.89645 7.39645C6.80268 7.49021 6.75 7.61739 6.75 7.75V9.25C6.75 9.38261 6.80268 9.50979 6.89645 9.60355C6.99021 9.69732 7.11739 9.75 7.25 9.75C7.3163 9.75 7.37989 9.77634 7.42678 9.82322C7.47366 9.87011 7.5 9.9337 7.5 10C7.5 10.0663 7.47366 10.1299 7.42678 10.1768C7.37989 10.2237 7.3163 10.25 7.25 10.25C7.11739 10.25 6.99021 10.3027 6.89645 10.3964C6.80268 10.4902 6.75 10.6174 6.75 10.75V12.25C6.75 12.3826 6.80268 12.5098 6.89645 12.6036C6.99021 12.6973 7.11739 12.75 7.25 12.75C7.3163 12.75 7.37989 12.7763 7.42678 12.8232C7.47366 12.8701 7.5 12.9337 7.5 13C7.5 13.0663 7.47366 13.1299 7.42678 13.1768C7.37989 13.2237 7.3163 13.25 7.25 13.25C7.11739 13.25 6.99021 13.3027 6.89645 13.3964C6.80268 13.4902 6.75 13.6174 6.75 13.75V15.25C6.75 15.3826 6.80268 15.5098 6.89645 15.6036C6.99021 15.6973 7.11739 15.75 7.25 15.75C7.3163 15.75 7.37989 15.7763 7.42678 15.8232C7.47366 15.8701 7.5 15.9337 7.5 16C7.5 16.0663 7.47366 16.1299 7.42678 16.1768C7.37989 16.2237 7.3163 16.25 7.25 16.25C7.11739 16.25 6.99021 16.3027 6.89645 16.3964C6.80268 16.4902 6.75 16.6174 6.75 16.75V18.25C6.75 18.3826 6.80268 18.5098 6.89645 18.6036C6.99021 18.6973 7.11739 18.75 7.25 18.75H8.75C8.88261 18.75 9.00979 18.6973 9.10355 18.6036C9.19732 18.5098 9.25 18.3826 9.25 18.25C9.25 18.1837 9.27634 18.1201 9.32322 18.0732C9.37011 18.0263 9.4337 18 9.5 18C9.5663 18 9.62989 18.0263 9.67678 18.0732C9.72366 18.1201 9.75 18.1837 9.75 18.25C9.75 18.3826 9.80268 18.5098 9.89645 18.6036C9.99021 18.6973 10.1174 18.75 10.25 18.75H11.75C11.8826 18.75 12.0098 18.6973 12.1036 18.6036C12.1973 18.5098 12.25 18.3826 12.25 18.25C12.25 18.1837 12.2763 18.1201 12.3232 18.0732C12.3701 18.0263 12.4337 18 12.5 18C12.5663 18 12.6299 18.0263 12.6768 18.0732C12.7237 18.1201 12.75 18.1837 12.75 18.25C12.75 18.3826 12.8027 18.5098 12.8964 18.6036C12.9902 18.6973 13.1174 18.75 13.25 18.75H14.75C14.8826 18.75 15.0098 18.6973 15.1036 18.6036C15.1973 18.5098 15.25 18.3826 15.25 18.25C15.25 18.1837 15.2763 18.1201 15.3232 18.0732C15.3701 18.0263 15.4337 18 15.5 18C15.5663 18 15.6299 18.0263 15.6768 18.0732C15.7237 18.1201 15.75 18.1837 15.75 18.25C15.75 18.3826 15.8027 18.5098 15.8964 18.6036C15.9902 18.6973 16.1174 18.75 16.25 18.75H17.75C17.8826 18.75 18.0098 18.6973 18.1036 18.6036C18.1973 18.5098 18.25 18.3826 18.25 18.25V16.75C18.25 16.6174 18.1973 16.4902 18.1036 16.3964C18.0098 16.3027 17.8826 16.25 17.75 16.25C17.6837 16.25 17.6201 16.2237 17.5732 16.1768C17.5263 16.1299 17.5 16.0663 17.5 16C17.5 15.9337 17.5263 15.8701 17.5732 15.8232C17.6201 15.7763 17.6837 15.75 17.75 15.75C17.8826 15.75 18.0098 15.6973 18.1036 15.6036C18.1973 15.5098 18.25 15.3826 18.25 15.25V13.75C18.25 13.6174 18.1973 13.4902 18.1036 13.3964C18.0098 13.3027 17.8826 13.25 17.75 13.25C17.6837 13.25 17.6201 13.2237 17.5732 13.1768C17.5263 13.1299 17.5 13.0663 17.5 13C17.5 12.9337 17.5263 12.8701 17.5732 12.8232C17.6201 12.7763 17.6837 12.75 17.75 12.75C17.8826 12.75 18.0098 12.6973 18.1036 12.6036C18.1973 12.5098 18.25 12.3826 18.25 12.25V10.75C18.25 10.6174 18.1973 10.4902 18.1036 10.3964C18.0098 10.3027 17.8826 10.25 17.75 10.25C17.6837 10.25 17.6201 10.2237 17.5732 10.1768C17.5263 10.1299 17.5 10.0663 17.5 10C17.5 9.9337 17.5263 9.87011 17.5732 9.82322C17.6201 9.77634 17.6837 9.75 17.75 9.75ZM17.25 8.855C17.0275 8.95247 16.8381 9.11267 16.7052 9.31602C16.5723 9.51937 16.5015 9.75705 16.5015 10C16.5015 10.2429 16.5723 10.4806 16.7052 10.684C16.8381 10.8873 17.0275 11.0475 17.25 11.145V11.855C17.0275 11.9525 16.8381 12.1127 16.7052 12.316C16.5723 12.5194 16.5015 12.7571 16.5015 13C16.5015 13.2429 16.5723 13.4806 16.7052 13.684C16.8381 13.8873 17.0275 14.0475 17.25 14.145V14.855C17.0275 14.9525 16.8381 15.1127 16.7052 15.316C16.5723 15.5194 16.5015 15.7571 16.5015 16C16.5015 16.2429 16.5723 16.4806 16.7052 16.684C16.8381 16.8873 17.0275 17.0475 17.25 17.145V17.75H16.645C16.5475 17.5275 16.3873 17.3381 16.184 17.2052C15.9806 17.0723 15.7429 17.0015 15.5 17.0015C15.2571 17.0015 15.0194 17.0723 14.816 17.2052C14.6127 17.3381 14.4525 17.5275 14.355 17.75H13.645C13.5475 17.5275 13.3873 17.3381 13.184 17.2052C12.9806 17.0723 12.7429 17.0015 12.5 17.0015C12.2571 17.0015 12.0194 17.0723 11.816 17.2052C11.6127 17.3381 11.4525 17.5275 11.355 17.75H10.645C10.5475 17.5275 10.3873 17.3381 10.184 17.2052C9.98063 17.0723 9.74295 17.0015 9.5 17.0015C9.25705 17.0015 9.01937 17.0723 8.81602 17.2052C8.61267 17.3381 8.45247 17.5275 8.355 17.75H7.75V17.145C7.97254 17.0475 8.16185 16.8873 8.29479 16.684C8.42773 16.4806 8.49853 16.2429 8.49853 16C8.49853 15.7571 8.42773 15.5194 8.29479 15.316C8.16185 15.1127 7.97254 14.9525 7.75 14.855V14.145C7.97254 14.0475 8.16185 13.8873 8.29479 13.684C8.42773 13.4806 8.49853 13.2429 8.49853 13C8.49853 12.7571 8.42773 12.5194 8.29479 12.316C8.16185 12.1127 7.97254 11.9525 7.75 11.855V11.145C7.97254 11.0475 8.16185 10.8873 8.29479 10.684C8.42773 10.4806 8.49853 10.2429 8.49853 10C8.49853 9.75705 8.42773 9.51937 8.29479 9.31602C8.16185 9.11267 7.97254 8.95247 7.75 8.855V8.25H8.355C8.45247 8.47254 8.61267 8.66185 8.81602 8.79479C9.01937 8.92773 9.25705 8.99853 9.5 8.99853C9.74295 8.99853 9.98063 8.92773 10.184 8.79479C10.3873 8.66185 10.5475 8.47254 10.645 8.25H11.355C11.4525 8.47254 11.6127 8.66185 11.816 8.79479C12.0194 8.92773 12.2571 8.99853 12.5 8.99853C12.7429 8.99853 12.9806 8.92773 13.184 8.79479C13.3873 8.66185 13.5475 8.47254 13.645 8.25H14.355C14.4525 8.47254 14.6127 8.66185 14.816 8.79479C15.0194 8.92773 15.2571 8.99853 15.5 8.99853C15.7429 8.99853 15.9806 8.92773 16.184 8.79479C16.3873 8.66185 16.5475 8.47254 16.645 8.25H17.25V8.855ZM12.5 9.5C11.5717 9.5 10.6815 9.86875 10.0251 10.5251C9.36875 11.1815 9 12.0717 9 13C9 13.9283 9.36875 14.8185 10.0251 15.4749C10.6815 16.1313 11.5717 16.5 12.5 16.5C13.1147 16.5013 13.7187 16.3391 14.25 16.03C14.3098 15.9987 14.3626 15.9557 14.4053 15.9035C14.448 15.8513 14.4796 15.791 14.4984 15.7263C14.5172 15.6615 14.5227 15.5936 14.5146 15.5267C14.5065 15.4597 14.4849 15.3951 14.4511 15.3367C14.4174 15.2784 14.3722 15.2274 14.3182 15.1869C14.2643 15.1465 14.2027 15.1174 14.1372 15.1013C14.0717 15.0853 14.0036 15.0826 13.9371 15.0936C13.8705 15.1045 13.8069 15.1288 13.75 15.165C13.2734 15.4402 12.7193 15.5504 12.1737 15.4785C11.628 15.4067 11.1214 15.1568 10.7322 14.7677C10.3431 14.3785 10.0932 13.8719 10.0214 13.3262C9.94956 12.7806 10.0598 12.2265 10.335 11.7499C10.6101 11.2733 11.0349 10.9008 11.5433 10.6902C12.0518 10.4796 12.6155 10.4427 13.1471 10.5851C13.6787 10.7276 14.1484 11.0415 14.4834 11.4781C14.8184 11.9147 15 12.4497 15 13V13.25C15 13.3045 14.9893 13.3585 14.9684 13.4088C14.9476 13.4592 14.917 13.5049 14.8784 13.5434C14.8399 13.582 14.7942 13.6126 14.7438 13.6334C14.6935 13.6543 14.6395 13.665 14.585 13.665C14.4745 13.665 14.3684 13.6214 14.2898 13.5438C14.2111 13.4661 14.1663 13.3605 14.165 13.25V11.835C14.1624 11.7325 14.1284 11.6334 14.0675 11.5509C14.0067 11.4684 13.922 11.4066 13.8249 11.3739C13.7278 11.3411 13.6229 11.339 13.5246 11.3679C13.4262 11.3967 13.3391 11.455 13.275 11.535C13.0374 11.4048 12.771 11.336 12.5 11.335C12.0584 11.335 11.6349 11.5104 11.3227 11.8227C11.0104 12.1349 10.835 12.5584 10.835 13C10.835 13.4416 11.0104 13.8651 11.3227 14.1773C11.6349 14.4896 12.0584 14.665 12.5 14.665C12.8995 14.6651 13.2853 14.5193 13.585 14.255C13.7838 14.4503 14.0357 14.5827 14.3092 14.6359C14.5827 14.689 14.8659 14.6605 15.1233 14.5539C15.3808 14.4473 15.6012 14.2673 15.7571 14.0363C15.913 13.8054 15.9974 13.5336 16 13.255V13C16 12.0717 15.6313 11.1815 14.9749 10.5251C14.3185 9.86875 13.4283 9.5 12.5 9.5ZM12.5 13.665C12.3685 13.665 12.2399 13.626 12.1305 13.5529C12.0212 13.4799 11.936 13.376 11.8856 13.2545C11.8353 13.133 11.8221 12.9993 11.8478 12.8703C11.8734 12.7413 11.9368 12.6228 12.0298 12.5298C12.1228 12.4368 12.2413 12.3734 12.3703 12.3478C12.4993 12.3221 12.633 12.3353 12.7545 12.3856C12.876 12.436 12.9799 12.5212 13.0529 12.6305C13.126 12.7399 13.165 12.8685 13.165 13C13.1657 13.0875 13.1489 13.1743 13.1157 13.2553C13.0826 13.3363 13.0336 13.4098 12.9717 13.4717C12.9098 13.5336 12.8363 13.5826 12.7553 13.6157C12.6743 13.6489 12.5875 13.6657 12.5 13.665Z"
                                        fill="#5063F4" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_3938_32118">
                                        <rect width="25" height="25" fill="white" transform="translate(0 0.5)" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </div>
                        <div
                            style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                            Postal Code:</div>
                        <div style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">
                            {{
            organization_data?.demographic_data?.postal_code }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div style="justify-content: flex-start; align-items: center; gap: 10px; display: flex;flex: 1;">
                        <div style="width: 25px; height: 25px; position: relative">
                            <img style="height: 25px" src="/assets/dashboard/icons/Settingyth4.svg" />
                        </div>
                        <div
                            style="color: black; font-size: 16px; font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                            Website:</div>
                        <div style="color: black; font-size: 16px; font-weight: 500; word-wrap: break-word">
                            <a v-if="this.organization_data?.demographic_data?.website != null" style="color:  #5063F4;"
                                :href="organization_data?.demographic_data?.website"> {{
            organization_data?.demographic_data?.website }}</a>
                            <span v-else>-</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-10"
                style="width: 100%; margin-top:25px; color: #252525;  font-size: 16px; font-family: Montserrat; font-weight: 500; word-wrap: break-word">
                {{ (organization_data?.demographic_data?.org_bio != 'null' &&
            organization_data?.demographic_data?.org_bio !=
            null) ? organization_data?.demographic_data?.org_bio :
            organization_data?.demographic_data?.description != null ?
                organization_data?.demographic_data?.description :
                '-'
                }}
                <br>
                <br>
                <span class="mt-4"
                    v-if="(organization_data?.demographic_data?.org_bio != 'null' && organization_data?.demographic_data?.org_bio !=
            null) || (organization_data?.demographic_data?.description != null && organization_data?.demographic_data?.description!='null')">Last
                    Modified: {{
                    organization_data?.demographic_data?.updated_at?
                    formatTimestamp(organization_data?.demographic_data?.updated_at,false):'-' }} </span>
            </div>
        </div>
    </div>
</template>
<script>
// import Tooltip from "../../../shared/Tooltip";
// import GeneralNoInteractionError from "../../../shared/messageboxes/GeneralNoInteractionError.vue";
import { formatTimestamp, formatCreatedAtToRequiredTimestamp } from '../../../../utils/commonUtils';
import Avatar from 'vue-avatar';
export default {
    components: { Avatar },
    props: ['datum', 'organization_data'],
    mounted() {
    },
    methods: {
        formatTimestamp(timeStamp, includetime) {
            let correctlyformattedtimestamp = formatCreatedAtToRequiredTimestamp(timeStamp);
            return formatTimestamp(correctlyformattedtimestamp, includetime);
        },
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
        // console.log(this.datum);
        let transferdetails = '';
        let financialstatement = '';
        if (this.organization_data?.document != null) {
            this.organization_data?.document.forEach((value, key) => {

                if (value.document_type == "Transfer Details") {
                    transferdetails = value.file_name;
                } else if (value.document_type == "Financial Statement") {
                    financialstatement = (value.file_name != null) ? value.file_name : '';
                }
            })

        }


        return {
            errorModalTitle: "",
            errorModalMessage: "",
            showErrorModal: false,
            errorModalSize: "md",
            financialstatement: (financialstatement === '') ? '' : '/' + financialstatement,
            organi: null
        }

    },
    computed: {
        creditrating() {
            let rate = this.organization_data?.deposit_credit_rating?.credit_rating?.description
            if (rate != null) {
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
        }
    },

}
</script>