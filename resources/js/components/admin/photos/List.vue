<template>
    <div>
        <!-- Detail Componenet -->
        <Detail :id="this.detail_id" v-if="detail_id > 0" v-on:backToList="backToList"/>

        <!-- Add Componenet -->
        <Add v-if="!show_list && show_add" v-on:backToList="backToList"/>

        <!-- Edit Componenet -->
        <Edit v-if="!show_list && show_edit" :id="this.edit_id" v-on:backToList="backToList"/>

        <!-- List Component -->
        <div class="box" v-if="show_list && detail_id < 1">
            <div class="page-title">
                Photos ({{ this.total_data }})
            </div>
            <!-- Error Message -->
            <ErrorMessage v-if="this.is_error" :message="this.error_message" :icon="this.error_icon"/>
            <!-- List Display -->
            <div>
                <div class="page-actions">
                    <div class="container-fluid p-0">
                        <div class="row">
                            <div class="col-sm-2">
                                <select v-on:change="onShownDataPerPaginationChange($event)" class="form-control">
                                    <option v-bind:key="shown" v-for="shown in this.available_shown_data" :value="shown">{{ shown }}</option>
                                </select>
                                <div class="help">
                                    <i class="fa fa-info-circle"></i> data per pagination
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <select v-on:change="onFilterByGalleryChange($event)" class="form-control" v-if="!is_gallery_loading">
                                    <option v-bind:key="gallery.id" v-for="gallery in this.galleries" :value="gallery.id">{{ gallery.title }}</option>
                                </select>
                                <Loading v-if="is_gallery_loading"/>
                                <div class="help">
                                    <i class="fa fa-info-circle"></i> Filter by gallery
                                </div>
                            </div>
                            <div class="col-sm-9 col-md-7 col-lg-4">
                                <div class="input-group mb-3">
                                    <input v-model="keyword" type="text" class="form-control" placeholder="Search..." aria-label="Search" aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                        <button v-on:click="search" class="btn btn-outline-default" type="button"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 col-md-3 col-lg-3">
                                <button v-on:click="showAddComponent()" type="button" class="btn btn-outline-default"><i class="fa fa-plus"></i> New</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid custom-table">
                    <div class="header d-none d-md-block">
                        <div class="row">
                            <div class="no-pad col-sm-2">
                                Image
                            </div>
                            <div class="no-pad col-sm-4">
                                <span class="link" v-on:click="sortBy('title')">
                                    Title
                                    <SortArrow :desc="this.desc"/>
                                </span>
                            </div>
                            <div class="no-pad col-sm-2">
                                Gallery
                            </div>
                            <div class="no-pad col-sm-2">
                                <span class="link" v-on:click="sortBy('is_active')">
                                    Is Active
                                    <SortArrow :desc="this.desc"/>
                                </span>
                            </div>
                            <div class="no-pad col-sm-2">
                                Actions
                            </div>
                        </div>
                    </div>    
                    <div align="center" v-if="this.is_loading">
                        <Loading />
                    </div>
                    <div class="body" v-if="!this.is_loading">
                        <div v-bind:key="data.id" v-for="data in this.datas" class="row">
                            <div class="no-pad col-sm-2 m-center">
                                <ImagePreviewer :photo="data.image_thumbnail_name" path="/images/photos/" size="small"/>
                            </div>
                            <div class="no-pad col-sm-4 m-center">
                                {{ data.title }}
                            </div>
                            <div class="no-pad col-sm-2 m-center">
                                <a class="link">{{ data.gallery_title }}</a>
                            </div>
                            <div class="no-pad col-sm-2 m-center">
                                <IsActiveDisplay :is_active="data.is_active"/>
                            </div>
                            <div class="no-pad col-sm-2 m-center">
                                <button v-on:click="showDetail(data.id)" class="btn btn-sm btn-default" title="View Details"><i class="fa fa-eye"></i></button>
                                <button v-on:click="showEdit(data.id)" class="btn btn-sm btn-light" title="Edit"><i class="fa fa-cog"></i></button>
                                <button v-on:click="showDeleteConfirm(data.id, data.title)" class="btn btn-sm btn-danger" title="Delete"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>

                        <div align="center">
                            <Loading v-if="is_load_more_loading"/>
                            <button v-if="!is_load_more_loading && is_show_load_more" v-on:click="loadMore" type="button" class="btn btn-sm btn-default">Load More</button>
                        </div>
                    </div>        
                </div>
            </div>
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
import SortArrow from './../../styles/SortArrow.vue';
import ImagePreviewer from './../../styles/ImagePreviewer.vue';
import Detail from './Detail.vue';
import Add from './Add.vue';
import Edit from './Edit.vue';

