<template>
    <span>
        <i v-if="this.desc" class="fa fa-chevron-up"></i>
        <i v-if="!this.desc" class="fa fa-chevron-down"></i>
    </span>
</template>

<script>
export default {
    props: [
        'desc'
    ]
}
</script>

