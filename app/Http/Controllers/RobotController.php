<?php

namespace App\Http\Controllers;

use App\Enum\EntityEnum;
use App\Enum\RobotEnum;
use App\Manager\RobotManager;
use App\Models\Robot;
use Illuminate\Http\Request;
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
        return $this->getEditView(
            $this->manager->findById($id)
        );
    }

    public function update(Request $request)
    {
        $data = $this->validateData($request, EntityEnum::OPERATION_TYPE_UPDATE);
        $manager = $this->manager;
        $manager->updateEntity($manager->findById($data['id']), $data);

        return redirect()->route('robot-index');
    }

    public function delete(Request $request)
    {
        $data = $this->validateData($request, EntityEnum::OPERATION_TYPE_DELETE);
        $manager = $this->manager;
        $manager->deleteEntity($manager->findById($data['id']));

        return redirect()->route('robot-index');
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

    private function getValidationRules(string $operationType): array
    {
        $dataRules = [
            'name' => 'required|string|min:1|max:255',
            'type' => [
                'required',
                Rule::in(RobotEnum::TYPES)
            ],
            'power' => 'required|integer|min:1|max:255',
        ];
        $idRules = ['id' => 'required|exists:robots,id'];

        switch ($operationType) {
            case EntityEnum::OPERATION_TYPE_STORE:
                return $dataRules;
            case EntityEnum::OPERATION_TYPE_UPDATE:
                return array_merge($idRules, $dataRules);
            case EntityEnum::OPERATION_TYPE_DELETE:
                return $idRules;
        }

        return [];
    }
}
