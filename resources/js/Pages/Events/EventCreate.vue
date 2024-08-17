<script setup>

import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import { computed, reactive, ref } from "vue";
import EventDetailsInput from "@/Pages/Events/Partials/EventDetailsInput.vue";
import EventDatePicker from "@/Pages/Events/Partials/EventDatePicker.vue";
import CheckboxInput from "@/Components/Inputs/CheckboxInput.vue";
import EventGuestSubmit from "@/Pages/Events/Partials/EventGuestSubmit.vue";
import { router } from "@inertiajs/vue3";

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
const showEventDatePicker= computed(() => stepIndex.value === 2);
const showEventGuestSubmit= computed(() => stepIndex.value === 3);
const showEndDateOption = ref(false);

const submitForm = () => {
    router.post(route('events.store'), form);
}
</script>

<template>
    <DefaultLayout>
        <div class="py-24 px-6 flex flex-col items-center justify-center bg-slate-100 rounded">
            <form @submit.prevent="submitForm" class="w-1/3 flex flex-col items-center">
                <EventDetailsInput
                    v-if=showEventDetailsInput
                    :form="form"
                    @submitEventDetails="stepIndex = 2"
                />
                <EventDatePicker
                    v-if="showEventDatePicker"
                    :form="form"
                    :show-end-date-input="showEndDateOption"
                    @returnToPreviousStep="stepIndex = 1"
                    @submitEventDetails="stepIndex = 3"
                    @update:modelValue="val => showEndDateOption = val"
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
