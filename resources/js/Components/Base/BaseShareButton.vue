<script setup>
import {ref} from "vue";

const copied = ref(false);

const props = defineProps({
    shareUrl : {
        type: String,
        default: window.location.href
    },
    shareTitle : {
        type: String,
        default: "You're Invited!"
    },
    shareText : {
        type: String,
        default: "Join this event on Partydos!"
    }
});

const shareEvent = () => {
    if (navigator.share) {
        navigator.share({
            title: props.shareTitle,
            text: props.shareText,
            url: props.shareUrl,
        }).catch(console.error);
    } else {
        copyToClipboard();
    }
};

const copyToClipboard = () => {
    navigator.clipboard.writeText(props.shareUrl).then(() => {
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
            <slot/>
        </button>

        <span v-if="copied" class="text-green-600">Link copied!</span>
    </div>
</template>
