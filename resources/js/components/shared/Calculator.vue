<template>
    <div>


    <div class="row">
        <div :class="'col-md-4'" style="min-height: 690px; min-width: 300px;">
            <div class="card p-2" >
                <div class=" card-body p-2 bg-grayy" style="min-height: 650px;">
                    <b-row>
                        <div class="bg-grayy" :class="'col-md-12'" >
                            <h5 class="text-primary" style="font-weight:bold;text-transform:uppercase;text-align: center;">Calculate interest</h5>
                            <form style="padding: 20px;">

                                <b-form-group  label="How much do you want to deposit?" label-class="text-primary" style="border:none;">
                                    <b-input-group prepend="$" size="lg" class="mb-2" style="font-weight: 700;">
                                        <b-form-input class="format-imput"  @keyup="onKeyUp" v-model="amount"></b-form-input>
                                    </b-input-group>
                                </b-form-group>


                                <b-form-group  label="Expected interested rate?" label-class="text-primary" style="border:none;">
                                    <b-input-group prepend="%" size="lg" class="mb-2" style="font-weight: 700;">
                                        <b-form-input class="format-imput"  @keyup="checkInterest" v-model="interest"></b-form-input>
                                    </b-input-group>
                                </b-form-group>


                                <b-form-group  label="Terms" label-class="text-primary" style="border:none;">
                                    <b-input-group size="lg" class="mb-0" label="Terms">
                                        <template #prepend>
                                            <b-form-select :options="termsType" size="lg" style="font-size: 15px; padding: 0px 40px; height: 90%; border-radius: 10px; background-color: #e9ecef;" class="mt-1" v-model="termType"></b-form-select>
                                        </template>

                                        <b-form-input class="format-imput" v-model="terms" @keyup="updateTermList"></b-form-input>

                                    </b-input-group>
                                </b-form-group>

                                <strong class="text-black">Compare Rates</strong>

                                <b-input-group size="lg" v-if="terms" v-for="(term, index) in termList" :key="term"  class="mb-3">
                                    <template #prepend>
                                        <b-form-select :options="termsType" size="lg" style="font-size: 15px; padding: 0px 40px; height: 90%; border-radius: 10px; background-color: #e9ecef;" class="mt-1" v-model="termType" :disabled="true"></b-form-select>
                                    </template>

                                    <b-form-input class="format-imput" :value="term" v-model="termList[index]"></b-form-input>
                                    

                                </b-input-group>
                                
                                <m-button text="Calculate" link="#" type="primary" xclass="float-end font-weight-bold my-3 font-s-12"
                                    @click.native="updateChart" :loading="isLoading">
                                </m-button> 
                            </form>
                        </div>
                    </b-row>
                </div>
            </div>
        </div>

        <div class="col-md-8" v-if="openChart">
            <div class="card"  style="height: 670px;"> 
                <h3 class="card-title text-center m-2 mt-4 text-black">{{ header }}</h3>
                <div class="card-body pt-5">
                    <apexchart width="700" type="bar" :options="options" :series="series"></apexchart>
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
                termList: Array(3).fill(0),
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
                        height: 350,
                        stacked: true,
                        toolbar: {
                            show: false
                        },
                        zoom: {
                            enabled: true
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
                            horizontal: false,
                            borderRadius: 10,
                            colors: {
                            },
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

                
                    colors: ['#008ffb','#00e396', '#90EE90'],
                

                    xaxis: {
                        type: 'category',
                        categories: ['10 Days', '20 Days', '30 Days', '40 Days'],
                        tickPlacement: 'on',
                    },
                    yaxis: {
                        // min: -400000,
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
                    annotations: {
                        xaxis: [
                            {
                            x: '2 Months',
                            x2: '3 Months',
                            fillColor: '#B3F7CA',
                            label: {
                                // borderColor: '#00E396',
                                // orientation: 'horizontal',
                                text: 'X Annotation'
                            }
                            }
                        ]   
                    },  
                    legend: {
                        position: 'bottom',
                        offsetY: 10
                    },
                    fill: {
                        opacity: 1
                    },
                },
                series: [
                    // {
                    //     name: 'Amount to Invest',
                    //     data: Array(4).fill(0),
                    // }, 
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
            header() {
                return "$"+this.amount.toLocaleString('en-US')+" at "+ this.interest + "% for " + this.terms + this.termType;
            }
        },  
        mounted() {
            this.updateTermList();
        },
        methods: {
            onKeyUp(e){
                var val = this.forceNumeric(e.srcElement.value)
                e.srcElement.value = val.length > 3 ? this.addComma(val) : val;
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
                this.terms = (this.terms >= 1) ? parseInt(this.terms) : ''
                this.termList = this.termList.map((value, index) => {
                    var step = ((index+1)*3);
                    return parseInt(this.terms) + step;
                });
            },
            checkInterest() {
                if (this.interest) {
                    this.interest = this.forceNumeric(this.interest);

                    if (this.interest > 100) {
                        this.interest = 100;
                    }
                }
            },

            updateChart() {

                var terms = [parseInt(this.terms), ...this.termList];
                var interest = parseInt(this.interest);
                var amount = parseInt(this.removeComma(this.amount));
                var len = (this.termType == "DAYS") ? 365 : 12 ;
                var lentype = (this.termType == "DAYS") ? 'Days' : 'Months' ;

                this.submitted = true;
                if(interest > 100 || interest < 1 || !amount || !this.terms) {
                    return;
                }

                var cal_interest =  Math.round(amount*interest/100* parseInt(this.terms)/len); 


                var extra = Array(4).fill(cal_interest);
                var xAxis = [];

                for (var i = 0; i < 4; i++) {
            
                    xAxis.push(terms[i] + " " +lentype);
                    extra[i] =  Math.round(amount*interest/100*(parseInt(terms[i]))/len) - extra[i];
                }
                

                this.series = [
                    {
                        name: 'Amount to Invest',
                        data: Array(4).fill((amount/100)),
                        fillColor: '#444444',
                    },
                    {
                        name: 'Estimated Interest',
                        data: Array(4).fill(cal_interest), //[13, 23, 20, 8]
                        fill: '#77777'
                    }, 
                    {
                        name: 'Extra Interest',
                        data: extra //[0, 17, 15, 15]
                    },
                ]

                this.openChart = true;
                this.options.xaxis.categories = [];
                this.options.xaxis.categories = xAxis;

                this.options.dataLabels.formatter = function(val) {
                    if (amount == (val * 100)) {
                        return "$" + amount.toLocaleString("en-US");
                    } else {
                        return "$" + val.toLocaleString("en-US");
                    }
                }


                this.options.plotOptions.bar.dataLabels.total.formatter = function(val) {
                    // var rubamount = amount + (val - (amount / 100))
                    return "$" + (amount + (val - (amount / 100))).toLocaleString("en-US");
                    
                }

                this.options.plotOptions.bar.colors.backgroundBarColors = ['#D3D3D3', '#ffffff80', '#ffffff80', '#ffffff80'];
                // this.options.plotOptions.bar.colors.ranges = [
                    // {from:0, to: 20000, color:'#ff0000'},
                    // {from:amount/100, to: cal_interest, color:'#008ffb'},
                    // {from:cal_interest, to: extra[extra.length-1], color:'#00e396'}
                // ];
                // this.options.yaxis.min = amount*-1;

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