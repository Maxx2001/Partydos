<script setup>
import TextInput from "@/Components/Inputs/TextInput.vue";
import TextAreaInput from "@/Components/Inputs/TextAreaInput.vue";
import BaseButton from "@/Components/Base/BaseButton.vue";
import { ref, reactive, watch } from "vue";
import { router } from "@inertiajs/vue3";
import BaseOutlineButton from "@/Components/Base/BaseOutlineButton.vue";
import AutoCompleteAddressInput from "@/Components/Inputs/AutoCompleteAddressInput.vue";

const props = defineProps({
    initialFormData: {
        type: Object,
        default: () => ({ title: null, description: null, location: null })
    },
    isEdit: {
        type: Boolean,
        default: false
    }
});

const localForm = reactive({
    title: props.initialFormData.title,
    description: props.initialFormData.description,
    location: props.initialFormData.location
});

watch(() => props.initialFormData, (newVal) => {
    localForm.title = newVal.title;
    localForm.description = newVal.description;
    localForm.location = newVal.location;
}, { deep: true });

const emit = defineEmits(['submitEventDetails', 'createDatePoll']);

const titleErrorBag = ref('');

const submitEventDetails = () => {
    if (!localForm.title) {
        titleErrorBag.value = 'Title is required.';
        return;
    }
    titleErrorBag.value = '';
    emit('submitEventDetails', { ...localForm });
};

const openDatePoll = () => {
    if (!localForm.title) {
        titleErrorBag.value = 'Title is required before creating a date poll.';
        return;
    }
    titleErrorBag.value = '';
    emit('createDatePoll', { ...localForm });
};

const updateLocation = (event) => {
    if (typeof event === 'string') {
        localForm.location = {
            address: event,
            place_id: null,
        };
        return;
    }
    localForm.location = event;
};
</script>

<template>
    <div class="flex flex-col items-center w-full">
        <div class="w-full md:w-2/3 xl:w-1/3 flex flex-col items-center gap-4">
            <div class="w-full flex flex-col items-center gap-4 py-8 lg:py-20 md:py-0">
                <div class="w-full flex justify-center text-2xl font-semibold items-center mb-4">
                    <h1 v-if="isEdit" class="text-2xl md:text-4xl">
                        Update event
                    </h1>
                    <h1 v-else class="text-2xl md:text-4xl">
                        What type of
                        <span class="text-blue-600">
                            event
                        </span> is it?
                    </h1>
                </div>

                <TextInput
                    :model-value="localForm.title"
                    :required="true"
                    @update:modelValue="val => localForm.title = val"
                    name="title"
                    placeholder="Fill in the title of the event"
                    class="mx-2 w-full"
                    :error="titleErrorBag"
                />

                <AutoCompleteAddressInput
                    :model-value="localForm.location"
                    @update:modelValue="updateLocation"
                    name="location"
                    placeholder="Where is it?"
                />

                <TextAreaInput
                    :model-value="localForm.description"
                    @update:modelValue="val => localForm.description = val"
                    name="description"
                    placeholder="Describe the event"
                    class="mx-2 w-full"
                />

                <div class="w-full flex flex-col space-y-4 lg:mt-6 lg:flex-row lg:justify-between lg:space-y-0">
                    <BaseOutlineButton
                        label="Cancel"
                        class="w-full lg:w-auto hidden lg:block"
                        @click="router.get(route('home'))"
                    />
                    <div class="flex flex-col space-y-4 w-full lg:w-auto lg:flex-row lg:space-y-0 lg:space-x-4">
                        <BaseButton
                            label="Create Date Poll"
                            @click="openDatePoll"
                            variant="secondary"
                            class="w-full lg:w-auto"
                        />
                        <BaseButton
                            label="Pick a date"
                            @click="submitEventDetails"
                            class="w-full lg:w-auto"
                        />
                    </div>
                    <BaseOutlineButton
                        label="Cancel"
                        class="w-full lg:w-auto  lg:hidden block"
                        @click="router.get(route('home'))"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
