<script setup>
import { router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    entries: {
        type: Array,
        default: () => [],
    },
    emptyText: {
        type: String,
        default: 'No krabbels yet. Be the first!',
    },
});

const authUser = computed(() => usePage().props.auth?.user);

const deleteEntry = (entryId) => {
    router.delete(route('guestbook.destroy', entryId), { preserveScroll: true });
};
</script>

<template>
    <div class="space-y-4">
        <div
            v-for="entry in entries"
            :key="entry.id"
            class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm"
        >
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img :src="entry.author.avatar" alt="" class="h-10 w-10 rounded-full object-cover" />
                    <div>
                        <p class="font-semibold text-gray-800">{{ entry.author.display_name }}</p>
                        <p class="text-xs text-gray-400">{{ entry.created_at }}</p>
                    </div>
                </div>
                <button
                    v-if="authUser && entry.can_delete"
                    type="button"
                    class="text-xs font-semibold text-red-500 hover:text-red-400"
                    @click="deleteEntry(entry.id)"
                >
                    Delete
                </button>
            </div>
            <p class="mt-3 text-sm text-gray-600">{{ entry.body }}</p>
        </div>
        <p v-if="!entries.length" class="text-sm text-gray-500">{{ emptyText }}</p>
    </div>
</template>
