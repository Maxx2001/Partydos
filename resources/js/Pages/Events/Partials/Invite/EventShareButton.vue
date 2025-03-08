<script setup>
import BaseShareButton from "@/Components/Base/BaseShareButton.vue";
import { computed } from "vue";

const props = defineProps({
    event: {
        type: Object,
        required: true
    },
    buttonClasses: {
        type: String,
        default: ''
    }
});

const eventDescription = computed(() => {
    let description = `Je bent uitgenodigd voor ${props.event.title}`;

    if (props.event.description) {
        description = `${description}\n\n${props.event.description}`;
    }

    if (props.event?.address?.address) {
        const dashes = '-'.repeat(40);
        description = `${description}\n${dashes}\n${props.event.address.address}\n`;
    }

    return description;
});
</script>

<template>
    <BaseShareButton
        :share-text="eventDescription"
        :share-url="event.shareLink"
    >
        <span :class="buttonClasses">
            Share Event
        </span>

    </BaseShareButton>
</template>
