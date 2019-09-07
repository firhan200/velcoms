<template>
    <div class="alert alert-danger">
        <i :class="this.icon"></i>&nbsp;
        {{ this.message }}
        <ul v-if="typeof this.errors!=='undefined'">
           <li v-bind:key="error" v-for="error in this.errors">{{ error }}</li> 
        </ul>
    </div>
</template>

<script>
export default {
    props: [
        'icon',
        'message',
        'errors'
    ]
}
</script>

