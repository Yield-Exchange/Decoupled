<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\Bank\FICampaignController;
use App\Http\Controllers\Dashboard\CG\TradeCGController;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\DB;


Route::get('bank/create-gic/{deposit_id}', 'PendingDepositController@createGic')->name('bank.create-gic');

Route::get('new-requests', 'NewRequestsController@index')->name('new-requests');
Route::get('new-requests-data', 'NewRequestsController@getData')->name('new-requests-data');
Route::get('new-requests-data-new', 'NewRequestsController@getNewRequests')->name('new-requests-data-new');

Route::get('get-withdraw-offer-reasons', function () {
    return response()->json(DB::table('offer_withdrawal_reasons')->get());
});
Route::get('get-inprogress-data', 'InProgressController@getInProgressData')->name('get-inprogress-data-vue');
Route::get('in-progress', 'InProgressController@index')->name('in-progress');
Route::get('in-progress-data', 'InProgressController@getData')->name('in-progress-data');
Route::post('offer-withdraw/{offer_id}', 'InProgressController@offerWithdraw')->name('bank.offer-withdraw');
Route::post('mark-deposits-inactive/{deposit_id}', 'ActiveDepositController@markDepositInactive')->name('bank.mark-deposit-inactive');

Route::get('bank-pending-deposits', 'PendingDepositController@index')->name('bank.pending-deposits');
Route::get('bank-pending-deposits-data', 'PendingDepositController@getPendingDeposits')->name('bank.pending-deposits-data');
Route::get('bank-pending-deposits-data-old', 'PendingDepositController@getData')->name('bank.pending-deposits-data-old');

Route::get('bank-active-deposits', 'ActiveDepositController@index')->name('bank.active-deposits');
Route::get('bank-active-deposits-data', 'ActiveDepositController@getData')->name('bank.active-deposits-data');
Route::get('bank-active-deposits-data-new', 'ActiveDepositController@getActiveDeposits')->name('bank.active-deposits-data-new');

Route::get('bank-history', 'HistoryController@index')->name('depositor-history');
Route::get('get-bank-history-offer', 'HistoryController@getOfferHistory')->name('get-bank-history');
Route::get('get-bank-deposit-history', 'HistoryController@getDepositHistory')->name('get-bank-deposit-history');

Route::get('bank-inactivation-reasons', 'ActiveDepositController@getInacticationreaseons')->name('bank-inactivation-reasons');


Route::get('deposit-history-data', 'HistoryController@getDepositHistoryData')->name('bank.deposits-history-data');
Route::get('offers-history-data', 'HistoryController@getOffersHistoryData')->name('bank.offers-history-data');

Route::get('bank-reports', 'ReportsController@index')->name('bank.reports');
Route::get('bank-reports-data', 'ReportsController@getData')->name('bank.reports-data');

Route::get('deposit/summary/{offer_id}', 'SummaryScreensController@deposit_summary_screen')->name('bank.deposit-summary');
Route::get('offer/summary/{offer_id}', 'SummaryScreensController@offer_summary_screen')->name('bank.offer-summary');

Route::post('confirm/activate-deposit', 'PendingDepositController@activateDeposit')->name('bank.confirm-activate-deposit');

Route::get('place-offer/{request_id}/{offer_id?}', 'NewRequestsController@placeOffer')->name('bank.place-offer');
Route::post('submit/place-offer', 'NewRequestsController@submitPlaceOffer')->name('bank.submit.place-offer');

Route::post('user/non-partnered-fi-terms/create-group-review', 'NewRequestsController@nonPartneredFiAcceptInvitation')->name('user.non-partnered-fi-terms-review');

