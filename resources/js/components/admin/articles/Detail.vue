<template>
    <div class="box">
        <div class="page-title">
            Article Details
        </div>
        <ErrorMessage v-if="this.is_error" :message="this.error_message" :icon="this.error_icon"/>
        <Loading align="center" v-if="this.is_loading"/>
        <div v-if="!this.is_loading && !this.is_error">
            <div class="row detail">
                <div class="col-sm-2 label">
                    Cover
                </div>
                <div class="col-sm-10 body">
                    <ImagePreviewer :photo="this.image_cover" :path="'/images/articles/'" size="large"/>
                </div>
            </div>
            <div class="row detail">
                <div class="col-sm-2 label">
                    Title
                </div>
                <div class="col-sm-10 body">
                    {{ this.title }}
                </div>
            </div>
            <div class="row detail">
                <div class="col-sm-2 label">
                    Url
                </div>
                <div class="col-sm-10 body">
                    {{ this.url }}
                </div>
            </div>
            <div class="row detail">
                <div class="col-sm-2 label">
                    Slug
                </div>
                <div class="col-sm-10 body">
                    {{ this.slug }}
                </div>
            </div> 
            <div class="row detail">
                <div class="col-sm-2 label">
                    Body
                </div>
                <div class="col-sm-10 body">
                    <div class="html-content" v-html="this.body">
                    </div>
                </div>
            </div>
            <div class="row detail">
                <div class="col-sm-2 label">
                    Category
                </div>
                <div class="col-sm-10 body">
                    {{ this.article_category_name }}
                </div>
            </div> 
            <div class="row detail">
                <div class="col-sm-2 label">
                    Is Active
                </div>
                <div class="col-sm-10 body">
                    <IsActiveDisplay :is_active="this.is_active"/>
                </div>
            </div>
            <div class="row detail">
                <div class="col-sm-2 label">
                    Created At
                </div>
                <div class="col-sm-10 body">
                    {{ this.created_at }}
                </div>
            </div>
            <div class="row detail">
                <div class="col-sm-2 label">
                    Updated At
                </div>
                <div class="col-sm-10 body">
                    {{ this.updated_at }}
                </div>
            </div>
        </div>
        <div align="left">
            <button id="back_btn" v-on:click="backToList()" type="button" class="btn btn-danger"><i class="fa fa-chevron-left"></i> Back</button>
        </div>
    </div>
</template>

<script>
//libs
import userHelper from './../../../helpers/userHelper';
import config from './../../../config';

//components
import Loading from './../../Loading.vue';
import ErrorMessage from './../../styles/ErrorMessage.vue';
import IsActiveDisplay from './../../styles/IsActiveDisplay.vue';
import ImagePreviewer from './../../styles/ImagePreviewer.vue';

export default {
    components : {
        Loading,
        ErrorMessage,
        IsActiveDisplay,
        ImagePreviewer,
    },
    props : [
        'id'
    ],
    data(){
        return {
            // detail
            image_cover : null,
            title : '',
            slug : '',
            body : '',
            url : '',
            article_category_id : '',
            article_category_name : '',
            is_active : '',
            created_at : '',
            updated_at : '',

            //loader
            is_loading: false,

            //error
            is_error: false,
            error_message: '',
            error_icon: ''
        }
    },
    methods:{
        backToList(){
            this.$emit('backToList');
        },
        //error display
        showError(is_error, error_message, error_icon){
            this.is_error = is_error;

            if(is_error){
                this.error_message = error_message;
                this.error_icon = error_icon;
            }else{
                this.error_message = '';
                this.error_icon = '';
            }
        },
        populateDetail(detailObj){
            this.image_cover = detailObj.image_cover;
            this.title = detailObj.title;
            this.slug = detailObj.slug;
            this.body = detailObj.body;
            this.url = detailObj.url;
            this.article_category_id = detailObj.article_category_id;
            this.article_category_name = detailObj.article_category_name;
            this.is_active = detailObj.is_active===1 ? true : false;
            this.created_at = detailObj.created_at;
            this.updated_at = detailObj.updated_at;
        },
        getDetail(id){
            //hide error
            this.showError(false);
            //show loading
            this.is_loading = true;

            //call API
            axios.get('/api/admin/articles/'+id,{
                headers: userHelper.authenticationBearer().headers
            })
            .then(res => {
                if(res.status===200){
                    //check if success
                    if(res.data.is_success){
                        //success 
                        this.populateDetail(res.data.data);
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
                this.is_loading = false;
            })
        }
    },
    mounted(){
        this.getDetail(this.id);
    }
}
</script>

