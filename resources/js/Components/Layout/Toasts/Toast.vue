<script setup lang="ts">
import {computed} from "vue";
import Icon from "@/Components/Icons/Icon.vue";
import {cva} from "class-variance-authority";
// import {Toast} from "@/Stores/ToastMessages";
import { Toast } from "../../../Stores/ToastMessages";

const props = defineProps<Toast>();

const icon = computed(() => {
    switch (props.type) {
        case 'info':
            return 'info';
        case 'warning':
            return 'error';
        case 'error':
            return 'cancel';
        case 'success':
        default:
            return 'check_circle';
    }
});

const iconClass = computed(() => {
    return cva(
        "w-8 h-8 min-w-[30px] rounded-full flex justify-center items-center",
        {
            variants: {
                type: {
                    default: 'bg-gray-500 text-white',
                    info: 'bg-blue-500 text-white',
                    warning: 'bg-orange-500 text-white',
                    error: 'bg-red-500 text-white',
                    success: 'bg-green-500 text-white',
                },
            }
        })({
        type: props.type,
    });
});

</script>
<template>
    <transition
        :appear="true"
        enter-active-class="transform ease-out duration-300 transition"
        enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
        leave-active-class="transition ease-in duration-100"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0 sm:translate-y-0 sm:translate-x-2"
    >
        <div
            v-if="isOpen"
            class="flex items-center w-full max-w-xs p-4 bg-white shadow-lg rounded-lg border border-gray-200"
            role="alert"
            style="border-left: 6px solid var(--toast-border-color);"
        >
            <div class="ml-3">
                <p class="text-base font-semibold text-gray-900">{{ message }}</p>
            </div>
            <button
                type="button"
                @click="props.close()"
                class="ml-auto bg-transparent text-gray-500 hover:text-gray-700 p-1.5 rounded-lg focus:outline-none"
                aria-label="Close"
            >
                <span class="sr-only">Close</span>
                <svg
                    class="w-4 h-4"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="2"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>
        </div>
    </transition>
</template>

<style scoped>
:root {
    --toast-border-color: #2196f3; /* Default to info */
    --toast-icon-bg-color: #2196f3; /* Default to info */
}

.toast-success {
    --toast-border-color: #4caf50;
    --toast-icon-bg-color: #4caf50;
}

.toast-error {
    --toast-border-color: #f44336;
    --toast-icon-bg-color: #f44336;
}

.toast-warning {
    --toast-border-color: #ff9800;
    --toast-icon-bg-color: #ff9800;
}
</style>