Route::group([
    'prefix' => 'campaigns/fi',
], function ($router) {
    Route::get('/', [FICampaignController::class, 'index'])->name('fi.campaigns.home');
    Route::get('/my-campaigns', [FICampaignController::class, 'getMyCampaigns'])->name('fi.campaigns.my-campaigns');

    Route::get('/get-campaign-details', [FICampaignController::class, 'getCampaignDetails'])->name('fi.get-campaign-details');

    Route::get('/get-all-products-types', [FICampaignController::class, 'getAllProductsTypes'])->name('fi.get-all-products-types');

    Route::get('/my-campaign-products', [FICampaignController::class, 'getMyCampaignProducts'])->name('fi.campaigns.my-campaign-products');
    Route::post('/update-campaign', [FICampaignController::class, 'updateCampaign'])->name('fi.campaigns.update-campaign');
    Route::post('/activate-deactivate-campaign', [FICampaignController::class, 'deactivateCampaign'])->name('fi.campaigns.activate-deactivate-campaign');
    Route::post('/my-campaigns/delete-campaign', [FICampaignController::class, 'deleteMyCampaign'])->name('fi.my-campaigns.delete-campaign');
    Route::post('/my-campaigns/deactivate-campaign', [FICampaignController::class, 'deactivateMyCampaign'])->name('fi.my-campaigns.deactivate-campaign');
    Route::post('/my-campaigns/remove-product', [FICampaignController::class, 'removeProductMyCampaign'])->name('fi.my-campaigns.remove-product-my-campaign');
    Route::post('/create-campaign', [FICampaignController::class, 'create'])->name('fi.campaigns.create');
    Route::post('/create-fi-campaign-product', [FICampaignController::class, 'createFICampaignProduct'])->name('fi.campaigns.create-fi-campaign-product');
    Route::post('/update-fi-campaign-product', [FICampaignController::class, 'updateFICampaignProduct'])->name('fi.campaigns.update-fi-campaign-product');
    Route::post('/delete-product', [FICampaignController::class, 'deleteFIProduct'])->name('fi.campaigns.delete-product');
    Route::post('/create-fi-campaign-specific-product', [FICampaignController::class, 'createFICampaignSpecificProduct'])->name('fi.campaigns.create-fi-campaign-specific-product');
    Route::post('/feature-unfeature-campaign', [FICampaignController::class, 'featureFICampaignProduct'])->name('fi.campaigns.feature-unfeature-campaign');
    Route::post('/update-campaign-product-info', [FICampaignController::class, 'updateCampaignProductInfo'])->name('fi.campaigns.update-campaign-product-info');
    Route::get('/my-groups', [FICampaignController::class, 'getMyGroups'])->name('fi.campaigns.groups.my-groups');
    Route::get('/get-group-depos', [FICampaignController::class, 'getMyGroup'])->name('fi.campaigns.groups.my-group');
    Route::get('/get-group-depos-count', [FICampaignController::class, 'getSelectedGroupsDepos'])->name('fi.campaigns.groups.my-group-deos-count');


    
    Route::get('/my-products', [FICampaignController::class, 'getMyProducts'])->name('fi.campaigns.groups.my-products');
    Route::post('/create-group', [FICampaignController::class, 'createGroup'])->name('fi.campaigns.groups.create-group');
    Route::post('/update-group', [FICampaignController::class, 'updateGroup'])->name('fi.campaigns.groups.update-group');
    Route::post('/delete-group', [FICampaignController::class, 'deleteGroup'])->name('fi.campaigns.delete-group');
    Route::post('/add-group-depositor', [FICampaignController::class, 'addGroupDepositor'])->name('fi.campaigns.groups.add-group-depositor');
    Route::post('/remove-group-depositor', [FICampaignController::class, 'removeGroupDepositor'])->name('fi.campaigns.groups.add-group-depositor');
    Route::post('/get-group-unlinked-depositors', [FICampaignController::class, 'getGroupUnlinkedDepositors'])->name('fi.get-group-unlinked-depositors');
    Route::get('/get-all-depos-ids', [FICampaignController::class, 'getMyDeposIds'])->name('fi.campaigns.groups.get-all-depos-ids');
});


