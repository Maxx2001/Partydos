<script setup>
import {ref} from "vue";
import axios from 'axios';

const props = defineProps({
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
        type: Object,
        default: {},
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
const emits = defineEmits(['update:modelValue']);
const autoCompletions = ref([]);
const activeIndex = ref(-1);

const cancelDebounce = ref(false);

const handleInput = (event) => {
    emits('update:modelValue', event);
}

const debounceTimeout = ref(null);
const debouncedGetAutoCompletions = () => {
    if (debounceTimeout.value) {
        clearTimeout(debounceTimeout.value);
    }

    debounceTimeout.value = setTimeout(() => {
        if (props.modelValue.length < 2) {
            return;
        }

        getAutoCompletions(props.modelValue.address);
    }, 300);
};

const getAutoCompletions = async (searchString) => {
    if (!searchString) {
        autoCompletions.value = [];
        return;
    }
    try {
        await axios.post(route('address.autocomplete'), {
            input: searchString,
        }).then((response) => {
            if (cancelDebounce.value) {
                cancelDebounce.value = false;
                return;
            }

            autoCompletions.value = response.data.addresses;
        });
    } catch (error) {
        autoCompletions.value = [];
        console.error(error.response ? error.response.data : error.message);
    }
};

const setAddress = (address) => {
    emits('update:modelValue', address);
    activeIndex.value = -1;
    autoCompletions.value = [];
};

const handleKeyNavigation = (event) => {
    switch (event.key) {
        case 'ArrowDown':
            activeIndex.value = (activeIndex.value + 1) % autoCompletions.value.length;
            break;
        case 'ArrowUp':
            activeIndex.value = (activeIndex.value - 1 + autoCompletions.value.length) % autoCompletions.value.length;
            break;
        case 'Enter':
            if (activeIndex.value >= 0) {
                setAddress(autoCompletions.value[activeIndex.value]);
            }
            break;
        default:
            debouncedGetAutoCompletions();
    }
};

const clearAutoCompletions = () => {
    cancelDebounce.value = true;
    autoCompletions.value = [];
};
</script>

<template>
    <div class="flex flex-col w-full ">
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
            <input
                :type="inputType"
                :id="name"
                :required="required"
                :value="modelValue?.address"
                :placeholder="placeholder"
                @input="handleInput($event.target.value)"
                @keyup="handleKeyNavigation"
                @blur="clearAutoCompletions"
                class="rounded-md border-black p-2 focus:outline-none w-full"
                :class="[error ? 'border-red-500 ring-red-300 focus:ring-red-500 focus:border-red-500' : '']"
            >
        </div>
        <ul class="bg-white border border-gray-300 rounded-md shadow-lg" v-if="autoCompletions.length > 0">
            <li
                v-for="(autoCompletion, index) in autoCompletions"
                :key="index"
                :class="[
                    'px-4 py-2 cursor-pointer text-gray-700',
                    index === activeIndex ? 'bg-gray-100' : 'hover:bg-gray-100'
                    ]"
                @mousedown="setAddress(autoCompletion)"
            >
                {{ autoCompletion.address }}
            </li>
        </ul>
        <div class="h-6" v-if="error">
            <span class="text-red-500 italic">
                {{ error }}
            </span>
        </div>
    </div>
</template>
