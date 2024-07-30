<?php

namespace App\Repositories;

use App\Http\Resources\UserResource;
use App\Models\OrganizationModel;
use App\Models\User;


class UserRepository
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    public function getAllParent(User $user)
    {
        $parents = collect([]);
        $user->load('organization');
        $parents->push(new UserResource($user));
        $current = $user;
        while ($current->manager) {
            $current = $current->manager;
            $current->load('organization');
            $parents->push(new UserResource($current));
        }
        return $parents;
    }
    public function getAllParents(User $user)
    {
        $parents = collect([]);
        $current = $user;

        while ($current->manager) {
            $current->load('organization');
            $parents->push($current);
            $current = $current->manager;
        }

        // Push the final top-level manager (if any)
        if ($current) {
            $current->load('organization');
            $parents->push($current);
        }

        return UserResource::collection($parents);
    }
    public function getUserById($id)
    {

        $user = User::find($id);

        $managers = $this->getAllParents($user);
        $manager =    $managers->first();
        $managersArray = $manager;

        return $managersArray;
    }
    public function getUser($user)
    {
        $managers = $this->getAllParent($user);
        return $managers->first();
    }


    public function getDirectlyManagedUsers($user)
    {
        return $user->managedUsers->pluck('id')->toArray();
    }

    // Hàm lấy danh sách tất cả người dùng được quản lý bởi một người dùng nhất định
    public function getAllManagedUsers($user)
    {
        $maxLevel = OrganizationModel::max('level');
        $directlyManagedUsers = $this->getDirectlyManagedUsers($user);
        $allManagedUsers = $directlyManagedUsers;
        $numberOfLoops = $maxLevel + 10 - $user->organization_id;
        $currentLevelUsers = $directlyManagedUsers;

        for ($i = 0; $i < $numberOfLoops; $i++) {
            $nextLevelUsers = [];
            foreach ($currentLevelUsers as $userId) {
                $managedUsers = User::find($userId)->managedUsers->pluck('id')->toArray();
                $nextLevelUsers = array_merge($nextLevelUsers, $managedUsers);
            }
            $allManagedUsers = array_merge($allManagedUsers, $nextLevelUsers);
            $currentLevelUsers = $nextLevelUsers;
        }

        $allManagedUsers[] = $user->id;
        return array_unique($allManagedUsers);
    }
}
