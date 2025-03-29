<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue';
import PollDatePicker from '@/Pages/DatePoll/Partials/PollDatePicker.vue';
import PollDateList from '@/Pages/DatePoll/Partials/PollDateList.vue';
import BaseButton from '@/Components/Base/BaseButton.vue';
import { ref, reactive } from 'vue';
import { router } from '@inertiajs/vue3';
import { useTitle } from '@/Composables/useTitle.js';

useTitle('Create Date Poll | Partydos');

const form = reactive({
    title: '',
    description: '',
    options: [], // Hier slaan we de datums en optionele tijden op
});

const addDateOption = (option) => {
    form.options.push(option);
};

const removeDateOption = (index) => {
    form.options.splice(index, 1);
};

const submitPoll = () => {
    router.post(route('date-polls.store'), form);
};
</script>

<template>
    <DefaultLayout>
        <div class="py-8 md:py-24 px-6 flex flex-col items-center justify-center bg-slate-100 rounded">
            <h1 class="text-2xl md:text-4xl font-bold mb-6">Create Your Date Poll</h1>

            <div class="w-full max-w-2xl space-y-6">
                <!-- Titel en beschrijving -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Poll Title</label>
                    <input v-model="form.title" type="text" placeholder="E.g. Team BBQ Planning"
                           class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-300" />

                    <label class="block text-sm font-medium text-gray-700 mt-4 mb-1">Description</label>
                    <textarea v-model="form.description" rows="3" placeholder="Optional details..."
                              class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-300"></textarea>
                </div>

                <!-- Datum toevoeger -->
                <PollDatePicker @addDateOption="addDateOption" />

                <!-- Lijst van toegevoegde opties -->
                <PollDateList :options="form.options" @removeOption="removeDateOption" />

                <!-- Submit knop -->
                <div class="flex justify-end">
                    <BaseButton label="Create Poll" @click="submitPoll" :disabled="form.options.length === 0" />
                </div>
            </div>
        </div>
    </DefaultLayout>
</template>