Route::group([
    'prefix' => 'campaigns/fi/analytics',
], function ($router) {
    Route::get('/get-campaign-dashboard-insights', [FICampaignController::class, 'campaignDashboardSummary'])->name('fi.analytics.get-campaign-dashboard-insights');
    Route::get('/get-campaign-insights', [FICampaignController::class, 'campaignInsights'])->name('fi.analytics.get-campaign-insights');
    Route::get('/get-campaign-product-insights', [FICampaignController::class, 'campaignProductInsights'])->name('fi.analytics.get-campaign-product-insights');
});


Route::group([
    'prefix' => 'trade/CG/',
], function ($router) {
    Route::post('/give-offers', [TradeCGController::class, 'giveOffer'])->name('trade.cg.give-offers');
    Route::post('/withdraw-offer', [TradeCGController::class, 'withdrawOffer'])->name('trade.cg.withdraw-offer');
    Route::get('/get-trade-requests', [TradeCGController::class, 'getTradeRequests'])->name('trade.cg.get-trade-requests');
    Route::get('/get-trade-request', [TradeCGController::class, 'getTradeRequest'])->name('trade.cg.get-trade-request');
    Route::get('/get-trade-request-offer', [TradeCGController::class, 'getTradeRequestOffer'])->name('trade.cg.get-trade-request-offer');

    Route::post('/give-counter-offer', [TradeCGController::class, 'giveCounterOffer'])->name('trade.cg.give-counter-offer');

    Route::post('/respond-counter-offer', [TradeCGController::class, 'respondCounterOffer'])->name('trade.cg.respond-counter-offer');
    Route::get('/get-trade-request-offers', [TradeCGController::class, 'getTradeRequestOffers'])->name('trade.cg.get-trade-request-offers');
    Route::post('/edit-offer', [TradeCGController::class, 'editOffer'])->name('trade.cg.edit-offer');

    Route::get('/get-pending-deposits', [TradeCGController::class, 'getPendingDeposits'])->name('trade.cg.get-pending-deposits');
    Route::get('/get-pending-deposit', [TradeCGController::class, 'getPendingDeposit'])->name('trade.cg.get-pending-deposit');

    Route::get('/get-offers', [TradeCGController::class, 'getOffers'])->name('trade.cg.get-offers');

    Route::get('/get-deposits', [TradeCGController::class, 'getDeposits'])->name('trade.cg.get-deposits');

    Route::post('/post-trade-events', [TradeCGController::class, 'postTradeEvent'])->name('trade.cg.early-terminate');

    Route::post('/respond-on-trade-event', [TradeCGController::class, 'respondOnTradeEvent'])->name('trade.cg.respond-on-trade-event');

    Route::get('/get-deposit-messages', [TradeCGController::class, 'getDepositMessages'])->name('trade.cg.get-deposit-messages');


    Route::post('/activate-trade', [TradeCGController::class, 'activateTrade'])->name('trade.cg.activate-trade');
    Route::post('/send-message', [TradeCGController::class, 'sendMessage'])->name('trade.cg.send-message');


    Route::post('/send-deposit-message', [TradeCGController::class, 'sendDepositMessage'])->name('trade.cg.send-deposit-message');

    Route::post('/mark-deposit-message-read', [TradeCGController::class, 'markDepositMessageRead'])->name('trade.ct.mark-deposit-message-read');


    Route::get('/get-offer-messages', [TradeCGController::class, 'getOfferMessages'])->name('trade.cg.get-offer-messages');
    Route::post('/send-offer-message', [TradeCGController::class, 'sendOfferMessage'])->name('trade.cg.send-offer-message');
    Route::post('/mark-offer-message-read', [TradeCGController::class, 'markOfferMessageRead'])->name('trade.cg.mark-offer-message-read');

    Route::post('/add-basket', [TradeCGController::class, 'addBasket'])->name('trade.ct.add-basket');
    Route::post('/validate-counter-party-entry', [TradeCGController::class, 'validateCounterPartyEntry'])->name('trade.cg.validate-counter-party-entry');
    Route::post('/validate-bilateral-collateral', [TradeCGController::class, 'validateBilateralCollateral'])->name('trade.cg.validate-bilateral-collateral');

    Route::get('/get-offer-messages', [TradeCGController::class, 'getOfferMessages'])->name('trade.cg.get-offer-messages');
    Route::get('/get-offer-messages', [TradeCGController::class, 'getOfferMessages'])->name('trade.cg.get-offer-messages');
    Route::get('/get-baskets', [TradeCGController::class, 'getBaskets'])->name('trade.cg.get-baskets');


    Route::get('/get-colleterals-issuers', [TradeCGController::class, 'getColleteralsIssuers'])->name('trade.cg.get-colleterals-issuers');
    Route::get('/get-colleterals', [TradeCGController::class, 'getCollaterals'])->name('trade.cg.get-colleterals');

    Route::get('/get-colleteral', [TradeCGController::class, 'getCollateral'])->name('trade.cg.get-colleteral');
    Route::get('/get-basket', [TradeCGController::class, 'getBasket'])->name('trade.cg.get-basket');

    Route::get('/get-basket-tri-party', [TradeCGController::class, 'getBasketTriparty'])->name('trade.cg.get-basket-tri-party');

    Route::post('/add-third-parties-to-basket', [TradeCGController::class, 'addCounterPartiesToBasket'])->name('trade.ct.add-third-parties-to-basket');

    Route::post('/update-third-party-status', [TradeCGController::class, 'archiveThirdParty'])->name('trade.cg.archive-third-party');
    Route::post('/update-org-collateral-status', [TradeCGController::class, 'archiveOrganizationCollateral'])->name('trade.cg.archive-org-collateral');

    Route::post('/add-cusip-to-issuer', [TradeCGController::class, 'addCusipToIssuer'])->name('trade.cg.add-cusip-to-issuer');
    Route::post('/update-cusip-to-issuer', [TradeCGController::class, 'updateCusipToIssuer'])->name('trade.cg.update-cusip-to-issuer');

    Route::post('/create-request', [TradeCGController::class, 'requestForMoney'])->name('trade.cg.create-request');
    Route::post('/update-request-offer', [TradeCGController::class, 'updateRequestOffer'])->name('trade.cg.update-request-offer');
    Route::post('/withdraw-request-offer', [TradeCGController::class, 'withdrawRequestOffer'])->name('trade.cg.withdraw-request-offer');
});

