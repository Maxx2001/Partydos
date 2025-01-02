<script setup>
import { ref, computed } from 'vue';
import EyeOpen from "@/Components/Icons/EyeOpen.vue";
import EyeClosed from "@/Components/Icons/EyeClosed.vue";

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
        default: '********'
    },
    type: {
        type: String,
        default: 'password',
        validator: (value) => ['text', 'email', 'password'].includes(value)
    },
    errorMessage: {
        type: String,
        default: ''
    }
});
const emit = defineEmits(['update:modelValue']);

const hasError = computed(() => props.errorMessage.length > 0);

const isPasswordVisible = ref(false);
const togglePasswordVisibility = () => {
    isPasswordVisible.value = !isPasswordVisible.value;
};
</script>

<template>
    <div>
        <label :for="id" class="block text-sm font-bold text-gray-700 mb-1">
            {{ label }}
        </label>
        <div class="relative">
            <input
                :id="id"
                :type="isPasswordVisible ? 'text' : type"
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
                :placeholder="placeholder"
                required
                class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-4 focus:ring-blue-400 focus:border-blue-400 placeholder-gray-400 text-gray-700"
                :class="{ 'border-red-500': hasError }"
            />
            <button
                type="button"
                class="absolute inset-y-0 right-3 flex items-center text-gray-500 focus:outline-none"
                @click="togglePasswordVisibility"
                aria-label="Toggle password visibility"
            >
                <EyeOpen v-if="isPasswordVisible"/>
                <EyeClosed v-else/>
            </button>
        </div>
        <p v-if="hasError" class="text-sm text-red-500 mt-1">
            {{ errorMessage }}
        </p>
    </div>
</template>
