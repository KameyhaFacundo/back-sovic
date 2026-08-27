<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        if($user->id_rol) {
            $user->load('rol.permisos');
            $permisos = $user->rol->permisos;
            $user->permisos()->syncWithoutDetaching($permisos->pluck('id_permiso'));
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        if($user->isDirty('id_rol')) {
            if($user->id_rol) {
                $user->load('rol.permisos');
                $permisos = $user->rol->permisos;
                $user->permisos()->sync($permisos->pluck('id_permiso'));
            } else {
                $user->permisos()->detach();
            }
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
