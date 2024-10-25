<script setup>
import {reactive, ref, defineExpose, defineEmits} from 'vue';
import {router} from '@inertiajs/vue3';
import TextInput from "@/Components/Inputs/TextInput.vue";

const form = reactive({
    name: '',
    email: '',
});

const props = defineProps({
    event: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['registerSuccess']);

// Error states
const errors = reactive({
    name: '',
    email: '',
});

// Reset form fields
const resetForm = () => {
    form.name = '';
    form.email = '';
    clearErrors();
};

// Clear error messages
const clearErrors = () => {
    errors.name = '';
    errors.email = '';
};

// Validate form fields
const validateForm = () => {
    clearErrors();

    let isValid = true;

    if (!form.name) {
        errors.name = 'Name is required.';
        isValid = false;
    }

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!form.email) {
        errors.email = 'Email is required.';
        isValid = false;
    } else if (!emailPattern.test(form.email)) {
        errors.email = 'Please enter a valid email address.';
        isValid = false;
    }

    return isValid;
};

// Submit form
const submitRegisterForm = () => {
    if (validateForm()) {
        router.post(
            route('events.register-guest', props.event.uniqueIdentifier),
            form,
            {
                onSuccess: () => {
                    resetForm();
                    emit('registerSuccess');
                },
            }
        );
    }
};

// Expose the method to the parent component
defineExpose({submitRegisterForm});
</script>

<template>
    <div class="w-full bg-white">
        <form class="flex flex-col justify-center w-full items-center bg-white" @submit.prevent="submitRegisterForm">
            <TextInput
                placeholder="Name"
                name="name"
                :model-value="form.name"
                :required="true"
                :error="errors.name"
                @update:modelValue="val => form.name = val"
                class="w-full"
            />
            <TextInput
                placeholder="Email"
                name="email"
                input-type="email"
                :model-value="form.email"
                :required="true"
                :error="errors.email"
                @update:modelValue="val => form.email = val"
                class="w-full"
            />
        </form>
    </div>
</template>
