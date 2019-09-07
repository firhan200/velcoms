<template>
    <div>
        <img v-if="this.photo!==null && this.photo!==''" :src="'/images/products/'+this.photo" :class="'img-fluid product-img img-thumbnail '+this.size"/>
        <span v-if="this.photo===null || this.photo===''">-</span>
    </div>
</template>

<script>
export default {
    props: [
        'photo',
        'size',
    ]
}
</script>
