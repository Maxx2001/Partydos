<script setup>
defineProps({
    required: {
        type: Boolean,
        default: false,
    },
    name: {
        type: String,
        required: true,
    },
    inputTitle : {
        type: String,
        required: true,
    },
    modelValue: {
        type: String,
        default: '',
    },
    placeholder: {
        type: String,
        default: '',
    },
    error: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <div class="flex flex-col">
        <label :for="name" class="text-gray-500 flex items-center justify-start">
            <span>
                {{ inputTitle }}
            </span>
            <span
                v-if="required"
                class="text-3xl text-red-500 flex items-center h-2 pt-2 pl-1"
            >
                 *
             </span>
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                </svg>
            </div>
            <input
                type="datetime-local"
                :id="name"
                :required="required"
                :value="modelValue"
                :placeholder="placeholder"
                @input="$emit('update:modelValue', $event.target.value)"
                class="bg-gray-50 border border-gray-300 text-black text-sm rounded-lg block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                :class="error ? 'border-red-500 ring-red-300 focus:ring-red-500 focus:border-red-500 text-red-500' : 'ring-gray-300  focus:ring-blue-300'"
            >
        </div>
        <span class="text-red-500 italic">
            {{ error }}
        </span>
    </div>
</template>
