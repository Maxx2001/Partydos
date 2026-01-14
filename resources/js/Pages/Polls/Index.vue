<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import { Link } from "@inertiajs/vue3";
import { useTitle } from "@/Composables/useTitle.js";

const props = defineProps({
    event: {
        type: Object,
        required: true,
    },
    polls: {
        type: Array,
        default: () => [],
    },
    canManage: {
        type: Boolean,
        default: false,
    },
});

useTitle(`${props.event.title} Polls | Partydos`);
</script>

<template>
    <DefaultLayout>
        <div class="bg-slate-100 min-h-screen py-10 px-6">
            <div class="max-w-5xl mx-auto">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-wide text-slate-500">Event polls</p>
                        <h1 class="text-3xl font-bold text-slate-900">{{ event.title }}</h1>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <Link
                            v-if="canManage"
                            :href="route('events.polls.create', event.id)"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-md bg-blue-700 text-white font-semibold hover:bg-blue-800 transition"
                        >
                            Create poll
                        </Link>
                        <Link
                            :href="route('events.show-invite', event.uniqueIdentifier)"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-md border border-slate-300 text-slate-700 font-semibold hover:bg-white transition"
                        >
                            Back to event
                        </Link>
                    </div>
                </div>

                <div class="mt-8 grid gap-4">
                    <div
                        v-for="poll in polls"
                        :key="poll.id"
                        class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6"
                    >
                        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-slate-900">{{ poll.question }}</h2>
                                <p v-if="poll.description" class="text-sm text-slate-500 mt-1">{{ poll.description }}</p>
                                <div class="flex flex-wrap gap-2 mt-3 text-xs font-semibold text-slate-600">
                                    <span class="px-2 py-1 rounded-full bg-slate-100 capitalize">{{ poll.status }}</span>
                                    <span class="px-2 py-1 rounded-full bg-slate-100 capitalize">{{ poll.voteMode }}</span>
                                    <span class="px-2 py-1 rounded-full bg-slate-100">{{ poll.votesCount }} votes</span>
                                </div>
                            </div>
                            <Link
                                :href="route('events.polls.show', { event: event.id, poll: poll.id })"
                                class="inline-flex items-center justify-center px-4 py-2 rounded-md bg-slate-900 text-white font-semibold hover:bg-slate-800 transition"
                            >
                                Open
                            </Link>
                        </div>
                    </div>
                    <div v-if="!polls.length" class="bg-white rounded-2xl border border-dashed border-slate-300 p-8 text-center">
                        <p class="text-sm text-slate-500">No polls yet. Create the first one to get input from guests.</p>
                    </div>
                </div>
            </div>
        </div>
    </DefaultLayout>
</template>
