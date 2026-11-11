<?php

namespace App\Policies;

use App\Models\Desa;
use App\Models\Pengajuan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PengajuanPolicy
{
    /**
     * Helper untuk cek kepemilikan desa baik dengan akun login maupun mode simulasi tanpa login
     */
    protected function isOwner(?User $user, Pengajuan $pengajuan): bool
    {
        if ($user) {
            if ($user->isAdmin()) {
                return true;
            }
            return $user->desa && ($pengajuan->kode_desa === $user->desa->kode_desa || $pengajuan->desa_id === $user->desa->id);
        }

        // Mode tanpa login: cek kesesuaian dengan session simulasi aktif
        $activeKode = session('simulasi_desa_kode');
        if (!$activeKode) {
            $default = Desa::where('nama_desa', 'like', '%Serang%')->first() ?: Desa::first();
            $activeKode = $default?->kode_desa;
        }

        return $pengajuan->kode_desa === $activeKode;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     * Acceptance Criteria: Draf desa lain tidak terlihat.
     */
    public function view(?User $user, Pengajuan $pengajuan): Response
    {
        if ($this->isOwner($user, $pengajuan)) {
            return Response::allow();
        }

        return Response::deny('Anda tidak memiliki hak akses untuk melihat pengajuan desa lain.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(?User $user): Response
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can update the model.
     * Acceptance Criteria:
     * - Draf yang sudah dikirim tidak dapat diubah
     * - Perangkat desa dapat melihat, mengubah, dan menghapus draf milik desanya sebelum dikirim.
     */
    public function update(?User $user, Pengajuan $pengajuan): Response
    {
        if (!$pengajuan->isDraft()) {
            return Response::deny('Draf yang sudah dikirim tidak dapat diubah.');
        }

        if ($this->isOwner($user, $pengajuan)) {
            return Response::allow();
        }

        return Response::deny('Anda hanya dapat mengubah draf pengajuan milik desa Anda sendiri.');
    }

    /**
     * Determine whether the user can delete the model.
     * Acceptance Criteria:
     * - Draf yang sudah dikirim tidak dapat diubah dan dihapus
     * - Perangkat desa dapat melihat, mengubah, dan menghapus draf milik desanya sebelum dikirim.
     */
    public function delete(?User $user, Pengajuan $pengajuan): Response
    {
        if (!$pengajuan->isDraft()) {
            return Response::deny('Pengajuan yang sudah dikirim tidak dapat dihapus.');
        }

        if ($this->isOwner($user, $pengajuan)) {
            return Response::allow();
        }

        return Response::deny('Anda hanya dapat menghapus draf milik desa Anda sendiri.');
    }

    /**
     * Determine whether the user can submit the draft.
     */
    public function submit(?User $user, Pengajuan $pengajuan): Response
    {
        if (!$pengajuan->isDraft()) {
            return Response::deny('Pengajuan ini telah dikirim sebelumnya.');
        }

        if ($this->isOwner($user, $pengajuan)) {
            return Response::allow();
        }

        return Response::deny('Anda tidak memiliki izin untuk mengirimkan pengajuan ini.');
    }
}
