<?php

namespace App\Http\Controllers\Api\V1\Article;

use App\Http\Controllers\Controller;
use App\Http\Resources\CertificateTypeResource;
use App\Models\Certificate;
use App\Models\CertificateType;
use App\Services\Article\CertificateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, CertificateService $certificateService)
    {
        $perPage = $request->query('per_page') ?? 10;
        $certificates = $certificateService->get($request, 1);

        return CertificateTypeResource::collection($certificates->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $certificateType = new CertificateType();
        $user = Auth::user();

        foreach ($certificateType->fillable as $field) {
            if ($request->has($field)) {
                $certificateType->$field = $request->$field;
            }
        }
        $certificateType->state = 0;
        $certificateType->user_id = $user->id;

        if ($request->hasFile('path')) {
            $file = $request->file('path');
            if ($file->isValid()) {
                $path = $file->store('certificate_types', 'public');
                $certificateType->path = $path;
            }
        }

        $certificateType->save();

        return response()->json(new CertificateTypeResource($certificateType));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $certificateType = CertificateType::find($id);
        if (!$certificateType) {
            return response()->json(['error' => 'Такой сертификат не найден'], 404);
        }
        return response()->json(new CertificateType($certificateType));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
