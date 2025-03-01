<script setup>
import ProfilePicture from "@/Components/Profile/ProfilePicture.vue";

const props = defineProps({
    participant: { type: Object, required: true },
    bgColor: { type: String, required: true }
});
</script>

<template>
    <li class="flex items-center flex-col" v-if="participant.profile_photo_url">
        <ProfilePicture
            class="h-16 w-16"
            :image-url="participant.profile_photo_url"
        />
        <span class="w-full text-center pt-1 line-clamp-2">{{ participant.name }}</span>
    </li>
    <li class="flex items-center flex-col" v-else>
        <div :class="['h-16 w-16 rounded-full', bgColor]"></div>
        <div :class="bgColor"></div>
        <span class="w-full text-center pt-1 line-clamp-2">{{ participant.name }}</span>
    </li>
</template>
