<template>
    <div>
        <img v-if="this.photo!==null && this.photo!==''" :src="this.path+this.photo" :class="'img-fluid img-thumbnail '+this.size"/>
        <span v-if="this.photo===null || this.photo===''">-</span>
    </div>
</template>

<script>
export default {
    props: [
        'photo',
        'path',
        'size',
    ]
}
</script>
