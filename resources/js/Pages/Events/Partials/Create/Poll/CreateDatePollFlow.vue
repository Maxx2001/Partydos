<script setup>
import { reactive, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import CreateDatePoll from './CreateDatePoll.vue';
import DatePicker from '../Standard/DatePicker.vue';
import TimePickerModal from '../Standard/TimePickerModal.vue';
import BaseButton from '@/Components/Base/BaseButton.vue';
import CrossIcon from "@/Components/Icons/CrossIcon.vue";

const props = defineProps({
    initialDetails: {
        type: Object,
        required: true,
    }
});

const emit = defineEmits(['returnToDetails']);

const pollForm = reactive({
    title: props.initialDetails.title,
    description: props.initialDetails.description,
    location: props.initialDetails.location,
    options: [],
});

const showDatePickerModal = ref(false);
const showTimePickerModal = ref(false);
const selectedOptionForTime = ref(null);
const selectedIndexForTime = ref(null);

const toggleDatePicker = () => {
    showDatePickerModal.value = !showDatePickerModal.value;
};

const handleAddOrUpdateDateOption = (newOption) => {
    const index = pollForm.options.findIndex(option =>
        option.selectedDate.toDateString() === newOption.selectedDate.toDateString()
    );

    if (index !== -1) {
        pollForm.options.splice(index, 1);
    } else {
        pollForm.options.push(newOption);
    }
};

const handleRemoveDateOption = (index) => {
    pollForm.options.splice(index, 1);
};

const handleCreatePoll = () => {
    if (!pollForm.title) {
        alert('Please ensure the event title is filled in.'); // Better validation UX needed
        return;
    }
    if (pollForm.options.length === 0) {
        alert('Please add at least one date option for the poll.');
        return;
    }

    const preparedOptions = pollForm.options.map(opt => ({
        date: opt.selectedDate.toISOString().split('T')[0],
        start_time: `${opt.selectedHour}:${opt.selectedMinute}`,
        end_time: opt.selectedEndHour ? `${opt.selectedEndHour}:${opt.selectedEndMinute}` : null,
    }));

    const payload = {
        title: pollForm.title,
        description: pollForm.description,
        location_address: pollForm.location?.address,
        location_place_id: pollForm.location?.place_id,
        options: preparedOptions,
    };
    router.post(route('date-polls.store'), payload, {
        preserveScroll: true,
    });
};

const handleOpenTimeSelection = ({ option, index }) => {
    selectedOptionForTime.value = option;
    selectedIndexForTime.value = index;
    showTimePickerModal.value = true;
};

const handleUpdateTime = ({ hour, minute, endHour, endMinute }) => {
    if (selectedIndexForTime.value !== null && selectedIndexForTime.value >= 0) {
        const option = pollForm.options[selectedIndexForTime.value];
        if (!option) return;

        option.selectedHour = hour;
        option.selectedMinute = minute;

        if (endHour !== undefined && endMinute !== undefined) {
            option.selectedEndHour = endHour;
            option.selectedEndMinute = endMinute;
        } else {
            delete option.selectedEndHour;
            delete option.selectedEndMinute;
        }
        pollForm.options = [...pollForm.options];
    }

    showTimePickerModal.value = false;
    selectedIndexForTime.value = null;
    selectedOptionForTime.value = null;
};

const returnToPrevious = () => {
    emit('returnToDetails');
};

</script>

<template>
    <div class="w-full flex flex-col items-center">
        <CreateDatePoll
            :options="pollForm.options"
            @returnToPreviousStep="returnToPrevious"
            @addDateOptionTrigger="toggleDatePicker"
            @removeDateOption="handleRemoveDateOption"
            @setTimeForOption="handleOpenTimeSelection"
            @submitPoll="handleCreatePoll"
        />
        <div v-if="showDatePickerModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50" @click="toggleDatePicker">
            <div class="bg-white p-6 rounded shadow-lg w-full max-w-md mx-4" @click.stop>
                <div class="w-full flex justify-end -mt-2 mb-2">
                    <button @click="toggleDatePicker" class="text-red-500 hover:text-red-700 text-xl">
                        <CrossIcon />
                    </button>
                </div>
                <DatePicker :selectedDates="pollForm.options.map(o => o.selectedDate)" @update="handleAddOrUpdateDateOption" />
                <div class="flex justify-end mt-4">
                    <BaseButton label="Done" @click="toggleDatePicker" />
                </div>
            </div>
        </div>

        <TimePickerModal
            v-if="showTimePickerModal"
            :option="selectedOptionForTime"
            :index="selectedIndexForTime"
            @close="showTimePickerModal = false"
            @confirm="handleUpdateTime"
        />
    </div>
</template>
