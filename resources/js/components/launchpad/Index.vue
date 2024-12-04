<template>
    <div class="goal-wrapper d-flex flex-column justify-content-center gap-3 align-items-center" v-if="width"
        :style="'width:' + width + '%;'">

        <div
            style="width: 100%; height: 70px; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 10px; display: inline-flex">
            <div
                style="width: 100%; padding-left: 3px; background: #EFF2FE; justify-content: flex-start; align-items: center; display: inline-flex">
                <div style="justify-content: space-between; display: inline-flex; width: 100%">
                    <div class="page-title">
                        <div class="title-icon">
                            <img src="/assets/dashboard/icons/launchpaddashaac.svg" />
                        </div>
                        <div class="text-div">Launchpad</div>
                    </div>
                </div>
            </div>

        </div>
        <p class="p-0 m-0 whats-your-goal">
            Choose Your Investment Option.
        </p>
        <p class="p-0 m-0 starting-point" v-if="organization_type == 'DEPOSITOR'">
            Select between GICs or Repos to match your financial goals.
        </p>
        <p class="p-0 m-0 starting-point" v-if="organization_type == 'BANK'">
            Select between GICs or Repos.
        </p>
        <div class="row w-100 mt-4 "
            :class="{ 'flex-columns justify-content-center gap-3 align-items-center': changeStyle }" v-if="permissions">
            <div class="col-md-12 col-lg-6 col-12 col-sm-12 " :class="{ 'w-100': changeStyle }">
                <div class="launch-section-header d-flex w-100 justify-content-between align-items-center gap-5 my-2">
                    <p class="p-0 m-0">GIC Exchange</p>
                    <i id="gicexhnage"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                            viewBox="0 0 16 16" fill="none">
                            <g clip-path="url(#clip0_15599_1014)">
                                <path
                                    d="M6.06016 5.99992C6.2169 5.55436 6.52626 5.17866 6.93347 4.93934C7.34067 4.70002 7.81943 4.61254 8.28495 4.69239C8.75047 4.77224 9.17271 5.01427 9.47688 5.3756C9.78106 5.73694 9.94753 6.19427 9.94683 6.66659C9.94683 7.99992 7.94683 8.66659 7.94683 8.66659M8.00016 11.3333H8.00683M14.6668 7.99992C14.6668 11.6818 11.6821 14.6666 8.00016 14.6666C4.31826 14.6666 1.3335 11.6818 1.3335 7.99992C1.3335 4.31802 4.31826 1.33325 8.00016 1.33325C11.6821 1.33325 14.6668 4.31802 14.6668 7.99992Z"
                                    stroke="#5063F4" stroke-width="1.33333" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </g>
                            <defs>
                                <clipPath id="clip0_15599_1014">
                                    <rect width="16" height="16" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                        <Tooltip v-if="true" message="GIC Exchange" target="gicexhnage" />

                    </i>
                </div>
                <hr class="w-100">
                <div>
                    <ChooseWhereTo v-if="organization_type == 'DEPOSITOR'" title='Invest in GICs'
                        desc='Earn fixed returns by lending cash over a set period of time. No collateral required.'
                        image='investingic.svg' userType='' stage="" type="gic" destinationurl="post-request"
                        :isorg_admin="isorg_admin" :parentPermID="getPermissionID('enable_campaigns')"
                        :request_access="requestAccess(true, 'depositor/post-request/post-request-button', 'depositor')">
                    </ChooseWhereTo>
                    <ChooseWhereTo v-if="organization_type == 'BANK'" title='Offer GICs'
                        desc='Share the latest rates for fixed term investments.' image='postgic.svg' userType=''
                        stage="" type="gic" destinationurl="new-requests" :isorg_admin="isorg_admin"
                        :request_access="requestAccess(true, 'bank/my-campaigns/page-access', 'bank')"
                        :parentPermID="getPermissionID('enable_campaigns')">
                    </ChooseWhereTo>
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-12 col-sm-12 " :class="{ 'w-100': changeStyle }">
                <div class="launch-section-header d-flex w-100 justify-content-between align-items-center gap-5 my-2">
                    <p class="p-0 m-0">Repo Exchange</p>
                    <i id="repoexchange"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                            viewBox="0 0 16 16" fill="none">
                            <g clip-path="url(#clip0_15599_1014)">
                                <path
                                    d="M6.06016 5.99992C6.2169 5.55436 6.52626 5.17866 6.93347 4.93934C7.34067 4.70002 7.81943 4.61254 8.28495 4.69239C8.75047 4.77224 9.17271 5.01427 9.47688 5.3756C9.78106 5.73694 9.94753 6.19427 9.94683 6.66659C9.94683 7.99992 7.94683 8.66659 7.94683 8.66659M8.00016 11.3333H8.00683M14.6668 7.99992C14.6668 11.6818 11.6821 14.6666 8.00016 14.6666C4.31826 14.6666 1.3335 11.6818 1.3335 7.99992C1.3335 4.31802 4.31826 1.33325 8.00016 1.33325C11.6821 1.33325 14.6668 4.31802 14.6668 7.99992Z"
                                    stroke="#5063F4" stroke-width="1.33333" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </g>
                            <defs>
                                <clipPath id="clip0_15599_1014">
                                    <rect width="16" height="16" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                        <Tooltip v-if="true" message="Repos Exchange" target="repoexchange" />

                    </i>
                </div>
                <hr class="w-100">
                <div>
                    <ChooseWhereTo v-if="organization_type == 'DEPOSITOR'" title='Invest in Repos'
                        desc='Earn fixed returns by lending cash over a set period of time, backed by collateral.'
                        image='investinrepo.svg' userType='' stage="" type="repo" destinationurl="repos/post-request"
                        :isorg_admin="isorg_admin"
                        :request_access="requestAccess(false, 'depositor/repos/give-offers', 'depositor')"
                        :parentPermID="getPermissionID('enable_repos')">
                    </ChooseWhereTo>
                    <ChooseWhereTo v-if="organization_type == 'BANK'" title='Offer Repos'
                        desc='I want to Exchange collateral for Cash' image='providecolateral.svg' userType='' stage=""
                        type="repo" destinationurl="repos/view-all-new-requests"
                        :parentPermID="getPermissionID('enable_repos')" :isorg_admin="isorg_admin"
                        :request_access="requestAccess(false, 'bank/repos/give-offers', 'bank')">
                    </ChooseWhereTo>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
