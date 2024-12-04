<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\Depositor\CampaignOffersController;
use App\Http\Controllers\Dashboard\CT\TradeCTController;

Route::get('depositor/create-gic/{deposit_id}', 'PendingDepositsController@createGic')->name('depositor.create-gic');
Route::post('depositor/confirm/activate-deposit', 'PendingDepositsController@postCreateGic')->name('depositor.confirm-activate-deposit');

Route::get('review-offers', 'ReviewOffersController@index')->name('review-offers');
//new routes
Route::get('review-offers-new', 'ReviewOffersController@index')->name('review-offers');
Route::get('request-offers/{request_id}', 'ReviewOffersController@getRequestOffers')->name('request-offers');
Route::get('request-fis/{request_id}', 'ReviewOffersController@getRequestFIS')->name('request-fis');
Route::get('request-summary', 'ReviewOffersController@getRequestSummary')->name('request-summary');

Route::get('request-offer-summary', 'ReviewOffersController@getRequestOfferSummary')->name('request-offer-summary');
Route::get('get-reasons-for-withdraw', 'ReviewOffersController@getReasonsForWithdraw')->name('reasons-for-withdraw');
Route::get('get-offer-counters', 'ReviewOffersController@getOfferCounters')->name('get-offer-counters');
Route::get('get-rate_types', 'ReviewOffersController@getRateTypes')->name('get-rate_types');
//new routes

Route::get('review-offers-data', 'ReviewOffersController@getData')->name('review-offers-data');

Route::get('get-review-offers-data', 'ReviewOffersController@getOffers')->name('get-review-offers-data');

Route::get('get-review-offers-data-new', 'ReviewOffersController@getOffersNew')->name('get-review-offers-data');





Route::get('pending-deposits', 'PendingDepositsController@index')->name('pending-deposits');
Route::get('pending-deposits-data', 'PendingDepositsController@getPendingDeposits')->name('pending-deposits-data');
Route::get('pending-deposits-datas', 'PendingDepositsController@getData')->name('pending-deposits-datas');

Route::get('active-deposits', 'ActiveDepositsController@index')->name('active-deposits');
Route::get('active-deposits-data', 'ActiveDepositsController@getActiveDeposits')->name('active-deposits-data');

Route::get('inactivation-reasons', 'ActiveDepositsController@getInacticationreaseons')->name('inactivation-reasons');

Route::get('depositor-history', 'HistoryController@index')->name('depositor-history');

Route::get('deposits-history-data', 'HistoryController@getDepositHistoryData')->name('depositor.deposits-history-data');
Route::get('deposits-history-data-new', 'HistoryController@newDepositsHistoryDate')->name('depositor.deposits-history-data-new');
Route::get('request-history-data', 'HistoryController@getRequestHistoryData')->name('depositor.request-history-data');
Route::get('request-history-data2', 'HistoryController@getRequestHistoryData2')->name('depositor.request-history-data2');

Route::get('depositor-reports', 'ReportsController@index')->name('depositor.reports');
Route::get('depositor-reports-data', 'ReportsController@getData')->name('depositor.reports-data');

Route::get('pick-offers/export/{deposit_id}', 'ReviewOffersController@exportReviewOffers')->name('pick.offers.export');

Route::get('pick-offers/{deposit}', 'ReviewOffersController@pickOffers')->name('pick.offers');
Route::post('pick-offers-data', 'ReviewOffersController@pickOffersData')->name('pick.offers-data');

Route::get('get-prequest-institutions', 'ReviewOffersController@getRequestInstitutions')->name('get-request-institutions');
Route::get('get-prequest-offers', 'ReviewOffersController@getPRequestOffers')->name('get-request-offers');

Route::get('pick-offers-data-new/{request_id}', 'ReviewOffersController@getOffersFromRequests')->name('pick.offers-data-2');
Route::post('submit-selected-offers', 'ReviewOffersController@postPickedOffersData')->name('submit.selected.offers');
Route::get('calculate', 'ReviewOffersController@calculate')->name('calculate');
Route::post('calculate/rate', 'ReviewOffersController@fetchRate')->name('calculate.rate');