export default {
    components: {
        Loading,
        ErrorMessage,
        IsActiveDisplay,
        SortArrow,
        ImagePreviewer,
        Detail,
        Add,
        Edit
    },
    data(){
        return{
            /* choose which component should be shown */
            detail_id: 0,
            show_list : true,
            show_add : false,
            show_edit : false,
            edit_id : 0,

            //default available shown data options
            available_shown_data : [10],

            //loading variables
            is_show_load_more : false,
            is_load_more_loading : false,
            is_loading : false,
            is_gallery_loading : false,

            //list api params
            gallery_id : null,
            take : 10,
            keyword : '',
            page : 1,
            total_data : 0,
            order_by : 'created_at',
            desc : true,

            //errors variable
            is_error : false,
            error_message : 'status error',
            error_icon : 'fa fa-info-circle',

            //results
            galleries : [],
            datas : []
        }
    },
    mounted(){
        //get available shown data
        this.setAvailableShownData();

        //get datas
        this.getDatas(false);

        //get galleries
        this.getGalleries();
    },
    methods: {
        //sorting
        sortBy(order_by){
            let is_desc = !this.desc;
            if(this.order_by!==order_by){
                is_desc = false;
            }
            this.desc = is_desc;
            this.order_by = order_by;

            if(config.is_debug){
                console.log("order by:"+this.order_by+", sort desc:"+this.desc);
            }

            //refresh
            this.resetFilter();
            this.getDatas(false);
        },
        //delete item
        showDeleteConfirm($id, $name){
            var isDelete = confirm("Delete "+$name+" ?");
            if (isDelete == true) {
                //call delete API
                axios.delete('/api/admin/photos/'+$id, userHelper.authenticationBearer())
                .then(res => {
                    if(res.status===200){
                        //check if success
                        if(res.data.is_success){
                            //success delete
                            this.$toast.open({
                                message: 'Successfully delete '+$name,
                                type: 'success',
                                position: config.toast_position
                            });
                            //update local state
                            this.datas = this.datas.filter(data => data.id!==$id);
                            this.total_data = this.datas.length;
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
            }
        },
        //show data detail
        showEdit($id){
            this.edit_id = $id;
            this.show_edit = true;
            this.show_list = false;
        },
        //show data detail
        showDetail($id){
            this.detail_id = $id;
        },
        backToList(isRefreshList){
            this.detail_id = 0;
            this.show_add = false;
            this.show_edit = false;
            this.show_list = true;

            if(isRefreshList){  
                this.getDatas(false);
            }
        },

        //show add component
        showAddComponent(){
            this.show_list = false;
            this.show_add = true;
        },

        //start searching filter by keyword
        search(){
            this.getDatas(false);
        },

        //on gallery filter change
        onFilterByGalleryChange(event){
            if(config.is_debug){
                console.log(event.target.value);
            }

            //set take
            this.gallery_id = event.target.value;

            //reset filter & load from first page
            this.resetFilter();
            this.getDatas(false);
        },

        //on data per pagination change select event
        onShownDataPerPaginationChange(event){
            if(config.is_debug){
                console.log(event.target.value);
            }

            //set take
            this.take = event.target.value;

            //reset filter & load from first page
            this.resetFilter();
            this.getDatas(false);
        },

        //reset filter
        resetFilter(){
            this.keyword = '';
            this.page = 1;
            this.total_user = 0;
        },

        //set pagination total data to be shown
        setAvailableShownData(){
            this.available_shown_data = config.available_shown_data;
            
            //set default
            if(this.available_shown_data.length > 0){
                this.take = this.available_shown_data[0];
            }
        },

        //load more data
        loadMore(){
            //increment page
            this.page++;
            
            this.getDatas(true);
        },

        //check if should show load more button or not
        checkShowLoadMore(page, total_page){
            if(page >= total_page){
                this.is_show_load_more = false;
            }else{
                this.is_show_load_more = true;
            }
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

        //get all galleries
        getGalleries(){
            //loading
            this.is_gallery_loading = true;

            //call API
            axios.get('/api/admin/galleries',{
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
                        let galleries = [{ id : null, title: 'Show All' }, ...res.data.results]
                        this.galleries = galleries;
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

        //get list of data from API
        getDatas(is_load_more){
            if(config.is_debug){
                console.log("GET, take: "+this.take+", keyword: "+this.keyword+", page: "+this.page);
            }

            //hide error if exist
            this.showError(false);

            //show loading
            if(!is_load_more){
                this.is_loading = true;
            }else{
                this.is_load_more_loading = true;
            }

            //get users
            axios.get('/api/admin/photos',{
                params:{
                    gallery_id: this.gallery_id,
                    take: this.take,
                    keyword: this.keyword,
                    page: this.page,
                    order_by: this.order_by,
                    sort: this.desc ? 'desc' : 'asc'
                },
                headers: userHelper.authenticationBearer().headers
            })
            .then(res => {
                //check response
                if(res.status===200){
                    if(config.is_debug){
                        console.log(res);
                    }

                    //check if res is valid
                    if(typeof res.data.status!=='undefined'){
                        //there is an error, show error
                        this.showError(true, res.data.status, 'fa fa-info-circle');
                    }else{
                        //all is ok
                        if(!is_load_more){
                            this.datas = res.data.results;
                            this.total_data = res.data.total_results;
                        }else{
                            //load more
                            this.datas = [...this.datas, ...res.data.results]; 
                        }

                        //check show load more
                        this.checkShowLoadMore(res.data.page, res.data.total_page);
                    }
                }
                else{
                    //error response
                    this.showError(true, 'error code: '+res.status+' '+res.statusText, 'fa fa-info-circle');
                }

                //hide loading
                if(!is_load_more){
                    this.is_loading = false;
                }else{
                    this.is_load_more_loading = false;
                }
            })
            .catch(err => {
                //error
                //hide loading
                if(!is_load_more){
                    this.is_loading = false;
                }else{
                    this.is_load_more_loading = false;
                }
            });
        }
    }
}
</script>
