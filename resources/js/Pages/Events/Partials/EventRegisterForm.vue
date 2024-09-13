<script setup>
import { reactive, defineExpose } from 'vue'
import { router } from '@inertiajs/vue3';
import TextInput from "@/Components/Inputs/TextInput.vue";

const form = reactive({
    name: null,
    email: null,
});

const props = defineProps({
    event: {
        type: Object,
        required: true
    },
});

const resetForm = () => {
    form.name = null;
    form.email = null;
};

const emit = defineEmits(['registerSuccess']);

const submitRegisterForm = () => {
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
};

// Expose the method to the parent component
defineExpose({submitRegisterForm});
</script>

<template>
    <div class="w-full bg-white">
        <form class="flex flex-col justify-center w-full items-center bg-white">
            <TextInput
                placeholder="Name"
                name="name"
                :model-value="form.name"
                :required="true"
                @update:modelValue="val => form.name = val"
                class="w-full"
            />
            <TextInput
                placeholder="Email"
                name="email"
                input-type="email"
                :model-value="form.email"
                :required="true"
                @update:modelValue="val => form.email = val"
                class="w-full"
            />
        </form>
    </div>
</template>
