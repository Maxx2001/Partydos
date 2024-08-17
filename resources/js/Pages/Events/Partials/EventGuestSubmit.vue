<script setup>
import TextInput from "@/Components/Inputs/TextInput.vue";
import BaseButton from "@/Components/Base/BaseButton.vue";
import { ref } from "vue";

const props = defineProps({
    form: {
        type: Object,
        required: true
    },
});

const emit = defineEmits(['submitEventGuestDetails']);
const titleErrorBag = ref([]);

const submitEventDetails = () => {
    if (props.form.name && props.form.email) {
        emit('submitEventGuestDetails');
        return;
    }

    if ( !props.form.name ) {
        titleErrorBag.value['name'] = 'Name is required.';
    }

    if ( !props.form.email ) {
        titleErrorBag.value['email'] = 'Email is required.';
    }

}
</script>

<template>
    <div class="w-full flex justify-center text-2xl font-semibold">
        <h1>
            Create even as guest
        </h1>
    </div>
    <div class="flex flex-col items-center w-full ">
        <TextInput
            :model-value="form.name"
            :required="true"
            @update:modelValue="val => form.name = val"
            name="name"
            input-title="Name"
            placeholder="Jan"
            class="mx-2 w-full my-3"
            :error="titleErrorBag['name']"
        />
        <TextInput
            :model-value="form.email"
            :required="true"
            @update:modelValue="val => form.email = val"
            name="email"
            input-title="Email"
            placeholder="example@mail.com"
            class="mx-2 w-full my-3"
            :error="titleErrorBag['email']"
        />

        <div class="w-full flex justify-end mt-4">
            <BaseButton
                label="Back to date picker"
                @click="emit('returnToPreviousStep')"

            />
            <BaseButton
                @click="submitEventDetails"
                class="ml-5"
                label="Create event"
            />
        </div>
    </div>
</template>
