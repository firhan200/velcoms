<template>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4 offset-sm-2 offset-md-3 offset-lg-4">
                <div class="login-box">
                    <br/>
                    <div class="box">                     
                        <form v-on:submit.prevent="loginProcess">
                            <div v-if="message != ''" class="alert alert-danger">
                                <i class="fa fa-info-circle"></i> {{ message }}
                            </div>
                            <div class="form-group">
                                <input v-model="email" type="email" placeholder="E-mail" class="form-control" required/>
                            </div>
                            <div class="form-group">
                                <input v-model="password" type="password" placeholder="Password" class="form-control" required/>
                            </div>
                            <div align="left" class="bottom-space">
                                <input v-model="remember_me" type="checkbox"/>&nbsp;remember me
                            </div>
                            <button v-if="!isLoading" type="submit" class="btn btn-sm btn-default btn-login bottom-space">Login</button>
                            <button v-if="isLoading" type="button" class="btn btn-sm btn-default btn-login bottom-space" disabled>
                                <Loading />
                            </button>
                        </form>
                        <div align="center" class="bottom-space">
                            <router-link to='/forgot-password'>Forgot Password</router-link>
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
import userTypes from './../constants/userTypes';

export default {
    components:{
        Loading
    },
    data(){
        return{
            message : '',
            isLoading : false,
            email : 'admin@velcoms.com',
            password : '123456',
            remember_me : true
        }
    },
    methods: {
        loginProcess(){
            //show loading
            this.isLoading = true;
            //hide error message
            this.message = '';

            this.$store.dispatch('login', {
                data : {
                    email : this.email,
                    password : this.password,
                    remember_me : this.remember_me
                },
                callback : res => {
                    //check validation
                    if(res.isValid){
                        //login success
                        //get user info
                        this.$store.dispatch('getUser',{
                            callback : res => {
                                if(res.isValid){
                                    //success init user details
                                    let redirectTo = '/cms';
                                    let user = this.$store.getters.getUser;

                                    //redirect
                                    this.$router.push(redirectTo);
                                }else{
                                    //set error message
                                    this.message = res.message;
                                }

                                //hide loading
                                this.isLoading = false;
                            }
                        });
                    }else{
                        //login failed
                        this.message = res.message;

                        //hide loading
                        this.isLoading = false;
                    }
                }
            })
        }
    }
}
</script>