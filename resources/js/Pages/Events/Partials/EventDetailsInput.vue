<script setup>
import TextInput from "@/Components/Inputs/TextInput.vue";
import TextAreaInput from "@/Components/Inputs/TextAreaInput.vue";
import BaseButton from "@/Components/Base/BaseButton.vue";
import { ref } from "vue";

const props = defineProps({
    form: {
        type: Object,
        required: true
    },
});

const emit = defineEmits(['submitEventDetails']);
const titleErrorBag = ref('');

const submitEventDetails = () => {
    if ( !props.form.title ) {
        titleErrorBag.value = 'Title is required.';
        return;
    }

    emit('submitEventDetails');
}
</script>

<template>
    <div class="w-full flex justify-center text-2xl font-semibold">
        <h1>
            Create an event!
        </h1>
    </div>
    <div class="flex flex-col items-center w-full">
        <TextInput
            :model-value="form.title"
            :required="true"
            @update:modelValue="val => form.title = val"
            name="title"
            input-title="Title"
            placeholder="Birthday party, roadtrip"
            class="mx-2 w-full my-3"
            :error="titleErrorBag"
        />

        <TextInput
            :model-value="form.location"
            :required="false"
            @update:modelValue="val => form.location = val"
            name="location"
            input-title="Location"
            placeholder="Adress, city, country"
            class="mx-2 w-full my-3"
        />

        <TextAreaInput
            :model-value="form.description"
            :required="false"
            @update:modelValue="val => form.description = val"
            name="description"
            input-title="Description"
            placeholder="Optional description of the event."
            class="mx-2 w-full my-3"
        />

        <div class="w-full flex justify-end mt-4">
            <BaseButton
                label="Pick a date"
                @click="submitEventDetails"
            />
        </div>
    </div>
</template>
