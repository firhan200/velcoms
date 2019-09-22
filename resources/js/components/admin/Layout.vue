<template>
    <div>
        <div class="page-wrapper chiller-theme toggled">
            <button type="button" id="show-sidebar" class="btn btn-sm btn-default">
                <i class="fas fa-bars"></i>
            </button>
            <nav id="sidebar" class="sidebar-wrapper">
                <div class="sidebar-content">
                    <div class="sidebar-brand">
                        <a>Velcoms &copy; CMS 2019</a>
                        <div id="close-sidebar">
                            <i class="fas fa-chevron-left"></i>
                        </div>
                    </div>
                    <div class="sidebar-header">
                        <div class="user-pic">
                            <img class="img-responsive img-rounded" src="https://raw.githubusercontent.com/azouaoui-med/pro-sidebar-template/gh-pages/src/img/user.jpg"
                            alt="User picture">
                        </div>
                        <div class="user-info">
                            <span class="user-name">
                                <strong>{{ this.$store.getters.getUser.name }}</strong>
                            </span>
                            <span class="user-status">
                                Online
                            </span>
                        </div>
                    </div>
                    <!-- sidebar-header  -->
                    <div class="sidebar-menu">
                        <ul>
                            <li class="header-menu">
                                <span>General</span>
                            </li>
                            <li>
                                <router-link to='/cms'>
                                    <i class="fa fa-tachometer-alt"></i>
                                    <span>Dashboard</span>
                                </router-link>
                            </li>
                            <li>
                                <router-link to='/cms/pages'>
                                    <i class="fa fa-file"></i>
                                    <span>Pages</span>
                                </router-link>
                            </li>
                            <li class="sidebar-dropdown">
                                <a>
                                    <i class="fa fa-book"></i>
                                    <span>Article</span>
                                </a>
                                <div class="sidebar-submenu">
                                    <ul>
                                        <li>
                                            <router-link to='/cms/articles'>List</router-link>
                                        </li>
                                        <li>
                                            <router-link to='/cms/article_categories'>Categories</router-link>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <router-link to='/cms/sliders'>
                                    <i class="fa fa-image"></i>
                                    <span>Slider</span>
                                </router-link>
                            </li>
                            <li class="sidebar-dropdown">
                                <a>
                                    <i class="fa fa-images"></i>
                                    <span>Gallery</span>
                                </a>
                                <div class="sidebar-submenu">
                                    <ul>
                                        <li>
                                            <router-link to='/cms/galleries'>Albums</router-link>
                                        </li>
                                        <li>
                                            <router-link to='/cms/photos'>Photos</router-link>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <router-link to='/cms/social_links'>
                                    <i class="fa fa-globe"></i>
                                    <span>Social Link</span>
                                </router-link>
                            </li>
                            <li>
                                <router-link to='/cms/contacts'>
                                    <i class="fa fa-envelope"></i>
                                    <span>Contact</span>
                                </router-link>
                            </li>                      
                        </ul>
                    </div>
                    <!-- sidebar-menu  -->
                </div>
                <!-- sidebar-content  -->    

                <!-- sidebar-footer  -->    
                <div class="sidebar-footer">
                    <router-link to="/cms/notifications">
                        <i class="fa fa-bell"></i>
                        <span v-if="this.$store.getters.getTotalNotifications > 0" class="badge badge-pill badge-warning notification">{{ this.$store.getters.getTotalNotifications }}</span>
                    </router-link>
                    <!-- <a>
                        <i class="fa fa-envelope"></i>
                        <span class="badge badge-pill badge-success notification">7</span>
                    </a> -->
                    <router-link to="/cms/update-profile">
                        <i class="fa fa-cog"></i>
                        <!-- <span class="badge-sonar"></span> -->
                    </router-link>
                    <a data-toggle="modal" data-target="#logoutModal">
                        <i class="fa fa-power-off"></i>
                    </a>
                </div>   
            </nav>
            <!-- sidebar-wrapper  -->
            <main class="page-content">
                <div class="container">
                    <transition name="slide-fade">
                        <router-view></router-view>
                    </transition>
                </div>
            </main>
            <!-- page-content" -->
        </div>
        <!-- page-wrapper -->
    </div>
</template>

<script>
//libs
import userHelper from './../../helpers/userHelper';

export default {
    data(){
        return {
            total_notification : 0,
            total_notification_error : false,
            total_notification_loading : false,
        }
    },
    mounted: function(){
        //sidebar
        jQuery(function ($) {
            $(".sidebar-dropdown > a").click(function() {
                $(".sidebar-submenu").slideUp(200);
                if ($(this).parent().hasClass("active")) {
                    $(".sidebar-dropdown").removeClass("active");
                    $(this).parent().removeClass("active");
                } else {
                    $(".sidebar-dropdown").removeClass("active");
                    $(this).next(".sidebar-submenu").slideDown(200);
                    $(this).parent().addClass("active");
                }
            });

            $("#close-sidebar").click(function() {
                $(".page-wrapper").removeClass("toggled");
            });
            $("#show-sidebar").click(function() {
                $(".page-wrapper").addClass("toggled");
            });
        });
    },

    methods : {
        getTotalUnreadNotifications(){
            //set loading
            this.total_notification_loading = true;

            //get total notification
            this.$store.dispatch('getTotalNotiications')
            axios.get('/api/admin/notifications/total',{
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
                        this.total_notification_error = res.data.status;
                    }else{
                        //all is ok
                        this.total_notification = res.data.total;
                    }
                }
                else{
                    //error response
                    this.total_notification_error = res.status+": "+res.statusText;
                }

                //hide loading
                this.total_notification_loading = false;
            })
            .catch(err => {
                //error
                //hide loading
                this.total_notification_loading = false
            });
        }
    }
}
</script>
