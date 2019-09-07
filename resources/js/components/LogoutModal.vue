<template>
    <!-- The Modal -->
    <div class="modal fade" id="logoutModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Confirmation</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    Are you sure you want to logout?
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button v-on:click="logout" type="button" class="btn btn-default">Logout anyway</button>
                    <button  id="closeLogoutModal" type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    methods : {
        logout(){
            //remove token, by dispathing logout
            this.$store.dispatch('logout');

            //hide modal
            document.getElementById("closeLogoutModal").click();

            //redirect to login
            this.$router.push('/');
        }
    }
}
</script>