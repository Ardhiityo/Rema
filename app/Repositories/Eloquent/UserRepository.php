<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Models\MetaData;
use App\Data\User\UserData;
use App\Data\User\CreateUserData;
use App\Data\User\UpdateUserData;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Contratcs\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function create(CreateUserData $create_user_data): UserData
    {
        $user = User::create([
            'name' => $create_user_data->name,
            'email' => $create_user_data->email,
            'password' => $create_user_data->password,
            'avatar' => $create_user_data->avatar->store('avatars', 'public')
        ]);

        return UserData::fromModel($user);
    }

    public function findById(int $user_id): UserData
    {
        $user = User::findOrFail($user_id);

        return UserData::fromModel($user);
    }

    public function update(int $user_id, UpdateUserData $update_user_data): UserData
    {
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
            'name' => $update_user_data->name,
            'avatar' => $new_avatar,
            'email' => $update_user_data->email,
            'password' => $password
        ]);

        return UserData::fromModel($user->refresh());
    }

    public function delete(int $user_id): bool
    {
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
    }
}
