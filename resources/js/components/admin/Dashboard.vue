<template>
    <div>
        <div class="row">
            <div class="col-md-6 col-lg-3">
                <DashboardTotal title="Store" table="stores" :is_active="true" :is_deleted="false"/>
            </div>
            <div class="col-md-6 col-lg-3">
                <DashboardTotal title="Employee" table="users" :is_active="true" :is_deleted="false"/>
            </div>
            <div class="col-md-6 col-lg-3">
                <DashboardTotal :help_text="this.product_help_text" title="Product" table="products" :is_active="true" :is_deleted="false"/>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="box d-total">
                    <div class="title">
                        Sales
                    </div>
                    <div class="value">
                        257
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box d-content">
                    <div class="title">
                        <i class="fa fa-chart-line"></i> Today Sales
                    </div>
                    <div class="content">
                        <div class="container">
                            <div class="row">
                                <div class="no-pad col-lg-7 col-md-6 col-sm-12">
                                    <div id="sales-chart"></div>
                                </div>
                                <div class="no-pad col-lg-5 col-md-6 col-sm-12">
                                    <div class="today-earn">
                                        <b>Today Earnings</b>
                                        <br/>
                                        Rp. 1.045.500
                                    </div>
                                    <ul class="orders">
                                        <li v-bind:key="index" v-for="index in 5">
                                            <div class="row">
                                                <div class="col-8">
                                                    <div class="number">
                                                        ODR-190293865
                                                    </div>
                                                    <div class="total-item">
                                                        5 Items
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="price">
                                                        Rp. 150.000
                                                    </div>
                                                    <div class="time">
                                                        <i class="far fa-clock"></i> 10:30
                                                    </div>
                                                </div>
                                            </div>                                      
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
//libs
import ApexCharts from 'apexcharts';
import DashboardTotal from './../styles/DashboardTotal.vue';
import userTypes from './../../constants/userTypes.js';

export default {
    mounted(){
        this.initProductHelpText();
        this.renderSalesChart();
    },
    components: {
        DashboardTotal
    },
    data(){
        return{
            product_help_text: ''
        }
    },
    methods : {
        initProductHelpText(){
            this.$store.watch(
                (newValue, oldValue) => {
                    if(this.$store.getters.getUser.type===userTypes.ADMIN){
                        this.product_help_text = 'availables on stores';
                    }
                }
            );
        },
        renderSalesChart(){
            var options = {
                chart: {
                    type: 'line'
                },
                series: [{
                    name: 'sales',
                    data: [30,40,35,50,49,60,70,91,125]
                }],
                xaxis: {
                    categories: [1991,1992,1993,1994,1995,1996,1997, 1998,1999]
                }
            }

            var chart = new ApexCharts(document.querySelector("#sales-chart"), options);

            chart.render();
        }
    }
}
</script>
