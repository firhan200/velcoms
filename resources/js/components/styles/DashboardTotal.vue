<template>
    <div class="box d-total">
        <div class="title">
            {{ this.title }}
        </div>
        <div class="value">
            <Loading v-if="this.is_loading"/>
            <span v-if="!this.is_loading">{{ this.total }}</span>
            <div v-if="this.help_text !== null && this.help_text!==''" class="help">
                {{ this.help_text }}
            </div>
        </div>
    </div>
</template>

<script>
//libs
import userHelper from './../../helpers/userHelper';
import config from './../../config';

//components
import Loading from './../Loading';

export default {
    components : {
        Loading
    },
    props : [
        'title',
        'table',
        'is_active',
        'is_deleted',
        'help_text',
    ],
    data(){
        return {
            is_loading: false,
            is_valid : false,
            total : ''
        }
    },
    mounted(){
        this.getTotal();
    },
    methods : {
        getTotal(){
            //set loading
            this.is_loading = true;

            //get results
            axios.get('/api/dashboard/getTotal',{
                params:{
                    table: this.table,
                    is_active: this.is_active,
                    is_deleted: this.is_deleted
                },
                headers: userHelper.authenticationBearer().headers
            })
            .then(res => {
                //check response
                if(res.status===200){
                    if(config.is_debug){
                        console.log(res);
                    }

                    //check if res is valid
                    if(typeof res.data.status!=='undefined'){
                        //there is an error, show error
                        this.total = res.data.status;
                    }else{
                        //check if valid
                        if(res.data.is_valid){
                            //ok
                            this.is_valid = true;
                            this.total = res.data.total;
                        }else{
                            this.total = res.data.message;
                        }
                    }
                }
                else{
                    //error response
                    this.total = res.statusText;
                }

                //hide loading
                this.is_loading = false;
            })
            .catch(err => {
                //error
                //hide loading
                this.is_loading = false;
            });
        }
    }
}
</script>