<script setup>
import { reactive } from 'vue'
import { router } from '@inertiajs/vue3';
import TextInput from "@/Components/Inputs/TextInput.vue";
import BaseButton from "@/Components/Base/BaseButton.vue";

const form = reactive({
    name: null,
    email: null,
})

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
        route(
            'events.register-guest',
            props.event.uniqueIdentifier
        ), form ,
        {
            onSuccess: () => {
                resetForm();
                emit('registerSuccess');
            },
        }
    );
}
</script>

<template>
    <div class="w-full lg:w-1/2 border bg-white mt-2">
        <form
            @submit.prevent="submitRegisterForm"
            class="flex flex-col justify-center w-full items-center bg-white py-8"
        >
            <span class="text-xl pb-2">
               Sign up!
            </span>
            <TextInput
                input-title="Name"
                name="name"
                :model-value="form.name"
                :required="true"
                @update:modelValue="val => form.name = val"
                class="w-10/12"
            />
            <TextInput
                input-title="Email"
                name="email"
                :model-value="form.email"
                input-type="email"
                :required="true"
                @update:modelValue="val => form.email = val"
                class="w-10/12"
            />
            <div class="flex justify-end mt-4">
                <BaseButton
                    label="Sign up for event"
                    type="submit"
                />
            </div>
        </form>
    </div>
</template>
