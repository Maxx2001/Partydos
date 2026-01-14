<script setup>
const props = defineProps({
    level: {
        type: String,
        default: 'public',
    },
});

const label = {
    public: 'Public',
    friends: 'Friends',
    private: 'Private',
}[props.level] || 'Public';

const color = {
    public: 'bg-green-100 text-green-700',
    friends: 'bg-yellow-100 text-yellow-700',
    private: 'bg-red-100 text-red-700',
}[props.level] || 'bg-green-100 text-green-700';
</script>

<template>
    <span :class="['inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold', color]">
        {{ label }}
    </span>
</template>
