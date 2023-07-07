<?php

namespace App\Utilities;

use App\Http\Resources\UserFileResource;
use App\Models\Page;
use App\Models\User;
use App\Models\UserFile;
use App\Models\UserFileType;
use Illuminate\Database\Eloquent\Builder;

class Helpers
{
    public static function getCleanPhone(?string $phone): ?string
    {
        if ($phone === null) {
            return null;
        }

        $clean = preg_replace('/\D+/', '', $phone);
        if (!$clean) {
            return $phone;
        }

        $prefixed = str_starts_with($phone, '+7')
            || str_starts_with($phone, '+8');

        $len = mb_strlen($clean);
        switch (true) {
            case $len > 11:
                return $clean;
            case $len === 11:
                if ($clean[0] !== '7' && $clean[0] !== '8') {
                    return $clean;
                }
                $clean = '7' . mb_substr($clean, -10);
                break;
            case $len === 10 && !$prefixed:
                $clean = "7{$clean}";
                break;
        }

        return $clean;
    }

    public static function getBreadcrumbs(
        Page $page,
        ?string $title = null,
    ): array {
        $breadcrumbs = [['text' => $title ?? $page->title ?? '_']];

        $parent = $page->parent;

        while ($parent !== null) {
            $title = $parent->title ?? '_';
            $slug = $parent->fullSlug();

            $breadcrumbs[] = ['text' => $title, 'link' => "/{$slug}"];

            $parent = $parent->parent;
        }

        $breadcrumbs[] = ['text' => 'Главная', 'link' => '/'];

        return array_reverse($breadcrumbs);
    }

    public static function userFiles(User $user, bool $show_in_order)
    {
        return UserFileType::query()->when($show_in_order == true, function (Builder $query) {
            $query->where('show_in_order', true);
        })->whereJsonContains('org_type', $user->company->org_type)->get()->map(
            function (UserFileType $type) use ($user) {
                /** @var ?UserFile $file */
                $file = $user->files->firstWhere('type_id', $type->id);
                return [
                    'type' => $type,
                    'files' => $file !== null ? UserFileResource::make($file) : [],
                ];
            }
        );
    }
}
