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

const props = defineProps({
    event: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    title: props.event.title,
    description: props.event.description,
    location: props.event.location,
    start_date_time: null,
    end_date_time: null,
    image: null,
    remove_image: false,
});

const setDateObject = (dateObject) => {
    if (!dateObject || !dateObject.selectedDate || !dateObject.selectedHour || !dateObject.selectedMinute) {
        console.warn("Invalid date object passed to setDateObject:", dateObject);
        form.start_date_time = null;
        form.end_date_time = null;
        return;
    }

    // Create start date-time
    const startDateTime = setMinutes(
        setHours(dateObject.selectedDate, parseInt(dateObject.selectedHour)),
        parseInt(dateObject.selectedMinute)
    );

    let endDateTime = null;

    if (dateObject.selectedEndHour && dateObject.selectedEndMinute) {
        // Create end date-time
        endDateTime = setMinutes(
            setHours(dateObject.selectedDate, parseInt(dateObject.selectedEndHour)),
            parseInt(dateObject.selectedEndMinute)
        );

        console.log(endDateTime)

        // Check if the end time is earlier than or equal to the start time
        if (endDateTime <= startDateTime) {
            const adjustedDate = new Date(dateObject.selectedDate);
            adjustedDate.setDate(adjustedDate.getDate() + 1);

            endDateTime = setMinutes(
                setHours(adjustedDate, parseInt(dateObject.selectedEndHour)),
                parseInt(dateObject.selectedEndMinute)
            );
        }
    }

    // Format and assign to the form
    form.start_date_time = isValid(startDateTime)
        ? format(startDateTime, "yyyy-MM-dd HH:mm:ss")
        : null;
    form.end_date_time = isValid(endDateTime)
        ? format(endDateTime, "yyyy-MM-dd HH:mm:ss")
        : null;
};


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

const scrollToTop = () => {
    window.scrollTo({
        top: 0,
        behavior: "smooth",
    });
};

const setImage = (event) => {
    console.log(event instanceof File);
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
                        <FileUpload
                            @fileUploaded="setImage($event)"
                            @clearImage="form.remove_image = true"
                            :initial-image="event.media[0]?.url"
                        />

                        <DatePicker
                            @update="setDateObject($event)"
                            :initial-start-time="form.start_date_time"
                            :initial-end-time="form.end_date_time"
                            class="mt-6"
                        />
                        <div class="w-full flex justify-between lg:justify-end mt-4">
                            <BaseOutlineButton
                                label="Cancel"
                                class="mr-4"
                                @click="router.get(route('home'))"
                            />
                            <BaseButton
                                label="Update event"
                                @click="submitEventDetails"
                            />
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </DefaultLayout>
</template>
