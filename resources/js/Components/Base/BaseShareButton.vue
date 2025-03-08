<script setup>
import {computed, ref} from "vue";

const copied = ref(false);

const props = defineProps({
    shareUrl : {
        type: String,
        default: window.location.href
    },
    shareText : {
        type: String,
        default: "Join this event on Partydos!"
    },
    variant: {
        type: String,
        default: "blue"
    }
});

const shareEvent = () => {
    if (navigator.share) {
        navigator.share({
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

const classes = computed(() => {
    return {
        'bg-indigo-600 text-white px-10 py-3 rounded-lg hover:bg-white hover:text-indigo-600 transition border border-indigo-600 w-full': props.variant === 'indigo',
        'bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition': props.variant === 'blue',
    };
});
</script>
<template>
    <div class="flex items-center gap-2">
        <button
            @click="shareEvent"
            :class="classes"
        >
            <slot/>
        </button>

        <span v-if="copied" class="text-green-600">Link copied!</span>
    </div>
</template>