Route::get('post-request', 'PostRequestController@index')->name('depositor.post-request');
Route::get('post-request-invites', 'PostRequestController@getRequestInvites')->name('depositor.post-request-invites');
Route::post('post-request-non-partnered-invite', 'PostRequestController@postRequestNonPartnered')->name('depositor.post-request-non-partnered-invite');
Route::post('post-request-submit', 'PostRequestController@postRequestSubmit')->name('depositor.post-request-submit');

Route::post('post-request-withdraw/{request_id}', 'PostRequestController@postRequestWithdraw')->name('depositor.post-request-withdraw');
Route::post('withdraw-deposit', 'PendingDepositsController@depositWithdraw')->name('depositor.withdraw-deposit');
Route::post('mark-deposit-inactive/{deposit_id?}', 'ActiveDepositsController@markDepositInactive')->name('depositor.mark-deposit-inactive');

Route::get('edit-post-request/{request_id}', 'PostRequestController@index')->name('depositor.post-request-edit');

Route::get('request/summary/{request_id}', 'SummaryScreensController@view_request_summary')->name('depositor.summary-request');
Route::get('request/offer/summary/{offer_id}', 'SummaryScreensController@view_offers_summary_screen')->name('depositor.offer-summary');
Route::get('summary/request/invited_institutions/{request_id}', 'SummaryScreensController@view_invited_institutions')->name('depositor.summary-request-invited_institutions');
Route::get('summary/request/review_offers/{deposit_id}/{request_id?}', 'SummaryScreensController@review_offers_summary')->name('depositor.summary-request-review_offers_summary');


Route::get('inv-camp-offers/{ref_id?}', [CampaignOffersController::class, 'index'])->name('depositor.inv-camp-offers');
Route::get('inv-camp-offers-fetch-data', [CampaignOffersController::class, 'getOffers'])->name('depositor.inv-camp-offers-get-data');
Route::post('inv-camp-offers-store', [CampaignOffersController::class, 'store'])->name('depositor.inv-camp-offers-store');
Route::group([
    'prefix' => 'campaigns/depositor',
], function ($router) {
    Route::post('/register-depositor-campaign-product-view', [CampaignOffersController::class, 'registerDepositorCampaignProductView'])->name('register-depositor-campaign-product-view');
    Route::post('/register-depositor-campaign-view', [CampaignOffersController::class, 'registerDepositorCampaignView'])->name('register-depositor-campaign-view');
});
Route::group([
    'prefix' => 'campaigns/depositor/analytics',
], function ($router) {
    Route::get('/get-trend-data', [CampaignOffersController::class, 'getTrendData'])->name('get-trend-data');
});


Route::get('purchase-gic/{offer_id?}', [CampaignOffersController::class, 'getPuchasedOffer']);
Route::get('document-type', [CampaignOffersController::class, 'getDocumentTypes'])->name('get-document-types');
Route::get('organization-data/{organization_id}', [CampaignOffersController::class, 'getOrganizationData']);
Route::post('create-document', [CampaignOffersController::class, 'createOrganizationDocuments'])->name('create_organization_documents');
Route::post('user-update', [CampaignOffersController::class, 'updateUserDetails'])->name('User-update-info');
Route::post('update-organization/{organization_id}', [CampaignOffersController::class, 'updateOrganizationData'], 'updateOrganizationData')->name('Organization-update-data');
Route::post('update-document/{document_id}', [CampaignOffersController::class, 'editDocument'])->name('document-update');
Route::post('share-documents', [CampaignOffersController::class, 'shareDepositorDetails'])->name('depositorShareDocument');

Route::post('dep-add-an-entity', [CampaignOffersController::class, 'addOrUpdateEntity'])->name('dep-add-an-entity');
Route::post('dep-add-an-individual', [CampaignOffersController::class, 'addOrUpdateKeyIndividuals'])->name('dep-add-an-individual');
Route::post('dep-remove-an-individual', [CampaignOffersController::class, 'deleteKeyIndividuals'])->name('dep-remove-an-individual');
Route::post('dep-remove-an-entity', [CampaignOffersController::class, 'deleteEntity'])->name('dep-remove-an-entity');


// get fi wire tranfer details
Route::get('get-wire-tranfer-details/{organization_id}', [CampaignOffersController::class, 'getFiWireTransferDetails'])->name('get-wire-tranfer-details');
// function () {
//     return view('dashboard.depositor.campaigns.purchasegic');
// });

