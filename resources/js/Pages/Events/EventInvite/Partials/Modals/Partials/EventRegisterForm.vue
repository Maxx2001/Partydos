<script setup>
import {reactive, ref, defineExpose, defineEmits} from 'vue';
import {router} from '@inertiajs/vue3';
import TextInput from "@/Components/Inputs/TextInput.vue";
import { EnvelopeIcon } from '@heroicons/vue/20/solid';

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

const errors = reactive({
    name: '',
    email: '',
});

const resetForm = () => {
    form.name = '';
    form.email = '';
    clearErrors();
};

const clearErrors = () => {
    errors.name = '';
    errors.email = '';
};

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

const submitRegisterForm = () => {
    console.log('submitting form');
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

defineExpose({submitRegisterForm});
</script>

<template>
    <div class="w-full bg-white">
        <form class="flex flex-col justify-center w-full items-center bg-white gap-5" @submit.prevent="submitRegisterForm">
            <TextInput
                placeholder="Enter your name"
                name="name"
                :model-value="form.name"
                :required="true"
                :error="errors.name"
                @update:modelValue="val => form.name = val"
                class="w-full"
            />
            <TextInput
                placeholder="Enter your Email"
                name="email"
                input-type="email"
                :icon="EnvelopeIcon"
                :model-value="form.email"
                :required="true"
                :error="errors.email"
                @update:modelValue="val => form.email = val"
                class="w-full"
            />
        </form>
    </div>
</template>
