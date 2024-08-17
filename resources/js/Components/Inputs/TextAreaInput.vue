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
                 class="text-3xl text-red-500 flex items-center h-2 pt-2"
             >
                 *
             </span>
        </label>
        <textarea
            type="text"
            :id="name"
            :required="required"
            :value="modelValue"
            :placeholder="placeholder"
            @input="$emit('update:modelValue', $event.target.value)"
            class="rounded-md border border-gray-300 p-2 ring-1 ring-gray-300 focus:ring-1 focus:ring-blue-300 focus:outline-none"
            rows="4"
        />
    </div>
</template>
