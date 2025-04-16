<?php

namespace Domain\DatePicker\Actions;

use Carbon\Carbon;
use Domain\DatePicker\Modals\DatePicker;
use Spatie\LaravelData\DataCollection;
use Support\Services\DateAdjustmentService;

class CreateDatePickerOptionsAction
{
    public function execute(DatePicker $datePicker, DataCollection $dateOptionData): void
    {
        foreach ($dateOptionData as $optionData) {
            $formattedStartTime = $optionData->start_datetime
                ? Carbon::parse($optionData->start_datetime)->format('Y-m-d H:i:s')
                : null;

            $formattedEndTime = null;
            if ($optionData->start_datetime && $optionData->end_datetime) {
                $endDateTime = DateAdjustmentService::adjustEndDate($optionData->start_datetime, $optionData->end_datetime);
                $formattedEndTime = Carbon::parse($endDateTime)->format('Y-m-d H:i:s');
            }

            $datePicker->options()->create([
                'date'       => $optionData->date,
                'start_time' => $formattedStartTime,
                'end_time'   => $formattedEndTime,
            ]);
        }
    }
}
