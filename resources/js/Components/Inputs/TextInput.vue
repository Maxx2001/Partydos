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
        required: false,
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
    icon: {
        type: Object,
        required: false,
    },
});
</script>

<template>
    <div class="flex flex-col">
        <label :for="name" class="text-gray-500 flex items-center justify-start">
            <span v-if="inputTitle">
                {{ inputTitle }}
            </span>
            <span
                v-if="required && inputTitle"
                class="text-3xl text-red-500 flex items-center h-2 pt-2 pl-1"
            >
                 *
             </span>
        </label>
        <div class="relative flex items-center">
            <span v-if="icon" class="absolute left-3 text-gray-500">
                <component :is="icon" class="w-5 h-5" />
            </span>
            <input
                :type="inputType"
                :id="name"
                :required="required"
                :value="modelValue"
                :placeholder="placeholder"
                @input="$emit('update:modelValue', $event.target.value)"
                class="rounded-md border-black p-2 focus:outline-none w-full"
                :class="[
                    error ? 'border-red-500 ring-red-300 focus:ring-red-500 focus:border-red-500' : '',
                    icon ? 'pl-10' : ''
                    ]"
            >
        </div>
        <div class="h-6" v-if="error">
            <span class="text-red-500 italic">
            {{ error }}
        </span>
        </div>
    </div>

</template>