Route::group([
    'prefix' => 'trade/market-place/CG/',
], function ($router) {
    Route::get('/get-my-requests', [TradeCGController::class, 'getPublishedRequests'])->name('trade.cg.get-my-requests');
    Route::get('/get-my-request-offers', [TradeCGController::class, 'getPublishedRequestOffers'])->name('trade.cg.get-my-request-offers');
    Route::get('/get-my-requests-offers', [TradeCGController::class, 'getPublishedRequestsOffers'])->name('trade.cg.get-my-requests-offers');
    Route::get('/get-my-requests-matured-offers', [TradeCGController::class, 'getPublishedRequestsMaturedOffers'])->name('trade.cg.get-my-requests-matured-offers');
    
    Route::get('/get-my-requests-offer-details', [TradeCGController::class, 'getPublishedRequestsOfferDetails'])->name('trade.cg.get-my-requests-offer-details');
    Route::get('/get-offer-details', [TradeCGController::class, 'getOfferDetails'])->name('trade.cg.get-offer-details');
    
});



Route::prefix('repos')->group(function () {
    Route::get('/cg-repos-pending-trades', function () {
        $unformattedusertimezone = auth()->user()->timezone;
        $formattedtimezone = formattedTimezone($unformattedusertimezone);
        return view('dashboard.repomarketplace.collateralgiver.pendingtrades.index', compact('formattedtimezone'));
    });
    Route::get('/cg-view-repo-pending-trade/{request_id}', function () {
        $unformattedusertimezone = auth()->user()->timezone;
        $formattedtimezone = formattedTimezone($unformattedusertimezone);
        return view('dashboard.repomarketplace.collateralgiver.pendingtrades.view_request', compact('formattedtimezone'));
    });
    Route::get('/cg-repos-active-trades', function () {
        $unformattedusertimezone = auth()->user()->timezone;
        $formattedtimezone = formattedTimezone($unformattedusertimezone);
        return view('dashboard.repomarketplace.collateralgiver.activetrades.index', compact('formattedtimezone'));
    });
    Route::get('/cg-view-repo-active-trade/{request_id}', function () {
        $unformattedusertimezone = auth()->user()->timezone;
        $formattedtimezone = formattedTimezone($unformattedusertimezone);
        return view('dashboard.repomarketplace.collateralgiver.activetrades.view_request', compact('formattedtimezone'));
    });
    Route::get('/cg-repos-history-trades', function () {
        $unformattedusertimezone = auth()->user()->timezone;
        $formattedtimezone = formattedTimezone($unformattedusertimezone);
        return view('dashboard.repomarketplace.collateralgiver.historytrades.index', compact('formattedtimezone'));
    });
    Route::get('/cg-view-repo-history-trade/{request_id}', function () {
        $unformattedusertimezone = auth()->user()->timezone;
        $formattedtimezone = formattedTimezone($unformattedusertimezone);
        return view('dashboard.repomarketplace.collateralgiver.historytrades.view_request', compact('formattedtimezone'));
    });
    Route::get('/publish-rates-offers', function () {
        $unformattedusertimezone = auth()->user()->timezone;
        $formattedtimezone = formattedTimezone($unformattedusertimezone);
        $prime_rate = SystemSetting::where([['status', 'ACTIVE'], ['setting_type', 'rate']])->select(['key', 'rate_label', 'value'])->get();

        return view('dashboard.repomarketplace.collateralgiver.publish-rates-offers.publish-rates-offers', compact('formattedtimezone', 'prime_rate'));
    });
    Route::get('/publish-rates-offers/ai', function () {
        $unformattedusertimezone = auth()->user()->timezone;
        $formattedtimezone = formattedTimezone($unformattedusertimezone);
        $prime_rate = SystemSetting::where([['status', 'ACTIVE'], ['setting_type', 'rate']])->select(['key', 'rate_label', 'value'])->get();

        return view('dashboard.repomarketplace.collateralgiver.publish-rates-offers.publish-rate-with-ai', compact('formattedtimezone', 'prime_rate'));
    });
    Route::get('/view-all-new-requests', function () {
        return view('dashboard.repomarketplace.collateralgiver.new-requests.new-request');
    });
    Route::get('/view-trade-request-summary/{request_id}', function () {
        $prime_rate = SystemSetting::where([['status', 'ACTIVE'], ['setting_type', 'rate']])->select(['key', 'rate_label', 'value'])->get();

        return view('dashboard.repomarketplace.collateralgiver.new-requests.view-request-summary', compact('prime_rate'));
    });
    Route::get('/view-all-in-progress', function () {
        return view('dashboard.repomarketplace.collateralgiver.inprogress.in-progress');
    });
    Route::get('/view-in-progress-summary/{request_id}', function () {
        return view('dashboard.repomarketplace.collateralgiver.inprogress.in-progress-summary');
    });

    Route::get('/view-baskets', function () {
        return view('dashboard.repomarketplace.collateralgiver.repo-baskets.view-baskets');
    });
    Route::get('/view-triparty-baskets/{baskettype?}', function () {
        return view('dashboard.repomarketplace.collateralgiver.repo-baskets.view-triparty-baskets');
    });
    Route::get('/view-bilateral-baskets/{baskettype?}', function () {
        return view('dashboard.repomarketplace.collateralgiver.repo-baskets.view-bilateral-baskets');
    });
    Route::get('/create-basket', function () {
        return view('dashboard.repomarketplace.collateralgiver.repo-baskets.create-baskets');
    });
    Route::get('/view-collaterals', function () {
        return view('dashboard.repomarketplace.collateralgiver.repo-baskets.view-collaterals');
    });
});
