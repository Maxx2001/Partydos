<?php

namespace Domain\DatePicker\Actions;

use Auth;
use Domain\DatePicker\DateTransferObjects\DatePickerData;
use Domain\DatePicker\Modals\DateOption;
use Domain\DatePicker\Modals\DatePicker;
use Domain\Users\Models\User;
use Support\Services\DateAdjustmentService;

class CreateDatePickerAction
{
    public function execute(DatePickerData $datePickerData): void
    {
        // date picker maken
        // date options maken
        $datePicker = DatePicker::create(
            [
                'user_id' => Auth::user()->getKey(),
            ] + $datePickerData->all()
        );

        (new CreateDatePickerOptionsAction())->execute($datePicker, $datePickerData->options);
//        $user = Auth::user();
//        $user = User::find(1);
//
//        $datePicker = DatePicker::create(
//            [
//                'user_id' => $user->getKey(),
//            ] + $datePickerData->all()
//        );
//
//        $dateOptions = $datePickerData->date_options;

//        $dateOptions->each(function ($dateOption) use ($user) {
//            $dateOption = DateOption::create($dateOption->all());
//            dd($dateOption);
//        });
    }
}
