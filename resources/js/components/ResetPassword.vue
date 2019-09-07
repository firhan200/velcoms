<template>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4 offset-sm-2 offset-md-3 offset-lg-4">
                <div class="login-box">
                    <br/>
                    <div class="box">          
                        <div class="title">
                            Reset Password
                            <div class="help">
                                create your new password.
                            </div>
                        </div>      
                        <loading align="center" v-if="this.is_loading"/>     
                        <div v-if="message != ''" :class="'alert alert-danger'">
                            <i class="fa fa-info-circle"></i> {{ message }}
                        </div>
                        <form v-if="!this.is_loading && this.is_link_valid" v-on:submit.prevent="resetPasswordProcess">
                            <div class="form-group">
                                <label>New Password</label>
                                <div class="input-group mb-3">
                                    <input v-model="password" :type="!this.is_password_visible ? 'password' : 'text'" class="form-control" placeholder="Password" minlength="4" maxlength="20" required/>
                                    <div class="input-group-append">
                                        <button v-on:click="changePasswordVisibility" class="btn btn-outline-default" type="button">
                                            <i v-if="!this.is_password_visible" class="fa fa-eye"></i>
                                            <i v-if="this.is_password_visible" class="fa fa-eye-slash"></i>
                                        </button>
                                    </div>
                                </div>            
                            </div>
                            <button v-if="!is_submit_loading" type="submit" class="btn btn-sm btn-default btn-login bottom-space">Reset</button>
                            <button v-if="is_submit_loading" type="button" class="btn btn-sm btn-default btn-login bottom-space" disabled>
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
//libs
import config from './../config.js'

//components
import Loading from './Loading';

export default {
    components:{
        Loading
    },
    data(){
        return{
            //DOM
            is_password_visible : false,
            message : '',
            is_loading : false,
            is_submit_loading : false,
            is_link_valid : false,

            //Form Data
            token : '',
            password : ''
        }
    },
    mounted(){
        //set token
        this.token = this.$route.query.token;
        //check link
        this.checkLinkAvailability();
    },
    methods: {
        changePasswordVisibility(){
            this.is_password_visible = !this.is_password_visible;
        },
        checkLinkAvailability(){
            //hide error
            this.message = '';

            //show loading
            this.is_loading = true;

            //post body
            let body = {
                token : this.token
            }

            //call API
            axios.get('/api/admin/reset-password-check', {
                params: body
            })
            .then(res => {
                if(res.status===200){
                    //check if success
                    if(res.data.is_valid){
                        //success add
                        this.message = '';
                        this.is_link_valid = true;
                    }else{
                        //failed to add
                        let message = (typeof res.data.status !=='undefined' ? res.data.status : res.data.message);
                        this.message = message;
                    }
                }
                else{
                    //error response
                    this.message = 'error code: '+res.status+' '+res.statusText;
                }

                //hide loading
                this.is_loading = false;
            })
            .catch(err => {
                //error
                this.message = 'something wrong :( please contact administrator.';

                //hide loading
                this.is_loading = false;
            })
        },
        resetPasswordProcess(){
            //hide error
            this.message = '';

            //show loading
            this.is_submit_loading = true;

            //post body
            let body = {
                token : this.token,
                password : this.password,
            }

            //call API
            axios.post('/api/admin/reset-password', body)
            .then(res => {
                if(res.status===200){
                    //check if success
                    if(res.data.is_success){
                        //success reset
                        this.$toast.open({
                            message: 'Successfully reset password.',
                            type: 'success',
                            position: config.toast_position
                        });

                        // go to login
                        this.$router.push('/');
                    }else{
                        //failed to reset
                        let message = (typeof res.data.status !=='undefined' ? res.data.status : res.data.message);
                        this.message = message;
                    }
                }
                else{
                    //error response
                    this.message = 'error code: '+res.status+' '+res.statusText;
                }

                //hide loading
                this.is_submit_loading = false;
            })
            .catch(err => {
                //error
                this.message = 'something wrong :( please contact administrator.';

                //hide loading
                this.is_submit_loading = false;
            })
       }
    }
}
</script>