<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import { computed, reactive, ref } from "vue";
import EventDetailsInput from "@/Pages/Events/Partials/EventDetailsInput.vue";
import EventDatePicker from "@/Pages/Events/Partials/EventDatePicker.vue";
import EventGuestSubmit from "@/Pages/Events/Partials/EventGuestSubmit.vue";
import { router } from "@inertiajs/vue3";
import { setHours, setMinutes } from 'date-fns';
import { format } from 'date-fns';  // Correct import for format function

const form = reactive({
    title: null,
    description: null,
    location: null,
    startDateTime: null,
    endDateTime: null,
    name: null,
    email: null,
});

const stepIndex = ref(1);

const showEventDetailsInput = computed(() => stepIndex.value === 1);
const showEventDatePicker = computed(() => stepIndex.value === 2);
const showEventGuestSubmit = computed(() => stepIndex.value === 3);
const showEndDateOption = ref(false);

const submitForm = () => {
    router.post(route('events.store'), form);
};

// Function to set DateTime in form
const setDateObject = (dateObject) => {
    // Combine selected date and time to create DateTime for start
    const startDateTime = setMinutes(
        setHours(dateObject.selectedDate, parseInt(dateObject.selectedHour)),
        parseInt(dateObject.selectedMinute)
    );

    // Combine selected date and time to create DateTime for end (if selected)
    let endDateTime = null;
    if (dateObject.selectedEndHour && dateObject.selectedEndMinute) {
        endDateTime = setMinutes(
            setHours(dateObject.selectedDate, parseInt(dateObject.selectedEndHour)),
            parseInt(dateObject.selectedEndMinute)
        );
    }

    // Format the DateTime to MySQL compatible format
    form.startDateTime = format(startDateTime, 'yyyy-MM-dd HH:mm:ss');
    form.endDateTime = endDateTime ? format(endDateTime, 'yyyy-MM-dd HH:mm:ss') : null;
};
</script>


<template>
    <DefaultLayout>
        <div class="py-16 md:py-24 px-6 flex flex-col items-center justify-center bg-slate-100 rounded">
            <form @submit.prevent="submitForm" class="w-full flex flex-col items-center">
                <EventDetailsInput
                    v-if=showEventDetailsInput
                    :form="form"
                    @submitEventDetails="stepIndex = 2"
                />
                <EventDatePicker
                    v-if="showEventDatePicker"
                    @update="setDateObject($event)"
                    @returnToPreviousStep="stepIndex = 1"
                    @submitEventDetails="stepIndex = 3"
                />
                <EventGuestSubmit
                    v-if="showEventGuestSubmit"
                    :form="form"
                    @returnToPreviousStep="stepIndex = 2"
                    @submitEventGuestDetails="submitForm"
                />
            </form>
        </div>
    </DefaultLayout>
</template>
