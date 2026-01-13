<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import { Link, useForm } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import { useTitle } from "@/Composables/useTitle.js";
import PollOptionRow from "@/Pages/Polls/Partials/PollOptionRow.vue";
import PollResults from "@/Pages/Polls/Partials/PollResults.vue";
import AddPollOptionForm from "@/Pages/Polls/Partials/AddPollOptionForm.vue";

const props = defineProps({
    event: {
        type: Object,
        required: true,
    },
    poll: {
        type: Object,
        required: true,
    },
    options: {
        type: Array,
        default: () => [],
    },
    selectedOptionIds: {
        type: Array,
        default: () => [],
    },
    canManage: {
        type: Boolean,
        default: false,
    },
    canVote: {
        type: Boolean,
        default: false,
    },
    canAddOption: {
        type: Boolean,
        default: false,
    },
});

useTitle(`${props.poll.question} | Polls`);

const selectedOptionId = ref(props.selectedOptionIds[0] ?? null);
const selectedOptionIds = ref([...props.selectedOptionIds]);

const voteForm = useForm({
    option_id: selectedOptionId.value,
    option_ids: selectedOptionIds.value,
});

const updateSelection = (value) => {
    if (props.poll.voteMode === "multiple") {
        selectedOptionIds.value = value;
        return;
    }

    selectedOptionId.value = value;
};

const submitVote = () => {
    if (props.poll.voteMode === "multiple") {
        voteForm.option_ids = selectedOptionIds.value;
    } else {
        voteForm.option_id = selectedOptionId.value;
    }

    voteForm.post(route("events.polls.vote", { event: props.event.id, poll: props.poll.id }), {
        preserveScroll: true,
    });
};

const selectedOptions = computed(() => {
    const ids = props.poll.voteMode === "multiple" ? selectedOptionIds.value : [selectedOptionId.value];
    return props.options.filter((option) => ids.includes(option.id));
});

const showEdit = ref(false);

const editForm = useForm({
    question: props.poll.question,
    description: props.poll.description || "",
    vote_mode: props.poll.voteMode,
    guests_can_add_options: props.poll.guestsCanAddOptions,
});

const submitEdit = () => {
    editForm.patch(route("events.polls.update", { event: props.event.id, poll: props.poll.id }), {
        preserveScroll: true,
        onSuccess: () => {
            showEdit.value = false;
        },
    });
};

const closePoll = () => {
    voteForm.post(route("events.polls.close", { event: props.event.id, poll: props.poll.id }), {
        preserveScroll: true,
    });
};

const reopenPoll = () => {
    voteForm.post(route("events.polls.reopen", { event: props.event.id, poll: props.poll.id }), {
        preserveScroll: true,
    });
};
</script>

