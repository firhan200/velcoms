<template>
    <div class="box">
        <div class="page-title">
            Edit Slider
        </div>
        <ErrorMessage v-if="this.is_error" :message="this.error_message" :icon="this.error_icon"/>
        <Loading align="center" v-if="this.is_loading"/>
        <form v-on:submit.prevent="submit">
            <div v-if="!this.is_loading">
                <div class="form-group">
                    <label>Image</label>
                    <div class="help">
                        current image
                    </div>
                    <ImagePreviewer :photo="this.image_name" :path="'/images/sliders/'" size="large"/>
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
                <div class="form-group">
                    <label>Is Text Shown</label>
                    &nbsp;
                    <toggle-button class="toggle-margin" :value="this.is_text_shown" color="#82C7EB" :sync="true" :labels="true" @change="onTextShownChange($event)"/>
                    <div class="help">
                        choose whether title and subtitle need to be displayed on slider.
                    </div>
                </div>        
                <div class="form-group">
                    <label>Is Active</label>
                    &nbsp;
                    <toggle-button class="toggle-margin" :value="this.is_active" color="#82C7EB" :sync="true" :labels="true" @change="onActiveChange($event)"/>
                </div>
                <div align="left">
                    <button id="back_btn" v-on:click="backToList()" type="button" class="btn btn-danger"><i class="fa fa-chevron-left"></i> Cancel</button>
                    <button type="submit" id="submit_btn" class="btn btn-default"><i class="fa fa-save"></i> Save</button>
                </div>
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
import ImagePreviewer from './../../styles/ImagePreviewer.vue';

export default {
    components : {
        Loading,
        ErrorMessage,
        ImageUploader,
        ImagePreviewer,
    },
    props : [
        'id'
    ],
    data(){
        return{
            //form data
            image_name: null,
            new_image_name: null,
            title: '',
            sub_title: '',
            link: '',
            is_text_shown : true,
            is_active : '',

            //loader
            is_loading: false,

            //error
            is_error: false,
            error_message: '',
            error_icon: ''
        }
    },
    mounted(){
        this.getDetail(this.id);

        //init keyboard press
        this.keyboardPress();
    },
    methods : {
        onActiveChange(e){
            this.is_active = e.value;
        },
        backToList(){
            this.$emit('backToList');
        },
        onTextShownChange(e){
            this.is_text_shown = e.value;
        },
        handlePhotoChange(base64String){
            this.new_image_name = base64String;
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
            this.image_name = detailObj.image_name;
            this.title = detailObj.title;
            this.sub_title = detailObj.sub_title;
            this.link = detailObj.link;
            this.is_text_shown = detailObj.is_text_shown===1 ? true : false;
            this.is_active = detailObj.is_active===1 ? true : false;
        },
        getDetail(id){
            //hide error
            this.showError(false);
            //show loading
            this.is_loading = true;

            //call API
            axios.get('/api/admin/sliders/'+id,{
                headers: userHelper.authenticationBearer().headers
            })
            .then(res => {
                if(res.status===200){
                    //check if success
                    if(res.data.is_success){
                        //success 
                        this.populateDetail(res.data.data);
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
                this.is_loading = false;
            })
            .catch(err => {
                //error
                this.showError(true, 'something wrong :( please contact administrator.', 'fa fa-info-circle');

                //hide loading
                this.setLoading(false);
            })
        },
        //submit form
        submit(){
            //hide error
            this.showError(false);

            //show loading
            this.setLoading(true);

            //post body
            let body = {
                image_name : this.new_image_name,
                title : this.title,
                sub_title : this.sub_title,
                link : this.link,
                is_text_shown : this.is_text_shown,
                is_active : this.is_active,
            }

            //call API
            axios.put('/api/admin/sliders/'+this.id, body, userHelper.authenticationBearer())
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