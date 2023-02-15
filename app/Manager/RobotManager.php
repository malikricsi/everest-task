<?php

namespace App\Manager;

use App\Enum\EntityEnum;
use App\Models\Robot;

class RobotManager
{
    public function findActiveEntities()
    {
        return Robot::where('state', 'active')->get();
    }

    public function findById(int $id): Robot
    {
        return Robot::find($id);
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
