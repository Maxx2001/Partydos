<script setup>
const props = defineProps({
    name: { type: String, required: true },
    bgColor: { type: String, required: true }
});
</script>

<template>
    <li class="flex items-center flex-col">
        <div :class="['h-16 w-16 rounded-full', bgColor]"></div>
        <span class="w-full text-center pt-1 line-clamp-2">{{ name }}</span>
    </li>
</template>
