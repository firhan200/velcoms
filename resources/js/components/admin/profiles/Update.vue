<template>
    <div class="box">
        <div class="page-title">
            <div class="row">
                <div class="col-8">
                    Update Profile
                </div>
                <div class="col-4" align="right">
                    <router-link to="/cms/update-password" class="help"><i class="fa fa-lock"></i> change password</router-link>
                </div>
            </div>
        </div>
        <ErrorMessage v-if="this.is_error" :message="this.error_message" :icon="this.error_icon"/>
        <form v-on:submit.prevent="submit">
            <div class="form-group p-0 col-sm-12 col-md-6 col-lg-4">
                <label>Name</label>
                <input v-model="name" type="text" class="form-control" placeholder="Name" maxlength="100" required/>
            </div>
            <div class="form-group p-0 col-sm-12 col-md-6 col-lg-4">
                <label>Email</label>
                <input v-model="email" type="email" class="form-control" placeholder="Email" maxlength="100" required/>
            </div>
            <div align="left">
                <button id="back_btn" v-on:click="back()" type="button" class="btn btn-danger"><i class="fa fa-chevron-left"></i> Cancel</button>
                <button type="submit" id="submit_btn" class="btn btn-default"><i class="fa fa-paper-plane"></i> Submit</button>
            </div>
        </form> 
    </div>
</template>

<script>
//libs
import userHelper from './../../../helpers/userHelper';
import config from './../../../config';

//components
import Loading from './../../Loading.vue';
import ErrorMessage from './../../styles/ErrorMessage.vue';

export default {
    components : {
        Loading,
        ErrorMessage
    },
    data(){
        return{
            //form data
            name: '',
            email: '',

            //loader
            is_loading: false,

            //error
            is_error: false,
            error_message: '',
            error_icon: ''
        }
    },
    mounted(){
        this.getDetail();
    },
    methods : {
        back(){
            this.$router.push('/cms');
        },
        setLoading(is_loading){
            let backBtn = document.getElementById('back_btn');
            let submitBtn = document.getElementById('submit_btn');
            let submitBtnText = 'Submit';
            if(is_loading){
                submitBtnText = "Please wait...";
            }

            submitBtn.innerText = submitBtnText;
            submitBtn.disabled = is_loading;
            backBtn.disabled = is_loading;
        },
        //error display
        showError(is_error, error_message, error_icon){
            this.is_error = is_error;

            if(is_error){
                this.error_message = error_message;
                this.error_icon = error_icon;

                // scroll to top error messages
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }else{
                this.error_message = '';
                this.error_icon = '';
            }
        },
        populateDetail(detailObj){
            this.name = detailObj.name;
            this.email = detailObj.email;
        },
        getDetail(id){
            //hide error
            this.showError(false);
            //show loading
            this.is_loading = true;

            //call API
            axios.get('/api/admin/user',{
                headers: userHelper.authenticationBearer().headers
            })
            .then(res => {
                if(res.status===200){
                    //check if success
                    if(typeof res.data.status === 'undefined'){
                        //success 
                        this.populateDetail(res.data.user);
                    }else{
                        //failed to populate
                        let message = (typeof res.data.status !=='undefined' ? res.data.status : res.data.message);
                        this.showError(true, message, 'fa fa-info-circle');
                    }
                }
                else{
                    //error response
                    this.showError(true, 'error code: '+res.status+' '+res.statusText, 'fa fa-info-circle');
                }

                //hide loading
                this.is_loading = false;
            })
            .catch(err => {
                //error
                this.showError(true, 'something wrong :( please contact administrator.', 'fa fa-info-circle');

                //hide loading
                this.setLoading(false);
            })
        },
        submit(){
            //hide error
            this.showError(false);

            //show loading
            this.setLoading(true);

            //post body
            let body = {
                name : this.name,
                email : this.email,
            }

            //call API
            axios.put('/api/admin/profiles', body, userHelper.authenticationBearer())
            .then(res => {
                if(res.status===200){
                    //check if success
                    if(res.data.is_success){
                        //success add
                        this.$toast.open({
                            message: 'Successfully update data.',
                            type: 'success',
                            position: config.toast_position
                        });

                        //re vuex user
                        this.$store.dispatch('getUser');
                    }else{
                        //failed to add
                        this.showError(true, res.data.message, 'fa fa-info-circle');
                    }
                }
                else{
                    //error response
                    this.showError(true, 'error code: '+res.status+' '+res.statusText, 'fa fa-info-circle');
                }

                //hide loading
                this.setLoading(false);
            })
            .catch(err => {
                //error
                this.showError(true, 'something wrong :( please contact administrator.', 'fa fa-info-circle');

                //hide loading
                this.setLoading(false);
            })
        }
    }
}
</script>
