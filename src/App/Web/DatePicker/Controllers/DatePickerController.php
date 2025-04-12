<?php

namespace App\Web\DatePicker\Controllers;

use Domain\DatePicker\Actions\CreateDatePickerAction;
use Domain\DatePicker\DateTransferObjects\DatePickerData;
use Support\Controllers\Controller;

class DatePickerController extends Controller
{
    public function store(CreateDatePickerAction $createDatePickerAction, DatePickerData $pickerData): void
    {
        dd($pickerData);
        $createDatePickerAction->execute($pickerData);
    }
}
