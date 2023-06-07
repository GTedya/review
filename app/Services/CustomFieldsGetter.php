<?php

namespace App\Services;

use App\Models\PageVar;
use App\Models\RepeatVar;
use Illuminate\Support\Collection;

class CustomFieldsGetter
{
    private PageVar $pageVar;
    /** @var array<string, array<string, string>> $imageFields */
    private array $imageFields = [];
    /** @var array<string, CustomVar> $repeaterFields */
    private array $repeaterFields = [];

    public function __construct(PageVar $pageVar)
    {
        $this->pageVar = $pageVar;
    }

    /**
     * @param array<string, string> $imageFields
     * Массив, в котором ключ — название поля картинки, а значение — название медиа-коллекции
     */
    public function setImageFields(string $sectionName, array $imageFields)
    {
        $this->imageFields[$sectionName] = $imageFields;
    }

    public function setRepeaterFields(string $fieldName, CustomVar $fields)
    {
        $this->repeaterFields[$fieldName] = $fields;
    }

    public function getFields(): ?array
    {
        $vars = $this->pageVar->vars ?? [];

        foreach ($this->repeaterFields as $name => $customVar) {
            /** @var Collection $collection */
            $collection = $this->pageVar->repeatVars()->where('name', $name)->get();
            $vars[$name] = $this->mapRepeatVars($collection, $customVar);
        }

        foreach ($this->imageFields as $sectionName => $imageInfos) {
            foreach ($imageInfos as $imageField => $imageCollection) {
                $image = $this->pageVar->getFirstMedia($imageCollection)?->getPathRelativeToRoot();
                $vars[$sectionName][$imageField] = $image;
            }
        }

        return ['vars' => $vars];
    }

    /** @var Collection<RepeatVar> $oldRepeatVars */
    private function mapRepeatVars(Collection $repeatVars, CustomVar $customVar)
    {
        return $repeatVars->map(function (RepeatVar $var) use ($customVar) {
            $map = ['var_id' => $var->id];

            foreach ($customVar->fields as $field) {
                $map[$field] = $var->vars[$field] ?? null;
            }

            foreach ($customVar->imageFields as $imageField => $imageCollection) {
                $map[$imageField] = $var->getFirstMedia($imageCollection)?->getPathRelativeToRoot();
            }

            return $map;
        })->toArray();
    }
}
