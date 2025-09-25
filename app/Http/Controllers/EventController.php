<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\EventStoreRequest;
use App\Http\Requests\EventUpdateRequest;
use App\Http\Resources\EventResource;
use App\Http\Resources\PaginateResource;
use App\Interfaces\EventRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;
use Spatie\Permission\Middleware\PermissionMiddleware;

class EventController extends Controller
{
    private EventRepositoryInterface $eventRepository;

    public function __construct(EventRepositoryInterface $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    // public static function middleware()
    // {
    //     return [
    //         new Middleware(PermissionMiddleware::using(['event-list|event-create|event-edit|event-delete']), only: ['index', 'getAllPaginated', 'show']),
    //         new middleware(PermissionMiddleware::using(['event-create']), only: ['store']),
    //         new middleware(PermissionMiddleware::using(['event-edit']), only: ['update']),
    //         new middleware(PermissionMiddleware::using(['event-delete']), only: ['destroy']),
    //     ];
    // }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $events = $this->eventRepository->getAll(
                $request->search,
                $request->limit,
                true
            );

            return ResponseHelper::JsonResponse(true, 'Data Event Berhasil Diambil', EventResource::collection($events), 200);
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
            $events = $this->eventRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page'],
                true
            );

            return ResponseHelper::JsonResponse(true, 'Data Event Berhasil Diambil', PaginateResource::make($events, EventResource::class), 200);
        } catch (\Exception $e) {
            return ResponseHelper::JsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EventStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $event = $this->eventRepository->create($request);

            return ResponseHelper::JsonResponse(true, 'Data Event Berhasil Dibuat', new EventResource($event), 201);
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
            $event = $this->eventRepository->getById($id);

            if (!$event) {
                return ResponseHelper::JsonResponse(false, 'Data Event Tidak Ditemukan', null, 404);
            }

            return ResponseHelper::JsonResponse(true, 'Data Event Berhasil DiTampilkan', new EventResource($event), 200);
        } catch (\Exception $e) {
            return ResponseHelper::JsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EventUpdateRequest $request, string $id)
    {
        $request = $request->validated();

        try {
            $event = $this->eventRepository->getById($id);

            if (!$event) {
                return ResponseHelper::JsonResponse(false, 'Data Event Tidak Ditemukan', null, 404);
            }

            $event = $this->eventRepository->update(
                $id,
                $request
            );

            return ResponseHelper::JsonResponse(true, 'Data Event Berhasil Diupdate', new EventResource($event), 200);
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
            $event = $this->eventRepository->getById($id);

            if (!$event) {
                return ResponseHelper::JsonResponse(false, 'Data Event Tidak Ditemukan', null, 404);
            }

            $event = $this->eventRepository->delete($id);

            return ResponseHelper::JsonResponse(true, 'Data Event Berhasil Dihapus', null, 200);
        } catch (\Exception $e) {
            return ResponseHelper::JsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}