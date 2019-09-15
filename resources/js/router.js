import Vue from 'vue';
import VueRouter from 'vue-router';
import store from './vuex/store';
import config from './config';
import userTypes from './constants/userTypes';

//component
import Login from './components/Login';
import ForgotPassword from './components/ForgotPassword';
import ResetPassword from './components/ResetPassword';

//Admin
import AdminLayout from './components/admin/Layout';
import AdminDashboard from './components/admin/Dashboard';
import AdminUpdateProfile from './components/admin/profiles/Update';
import AdminChangePassword from './components/admin/profiles/ChangePassword';

/* CRUD */
import AdminArticleCategories from './components/admin/article_categories/List';
import AdminArticles from './components/admin/articles/List';
import AdminSliders from './components/admin/sliders/List';
import AdminGalleries from './components/admin/galleries/List';
import AdminPhotos from './components/admin/photos/List';
import AdminSocialLinks from './components/admin/social_links/List';
import AdminContacts from './components/admin/contacts/List';
import AdminNotifications from './components/admin/notifications/List';

//use router
Vue.use(VueRouter);

//define routes here
const router = new VueRouter({
    routes : [
        {
            path: '/',
            component : Login
        },
        {
            path: '/forgot-password',
            component : ForgotPassword
        },
        {
            path: '/reset-password',
            component : ResetPassword
        },
        {
            path: '/cms',
            component : AdminLayout,
            children : [
                {
                    path: '',
                    component : AdminDashboard
                },
                {
                    path: 'update-profile',
                    component : AdminUpdateProfile
                },
                {
                    path: 'update-password',
                    component : AdminChangePassword
                },

                /* CRUD */
                {
                    path: 'article_categories',
                    component : AdminArticleCategories
                },
                {
                    path: 'articles',
                    component : AdminArticles
                },
                {
                    path: 'sliders',
                    component : AdminSliders
                },
                {
                    path: 'galleries',
                    component : AdminGalleries
                },
                {
                    path: 'photos',
                    component : AdminPhotos
                },
                {
                    path: 'social_links',
                    component : AdminSocialLinks
                },
                {
                    path: 'contacts',
                    component : AdminContacts
                },
                {
                    path: 'notifications',
                    component : AdminNotifications
                },
            ]
        }
    ]
});

//guest route
//available to access when not login
let guest_routes = [
    '/',
    '/forgot-password',
    '/reset-password',
];

//middleware
router.beforeEach((to, from, next) => {
    store.dispatch('fetchAccessToken')
    let token = store.getters.getToken;
    let continueRoute = true;

    if(config.is_debug){
        console.log("router middleware, to: "+to.fullPath+", from: "+from.fullPath+", next: "+next.fullPath);
    }
    
    //prevent logged in user to page login(/)
    if (guest_routes.includes(to.path)) {
        //user want to go to login, path = /
        //check if token already set
        if (token !== null) {
            if(config.is_debug){
                console.log("token already exist, cannot go here");
            }

            //get user detail(type) to get redirect to
            store.dispatch('getUser', {
                callback : res => {
                    if(res.isValid){
                        let redirectTo = '/cms';
                        next(redirectTo);
                    }
                }
            });

            continueRoute = false;
        }
    }else{
        if (token === null || token==='') {
            if(config.is_debug){
                console.log("token didnt exist, go to login");
            }
            next('/');
            continueRoute = false;
        }
    }

    next(continueRoute);
});

export default router;