<?php

namespace App\Http\Controllers\Api\V1\Article;

use App\Http\Controllers\Controller;
use App\Http\Resources\CertificateResource;
use App\Services\Article\CertificateService;
use Illuminate\Http\Request;
use Log;

class CertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, CertificateService $certificateService)
    {
        $perPage = $request->query('per_page') ?? 10;
        $certificates = $certificateService->get($request, 0);

        return CertificateResource::collection($certificates->paginate($perPage));
        return $certificates->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
