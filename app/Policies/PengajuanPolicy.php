<?php

namespace App\Policies;

use App\Models\Pengajuan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PengajuanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isPemohon() || $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     * Acceptance Criteria: Draf desa lain tidak terlihat.
     */
    public function view(User $user, Pengajuan $pengajuan): Response
    {
        if ($user->isAdmin()) {
            return Response::allow();
        }

        if ($user->isPemohon() && $user->desa && $pengajuan->desa_id === $user->desa->id) {
            return Response::allow();
        }

        return Response::deny('Anda tidak memiliki hak akses untuk melihat pengajuan desa lain.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        if ($user->isPemohon() && $user->desa) {
            return Response::allow();
        }

        return Response::deny('Hanya perwakilan desa terdaftar yang dapat membuat pengajuan.');
    }

    /**
     * Determine whether the user can update the model.
     * Acceptance Criteria:
     * - Draf yang sudah dikirim tidak dapat diubah
     * - Perangkat desa dapat melihat, mengubah, dan menghapus draf milik desanya sebelum dikirim.
     */
    public function update(User $user, Pengajuan $pengajuan): Response
    {
        if (!$pengajuan->isDraft()) {
            return Response::deny('Draf yang sudah dikirim tidak dapat diubah.');
        }

        if ($user->isPemohon() && $user->desa && $pengajuan->desa_id === $user->desa->id) {
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
    public function delete(User $user, Pengajuan $pengajuan): Response
    {
        if (!$pengajuan->isDraft()) {
            return Response::deny('Pengajuan yang sudah dikirim tidak dapat dihapus.');
        }

        if ($user->isPemohon() && $user->desa && $pengajuan->desa_id === $user->desa->id) {
            return Response::allow();
        }

        return Response::deny('Anda hanya dapat menghapus draf milik desa Anda sendiri.');
    }

    /**
     * Determine whether the user can submit the draft.
     */
    public function submit(User $user, Pengajuan $pengajuan): Response
    {
        if (!$pengajuan->isDraft()) {
            return Response::deny('Pengajuan ini telah dikirim sebelumnya.');
        }

        if ($user->isPemohon() && $user->desa && $pengajuan->desa_id === $user->desa->id) {
            return Response::allow();
        }

        return Response::deny('Anda tidak memiliki izin untuk mengirimkan pengajuan ini.');
    }
}