Route::group([
    'prefix' => 'trade/CT/',
], function ($router) {
    Route::post('/create-request', [TradeCTController::class, 'createRequest'])->name('trade.ct.create-request');

    Route::post('/update-request', [TradeCTController::class, 'updateRequest'])->name('trade.ct.update-request');

    Route::post('/withdraw-request', [TradeCTController::class, 'withdrawRequest'])->name('trade.ct.withdraw-request');



    Route::get('/get-trade-requests', [TradeCTController::class, 'getTradeRequests'])->name('trade.ct.get-trade-requests');
    Route::get('/get-trade-request', [TradeCTController::class, 'getTradeRequest'])->name('trade.ct.get-trade-request');

    Route::get('/get-trade-request-offer', [TradeCTController::class, 'getTradeRequestOffer'])->name('trade.ct.get-trade-request-offer');

    Route::get('/get-trade-request-offers', [TradeCTController::class, 'getTradeRequestOffers'])->name('trade.ct.get-trade-request-offers');
    Route::get('/get-trade-request-invited-institutions', [TradeCTController::class, 'getTradeRequestInvitedCGS'])->name('trade.ct.get-trade-request-invited-institutions');

    Route::post('/give-counter-offer', [TradeCTController::class, 'giveCounterOffer'])->name('trade.ct.give-counter-offer');
    Route::post('/select-offers', [TradeCTController::class, 'selectOffers'])->name('trade.ct.select-offers');
    Route::get('/get-pending-deposits', [TradeCTController::class, 'getPendingDeposits'])->name('trade.ct.get-pending-deposits');
    Route::get('/get-pending-deposit', [TradeCTController::class, 'getPendingDeposit'])->name('trade.ct.get-pending-deposit');

    Route::get('/get-deposits', [TradeCTController::class, 'getDeposits'])->name('trade.ct.get-deposits');
    Route::get('/get-offers', [TradeCTController::class, 'getOffers'])->name('trade.ct.get-offers');


    Route::post('/post-trade-events', [TradeCTController::class, 'postTradeEvent'])->name('trade.ct.early-terminate');

    Route::post('/respond-on-trade-event', [TradeCTController::class, 'respondOnTradeEvent'])->name('trade.ct.respond-on-trade-event');

    Route::get('/get-deposit-messages', [TradeCTController::class, 'getDepositMessages'])->name('trade.ct.get-deposit-messages');

    Route::post('/send-deposit-message', [TradeCTController::class, 'sendDepositMessage'])->name('trade.ct.send-deposit-message');

    Route::post('/mark-deposit-message-read', [TradeCTController::class, 'markDepositMessageRead'])->name('trade.ct.mark-deposit-message-read');


    Route::get('/get-offer-messages', [TradeCTController::class, 'getOfferMessages'])->name('trade.ct.get-offer-messages');
    Route::post('/send-offer-message', [TradeCTController::class, 'sendOfferMessage'])->name('trade.ct.send-offer-message');
});

Route::group([
    'prefix' => 'trade/market-place/CT/',
], function ($router) {
    Route::get('/get-my-requests', [TradeCTController::class, 'getPublishedRequests'])->name('trade.cg.get-my-requests');
    Route::get('/get-my-request-offers', [TradeCTController::class, 'getPublishedRequestOffers'])->name('trade.cg.get-my-request-offers');
    Route::get('/get-my-requests-offers', [TradeCTController::class, 'getPublishedRequestsOffers'])->name('trade.cg.get-my-requests-offers');
    Route::get('/get-offer-details', [TradeCTController::class, 'getOfferDetails'])->name('trade.cg.get-offer-details');
    Route::post('/confirm-market-offer', [TradeCTController::class, 'confirmMarketOffer'])->name('trade.cg.confirm-market-offer');
    Route::post('/give-counter-offer', [TradeCTController::class, 'counterOfferMarketOffer'])->name('trade.cg.give-counter-offer');

    
    Route::get('/get-other-related-products', [TradeCTController::class, 'getOfferOtherRelatedProductsOffers'])->name('trade.cg.get-other-related-products');
    Route::get('/get-my-related-products', [TradeCTController::class, 'getOfferMyRelatedProductsOffers'])->name('trade.cg.et-my-related-products');
    
});


