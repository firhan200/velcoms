<template>
    <div class="box">
        <div class="page-title">
            New Article
        </div>
        <ErrorMessage v-if="this.is_error" :message="this.error_message" :icon="this.error_icon"/>
        <form v-on:submit.prevent="submit">
            <div class="form-group">
                <label>Cover Photo</label>
                <ImageUploader :id="'image_cover'" v-on:base64Result="handlePhotoChange"/>
                <div class="help">
                    <i class="fa fa-info-circle"></i> this photo will be article cover
                </div>
            </div>
            <div class="form-group">
                <label>Title</label>
                <input v-model="title" type="text" class="form-control" placeholder="Title" maxlength="100" required/>
            </div>
            <div class="form-group">
                <label>Slug</label>
                <textarea v-model="slug" class="form-control" placeholder="Slug" maxlength="500" required></textarea>
            </div>
            <div class="form-group col-sm-4 p-0">
                <label>Category</label>
                <select v-if="!is_category_loading" class="form-control" v-model="article_category_id" required>
                    <option :value="article_category.id" v-bind:key="article_category.id" v-for="article_category in this.article_categories">
                        {{ article_category.name }}
                    </option>
                </select>
                <div class="help" v-if="article_categories.length < 1">
                    <router-link to="/cms/article_categories?show=add">
                        <i class="fa fa-info-circle"></i> Please create article category first.
                    </router-link>
                </div>
                <Loading v-if="is_category_loading"/>
            </div>
            <div class="form-group">
                <label>Body</label>
                <textarea id="body" v-model="body" class="ckeditor form-control" placeholder="Body" maxlength="2000" required></textarea>
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
            //DOM & Collections
            article_categories : [],

            //form data
            image_cover: null,
            title: '',
            slug: '',
            body: '',
            article_category_id : null,

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
        //get article categories
        this.getArticleCategories();

        CKEDITOR.replace(document.getElementsByClassName('ckeditor')[0]);

        //init keyboard press
        this.keyboardPress();
    },
    methods : { 
        backToList(){
            this.$emit('backToList');
        },
        handlePhotoChange(base64String){
            this.image_cover = base64String;
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
        getArticleCategories(){
            //loading
            this.is_category_loading = true;

            //call API
            axios.get('/api/admin/article_categories',{
                params:{
                    take: 999,
                    keyword: '',
                    page: 1,
                    order_by: 'name',
                    sort: 'asc'
                },
                headers: userHelper.authenticationBearer().headers
            })
            .then(res => {
                if(res.status===200){
                    //check if success
                    if(res.data.status !== 'undefined'){
                        //success
                        this.article_categories = res.data.results;

                        //select first
                        if(res.data.results.length > 0){
                            this.article_category_id = res.data.results[0].id;
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
                this.is_category_loading = false;
            })
            .catch(err => {
                //error
                this.showError(true, 'something wrong :( please contact administrator.', 'fa fa-info-circle');

                //hide loading
                this.is_category_loading = false;
            })
        },
        //input validation, 
        isValid(body){
            //init errors array
            let errors = [];

            //start validating, put yur validation here
            if(body.body===null || body.body===''){
                errors.push(true);
                //show toast
                this.$toast.open({
                    message: 'Body cannot be empty.',
                    type: 'error',
                    position: config.toast_position
                });
            }

            if(body.article_category_id===null || body.article_category_id===''){
                errors.push(true);
                //show toast
                this.$toast.open({
                    message: 'Please choose category.',
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
                image_cover : this.image_cover,
                title : this.title,
                slug : this.slug,
                article_category_id : this.article_category_id,
                body : CKEDITOR.instances.body.getData(),
            }

            //validation
            if(this.isValid(body)){
                //call API
                axios.post('/api/admin/articles', body, userHelper.authenticationBearer())
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