<template>
    <DefaultLayout>
        <div class="bg-slate-100 min-h-screen py-10 px-6">
            <div class="max-w-4xl mx-auto space-y-6">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <Link
                            :href="route('events.polls.index', event.id)"
                            class="text-sm text-blue-700 font-semibold hover:text-blue-800"
                        >
                            ← Back to polls
                        </Link>
                        <h1 class="text-3xl font-bold text-slate-900 mt-2">{{ poll.question }}</h1>
                        <p v-if="poll.description" class="text-sm text-slate-600 mt-1">{{ poll.description }}</p>
                        <div class="flex flex-wrap gap-2 mt-3 text-xs font-semibold text-slate-600">
                            <span class="px-2 py-1 rounded-full bg-slate-100 capitalize">{{ poll.status }}</span>
                            <span class="px-2 py-1 rounded-full bg-slate-100 capitalize">{{ poll.voteMode }}</span>
                            <span
                                v-if="poll.guestsCanAddOptions"
                                class="px-2 py-1 rounded-full bg-slate-100"
                            >
                                Guests can add options
                            </span>
                        </div>
                    </div>
                    <div v-if="canManage" class="flex flex-wrap gap-3">
                        <button
                            v-if="poll.status === 'open'"
                            type="button"
                            @click="closePoll"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-md bg-slate-900 text-white font-semibold hover:bg-slate-800 transition"
                        >
                            Close poll
                        </button>
                        <button
                            v-else
                            type="button"
                            @click="reopenPoll"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-md bg-blue-700 text-white font-semibold hover:bg-blue-800 transition"
                        >
                            Reopen poll
                        </button>
                        <button
                            type="button"
                            @click="showEdit = !showEdit"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-md border border-slate-300 text-slate-700 font-semibold hover:bg-white transition"
                        >
                            {{ showEdit ? 'Cancel edit' : 'Edit poll' }}
                        </button>
                    </div>
                </div>

                <div v-if="showEdit && canManage" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-slate-900">Edit poll settings</h2>
                    <form @submit.prevent="submitEdit" class="mt-4 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Question</label>
                            <input
                                v-model="editForm.question"
                                type="text"
                                class="mt-2 w-full rounded-md border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                                maxlength="160"
                            />
                            <p v-if="editForm.errors.question" class="text-sm text-red-600 mt-1">{{ editForm.errors.question }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Description</label>
                            <textarea
                                v-model="editForm.description"
                                class="mt-2 w-full rounded-md border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                                rows="3"
                                maxlength="240"
                            ></textarea>
                            <p v-if="editForm.errors.description" class="text-sm text-red-600 mt-1">{{ editForm.errors.description }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700">Vote mode</label>
                            <div class="mt-2 space-y-2">
                                <label class="flex items-center gap-2 text-sm text-slate-600">
                                    <input v-model="editForm.vote_mode" type="radio" value="single" class="text-blue-600" />
                                    Single choice
                                </label>
                                <label class="flex items-center gap-2 text-sm text-slate-600">
                                    <input v-model="editForm.vote_mode" type="radio" value="multiple" class="text-blue-600" />
                                    Multiple choice
                                </label>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <input
                                id="edit-guests"
                                v-model="editForm.guests_can_add_options"
                                type="checkbox"
                                class="rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                            />
                            <label for="edit-guests" class="text-sm text-slate-600">Allow guests to add options</label>
                        </div>
                        <div class="flex justify-end">
                            <button
                                type="submit"
                                class="inline-flex items-center justify-center px-4 py-2 rounded-md bg-blue-700 text-white font-semibold hover:bg-blue-800 transition"
                                :disabled="editForm.processing"
                            >
                                Save changes
                            </button>
                        </div>
                    </form>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-slate-900">Vote</h2>
                    <p v-if="!canVote" class="text-sm text-slate-500 mt-2">Voting is closed.</p>
                    <div class="mt-4 space-y-3">
                        <PollOptionRow
                            v-for="option in options"
                            :key="option.id"
                            :option="option"
                            :vote-mode="poll.voteMode"
                            :model-value="poll.voteMode === 'multiple' ? selectedOptionIds : selectedOptionId"
                            :disabled="!canVote"
                            @update:modelValue="updateSelection"
                        />
                    </div>
                    <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
                        <div v-if="selectedOptions.length" class="text-sm text-slate-600">
                            You voted for:
                            <span class="font-semibold text-slate-800">
                                {{ selectedOptions.map((option) => option.text).join(', ') }}
                            </span>
                        </div>
                        <button
                            type="button"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-md bg-blue-700 text-white font-semibold hover:bg-blue-800 transition"
                            :disabled="!canVote || voteForm.processing"
                            @click="submitVote"
                        >
                            Submit vote
                        </button>
                    </div>
                </div>

                <div v-if="canAddOption" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <AddPollOptionForm :event-id="event.id" :poll-id="poll.id" />
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-slate-900">Results</h2>
                        <span class="text-sm text-slate-500">{{ options.length }} options</span>
                    </div>
                    <div class="mt-4">
                        <PollResults :options="options" :show-voters="canManage" />
                    </div>
                </div>
            </div>
        </div>
    </DefaultLayout>
</template>
