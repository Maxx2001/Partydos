<script setup>
import EmailInput from "@/Components/Form/EmailInput.vue";
import TextInput from "@/Components/Inputs/TextInput.vue";
import {router} from "@inertiajs/vue3";

const submitGuestRegister = () => {
    if (validateForm()) {
        return router.post(
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
</script>

<template>
    <div>
        <TextInput
            placeholder="Enter your name"
            name="name"
            :model-value="form.name"
            :required="true"
            :error="errors.name"
            @update:modelValue="val => form.name = val"
            class="w-full"
        />
        <EmailInput
            id="email"
            label="Your Email"
            v-model=form.email
            placeholder="party@dos.com"
            :error-message="errors.email"
        />
</div>
</template>
