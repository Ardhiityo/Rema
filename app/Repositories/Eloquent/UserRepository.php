<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Models\MetaData;
use App\Data\User\UserData;
use App\Data\User\CreateUserData;
use App\Data\User\UpdateUserData;
use App\Services\AvatarGenerator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Contratcs\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function create(CreateUserData $create_user_data): UserData
    {
        $user = User::create([
            'name' => ucwords(strtolower($create_user_data->name)),
            'email' => empty($create_user_data->email)
                ? $create_user_data->nim . '@gmail.com' : $create_user_data->email,
            'password' => empty($create_user_data->password) ?
                intval($create_user_data->nim) * 2 : $create_user_data->password,
            'avatar' => empty($create_user_data->avatar) ?
                AvatarGenerator::generate() : $create_user_data->avatar->store('avatars', 'public'),
            'email_verified_at' => now()
        ]);

        $user->assignRole('contributor');

        return UserData::fromModel($user);
    }

    public function findById(int $user_id): UserData|null
    {
        try {
            $user = User::findOrFail($user_id);

            return UserData::fromModel($user);
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function update(int $user_id, UpdateUserData $update_user_data): UserData|null
    {
        try {
            $user = User::findOrFail($user_id);

            $old_avatar = $user->avatar;

            $new_avatar = $update_user_data->avatar ?? null;

            if (empty($new_avatar)) {
                $new_avatar = $old_avatar;
            } else {
                if ($old_avatar) {
                    if (Storage::disk('public')->exists($old_avatar)) {
                        Storage::disk('public')->delete($old_avatar);
                    }
                }
                $new_avatar = $new_avatar->store('avatars', 'public');
            }

            $password = $update_user_data->password ? Hash::make($update_user_data->password) : $user->password;

            $user->update([
                'name' => ucwords(strtolower($update_user_data->name)),
                'avatar' => $new_avatar,
                'email' => $update_user_data->email,
                'password' => $password
            ]);

            return UserData::fromModel($user->refresh());
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function delete(int $user_id): bool
    {
        try {
            $user = User::findOrFail($user_id);

            $avatar = $user->avatar ?? null;

            if ($avatar) {
                if (Storage::disk('public')->exists($avatar)) {
                    Storage::disk('public')->delete($avatar);
                }
            }

            $author_id = $user->author->id;

            $meta_data = MetaData::with('categories')->where('author_id', $author_id)->get();

            foreach ($meta_data as $data) {
                if ($data->categories->isNotEmpty()) {
                    foreach ($data->categories as $category) {
                        $file_path = $category->pivot->file_path ?? null;

                        if ($file_path) {
                            if (Storage::disk('public')->exists($file_path)) {
                                Storage::disk('public')->delete($file_path);
                            }
                        }
                    }
                }
            }

            return $user->delete();
        } catch (\Throwable $th) {
            return false;
        }
    }
}
