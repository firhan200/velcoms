<template>
    <div>
        <div class="row">
            <div class="col-md-6 col-lg-3">
                <DashboardTotal title="Articles" table="articles" :is_active="true" :is_deleted="false"/>
            </div>
            <div class="col-md-6 col-lg-3">
                <DashboardTotal title="Sliders" table="sliders" :is_active="true" :is_deleted="false"/>
            </div>
            <div class="col-md-6 col-lg-3">
                <DashboardTotal title="Photos" table="photos" :is_active="true" :is_deleted="false"/>
            </div>
            <div class="col-md-6 col-lg-3">
                <DashboardTotal title="Contacts" table="contacts" :is_active="true" :is_deleted="false"/>
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
        
    }
}
</script>
