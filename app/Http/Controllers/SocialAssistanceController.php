<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\SocialAssistanceStoreRequest;
use App\Http\Requests\SocialAssistanceUpdateRequest;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\SocialAssistanceResource;
use App\Interfaces\SocialAssistanceRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class SocialAssistanceController extends Controller implements HasMiddleware
{

    private SocialAssistanceRepositoryInterface $socialAssistanceRepository;

    public function __construct(SocialAssistanceRepositoryInterface $socialAssistanceRepository)
    {
        $this->socialAssistanceRepository = $socialAssistanceRepository;
    }

    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using(['social-assistance-list|social-assistance-create|social-assistance-edit|social-assistance-delete']), only: ['index', 'getAllPaginated', 'show']),
            new middleware(PermissionMiddleware::using(['social-assistance-create']), only: ['store']),
            new middleware(PermissionMiddleware::using(['social-assistance-edit']), only: ['update']),
            new middleware(PermissionMiddleware::using(['social-assistance-delete']), only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $socialAssistances = $this->socialAssistanceRepository->getAll(
                $request->search,
                $request->limit,
                true
            );

            return ResponseHelper::JsonResponse(true, 'Data Bantuan Sosial Berhasil Diambil', SocialAssistanceResource::collection($socialAssistances), 200);
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
            $socialAssistances = $this->socialAssistanceRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page']
            );

            return ResponseHelper::JsonResponse(true, 'Data Bantuan Sosial Berhasil Diambil', PaginateResource::make($socialAssistances, SocialAssistanceResource::class), 200);
        } catch (\Exception $e) {
            return ResponseHelper::JsonResponse(false, $e->getMessage(), null, 500);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(SocialAssistanceStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $socialAssistance = $this->socialAssistanceRepository->create($request);

            return ResponseHelper::JsonResponse(true, 'Data Bantuan Sosial Berhasil Ditambahkan', new SocialAssistanceResource($socialAssistance), 200);
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
            $socialAssistance = $this->socialAssistanceRepository->getById($id);

            if (!$socialAssistance) {
                return ResponseHelper::JsonResponse(false, 'Data Bantuan Sosial Tidak Ditemukan', null, 404);
            }

            return ResponseHelper::JsonResponse(true, 'Data Bantuan Sosial Berhasil Diambil', new SocialAssistanceResource($socialAssistance), 200);
        } catch (\Exception $e) {
            return ResponseHelper::JsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SocialAssistanceUpdateRequest $request, string $id)
    {
        $request = $request->validated();

        try {
            $socialAssistance = $this->socialAssistanceRepository->getById($id);

            if (!$socialAssistance) {
                return ResponseHelper::JsonResponse(false, 'Data Bantuan sosial Tidak Ditemukan', null, 404);
            }

            $socialAssistance = $this->socialAssistanceRepository->update(
                $id,
                $request
            );


            return ResponseHelper::JsonResponse(true, 'Data Bantuan Sosial Berhasil Update', new SocialAssistanceResource($socialAssistance), 200);
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
            $socialAssistance = $this->socialAssistanceRepository->getById($id);

            if (!$socialAssistance) {
                return ResponseHelper::JsonResponse(false, 'Data Bantuan Sosial Tidak Ditemukan', null, 404);
            }

            $socialAssistance = $this->socialAssistanceRepository->delete($id);

            return ResponseHelper::JsonResponse(true, 'Data Bantuan Sosial Berhasil Hapus', null, 200);
        } catch (\Exception $e) {
            return ResponseHelper::JsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}