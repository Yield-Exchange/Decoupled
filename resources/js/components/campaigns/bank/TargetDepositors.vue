<template>
    <div style="width:100%;overflow-x:auto; overflow-y:hidden; ">
        <div class="target-depositor-container" style="width:100%; padding-right: 1rem !important;">
            <div
                style="width: 100%;  padding-top: 10px; padding-bottom: 10px; background: #EFF2FE; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 30px; display: inline-flex">
                <div style="justify-content: center; align-items: flex-start; display: inline-flex">
                    <div style="display: flex; flex-direction: column;">
                        <div
                            style="width: 100%; align-self: stretch; justify-content: flex-start; align-items: center; gap: 10px; display: flex">
                            <div style="width: 40px; height: 40px; position: relative">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40"
                                    fill="none">
                                    <path
                                        d="M5.58813 15.2875C6.67396 10.6585 10.2883 7.04407 14.9174 5.95825C18.2604 5.17408 21.7396 5.17407 25.0826 5.95824C29.7117 7.04407 33.326 10.6585 34.4119 15.2875C35.196 18.6305 35.196 22.1097 34.4119 25.4527C33.326 30.0818 29.7117 33.6962 25.0826 34.782C21.7396 35.5662 18.2604 35.5662 14.9174 34.782C10.2884 33.6962 6.67396 30.0818 5.58813 25.4527C4.80396 22.1097 4.80396 18.6305 5.58813 15.2875Z"
                                        fill="#EFF2FE" stroke="#5063F4" stroke-width="1.35" stroke-linecap="round" />
                                    <path
                                        d="M16.4 11C15.5407 11 14.7166 11.3504 14.109 11.9742C13.5014 12.598 13.16 13.444 13.16 14.3261C13.16 15.2082 13.5014 16.0542 14.109 16.678C14.7166 17.3017 15.5407 17.6522 16.4 17.6522C17.2593 17.6522 18.0834 17.3017 18.691 16.678C19.2986 16.0542 19.64 15.2082 19.64 14.3261C19.64 13.444 19.2986 12.598 18.691 11.9742C18.0834 11.3504 17.2593 11 16.4 11ZM14.24 14.3261C14.24 14.0349 14.2959 13.7466 14.4044 13.4775C14.513 13.2085 14.6721 12.9641 14.8726 12.7582C15.0732 12.5523 15.3113 12.3889 15.5734 12.2775C15.8355 12.166 16.1163 12.1087 16.4 12.1087C16.6837 12.1087 16.9645 12.166 17.2266 12.2775C17.4887 12.3889 17.7268 12.5523 17.9274 12.7582C18.1279 12.9641 18.287 13.2085 18.3956 13.4775C18.5041 13.7466 18.56 14.0349 18.56 14.3261C18.56 14.9142 18.3324 15.4782 17.9274 15.894C17.5223 16.3099 16.9729 16.5435 16.4 16.5435C15.8271 16.5435 15.2777 16.3099 14.8726 15.894C14.4676 15.4782 14.24 14.9142 14.24 14.3261ZM23.96 12.4783C23.6291 12.4783 23.3014 12.5452 22.9956 12.6752C22.6899 12.8052 22.4121 12.9957 22.1781 13.236C21.9441 13.4762 21.7585 13.7614 21.6318 14.0752C21.5052 14.3891 21.44 14.7255 21.44 15.0652C21.44 15.4049 21.5052 15.7413 21.6318 16.0552C21.7585 16.3691 21.9441 16.6543 22.1781 16.8945C22.4121 17.1347 22.6899 17.3252 22.9956 17.4553C23.3014 17.5853 23.6291 17.6522 23.96 17.6522C24.6283 17.6522 25.2693 17.3796 25.7419 16.8945C26.2145 16.4093 26.48 15.7513 26.48 15.0652C26.48 14.3791 26.2145 13.7211 25.7419 13.236C25.2693 12.7508 24.6283 12.4783 23.96 12.4783ZM22.52 15.0652C22.52 14.6732 22.6717 14.2972 22.9418 14.0199C23.2118 13.7427 23.5781 13.587 23.96 13.587C24.3419 13.587 24.7082 13.7427 24.9782 14.0199C25.2483 14.2972 25.4 14.6732 25.4 15.0652C25.4 15.4573 25.2483 15.8333 24.9782 16.1105C24.7082 16.3877 24.3419 16.5435 23.96 16.5435C23.5781 16.5435 23.2118 16.3877 22.9418 16.1105C22.6717 15.8333 22.52 15.4573 22.52 15.0652ZM12.62 19.1304C12.1903 19.1304 11.7783 19.3056 11.4745 19.6175C11.1707 19.9294 11 20.3524 11 20.7935V21.186C11.0009 21.2458 11.005 21.3055 11.0122 21.3648C11.0245 21.4779 11.0475 21.6331 11.0914 21.8172C11.2109 22.3024 11.4231 22.7584 11.7157 23.1587C12.4458 24.1587 13.8382 25.0435 16.4 25.0435C17.5023 25.0435 18.3886 24.8794 19.0978 24.6133C19.0039 24.2463 18.9469 23.8703 18.9279 23.4913C18.326 23.7537 17.5095 23.9348 16.4 23.9348C14.1018 23.9348 13.0642 23.1565 12.5797 22.4935C12.3735 22.2115 12.2242 21.8901 12.1405 21.5481C12.1102 21.4249 12.0902 21.2993 12.0807 21.1727L12.08 21.1593V20.7935C12.08 20.6465 12.1369 20.5055 12.2382 20.4015C12.3394 20.2975 12.4768 20.2391 12.62 20.2391H19.7516C19.9888 19.8538 20.2713 19.4998 20.5926 19.1851C20.4579 19.1488 20.3192 19.1304 20.18 19.1304H12.62ZM29 23.1957C29 24.4698 28.5069 25.6918 27.6293 26.5928C26.7516 27.4938 25.5612 28 24.32 28C23.0788 28 21.8884 27.4938 21.0107 26.5928C20.1331 25.6918 19.64 24.4698 19.64 23.1957C19.64 21.9215 20.1331 20.6995 21.0107 19.7985C21.8884 18.8975 23.0788 18.3913 24.32 18.3913C25.5612 18.3913 26.7516 18.8975 27.6293 19.7985C28.5069 20.6995 29 21.9215 29 23.1957ZM24.68 20.2391C24.68 20.1411 24.6421 20.0471 24.5746 19.9778C24.507 19.9085 24.4155 19.8696 24.32 19.8696C24.2245 19.8696 24.133 19.9085 24.0654 19.9778C23.9979 20.0471 23.96 20.1411 23.96 20.2391V22.8261H21.44C21.3445 22.8261 21.253 22.865 21.1854 22.9343C21.1179 23.0036 21.08 23.0976 21.08 23.1957C21.08 23.2937 21.1179 23.3877 21.1854 23.457C21.253 23.5263 21.3445 23.5652 21.44 23.5652H23.96V26.1522C23.96 26.2502 23.9979 26.3442 24.0654 26.4135C24.133 26.4828 24.2245 26.5217 24.32 26.5217C24.4155 26.5217 24.507 26.4828 24.5746 26.4135C24.6421 26.3442 24.68 26.2502 24.68 26.1522V23.5652H27.2C27.2955 23.5652 27.387 23.5263 27.4546 23.457C27.5221 23.3877 27.56 23.2937 27.56 23.1957C27.56 23.0976 27.5221 23.0036 27.4546 22.9343C27.387 22.865 27.2955 22.8261 27.2 22.8261H24.68V20.2391Z"
                                        fill="#5063F4" />
                                </svg>
                            </div>
                            <div
                                style="color: #252525; font-size: 30px;  font-weight: 800; line-height: 32px; word-wrap: break-word">
                                Select Organizations</div>
                        </div>
                    </div>

                </div>
            </div>
            <div
                style="width: 100%;  display: flex; justify-content: flex-end;height: 1%; margin-top: 1%;margin-bottom: 1%;">
                <div style="width: 150px">
                    <CreateGroup @group_created="getGroups" />
                </div>
            </div>
            <div style="width: 100%; height:500px; ">

                <div id="table"
                    style="height: 600px; width: 100%; padding-bottom: 39px; padding-left: 2px; padding-right: 2px; flex-direction: row; justify-content: space-between; align-items: flex-start; display: flex">
                    <div style="width: 40%;min-width:400px;">
                        <div style="display: flex; flex-direction: column;">

                            <div style="display: flex; flex-direction: row; justify-content:space-between; ">
                                <div>

                                    <FilterBox :filtered="filtered" @apply_filters="getFilters"
                                        @clear_filters="clearFilters" :provinces="provinces" :industries="industries"
                                        :groupings="groupings">

                                    </FilterBox>
                                </div>

                                <div
                                    style=" display: flex; flex-direction: column; align-items: end; justify-content: flex-end; ">

                                    <b-form-input type="text" :placeholder="'Search'" :class="'font-13 input-height '"
                                        :id="'search-input'"
                                        :aria-describedby="'input-live-help input-date_of_deposit-feedback'"
                                        :style="'border-radius: 10px;outline:none; box-shadow: none;width:200px; margin-bottom:20px;'"
                                        v-model="organizationSearchKeyWord" v-on:keyup="searchOrganization" />
                                </div>
                            </div>
                        </div>
                        <div style=" width: 100%;">

                            <div style="display: flex; justify-content: flex-end;height: 50px; width:100%;">
                                <div id="table"
                                    style="height: 500px; width:100%;  padding-bottom: 39px; padding-left: 0px; padding-right: 0px; background: #FDFDFD;border-top-left-radius: 10px; border-top-right-radius: 10px; border: 0.50px #D9D9D9 solid; flex-direction: column; justify-content: flex-start; align-items: flex-end; display: inline-flex">

                                    <div
                                        style="width: 100%; margin: 0px;margin: 0px; flex-direction: column; justify-content: flex-end; align-items: flex-end; display: flex; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                        <div
                                            style="padding: 10px;width: 100%; background: rgba(80, 99, 244, 0.05); border-top-left-radius: 10px; border-top-right-radius: 10px; overflow: hidden; justify-content: flex-start; align-items: center; gap: 10px; display: inline-flex">


                                            <div
                                                style="height: 39px;width: 55%; margin-left: 6px; color: black; font-size: 16px;  font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                                                <div class="d-flex align-items-center"
                                                    style="height: 100%;color: black; font-size: 16px;  font-weight: 700; text-transform: capitalize;">
                                                    <b-form-checkbox :id="'checkbox-all-depositors'"
                                                        name="checkbox-all-depositors" class="checkbox-select-table"
                                                        @change="toggleSelectAllDepositors" v-model="select_all_orgs" />
                                                    <span v-if="(filter_label=='')"> Depositors ({{
                                                        displayableGroupDepositors.length }})</span>
                                                    <span v-else> Depositors {{
                                                        filter_label }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="align-self: stretch; flex: 1 1 0; border: 0.50px #D9D9D9 solid">
                                        </div>
                                    </div>
                                    <!-- <div
                                    style="width: 100%; flex-direction: column; justify-content: flex-end; align-items: flex-end; display: flex">
                                </div> -->
                                    <template>
                                        <div class="depositor-list">
                                            <div v-for="(depositor, index) in displayableGroupDepositors"
                                                :key="depositor.id">
                                                <div class="depositor-row" @click="onDepositorClick(depositor)">
                                                    <div class="checkbox-column">
                                                        <b-form-checkbox :id="'checkbox-a' + index" name="checkbox-1"
                                                            class="checkbox-select-table" :value="depositor.id"
                                                            v-model="leftSelected" />
                                                    </div>
                                                    <div class="org-column">
                                                        <div class="org-label">Org</div>
                                                    </div>
                                                    <div class="name-column">{{ capitalize(depositor.name) }} </div>
                                                    <div class="new-column">
                                                        <div v-if="( depositor.login_count < 5 )"
                                                            style="width: 45px;
                                                            height: 17px;
                                                            padding: 5px; background: #44E0AA; align-items: center; gap: 10px; display: flex; justify-content:center;">
                                                            <div style="text-align: center; color: #FDFDFD; font-family: Montserrat;
                                                                font-size: 12px;
                                                                font-weight: 400;
                                                                line-height: 15px;
                                                                letter-spacing: 0em;
                                                                text-align: center;
                                                                ">
                                                                New</div>
                                                        </div>
                                                        <!-- <div v-if="( depositor.login_count < 5 )" style="cakground-color:">
                                                            New</div> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="d-flex justify-content-center align-items-center" style="width: 20%;min-width: 300px;">

                        <b-button @click="addOrgs()" class="btn-add-group">
                            <span style="display: flex; flex-direction: row; justify-content: space-between; ">
                                Add Org
                                <img src="/assets/dashboard/icons/chevron-down.svg" style="margin-left: 70px;">
                                <b-spinner v-if="loadingaddorg" variant="primary" label="Loading"
                                    style="width: 1.3rem; height: 1.3rem; margin-left: 5px;"></b-spinner>
                            </span>
                        </b-button>


                    </div>

                    <div style="width: 40%;min-width:400px; height: 500px;">
                        <div
                            style="width:100%; display: flex; flex-direction: column; align-items: end; justify-content: flex-end; ">

                            <b-form-input type="text" :placeholder="'Search'" :class="'font-13 input-height '"
                                :id="'search-input'"
                                :aria-describedby="'input-live-help input-date_of_deposit-feedback'"
                                :style="'border-radius: 10px;outline:none; box-shadow: none;margin-bottom:10px; width:200px;'"
                                v-model="groupSearchKeyWord" v-on:keyup="searchGroup" />
                        </div>
                        <div style=" width: 100%;">

                            <div style="display: flex; justify-content: flex-end;height: 50px; width:100%;">
                                <div id="table"
                                    style="height: 500px; width:100%; padding-bottom: 39px; padding-left: 0px; padding-right: 0px; background: #FDFDFD;border-top-left-radius: 10px; border-top-right-radius: 10px; border: 0.50px #D9D9D9 solid; flex-direction: column; justify-content: flex-start; align-items: flex-end; display: inline-flex">

                                    <div
                                        style="width: 100%; margin: 0px;margin: 0px; flex-direction: column; justify-content: flex-end; align-items: flex-end; display: flex; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                                        <div
                                            style="padding: 10px;width: 100%; background: rgba(80, 99, 244, 0.05); border-top-left-radius: 10px; border-top-right-radius: 10px; overflow: hidden; justify-content: flex-start; align-items: center; gap: 10px; display: inline-flex">

                                            <div
                                                style="height: 39px;width: 60%; color: black; font-size: 16px;  font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                                                <div
                                                    style="width: 100%; display: flex; justify-content: space-between; align-items: center;">
                                                    <div class="form-label-text-required-container"
                                                        style="color: black;font-size: 16px;  font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                                                        Groups

                                                    </div>
                                                    <div class="form-label-helper-container">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            viewBox="0 0 16 16" fill="none" id="grouphelper">
                                                            <g clip-path="url(#clip0_1586_18945)">
                                                                <path
                                                                    d="M6.06016 5.99992C6.2169 5.55436 6.52626 5.17866 6.93347 4.93934C7.34067 4.70002 7.81943 4.61254 8.28495 4.69239C8.75047 4.77224 9.17271 5.01427 9.47688 5.3756C9.78106 5.73694 9.94753 6.19427 9.94683 6.66659C9.94683 7.99992 7.94683 8.66659 7.94683 8.66659M8.00016 11.3333H8.00683M14.6668 7.99992C14.6668 11.6818 11.6821 14.6666 8.00016 14.6666C4.31826 14.6666 1.3335 11.6818 1.3335 7.99992C1.3335 4.31802 4.31826 1.33325 8.00016 1.33325C11.6821 1.33325 14.6668 4.31802 14.6668 7.99992Z"
                                                                    stroke="#5063F4" stroke-width="1.33333"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                            </g>
                                                            <defs>
                                                                <clipPath id="clip0_1586_18945">
                                                                    <rect width="16" height="16" fill="white" />
                                                                </clipPath>
                                                            </defs>
                                                        </svg>
                                                        <!-- {{ showHelperText }} -->
                                                        <Tooltip
                                                            message="Please click on the group name to show or hide group members. To add a member to a group, please select the group and the depositor by checking the checkboxes against their names and then click add org."
                                                            target="grouphelper" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                style="width: 22%; height: 39px; color: black;font-size: 16px;  font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                                                Members</div>
                                            <div
                                                style="width: 18%; height: 39px; color: black; font-size: 16px;  font-weight: 700; text-transform: capitalize; word-wrap: break-word">
                                                Actions</div>
                                        </div>
                                        <div style="align-self: stretch; flex: 1 1 0; border: 0.50px #D9D9D9 solid">
                                        </div>
                                    </div>
                                    <!-- <div
                                    style="width: 100%; flex-direction: column; justify-content: flex-end; align-items: flex-end; display: flex">
                                </div> -->
                                    <template>
                                        <div style=" overflow-y: auto; overflow-x:hidden;width:100%;">
                                            <div v-if="(groups_lists?.length > 0)"
                                                v-for="(group, index) in groups_lists" style="width:100%; height:auto;">
                                                <div
                                                    style="flex-direction: column; width:100%;  justify-content: flex-start; align-items: flex-start; display: flex;cursor: pointer">
                                                    <div
                                                        style="width: 100%;height: auto; flex-direction: column; justify-content: flex-start; align-items: flex-start; display: flex">
                                                        <div v-bind:class="((groups_selected.includes(group.id))) ? 'active-group' : ''"
                                                            style="padding: 2px 10px; height:auto; width:100%; border-bottom: 0.50px #D9D9D9 solid; justify-content: flex-start; align-items: center; gap: 10px; display: inline-flex">
                                                            <div
                                                                style="width: 5%; height: 39px;padding: 5px;  justify-content: flex-start;align-items: center; display: flex">
                                                                <b-form-checkbox :id="'checkbox-abg' + group.id"
                                                                    class="checkbox-select-table" :value="group.id"
                                                                    v-model="groups_selected" @change="selectGroups">
                                                                </b-form-checkbox>
                                                            </div>
                                                            <div style="height: auto; width:60%; justify-content: flex-start;align-items: center; display: flex"
                                                                @click="leftClick(group.id)">
                                                                <div
                                                                    style="height: 20px; padding: 5px; background: #44E0AA; align-items: center; gap: 10px; display: flex">
                                                                    <div
                                                                        style="text-align: center; color: #FDFDFD; font-size: 9px;  font-weight: 500; word-wrap: break-word">
                                                                        Grp</div>
                                                                </div>
                                                                <div
                                                                    style="width: 91%;height:auto;padding: 5px; color: black;  font-size: 16px;  font-weight: 500;  justify-content: flex-start;align-items: center; display: flex">
                                                                    {{ capitalize(group.group_name) }}</div>
                                                            </div>
                                                            <div
                                                                style="width: 22%; height: auto; color: #252525; font-size: 16px;  font-weight: 500;   justify-content: flex-start;align-items: center; display: flex;">
                                                                {{ getActiveCount(group.depositors) }}

                                                            </div>
                                                            <div
                                                                style="width: 18%; height: 39px;   color: #252525; font-size: 16px;  font-weight: 500;  justify-content: space-between;align-items: center; display: flex;">

                                                                <EditGroup v-if="group.fi_id!=0" :group="group"
                                                                    @group_updated="getGroups" />
                                                                <div v-if="group.fi_id!=0"
                                                                    @click="deleteGroupInit(group.id)">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="25" viewBox="0 0 24 25" fill="none">
                                                                        <path
                                                                            d="M1.91169 8.94217C2.67177 5.70184 5.20184 3.17177 8.44217 2.41169C10.7823 1.86277 13.2177 1.86277 15.5578 2.41169C18.7982 3.17177 21.3282 5.70185 22.0883 8.94218C22.6372 11.2823 22.6372 13.7177 22.0883 16.0578C21.3282 19.2982 18.7982 21.8282 15.5578 22.5883C13.2177 23.1372 10.7823 23.1372 8.44218 22.5883C5.20185 21.8282 2.67177 19.2982 1.91169 16.0578C1.36277 13.7177 1.36277 11.2823 1.91169 8.94217Z"
                                                                            fill="#EFF2FE" stroke="#5063F4"
                                                                            stroke-width="1.35" />
                                                                        <path
                                                                            d="M11.34 8.81289V9.02539H13.3592V8.81289C13.3592 8.5311 13.2529 8.26085 13.0635 8.06159C12.8742 7.86233 12.6174 7.75039 12.3496 7.75039C12.0818 7.75039 11.825 7.86233 11.6357 8.06159C11.4464 8.26085 11.34 8.5311 11.34 8.81289ZM10.5323 9.02539V8.81289C10.5323 8.30566 10.7238 7.81921 11.0646 7.46055C11.4054 7.10189 11.8676 6.90039 12.3496 6.90039C12.8316 6.90039 13.2938 7.10189 13.6346 7.46055C13.9755 7.81921 14.1669 8.30566 14.1669 8.81289V9.02539H17.1958C17.3029 9.02539 17.4056 9.07017 17.4813 9.14987C17.5571 9.22957 17.5996 9.33767 17.5996 9.45039C17.5996 9.56311 17.5571 9.67121 17.4813 9.75091C17.4056 9.83061 17.3029 9.87539 17.1958 9.87539H16.5868L15.8227 16.9168C15.7663 17.4354 15.5306 17.914 15.1604 18.2614C14.7903 18.6088 14.3115 18.8006 13.8156 18.8004H10.8836C10.3877 18.8006 9.90895 18.6088 9.53878 18.2614C9.16861 17.914 8.93288 17.4354 8.87653 16.9168L8.11246 9.87539H7.50346C7.39635 9.87539 7.29363 9.83061 7.21789 9.75091C7.14216 9.67121 7.09961 9.56311 7.09961 9.45039C7.09961 9.33767 7.14216 9.22957 7.21789 9.14987C7.29363 9.07017 7.39635 9.02539 7.50346 9.02539H10.5323ZM9.67938 16.8199C9.71309 17.131 9.85439 17.4181 10.0763 17.6266C10.2983 17.8351 10.5853 17.9503 10.8828 17.9504H13.816C14.1135 17.9503 14.4005 17.8351 14.6225 17.6266C14.8444 17.4181 14.9857 17.131 15.0194 16.8199L15.7742 9.87539H8.9254L9.67938 16.8199ZM11.1381 11.3629C11.2452 11.3629 11.3479 11.4077 11.4236 11.4874C11.4994 11.5671 11.5419 11.6752 11.5419 11.7879V16.0379C11.5419 16.1506 11.4994 16.2587 11.4236 16.3384C11.3479 16.4181 11.2452 16.4629 11.1381 16.4629C11.031 16.4629 10.9282 16.4181 10.8525 16.3384C10.7768 16.2587 10.7342 16.1506 10.7342 16.0379V11.7879C10.7342 11.6752 10.7768 11.5671 10.8525 11.4874C10.9282 11.4077 11.031 11.3629 11.1381 11.3629ZM13.965 11.7879C13.965 11.6752 13.9224 11.5671 13.8467 11.4874C13.771 11.4077 13.6683 11.3629 13.5611 11.3629C13.454 11.3629 13.3513 11.4077 13.2756 11.4874C13.1998 11.5671 13.1573 11.6752 13.1573 11.7879V16.0379C13.1573 16.1506 13.1998 16.2587 13.2756 16.3384C13.3513 16.4181 13.454 16.4629 13.5611 16.4629C13.6683 16.4629 13.771 16.4181 13.8467 16.3384C13.9224 16.2587 13.965 16.1506 13.965 16.0379V11.7879Z"
                                                                            fill="#5063F4" />
                                                                    </svg>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <template>
                                                    <div v-for="(depositor, index) in group.depositors"
                                                        v-if="open_group && open_group == group.id && depositor.status==='ACTIVE' && depositor.is_test === isTest && depositor.enable_campaigns === 1 "
                                                        :key="index"
                                                        style="width:92%;margin-left:8%;border-bottom:1px #D9D9D9 solid">
                                                        <div
                                                            style="flex-direction: row; width:100%; min-height: 44px; justify-content: flex-start; align-items: center; display: flex;">
                                                            <div
                                                                style="width: 8%; height: 100%; font-size: 9px;  font-weight: 500; word-wrap: break-word">
                                                                <span
                                                                    style=" background-color:#5063F4; padding: 5px; color: white;">
                                                                    Org
                                                                </span>

                                                            </div>
                                                            <div
                                                                style="width: 72%; height: 100%; color: black; font-size: 16px;  font-weight: 500; word-wrap: break-word">
                                                                {{ capitalize(depositor.name) }}
                                                            </div>
                                                            <div style="width: 18%; height: 100%;">
                                                                <div v-if="group.fi_id!=0"
                                                                    @click="showConfirmRemoveOrg(depositor, group)"
                                                                    style="display:flex; justify-content: flex-end; ">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="25" viewBox="0 0 24 25" fill="none"
                                                                        style="margin-left: 45px;">
                                                                        <path
                                                                            d="M1.91169 8.94217C2.67177 5.70184 5.20184 3.17177 8.44217 2.41169C10.7823 1.86277 13.2177 1.86277 15.5578 2.41169C18.7982 3.17177 21.3282 5.70185 22.0883 8.94218C22.6372 11.2823 22.6372 13.7177 22.0883 16.0578C21.3282 19.2982 18.7982 21.8282 15.5578 22.5883C13.2177 23.1372 10.7823 23.1372 8.44218 22.5883C5.20185 21.8282 2.67177 19.2982 1.91169 16.0578C1.36277 13.7177 1.36277 11.2823 1.91169 8.94217Z"
                                                                            fill="#EFF2FE" stroke="#5063F4"
                                                                            stroke-width="1.35" />
                                                                        <path
                                                                            d="M11.34 8.81289V9.02539H13.3592V8.81289C13.3592 8.5311 13.2529 8.26085 13.0635 8.06159C12.8742 7.86233 12.6174 7.75039 12.3496 7.75039C12.0818 7.75039 11.825 7.86233 11.6357 8.06159C11.4464 8.26085 11.34 8.5311 11.34 8.81289ZM10.5323 9.02539V8.81289C10.5323 8.30566 10.7238 7.81921 11.0646 7.46055C11.4054 7.10189 11.8676 6.90039 12.3496 6.90039C12.8316 6.90039 13.2938 7.10189 13.6346 7.46055C13.9755 7.81921 14.1669 8.30566 14.1669 8.81289V9.02539H17.1958C17.3029 9.02539 17.4056 9.07017 17.4813 9.14987C17.5571 9.22957 17.5996 9.33767 17.5996 9.45039C17.5996 9.56311 17.5571 9.67121 17.4813 9.75091C17.4056 9.83061 17.3029 9.87539 17.1958 9.87539H16.5868L15.8227 16.9168C15.7663 17.4354 15.5306 17.914 15.1604 18.2614C14.7903 18.6088 14.3115 18.8006 13.8156 18.8004H10.8836C10.3877 18.8006 9.90895 18.6088 9.53878 18.2614C9.16861 17.914 8.93288 17.4354 8.87653 16.9168L8.11246 9.87539H7.50346C7.39635 9.87539 7.29363 9.83061 7.21789 9.75091C7.14216 9.67121 7.09961 9.56311 7.09961 9.45039C7.09961 9.33767 7.14216 9.22957 7.21789 9.14987C7.29363 9.07017 7.39635 9.02539 7.50346 9.02539H10.5323ZM9.67938 16.8199C9.71309 17.131 9.85439 17.4181 10.0763 17.6266C10.2983 17.8351 10.5853 17.9503 10.8828 17.9504H13.816C14.1135 17.9503 14.4005 17.8351 14.6225 17.6266C14.8444 17.4181 14.9857 17.131 15.0194 16.8199L15.7742 9.87539H8.9254L9.67938 16.8199ZM11.1381 11.3629C11.2452 11.3629 11.3479 11.4077 11.4236 11.4874C11.4994 11.5671 11.5419 11.6752 11.5419 11.7879V16.0379C11.5419 16.1506 11.4994 16.2587 11.4236 16.3384C11.3479 16.4181 11.2452 16.4629 11.1381 16.4629C11.031 16.4629 10.9282 16.4181 10.8525 16.3384C10.7768 16.2587 10.7342 16.1506 10.7342 16.0379V11.7879C10.7342 11.6752 10.7768 11.5671 10.8525 11.4874C10.9282 11.4077 11.031 11.3629 11.1381 11.3629ZM13.965 11.7879C13.965 11.6752 13.9224 11.5671 13.8467 11.4874C13.771 11.4077 13.6683 11.3629 13.5611 11.3629C13.454 11.3629 13.3513 11.4077 13.2756 11.4874C13.1998 11.5671 13.1573 11.6752 13.1573 11.7879V16.0379C13.1573 16.1506 13.1998 16.2587 13.2756 16.3384C13.3513 16.4181 13.454 16.4629 13.5611 16.4629C13.6683 16.4629 13.771 16.4181 13.8467 16.3384C13.9224 16.2587 13.965 16.1506 13.965 16.0379V11.7879Z"
                                                                            fill="#5063F4" />
                                                                    </svg>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </template>
                                            </div>
                                        </div>

                                    </template>
                                    <NoGroup v-if="!loading && groups_lists?.length === 0" />
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
            <div style="margin-top: 30px; margin-bottom: 15px;">
                <div v-if="has_navigation"
                    style="display: flex;flex-direction: row;justify-content: flex-end;width: 100%;gap: 50px">
                    <b-button @click="$emit('back-step', 3)"
                        style="width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: #9CA1AA 2px solid !important;background-color: #EFF2FE !important;color:#9CA1AA  !important;">
                        Previous
                    </b-button>
                    <!-- <b-button @click="saveAsDraftSteps = 1"
                        style="width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: #5063F4 2px solid !important;background-color: #EFF2FE !important;color:#5063F4;  !important;">
                        Save as Draft
                    </b-button> -->
                    <b-button @click="nextStep"
                        style=" width:166px;  height: 40px !important;padding: 10px, 30px, 10px, 30px !important;border-radius: 20px !important;border: 2px !important;background-color: #5063F4 !important;color: white !important;">
                        Next
                    </b-button>
                </div>

            </div>
            <!-- <ApiError @cancelled="response_error = null" title="Delete Group" :message="response_error"
                :show="response_error !== null" /> -->
            <GeneralNoInteractionError :size="box_size" @cancelled="() => showgeneralmessagebox = false"
                :title="box_title" :show="showgeneralmessagebox" :message="box_message" />
            <OKButtonActionMessageBoxVue :size="successModalSize" @closedSuccessModal="showSuccessModal = false"
                :title="successModalTitle" :showm="showSuccessModal" @okClicked="showSuccessModal = false" />


            <ConfirmDeletionPrompt :size="successModalSize" @closedSuccessModal="deleteStep = 0" btnOneText="Previous"
                btnTwoText="Delete" title='Are you sure you want to Delete this Group ?' :showm="deleteStep === 1"
                @btnOneClicked="deleteStep = 0" @btnTwoClicked="deleteGroup()"
                message="This operation cannot be undone." />

            <ConfirmRemoveOrganization :size="successModalSize" @closedSuccessModal="confirmRemoveOrg = 0"
                btnOneText="No" btnTwoText="Yes" :title=confirmOrganizationRemoveMessage
                @btnOneClicked="confirmRemoveOrg = 0" @btnTwoClicked="removeOrgs()" message=""
                :showm="confirmRemoveOrg === 1" />



            <DeleteGroupSuccess :size="successModalSize" @closedSuccessModal="showDeleteGroupSuccess = false"
                btnOneText="Previous" btnTwoText="View Groups" title="Group successfully Deleted"
                :showm="showDeleteGroupSuccess" @btnOneClicked="showDeleteGroupSuccess = false"
                @btnTwoClicked="showDeleteGroupSuccess = false" />
        </div>

    </div>
</template>
<style>
    .target-depositor-container {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .target-depositor-container .custom-checkbox .custom-control-label {
        margin-top: 0;
    }

    .target-depositor-container .custom-checkbox .custom-control-label::before {
        border-radius: 4px !important;
        border: 0.50px #5063F4 solid !important;
    }

    .target-depositor-container .active-group {
        width: 100%;
        cursor: pointer;
        background: rgba(80, 99, 244, 0.20);
    }

    .depositor-list {
        overflow: auto;
        width: 100%;
    }

    .depositor-row {
        display: flex;
        cursor: pointer;
        padding: 2px 10px;
        border-bottom: 0.5px #D9D9D9 solid;
        align-items: center;
        gap: 10px;
    }

    .checkbox-column {
        width: 5%;
        height: 39px;
        padding: 5px;
        display: flex;
        align-items: center;
    }

    .org-column {
        height: 20px;
        padding: 5px;
        background: #5063F4;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .org-label {
        text-align: center;
        color: #FDFDFD;
        font-size: 9px;
        font-weight: 500;
        word-wrap: break-word;
    }

    .name-column {
        width: 80%;
        min-height: 39px;
        height: auto;
        padding: 5px;
        color: black;
        font-size: 16px;
        font-weight: 500;
        align-items: center;
        display: flex;
    }

    .new-column {
        width: 11%;
        min-height: 39px;
        height: auto;
        padding: 5px;
        color: black;
        font-size: 16px;
        font-weight: 500;
        align-items: center;
        display: flex;
    }

    .btn-add-rm-group {
        min-width: 200px;
        height: 40px !important;
        border: 0.50px #D9D9D9 solid;
        padding: 10px 30px 10px 30px !important;
        border-radius: 20px !important;
        color: #5063F4 !important;
        background-color: #F8FAFF !important;
        display: flex;
        justify-content: flex-start !important;
        /* Use flexbox for layout */

        /* Center content vertically */
    }

    .btn-add-group {
        margin-top: 200px;
        min-width: 200px;
        border: 0.50px #D9D9D9 solid;
        border-radius: 20px !important;
        color: #5063F4 !important;
        background-color: #F8FAFF !important;
        /* Use flexbox for layout */

        /* Center content vertically */
    }
</style>
<script>
    import TwoButtonActionMessageBox from "../../shared/messageboxes/TwoButtonActionMessageBox.vue";
    import OKButtonActionMessageBoxVue from "../../shared/messageboxes/OKButtonActionMessageBox.vue";
    import GeneralNoInteractionError from "../../shared/messageboxes/GeneralNoInteractionError.vue";
    import CreateGroup from "./CreateGroup";
    import EditGroup from "./EditGroup";
    import GroupButton from "./GroupButton";
    import Tooltip from "../../shared/Tooltip";
    // import DeleteGroupPrompt from "./DeleteGroupPrompt";
    // import DeleteGroupSuccess from "./DeleteGroupSuccess";
    import ApiError from "./ApiError";
    import Button from "../../shared/Buttons/Button";
    import NoGroup from "./NoGroup";
    import Accordion from "../../shared/SectionTitleFullWidth";
    import NoData from "../../shared/NoData";

    import DeleteGroupSuccess from "../../shared/messageboxes/TwoButtonActionMessageBox.vue";
    import ConfirmDeletionPrompt from "../../shared/messageboxes/ConfirmDeletionPrompt.vue";
    import CustomInput from "../../shared/CustomInput";
    import FilterBox from "../../shared/GroupFilterBox";


    export default {
        mounted() {

            this.getGroups();
            this.loadAllProvinces();
            this.loadAllIndustries();
        },
        components: {

            organizationfilterstr: '',
            FilterBox,
            CustomInput,
            Tooltip,
            NoData,
            OKButtonActionMessageBoxVue,
            TwoButtonActionMessageBox,
            Accordion,
            GeneralNoInteractionError,
            Button,
            EditGroup,
            CreateGroup,
            GroupButton,
            DeleteGroupSuccess,
            ConfirmDeletionPrompt,
            ConfirmRemoveOrganization: ConfirmDeletionPrompt,
            ApiError,
            NoGroup
        },
        props: ['depositors', 'has_navigation', 'data', 'selectable', 'selected_groups'],
        data() {
            let group_ids = [];
            if (this.data?.campaign_groups) {
                this.data.campaign_groups.forEach(e => {
                    group_ids.push(e.fi_campaign_group_id);
                });
            }
            console.log(this.depositors, "this.depositorsthis.depositors");
            return {
                total_depos: this.depositors?.length,
                filter_label: "",
                select_all_orgs: false,
                isTest: 0,
                doNotProceed: false,
                groupings: ['Grouped Organizations', 'Ungrouped Organizations', 'All'],
                industries: [],
                provinces: [],
                filtered: [],
                organizationSearchKeyWord: '',
                groupSearchKeyWord: '',
                confirmRemoveOrg: 0,
                orgToremove: null,
                groupToRemoveFrom: null,
                confirmOrganizationRemoveMessage: '',
                showDeleteGroupSuccess: false,
                showSuccessModal: false,
                isChecked: {},
                is_open: true,
                displayableGroupDepositors: this.depositors,
                groups_lists: null,
                open_group: null,
                leftSelected: [],
                rightSelected: [],
                groups_selected: this.selected_groups === undefined || !this.selected_groups ? group_ids : this.selected_groups,
                can_select_group: this.selectable !== undefined ? this.selectable : true,
                submitted: false,
                loading: false,
                success_group: false,
                response_error: null,
                group_id_to_delete: null,
                deleteStep: 0,
                showgeneralmessagebox: false,
                box_message: null,
                box_title: null,
                box_size: "md",
                successModalTitle: "",
                successbtnOneText: "",
                successbtnTwoText: "",
                showSuccessModal: false,
                successModalSize: "md",
                errorModalSize: "md",
                showErrorModal: false,
                errorModalTitle: "",
                errorModalMessage: "",
                loadingaddorg: false,
                loadingrmorg: false,

            };
        },
        methods: {


            onDepositorClick(depositor) {

            },
            capitalize(thestring) {
                if (thestring != undefined) {
                    return thestring
                        .toLowerCase()
                        .split(' ')
                        .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
                        .join(' ');
                }

            },
            async nextStep() {
                if (!this.groups_selected || this.groups_selected?.length === 0) {

                    this.box_message = "Select at least 1 group.";
                    this.box_title = "Campaign Groups";
                    this.showgeneralmessagebox = !this.showgeneralmessagebox;
                    return;
                }

                let allSelected = [];
                if (this.groups_selected) {
                    this.groups_selected.forEach((e) => {
                        //   console.log(e, "propro");
                        let p = e?.fi_campaign_group_id;
                        if (p === undefined) {
                            p = e;
                        }

                        if (p) {

                            if (this.doNotProceed) {
                                this.box_message = "The selected groups should have members.";
                                this.box_title = "Campaign Groups";
                                this.showgeneralmessagebox = !this.showgeneralmessagebox;
                                return;
                            } else {
                                allSelected.push(p);
                            }

                        }
                    });
                }



                await this.getGroup(allSelected);

                await this.getGroupDeposCount(allSelected);


            },
            selectGroups(newValue) {
                if (this.isChecked[newValue]) {
                    if (!this.groups_selected.includes(newValue)) {
                        this.groups_selected.push(newValue);
                    }


                } else {
                    const index = this.groups_selected.indexOf(newValue);
                    if (index !== -1) {
                        console.log(index, "index to unselect -1");
                        this.groups_selected.splice(index, 1);
                    }

                }

            },
            searchGroup() {
                if (this.groupSearchKeyWord != null && this.groupSearchKeyWord != '') {
                    this.loading = true;
                    let this_ = this;
                    axios.get(`/campaigns/fi/my-groups?search=${this.groupSearchKeyWord}`)
                        .then(response => {
                            this.isTest = response?.data?.is_test;
                            this_.groups_lists = response?.data?.groups;
                            this.loading = false;
                        }).catch(error => {
                            console.log("error > " + error);
                            this_.groups_lists = null;
                            this.loading = false;
                        });
                } else {
                    this.getGroups();
                }
            },
            getGroups() {
                this.loading = true;
                let this_ = this;
                axios.get(`/campaigns/fi/my-groups?search=${this.groupSearchKeyWord}`)
                    .then(response => {

                        this.isTest = response?.data?.is_test;
                        this_.groups_lists = response?.data?.groups;
                        this.loading = false;

                    }).catch(error => {
                        console.log("error > " + error);
                        this_.groups_lists = null;
                        this.loading = false;
                    });
            },
            getGroup(groupselected) {
                this.loading = true;
                let this_ = this;
                axios.get(`/campaigns/fi/get-group-depos?groups=${groupselected}`)
                    .then(response => {

                        if (response?.data === 0) {
                            this.box_message = "The selected groups should have members.";
                            this.box_title = "Campaign Groups";
                            this.showgeneralmessagebox = !this.showgeneralmessagebox;
                        } else {
                            //         this.$emit('selectedGroups', groupselected);
                            //      this.$emit('back-step', 5)
                        }

                    }).catch(error => {
                        console.log("error > " + error);
                        this_.groups_lists = null;
                        this.loading = false;
                    });
            },
            getGroupDeposCount(groupselected) {
                this.loading = true;
                let this_ = this;
                axios.get(`/campaigns/fi/get-group-depos-count?groups=${groupselected}`)
                    .then(response => {

                        if (response?.data === 0) {
                            this.box_message = "The selected groups should have members.";
                            this.box_title = "Campaign Groups";
                            this.showgeneralmessagebox = !this.showgeneralmessagebox;
                        } else {
                            this.$emit('selectedGroups', groupselected);
                            this.$emit('selectedGroupDepositors', response?.data);
                            this.$emit('back-step', 5)
                        }

                    }).catch(error => {
                        console.log("error > " + error);
                        this_.groups_lists = null;
                        this.loading = false;
                    });
            },
            checkedDepositors(newValue) {

                if (newValue > 0) {
                    // Checkbox checked
                    this.leftSelected.push(newValue);

                } else {

                    // Checkbox unchecked
                    newValue = Math.abs(newValue);
                    const index = this.leftSelected.indexOf(newValue);
                    if (index !== -1) {
                        this.leftSelected.splice(index, 1);
                    }
                }

            },
            checkedGroups(newValue) {
                if (newValue > 0) {
                    // Checkbox checked
                    this.rightSelected.push(newValue);
                } else {
                    // Checkbox unchecked
                    newValue = Math.abs(newValue);
                    const index = this.rightSelected.indexOf(newValue);
                    if (index !== -1) {
                        this.rightSelected.splice(index, 1);
                    }
                }
            },
            async addOrgs() {
                let this_ = this;

                if (Array.isArray(this.leftSelected)) {
                    const length = this.leftSelected.length;
                    //validate that the selected is in the displayable depos
                    //validate that the selected is in the dispalyable depos

                    if (length === 0) {
                        this.box_message = "Please select a minimum of one depositor from your left hand side to add to your group .";
                        this.box_title = "Adding Depositor To a Group";
                        this.showgeneralmessagebox = !this.showgeneralmessagebox;

                    } else {
                        //    set open group based on selected groups
                        if (this.groups_selected.length > 1) {
                            this.box_message = "Please select only One Group.";
                            this.box_title = "Adding Depositor To a Group";
                            this.showgeneralmessagebox = !this.showgeneralmessagebox;
                            return;
                        } else {
                            this.open_group = this.groups_selected[0];
                        }
                        // select opne group based on select groups

                        if (this.open_group == null) {
                            this.box_message = "Please select a group to add to .";
                            this.box_title = "Adding Depositor To a Group";
                            this.showgeneralmessagebox = !this.showgeneralmessagebox;
                        } else {
                            this.loadingaddorg = true;
                            axios.post("/campaigns/fi/add-group-depositor", {
                                group: this.open_group,
                                depositors: this.leftSelected
                            }).then(response => {
                                if (response.data.success) {
                                    console.log(response);
                                    this_.getGroups();
                                    if (this.leftSelected.length > 1) {
                                        this.successModalTitle = response.data.message;
                                    } else {

                                        this.successModalTitle = response.data.message;
                                    }

                                    this.showSuccessModal = true;
                                    this.successbtnOneText = "New Group";
                                    this.successbtnTwoText = "View Groups";

                                } else {
                                    this.box_message = "";
                                    this.box_title = response?.data?.message;
                                    this.showgeneralmessagebox = !this.showgeneralmessagebox;
                                }


                                this.loadingaddorg = false;
                            }).catch(error => {

                                console.log("error > " + error);
                                this.loadingaddorg = false;
                            });
                        }

                    }
                    await this_.refreshDisplayableDepositors(this.groups_selected);
                } else {
                    this.box_message = "Please select a minimum of one depositor from your left hand side to add to your group .";
                    this.box_title = "Adding a depositor to a group";
                    this.showgeneralmessagebox = !this.showgeneralmessagebox;

                }

            },
            showConfirmRemoveOrg(depositor, group) {
                this.groupToRemoveFrom = group;
                this.orgToremove = depositor;
                this.confirmOrganizationRemoveMessage = `Are you sure you want to remove ${this.orgToremove?.name} from ${this.groupToRemoveFrom?.group_name} group ?`;
                this.confirmRemoveOrg = 1;
            },
            async removeOrgs() {
                this.confirmRemoveOrg = 0;
                let this_ = this;
                this.loadingrmorg = true;
                axios.post("/campaigns/fi/remove-group-depositor", {
                    group: this.open_group,
                    depositors: [this.orgToremove?.id]
                }).then(response => {
                    if (response?.data?.success) {
                        if (this.rightSelected.length > 1) {
                            this.successModalTitle = "Depositors removed from the group";
                        } else {
                            this.successModalTitle = "Depositor removed from the group";
                        }

                        this.showSuccessModal = true;
                        this.successbtnOneText = "New Group";
                        this.successbtnTwoText = "View Groups";

                    } else {
                        this.showErrorModal = true;
                        this.errorModalTitle = "Removing a depositor";
                        this.errorModalMessage = response?.data?.message;
                    }

                    this_.getGroups();
                    // await  this.refreshDisplayableDepositors(this.open_group);
                    this.loadingrmorg = false;
                }).catch(error => {
                    console.log("error > " + error);
                    this.loadingrmorg = false;
                });
                await this_.refreshDisplayableDepositors(this.groups_selected);


            },
            async leftClick(group_id) {

                if (this.open_group == group_id) {
                    this.open_group = null;
                    // this.displayableGroupDepositors = this.depositors;
                } else {
                    this.open_group = group_id;
                    if (!this.groups_selected.includes(group_id)) {
                        this.groups_selected.push(group_id);
                    }


                    // await this.refreshDisplayableDepositors(this.groups_selected);
                }
                this.rightSelected = [];

            },
            refreshDisplayableDepositors(groups) {
                axios.post(`/campaigns/fi/get-group-unlinked-depositors?search=${this.organizationSearchKeyWord}&${this.organizationfilterstr}`, {
                    groups: this.groups_selected,
                }).then(response => {
                    this.displayableGroupDepositors = response?.data;
                }).catch(error => {

                });

            },
            loadAllProvinces() {

                axios.get("/get-all-provinces", {
                }).then(response => {
                    this.provinces = response?.data;

                }).catch(error => {

                });

            },
            loadAllIndustries() {

                axios.get("/get-all-industries", {
                }).then(response => {
                    //  console.log(response?.data, "all industries");
                    this.industries = response?.data;

                }).catch(error => {

                });

            },
            searchOrganization() {
                this.refreshDisplayableDepositors();
            },
            clearFilters() {
                this.organizationfilterstr = '';
                this.refreshDisplayableDepositors();
            },
            getFilters(data) {

                this.organizationfilterstr = data;
                this.refreshDisplayableDepositors();
            },
            refreshDisplayableDepositors(groups) {
                axios.post(`/campaigns/fi/get-group-unlinked-depositors?search=${this.organizationSearchKeyWord}&${this.organizationfilterstr}`, {
                    groups: this.groups_selected,
                }).then(response => {
                    this.displayableGroupDepositors = response?.data;

                }).catch(error => {

                });

            },
            deleteGroupInit(group_id) {
                this.group_id_to_delete = group_id;
                this.deleteStep = 1;
            },
            refreshGroupsSelectedOn(groupselected) {
                let newgroups = this.groups_selected.filter(element => element !== groupselected);

                this.groups_selected = newgroups;
                return newgroups;

            },
            deleteGroup() {
                this.submitted = true;
                // let this_ = this;
                axios.post("/campaigns/fi/delete-group", {
                    group: this.group_id_to_delete,
                }).then(response => {


                    if (response?.data?.success) {



                        console.log(this.groups_selected, "selected groups");
                        let newgroups = this.refreshGroupsSelectedOn(this.group_id_to_delete);
                        console.log(newgroups, " $newgroups ");

                        this.response_error = null;
                        this.success_group = true;
                        this.deleteStep = 2;
                        this.getGroups();
                        this.showDeleteGroupSuccess = true;


                    } else {
                        throw new Error(response?.data?.message);
                    }
                    this.submitted = false;
                }).catch(error => {
                    console.log("error > " + error);
                    this.success_group = false;
                    this.submitted = false;
                    this.response_error = error;
                    this.deleteStep = 0;
                });
            },
            async deleteRefresh() {
                // await  this.refreshDisplayableDepositors(this.open_group);
                this.deleteStep = 0;
                this.group_id_to_delete = null;
                this.success_group = false;
                this.response_error = null;
                this.getGroups();

                console.log(this.groups_selected, "groups_selected");

            },
            viewGroups() {
                this.show = false;
                this.showSuccessModal = false;
            },
            newGroup() {
                this.show = true;
                this.success_group = false;
            },
            closeAddGroupModal() {
                this.show = false;
            },
            closeErrorModal() {
                this.showErrorModal = false;
            },
            closeSuccessModal() {
                this.showSuccessModal = false;
            },
            toggleSelectAllDepositors(newval) {
                if (newval) {
                    this.displayableGroupDepositors.map((item, key) => {
                        if (!this.leftSelected.includes(item.id)) {
                            this.leftSelected.push(item.id);
                        }
                    })
                    console.log(this.leftSelected, "this.leftSelected");
                } else {
                    let oldselected = this.leftSelected;
                    this.leftSelected = [];
                }

            },
            getActiveCount(depos) {
                const activeDepositorsCount = depos.filter(depositor => (depositor.status === "ACTIVE" && depositor.is_test === this.isTest && depositor.enable_campaigns === 1)).length;
                return activeDepositorsCount;

            }
        },
        watch: {
            leftSelected: async function (val) {
                if (this.displayableGroupDepositors.length != this.leftSelected.length) {
                    this.select_all_orgs = false;
                }
            },
            groups_selected: async function (val) {
                if (val.length === 1) {
                    this.open_group = val[0];
                }
                await this.refreshDisplayableDepositors(this.groups_selected);

            },
            displayableGroupDepositors: async function (val) {
                console.log(this.displayableGroupDepositors, "displayableGroupDepositors");
                this.leftSelected = [];
                if (this.select_all_orgs) {
                    this.displayableGroupDepositors.map((item, key) => {
                        this.leftSelected.push(item.id);
                    })

                }
                if (this.displayableGroupDepositors.length < 1) {
                    this.select_all_orgs = false;
                    this.filter_label = "(0)";
                } else {
                    if (this.total_depos === this.displayableGroupDepositors.length) {
                        this.filter_label = "";
                    } else {
                        this.filter_label = `(Filtered ${this.displayableGroupDepositors.length}/${this.total_depos})`;
                    }
                }
            }
        }
    }
</script>
