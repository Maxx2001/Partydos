<script setup>
import { computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

const props = defineProps({
    profileUser: {
        type: Object,
        required: true,
    },
    friendship: {
        type: Object,
        required: true,
    },
    permissions: {
        type: Object,
        required: true,
    },
});

const authUser = computed(() => usePage().props.auth?.user);

const sendRequest = () => router.post(route('friendships.request', props.profileUser.id));
const acceptRequest = () => router.post(route('friendships.accept', props.friendship.id));
const cancelRequest = () => router.delete(route('friendships.cancel', props.friendship.id));
const blockRequest = () => router.post(route('friendships.block', props.friendship.id));
</script>

<template>
    <div class="bg-white rounded-3xl shadow-lg p-6 flex flex-col md:flex-row gap-6 items-center">
        <img
            :src="profileUser.avatar"
            :alt="profileUser.display_name"
            class="h-28 w-28 rounded-full object-cover ring-4 ring-yellow-200"
        />
        <div class="flex-1">
            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-2xl font-bold text-gray-900">{{ profileUser.display_name }}</h1>
                <span class="text-xs font-semibold uppercase tracking-wide text-gray-400">Profile v1</span>
            </div>
            <p v-if="profileUser.tagline" class="mt-2 text-gray-600 italic">“{{ profileUser.tagline }}”</p>
            <div class="mt-4 flex flex-wrap items-center gap-3 text-sm text-gray-500">
                <span v-if="profileUser.city">📍 {{ profileUser.city }}</span>
                <span v-if="profileUser.birthdate">🎂 {{ profileUser.birthdate }}</span>
            </div>
        </div>
        <div v-if="authUser && !permissions.isOwner" class="flex flex-col gap-2 min-w-[180px]">
            <button
                v-if="friendship.status === 'none'"
                class="w-full rounded-full bg-yellow-500 px-4 py-2 text-sm font-semibold text-white hover:bg-yellow-400"
                type="button"
                @click="sendRequest"
            >
                Add friend
            </button>
            <button
                v-else-if="friendship.status === 'pending' && friendship.is_requester"
                class="w-full rounded-full bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-600"
                type="button"
                @click="cancelRequest"
            >
                Cancel request
            </button>
            <div v-else-if="friendship.status === 'pending' && friendship.is_addressee" class="flex flex-col gap-2">
                <button
                    class="w-full rounded-full bg-green-500 px-4 py-2 text-sm font-semibold text-white hover:bg-green-400"
                    type="button"
                    @click="acceptRequest"
                >
                    Accept
                </button>
                <button
                    class="w-full rounded-full bg-red-100 px-4 py-2 text-sm font-semibold text-red-600"
                    type="button"
                    @click="blockRequest"
                >
                    Block
                </button>
            </div>
            <span v-else-if="friendship.status === 'accepted'" class="rounded-full bg-green-100 px-4 py-2 text-center text-sm font-semibold text-green-700">
                Friends
            </span>
            <span v-else-if="friendship.status === 'blocked'" class="rounded-full bg-red-100 px-4 py-2 text-center text-sm font-semibold text-red-700">
                Blocked
            </span>
        </div>
    </div>
</template>
