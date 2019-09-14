<template>
    <div class="box">
        <div class="page-title">
            Edit Photo
        </div>
        <ErrorMessage v-if="this.is_error" :message="this.error_message" :icon="this.error_icon"/>
        <Loading align="center" v-if="this.is_loading"/>
        <form v-on:submit.prevent="submit">
            <div v-if="!this.is_loading">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Image Thumbnail</label>
                            <div class="help">
                                current thumbnail
                            </div>
                            <ImagePreviewer :photo="this.image_thumbnail_name" :path="'/images/photos/'" size="large"/>
                            <ImageUploader :id="'image_thumbnail_name'" v-on:base64Result="handleThumbnailPhotoChange"/>
                            <div class="help">
                                <i class="fa fa-info-circle"></i> this photo will be thumbnail
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Image Original</label>
                            <div class="help">
                                current original
                            </div>
                            <ImagePreviewer :photo="this.image_original_name" :path="'/images/photos/'" size="large"/>
                            <ImageUploader :id="'image_original_name'" v-on:base64Result="handleOriginalPhotoChange"/>
                            <div class="help">
                                <i class="fa fa-info-circle"></i> this photo will be original
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-sm-4 p-0">
                    <label>Gallery</label>
                    <select v-if="!is_gallery_loading" class="form-control" v-model="gallery_id">
                        <option :value="gallery.id" v-bind:key="gallery.id" v-for="gallery in this.galleries">
                            {{ gallery.title }}
                        </option>
                    </select>
                    <Loading v-if="is_gallery_loading"/>
                </div>
                <div class="form-group">
                    <label>Title</label>
                    <input v-model="title" type="text" class="form-control" placeholder="Title" maxlength="100" required/>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea id="description" v-model="description" class="ckeditor form-control" placeholder="Description" maxlength="2000" required></textarea>
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
            //collection
            galleries : [],

            //form data
            image_thumbnail_name: null,
            new_image_thumbnail_name: null,
            image_original_name: null,
            new_image_original_name: null,
            title: '',
            description: '',
            gallery_id: 0,
            is_text_shown : true,
            is_active : '',

            //loader
            is_loading: false,
            is_gallery_loading: false,

            //error
            is_error: false,
            error_message: '',
            error_icon: ''
        }
    },
    mounted(){
        this.getGalleries().then(() => {
            this.getDetail(this.id)
            .then(() => {
                //init CKEditor
                CKEDITOR.replace(document.getElementsByClassName('ckeditor')[0]);
                //set body
                CKEDITOR.instances.description.setData(this.description);
            });
        })
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
        handleThumbnailPhotoChange(base64String){
            this.new_image_thumbnail_name = base64String;
        },
        handleOriginalPhotoChange(base64String){
            this.new_image_original_name = base64String;
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
            this.image_thumbnail_name = detailObj.image_thumbnail_name;
            this.image_original_name = detailObj.image_original_name;
            this.title = detailObj.title;
            this.description = detailObj.description;
            this.gallery_id = detailObj.gallery_id;
            this.is_active = detailObj.is_active===1 ? true : false;
        },
        async getGalleries(){
            //loading
            this.is_gallery_loading = true;

            //call API
            await axios.get('/api/admin/galleries',{
                params:{
                    take: 999,
                    keyword: '',
                    page: 1,
                    order_by: 'title',
                    sort: 'asc'
                },
                headers: userHelper.authenticationBearer().headers
            })
            .then(res => {
                if(res.status===200){
                    //check if success
                    if(res.data.status !== 'undefined'){
                        //success
                        this.galleries = res.data.results;

                        //select first
                        if(res.data.results.length > 0){
                            this.gallery_id = res.data.results[0].id;
                        }
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
                this.is_gallery_loading = false;
            })
            .catch(err => {
                //error
                this.showError(true, 'something wrong :( please contact administrator.', 'fa fa-info-circle');

                //hide loading
                this.is_gallery_loading = false;
            })
        },
        async getDetail(id){
            //hide error
            this.showError(false);
            //show loading
            this.is_loading = true;

            //call API
            await axios.get('/api/admin/photos/'+id,{
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
        //input validation, 
        isValid(body){
            //init errors array
            let errors = [];

            //start validating, put yur validation here

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
                image_thumbnail_name : this.new_image_thumbnail_name,
                image_original_name : this.new_image_original_name,
                title : this.title,
                gallery_id : this.gallery_id,
                description : CKEDITOR.instances.description.getData(),
                is_active : this.is_active,
            }

            //validation
                if(this.isValid(body)){
                //call API
                axios.put('/api/admin/photos/'+this.id, body, userHelper.authenticationBearer())
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