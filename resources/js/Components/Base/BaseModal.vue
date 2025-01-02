<script setup>
import { defineProps, defineEmits } from 'vue';
import BaseButton from "@/Components/Base/BaseButton.vue";
import BaseCancelButton from "@/Components/Base/BaseCancelButton.vue";

const props = defineProps({
    title: {
        type: String,
        required: false,
    },
    isVisible: {
        type: Boolean,
        default: false,
    },
    showSubmitButton: {
        type: Boolean,
        default: true,
    },
    showCancelButton: {
        type: Boolean,
        default: true,
    },
    submitButtonLabel: {
        type: String,
        default: 'Confirm',
    },
    cancelButtonLabel: {
        type: String,
        default: 'Cancel',
    },
});

const emit = defineEmits(['close', 'confirm']);

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
            class="fixed inset-0 bg-opacity-50 bg-indigo-900 flex items-center justify-center z-50 shadow-lg"
            @click="closeModal"
        >
            <div
                class="p-1 rounded-xl w-full max-w-3xl m-3"
                @click.stop
            >
                <div class="bg-white rounded-xl shadow-lg w-full h-full py-8 px-4 lg:px-12">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-2xl font-bold text-indigo-700">{{ title }}</h3>
                        <button @click="closeModal" class="hover:text-gray-900 text-4xl text-cancel-red">
                            &times;
                        </button>
                    </div>

                    <div class="text-gray-700 py-2 lg:py-4">
                        <slot></slot>
                    </div>

                    <div class="flex justify-end mt-4 space-x-4">
                        <BaseCancelButton @click="closeModal" :label="cancelButtonLabel" v-if="showCancelButton"/>
                        <BaseButton @click="confirmAction" :label="submitButtonLabel" v-if="showSubmitButton"/>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>
