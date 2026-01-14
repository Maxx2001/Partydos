<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
    photos: {
        type: Array,
        default: () => [],
    },
    canManage: {
        type: Boolean,
        default: false,
    },
});

const deletePhoto = (photoId) => {
    router.delete(route('profile.photos.destroy', photoId), { preserveScroll: true });
};
</script>

<template>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div
            v-for="photo in photos"
            :key="photo.id"
            class="relative overflow-hidden rounded-2xl bg-gray-100 shadow"
        >
            <img :src="photo.url" :alt="photo.caption || 'Profile photo'" class="h-48 w-full object-cover" />
            <div class="absolute inset-0 flex flex-col justify-end bg-gradient-to-t from-black/60 via-black/10 to-transparent p-3">
                <p v-if="photo.caption" class="text-xs text-white">{{ photo.caption }}</p>
                <button
                    v-if="canManage"
                    type="button"
                    class="mt-2 inline-flex w-fit rounded-full bg-white/80 px-3 py-1 text-xs font-semibold text-gray-800"
                    @click="deletePhoto(photo.id)"
                >
                    Remove
                </button>
            </div>
        </div>
    </div>
</template>
