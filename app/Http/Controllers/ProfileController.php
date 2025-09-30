<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\ProfileStoreRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\ProfileResource;
use App\Interfaces\ProfileRepositoryInterface;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    private ProfileRepositoryInterface $profileRepository;

    public function __construct(ProfileRepositoryInterface $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    public function index()
    {
        try {
            $profile = $this->profileRepository->get();

            if (!$profile) {
                return ResponseHelper::JsonResponse(false, 'Data Profile Tidak Ditemukan', null, 404);
            }

            return ResponseHelper::JsonResponse(true, 'Profil Berhasil Diambil', new ProfileResource($profile), 200);
        } catch (\Exception $e) {
            return ResponseHelper::JsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function store(ProfileStoreRequest $requst)
    {
        $requst = $requst->validated();

        try {
            $profile = $this->profileRepository->create($requst);

            return ResponseHelper::JsonResponse(true, 'Data Profile Berhasil Ditambahkan', new ProfileResource($profile), 201);
        } catch (\Exception $e) {
            return ResponseHelper::JsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function update(ProfileUpdateRequest $requst)
    {
        $requst = $requst->validated();

        try {
            $profile = $this->profileRepository->update($requst);

            return ResponseHelper::JsonResponse(true, 'Data Profile Berhasil Diubah', new ProfileResource($profile), 200);
        } catch (\Exception $e) {
            return ResponseHelper::JsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}