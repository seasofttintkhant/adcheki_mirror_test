<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Email;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmailPolicy
{
    use HandlesAuthorization;

    public function edit(Admin $admin, Email $email)
    {
        return !$email->contact;
    }

    public function update(Admin $admin, Email $email)
    {
        return !$email->contact;
    }

    public function remove(Admin $admin, Email $email)
    {
        return !$email->contact;
    }

    public function download(Admin $admin)
    {
        //
    }
}
