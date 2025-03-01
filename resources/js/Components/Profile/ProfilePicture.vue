<script setup>
import { ref, watch } from 'vue';
import { PencilSquareIcon } from "@heroicons/vue/20/solid";

const props = defineProps({
    imageUrl: {
        type: String,
        required: true
    },
    canEdit: {
        type: Boolean,
        default: false
    }
});

const previewUrl = ref(props.imageUrl);
const fileInput = ref(null);
const file = ref(null);
const emit = defineEmits(['fileUploaded']);

watch(() => props.imageUrl, (newImage) => {
    if (newImage && !file.value) {
        previewUrl.value = newImage;
    }
});

const triggerFileInput = () => {
    fileInput.value.click();
};

const handleFileChange = (event) => {
    const selectedFile = event.target.files[0];
    if (selectedFile && selectedFile.size > 5 * 1024 * 1024) {
        alert('File size exceeds 5MB. Please choose a smaller file.');
        return;
    }
    file.value = selectedFile;
    previewUrl.value = URL.createObjectURL(selectedFile);
    emit('fileUploaded', selectedFile);
};
</script>

<template>
    <div class="relative inline-block" @click="canEdit ? triggerFileInput() : null">
        <img
            :src="previewUrl"
            alt="Profile Picture"
            class="rounded-full mx-auto cursor-pointer object-cover shadow-md"
        />
        <input
            ref="fileInput"
            type="file"
            class="hidden"
            accept="image/*"
            @change="handleFileChange"
        />
        <PencilSquareIcon
            v-if="canEdit"
            class="h-8 w-8 absolute bottom-2 right-2 bg-white p-1 rounded-full shadow cursor-pointer"
            @click.stop="triggerFileInput"
        />
    </div>
</template>
