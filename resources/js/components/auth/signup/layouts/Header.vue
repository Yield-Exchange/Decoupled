<template>
    <div class="top-header px-4 d-flex shadow-sm align-items-center justify-content-between mb-3">
        <div>
            <!-- <form method="post" class="timezone_switcher_form" action="#">
                <select name="timezone" class="form-control switch_timezone" required>
                    <option value="">Select Timezone</option>
                    <option value="" v-for="timezone in getTimezones">{{ timezone }}</option>
                </select>
            </form> -->

        </div>
        <div class="d-flex align-items-center gap-3">
            <!-- <img src="/assets/signup/mail.png" alt="">
            <img src="/assets/signup/Notification.png" alt=""> -->
            <div class="d-dlex justify-content-center align-items-center icon-holder"> {{ getInitials }} </div>
            <div class="d-flex flex-column justify-content-start">
                <p class="m-0 p-0 username-id">{{ getLoggedInUser.first_name + " " + getLoggedInUser.last_name }}</p>
                <p class="m-0 p-0 username-id-type">{{ user_type }}</p>

            </div>
            <img src="/assets/signup/pepicons-pop_dots-y.png" alt="" type="button" id="dropdownMenuButton"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

            <div class="dropdown">
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item d-flex justify-content-start align-items-center my-3 gap-1" href="/logout">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M11.5 18.573C10.6462 18.5753 9.80037 18.4083 9.01149 18.0816C8.22261 17.755 7.5063 17.2752 6.904 16.67C5.677 15.442 5 13.81 5 12.073C5 10.336 5.677 8.704 6.904 7.476C6.99684 7.38316 7.10707 7.30951 7.22837 7.25926C7.34968 7.20901 7.4797 7.18315 7.611 7.18315C7.7423 7.18315 7.87232 7.20901 7.99363 7.25926C8.11493 7.30951 8.22516 7.38316 8.318 7.476C8.41084 7.56884 8.48449 7.67907 8.53474 7.80037C8.58499 7.92168 8.61085 8.0517 8.61085 8.183C8.61085 8.3143 8.58499 8.44432 8.53474 8.56563C8.48449 8.68693 8.41084 8.79716 8.318 8.89C7.468 9.741 7 10.871 7 12.073C7 13.275 7.468 14.406 8.318 15.256C9.168 16.106 10.297 16.573 11.5 16.573C12.703 16.573 13.832 16.105 14.682 15.256C15.533 14.406 16 13.276 16 12.073C16 10.87 15.532 9.74 14.682 8.89C14.5892 8.79716 14.5155 8.68693 14.4653 8.56563C14.415 8.44432 14.3892 8.3143 14.3892 8.183C14.3892 8.0517 14.415 7.92168 14.4653 7.80037C14.5155 7.67907 14.5892 7.56884 14.682 7.476C14.7748 7.38316 14.8851 7.30951 15.0064 7.25926C15.1277 7.20901 15.2577 7.18315 15.389 7.18315C15.5203 7.18315 15.6503 7.20901 15.7716 7.25926C15.8929 7.30951 16.0032 7.38316 16.096 7.476C17.323 8.705 18 10.337 18 12.073C18 13.809 17.323 15.442 16.096 16.67C15.4937 17.2752 14.7774 17.755 13.9885 18.0816C13.1996 18.4083 12.3538 18.5753 11.5 18.573ZM11.5 11C11.2348 11 10.9804 10.8946 10.7929 10.7071C10.6054 10.5196 10.5 10.2652 10.5 10V5C10.5 4.73478 10.6054 4.48043 10.7929 4.29289C10.9804 4.10536 11.2348 4 11.5 4C11.7652 4 12.0196 4.10536 12.2071 4.29289C12.3946 4.48043 12.5 4.73478 12.5 5V10C12.5 10.2652 12.3946 10.5196 12.2071 10.7071C12.0196 10.8946 11.7652 11 11.5 11Z"
                                fill="#5063F4" />
                        </svg>
                        Logout</a>

                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    // mounted() {
    //     this.availabletimezones = this.$store.getters.getTimezones
    //     this.availabletimezones = this.$store.getters.getTimezones

    //     // console.log(this.availabletimezones)
    // },


    data() {
        return {
            availabletimezones: null,
            user_type: null
        }
    },
    computed: {
        getTimezones() {
            this.availabletimezones = this.$store.getters.getTimezones
            return this.$store.getters.getTimezones
        },
        getLoggedInUser() {
            return this.$store.getters.getLoggedInUser
        },
        getInitials() {
            if (this.getLoggedInUser != null)
                return this.getLoggedInUser.first_name.charAt(0) + this.getLoggedInUser.last_name.charAt(0)
        },
        getUserType() {
            return this.$store.getters.getUserType;
        },
    },
    methods: {
        setUserType() {
            if (this.getUserType != null) {
                if (this.getUserType.toLowerCase() === 'bank') {
                    this.user_type = "Financial Institution"
                } else if (this.getUserType.toLowerCase() === 'depositor') {
                    this.user_type = "Corporate"
                }
            }
        }
    },
    watch: {
        availabletimezones() {
            // console.log(this.availabletimezones)
        },
        getUserType() {
            this.setUserType()
        }
    }

}
</script>

<style scoped>
.switch_timezone {
    background-color: #EFF2FE !important;
    color: #5063F4 !important;
    padding: 0 40px !important;
    background: url('/assets/signup/select.png') no-repeat left 10px center;
    /* background-size: 20px; */
}

.top-header {
    width: auto;
    height: 65px;
    flex-shrink: 0;
    background: #FFF;
}

.icon-holder {
    width: 50px;
    height: 50px;
    background-color: #44E0AA;
    border-radius: 50%;
    display: flex !important;
    color: var(--Light---text-color-on-inverted, var(--White, #FFF));
    text-align: center;
    font-family: Montserrat !important;
    font-size: 18px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: -0.5px;
    border: 2px solid #5063F4;
}

.username-id {
    color: #252525;
    font-family: Montserrat !important;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 22px;
    /* 137.5% */
    letter-spacing: 0.4px;
}

.username-id-type {
    color: #44E0AA;
    /* text-align: center; */
    font-family: Montserrat !important;
    font-size: 11px;
    font-style: normal;
    font-weight: 700;
    line-height: 22px;
    /* 200% */
    letter-spacing: 0.275px;
}
</style>