<template>
    <div>


    <div class="row">
        <div :class="'col-md-4'" style="height: 700px; min-width: 400px;">
            <div class="card p-2" >
                <div class=" card-body p-2 bg-grayy" style="height: 700px;">
                    <b-row>
                        <div class="bg-grayy" :class="'col-md-12'" >
                            <h5 class="text-primary" style="font-weight:bold;text-transform:uppercase;text-align: center;">Calculate interest</h5>
                            <form style="padding: 5px;">

                                <b-form-group  label="How much do you want to deposit?" label-class="text-primary" style="border:none;">
                                    <b-input-group prepend="$" size="lg" class="mb-2" style="font-weight: 700;">
                                        <b-form-input class="format-imput"  @keyup="onKeyUp" v-model="amount" @change="fetchRates"></b-form-input>
                                    </b-input-group>
                                </b-form-group>


                                <b-form-group  label="Expected interested rate?" label-class="text-primary" style="border:none;">
                                    <b-input-group prepend="%" size="lg" class="mb-2" style="font-weight: 700;">
                                        <b-form-input class="format-imput"  @keyup="checkInterest" v-model="interest" @change="fetchRates" ></b-form-input>
                                    </b-input-group>
                                </b-form-group>


                                <b-form-group  label="Terms" label-class="text-primary" style="border:none;">
                                    <b-input-group size="lg" class="mb-0" label="Terms">
                                        <template #prepend>
                                            <b-form-select :options="termsType" size="lg"  @change="fetchRates" style="font-size: 15px; padding: 0px 40px; height: 90%; border-radius: 10px; background-color: #e9ecef;" class="mt-1" v-model="termType"></b-form-select>
                                        </template>

                                        <b-form-input class="format-imput" v-model="terms" @keyup="updateTermList"  @change="fetchRates"></b-form-input>

                                    </b-input-group>
                                </b-form-group>

                                <strong class="text-black text-primary">Compare Rates</strong>
                                

                                <template  v-if="rates" class="mt-2">
                                    <div v-for="(rate, index) in loopRates" :key="index"  class="mb-3" v-if="index < 4">
                                        <b-alert show variant="primary" style="font-size: 14px; padding: 10px;">{{ rate.organization.name }} <br>{{ rate.product_name }} for {{ parseInt(rate.term_length) +" "+ rate.term_length_type.toLowerCase() }} at {{ parseInt(rate.interest_rate) }}%</b-alert>
                                    </div>
                                </template>
                                
                                <!-- <m-button text="Calculate" link="#" type="primary" xclass="float-end font-weight-bold my-3 font-s-12"
                                    @click.native="updateChart" :loading="isLoading">
                                </m-button>  -->
                            </form>
                        </div>
                    </b-row>
                </div>
            </div>
        </div>

        <div class="col-md-8" v-if="openChart"  style="height: 720px;">
            <div class="card"  style="height: 720px;"> 
                <h3 class="card-title text-center m-2 mt-4 text-black">Compare Interest Rate</h3>
                <div class="card-body pt-5">
                    <apexchart width="800" type="bar" :options="options" :series="series"></apexchart>
                </div>
            </div>
        </div>
    </div>  

    </div>
</template>
<script>

