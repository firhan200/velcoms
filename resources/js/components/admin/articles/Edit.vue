<template>
    <div class="box">
        <div class="page-title">
            Edit Article
        </div>
        <ErrorMessage v-if="this.is_error" :message="this.error_message" :icon="this.error_icon"/>
        <Loading align="center" v-if="this.is_loading"/>
        <form v-on:submit.prevent="submit">
            <div v-if="!this.is_loading">
                <div class="form-group">
                    <label>Cover Photo</label>
                    <div class="help">
                        current image
                    </div>
                    <ImagePreviewer :photo="this.image_cover" :path="'/images/articles/'" size="large"/>
                    <ImageUploader :id="'image_cover'" v-on:base64Result="handlePhotoChange"/>
                    <div class="help">
                        <i class="fa fa-info-circle"></i> leave this field empty if you dont want to change current image.
                    </div>
                </div>
                <div class="form-group">
                    <label>Title</label>
                    <input v-model="title" type="text" class="form-control" placeholder="Title" maxlength="100" required/>
                </div>
                <div class="form-group">
                    <label>Slug</label>
                    <div class="help">
                        short description of article.
                    </div>
                    <textarea v-model="slug" class="form-control" placeholder="Slug" maxlength="500" required></textarea>
                </div>   
                <div class="form-group">
                    <label>Category</label>
                    <select v-if="!is_category_loading" class="form-group" v-model="article_category_id">
                        <option :value="article_category.id" v-bind:key="article_category.id" v-for="article_category in this.article_categories">
                            {{ article_category.name }}
                        </option>
                    </select>
                    <Loading v-if="is_category_loading"/>
                </div>
                <div class="form-group">
                    <label>Body</label>
                    <textarea id="body" v-model="body" class="ckeditor form-control" placeholder="Body" maxlength="2000" required></textarea>
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
            //DOM & Collections
            article_categories : [],

            //form data
            image_cover: null,
            new_image_cover: null,
            title: '',
            slug: '',
            body: '',
            article_category_id : null,
            is_active : '',

            //loader
            is_loading: false,
            is_category_loading: false,

            //error
            is_error: false,
            error_message: '',
            error_icon: ''
        }
    },
    mounted(){
        this.getArticleCategories()
        .then(() => {
            //get article categories
            this.getDetail(this.id).
            then(() => {
                //init CKEditor
                CKEDITOR.replace(document.getElementsByClassName('ckeditor')[0]);
                //set body
                CKEDITOR.instances.body.setData(this.body);

                //init keyboard press
                this.keyboardPress();   
            });
        });
    },
    methods : {
        onActiveChange(e){
            this.is_active = e.value;
        },
        backToList(){
            this.$emit('backToList');
        },
        handlePhotoChange(base64String){
            this.new_image_cover = base64String;
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
        async getArticleCategories(){
            //loading
            this.is_category_loading = true;

            //call API
            await axios.get('/api/admin/article_categories', userHelper.authenticationBearer())
            .then(res => {
                if(res.status===200){
                    //check if success
                    if(res.data.status !== 'undefined'){
                        //success
                        this.article_categories = res.data.results;
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
                this.is_category_loading = false;
            })
            .catch(err => {
                //error
                this.showError(true, 'something wrong :( please contact administrator.', 'fa fa-info-circle');

                //hide loading
                this.is_category_loading = false;
            })
        },
        populateDetail(detailObj){
            this.image_cover = detailObj.image_cover;
            this.title = detailObj.title;
            this.slug = detailObj.slug;
            this.article_category_id = detailObj.article_category_id;
            this.body = detailObj.body;
            this.is_active = detailObj.is_active===1 ? true : false;
        },
        async getDetail(id){
            //hide error
            this.showError(false);
            //show loading
            this.is_loading = true;

            //call API
            await axios.get('/api/admin/articles/'+id,{
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
                this.is_loading = false;
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
                image_cover : this.new_image_cover,
                title : this.title,
                slug : this.slug,
                article_category_id : this.article_category_id,
                body : CKEDITOR.instances.body.getData(),
                is_active : this.is_active,
            }

            //validation
            if(this.isValid(body)){
                //call API
                axios.put('/api/admin/articles/'+this.id, body, userHelper.authenticationBearer())
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