<?php

use App\Http\Controllers\Dashboard\Admin\AccountsManagement\AccountsManagementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\Admin\Trade\TradeController;

Route::resource('blogs-category', 'Admin\BlogCategoryController');
Route::post('blogs-category/data', 'Admin\BlogCategoryController@getData')->name('admin.blogs-category.data');
Route::get('blogs-category-delete', 'Admin\BlogCategoryController@deleteBlog')->name('admin.blogs-category.delete');

Route::resource('blogs-tag', 'Admin\BlogTagsController');
Route::post('blogs-tag/data', 'Admin\BlogTagsController@getData')->name('admin.blogs-tag.data');
Route::get('blogs-tag-delete', 'Admin\BlogTagsController@deleteBlogTag')->name('admin.tag-category.delete');
Route::resource('industries', 'Admin\IndustriesController');
Route::post('industries/data', 'Admin\IndustriesController@getData')->name('admin.industries.data');
Route::get('industries-delete', 'Admin\IndustriesController@delete')->name('admin.industries.delete');
Route::resource('blogs', 'Admin\BlogController');
Route::post('blogs/data', 'Admin\BlogController@getData')->name('admin.blogs.data');
Route::get('blog/delete', 'Admin\BlogController@deleteBlog')->name('admin.blog.delete');
Route::get('blog/approve', 'Admin\BlogController@approve')->name('admin.blog.approve');
Route::post('blogs/image-upload', 'Admin\BlogController@uploadBlogImage')->name('admin.blogs.image-upload');
Route::get('dashboard', 'DashboardController@adminDashboard')->name('admin.home');
Route::get('users/{type}', 'Admin\ManageUsersController@index')->name('admin.users');
Route::post('users/data/{type}', 'Admin\ManageUsersController@getData')->name('admin.users.data');
Route::get('users/mark_as_test/{type}/{organization_id}', 'Admin\ManageUsersController@markUnmarkAstest')->name('admin.users.mark_as_test');
Route::post('organization/user-limit/update/{organization_id}', 'Admin\ManageUsersController@updateUsersLimit')->name('organization.user-limit.update');
Route::post('organization/industry/update/{organization_id}', 'Admin\ManageUsersController@updateIndustry')->name('organization.update-industry.update');
Route::post('organization/fi-visibility/update/{organization_id}', 'Admin\ManageUsersController@enableVisibility')->name('organization.enable.visibility');
Route::post('organization/assign/super-admin/{organization_id}', 'Admin\ManageUsersController@assignSuperAdmin')->name('organization.assign-super-admin');
Route::post('admins/create', 'Admin\ManageUsersController@adminCreate')->name('admins.create');
Route::post('organization/aws/integrate/{organization_id}', 'Admin\ManageUsersController@awsIntegrte')->name('organization.aws-integrate');

Route::post('users/update-status/{action}', 'Admin\ManageUsersController@doAction')->name('admin.users.update-status');

Route::get('system/activity-logs', 'Admin\LogsController@index')->name('admin.system.activity.logs');
Route::post('system/activity-logs/data', 'Admin\LogsController@getData')->name('admin.system.activity.logs.data');
Route::post('login/activity-logs/data', 'Admin\LogsController@getLoginLogsData')->name('admin.login.activity.logs.data');

Route::get('system/settings', 'Admin\SystemSettingsController@index')->name('admin.system.settings');
Route::post('system/settings', 'Admin\SystemSettingsController@update')->name('admin.system.settings.submit');

Route::get('roles', 'UsersController@roles')->name('admin.roles.index');
Route::get('roles-data', 'UsersController@rolesData')->name('admin.roles.data');
Route::post('roles-create', 'UsersController@rolesCreate')->name('admin.roles.create');
Route::post('roles-delete/{id}', 'UsersController@deleteRole')->name('admin.roles.delete');
Route::get('roles-permissions/{role_id}', 'UsersController@rolePermissions')->name('admin.roles.permissions');
Route::post('roles/assign/permission', 'UsersController@assignRolePermission')->name('admin.roles.assign.permission');

Route::get('users/organizations/{organization_id}', 'UsersController@index')->name('admin.organizations.users.index');
Route::get('profile-setting', 'SettingsController@profileSetting')->name('admin.profile-setting');


Route::post('update-profile-setting', 'SettingsController@updateProfileSetting')->name('admin.update-profile-setting');
Route::post('delete-fi-file', 'SettingsController@deleteFIFile')->name('admin.delete-fi-file');

