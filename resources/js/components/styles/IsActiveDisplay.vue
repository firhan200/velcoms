<template>
    <span>
        <span v-if="this.is_active"><label class="badge badge-success">active</label></span>
        <span v-if="!this.is_active"><label class="badge badge-danger">in-active</label></span>
    </span>
</template>

<script>
export default {
    props : [
        'is_active'
    ]
}
</script>

