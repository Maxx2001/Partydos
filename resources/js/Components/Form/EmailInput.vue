<script setup>
import {computed} from "vue";

const props = defineProps({
    id: {
        type: String,
        required: true
    },
    label: {
        type: String,
        default: 'Your Input'
    },
    modelValue: {
        type: String,
        required: true
    },
    placeholder: {
        type: String,
        default: 'Enter value here'
    },
    errorMessage: {
        type: String,
        default: ''
    }
});

const emit = defineEmits(['update:modelValue']);

const hasError = computed(() => props.errorMessage.length > 0);
</script>

<template>
    <div>
        <label :for="id" class="block text-sm font-bold text-gray-700 mb-1">
            {{ label }}
        </label>
        <input
            :id="id"
            type="email"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            :placeholder="placeholder"
            required
            class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-4 focus:ring-blue-400 focus:border-blue-400 placeholder-gray-400 text-gray-700"
            :class="{ 'border-red-500': hasError }"
        />
        <p v-if="hasError" class="text-sm text-red-500 mt-1">
            {{ errorMessage }}
        </p>
    </div>
</template>
