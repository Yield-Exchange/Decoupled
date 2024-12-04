<template>
    <input type="text" :id="datepickerId" :disabled="disabled" :class="{ 'has-error': hasError, 'disabled': disabled }"
        :placeholder="placeholder" :style="inputStyle" style="width:100%;"
        class="input-height rounded-field date-picker" autocomplete="off">
</template>.

<script>
import $ from "jquery";
import datetimepicker from "jquery-datetimepicker";
import { formatTimestamp } from "../../utils/commonUtils";
export default {
    props: ['start_date', 'disabled', 'inputStyle', 'cannotpicktime', 'end_date', 'formattedtimezone', 'placeholder', 'id', 'hasError', 'selected_date'],
    mounted() {
        if (this.formattedtimezone != null) {
            this.NewtimeZone = JSON.parse(this.formattedtimezone)
            this.setDateOptions()
        }
    },
    data() {
        return {
            dateInput: null,
            NewtimeZone: null,
            min_date: false,
            max_date: false,
            default_date: false,
        }
    },
    computed: {
        datepickerId() {
            return this.id ? 'datepicker' + this.id : 'datepicker'
        },
        timepicker() {
            if (this.cannotpicktime) {
                return false
            } else {
                return true
            }
        },
    },
    methods: {
        dateChange() {
            this.$emit('input', this.dateInput);
            this.$emit('inputChange', this.dateInput);

        },
        getDefaultDate(date) {
            if (date !== null) {
                let defaultstr = new Date(date).toLocaleString("en-US", { timeZone: this.NewtimeZone });
                return new Date(defaultstr);
            }
            return false;
        },
        setDateOptions() {

            if (this.start_date) {
                this.min_date = this.getDefaultDate(this.start_date);
            }
            if (this.end_date) {
                this.max_date = this.getDefaultDate(this.end_date);
            }
            if (this.selected_date) {
                this.default_date = this.getDefaultDate(this.selected_date);
            }

            $(`#${this.datepickerId}`).datetimepicker({
                defaultDate: this.default_date,
                minDate: this.min_date,
                maxDate: this.max_date,
                format: this.timepicker ? "M d, Y H:i" : "M d, Y",
                timepicker: this.timepicker,
                timeZone: this.NewtimeZone,
                onClose: (date) => {
                    this.dateInput = this.formatDateTime(date)
                    this.$emit('input', this.dateInput);
                    this.$emit('inputChange', this.dateInput);

                }
            });
        },
        formatDateTime(date) {
            const dateObj = new Date(date);

            const year = dateObj.getFullYear();
            const month = String(dateObj.getMonth() + 1).padStart(2, '0');
            const day = String(dateObj.getDate()).padStart(2, '0');
            const hours = String(dateObj.getHours()).padStart(2, '0');
            const minutes = String(dateObj.getMinutes()).padStart(2, '0');
            if (this.timepicker)
                return `${year}-${month}-${day} ${hours}:${minutes}`;
            else
                return `${year}-${month}-${day}`;
        }
    },
    watch: {
        dateInput() {
            // console.log(this.dateInput)
            // this.dateChange()
        }
    }
}
</script>

<style>
.date-picker {
    padding: 0 20px;
    letter-spacing: normal;
    font-weight: 400;
    /* font-family: Montserrat !important; */
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: 18px;
    color: #252525;
    /* background: url('/assets/dashboard/icons/active-dposits.svg') no-repeat scroll 98% auto; */
    background: url('/assets/dashboard/icons/jam_calendar.svg') no-repeat 98%;
    background-position-y: center;
    background-size: 20px;

}

.date-picker::placeholder {
    color: #9CA1AA !important;
}

.has-error {
    border: 0.5px solid red;
}
</style>


<style scoped>
.disabled {
    background-color: #f5f5f5 !important;
    color: #999 !important;
}
</style>