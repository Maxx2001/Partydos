<script setup>
defineProps({
    label: {
        type: String,
        required: true,
    },
    labelText: {
        type: String,
        required: true,
    },
    modelValue: {
        type: Boolean,
        default: false,
    },
})
</script>

<template>
    <div class="relative flex items-start">
        <div class="flex h-6 items-center">
            <input
                :id="label"
                aria-describedby="comments-description"
                :name="label"
                type="checkbox"
                :checked="modelValue"
                @input="$emit('update:modelValue', $event.target.checked)"
                class="h-4 w-4 rounded border-gray-300 text-blue-500 focus:ring-transparent"
            />
        </div>
        <div class="ml-3 text-sm leading-6">
            <label :for="label" class="font-medium text-gray-900">
                {{label }}
            </label>
            {{ ' ' }}
            <label
                :for="label"
                class="text-gray-500"
            >
                <span
                    class="sr-only">
                    {{ label}}
                </span>
                {{ labelText }}
            </label>
        </div>
    </div>
</template>
