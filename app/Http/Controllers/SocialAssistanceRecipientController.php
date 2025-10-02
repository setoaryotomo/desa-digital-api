<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\SocialAssistanceRecipientStoreRequest;
use App\Http\Requests\SocialAssistanceRecipientUpdateRequst;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\SocialAssistanceRecipientResource;
use App\Interfaces\SocialAssistanceRecipientRepositoryInterface;
use App\Models\SocialAssistanceRecipient;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class SocialAssistanceRecipientController extends Controller implements HasMiddleware
{

    private SocialAssistanceRecipientRepositoryInterface $socialAssistanceRecipientRepository;

    public function __construct(SocialAssistanceRecipientRepositoryInterface $socialAssistanceRecipientRepository)
    {
        $this->socialAssistanceRecipientRepository = $socialAssistanceRecipientRepository;
    }

    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using(['social-assistance-recipient-list|social-assistance-recipient-create|social-assistance-recipient-edit|social-assistance-recipient-delete']), only: ['index', 'getAllPaginated', 'show']),
            new middleware(PermissionMiddleware::using(['social-assistance-recipient-create']), only: ['store']),
            new middleware(PermissionMiddleware::using(['social-assistance-recipient-edit']), only: ['update']),
            new middleware(PermissionMiddleware::using(['social-assistance-recipient-delete']), only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $socialAssistanceRecipients = $this->socialAssistanceRecipientRepository->getAll(
                $request->search,
                $request->limit,
                true
            );

            return ResponseHelper::JsonResponse(true, 'Data Penerima Bantuan Sosial Berhasil Diambil', SocialAssistanceRecipientResource::collection($socialAssistanceRecipients), 200);
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
            $socialAssistanceRecipients = $this->socialAssistanceRecipientRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page'],
                true
            );

            return ResponseHelper::JsonResponse(true, 'Data Penerima Bantuan Sosial Berhasil Diambil', PaginateResource::make($socialAssistanceRecipients, SocialAssistanceRecipientResource::class), 200);
        } catch (\Exception $e) {
            return ResponseHelper::JsonResponse(false, $e->getMessage(), null, 500);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(SocialAssistanceRecipientStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $socialAssistanceRecipient = $this->socialAssistanceRecipientRepository->create($request);
            return ResponseHelper::JsonResponse(true, 'Data Penerima Bantuan Sosial Berhasil Dibuat', new SocialAssistanceRecipientResource($socialAssistanceRecipient), 200);
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
            $socialAssistanceRecipient = $this->socialAssistanceRecipientRepository->getById(
                $id
            );

            if (!$socialAssistanceRecipient) {
                return ResponseHelper::JsonResponse(false, 'Data Penerima Bantuan Tidak ditemukan', null, 404);
            }

            return ResponseHelper::JsonResponse(true, 'Data Penerima Bantuan Sosial Berhasil Ditemukan', new SocialAssistanceRecipientResource($socialAssistanceRecipient), 200);
        } catch (\Exception $e) {
            return ResponseHelper::JsonResponse(false, $e->getMessage(), null, 500);
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(SocialAssistanceRecipientUpdateRequst $request, string $id)
    {
        $request = $request->validated();

        try {
            $socialAssistanceRecipient = $this->socialAssistanceRecipientRepository->getById(
                $id
            );

            if (!$socialAssistanceRecipient) {
                return ResponseHelper::JsonResponse(false, 'Data Penerima Bantuan Tidak ditemukan', null, 404);
            }

            $socialAssistanceRecipient = $this->socialAssistanceRecipientRepository->update(
                $id,
                $request
            );

            return ResponseHelper::JsonResponse(true, 'Data Penerima Bantuan Sosial Berhasil Diupdate', new SocialAssistanceRecipientResource($socialAssistanceRecipient), 200);
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
            $socialAssistanceRecipient = $this->socialAssistanceRecipientRepository->getById(
                $id
            );

            if (!$socialAssistanceRecipient) {
                return ResponseHelper::JsonResponse(false, 'Data Penerima Bantuan Tidak ditemukan', null, 404);
            }

            $socialAssistanceRecipient = $this->socialAssistanceRecipientRepository->delete($id);

            return ResponseHelper::JsonResponse(true, 'Data Penerima Bantuan Sosial Berhasil Dihapus', null, 200);
        } catch (\Exception $e) {
            return ResponseHelper::JsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}