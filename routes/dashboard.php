<?php

use Illuminate\Support\Facades\Route;

Route::get('dashboard', 'DashboardController@index')->name('dashboard');

Route::post('update-timezone', 'SettingsController@updateTimezone')->name('user.update-timezone');
Route::post('switch-organization', 'DashboardController@switchOrganization')->name('user.switch-organization');
Route::post('update-preference', 'SettingsController@updatePreference')->name('user.update-preference');
Route::get('account-setting', 'SettingsController@accountSetting')->name('user.account-setting');
Route::get('profile-setting', 'SettingsController@profileSetting')->name('user.profile-setting');
Route::post('update-account-setting', 'SettingsController@updateAccountSetting')->name('user.update-account-setting');
Route::post('update-profile-setting', 'SettingsController@updateProfileSetting')->name('user.update-profile-setting');
Route::get('notifications', 'NotificationsController@index')->name('user.notifications');
Route::get('notifications-data', 'NotificationsController@getData')->name('user.notifications-data');
Route::post('notifications-delete', 'NotificationsController@deleteNotifications')->name('user.notifications-delete');

Route::get('deposit-chat-room/{deposit_id}', 'DepositChatRoomController@index')->name('deposit.chat.room');
Route::get('deposit-chat-room/messages/{deposit_id}', 'DepositChatRoomController@getMessages')->name('deposit.chat.room.fetch-messages');
Route::post('deposit-chat-room', 'DepositChatRoomController@store')->name('deposit.chat.room.store');
Route::get('deposit-chat-room-mark-read', 'DepositChatRoomController@markAsRead')->name('deposit.chat.room.mark-read');
Route::get('deposit-chats.unread.count', 'DepositChatRoomController@countUnread')->name('deposit-chats.unread.count');
Route::get('notifications.unread.count', 'NotificationsController@countUnread')->name('notifications.unread.count');

Route::get('users', 'UsersController@index')->name('users.index');
//Route::get('users-data', 'UsersController@usersData')->name('users.data');
Route::post('users-data/{org_id?}', 'UsersController@usersDataNew')->name('users.data.new');
Route::get('users-data/{org_id?}', 'UsersController@usersData')->name('users.data');
Route::post('users-delete', 'UsersController@deleteUser')->name('users.delete');
Route::post('update-users-status/{action}', 'UsersController@doAction')->name('org.update-users-status');

Route::resource('counter-offer', 'CounterOfferController');
Route::get('offers-requests/{campaign_product_id}','CounterOfferController@campaignOffers');
Route::get('counter-offer/{counter_offer_id}/{action}', 'CounterOfferController@action');

Route::get('campaigns/campaign-product-summary/{id}', 'CampaignsController@CampaignProductsSummary')->middleware('auth.bank');
Route::get('campaigns/products-summary/{id}', 'CampaignsController@productsSummary')->middleware('auth.bank');
Route::get('campaigns/summary/{id}', 'CampaignsController@campaignSummary')->middleware('auth.bank');
Route::get('campaigns/products', 'CampaignsController@products')->middleware('auth.bank');
Route::get('campaigns/groups', 'CampaignsController@groups')->middleware('auth.bank');
Route::get('campaigns/drafts', 'CampaignsController@drafts')->middleware('auth.bank');

Route::resource('campaigns', 'CampaignsController')->middleware('auth.bank');

// Route::post('campaigns/{id}','CampaignsController@update');
//Route::post('campaigns/u/featured/{id?}','CampaignsController@featured')->name('campaign.offer.featured');
Route::get('depositor/campaigns', 'CampaignsController@index')->name('campaign.get.offer');
Route::post('depositor/campaigns', 'CampaignsController@store')->name('campaign.store.offer');
//Route::post('accepted_campaigns','CampaignsController@accepted_campaigns')->name('accepted_campaigns');

Route::middleware('auth.bank')->namespace('Bank')->group(base_path('routes/bank/web.php'));
Route::middleware('auth.depositor')->namespace('Depositor')->group(base_path('routes/depositor/web.php'));
Route::middleware('auth.admin')->prefix('yie-admin')->group(base_path('routes/admin/web.php'));



Route::get('campaigns/campaign-product-summary/{id}', 'CampaignsController@CampaignProductsSummary')->middleware('auth.bank');
Route::get('campaigns/products-summary/{id}', 'CampaignsController@productsSummary')->middleware('auth.bank');
Route::get('campaigns/summary/{id}', 'CampaignsController@campaignSummary')->middleware('auth.bank');

Route::get('campaigns/draft-summary/{id}', 'CampaignsController@campaignDraftSummary')->middleware('auth.bank');

Route::get('campaigns/edit-campaign/{id}', 'CampaignsController@editCampaign')->middleware('auth.bank');

Route::get('campaigns/products', 'CampaignsController@products')->middleware('auth.bank');
Route::get('campaigns/groups', 'CampaignsController@groups')->middleware('auth.bank');
Route::get('campaigns/drafts', 'CampaignsController@drafts')->middleware('auth.bank');
Route::resource('campaigns', 'CampaignsController')->middleware('auth.bank');


Route::get('load-province-cities', 'SettingsController@loadProvinceCity')->name('settings.load-cities');
Route::get('get-all-provinces', 'SettingsController@getAllProvinces')->name('get-all-provinces');
Route::get('get-all-industries', 'SettingsController@getAllIndustries')->name('get-all-industries');

Route::post('update-profile-info', 'SettingsController@updateProfileInfo')->name('settings.update-profile-info');
Route::post('delete-fi-file', 'SettingsController@deleteFIFile')->name('settings.delete-fi-file');