Route::get('allow-multi-organizations', 'Admin\ManageUsersController@allow_multi_organizations')->name('admin.allow-multi-organizations');
Route::get('enable-campaigns', 'Admin\ManageUsersController@enable_campaigns')->name('admin.enable-campaigns');

Route::post('update-organization-level', 'Admin\ManageUsersController@updateOrganizationLevel')->name('admin.update-organization-level');

Route::get('marketplace', 'Admin\MarketPlaceController@index')->name('admin.marketplace.index');
Route::post('marketplace/searchbybank', 'Admin\MarketPlaceController@filterByBank')->name('admin.marketplace.filterByBank');
Route::post('marketplace/searchbystatus', 'Admin\MarketPlaceController@filterByStatus')->name('admin.marketplace.filterByStatus');
Route::post('marketplace/import-offers', 'Admin\MarketPlaceController@importOffers')->name('admin.marketplace.importOffers');

Route::get('active-request-list', 'Admin\RequestController@index')->name('active.request.list');
Route::post('active-request-list', 'Admin\RequestController@getRequests')->name('active.request.fetch');

Route::get('post-request/{organization_id}', 'Depositor\PostRequestController@adminPostRequest')->name('admin.post-request');
Route::get('post-request-invites', 'Depositor\PostRequestController@getRequestInvites')->name('admin.post-request-invites');
Route::post('post-request-non-partnered-invite', 'Depositor\PostRequestController@postRequestNonPartnered')->name('admin.post-request-non-partnered-invite');
Route::post('post-request-submit', 'Depositor\PostRequestController@postRequestSubmit')->name('admin.post-request-submit');

Route::get('product-list', 'Admin\ProductsController@index')->name('product.list');
Route::post('product-list', 'Admin\ProductsController@get_products')->name('product.list');
Route::post('product-add', 'Admin\ProductsController@add_product')->name('product.add');
Route::post('product-toggle', 'Admin\ProductsController@toggle_status')->name('product.toggle');


Route::get('load-province-cities', 'SettingsController@loadProvinceCity')->name('settings.load-cities');
Route::get('get-all-provinces', 'SettingsController@getAllProvinces')->name('get-all-provinces');
Route::get('get-all-industries', 'SettingsController@getAllIndustries')->name('get-all-industries');

Route::post('update-profile-info', 'SettingsController@updateProfileInfo')->name('settings.update-profile-info');



Route::get(
    'repo-product-list',
    function () {
        return view('dashboard.admin.repoproducts.index');
    }
)->name('product.repo.list');
Route::get(
    'repsond-to-requests',
    function () {
        return view('dashboard.admin.respond.index');
    }
)->name('repsond-to-requests');
Route::get(
    'create-collateral',
    function () {
        return view('dashboard.admin.repocollaterals.index');
    }
)->name('create-collateral');
Route::get(
    'create-basket-types',
    function () {
        return view('dashboard.admin.basket-type.index');
    }
)->name('basket.type');
Route::get(
    'create-day-count-conventions',
    function () {
        return view('dashboard.admin.create-day-count-conventions.index');
    }
)->name('create-day-count-conventions');
Route::group([
    'prefix' => 'trade',
], function ($router) {
    Route::post('/add-new-product', [TradeController::class, 'addProduct'])->name('trade.ct.add-new-product');
    Route::get('/get-all-products', [TradeController::class, 'getProducts'])->name('trade.ct.get-all-products');
    Route::post('/update-product/{product_id}', [TradeController::class, 'updateProduct'])->name('trade.ct.update-product');
    Route::post('/activate-deactivate-product/{product_id}', [TradeController::class, 'activateDeactivateProduct'])->name('activate-deactivate-product');
    Route::post('/add-new-basket-types', [TradeController::class, 'addNewBasketTypes'])->name('add-new-basket-types');
    Route::post('/add-new-collateral', [TradeController::class, 'addTradeCollateral'])->name('add-new-collateral');
    Route::post('/add-day-convention', [TradeController::class, 'addDayConvention'])->name('add-day-convention');
});
Route::group([
    'prefix' => 'accounts-management/',
], function ($router) {
    Route::get('/get-org-access-requests', [AccountsManagementController::class, 'getAllowedOrganizations'])->name('user-account.common.account-management.get-my-organizations');
    Route::POST('/respond-to-access-request', [AccountsManagementController::class, 'respondOnAccessRequest'])->name('user-account.common.account-management.change-default-organization');
});
