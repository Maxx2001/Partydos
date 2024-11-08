<script setup>
import heroImage from "@/Assets/heroImage.webp";
import "aos/dist/aos.css";
import { computed, onMounted } from "vue";
import BaseOutlineButton from "@/Components/Base/BaseOutlineButton.vue";
import AOS from "aos";
import BaseButton from "@/Components/Base/BaseButton.vue";

const props = defineProps({
    event: {
        type: Object,
        required: true,
    },
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
onMounted(() => {
    AOS.refresh();
});
</script>

<template>
    <div class="flex items-center justify-center bg-slate-100 py-12">
        <div class="max-w-6xl flex flex-row w-full">
            <div class="w-1/2 flex items-center justify-center">
                <div
                    class="h-[380px] w-full bg-gray-200 rounded-l-lg overflow-hidden flex items-center justify-center shadow-lg"
                    style="aspect-ratio: 4 / 3;"
                >
                    <img
                        :src="heroImage"
                        alt="Event Planning Illustration"
                        class="w-full h-full object-cover"
                        data-aos="fade-in"
                        data-aos-duration="1000"
                    />
                </div>
            </div>
            <div
                class="w-1/2 h-[380px] bg-gradient-to-br from-blue-500  rounded-r-lg to-purple-600 text-white font-extrabold leading-tight text-2xl flex flex-col items-center justify-center"
            >
                <div class="flex flex-col pb-10 px-8">
                    <p>
                        Location:
                        <span class="ml-4 text-yellow-400">
                            {{ event.location || 'No event location set.' }}
                        </span>
                    </p>
                    <p class="pt-3">
                        Event starts at:
                        <span class="ml-4 text-pink-300">
                            {{ formattedStartDate }}
                        </span>
                    </p>
                </div>
                <div class="flex justify-center">
                    <BaseButton
                        label="Join event!"
                        @click="emits('acceptEventInvite')"
                        class="h-12"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
