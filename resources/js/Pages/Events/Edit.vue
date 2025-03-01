<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import {  ref } from "vue";
import { format, setHours, setMinutes, parseISO, isValid } from "date-fns";
import { router, useForm } from "@inertiajs/vue3";
import BaseButton from "@/Components/Base/BaseButton.vue";
import TextAreaInput from "@/Components/Inputs/TextAreaInput.vue";
import TextInput from "@/Components/Inputs/TextInput.vue";
import BaseOutlineButton from "@/Components/Base/BaseOutlineButton.vue";
import DatePicker from "@/Pages/Events/Partials/Create/DatePicker.vue";
import FileUpload from "@/Components/Form/FileUpload.vue";
import EventDetailsFormEdit from "@/Pages/Events/Partials/Edit/EventDetailsFormEdit.vue";
import DatePickerFormEdit from "@/Pages/Events/Partials/Edit/DatePickerFormEdit.vue";
import ActionButtons from "@/Pages/Events/Partials/Edit/ActionButtons.vue";
import {useTitle} from "@/Composables/useTitle.js";

const props = defineProps({
    event: {
        type: Object,
        required: true,
    },
});
useTitle(props.event.title + ' | Partydos');
const form = useForm({
    title: props.event.title,
    description: props.event.description,
    location: props.event.address,
    start_date_time: null,
    end_date_time: null,
    image: null,
    remove_image: false,
});

const initializeDates = () => {
    const isoStart = props.event.isoStartDateTime
        ? parseISO(props.event.isoStartDateTime)
        : null;
    if (isoStart && isValid(isoStart)) {
        form.start_date_time = format(isoStart, "yyyy-MM-dd HH:mm:ss");
    }

    const isoEnd = props.event.isoEndDateTime
        ? parseISO(props.event.isoEndDateTime)
        : null;
    if (isoEnd && isValid(isoEnd)) {
        form.end_date_time = format(isoEnd, "yyyy-MM-dd HH:mm:ss");
    }
};

initializeDates();

const titleErrorBag = ref("");

const submitEventDetails = () => {
    if (!form.title) {
        titleErrorBag.value = "Title is required.";
        return;
    }

    router.post(route('users-events.update', {event: props.event.id}),
        {
            ...form.data(),
            _method: 'put',
        });
};

const setImage = (event) => {
    form.image = event;
    form.remove_image = false;
};
</script>

<template>
    <DefaultLayout>
        <div class="py-8 md:py-24 px-6 flex flex-col items-center justify-center bg-slate-100 rounded">
            <form @submit.prevent class="w-full flex flex-col items-center">
                <div class="w-full flex justify-center text-2xl font-semibold">
                    <h1 class="text-2xl md:text-4xl">
                        Update event details
                    </h1>
                </div>
                <div class="flex flex-col items-center w-full pt-6">
                    <div class="w-full md:w-2/3 xl:w-1/3 flex flex-col items-center gap-4">
                        <EventDetailsFormEdit
                            :form="form"
                            :titleErrorBag="titleErrorBag"
                        />
                        <FileUpload
                            @fileUploaded="setImage($event)"
                            @clearImage="form.remove_image = true"
                            :initial-image="event.media[0]?.url"
                        />

                        <DatePickerFormEdit
                            :form="form"
                            @update:form="updatedForm => form = updatedForm"
                        />

                        <ActionButtons
                            :onCancelEvent="() => router.get(route('home'))"
                            :onCancel="() => router.get(route('home'))"
                            :onUpdate="submitEventDetails"
                            :event="event"
                        />
                    </div>
                </div>
            </form>
        </div>
    </DefaultLayout>
</template>
