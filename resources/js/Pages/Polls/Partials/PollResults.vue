<script setup>
import { computed, ref } from "vue";

const props = defineProps({
    options: {
        type: Array,
        default: () => [],
    },
    showVoters: {
        type: Boolean,
        default: false,
    },
});

const expandedOptions = ref({});

const totalVotes = computed(() => props.options.reduce((total, option) => total + (option.votesCount || 0), 0));

const toggle = (optionId) => {
    expandedOptions.value = {
        ...expandedOptions.value,
        [optionId]: !expandedOptions.value[optionId],
    };
};

const percentageFor = (count) => {
    if (!totalVotes.value) {
        return 0;
    }

    return Math.round((count / totalVotes.value) * 100);
};
</script>

<template>
    <div class="space-y-4">
        <div v-for="option in options" :key="option.id" class="space-y-2">
            <div class="flex items-center justify-between text-sm text-slate-700">
                <span class="font-medium">{{ option.text }}</span>
                <span>{{ option.votesCount }} votes · {{ percentageFor(option.votesCount) }}%</span>
            </div>
            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                <div
                    class="h-2 bg-blue-600 rounded-full"
                    :style="{ width: `${percentageFor(option.votesCount)}%` }"
                ></div>
            </div>
            <div v-if="showVoters" class="text-sm text-slate-500">
                <button
                    type="button"
                    class="text-xs font-semibold text-blue-700 hover:text-blue-800"
                    @click="toggle(option.id)"
                >
                    {{ expandedOptions[option.id] ? 'Hide voters' : 'Show voters' }}
                </button>
                <ul v-if="expandedOptions[option.id]" class="mt-2 space-y-1">
                    <li v-if="!option.voters.length" class="text-xs text-slate-400">No votes yet.</li>
                    <li v-for="voter in option.voters" :key="voter.id" class="text-xs">
                        {{ voter.name || voter.email }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
