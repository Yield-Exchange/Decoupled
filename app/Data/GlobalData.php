<?php
namespace App\Data;

class GlobalData{
    public static $admin_can_access_routes = ['dashboard','update-timezone','update-preference','account-setting',
        'profile-setting','update-account-setting','update-profile-setting','users','users-data','users-create','users-delete','update-users-status',
        'deposit-chat-room-mark-read','deposit-chats.unread.count','notifications.unread.count'];
}