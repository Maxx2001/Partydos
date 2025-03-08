<?php

namespace Domain\DatePicker\Actions;

use Auth;
use Domain\DatePicker\DateTransferObjects\DatePickerData;
use Domain\DatePicker\Modals\DateOption;
use Domain\DatePicker\Modals\DatePicker;
use Domain\Users\Models\User;

class CreateDatePickerAction
{
    public function execute(DatePickerData $datePickerData): void
    {
//        $user = Auth::user();
        $user = User::find(1);

        $datePicker = DatePicker::create(
            [
                'user_id' => $user->getKey(),
            ] + $datePickerData->all()
        );

        $dateOptions = $datePickerData->date_options;

//        $dateOptions->each(function ($dateOption) use ($user) {
//            $dateOption = DateOption::create($dateOption->all());
//            dd($dateOption);
//        });
    }
}
