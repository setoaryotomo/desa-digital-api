<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\DevelopmentApplicantStoreRequest;
use App\Http\Requests\DevelopmentApplicantUpdateRequest;
use App\Http\Resources\DevelopmentApplicantResource;
use App\Http\Resources\PaginateResource;
use App\Interfaces\DevelopmentApplicantRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class DevelopmentApplicantController extends Controller
{
    private DevelopmentApplicantRepositoryInterface $developmentApplicantRepository;

    public function __construct(DevelopmentApplicantRepositoryInterface $developmentApplicantRepository)
    {
        $this->developmentApplicantRepository = $developmentApplicantRepository;
    }

    // public static function middleware()
    // {
    //     return [
    //         new Middleware(PermissionMiddleware::using(['development-applicant-list|development-applicant-create|development-applicant-edit|development-applicant-delete']), only: ['index', 'getAllPaginated', 'show']),
    //         new middleware(PermissionMiddleware::using(['development-applicant-create']), only: ['store']),
    //         new middleware(PermissionMiddleware::using(['development-applicant-edit']), only: ['update']),
    //         new middleware(PermissionMiddleware::using(['development-applicant-delete']), only: ['destroy']),
    //     ];
    // }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $developmentAplicants = $this->developmentApplicantRepository->getAll(
                $request->search,
                $request->limit,
                true
            );

            return ResponseHelper::JsonResponse(true, 'Data Pendaftar Pembangunan Berhasil Diambil', DevelopmentApplicantResource::collection($developmentAplicants), 200);
        } catch (\Exception $e) {
            return ResponseHelper::JsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function getAllPaginated(Request $request)
    {
        $request = $request->validate([
            'search' => 'nullable|string',
            'row_per_page' => 'required|integer'
        ]);

        try {
            $developmentAplicants = $this->developmentApplicantRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page'],
                true
            );

            return ResponseHelper::JsonResponse(true, 'Data Pendaftar Pembangunan Berhasil Diambil', PaginateResource::make($developmentAplicants, DevelopmentApplicantResource::class), 200);
        } catch (\Exception $e) {
            return ResponseHelper::JsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DevelopmentApplicantStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $developmentAplicant = $this->developmentApplicantRepository->create($request);

            return ResponseHelper::JsonResponse(true, 'Data Pendaftar Pembangunan Berhasil Dibuat', new DevelopmentApplicantResource($developmentAplicant), 201);
        } catch (\Exception $e) {
            return ResponseHelper::JsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $developmentAplicant = $this->developmentApplicantRepository->getById($id);

            if (!$developmentAplicant) {
                return ResponseHelper::JsonResponse(false, 'Data Pendaftar Pembangunan Tidak Ditemukan', null, 404);
            }

            return ResponseHelper::JsonResponse(true, 'Data Pendaftar Pembangunan Berhasil Ditemukan', new DevelopmentApplicantResource($developmentAplicant), 200);
        } catch (\Exception $e) {
            return ResponseHelper::JsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DevelopmentApplicantUpdateRequest $request, string $id)
    {
        $request = $request->validated();

        try {
            $developmentAplicant = $this->developmentApplicantRepository->getById($id);

            if (!$developmentAplicant) {
                return ResponseHelper::JsonResponse(false, 'Data Pendaftar Pembangunan Tidak Ditemukan', null, 404);
            }

            $developmentAplicant = $this->developmentApplicantRepository->update($id, $request);

            return ResponseHelper::JsonResponse(true, 'Data Pendaftar Pembangunan Berhasil Diupdate', new DevelopmentApplicantResource($developmentAplicant), 200);
        } catch (\Exception $e) {
            return ResponseHelper::JsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $developmentAplicant = $this->developmentApplicantRepository->getById($id);

            if (!$developmentAplicant) {
                return ResponseHelper::JsonResponse(false, 'Data Pendaftar Pembangunan Tidak Ditemukan', null, 404);
            }

            $this->developmentApplicantRepository->delete($id);

            return ResponseHelper::JsonResponse(true, 'Data Pendaftar Pembangunan Berhasil Dihapus', null, 200);
        } catch (\Exception $e) {
            return ResponseHelper::JsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}