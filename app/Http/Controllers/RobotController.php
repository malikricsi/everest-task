<?php

namespace App\Http\Controllers;

use App\Enum\EntityEnum;
use App\Enum\RobotEnum;
use App\Manager\RobotManager;
use App\Models\Robot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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

        return redirect()->route('robot-index')->with('message', 'Sikeres mentés.');
    }

    public function edit(int $id)
    {
        if (true === $this->validateId($id) && null !== $robot = $this->manager->findById($id)) {
            return $this->getEditView($robot);
        }

        return redirect()->route('robot-index')->withErrors(['id' => 'Invalid entity ID.']);
    }

    public function update(Request $request)
    {
        $data = $this->validateData($request, EntityEnum::OPERATION_TYPE_UPDATE);
        $manager = $this->manager;
        $manager->updateEntity($manager->findById($data['id']), $data);

        return redirect()->route('robot-index')->with('message', 'Sikeres módosítás.');
    }

    public function delete(int $id)
    {
        $manager = $this->manager;
        $response = redirect()->route('robot-index');
        if (true === $this->validateId($id) && null !== $robot = $manager->findById($id)) {
            $manager->deleteEntity($robot);
            return $response->with('message', 'Sikeres törlés.');
        }

        return $response->withErrors(['id' => 'Invalid entity ID.']);
    }

    public function combat(Request $request)
    {
        $data = $this->validateData($request, RobotEnum::OPERATION_TYPE_COMBAT);
        $manager = $this->manager;

        try {
            $firstRobot = $manager->findByIdAndValidate($data['id'][0], 0);
            $secondRobot = $manager->findByIdAndValidate($data['id'][1], 1);
        } catch (ValidationException $exception) {
            return redirect()->route('robot-index')->withErrors(['id' => 'Invalid entity ID.']);
        }

        return view('robot.combat', [
            'winner' => $this->doCombat($firstRobot, $secondRobot)
        ]);
    }

    public function apiCombat(Request $request)
    {
        $data = $this->validateData($request, RobotEnum::OPERATION_TYPE_COMBAT);
        $manager = $this->manager;
        $firstRobot = $manager->findByIdAndValidate($data['id'][0], 0);
        $secondRobot = $manager->findByIdAndValidate($data['id'][1], 1);

        return json_encode($this->doCombat($firstRobot, $secondRobot));
    }

    private function doCombat(Robot $firstRobot, Robot $secondRobot): Robot
    {
        switch ($firstRobot->power <=> $secondRobot->power) {
            case 1:
                return $firstRobot;
            case 0:
                return ($firstRobot->created_at > $secondRobot->created_at)
                    ? $firstRobot
                    : $secondRobot;
            default:
                return $secondRobot;
        }
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
            'power' => 'required|integer|min:1|max:2147483647',
        ];
    }

    private function getIdRules(): array
    {
        return [
            'id' => 'required|integer'
        ];
    }

    private function getCombatRules(): array
    {
        return [
            'id' => 'required|array|size:2',
            'id.0' => 'required|integer',
            'id.1' => 'required|integer|different:id.0'
        ];
    }
}
