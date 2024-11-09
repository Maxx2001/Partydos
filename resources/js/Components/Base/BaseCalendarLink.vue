<script setup>
import {defineProps} from 'vue'

const props = defineProps({
    label: {
        type: String,
        required: true,
    },
    url: {
        type: String,
        required: true,
    },
    backgroundColor: {
        type: String,
        required: true, // Expect an exact color class like "bg-blue-500"
    },
    hoverColor: {
        type: String,
        required: true, // Expect an exact hover color class like "hover:bg-blue-700"
    },
    icon: {
        type: Object,
        required: false,
    },
})
</script>

<template>
        <a
            :href="url"
            target="_blank"
            :class="`border p-2 rounded ${backgroundColor} ${hoverColor} flex justify-center items-center text-white text-xl font-bold h-40 w-full`"
        >
            <div class="flex flex-col">
                <Component :is="icon" v-if="icon" class="h-20
                 w-20"/>
                <span class="text-black text-center pb-3 -mt-3">
                    {{ label }}
                </span>
            </div>
        </a>
</template>
