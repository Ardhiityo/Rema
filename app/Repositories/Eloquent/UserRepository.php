<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Data\User\UserData;
use App\Data\User\CreateUserData;
use App\Data\User\UpdateUserData;
use App\Models\MetaData;
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

        $avatar = $update_user_data->avatar;

        if ($avatar) {
            if (Storage::disk('public')->exists($avatar)) {
                Storage::disk('public')->delete($avatar);
            }
            $avatar = $avatar->store('avatars', 'public');
        } else {
            $avatar = $user->avatar;
        }

        $password = $update_user_data->password ? Hash::make($update_user_data->password) : $user->password;

        $user->update([
            'name' => $update_user_data->name,
            'avatar' => $avatar,
            'email' => $update_user_data->email,
            'password' => $password
        ]);

        return UserData::fromModel($user->refresh());
    }

    public function delete(int $user_id): bool
    {
        $user = User::findOrFail($user_id);

        $avatar = $user->avatar;

        if ($avatar) {
            if (Storage::disk('public')->exists($avatar)) {
                Storage::disk('public')->delete($avatar);
            }
        }

        $author_id = $user->author->id;

        $meta_data = MetaData::with('categories')->where('author_id', $author_id)->get();

        foreach ($meta_data as $key => $category) {
            $file_path = $category->pivot->file_path ?? null;

            if ($file_path) {
                if (Storage::disk('public')->exists($file_path)) {
                    Storage::disk('public')->delete($file_path);
                }
            }
        }

        return $user->delete();
    }
}
