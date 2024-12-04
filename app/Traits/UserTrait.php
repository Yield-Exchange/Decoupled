<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Storage;
use Auth;

trait UserTrait
{

    public function getLoggedInUserDetails()
    {
        $user = auth()->user();
        return ['user_details' => $user, 'organization' => $this->getUserOrganizationDetails($user)];
    }
    public function getUserOrganizationDetails($user)
    {
    }
}
