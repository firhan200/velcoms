<template>
    <div>
        <a id="expiredModalButton" class="d-none" data-toggle="modal" data-target="#expiredModal">
        </a>
        <!-- The Modal -->
        <div class="modal fade" id="expiredModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Your session has expired.</h4>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        Try signing in again or contact administrator to resolve this issue.
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button v-on:click="signingIn" type="button" class="btn btn-light">Signing In</button>
                        <button id="closeExpiredModal" type="button" class="btn btn-danger d-none" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    methods : {
        signingIn(){
            //remove token, by dispathing logout
            this.$store.dispatch('logout');

            //hide modal
            document.getElementById("closeExpiredModal").click();

            //redirect to login
            this.$router.push('/');
        }
    }
}
</script>