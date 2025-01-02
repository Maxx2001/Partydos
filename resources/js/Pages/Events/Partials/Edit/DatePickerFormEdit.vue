<template>
  <DatePicker
    @update="updateDates"
    :initial-start-time="form.start_date_time"
    :initial-end-time="form.end_date_time"
    class="mt-6"
  />
</template>

<script setup>
import { format, setHours, setMinutes, isValid } from "date-fns";
import DatePicker from "@/Pages/Events/Partials/Create/DatePicker.vue";

const props = defineProps({
  form: {
    type: Object,
    required: true
  }
});

const emit = defineEmits(["update:form"]);

const updateDates = (dateObject) => {
  if (!dateObject || !dateObject.selectedDate || !dateObject.selectedHour || !dateObject.selectedMinute) {
    console.warn("Invalid date object passed to updateDates:", dateObject);
    props.form.start_date_time = null;
    props.form.end_date_time = null;
    return;
  }

  const startDateTime = setMinutes(
    setHours(dateObject.selectedDate, parseInt(dateObject.selectedHour)),
    parseInt(dateObject.selectedMinute)
  );

  let endDateTime = null;

  if (dateObject.selectedEndHour && dateObject.selectedEndMinute) {
    endDateTime = setMinutes(
      setHours(dateObject.selectedDate, parseInt(dateObject.selectedEndHour)),
      parseInt(dateObject.selectedEndMinute)
    );

    if (endDateTime <= startDateTime) {
      const adjustedDate = new Date(dateObject.selectedDate);
      adjustedDate.setDate(adjustedDate.getDate() + 1);

      endDateTime = setMinutes(
        setHours(adjustedDate, parseInt(dateObject.selectedEndHour)),
        parseInt(dateObject.selectedEndMinute)
      );
    }
  }

  props.form.start_date_time = isValid(startDateTime)
    ? format(startDateTime, "yyyy-MM-dd HH:mm:ss")
    : null;
  props.form.end_date_time = isValid(endDateTime)
    ? format(endDateTime, "yyyy-MM-dd HH:mm:ss")
    : null;

  emit("update:form", props.form);
};
</script>
