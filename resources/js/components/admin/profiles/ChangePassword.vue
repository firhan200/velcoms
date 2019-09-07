<template>
    <div class="box">
        <div class="page-title">
            Change Password
        </div>
        <ErrorMessage v-if="this.is_error" :message="this.error_message" :icon="this.error_icon"/>
        <form v-on:submit.prevent="submit">
            <div class="form-group p-0 col-sm-12 col-md-6 col-lg-4">
                <label>Old Password</label>
                <div class="input-group mb-3">
                    <input v-model="old_password" :type="!this.is_old_password_visible ? 'password' : 'text'" class="form-control" placeholder="Password" minlength="4" maxlength="20" required/>
                    <div class="input-group-append">
                        <button v-on:click="changeOldPasswordVisibility" class="btn btn-outline-default" type="button">
                            <i v-if="!this.is_old_password_visible" class="fa fa-eye"></i>
                            <i v-if="this.is_old_password_visible" class="fa fa-eye-slash"></i>
                        </button>
                    </div>
                </div>            
            </div>
            <div class="form-group p-0 col-sm-12 col-md-6 col-lg-4">
                <label>New Password</label>
                <div class="input-group mb-3">
                    <input v-model="new_password" :type="!this.is_new_password_visible ? 'password' : 'text'" class="form-control" placeholder="Password" minlength="4" maxlength="20" required/>
                    <div class="input-group-append">
                        <button v-on:click="changeNewPasswordVisibility" class="btn btn-outline-default" type="button">
                            <i v-if="!this.is_new_password_visible" class="fa fa-eye"></i>
                            <i v-if="this.is_new_password_visible" class="fa fa-eye-slash"></i>
                        </button>
                    </div>
                </div>            
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
            //DOM
            is_old_password_visible : false,
            is_new_password_visible : false,

            //loader
            is_loading: false,

            //error
            is_error: false,
            error_message: '',
            error_icon: '',

            //data
            old_password : '',
            new_password : '',
        }
    },
    methods : {
        back(){
            this.$router.push('/cms/update-profile');
        },
        changeOldPasswordVisibility(){
            this.is_old_password_visible = !this.is_old_password_visible;
        },
        changeNewPasswordVisibility(){
            this.is_new_password_visible = !this.is_new_password_visible;
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
        submit(){
            //hide error
            this.showError(false);

            //show loading
            this.setLoading(true);

            //post body
            let body = {
                old_password : this.old_password,
                new_password : this.new_password,
            }

            //call API
            axios.put('/api/admin/profiles/password', body, userHelper.authenticationBearer())
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
                        this.$router.push('/cms/update-profile');
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
