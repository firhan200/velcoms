import Vue from 'vue';
import Vuex from 'vuex';

//modules
import userModule from './modules/userModule';

Vue.use(Vuex);

const store = new Vuex.Store({
    modules:{
        user: userModule /* admin user modules */
    }
});

export default store;