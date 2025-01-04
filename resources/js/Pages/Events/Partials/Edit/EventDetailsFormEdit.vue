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
    <AutoCompleteAddressInput
        :model-value="form.location"
        @update:modelValue="val => updateLocation(val)"
        name="location"
        placeholder="Where is it?"
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
import AutoCompleteAddressInput from "@/Components/Inputs/AutoCompleteAddressInput.vue";

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

const updateLocation = (event) => {
    if (typeof event === 'string') {
        props.form.location = {
            id: props.form.location.id,
            address: event,
            place_id: null,
        };

        return;
    }

    props.form.location = {
        id: props.form.location.id,
        address: event.address,
        place_id: event.place_id,
    };
}
</script>
