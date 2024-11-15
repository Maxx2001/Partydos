<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import { computed, reactive, ref } from "vue";
import EventDetailsInput from "@/Pages/Events/EventInvite/Partials/EventDetailsInput.vue";
import EventDatePicker from "@/Pages/Events/EventCreate/Partials/EventDatePicker.vue";
import EventGuestSubmit from "@/Pages/Events/EventInvite/Partials/EventGuestSubmit.vue";
import {router, usePage} from "@inertiajs/vue3";
import { setHours, setMinutes } from 'date-fns';
import { format } from 'date-fns';
import { useTitle } from '@/Composables/useTitle.js';

useTitle('Partydos | Create Event');

const form = reactive({
    title: null,
    description: null,
    location: null,
    start_date_time: null,
    end_date_time: null,
    name: null,
    email: null,
});

const stepIndex = ref(1);

const showEventDetailsInput = computed(() => stepIndex.value === 1);
const showEventDatePicker = computed(() => stepIndex.value === 2);
const showEventGuestSubmit = computed(() => stepIndex.value === 3);

const submitForm = () => router.post(route('events.store'), form);

const submitAuthenticatedForm = () => router.post(route('events.store'), form)

const isLoggedIn = usePage().props.auth.user;

const setDateObject = (dateObject) => {
    const startDateTime = setMinutes(
        setHours(dateObject.selectedDate, parseInt(dateObject.selectedHour)),
        parseInt(dateObject.selectedMinute)
    );

    let endDateTime = null;
    if (dateObject.selectedEndHour && dateObject.selectedEndMinute) {
        endDateTime = setMinutes(
            setHours(dateObject.selectedDate, parseInt(dateObject.selectedEndHour)),
            parseInt(dateObject.selectedEndMinute)
        );
    }

    form.start_date_time = format(startDateTime, 'yyyy-MM-dd HH:mm:ss');
    form.end_date_time = endDateTime ? format(endDateTime, 'yyyy-MM-dd HH:mm:ss') : null;
};

const submitEventDetails = (stepIndexNumber) => {
    if (stepIndexNumber === 3 && isLoggedIn) {
        submitAuthenticatedForm();
    }

    stepIndex.value = stepIndexNumber;
    scrollToTop();
}

const scrollToTop = () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
};
</script>


<template>
    <DefaultLayout>
        <div class="py-8 md:py-24 px-6 flex flex-col items-center justify-center bg-slate-100 rounded">
            <form @submit.prevent="submitForm" class="w-full flex flex-col items-center">
                <EventDetailsInput
                    v-if=showEventDetailsInput
                    :form="form"
                    @submitEventDetails="() => submitEventDetails(2)"
                />
                <EventDatePicker
                    v-if="showEventDatePicker"
                    @update="setDateObject($event)"
                    @returnToPreviousStep="() => submitEventDetails(1)"
                    @submitEventDetails="() => submitEventDetails(3)"
                />
                <EventGuestSubmit
                    v-if="showEventGuestSubmit"
                    :form="form"
                    @returnToPreviousStep="() => submitEventDetails(2)"
                    @submitEventGuestDetails="submitForm"
                />
            </form>
        </div>
    </DefaultLayout>
</template>
