<script setup>
import { ref } from 'vue';

const props = defineProps({
    modelValue: {
        type: Array,
        default: () => [],
    },
    label: {
        type: String,
        default: '',
    },
    placeholder: {
        type: String,
        default: 'Add a tag and press enter',
    },
    helper: {
        type: String,
        default: '',
    },
});

const emit = defineEmits(['update:modelValue']);

const inputValue = ref('');

const addTag = () => {
    const value = inputValue.value.trim();
    if (!value) return;
    const next = [...props.modelValue];
    if (!next.includes(value)) {
        next.push(value);
        emit('update:modelValue', next);
    }
    inputValue.value = '';
};

const removeTag = (index) => {
    const next = [...props.modelValue];
    next.splice(index, 1);
    emit('update:modelValue', next);
};
</script>

<template>
    <div>
        <label v-if="label" class="block text-sm font-semibold text-gray-700 mb-2">{{ label }}</label>
        <div class="flex flex-wrap gap-2">
            <button
                v-for="(tag, index) in modelValue"
                :key="`${tag}-${index}`"
                type="button"
                class="inline-flex items-center gap-1 rounded-full bg-yellow-100 text-yellow-800 px-3 py-1 text-xs font-semibold"
                @click="removeTag(index)"
            >
                {{ tag }}
                <span aria-hidden="true" class="text-yellow-500">×</span>
            </button>
        </div>
        <input
            v-model="inputValue"
            type="text"
            class="mt-3 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-yellow-500 focus:ring-yellow-500"
            :placeholder="placeholder"
            @keydown.enter.prevent="addTag"
        />
        <p v-if="helper" class="mt-1 text-xs text-gray-500">{{ helper }}</p>
    </div>
</template>
