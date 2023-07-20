<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserFile;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class UserService
{
    /**
     * @throws FileIsTooBig
     * @throws FileDoesNotExist
     */
    public function edit(User $user, array $data): void
    {
        DB::beginTransaction();
        if (filled($data['files'] ?? null) ) {
            $user->files->whereIn('type_id', array_keys($data['files']))->each(function (UserFile $userFile) {
                $userFile->delete();
            });

            foreach ($data['files'] as $id => $files) {
                /** @var UserFile $userFile */
                $userFile = $user->files()->create(['type_id' => $id]);
                foreach ($files as $file) {
                    $userFile->addMedia($file)->toMediaCollection();
                }
            }
        };
        $user->update($data);
        DB::commit();
    }
}
