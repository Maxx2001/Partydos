<script setup>
import { defineProps, defineEmits } from 'vue';

// Define props for the component
const props = defineProps({
    message: {
        type: String,
        required: true
    }
});

// Define emits for the component
const emit = defineEmits(['closeMessage']);

// Method to emit the closeMessage event
const closeMessage = () => {
    emit('closeMessage');
};
</script>

<template>
    <transition
        name="slide-fade"
        enter-active-class="transition ease-out duration-300 transform"
        enter-from-class="opacity-0 translate-y-4"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition ease-in duration-300 transform"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 translate-y-4"
    >
        <div
            class="w-full bg-green-100 text-green-800 text-center py-1.5 px-6 rounded border border-green-200 relative"
        >
            <!-- Success Message -->
            <p>{{ message }}</p>

            <!-- Close Button -->
            <button
                @click="closeMessage"
                class="absolute top-0 right-2 text-gray-600 hover:text-red-800 text-2xl"
                aria-label="Close"
            >
                &times;
            </button>
        </div>
    </transition>
</template>
