<?php

namespace App\Http\Controllers;

use App\Enum\EntityEnum;
use App\Enum\RobotEnum;
use App\Manager\RobotManager;
use App\Models\Robot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RobotController extends Controller
{
    protected $manager;

    public function __construct()
    {
        $this->manager = new RobotManager();
    }

    public function index()
    {
        return $this->getIndexView();
    }

    public function create()
    {
        return $this->getEditView();
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request, EntityEnum::OPERATION_TYPE_STORE);
        $this->manager->saveEntity($data);

        return redirect()->route('robot-index');
    }

    public function edit(int $id)
    {
        if (true === $this->validateId($id)) {
            return $this->getEditView(
                $this->manager->findById($id)
            );
        }

        return redirect()->route('robot-index');
    }

    public function update(Request $request)
    {
        $data = $this->validateData($request, EntityEnum::OPERATION_TYPE_UPDATE);
        $manager = $this->manager;
        $manager->updateEntity($manager->findById($data['id']), $data);

        return redirect()->route('robot-index');
    }

    public function delete(int $id)
    {
        if (true === $this->validateId($id)) {
            $manager = $this->manager;
            $manager->deleteEntity($manager->findById($id));
        }

        return redirect()->route('robot-index');
    }

    public function combat(Request $request)
    {
        $data = $this->validateData($request, RobotEnum::OPERATION_TYPE_COMBAT);

        return view('robot.combat', [
            'winner' => $this->doCombat($data)
        ]);
    }

    private function doCombat(array $data): ?Robot
    {
        $manager = $this->manager;
        $firstRobot = $manager->findById($data['id'][0]);
        $secondRobot = $manager->findById($data['id'][1]);
        switch ($firstRobot->power <=> $secondRobot->power) {
            case 1:
                return $firstRobot;
            case 0:
                return ($firstRobot->created_at > $secondRobot->created_at)
                    ? $firstRobot
                    : $secondRobot;
            case -1:
                return $secondRobot;
        }

        return null;
    }

    private function getIndexView()
    {
        return view('robot.index', [
            'entities' => $this->manager->findActiveEntities(),
        ]);
    }

    private function getEditView(?Robot $robot = null)
    {
        return view('robot.edit', compact('robot'));
    }

    private function validateData(Request $request, string $operationType): array
    {
        return $request->validate($this->getValidationRules($operationType));
    }

    private function validateId(int $id): bool
    {
        $validator = Validator::make(compact('id'), $this->getIdRules());
        return !$validator->fails();
    }

    private function getValidationRules(string $operationType): array
    {
        $dataRules = $this->getDataRules();
        switch ($operationType) {
            case EntityEnum::OPERATION_TYPE_STORE:
                return $dataRules;
            case EntityEnum::OPERATION_TYPE_UPDATE:
                return array_merge($this->getIdRules(), $dataRules);
            case RobotEnum::OPERATION_TYPE_COMBAT:
                return $this->getCombatRules();
        }

        return [];
    }

    private function getDataRules(): array
    {
        return [
            'name' => 'required|string|min:1|max:255',
            'type' => [
                'required',
                Rule::in(RobotEnum::TYPES)
            ],
            'power' => 'required|integer|min:1|max:255',
        ];
    }

    private function getIdRules(): array
    {
        return ['id' => 'required|integer|exists:robots,id'];
    }

    private function getCombatRules(): array
    {
        return [
            'id.0' => 'required|integer|exists:robots,id',
            'id.1' => 'required|integer|exists:robots,id|different:id.0'
        ];
    }
}
