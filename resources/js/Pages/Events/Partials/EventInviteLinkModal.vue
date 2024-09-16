<script setup>
import { defineProps, defineEmits } from 'vue';
import BaseButton from "@/Components/Base/BaseButton.vue";
import BaseCancelButton from "@/Components/Base/BaseCancelButton.vue";
import InviteLink from "@/Pages/Events/Partials/InviteLink.vue";


// Props for title and visibility
const props = defineProps({
    title: {
        type: String,
        required: false,
    },
    isVisible: {
        type: Boolean,
        default: false,
    },
    event: {
        type: Object,
        required: true
    }
});

// Emit events for closing and confirming
const emit = defineEmits(['close', 'confirm']);

// Methods to handle modal actions
const closeModal = () => emit('close');
const confirmAction = () => emit('confirm');
</script>

<template>
    <transition
        enter-active-class="transition-opacity duration-300"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-opacity duration-300"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="isVisible"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            @click="closeModal"
        >
            <div
                class="bg-white rounded-lg shadow-lg p-6 relative"
                @click.stop
            >
                <!-- Modal Header -->
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-700">{{ title }}</h3>
                    <button @click="closeModal" class="hover:text-gray-900 text-4xl text-red-500">
                        &times;
                    </button>
                </div>

                <!-- Modal Content -->
                <div class="text-gray-700">
                    <InviteLink :event="event"/>
                </div>

                <!-- Modal Actions -->
                <div class="flex justify-end mt-6 space-x-4">
                    <BaseButton @click="confirmAction" label="Close" />
                </div>
            </div>
        </div>
    </transition>
</template>
