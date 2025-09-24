<?php

namespace App\Repositories;

use App\Http\Resources\SocialAssistanceResource;
use App\Interfaces\SocialAssistanceRecipientRepositoryInterface;
use App\Models\SocialAssistanceRecipient;
use Exception;
use Illuminate\Support\Facades\DB;

class SocialAssistanceRecipientRepository implements SocialAssistanceRecipientRepositoryInterface
{
    public function getAll(
        ?string $search,
        ?int $limit,
        bool $execute
    ) {
        $querry = SocialAssistanceRecipient::where(function ($querry) use ($search) {
            if ($search) {
                $querry->search($search);
            }
        });

        $querry->orderBy('created_at', 'desc');

        if ($limit) {
            $querry->take($limit);
        }

        if ($execute) {
            return $querry->get();
        }

        return $querry;
    }

    public function getAllPaginated(
        ?string $search,
        ?int $rowPerPage
    ) {
        $querry = $this->getAll(
            $search,
            $rowPerPage,
            false
        );

        return $querry->paginate($rowPerPage);
    }

    public function getById(
        string $id
    ) {
        $querry = SocialAssistanceRecipient::where('id', $id);

        return $querry->first();
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $socialAssistanceRecipient = new SocialAssistanceRecipient;
            $socialAssistanceRecipient->social_assistance_id = $data['social_assistance_id'];
            $socialAssistanceRecipient->head_of_family_id = $data['head_of_family_id'];
            $socialAssistanceRecipient->amount = $data['amount'];
            $socialAssistanceRecipient->reason = $data['reason'];
            $socialAssistanceRecipient->bank = $data['bank'];
            $socialAssistanceRecipient->account_number = $data['account_number'];

            if (isset($data['proof'])) {
                $socialAssistanceRecipient->proof = $data['proof'];
            }

            if (isset($data['status'])) {
                $socialAssistanceRecipient->proof = $data['status'];
            }

            $socialAssistanceRecipient->save();

            DB::commit();

            return $socialAssistanceRecipient;
        } catch (\Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }
    }

    public function update(
        string $id,
        array $data
    ) {
        DB::beginTransaction();

        try {
            $socialAssistanceRecipient = SocialAssistanceRecipient::find($id);
            $socialAssistanceRecipient->social_assistance_id = $data['social_assistance_id'];
            $socialAssistanceRecipient->head_of_family_id = $data['head_of_family_id'];
            $socialAssistanceRecipient->amount = $data['amount'];
            $socialAssistanceRecipient->reason = $data['reason'];
            $socialAssistanceRecipient->bank = $data['bank'];
            $socialAssistanceRecipient->account_number = $data['account_number'];

            if (isset($data['proof'])) {
                $socialAssistanceRecipient->proof = $data['proof'];
            }

            if (isset($data['status'])) {
                $socialAssistanceRecipient->status = $data['status'];
            }

            $socialAssistanceRecipient->save();

            DB::commit();

            return $socialAssistanceRecipient;
        } catch (\Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }
    }

    public function delete(
        string $id
    ) {
        DB::beginTransaction();

        try {
            $socialAssistanceRecipient = SocialAssistanceRecipient::find($id);
            $socialAssistanceRecipient->delete();

            DB::commit();

            return $socialAssistanceRecipient;
        } catch (\Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }
    }
}