<script setup>
import TextInput from "@/Components/Inputs/TextInput.vue";
import BaseButton from "@/Components/Base/BaseButton.vue";
import { ref, reactive } from "vue";
import BaseOutlineButton from "@/Components/Base/BaseOutlineButton.vue";

const guestDetails = reactive({
    name: null,
    email: null,
});

const emits = defineEmits(['submitEventGuestDetails', 'returnToPreviousStep']);

const errors = reactive({
    name: null,
    email: null,
});

const submitEventDetails = () => {
    let hasError = false;
    errors.name = null;
    errors.email = null;

    if (!guestDetails.name) {
        errors.name = 'Name is required.';
        hasError = true;
    }

    if (!guestDetails.email) {
        errors.email = 'Email is required.';
        hasError = true;
    } else if (!/\S+@\S+\.\S+/.test(guestDetails.email)) {
        errors.email = 'Please enter a valid email address.';
        hasError = true;
    }

    if (!hasError) {
        emits('submitEventGuestDetails', { name: guestDetails.name, email: guestDetails.email });
    }
}
</script>

<template>
    <div class="w-full flex justify-center text-2xl font-semibold mb-6">
        <h1 class="text-2xl md:text-4xl">
            Enter Your Details
        </h1>
    </div>
    <div class="flex flex-col items-center w-full md:w-2/3 xl:w-1/3 pt-6 gap-4">
        <TextInput
            :model-value="guestDetails.name"
            :required="true"
            @update:modelValue="val => guestDetails.name = val"
            name="name"
            placeholder="Name"
            class="w-full"
            :error="errors.name"
        />
        <TextInput
            :model-value="guestDetails.email"
            :required="true"
            @update:modelValue="val => guestDetails.email = val"
            name="email"
            type="email"
            placeholder="Your Email"
            class="w-full"
            :error="errors.email"
        />

        <div class="w-full flex justify-end mt-4">
            <BaseOutlineButton
                label="Back"
                class="mr-4"
                @click="emits('returnToPreviousStep')"
            />
            <BaseButton
                @click="submitEventDetails"
                label="Create event"
            />
        </div>
    </div>
</template>
