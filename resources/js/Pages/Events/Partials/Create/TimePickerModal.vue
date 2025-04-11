<template>
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center px-8" @click="close">
        <div class="bg-white p-6 rounded-2xl shadow-lg w-full max-w-md" @click.stop>
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Select Time</h2>

            <div class="flex flex-col space-y-6">
                <!-- Start Time -->
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Start Time</label>
                    <div class="flex justify-between space-x-4">
                        <select v-model="selectedHour" class="flex-1 text-center p-2 border rounded-md">
                            <option v-for="hour in hours" :key="hour" :value="hour">{{ hour }}</option>
                        </select>
                        <select v-model="selectedMinute" class="flex-1 text-center p-2 border rounded-md">
                            <option v-for="minute in minutes" :key="minute" :value="minute">{{ minute }}</option>
                        </select>
                    </div>
                </div>

                <!-- End Time Toggle -->
                <div class="flex items-center space-x-2">
                    <input id="enableEndTime" type="checkbox" v-model="enableEndTime"
                           class="rounded h-4 w-4 text-blue-600 border-gray-300 cursor-pointer" />
                    <label for="enableEndTime" class="text-sm text-gray-700 cursor-pointer">Set end time?</label>
                </div>

                <!-- End Time -->
                <transition>
                    <div v-show="enableEndTime" class="flex flex-col space-y-2">
                        <label class="block text-sm text-gray-600 mb-1">End Time</label>
                        <div class="flex justify-between space-x-4">
                            <select v-model="selectedEndHour" class="flex-1 text-center p-2 border rounded-md">
                                <option v-for="hour in hours" :key="hour" :value="hour">{{ hour }}</option>
                            </select>
                            <select v-model="selectedEndMinute" class="flex-1 text-center p-2 border rounded-md">
                                <option v-for="minute in minutes" :key="minute" :value="minute">{{ minute }}</option>
                            </select>
                        </div>
                    </div>
                </transition>

                <!-- Buttons -->
                <div class="flex justify-between pt-2">
                    <BaseButton label="Cancel" @click="close" variant="cancel" />
                    <BaseButton label="Confirm" @click="confirmTime" class="ml-2" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref} from 'vue';
import BaseButton from '@/Components/Base/BaseButton.vue';

const emit = defineEmits(['close', 'confirm']);
const props = defineProps({
    option: Object,
    index: Number,
});

// Init values from props
const selectedHour = ref(props.option?.selectedHour?.toString().padStart(2, '0') || '12');
const selectedMinute = ref(props.option?.selectedMinute?.toString().padStart(2, '0') || '00');

const selectedEndHour = ref(props.option?.selectedEndHour?.toString().padStart(2, '0') || '12');
const selectedEndMinute = ref(props.option?.selectedEndMinute?.toString().padStart(2, '0') || '00');

const enableEndTime = ref(
    props.option?.selectedEndHour !== undefined && props.option?.selectedEndMinute !== undefined
);

// Time options
const hours = Array.from({length: 24}, (_, i) => i.toString().padStart(2, '0'));
const minutes = Array.from({length: 60}, (_, i) => i.toString().padStart(2, '0'));

// Actions
const close = () => emit('close');

const confirmTime = () => {
    emit('confirm', {
        hour: selectedHour.value,
        minute: selectedMinute.value,
        ...(enableEndTime.value && {
            endHour: selectedEndHour.value,
            endMinute: selectedEndMinute.value,
        }),
    });
    close();
};
</script>
