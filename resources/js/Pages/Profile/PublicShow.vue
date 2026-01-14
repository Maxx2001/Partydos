<script setup>
import { computed, ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import DefaultLayout from '@/Layouts/DefaultLayout.vue';
import PageHeader from '@/Components/Layout/PageHeader.vue';
import PageContainer from '@/Components/Layout/PageContainer.vue';
import ProfileHeaderCard from '@/Components/Profile/ProfileHeaderCard.vue';
import GuestbookList from '@/Components/Profile/GuestbookList.vue';
import PhotoGrid from '@/Components/Profile/PhotoGrid.vue';
import PrivacyBadge from '@/Components/Profile/PrivacyBadge.vue';
import { useTitle } from '@/Composables/useTitle.js';

const props = defineProps({
    profileUser: {
        type: Object,
        required: true,
    },
    photos: {
        type: Array,
        default: () => [],
    },
    guestbookEntries: {
        type: Array,
        default: () => [],
    },
    friendship: {
        type: Object,
        required: true,
    },
    stats: {
        type: Object,
        required: true,
    },
    permissions: {
        type: Object,
        required: true,
    },
});

useTitle(`${props.profileUser.display_name} | Partydos`);

const authUser = computed(() => usePage().props.auth?.user);
const activeTab = ref('about');

const guestbookForm = useForm({
    body: '',
});

const submitGuestbook = () => {
    guestbookForm.post(route('guestbook.store', props.profileUser.id), {
        preserveScroll: true,
        onSuccess: () => guestbookForm.reset('body'),
    });
};
</script>

<template>
    <DefaultLayout>
        <PageHeader>
            Hyves-style Profile
        </PageHeader>
        <PageContainer>
            <ProfileHeaderCard
                :profile-user="profileUser"
                :friendship="friendship"
                :permissions="permissions"
            />

            <div class="mt-8 flex flex-wrap gap-3">
                <button
                    v-for="tab in ['about', 'photos', 'guestbook', 'stats']"
                    :key="tab"
                    type="button"
                    class="rounded-full px-4 py-2 text-sm font-semibold capitalize"
                    :class="activeTab === tab ? 'bg-yellow-500 text-white' : 'bg-gray-100 text-gray-600'"
                    @click="activeTab = tab"
                >
                    {{ tab }}
                </button>
            </div>

            <section v-if="activeTab === 'about'" class="mt-8 grid gap-6 lg:grid-cols-[2fr_1fr]">
                <div class="rounded-3xl bg-white p-6 shadow">
                    <h2 class="text-lg font-semibold text-gray-800">About me</h2>
                    <p class="mt-3 text-sm text-gray-600 whitespace-pre-line">
                        {{ profileUser.bio || 'No bio yet — but every legend starts somewhere.' }}
                    </p>
                    <div class="mt-6">
                        <h3 class="text-sm font-semibold text-gray-700">Interests</h3>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <span
                                v-for="interest in profileUser.interests"
                                :key="interest"
                                class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-800"
                            >
                                {{ interest }}
                            </span>
                            <span v-if="!profileUser.interests.length" class="text-xs text-gray-400">No interests yet.</span>
                        </div>
                    </div>
                    <div class="mt-6">
                        <h3 class="text-sm font-semibold text-gray-700">Party style</h3>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <span
                                v-for="tag in profileUser.party_style_tags"
                                :key="tag"
                                class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700"
                            >
                                {{ tag }}
                            </span>
                            <span v-if="!profileUser.party_style_tags.length" class="text-xs text-gray-400">No tags yet.</span>
                        </div>
                    </div>
                    <div class="mt-6">
                        <h3 class="text-sm font-semibold text-gray-700">Favorite music</h3>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <span
                                v-for="song in profileUser.favorite_music"
                                :key="song"
                                class="rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700"
                            >
                                {{ song }}
                            </span>
                            <span v-if="!profileUser.favorite_music.length" class="text-xs text-gray-400">No favorites yet.</span>
                        </div>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="rounded-3xl bg-white p-6 shadow">
                        <h2 class="text-lg font-semibold text-gray-800">Social links</h2>
                        <div class="mt-4 space-y-3 text-sm text-gray-600">
                            <a v-if="profileUser.links.website_url" :href="profileUser.links.website_url" class="block hover:text-yellow-500" target="_blank" rel="noopener">🌐 Website</a>
                            <a v-if="profileUser.links.instagram_url" :href="profileUser.links.instagram_url" class="block hover:text-yellow-500" target="_blank" rel="noopener">📸 Instagram</a>
                            <a v-if="profileUser.links.tiktok_url" :href="profileUser.links.tiktok_url" class="block hover:text-yellow-500" target="_blank" rel="noopener">🎵 TikTok</a>
                            <a v-if="profileUser.links.spotify_url" :href="profileUser.links.spotify_url" class="block hover:text-yellow-500" target="_blank" rel="noopener">🎧 Spotify</a>
                            <p v-if="!profileUser.links.website_url && !profileUser.links.instagram_url && !profileUser.links.tiktok_url && !profileUser.links.spotify_url" class="text-xs text-gray-400">
                                No links yet.
                            </p>
                        </div>
                    </div>
                    <div class="rounded-3xl bg-white p-6 shadow">
                        <h2 class="text-lg font-semibold text-gray-800">Privacy</h2>
                        <div class="mt-4 space-y-3 text-sm text-gray-600">
                            <div class="flex items-center justify-between">
                                <span>Photos</span>
                                <PrivacyBadge :level="profileUser.privacy.photos" />
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Guestbook</span>
                                <PrivacyBadge :level="profileUser.privacy.guestbook" />
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section v-if="activeTab === 'photos'" class="mt-8 rounded-3xl bg-white p-6 shadow">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">Photo gallery</h2>
                    <PrivacyBadge :level="profileUser.privacy.photos" />
                </div>
                <div class="mt-6">
                    <PhotoGrid v-if="permissions.canViewPhotos" :photos="photos" />
                    <p v-else class="text-sm text-gray-500">Photos are private.</p>
                </div>
            </section>

            <section v-if="activeTab === 'guestbook'" class="mt-8 rounded-3xl bg-white p-6 shadow">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">Guestbook</h2>
                    <PrivacyBadge :level="profileUser.privacy.guestbook" />
                </div>
                <div class="mt-6 space-y-6">
                    <div v-if="permissions.canWriteGuestbook" class="rounded-2xl bg-yellow-50 p-4">
                        <h3 class="text-sm font-semibold text-yellow-700">Leave a krabbel</h3>
                        <textarea
                            v-model="guestbookForm.body"
                            rows="3"
                            class="mt-3 w-full rounded-xl border border-yellow-200 bg-white px-3 py-2 text-sm focus:border-yellow-500 focus:ring-yellow-500"
                            placeholder="Say something fun..."
                        />
                        <button
                            type="button"
                            class="mt-3 rounded-full bg-yellow-500 px-4 py-2 text-sm font-semibold text-white"
                            @click="submitGuestbook"
                        >
                            Post krabbel
                        </button>
                        <p v-if="guestbookForm.errors.body" class="mt-2 text-xs text-red-500">{{ guestbookForm.errors.body }}</p>
                    </div>
                    <div v-else-if="!authUser" class="text-sm text-gray-500">
                        Log in to leave a krabbel.
                    </div>
                    <GuestbookList v-if="permissions.canViewGuestbook" :entries="guestbookEntries" />
                    <p v-else class="text-sm text-gray-500">Guestbook is private.</p>
                </div>
            </section>

            <section v-if="activeTab === 'stats'" class="mt-8 rounded-3xl bg-white p-6 shadow">
                <h2 class="text-lg font-semibold text-gray-800">Party stats</h2>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl bg-yellow-50 p-4">
                        <p class="text-sm text-gray-500">Parties hosted</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ stats.events_hosted_count }}</p>
                    </div>
                    <div class="rounded-2xl bg-green-50 p-4">
                        <p class="text-sm text-gray-500">Parties attended</p>
                        <p class="text-2xl font-bold text-green-600">{{ stats.events_attended_count }}</p>
                    </div>
                </div>
                <div class="mt-6 text-sm text-gray-500">
                    Top vibes powered by party style tags. ✨
                </div>
            </section>
        </PageContainer>
    </DefaultLayout>
</template>
