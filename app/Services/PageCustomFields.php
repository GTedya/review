<?php

namespace App\Services;

use App\Filament\Resources\PageResource\Templates\AboutPageFields;
use App\Filament\Resources\PageResource\Templates\DefaultPageFields;
use App\Filament\Resources\PageResource\Templates\LeasingPageFields;
use App\Filament\Resources\PageResource\Templates\LeasingsPageFields;
use App\Filament\Resources\PageResource\Templates\MainPageFields;
use App\Filament\Resources\PageResource\Templates\SearchPageFields;
use App\Models\Page;

abstract class PageCustomFields
{
    private const PAGE_CLASS_MAP = [
        'default' => DefaultPageFields::class,
        'main' => MainPageFields::class,
        'about' => AboutPageFields::class,
        'search' => SearchPageFields::class,
        'leasings' => LeasingsPageFields::class,
        'leasing' => LeasingPageFields::class,
    ];

    protected Page $page;

    public function __construct(Page $page)
    {
        $this->page = $page;
    }

    /**
     * @return \Filament\Forms\Components\Component[]
     */
    abstract public function getSchema(): array;

    abstract public function getFields(): ?array;

    abstract public function saveFields(array $vars): void;

    static public function getInstance($page): ?\App\Services\PageCustomFields
    {
        $template = $page->template;
        $className = self::PAGE_CLASS_MAP[$template] ?? null;

        if ($className === null) {
            return null;
        }

        return app()->make($className, ['page' => $page]);
    }


    abstract public function getPageVars(): array;
}
