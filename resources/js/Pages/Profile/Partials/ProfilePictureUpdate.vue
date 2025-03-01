<script setup>
defineProps({
    imageUrl : {
        type: String,
        required: true
    }
})
</script>
<template>
    <img
        :src="imageUrl"
        alt="Profile Picture"
        class="rounded-full w-24 h-24 mx-auto border"
    />
</template>
