<template>
    <div id="app">
        <router-view></router-view>

        <!-- Expired Modal -->
        <ExpiredModal />

        <!-- Logout Modal -->
        <LogoutModal />
    </div>
</template>

<script>
//componenets
import ExpiredModal from './ExpiredModal.vue';
import LogoutModal from './LogoutModal.vue';

export default {
    components: {
        ExpiredModal,
        LogoutModal,
    },
    mounted(){
        this.$store.dispatch('getUser', {
            callback : res => {  
                //check response
                if(!res.isValid){
                    //error get user, maybe token expired
                    if(res.data.status==='Token is Expired'){
                        //show expired modal
                        document.getElementById("expiredModalButton").click();
                    }
                }
            }
        });
    }
}
</script>