Route::prefix('repos')->group(function () {
    Route::get('/post-request', function () {
        $unformattedusertimezone = auth()->user()->timezone;
        $formattedtimezone = formattedTimezone($unformattedusertimezone);
        return view('dashboard.repomarketplace.collateraltaker.postrequest.index', compact('formattedtimezone'));
    });
    Route::get('/edit-trade-request', function () {
        $unformattedusertimezone = auth()->user()->timezone;
        $formattedtimezone = formattedTimezone($unformattedusertimezone);
        return view('dashboard.repomarketplace.collateraltaker.postrequest.index', compact('formattedtimezone'));
    });
    Route::get('/repos-reviews', function () {
        $unformattedusertimezone = auth()->user()->timezone;
        $formattedtimezone = formattedTimezone($unformattedusertimezone);
        return view('dashboard.repomarketplace.collateraltaker.reviewoffers.index', compact('formattedtimezone'));
    });

    Route::get('/post-request-with-ai', function () {
        $unformattedusertimezone = auth()->user()->timezone;
        $formattedtimezone = formattedTimezone($unformattedusertimezone);
        return view('dashboard.repomarketplace.collateraltaker.post-with-ai.publish-rate-with-ai', compact('formattedtimezone'));
    });

    Route::get('/repos-reviews/summary/{request_id}', function () {
        $unformattedusertimezone = auth()->user()->timezone;
        $formattedtimezone = formattedTimezone($unformattedusertimezone);
        return view('dashboard.repomarketplace.collateraltaker.reviewoffers.view_request', compact('formattedtimezone'));
    });
    // pending

    Route::get('/ct-repos-pending-trades', function () {
        $unformattedusertimezone = auth()->user()->timezone;
        $formattedtimezone = formattedTimezone($unformattedusertimezone);
        return view('dashboard.repomarketplace.collateraltaker.pendingtrades.index', compact('formattedtimezone'));
    });
    Route::get('/ct-repos-pending-trades/summary/{request_id}', function () {
        $unformattedusertimezone = auth()->user()->timezone;
        $formattedtimezone = formattedTimezone($unformattedusertimezone);
        return view('dashboard.repomarketplace.collateraltaker.pendingtrades.view_request', compact('formattedtimezone'));
    });
    // my offers
    Route::get('/ct-repos-my-offers', function () {
        $unformattedusertimezone = auth()->user()->timezone;
        $formattedtimezone = formattedTimezone($unformattedusertimezone);
        return view('dashboard.repomarketplace.collateraltaker.myoffers.index', compact('formattedtimezone'));
    });
    Route::get('/ct-repos-offer/summary/{request_id}', function () {
        $unformattedusertimezone = auth()->user()->timezone;
        $formattedtimezone = formattedTimezone($unformattedusertimezone);
        return view('dashboard.repomarketplace.collateraltaker.myoffers.view_request', compact('formattedtimezone'));
    });
    // active trades
    Route::get('/ct-repos-active-trades', function () {
        $unformattedusertimezone = auth()->user()->timezone;
        $formattedtimezone = formattedTimezone($unformattedusertimezone);
        return view('dashboard.repomarketplace.collateraltaker.activetrades.index', compact('formattedtimezone'));
    });

    Route::get('/ct-repos-active-trades/summary/{request_id}', function () {
        $unformattedusertimezone = auth()->user()->timezone;
        $formattedtimezone = formattedTimezone($unformattedusertimezone);
        return view('dashboard.repomarketplace.collateraltaker.activetrades.view_request', compact('formattedtimezone'));
    });
    // history trades
    Route::get('/ct-repos-history-trades', function () {
        $unformattedusertimezone = auth()->user()->timezone;
        $formattedtimezone = formattedTimezone($unformattedusertimezone);
        return view('dashboard.repomarketplace.collateraltaker.historytrades.index', compact('formattedtimezone'));
    });
    Route::get('/ct-view-repo-history-trade/{request_id}', function () {
        $unformattedusertimezone = auth()->user()->timezone;
        $formattedtimezone = formattedTimezone($unformattedusertimezone);
        return view('dashboard.repomarketplace.collateraltaker.historytrades.view_request', compact('formattedtimezone'));
    });
    Route::get('/ct-view-repo-request-history-trade/{request_id}', function () {
        $unformattedusertimezone = auth()->user()->timezone;
        $formattedtimezone = formattedTimezone($unformattedusertimezone);
        return view('dashboard.repomarketplace.collateraltaker.historytrades.view_request_history', compact('formattedtimezone'));
    });
});
