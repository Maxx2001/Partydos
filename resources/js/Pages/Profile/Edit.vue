<script setup>
import { useForm } from '@inertiajs/vue3';
import DefaultLayout from '@/Layouts/DefaultLayout.vue';
import PageHeader from '@/Components/Layout/PageHeader.vue';
import PageContainer from '@/Components/Layout/PageContainer.vue';
import ProfilePicture from '@/Components/Profile/ProfilePicture.vue';
import InterestChipsInput from '@/Components/Profile/InterestChipsInput.vue';
import PhotoGrid from '@/Components/Profile/PhotoGrid.vue';
import { useTitle } from '@/Composables/useTitle.js';

const props = defineProps({
    profile: {
        type: Object,
        required: true,
    },
    user: {
        type: Object,
        required: true,
    },
    photos: {
        type: Array,
        default: () => [],
    },
    privacyOptions: {
        type: Object,
        required: true,
    },
});

useTitle('Profile Settings | Partydos');

const form = useForm({
    display_name: props.profile.display_name,
    tagline: props.profile.tagline ?? '',
    bio: props.profile.bio ?? '',
    city: props.profile.city ?? '',
    birthdate: props.profile.birthdate ?? '',
    website_url: props.profile.website_url ?? '',
    instagram_url: props.profile.instagram_url ?? '',
    tiktok_url: props.profile.tiktok_url ?? '',
    spotify_url: props.profile.spotify_url ?? '',
    interests: props.profile.interests ?? [],
    favorite_music: props.profile.favorite_music ?? [],
    party_style_tags: props.profile.party_style_tags ?? [],
    privacy_profile: props.profile.privacy_profile,
    privacy_guestbook: props.profile.privacy_guestbook,
    privacy_photos: props.profile.privacy_photos,
    profile_photo: null,
});

const uploadForm = useForm({
    photo: null,
    caption: '',
});

const handleAvatarUpload = (file) => {
    form.profile_photo = file;
};

const handleGalleryUpload = (event) => {
    const file = event.target.files[0];
    if (!file) return;
    uploadForm.photo = file;
};

const submitProfile = () => {
    form.put(route('profile.update'), {
        preserveScroll: true,
    });
};

const submitPhoto = () => {
    uploadForm.post(route('profile.photos.store'), {
        preserveScroll: true,
        onSuccess: () => uploadForm.reset('photo', 'caption'),
    });
};
</script>

<template>
    <DefaultLayout>
        <PageHeader>
            Your Profile Expansion
        </PageHeader>
        <PageContainer>
            <div class="grid gap-6 lg:grid-cols-[2fr_1fr]">
                <form class="space-y-6 rounded-3xl bg-white p-6 shadow" @submit.prevent="submitProfile">
                    <div class="flex flex-col items-center gap-4">
                        <ProfilePicture
                            :image-url="user.profile_photo_url"
                            :can-edit="true"
                            image-size="h-28 w-28"
                            @fileUploaded="handleAvatarUpload"
                        />
                        <p class="text-xs text-gray-500">Tap to update your avatar.</p>
                    </div>

                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Basics</h2>
                        <div class="mt-4 grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="text-sm font-semibold text-gray-700">Display name</label>
                                <input v-model="form.display_name" type="text" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" />
                                <p v-if="form.errors.display_name" class="text-xs text-red-500">{{ form.errors.display_name }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-gray-700">City</label>
                                <input v-model="form.city" type="text" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" />
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-sm font-semibold text-gray-700">Tagline</label>
                                <input v-model="form.tagline" type="text" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" />
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-sm font-semibold text-gray-700">Bio</label>
                                <textarea v-model="form.bio" rows="4" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" />
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-gray-700">Birthdate</label>
                                <input v-model="form.birthdate" type="date" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2" />
                            </div>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Links</h2>
                        <div class="mt-4 grid gap-4">
                            <input v-model="form.website_url" type="url" placeholder="Website" class="w-full rounded-lg border border-gray-200 px-3 py-2" />
                            <input v-model="form.instagram_url" type="url" placeholder="Instagram" class="w-full rounded-lg border border-gray-200 px-3 py-2" />
                            <input v-model="form.tiktok_url" type="url" placeholder="TikTok" class="w-full rounded-lg border border-gray-200 px-3 py-2" />
                            <input v-model="form.spotify_url" type="url" placeholder="Spotify" class="w-full rounded-lg border border-gray-200 px-3 py-2" />
                        </div>
                    </div>

                    <div class="space-y-4">
                        <InterestChipsInput v-model="form.interests" label="Interests" helper="Up to 15. Press enter to add." />
                        <InterestChipsInput v-model="form.favorite_music" label="Favorite music" helper="Share artists, playlists, or vibes." />
                        <InterestChipsInput v-model="form.party_style_tags" label="Party style tags" helper="Pre-drinks, techno, board games..." />
                    </div>

                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Privacy</h2>
                        <div class="mt-4 grid gap-4 md:grid-cols-3">
                            <div>
                                <label class="text-sm font-semibold text-gray-700">Profile</label>
                                <select v-model="form.privacy_profile" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2">
                                    <option v-for="(label, value) in privacyOptions" :key="value" :value="value">{{ label }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-gray-700">Guestbook</label>
                                <select v-model="form.privacy_guestbook" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2">
                                    <option v-for="(label, value) in privacyOptions" :key="value" :value="value">{{ label }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-gray-700">Photos</label>
                                <select v-model="form.privacy_photos" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2">
                                    <option v-for="(label, value) in privacyOptions" :key="value" :value="value">{{ label }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-full bg-yellow-500 px-4 py-3 text-sm font-semibold text-white hover:bg-yellow-400"
                        :disabled="form.processing"
                    >
                        Save profile
                    </button>
                </form>

                <div class="space-y-6">
                    <div class="rounded-3xl bg-white p-6 shadow">
                        <h2 class="text-lg font-semibold text-gray-800">Photo gallery</h2>
                        <p class="mt-2 text-sm text-gray-500">Upload vibe shots for your Hyves wall.</p>
                        <div class="mt-4 space-y-3">
                            <input type="file" accept="image/*" @change="handleGalleryUpload" />
                            <input v-model="uploadForm.caption" type="text" placeholder="Caption" class="w-full rounded-lg border border-gray-200 px-3 py-2" />
                            <button
                                type="button"
                                class="rounded-full bg-green-500 px-4 py-2 text-sm font-semibold text-white hover:bg-green-400"
                                :disabled="uploadForm.processing || !uploadForm.photo"
                                @click="submitPhoto"
                            >
                                Upload photo
                            </button>
                            <p v-if="uploadForm.errors.photo" class="text-xs text-red-500">{{ uploadForm.errors.photo }}</p>
                        </div>
                    </div>
                    <div class="rounded-3xl bg-white p-6 shadow">
                        <h2 class="text-lg font-semibold text-gray-800">Your gallery</h2>
                        <div class="mt-4">
                            <PhotoGrid :photos="photos" :can-manage="true" />
                            <p v-if="!photos.length" class="text-sm text-gray-500">No photos yet. Upload your first!</p>
                        </div>
                    </div>
                </div>
            </div>
        </PageContainer>
    </DefaultLayout>
</template>
