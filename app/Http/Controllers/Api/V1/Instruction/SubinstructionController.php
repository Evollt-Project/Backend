<?php

namespace App\Http\Controllers\Api\V1\Instruction;

use App\Enums\RoleEnums;
use App\Http\Controllers\Controller;
use App\Http\Resources\SubinstructionResource;
use App\Models\Subinstruction;
use App\Services\Instruction\SubinstructionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubinstructionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store', 'update', 'destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, SubinstructionService $subinstructionService)
    {
        $perPage = $request->query('per_page') ?? 10;

        $validatedRequest = $request->validate([
            'search' => '',
            'instruction_id' => ''
        ]);

        $subinstructions = $subinstructionService->filter($validatedRequest);

        return SubinstructionResource::collection($subinstructions->paginate($perPage))->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $role = RoleEnums::from($user->role);

        if (!$role->hasPermission(RoleEnums::ADMIN->value)) {
            return response()->json(['error' => 'У вас нет прав для создания подинструкции'], 403);
        }
        $newSubinstruction = $request->validate([
            'title' => 'required|string|max:255',
            'logo' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
        ]);
        $subinstruction = Subinstruction::create(array_merge($newSubinstruction, ['instruction_id' => $request->instruction_id]));

        return response()->json(new SubinstructionResource($subinstruction), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $subinstruction = Subinstruction::find($id);

        if (!$subinstruction) {
            return response()->json(['error' => 'Подинструкция не найдена'], 404);
        }

        return response()->json(new SubinstructionResource($subinstruction), 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();
        $role = RoleEnums::from($user->role);

        if (!$role->hasPermission(RoleEnums::ADMIN->value)) {
            return response()->json(['error' => 'У вас нет прав для редактирования подинструкции'], 403);
        }

        $subinstruction = Subinstruction::find($id);

        if (!$subinstruction) {
            return response()->json(['error' => 'Подинструкция не найдена'], 404);
        }

        $data = $request->validate([
            'title' => 'string|max:255',
            'logo' => 'string|max:255',
            'short_description' => 'string|max:500',
            'description' => 'string',
        ]);

        foreach ($subinstruction->fillable as $field) {
            if (isset($data[$field])) {
                $subinstruction->$field = $data[$field];
            }
        }

        $subinstruction->save();

        return response()->json(new SubinstructionResource($subinstruction), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subinstruction = Subinstruction::find($id);

        if (!$subinstruction) {
            return response()->json(['error' => 'Подинструкция не найдена'], 404);
        }

        $subinstruction->delete();

        return response()->json(['message' => 'Подинструкция удалена'], 200);
    }
}
