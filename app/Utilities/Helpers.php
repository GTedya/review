<?php

namespace App\Utilities;

use App\Models\Page;

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
}
