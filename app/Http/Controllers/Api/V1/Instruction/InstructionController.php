<?php

namespace App\Http\Controllers\Api\V1\Instruction;

use App\Enums\RoleEnums;
use App\Http\Controllers\Controller;
use App\Http\Resources\InstructionResource;
use App\Models\Instruction;
use App\Services\Instruction\InstructionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstructionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $instructions = Instruction::paginate(10);
        return InstructionResource::collection($instructions)->response();
    }

    public function search(Request $request, InstructionService $instructionService)
    {
        $searchText = $request->get('search');

        $instructions = $instructionService->search($searchText);


        return InstructionResource::collection($instructions)->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $role = RoleEnums::from($user->role);

        if (!$role->hasPermission(RoleEnums::ADMIN->value)) {
            return response()->json(['error' => 'У вас нет прав для создания инструкции'], 403);
        }
        $newInstruction = $request->validate([
            'title' => 'required|string|max:255',
            'logo' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
        ]);
        $instruction = Instruction::create($newInstruction);

        return response()->json(new InstructionResource($instruction), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $instruction = Instruction::find($id);

        if (!$instruction) {
            return response()->json(['error' => 'Инструкция не найдена'], 404);
        }

        return response()->json(new InstructionResource($instruction), 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();
        $role = RoleEnums::from($user->role);

        if (!$role->hasPermission(RoleEnums::ADMIN->value)) {
            return response()->json(['error' => 'У вас нет прав для редактирования инструкции'], 403);
        }

        $instruction = Instruction::find($id);

        if (!$instruction) {
            return response()->json(['error' => 'Инструкция не найдена'], 404);
        }

        $data = $request->validate([
            'title' => 'string|max:255',
            'logo' => 'string|max:255',
            'short_description' => 'string|max:500',
            'description' => 'string',
        ]);

        foreach ($instruction->fillable as $field) {
            if (isset($data[$field])) {
                $instruction->$field = $data[$field];
            }
        }

        $instruction->save();

        return response()->json(new InstructionResource($instruction), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $instruction = Instruction::find($id);

        if (!$instruction) {
            return response()->json(['error' => 'Инструкция не найдена'], 404);
        }

        $instruction->delete();

        return response()->json(['message' => 'Инструкция удалена'], 200);
    }
}
