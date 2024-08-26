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
    inputType: {
        type: String,
        default: 'text',
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
        <input
            :type="inputType"
            :id="name"
            :required="required"
            :value="modelValue"
            :placeholder="placeholder"
            @input="$emit('update:modelValue', $event.target.value)"
            class="rounded-md border border-gray-300 p-2 ring-1 focus:ring-1 focus:outline-none"
            :class="error ? 'border-red-500 ring-red-300 focus:ring-red-500 focus:border-red-500' : 'ring-gray-300  focus:ring-blue-300'"
        >
        <div class="h-6">
            <span class="text-red-500 italic">
            {{ error }}
        </span>
        </div>
    </div>

</template>
