<script setup>
import {Autoplay} from "swiper/modules";
import {Swiper, SwiperSlide} from "swiper/vue";
import EventCardInspiration from "@/Pages/LandingsPage/Partials/EventCardInspiration.vue";
import {computed} from "vue";

const inspirationEvents = [
    {
        title: "A Party to Remember",
        description: "An unforgettable night filled with music, dancing, and good vibes."
    },
    {
        title: "A Fun Trip",
        description: "An exciting adventure to explore new places and create lasting memories."
    },
    {
        title: "A Beach Bonfire Night",
        description: "Gather by the shore, enjoy roasted marshmallows, and share stories under the stars."
    },
    {
        title: "A Starry Sky Campout",
        description: "A night under the stars, complete with campfires, s'mores, and breathtaking views."
    },
    {
        title: "A Mystery Dinner Party",
        description: "Step into a world of intrigue and solve a thrilling mystery while enjoying a delicious meal."
    },
    {
        title: "A Festival of Colors",
        description: "Celebrate life and joy with bursts of color, music, and festive energy."
    },
    {
        title: "A Family Gathering",
        description: "A warm and cozy event where family comes together to share laughter and stories."
    },
];


const sliderPerView = computed(() => {
    if (window.innerWidth < 640) {
        return 1;
    }

    if (window.innerWidth < 1024) {
        return 2;
    }

    return 3;
});
</script>

<template>
    <div class="w-full max-w-6xl">
        <h2 class="text-2xl font-bold text-blue-600 mb-4 text-center pb-4">
            Plan your next event!
        </h2>
        <Swiper
            :modules="[Autoplay]"
            :slides-per-view="sliderPerView"
            :space-between="16"
            :loop="true"
            :autoplay="{ delay: 5000, disableOnInteraction: false }"
            class="w-full"
        >
            <SwiperSlide v-for="(event, index) in inspirationEvents" :key="index">
                <EventCardInspiration :index="index" :event="event"/>
            </SwiperSlide>
        </Swiper>
    </div>
</template>
