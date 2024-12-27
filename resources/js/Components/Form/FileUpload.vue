<template>
    <div class="w-full bg-gray-50 flex items-center justify-center pb-4 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="pt-5 text-center text-xl font-extrabold text-gray-900">
                    Upload Your File
                </h2>
            </div>
            <form @submit.prevent="handleSubmit">
                <div class="rounded-md shadow-sm -space-y-px">
                    <div
                        class="flex items-center justify-center w-full border-2 border-dashed border-gray-300 rounded-lg p-3 bg-white hover:border-blue-500 cursor-pointer"
                        @click="triggerFileInput"
                        @dragover.prevent
                        @drop.prevent="handleDrop"
                    >
                        <p v-if="!file" class="text-gray-400">Drop your file here or click to upload</p>
                        <p v-else class="text-gray-600">{{ file.name }}</p>
                    </div>
                    <input
                        ref="fileInput"
                        type="file"
                        class="hidden"
                        accept="image/*"
                        @change="handleFileChange"
                    />
                </div>
                <div v-if="file" class="mt-4 flex justify-center">
                    <img :src="previewUrl" alt="File preview" class="max-w-full h-32 object-contain rounded" />
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const file = ref(null);
const fileInput = ref(null);
const previewUrl = ref(null);

const emit = defineEmits(['fileUploaded']);

const triggerFileInput = () => {
    fileInput.value.click();
};

const handleFileChange = (event) => {
    file.value = event.target.files[0];
    if (file.value) {
        previewUrl.value = URL.createObjectURL(file.value);
        emit('fileUploaded', file.value);
    }
};

const handleDrop = (event) => {
    const droppedFile = event.dataTransfer.files[0];
    if (droppedFile) {
        file.value = droppedFile;
        previewUrl.value = URL.createObjectURL(droppedFile);
        emit('fileUploaded', file.value);
    }
};

const handleSubmit = () => {
    if (file.value) {
        emit('fileUploaded', file.value);
    }
};
</script>
