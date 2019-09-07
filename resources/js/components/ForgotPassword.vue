<template>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4 offset-sm-2 offset-md-3 offset-lg-4">
                <div class="login-box">
                    <br/>
                    <div class="box">          
                        <div class="title">
                            Forgot Password
                            <div class="help">
                                link to reset password will be sent to your email address.
                            </div>
                        </div>           
                        <form v-on:submit.prevent="forgotPasswordProcess">
                            <div v-if="message != ''" :class="'alert alert-'+this.message_alert">
                                <i class="fa fa-info-circle"></i> {{ message }}
                            </div>
                            <div class="form-group">
                                <input v-model="email" type="email" placeholder="E-mail" class="form-control" required/>
                            </div>
                            <button v-if="!is_loading" type="submit" class="btn btn-sm btn-default btn-login bottom-space">Send Link</button>
                            <button v-if="is_loading" type="button" class="btn btn-sm btn-default btn-login bottom-space" disabled>
                                <Loading />
                            </button>
                        </form>
                        <div align="center" class="bottom-space">
                            <router-link to='/'>Back</router-link>
                        </div>
                        <div align="center">  
                            Velcoms &copy; CMS 2019
                        </div>                   
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
//components
import Loading from './Loading';

export default {
    components:{
        Loading
    },
    data(){
        return{
            message_alert : 'danger',
            message : '',
            is_loading : false,
            email : 'firhan.faisal1995@gmail.com'
        }
    },
    methods: {
       forgotPasswordProcess(){
           //hide error
            this.message = '';

            //show loading
            this.is_loading = true;

            //post body
            let body = {
                email : this.email
            }

            //call API
            axios.post('/api/admin/forgot-password', body)
            .then(res => {
                if(res.status===200){
                    //check if success
                    if(res.data.is_success){
                        //success add
                        this.email = '';
                        this.message = res.data.message;
                        this.message_alert = 'success';
                    }else{
                        //failed to add
                        let message = (typeof res.data.status !=='undefined' ? res.data.status : res.data.message);
                        this.message = message;
                        this.message_alert = 'danger';
                    }
                }
                else{
                    //error response
                    this.message = 'error code: '+res.status+' '+res.statusText;
                    this.message_alert = 'danger';
                }

                //hide loading
                this.is_loading = false;
            })
            .catch(err => {
                //error
                this.message = 'something wrong :( please contact administrator.';
                this.message_alert = 'danger';

                //hide loading
                this.is_loading = false;
            })
       }
    }
}
</script>