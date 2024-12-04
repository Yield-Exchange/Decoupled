<template>
    <input type="text" id="datepicker" style="width:100%; height: 44px;" class="input-height rounded-field date-picker"
        autocomplete="off" :class="{'systemDateError': hasError}">
</template>.

<script>
    import $ from "jquery";
    import datetimepicker from "jquery-datetimepicker";
    export default {
        props: ['start_date', 'end_date', 'formattedtimezone' ,'hasError'],
        mounted() {

            console.log(this.start_date, "Start Date")
            this.setDateOptions()

        },
        data() {
            return {
                dateInput: null,
                NewtimeZone: null
            }
        },

        methods: {
            dateChange() {

                this.$emit('input', this.dateInput);
            },
            setDateOptions() {
                let currentDateTimestr = new Date().toLocaleString("en-US", { timeZone: this.timezone });
                let minimumDate = this.formatDateTime(currentDateTimestr);
                minimumDate = this.addWeekdays(minimumDate, 3);
                $("#datepicker").datetimepicker({
                    defaultDate: minimumDate,
                    minDate: minimumDate,
                    format: "Y-m-d H:i",
                    timeZone: this.timezone,
                    step: 30,
                    onClose: (dp, $input) => {
                        this.dateInput = $input.val();

                    }, onChangeDateTime: (dp, $input) => {

                    },
                });

            },
            formatDateTime(date) {
                const dateObj = new Date(date);
                const year = dateObj.getFullYear();
                const month = String(dateObj.getMonth() + 1).padStart(2, '0');
                const day = String(dateObj.getDate()).padStart(2, '0');
                const hours = String(dateObj.getHours()).padStart(2, '0');
                const minutes = String(dateObj.getMinutes()).padStart(2, '0');

                return `${year}-${month}-${day} ${hours}:${minutes}`;
            },
            validateDate(dateString) {
                const dateFormat = /^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/;
                return dateFormat.test(dateString);
            },
            addWeekdays(startDate, daysToAdd) {
                var currentDate = new Date(startDate);
                var addedDays = 0;

                while (addedDays < daysToAdd) {
                    currentDate.setDate(currentDate.getDate() + 1);

                    if (currentDate.getDay() !== 0 && currentDate.getDay() !== 6) {
                        addedDays++;
                    }
                }

                return currentDate;
            }
        },
        watch: {
            dateInput() {
                this.dateChange()
            }
        }
    }
</script>

<style>
    .date-picker {
        padding: 0 20px;
        letter-spacing: 1px;
        font-weight: normal;
    }
    .systemDateError{
        border: 0.5px solid red !important;
    }
</style>