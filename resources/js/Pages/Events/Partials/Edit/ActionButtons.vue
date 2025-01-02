<script setup>
import BaseOutlineButton from '@/Components/Base/BaseOutlineButton.vue';
import BaseButton from '@/Components/Base/BaseButton.vue';
import CancelEventModal from "@/Pages/Events/Partials/Edit/CancelEventModal.vue";
import {ref} from "vue";
import RestoreEventModal from "@/Pages/Events/Partials/Edit/RestoreEventModal.vue";
import DeleteEventModal from "@/Pages/Events/Partials/Edit/DeleteEventModal.vue";

const props = defineProps({
    onCancelEvent: {
        type: Function,
        required: true
    },
    onCancel: {
        type: Function,
        required: true
    },
    onUpdate: {
        type: Function,
        required: true
    },
    event:{
        type: Object,
        required: true
    }
});

const cancelEventModal = ref('cancelEventModal');
const restoreEventModal = ref('restoreEventModal');
const deleteEventModal = ref('deleteEventModal');
</script>

<template>
    <div class="w-full grid grid-cols-2 lg:grid-cols-3 gap-y-2 mt-4 px-4 lg:px-0">
        <BaseOutlineButton
            v-if="!event.canceledAt"
            label="Cancel Event"
            variant="cancel"
            class="mr-auto"
            @click="cancelEventModal.openModal()"
        />
        <BaseOutlineButton
            v-if="event.canceledAt"
            label="Restore Event"
            class="mr-auto"
            @click="restoreEventModal.openModal()"
        />
        <BaseOutlineButton
            class="hidden lg:block lg:mr-4"
            label="Cancel"
            @click="onCancel"
        />
        <BaseButton
            class="lg:hidden"
            label="Update"
            @click="onUpdate"
        />
        <BaseButton
            class="hidden lg:block"
            label="Update event"
            @click="onUpdate"
        />
        <BaseOutlineButton
            v-if="event.canceledAt"
            label="Delete Event"
            variant="cancel"
            class="mr-auto"
            @click="deleteEventModal.openModal()"
        />
    </div>

    <CancelEventModal
        ref="cancelEventModal"
        :event="event"
    />

    <RestoreEventModal
        ref="restoreEventModal"
        :event="event"
    />

    <DeleteEventModal
        ref="deleteEventModal"
        :event="event"
    />
</template>
