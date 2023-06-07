<?php

namespace App\Services;

use App\Models\Page;
use App\Models\PageVar;
use App\Models\RepeatVar;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CustomFieldsSaver
{
    private array $vars;
    /** @var array<string, CustomVar> $pageVarFields */
    private array $pageVarFields = [];
    /** @var array<string, CustomVar> $repeatVarsFields */
    private array $repeatVarsFields = [];

    public function __construct(array $vars)
    {
        $this->vars = $vars;
    }

    public function setPageVarFields(string $varName, CustomVar $fields)
    {
        $this->pageVarFields[$varName] = $fields;
    }

    public function setRepeatVarsFields(string $varName, CustomVar $fields)
    {
        $this->repeatVarsFields[$varName] = $fields;
    }

    public function save(Page $page): void
    {
        $page = $page->fresh();

        $this->savePageVar($page);

        $pageVar = $page->refresh()->pageVar;

        foreach ($this->repeatVarsFields as $name => $customVar) {
            $this->saveRepeatVars($pageVar, $name, $customVar);
        }
    }

    private function savePageVar(Page $page): void
    {
        $vars = [];

        foreach ($this->pageVarFields as $name => $customVar) {
            $var = $this->vars[$name] ?? null;

            if (!is_array($var)) {
                continue;
            }

            foreach ($customVar->fields as $field) {
                $vars[$name][$field] = $var[$field];
            }
        }

        $page->pageVar()->updateOrCreate(
            ['page_id' => $page->id],
            ['vars' => $vars],
        );

        foreach ($this->pageVarFields as $name => $customVar) {
            $var = $this->vars[$name] ?? null;

            if (!is_array($var)) {
                continue;
            }

            foreach ($customVar->imageFields as $imageField => $imageCollection) {
                $image = $var[$imageField] ?? [];
                $pageVar = $page->pageVar;

                $image = current($image);

                $this->saveOrDeleteImage($pageVar, $image, $imageCollection);
            }
        }
    }

    private function saveRepeatVars(PageVar $pageVar, string $name, CustomVar $customVar): void
    {
        $newRepeatVars = $this->vars[$name] ?? null;

        if (!is_array($newRepeatVars)) {
            return;
        }

        /** @var Collection<RepeatVar> $oldRepeatVars */
        $oldRepeatVars = $pageVar->repeatVars()->where('name', $name)->get();

        $oldItems = $this->mapRepeatVars($oldRepeatVars, $customVar);

        $newItems = [];
        array_walk($newRepeatVars, function ($v, $k) use (&$newItems) {
            $key = $v['var_id'] ?? $k;
            $newItems[$key] = $v;
        });

        $toDelete = array_keys(array_diff_key($oldItems, $newItems));
        $toCreate = array_diff_key($newItems, $oldItems);
        $toUpdate = array_diff_key($newItems, $toCreate, $toDelete);

        DB::beginTransaction();

        $this->deleteRepeatVars($oldRepeatVars, $toDelete);

        $this->createRepeatVars($toCreate, $pageVar, $name, $customVar);

        $this->updateRepeatVars($toUpdate, $oldRepeatVars, $customVar);

        DB::commit();
    }

    /** @var Collection<RepeatVar> $oldRepeatVars */
    private function mapRepeatVars(Collection $repeatVars, CustomVar $customVar)
    {
        return $repeatVars->mapWithKeys(function (RepeatVar $var) use ($customVar) {
            $map = ['var_id' => $var->id];

            foreach ($customVar->fields as $field) {
                $map[$field] = $var->vars[$field] ?? null;
            }

            foreach ($customVar->imageFields as $imageField => $imageCollection) {
                $map[$imageField] = $var->getFirstMedia($imageCollection)?->getPathRelativeToRoot();
            }

            return [$var->id => $map];
        })->toArray();
    }

    /** @var Collection<RepeatVar> $repeatVars */
    private function deleteRepeatVars(Collection $repeatVars, array $toDelete)
    {
        $repeatVars->whereIn('id', $toDelete)
            ->each(fn(RepeatVar $var) => $var->delete());
    }

    private function createRepeatVars(array $toCreate, PageVar $pageVar, string $name, CustomVar $customVar)
    {
        foreach ($toCreate as $item) {
            $vars = [];

            foreach ($customVar->fields as $field) {
                $vars[$field] = $item[$field];
            }

            /** @var RepeatVar $created */
            $created = $pageVar->repeatVars()->create([
                'name' => $name,
                'vars' => $vars,
            ]);

            foreach ($customVar->imageFields as $imageField => $imageCollection) {
                $image = current($item[$imageField]);

                if ($image !== false) {
                    $path = storage_path("app\\public\\$image");
                    $created->addMedia($path)->toMediaCollection($imageCollection);
                }
            }
        }
    }

    /** @param Collection<RepeatVar> $oldRepeatVars */
    private function updateRepeatVars(array $toUpdate, Collection $oldRepeatVars, CustomVar $customVar)
    {
        foreach ($toUpdate as $key => $item) {
            /** @var RepeatVar $var */
            $var = $oldRepeatVars->where('id', $key)->first();

            $vars = [];

            foreach ($customVar->fields as $field) {
                $vars[$field] = $item[$field];
            }

            $var->vars = $vars;

            $var->save();

            foreach ($customVar->imageFields as $imageField => $imageCollection) {
                $image = current($item[$imageField]);

                $this->saveOrDeleteImage($var, $image, $imageCollection);
            }
        }
    }

    private function saveOrDeleteImage(PageVar|RepeatVar $var, $image, ?string $collection)
    {
        if ($image === false) {
            $var->getFirstMedia($collection)?->delete();
        } else {
            $oldImage = $var->getFirstMedia($collection)?->getPathRelativeToRoot();

            if ($oldImage !== $image) {
                $path = storage_path("app\\public\\$image");
                $var->addMedia($path)->toMediaCollection($collection);
            }
        }
    }
}
