<script setup>
import {computed} from "vue";
import BaseOutlineButton from "@/Components/Base/BaseOutlineButton.vue";
import EventBanner from "@/Pages/Events/EventInvite/Partials/EventBanner.vue";

const props = defineProps({
    event: {
        type: Object,
        required: true
    }
});

const formattedStartDate = computed(() => {
    return props.event.startDateTime ? formatDate(props.event.startDateTime) : 'No start date set.';
});

const formatDate = (dateString) => {
    const options = {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    };
    return new Intl.DateTimeFormat('en-GB', options).format(new Date(dateString));
};

const emits = defineEmits(['acceptEventInvite']);
</script>

<template>
    <section class="relative bg-gradient-to-br from-blue-500 to-purple-600 text-white py-8 md:py-24 px-6">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-start h-full">
            <div
                class="w-full lg:px-0 h-full flex flex-col justify-center space-y-6"
                data-aos="fade-up"
                data-aos-duration="1000"
            >
                <h1 class="text-2xl md:text-5xl font-extrabold leading-tight text-center lg:text-left flex flex-col md:flex-row justify-center">
                    You are invited to:
                    <span class="pl-2 text-green-400">
                        {{ event.title }}
                    </span>
                </h1>
                <EventBanner class="block md:hidden"/>
                <div class="md:pb-20 md:hidden flex flex-col text-lg">
                    <p class="flex flex-col text-center font-extrabold">
                        Location:
                        <span class="ml-2 md:ml-4 text-yellow-400">
                            {{ event.location || 'No event location set.' }}
                        </span>
                    </p>
                    <p class="pt-1 flex flex-col text-center">
                        Event starts at:
                        <span class="ml-2 md:ml-4 text-pink-300">
                            {{ formattedStartDate }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
        <div class="absolute top-0 left-0 w-24 h-24 bg-white/20 rounded-full blur-lg"></div>
        <div class="flex justify-center pt-4">
            <BaseOutlineButton label="Join event!" @click="emits('acceptEventInvite')" class="block md:hidden"/>
        </div>
    </section>
</template>
