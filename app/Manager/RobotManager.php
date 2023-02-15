<?php

namespace App\Manager;

use App\Enum\EntityEnum;
use App\Models\Robot;
use Illuminate\Http\RedirectResponse;

class RobotManager
{
    public function findActiveEntities()
    {
        return Robot::where('state', 'active')->get();
    }

    public function findById(int $id)
    {
        $robot = Robot::find($id);

        if (null === $robot || EntityEnum::STATE_DELETED === $robot->state) {
            return redirect()->route('robot-index')->withErrors(['id' => 'Invalid entity ID.']);
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
