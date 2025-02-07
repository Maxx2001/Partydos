<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import { computed, reactive, ref } from "vue";
import EventDetailsInput from "@/Pages/Events/Partials/Invite/EventDetailsInput.vue";
import EventDatePicker from "@/Pages/Events/Partials/Create/EventDatePicker.vue";
import EventGuestSubmit from "@/Pages/Events/Partials/Invite/EventGuestSubmit.vue";
import {router, usePage} from "@inertiajs/vue3";
import { setHours, setMinutes } from 'date-fns';
import { format } from 'date-fns';
import { useTitle } from '@/Composables/useTitle.js';
import EventCustomization from "@/Pages/Events/Partials/Invite/EventCustomization.vue";

useTitle('Partydos | Create Event');

const form = reactive({
    title: null,
    description: null,
    location: null,
    start_date_time: null,
    end_date_time: null,
    name: null,
    email: null,
    image: null,
});

const stepIndex = ref(1);

const showEventDetailsInput = computed(() => stepIndex.value === 1);
const showEventDatePicker = computed(() => stepIndex.value === 2);
const showEventCustomization = computed(() => stepIndex.value === 3);
const showEventGuestSubmit = computed(() => stepIndex.value === 4);

const submitForm = () => router.post(route('guest-events.store'), form);

const submitAuthenticatedForm = () => router.post(route('users-events.store'), form)

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
    if (stepIndexNumber === 4 && isLoggedIn) {
        submitAuthenticatedForm();
        return;
    }

    stepIndex.value = stepIndexNumber;
    scrollToTop();
}

const scrollToTop = () => topElement.value?.scrollIntoView({ behavior: 'smooth' })

const setImage = (event) => form.image = event;

const topElement = ref(null);
</script>


<template>
    <DefaultLayout>
        <div class="py-8 md:py-24 px-6 flex flex-col items-center justify-center bg-slate-100 rounded" ref="topElement">
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
                <EventCustomization
                    v-if="showEventCustomization"
                    @update="setImage($event)"
                    @returnToPreviousStep="() => submitEventDetails(2)"
                    @submitEventDetails="() => submitEventDetails(4)"
                    />
                <EventGuestSubmit
                    v-if="showEventGuestSubmit"
                    :form="form"
                    @returnToPreviousStep="() => submitEventDetails(3)"
                    @submitEventGuestDetails="submitForm"
                />
            </form>
        </div>
    </DefaultLayout>
</template>
