<?php

namespace App\Constants;

class StatusesConstants
{
    const PROCESSING_ID = 1;
    const REVIEWING_ID = 2;
    const COLLECTING_ID = 3;
    const CANCELED_ID = 4;

    public const STATUSES = [
        self::PROCESSING_ID => 'В обработке',
        self::REVIEWING_ID => 'На рассмотрении',
        self::COLLECTING_ID => 'Сбор заявок',
        self::CANCELED_ID => 'Отменен',
    ];
}
