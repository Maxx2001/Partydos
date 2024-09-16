<script setup>
import { defineProps, ref } from "vue";
import BaseButton from "@/Components/Base/BaseButton.vue";
import { ClipboardDocumentIcon } from "@heroicons/vue/24/solid/index.js";

const props = defineProps({
    event: {
        type: Object,
        required: true
    },
})

const copied = ref(false);

const copyToClipboard = async() => {
    try {
        await navigator.clipboard.writeText(props.event.shareLink);
        copied.value = true;

        setTimeout(() => {
            copied.value = false;
        }, 2000);
    } catch(err) {
        console.error('Failed to copy: ', err);
    }
}
</script>

<template>
    <div class="py-4 md:px-6 flex flex-col justify-center w-full shadow-lg rounded-lg bg-white">
        <div class="w-full flex justify-center text-2xl font-semibold">
            <h1>
                Share this invite
            </h1>
        </div>
        <div class="flex flex-col items-center w-full px-8 text-base py-0.5 text-gray-600">
            Copy and share the link with your friends!
        </div>
        <div class="flex flex-col xl:flex-row pt-2 px-12">
                <span
                    ref="shareLinkInput"
                    type="text"
                    @click="copyToClipboard"
                    class="w-full border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                    {{ event.shareLink }}
                </span>
            <BaseButton
                class="w-full xl:w-1/4 mt-2 xl:mt-0 xl:ml-2"
                label="Copy link"
                :icon="ClipboardDocumentIcon"
                @click="copyToClipboard"
            />
        </div>

        <span
            v-if="copied"
            class="text-green-500 font-medium"
        >
      Link copied!
    </span>
    </div>
</template>
