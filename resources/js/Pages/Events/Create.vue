<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import { reactive, ref } from "vue";
import EventDetailsInput from "@/Pages/Events/Partials/Create/Common/EventDetailsInput.vue";
import CreateEventFlow from "@/Pages/Events/Partials/Create/Standard/CreateEventFlow.vue";
import CreateDatePollFlow from "@/Pages/Events/Partials/Create/Poll/CreateDatePollFlow.vue";
import { useTitle } from '@/Composables/useTitle.js';

useTitle('Create Event | Partydos');

// Enum for creation type
const CreationType = {
  NONE: 'none',
  STANDARD: 'standard',
  POLL: 'poll'
};

const creationType = ref(CreationType.NONE);
const initialDetails = reactive({
    title: null,
    description: null,
    location: null,
});

const handleDetailsSubmit = (details) => {
    Object.assign(initialDetails, details);
    creationType.value = CreationType.STANDARD;
    scrollToTop();
};

const handlePollSubmit = (details) => {
    Object.assign(initialDetails, details);
    creationType.value = CreationType.POLL;
    scrollToTop();
};

const returnToDetails = () => {
    creationType.value = CreationType.NONE;
    // Optionally clear initialDetails if needed when going back
    // Object.assign(initialDetails, { title: null, description: null, location: null });
    scrollToTop();
};

const layoutRef = ref(null);

const scrollToTop = async () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth',
    });
};

</script>

<template>
    <DefaultLayout ref="layoutRef">
        <div class="py-8 md:py-24 px-6 flex flex-col items-center justify-center bg-slate-100 rounded" ref="topElement">
            <div class="w-full flex flex-col items-center">
                <EventDetailsInput
                    v-if="creationType === CreationType.NONE"
                    :initial-form-data="initialDetails"
                    @submitEventDetails="handleDetailsSubmit"
                    @createDatePoll="handlePollSubmit"
                />

                <CreateEventFlow
                    v-if="creationType === CreationType.STANDARD"
                    :initial-details="initialDetails"
                    @returnToDetails="returnToDetails"
                />

                <CreateDatePollFlow
                    v-if="creationType === CreationType.POLL"
                    :initial-details="initialDetails"
                    @returnToDetails="returnToDetails"
                />
            </div>
        </div>
    </DefaultLayout>
</template>
