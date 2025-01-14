<script setup>
import {ref, watch} from 'vue';

const props = defineProps({
    initialImage: {
        type: String,
        default: null,
    },
});

const initialImage = ref(props.initialImage);

const file = ref(null);
const fileInput = ref(null);
const previewUrl = ref(null);

const emit = defineEmits(['fileUploaded', 'clearImage']);

watch(() => props.initialImage, (newImage) => {
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
    if (file.value) {
        previewUrl.value = URL.createObjectURL(file.value);
        emit('fileUploaded', file.value);
    }
};

const handleDrop = (event) => {
    const droppedFile = event.dataTransfer.files[0];
    if (droppedFile && droppedFile.size > 5* 1024 * 1024) {
        alert('File size exceeds 5MB. Please choose a smaller file.');
        return;
    }
    if (droppedFile) {
        file.value = droppedFile;
        previewUrl.value = URL.createObjectURL(droppedFile);
        emit('fileUploaded', file.value);
    }
};



const clearImage = () => {
    file.value = null;
    previewUrl.value = null;
    emit('clearImage');
};
const handleSubmit = () => {
    if (file.value) {
        emit('fileUploaded', file.value);
    }
};
</script>

<template>
    <div class="w-full bg-gray-50 flex items-center justify-center pb-4 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="pt-5 text-center text-xl font-extrabold text-gray-900">
                    {{ file ? 'Update Your Banner image' : 'Upload Your Banner image' }}
                </h2>
            </div>
            <form @submit.prevent="handleSubmit">
                <div class="rounded-md -space-y-px">
                    <div
                        class="flex items-center justify-center w-full border-2 border-dashed border-blue-400 rounded-lg p-3 bg-white hover:border-blue-500 cursor-pointer"
                        @click="triggerFileInput"
                        @dragover.prevent
                        @drop.prevent="handleDrop"
                    >
                        <p v-if="!file && !initialImage" class="text-gray-400">Drop your file here or click to
                            upload</p>
                        <p v-else-if="file" class="text-gray-600">{{ file.name }}</p>
                        <p v-else class="text-gray-400">Click to upload or drop a new image</p>
                    </div>
                    <p class="text-bold pt-2 text-gray-700 text-sm">
                        This image will be used as the
                        <span class="text-blue-600 text-bold">
                            banner image
                        </span> for your event.
                    </p>
                    <input
                        ref="fileInput"
                        type="file"
                        class="hidden"
                        accept="image/*"
                        @change="handleFileChange"
                    />
                </div>
                <p class="text-gray-500 w-full text-center italic underline mt-1">
                    Images up to 5MB
                </p>
                <div v-if="previewUrl || initialImage" class="mt-4 flex flex-col items-center">
                    <img
                        :src="previewUrl || initialImage"
                        alt="File preview"
                        class="max-w-full h-32 object-contain rounded cursor-pointer"
                        @click="triggerFileInput"
                    />
                    <button
                        type="button"
                        class="mt-2 text-sm text-red-500 underline"
                        @click="clearImage"
                    >
                        Remove Image
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