import CustomInputGroup from "./CustomInputGroup";
import CustomInput from "./CustomInput";
import CustomSelect from "./CustomSelect";
import { on } from "events";
    export default {
        components: {
            CustomInputGroup,
            CustomSelect,
            CustomInput
        },
        
        props: ['offer','size','pStyle','cStyle'],

        data() {
            return {
                compoundFrequencies: ["At maturity", "Monthly", "Quarterly", "Semi annually", "Annually"],
                termsType: ["DAYS", "MONTHS"],
                currrencies: ["CAD", "USD"],

                termType: "MONTHS",
                terms: 12,
                rates: [],
                interest: 5,
                amount: '1,000,000',
                
                termTypeError: "",
                termsError: "",

                submitted: false,
                openChart: false,
                loading: false,

                options: {
                    chart: {
                        type: 'bar',
                        stacked: true,
                        toolbar: {
                            show: false
                        },
                        zoom: {
                            enabled: false
                        }
                    },
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            legend: {
                            position: 'bottom',
                            offsetX: -10,
                            offsetY: 0
                            }
                        }
                    }],
                    plotOptions: {
                        bar: {
                            borderRadius: 5,
                            horizontal: false,
                            dataLabels: {
                                total: {
                                    enabled: true,
                                    style: {
                                        fontSize: '13px',
                                        fontWeight: 900
                                    },
                                    formatter: function (val) {
                                        return " Total $" + val.toLocaleString("en-US");
                                    },
                                },
                            }
                        },
                    },
                    dataLabels: {
                        enabled: true,
                    },
                    xaxis: {
                        type: 'category',
                        categories: ['10 Days', '20 Days', '30 Days', '40 Days'],
                        tickPlacement: 'on',
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: [],
                                fontSize: '15px',
                                fontWeight: 500
                            },
                            formatter: function(val) {
                                return "$"+val.toLocaleString('en-US');
                            }
                        }
                    },
                    legend: {
                        position: 'bottom',
                        offsetY: 10
                    },

                },
                series: [
                    {
                        name: 'Estimated Interest',
                        data: Array(4).fill(0) //[13, 23, 20, 8]
                    }, {
                        name: 'Extra Interest',
                        data: Array(4).fill(0) //[0, 17, 15, 15]
                    },
                ]
            
            }
        },
        computed: {
            listAmount() {
               return Array(4).fill(this.amount); 
            },
            isLoading() {
                return this.loading;
            },
            loopRates() {
                return (this.rates.length <= 4) ? this.rates.slice(0, this.rates.length) : this.rates.slice(0, 4);
            }
        },  
        mounted() {
            this.fetchRates();
            this.updateTermList();
        },
        methods: {
            fetchRates() {
                var $this = this;
                const form_data = new FormData()
                form_data.set("amount", parseInt(this.removeComma(this.amount)));
                form_data.set("interest", parseInt(this.removeComma(this.interest)));
                form_data.set("termlength", parseInt(this.removeComma(this.terms)));
                form_data.set("termlengthtype", this.termType);
                axios.post(route('calculate.rate'),form_data)
                .then(response => {
                    // console.log(response.data.rates)
                    $this.rates = JSON.parse(response.data.rates)
                    $this.updateChart();
                    
                }).catch(error => {

                    
                });

            },
            onKeyUp(e){
                var val = this.forceNumeric(e.srcElement.value)
                e.srcElement.value = val.length > 3 ? this.addComma(val) : val;
                this.fetchRates()
            },
            addComma(newValue){
                let return_ =  newValue ? newValue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") : '';
                return return_;
            },
            removeComma(newValue){
                let return_ =  newValue ? parseFloat(newValue.toString().replace(/,/g, '')) : '';
                return return_;
            },
            forceNumeric(newValue){
                let return_ =  newValue ? newValue.toString().replace(/[^\d.]+/g,''): '';
                return return_;
            },
            updateTermList() {

                var data = [];

                // data['termList'] = this.loopRates.map((value) => {
                //     return parseInt(value.term_length)
                // });
                    var amount = parseInt(this.removeComma(this.amount));
                
                data['extra'] = this.loopRates.map((value) => {

                    var len = (value.term_length_type == "DAYS") ? 365 : 12 ;
                    
                    var cal_interest =  Math.round(amount*value.interest_rate/100* parseInt(this.terms)/len); 
                    return cal_interest;//parseInt(value.interest_earned);
                });
                
                // data['termType'] = this.loopRates.map((value) => {
                //     return parseInt(value.term_length_type);
                // });

                // data['amount'] = this.loopRates.map((value) => {
                //     return parseInt(value.minimum_amount);
                // });

                data['xAxis'] = this.loopRates.map((value) => {
                    return value.organization.name 
                });


                    // console.log(data)
                for (let index = 0; index < data['extra'].length; index++) {
                    for (let indexTwo = 0; indexTwo < data['extra'].length; indexTwo++) {
                        var nextIndex = indexTwo + 1;
                            
                        if(data['extra'][nextIndex]) {

                            var initialTotal = data['extra'][indexTwo];
                            var nextTotal = data['extra'][nextIndex];

                            if(initialTotal > nextTotal) {

                                var firstExtra = data['extra'][indexTwo]
                                var secondExtra = data['extra'][nextIndex]

                                var firstXaxis = data['xAxis'][indexTwo]
                                var secondXaxis = data['xAxis'][nextIndex]


                                data['extra'][nextIndex] = firstExtra
                                data['extra'][indexTwo] = secondExtra

                                data['xAxis'][nextIndex] = firstXaxis
                                data['xAxis'][indexTwo] = secondXaxis

                                index--

                            }

                        }
                    }

                }           
                return data;
            },
            checkInterest() {
                if (this.interest) {
                    this.interest = this.forceNumeric(this.interest);

                    if (this.interest > 100) {
                        this.interest = 100;
                    }

                    this.fetchRates();
                }
            },

            updateChart() {
                console.log(this.updateTermList())
                // var terms = this.updateTermList()['termList'];
                var interest = parseInt(this.interest);
                var amount = parseInt(this.removeComma(this.amount));
                var len = (this.termType == "DAYS") ? 365 : 12 ;
                var lentype = (this.termType == "DAYS") ? 'Days' : 'Months' ;

                this.submitted = true;
                if(interest > 100 || interest < 1 || !amount || !this.terms) {
                    return;
                }

                var cal_interest =  Math.round(amount*interest/100* parseInt(this.terms)/len); 
                // var lessAmount = amount/100
                var lessAmount = amount

                this.series = [
                    // {
                    //     name: 'Amount to Invest',
                    //     data: Array(this.updateTermList()['extra'].length + 1).fill(lessAmount), 
                    //     fillColor: '#444444',
                    // },
                    {
                        name: 'Estimated Interest',
                        data: [cal_interest, ...this.updateTermList()['extra']],
                        fill: '#77777'
                    }, 
                ]
                
                this.openChart = true;
                this.options.xaxis.categories = [];
                this.options.xaxis.categories = ["", ...this.updateTermList()['xAxis']];

                this.options.dataLabels.formatter = function(val) {
                    if (amount == (val * 100)) {
                        return "$" + val.toLocaleString("en-US");
                    } else {
                        return "$" + val.toLocaleString("en-US");
                    }
                }
                this.options.plotOptions.bar.dataLabels.total.formatter = function(val) {
                    return "$" + ((val - lessAmount) + amount).toLocaleString('en-US'); 
                }


                this.options.plotOptions.bar.colors.backgroundBarColors = ['#D3D3D3', '#ffffff80', '#ffffff80', '#ffffff80', '#ffffff80'];
                // this.options.colors = ['#d45325', "#905217",'#d45325']
                

            }
        }
    }
</script>


<style>
.format-imput {
    background-color: transparent;
    border: none;
    border-bottom: 1px solid #dddddd;
    outline:none !important;
    font-size: 20px !important;
}

input.format-imput:focus {
    background-color: transparent !important;
    outline: none !important;
    box-shadow: none;

}
</style>