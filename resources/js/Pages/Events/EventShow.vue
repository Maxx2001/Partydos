<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import { ref } from "vue";
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
        await navigator.clipboard.writeText(props.event.share_link);
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
    <DefaultLayout>
        <div class="py-24 px-6 flex flex-col items-center justify-center bg-slate-100 rounded">
            <div class="w-full flex justify-center text-2xl font-semibold">
                <h1>
                    Invite the people!
                </h1>
            </div>
            <div class="flex flex-col items-center w-full ">
                Copy the link below and share it with your friends!
            </div>
            <div class="flex w-1/3 pt-2">
                <input
                    ref="shareLinkInput"
                    type="text"
                    :value="event.share_link"
                    disabled
                    @click="copyToClipboard"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                />
                <BaseButton
                    class="w-1/4 ml-2"
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
    </DefaultLayout>
</template>
