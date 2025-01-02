<script setup>
import TextInput from "@/Components/Inputs/TextInput.vue";
import BaseButton from "@/Components/Base/BaseButton.vue";
import { ref } from "vue";
import BaseOutlineButton from "@/Components/Base/BaseOutlineButton.vue";

const props = defineProps({
    form: {
        type: Object,
        required: true
    },
});

const emits = defineEmits(['submitEventGuestDetails', 'returnToPreviousStep']);
const titleErrorBag = ref([]);

const submitEventDetails = () => {
    if (props.form.name && props.form.email) {
        emits('submitEventGuestDetails');
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
        <h1 class="text-2xl md:text-4xl">
            Create event as guest
        </h1>
    </div>
    <div class="flex flex-col items-center w-full md:w-2/3 xl:w-1/3 pt-6 gap-4">
        <TextInput
            :model-value="form.name"
            :required="true"
            @update:modelValue="val => form.name = val"
            name="name"
            placeholder="Name"
            class="mx-2 w-full"
            :error="titleErrorBag['name']"
        />
        <TextInput
            :model-value="form.email"
            :required="true"
            @update:modelValue="val => form.email = val"
            name="email"
            placeholder="Your Email"
            class="mx-2 w-full"
            :error="titleErrorBag['email']"
        />

        <div class="w-full flex justify-end mt-4">
            <BaseOutlineButton
                label="Back to date picker"
                @click="emits('returnToPreviousStep')"

            />
            <BaseButton
                @click="submitEventDetails"
                class="ml-5"
                label="Create event"
            />
        </div>
    </div>
</template>
