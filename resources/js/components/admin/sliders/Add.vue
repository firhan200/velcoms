<template>
    <div class="box">
        <div class="page-title">
            New
        </div>
        <ErrorMessage v-if="this.is_error" :message="this.error_message" :icon="this.error_icon"/>
        <form id="submit_form" v-on:submit.prevent="submit">
            <div class="form-group">
                <label>Image</label>
                <ImageUploader :id="'image_name'" v-on:base64Result="handlePhotoChange"/>
                <div class="help">
                    <i class="fa fa-info-circle"></i> this photo will be slider image
                </div>
            </div>
            <div class="form-group">
                <label>Title</label>
                <input v-model="title" type="text" class="form-control" placeholder="Title" maxlength="100" required/>
            </div>
            <div class="form-group">
                <label>Sub Title</label>
                <input v-model="sub_title" type="text" class="form-control" placeholder="Sub Title" maxlength="100"/>
            </div>
            <div class="form-group">
                <label>Link</label>
                <input v-model="link" type="text" class="form-control" placeholder="Link" maxlength="200" required/>
            </div>
            <div align="left">
                <button id="back_btn" v-on:click="backToList()" type="button" class="btn btn-danger"><i class="fa fa-chevron-left"></i> Cancel</button>
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
import ImageUploader from './../../styles/ImageUploader.vue';

export default {
    components : {
        Loading,
        ErrorMessage,
        ImageUploader,
    },
    data(){
        return{
            //form data
            image_name: null,
            title: '',
            sub_title: '',
            link: '',

            //loader
            is_loading: false,

            //error
            is_error: false,
            error_message: '',
            error_icon: ''
        }
    },
    mounted(){
        //init keyboard press
        this.keyboardPress();
    },
    methods : {
        backToList(){
            this.$emit('backToList');
        },
        handlePhotoChange(base64String){
            this.image_name = base64String;
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
        isValid(body){
            //init errors array
            let errors = [];

            if(body.image_name===null || body.image_name===''){
                errors.push(true);
                //show toast
                this.$toast.open({
                    message: 'Image cannot be empty.',
                    type: 'error',
                    position: config.toast_position
                });
            }

            //check if input is valid or not
            if(errors.length > 0){
                return false;
            }else{
                return true;
            }
        },
        //submit form
        submit(){
            //hide error
            this.showError(false);

            //show loading
            this.setLoading(true);

            //post body
            let body = {
                image_name : this.image_name,
                title : this.title,
                sub_title : this.sub_title,
                link : this.link,
            }

            //validation
            if(this.isValid(body)){
                //call API
                axios.post('/api/admin/sliders', body, userHelper.authenticationBearer())
                .then(res => {
                    if(res.status===200){
                        //check if success
                        if(res.data.is_success){
                            //success add
                            this.$toast.open({
                                message: 'Successfully add new data.',
                                type: 'success',
                                position: config.toast_position
                            });

                            //back to list and then refresh list
                            this.$emit('backToList', true);
                        }else{
                            //failed to add
                            let message = (typeof res.data.status !=='undefined' ? res.data.status : res.data.message);
                            this.showError(true, message, 'fa fa-info-circle');
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
            }else{
                //hide error
                this.showError(false);

                //hide loading
                this.setLoading(false);
            }
        },
        keyboardPress(){
            document.addEventListener("keydown", (event) => {
                if (event.ctrlKey || event.metaKey) {
                    switch (String.fromCharCode(event.which).toLowerCase()) {
                    case 's': //CTRL + S
                        event.preventDefault();
                        document.getElementById('submit_btn').click();
                        break;
                    }
                }
                return false;
            }, false);
        }
    }
}
</script>

