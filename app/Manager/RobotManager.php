<?php

namespace App\Manager;

use App\Enum\EntityEnum;
use App\Models\Robot;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class RobotManager
{
    public function findActiveEntities(): Collection
    {
        return Robot::where('state', EntityEnum::STATE_ACTIVE)->get();
    }

    public function findById(int $id): ?Robot
    {
        return Robot::where('id', $id)
            ->where('state', '!=', EntityEnum::STATE_DELETED)
            ->first();
    }

    public function findByIdAndValidate(int $id, int $sequence): Robot
    {
        if (null === $robot = $this->findById($id)) {
            throw ValidationException::withMessages(["id.$sequence" => 'Invalid entity ID.']);
        }

        return $robot;
    }

    public function saveEntity(array $data): void
    {
        Robot::create(array_merge($data, [
            'state' => EntityEnum::STATE_ACTIVE
        ]));
    }

    public function updateEntity(Robot $robot, array $data): void
    {
        $robot->update($data);
    }

    public function deleteEntity(Robot $robot): void
    {
        $robot->update([
            'state' => EntityEnum::STATE_DELETED,
        ]);
    }
}
