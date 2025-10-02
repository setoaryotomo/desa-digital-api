<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\EventParticipantStoreRequest;
use App\Http\Requests\EventParticipantUpdateRequest;
use App\Http\Resources\EventParticipantResource;
use App\Http\Resources\PaginateResource;
use App\Interfaces\EventParticipantRepositoryInterface;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class EventParticipantController extends Controller implements HasMiddleware
{

    private EventParticipantRepositoryInterface $eventParticipantRepository;

    public function __construct(EventParticipantRepositoryInterface $eventParticipantRepository)
    {
        $this->eventParticipantRepository = $eventParticipantRepository;
    }

    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using(['event-participant-list|event-participant-create|event-participant-edit|event-participant-delete']), only: ['index', 'getAllPaginated', 'show']),
            new middleware(PermissionMiddleware::using(['event-participant-create']), only: ['store']),
            new middleware(PermissionMiddleware::using(['event-participant-edit']), only: ['update']),
            new middleware(PermissionMiddleware::using(['event-participant-delete']), only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $eventParticipants = $this->eventParticipantRepository->getAll(
                $request->search,
                $request->limit,
                true
            );

            return ResponseHelper::JsonResponse(true, 'Data Pendaftar Event Berhasil Diambil', EventParticipantResource::collection($eventParticipants), 200);
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
            $eventParticipants = $this->eventParticipantRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page'],
                true
            );

            return ResponseHelper::JsonResponse(true, 'Data Pendaftar Event Berhasil Diambil', PaginateResource::make($eventParticipants, EventParticipantResource::class), 200);
        } catch (\Exception $e) {
            return ResponseHelper::JsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EventParticipantStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $eventParticipant = $this->eventParticipantRepository->create($request);

            return ResponseHelper::JsonResponse(true, 'Data Pendaftar Event Berhasil Ditambahkan', new EventParticipantResource($eventParticipant), 200);
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
            $eventParticipant = $this->eventParticipantRepository->getById($id);

            if (!$eventParticipant) {
                return ResponseHelper::JsonResponse(false, 'Data Pendaftar Event Tidak Ditemukan', null, 404);
            }

            return ResponseHelper::JsonResponse(true, 'Data Pendaftar Event Berhasil DiTampilkan', new EventParticipantResource($eventParticipant), 200);
        } catch (\Exception $e) {
            return ResponseHelper::JsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EventParticipantUpdateRequest $request, string $id)
    {
        try {

            $request = $request->validated();

            $eventParticipant = $this->eventParticipantRepository->getById($id);

            if (!$eventParticipant) {
                return ResponseHelper::JsonResponse(false, 'Data Pendaftar Event Tidak Ditemukan', null, 404);
            }

            $eventParticipant = $this->eventParticipantRepository->update($id, $request);

            return ResponseHelper::JsonResponse(true, 'Data Pendaftar Event Berhasil DiUpdate', new EventParticipantResource($eventParticipant), 200);
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
            $eventParticipant = $this->eventParticipantRepository->getById($id);

            if (!$eventParticipant) {
                return ResponseHelper::JsonResponse(false, 'Data Pendaftar Event Tidak Ditemukan', null, 404);
            }

            $this->eventParticipantRepository->delete($id);

            return ResponseHelper::JsonResponse(true, 'Data Pendaftar Event Berhasil Dihapus', null, 200);
        } catch (\Exception $e) {
            return ResponseHelper::JsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}