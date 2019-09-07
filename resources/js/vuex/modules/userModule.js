/*
 ADMINISTRAOT USER MODULES
 */

import config from './../../config';
import userHelper from './../../helpers/userHelper';
import userTypes from './../../constants/userTypes';

export default {
    state:{
        user : {
            name: '',
            email: ''
        },
        token : '',
    },
    mutations:{
        setUser(state, payload){
            if(config.is_debug){
                console.log("commit setUser:");
                console.log(payload);
            }
            state.user = payload;
        },
        setToken(state, token){
            state.token = token;
        },
        clearAll(state){
            state.user = {
                name: '',
                email: ''
            };
            state.token = '';
        }
    },
    getters:{
        getToken: (state) => {
            return state.token;
        },
        getUser: (state) => {
            return state.user;
        }
    },
    actions:{
        //login method
        login : async (context, payload) => {
            if(config.is_debug){
                console.log("login actions called");
            }

            //call api
            axios.post('/api/admin/login', {
                email: payload.data.email,
                password: payload.data.password,
                remember_me: payload.data.remember_me,
            }).then(res => {
                //success
                if(config.is_debug){
                    console.log(res);
                }

                //init message
                let message = '';
                let isValid = false;

                //save jwt
                if(res.status==200){
                    //check if error or not
                    if(typeof res.data.error == 'undefined'){ //success, no error
                        message = 'success';
                        //save jwt to local storage
                        userHelper.saveJWTToLocalStorage(res.data.token);
                        //save token to state
                        context.commit('setToken', res.data.token);
                        //set is valid
                        isValid = true;
                    }else{
                        //credential failed
                        message = 'email/password incorrect';
                    }
                    
                }else{
                    message = 'internal server error';
                }

                //run callback
                payload.callback({...res, isValid : isValid, message : message});
            }).catch(err => {
                //error
                if(config.is_debug){
                    console.log("error: "+err);
                }

                //run callback
                payload.callback({isValid : false, message : 'internal server error'});
            })
        },

        //get user detail method
        getUser : (context, payload) => {
            if(config.is_debug){
                console.log("getUser actions called");
            }

            //call api
            axios.get('/api/admin/user', userHelper.authenticationBearer()).then(res => {
                //success
                if(config.is_debug){
                    console.log(res);
                }

                //init var
                let message = '';
                let isValid = false;

                //check status
                if(res.status==200){
                    //check res
                    if(typeof res.data.status !== 'undefined'){
                        //error
                        message = res.data.status;
                    }else{
                        //success
                        let user = res.data.user;

                        //save to state
                        context.commit('setUser', {
                            name : user.name,
                            email : user.email
                        });

                        isValid = true;
                    }
                }

                //run callback
                payload.callback({...res, isValid : isValid, message : message});
            }).catch(err => {
                //error
                if(config.is_debug){
                    console.log("error: "+err);
                }

                payload.callback({isValid : false, message : 'internal server error'});
            })
        },

        fetchAccessToken : (context) => {
            //check jwt from local storage
            var token = userHelper.getJWTFromLocalStorage();
            if(token!=''){
                //user has logged in, get user info again
                //set token
                context.commit('setToken', token);
            }
        },

        logout : (context) => {
            userHelper.deleteJWTToLocalStorage();

            context.commit('clearAll');
        }
    }
}