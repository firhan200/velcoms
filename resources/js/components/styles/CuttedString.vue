<template>
    <span>{{ words.length > maxLength ? words.slice(0, maxLength)+"..." : words }}</span>
</template>
<script>
export default {
    props: [
        'words',
        'maxLength',
    ]
}
</script>
