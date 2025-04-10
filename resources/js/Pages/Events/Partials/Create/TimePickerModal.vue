<template>
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center px-8" @click="close">
        <div class="bg-white p-6 rounded-2xl shadow-lg w-full max-w-md" @click.stop>
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Select Time</h2>

            <div class="flex flex-col space-y-6">
                <!-- Start Time -->
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Start Time</label>
                    <div class="flex justify-between space-x-4">
                        <select v-model="selectedHour" @change="onTimeChange"
                            class="flex-1 text-center p-2 bg-white border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-300 appearance-none">
                            <option v-for="hour in hours" :key="hour" :value="hour">{{ hour }}</option>
                        </select>
                        <select v-model="selectedMinute" @change="onTimeChange"
                            class="flex-1 text-center p-2 bg-white border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-300 appearance-none">
                            <option v-for="minute in minutes" :key="minute" :value="minute">{{ minute }}</option>
                        </select>
                    </div>
                </div>

                <!-- End Time Toggle -->
                <div class="flex items-center space-x-2">
                    <input id="enableEndTime" type="checkbox" v-model="enableEndTime"
                        class="rounded h-4 w-4 text-blue-600 border-gray-300 focus:ring-0 cursor-pointer" />
                    <label for="enableEndTime" class="text-sm text-gray-700 cursor-pointer">Set end time?</label>
                </div>

                <!-- End Time Fields -->
                <transition>
                    <div v-show="enableEndTime" class="flex flex-col space-y-2">
                        <label class="block text-sm text-gray-600 mb-1">End Time</label>
                        <div class="flex justify-between space-x-4">
                            <select v-model="selectedEndHour" @change="onEndTimeChange"
                                class="flex-1 text-center p-2 bg-white border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-300 appearance-none">
                                <option v-for="hour in hours" :key="hour" :value="hour">{{ hour }}</option>
                            </select>
                            <select v-model="selectedEndMinute" @change="onEndTimeChange"
                                class="flex-1 text-center p-2 bg-white border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-300 appearance-none">
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
import { ref, watch } from 'vue';
import { parse, format, setHours, setMinutes, addDays, isBefore } from 'date-fns';
import BaseButton from '@/Components/Base/BaseButton.vue';
import { defineProps, defineEmits } from 'vue';

const emits = defineEmits(['close', 'confirm']);

const props = defineProps({
    option: Object,
    index: Number,
});

const baseStart = props.option?.datetime
    ? parse(props.option.datetime, 'yyyy-MM-dd HH:mm:ss', new Date())
    : new Date();

const baseEnd = props.option?.endDatetime
    ? parse(props.option.endDatetime, 'yyyy-MM-dd HH:mm:ss', new Date())
    : baseStart;

const selectedHour = ref(format(baseStart, 'HH'));
const selectedMinute = ref(format(baseStart, 'mm'));

const selectedEndHour = ref(format(baseEnd, 'HH'));
const selectedEndMinute = ref(format(baseEnd, 'mm'));

const enableEndTime = ref(!!props.option?.endDatetime);

const hours = Array.from({ length: 24 }, (_, i) => i.toString().padStart(2, '0'));
const minutes = Array.from({ length: 60 }, (_, i) => i.toString().padStart(2, '0'));

const close = () => emits('close');

const confirmTime = () => {
    emits('confirm', {
        start: {
            hour: selectedHour.value,
            minute: selectedMinute.value,
        },
        end: enableEndTime.value
            ? {
                  hour: selectedEndHour.value,
                  minute: selectedEndMinute.value,
              }
            : null,
    });
    close();
};

const onTimeChange = () => {
    // Je kan hier extra validatie doen of updaten naar een datetime object
};

const onEndTimeChange = () => {
    if (enableEndTime.value) {
        const startDateTime = setHours(setMinutes(new Date(), parseInt(selectedMinute.value)), parseInt(selectedHour.value));
        let endDateTime = setHours(setMinutes(new Date(), parseInt(selectedEndMinute.value)), parseInt(selectedEndHour.value));
        if (isBefore(endDateTime, startDateTime)) {
            endDateTime = addDays(endDateTime, 1);
        }
        selectedEndHour.value = format(endDateTime, 'HH');
        selectedEndMinute.value = format(endDateTime, 'mm');
    } else {
        selectedEndHour.value = null;
        selectedEndMinute.value = null;
    }
};

watch([selectedHour, selectedMinute], onTimeChange);
watch([selectedEndHour, selectedEndMinute, enableEndTime], onEndTimeChange);
</script>
