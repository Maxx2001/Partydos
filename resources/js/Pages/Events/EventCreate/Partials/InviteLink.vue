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
    <div class="gird flex-row lg:grid lg:pt-2" style="grid-template-columns: repeat(14, minmax(0, 1fr))">
          <div
              ref="shareLinkInput"
              type="text"
              @click="copyToClipboard"
              class="w-full border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 col-span-10"
          >
            {{ event.shareLink }}
        </div>

        <BaseButton
            class="lg:mt-2 mt-3 xl:mt-0 xl:ml-2 col-span-4 w-full"
            label="Copy link"
            :icon="ClipboardDocumentIcon"
            @click="copyToClipboard"
        />
    </div>
    <div class="flex flex-col w-full lg:-mb-10 pt-2 py-0.5 lg:text-lg underline italic text-slate-600">
        Copy and share the link with your friends!
    </div>
</template>
