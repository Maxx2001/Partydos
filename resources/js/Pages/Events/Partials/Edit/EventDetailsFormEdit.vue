<template>
    <TextInput
        :model-value="form.title"
        :required="true"
        @update:modelValue="val => form.title = val"
        name="title"
        placeholder="Fill in the title of the event"
        class="mx-2 w-full"
        :error="titleErrorBag"
    />

    <TextInput
        :model-value="form.location"
        @update:modelValue="val => form.location = val"
        name="location"
        placeholder="Where is it?"
        class="mx-2 w-full"
    />

    <TextAreaInput
        :model-value="form.description"
        @update:modelValue="val => form.description = val"
        name="description"
        placeholder="Describe the event"
        class="mx-2 w-full"
    />
</template>

<script setup>
import TextInput from '@/Components/Inputs/TextInput.vue';
import TextAreaInput from '@/Components/Inputs/TextAreaInput.vue';

const props = defineProps({
    form: {
        type: Object,
        required: true
    },
    titleErrorBag: {
        type: String,
        default: ''
    }
});
</script>
