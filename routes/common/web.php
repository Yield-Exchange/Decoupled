<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\TradeCommon\TradeCommonController;
use App\Http\Controllers\UserAccountManagerController;

Route::group([
    'prefix' => 'common/trade/',
    ], function ($router) {
        Route::get('/get-products-list', [TradeCommonController::class, 'getProducts'])->name('trade.common.get-all-products');   
        Route::get('/get-colletarals-list', [TradeCommonController::class, 'getCollaterals'])->name('trade.common.get-colletarals-list');   
        Route::get('/get-preferred-collateral-list', [TradeCommonController::class, 'getPreferredCollaterals'])->name('trade.common.get-preferred-collateral-list');   
        Route::get('/get-collateral-givers', [TradeCommonController::class, 'getCollateralGivers'])->name('trade.common.get-collateral-givers');   
        Route::get('/get-collateral-takers', [TradeCommonController::class, 'getCollateralTakers'])->name('trade.common.get-collateral-takers');  
        Route::get('/get-settlement-dates', [TradeCommonController::class, 'getSettlementDates'])->name('trade.common.get-settlement-dates'); 
        Route::get('/get-basket-types', [TradeCommonController::class, 'getBasketTypes'])->name('trade.common.get-basket-types'); 
        Route::get('/get-counterparties', [TradeCommonController::class, 'getCounterParties'])->name('trade.common.get-counterparties');          
        Route::get('/get-all-interest-calculation-options', [TradeCommonController::class, 'getAllInterestCalculationOptions'])->name('trade.common.get-all-interest-calculation-options');        
        
        // Route::get('/get-counterparties', [TradeCommonController::class, 'getCounterParties'])->name('trade.common.get-counterparties');          
        
});

Route::group([
    'prefix' => 'common/account-management/',
    ], function ($router) {
        Route::get('/get-my-organizations', [UserAccountManagerController::class, 'getAllowedOrganizations'])->name('user-account.common.account-management.get-my-organizations');       
        Route::POST('/change-default-organization', [UserAccountManagerController::class, 'changeDefaultOrganization'])->name('user-account.common.account-management.change-default-organization');       
        Route::get('/get-org-level-permissions', [UserAccountManagerController::class, 'getOrgLevelPermissions'])->name('user-account.common.account-management.get-org-level-permissions');       
        Route::POST('/request-access-to-launchpad-item', [UserAccountManagerController::class, 'requestAccessToLaunchpadItem'])->name('user-account.common.account-management.request-access-to-launchpad-item');       
    
        
});
