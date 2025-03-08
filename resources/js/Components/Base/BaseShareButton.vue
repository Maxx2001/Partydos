<script setup>
import {ref} from "vue";

const eventUrl = window.location.href;
const copied = ref(false);

const shareEvent = () => {
    if (navigator.share) {
        navigator.share({
            title: "You're Invited!",
            text: "Join this amazing event on Partydos!",
            url: eventUrl,
        }).catch(console.error);
    } else {
        copyToClipboard();
    }
};

const copyToClipboard = () => {
    navigator.clipboard.writeText(eventUrl).then(() => {
        copied.value = true;
        setTimeout(() => copied.value = false, 2000);
    });
};
</script>
<template>
    <div class="flex items-center gap-2">
        <button
            @click="shareEvent"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition"
        >
            Share Event
        </button>

        <span v-if="copied" class="text-green-600">Link copied!</span>
    </div>
</template>
