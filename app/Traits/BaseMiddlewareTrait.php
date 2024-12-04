<?php
namespace App\Traits;

trait BaseMiddlewareTrait{
    public function shouldUpdatePassword($user){
        if( $user ) {
            return $user->requires_password_update;
        }
        return false;
    }

    public function shouldConfirmUsersSeats($user){
        if( $user ) {
            $organization = $user->organization;
            if(!empty($organization)){
                return $organization->requires_to_confirm_users_seats && $user->role_name == 'Organization Administrator';
            }
        }
        return false;
    }
}
