<template>
    <span>{{ (
		'Rp. ' +
		parseFloat(this.price)
		.toFixed(0) // always two decimal digits
		.replace('.', ',') // replace decimal point character with ,
		.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')
	) }}</span>
</template>
<script>
export default {
    props: [
        'price'
    ]
}
</script>
