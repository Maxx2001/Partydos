<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import { computed, reactive, ref } from "vue";
import EventDetailsInput from "@/Pages/Events/Partials/Invite/EventDetailsInput.vue";
import EventDatePicker from "@/Pages/Events/Partials/Create/EventDatePicker.vue";
import EventGuestSubmit from "@/Pages/Events/Partials/Invite/EventGuestSubmit.vue";
import { router, usePage } from "@inertiajs/vue3";
import { setHours, setMinutes, format } from 'date-fns';
import { useTitle } from '@/Composables/useTitle.js';
import EventCustomization from "@/Pages/Events/Partials/Invite/EventCustomization.vue";
import CreateDatePoll from "@/Pages/Events/Partials/Create/CreateDatePoll.vue";
useTitle('Create Event | Partydos');

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
const showEventDatePoll = computed(() => stepIndex.value === 5);
const isLoggedIn = usePage().props.auth.user;

const submitForm = () => router.post(route('guest-events.store'), form);
const submitAuthenticatedForm = () => router.post(route('users-events.store'), form);

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
};

const layoutRef = ref(null);


const scrollToTop = async () => {
    if (window.innerWidth < 768) {
        window.scrollTo({
            top: 0,
            behavior: 'smooth',
        });
    }
};

const setImage = (event) => form.image = event;


const handleDatePoll = () => {
    if (!form.title) {
        alert('Please fill in the event title first!');
        return;
    }

    router.get(route('date-polls.create'), {
        title: form.title,
        description: form.description,
        location: form.location?.address || '',
    });
};
</script>

<template>
    <DefaultLayout ref="layoutRef">
        <div class="py-8 md:py-24 px-6 flex flex-col items-center justify-center bg-slate-100 rounded" ref="topElement">
            <form @submit.prevent="submitForm" class="w-full flex flex-col items-center">
                <EventDetailsInput
                    v-if="showEventDetailsInput"
                    :form="form"
                    @submitEventDetails="() => submitEventDetails(2)"
                    @createDatePoll="() => submitEventDetails(5)"
                />

                <CreateDatePoll
                    v-if="showEventDatePoll"
                    @returnToPreviousStep="() => submitEventDetails(1)"
                    @addDateOption="handleDatePoll"
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
