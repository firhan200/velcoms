<template>
    <span>
        <span v-if="this.is_stock_available"><label class="badge badge-success">available</label></span>
        <span v-if="!this.is_stock_available"><label class="badge badge-danger">unavailable</label></span>
    </span>
</template>

<script>
export default {
    props : [
        'is_stock_available'
    ]
}
</script>

