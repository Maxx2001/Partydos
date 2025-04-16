<script setup>
import { reactive, ref, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { setHours, setMinutes, format } from 'date-fns';
import EventDatePicker from './EventDatePicker.vue';
import EventCustomization from './EventCustomization.vue';
import EventGuestSubmit from './EventGuestSubmit.vue';

const props = defineProps({
    initialDetails: {
        type: Object,
        required: true,
    }
});

const emit = defineEmits(['returnToDetails']);

const isLoggedIn = computed(() => usePage().props.auth.user);
const stepIndex = ref(1); // 1: DatePicker, 2: Customization, 3: GuestSubmit

const form = reactive({
    title: props.initialDetails.title,
    description: props.initialDetails.description,
    location: props.initialDetails.location,
    start_date_time: null,
    end_date_time: null,
    image: null,
    name: null,
    email: null,
});

const showEventDatePicker = computed(() => stepIndex.value === 1);
const showEventCustomization = computed(() => stepIndex.value === 2);
const showEventGuestSubmit = computed(() => stepIndex.value === 3 && !isLoggedIn.value);

const handleDateUpdate = (dateObject) => {
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

const handleImageUpdate = (imageFile) => {
    form.image = imageFile;
};

// Methods for navigation
const goToNextStep = () => {

    if (stepIndex.value === 2 && isLoggedIn.value) {
        submitAuthenticatedForm();
    } else if (stepIndex.value < 3) {
         stepIndex.value++;
         scrollToTop();
    } else if (stepIndex.value === 3 && !isLoggedIn.value) {
        // Guest submit is handled by EventGuestSubmit component emitting 'submitGuestDetails'
    }
};

const goToPreviousStep = (targetStep) => {
    if (targetStep === 'details') {
        emit('returnToDetails');
    } else {
        stepIndex.value = targetStep;
        scrollToTop();
    }
};

// Submit methods
const submitAuthenticatedForm = () => {
    router.post(route('users-events.store'), form);
};

const submitGuestForm = (guestUserData) => {
    form.name = guestUserData.name;
    form.email = guestUserData.email;
    router.post(route('guest-events.store'), form);
};

const scrollToTop = () => {
    if (window.innerWidth < 768) {
        window.scrollTo({
            top: 0,
            behavior: 'smooth',
        });
    }
};

</script>

<template>
    <div class="w-full flex flex-col items-center">
        <EventDatePicker
            v-if="showEventDatePicker"
            @update="handleDateUpdate($event)"
            @returnToPreviousStep="() => goToPreviousStep('details')"
            @submitEventDetails="goToNextStep"
        />

        <EventCustomization
            v-if="showEventCustomization"
            @update="handleImageUpdate($event)"
            @returnToPreviousStep="() => goToPreviousStep(1)"
            @submitEventDetails="goToNextStep"
        />

        <EventGuestSubmit
            v-if="showEventGuestSubmit"
            :form="form"
            @returnToPreviousStep="() => goToPreviousStep(2)"
            @submitEventGuestDetails="submitGuestForm($event)"
        />
    </div>
</template>