import ChooseWhereTo from '../auth/signup/shared/ChooseWhereTo.vue'
import { userCan } from '../../utils/GlobalUtils'
import Tooltip from '../shared/Tooltip.vue'
export default {
    props: ['enable_repos', 'enable_campaigns', 'userLoggedIn'],
    components: { ChooseWhereTo, Tooltip },
    beforeMount() {
        this.organization_type = this.userLoggedIn.organization.type
        this.isorg_admin = this.userLoggedIn.role[0].name == "organization-administrator"
        console.log(this.isorg_admin)
        this.getPermissions()
    },
    mounted() {

        let width = document.documentElement.offsetWidth
        console.log(width)
        if (width >= 1920) {
            this.width = 63
        } else if (width >= 1630 && width < 1920) {
            this.width = 80
        } else {
            this.width = 100
        }
        if (width != null && width < 1200) {
            this.changeStyle = true
        }
    },
    // beforeUpdate() {
    //     let width = document.documentElement.offsetWidth
    //     console.log(width)
    //     if (width > 1920) {
    //         this.width = 63
    //     } else if (width > 1631 && width < 1920) {
    //         this.width = 80
    //     } else {
    //         this.width = 100
    //     }
    // },
    data() {
        return {
            organization_type: null,
            permissions: null,
            isorg_admin: false,
            changeStyle: false,
            width: null
        }
    },
    computed: {

    },
    methods: {
        getPermissionID(permission) {
            let found_permission = this.permissions.find(item => item.slug == permission)
            return found_permission.encoded_id

        },
        requestAccess(isgic = true, permission, organization) {
            if (organization === this.organization_type.toLowerCase()) {
                if (isgic) {
                    if (this.enable_campaigns) {
                        return false
                        // return !this.userCan(permission)
                    } else {
                        return true
                    }
                } else {
                    if (this.enable_repos) {
                        return false
                        // return !this.userCan(permission)
                    } else {
                        return true
                    }
                }
            } else {
                return true
            }

        },
        userCan(permission) {
            return userCan(this.userLoggedIn, permission)
        },
        async getPermissions() {

            await axios.get('/common/account-management/get-org-level-permissions').then(response => {
                this.question = false
                this.permissions = response.data
            }).catch(err => {
                // this.question = false
                // this.fail = true
                // console.log(err)
            })

        }
    }
}

</script>

<style scoped>
.goal-wrapper {
    font-family: Montserrat !important;
}

.launch-section-header p {
    color: #252525;
    /* text-align: center; */
    font-family: Montserrat;
    font-size: 30px;
    font-style: normal;
    font-weight: 400;
    line-height: 26px;
    word-break: keep-all;
    /* white-space-collapse:  ; */

}

.starting-point {
    color: #9CA1AA;
    font-family: Montserrat;
    font-size: 20px;
    font-style: normal;
    font-weight: 400;
    line-height: 26px;
    /* 130% */
}

.whats-your-goal {
    color: #5063F4;
    text-align: center;
    font-size: 40px;
    font-style: normal;
    font-weight: 800;
    line-height: 125.4%;
}
</style>