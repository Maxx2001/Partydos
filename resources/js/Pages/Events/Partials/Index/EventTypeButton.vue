<script setup>
defineProps({
    activeTab: {
        type: Number,
        required: true
    },
    setActiveTab: {
        type: Function,
        required: true
    },
    title: {
        type: String,
        required: true
    },
    tabNumber: {
        type: Number,
        required: true
    }
});
</script>

<template>
    <div
        :class="{
            'text-blue-500': activeTab === tabNumber,
            'text-gray-600 hover:bg-gray-100 hover:text-blue-500 hover:shadow-lg': activeTab !== tabNumber
        }"
        class="flex-1 py-4 text-center text-lg md:text-xl font-bold cursor-pointer transition-colors duration-200 ease-out bg-white"
        @click="setActiveTab(tabNumber)"
    >
        {{ title }}
    </div>
</template>
