<script setup>
import FeatureBox from "../../../Components/Base/FeatureBox.vue";
import {
    ShoppingCartIcon,
    CalendarIcon,
    SparklesIcon,
    GlobeAltIcon,
    LightBulbIcon,
    StarIcon,
    EnvelopeIcon
} from '@heroicons/vue/20/solid'
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/swiper-bundle.css';
import {Autoplay} from "swiper/modules";

const features = [
    {
        title: 'Send Invites',
        description: 'Easily invite your friends and family to your event and allow them to sign up seamlessly.',
        icon: EnvelopeIcon,
        comingSoon: false,
    },
    {
        title: 'Shopping List',
        description: 'Create a list of items that people need to bring to the event.',
        icon: ShoppingCartIcon,
        comingSoon: true,
    },
    {
        title: 'Date Picker',
        description: 'Easily choose a date and time for your event when it is not fixed.',
        icon: CalendarIcon,
        comingSoon: true,
    },
    {
        title: 'Activity Randomizer',
        description: 'Use AI integration to create a fun and engaging schedule for the event.',
        icon: SparklesIcon,
        comingSoon: true,
    },
    {
        title: 'Custom Event Invite Template',
        description: 'Design and send personalized event invitations with ease.',
        icon: StarIcon,
        comingSoon: true,
    },
    {
        title: 'Widget for Your Website',
        description: 'Integrate an event widget into your website for better engagement.',
        icon: GlobeAltIcon,
        comingSoon: true,
    },
    {
        title: 'Interactive Polls',
        description: 'Create interactive polls for attendees to vote on event activities or options.',
        icon: LightBulbIcon,
        comingSoon: true,
    },
];
</script>

<template>
    <div class="py-10 md:py-16 px-6 lg:px-24 flex flex-col items-center justify-center bg-gradient-to-b from-blue-50 to-purple-50">
        <h1 class="text-2xl md:text-4xl font-bold pb-8 text-gray-900">
            Features
        </h1>

        <div class="w-full md:hidden">
            <Swiper
                :modules="[Autoplay]"
                :slides-per-view="1"
                :space-between="16"
                :loop="true"
                :autoplay="{ delay: 5000, disableOnInteraction: false }"
                class="w-full"
            >
                <SwiperSlide v-for="(feature, index) in features" :key="index">
                    <FeatureBox
                        :icon="feature.icon"
                        :title="feature.title"
                        :description="feature.description"
                        :coming-soon="feature.comingSoon"
                    />
                </SwiperSlide>
            </Swiper>
        </div>

        <div class="hidden md:grid grid-cols-1 xl:grid-cols-2 2xl:grid-cols-3 auto-cols-max gap-8 md:px-16 xl:px-20 2xl:px-32 w-full">
            <FeatureBox
                v-for="(feature, index) in features"
                :key="index"
                :icon="feature.icon"
                :title="feature.title"
                :description="feature.description"
                :coming-soon="feature.comingSoon"
            />
        </div>
    </div>
</template>
