import ChatMessages from "./components/ChatMessages.vue";
import ChatForm from "./components/ChatForm.vue";
import NotifyCount from "./components/notifications/NotifyCount.vue";
import ChatsCount from "./components/notifications/ChatsCount.vue";
import Login from "./components/auth/Login";
import DepositorSignUp from "./components/auth/Depositor";

import Vue from "vue";
import ResetPassword from "./components/auth/ResetPassword";
import ChangePassword from "./components/auth/ChangePassword";
import VerifyOtp from "./components/auth/VerifyOtp";
import SignUp from "./components/auth/SignUp";
import SignUpStep1 from "./components/auth/SignUpStep1";
import SignUpStep2 from "./components/auth/SignUpStep2";
import AboutUs from "./components/home/AboutUs";
import TeamMemberCard from "./components/home/TeamMemberCard";
import Profile from "./components/dashboard/Profile";
import RespondToRequest from "./components/dashboard/respond/Index.vue";
import AccountSetting from "./components/dashboard/AccountSetting";

import DepositorAccountSetting from "./components/dashboard/AccountSetting_bak";

import CreateGic from "./components/dashboard/CreateGic";
import UserForm from "./components/dashboard/UserForm";
import UserList from "./components/dashboard/UserList";
import AdminList from "./components/dashboard/AdminList";
import CreateBlog from "./components/blogs/CreateBlog";
import BlogDetails from "./components/blogs/BlogDetails";
import BlogListing from "./components/blogs/BlogListing";
import BlogCard from "./components/blogs/BlogCard";
import YETable from "./components/dashboard/YETable";
import TestComponent from "./components/blogs/Test";
import SignUpStep3 from "./components/auth/SignUpStep3";

import MButton from "./components/shared/Button";
import MOffer from "./components/marketplace/shared/Offer";
import MOfferFI from "./components/marketplace/shared/OfferFI";
import MAddOfferModel from "./components/marketplace/shared/AddOfferModel";
import MUpdateFeatureModel from "./components/marketplace/shared/UpdateFeatureModel";
import MDefaultPage from "./components/marketplace/bank/Default";
import FilterPage1 from "./components/marketplace/depositor/FilterPage1";
import DepositorMarketPlace from "./components/marketplace/depositor/MarketPlace";
import Featured from "./components/marketplace/depositor/Featured";

import AdminMarketplace from "./components/marketplace/Admin/Dashboard";
import Calculator from "./components/shared/Calculator";
import RatesCalculator from "./components/shared/RatesCalculator";
import BankCampaign from "./components/campaigns/bank/Index.vue";

import DraftsCampaign from "./components/campaigns/bank/Drafts.vue";
import GroupsCampaign from "./components/campaigns/bank/Groups.vue";
import ProductsCampaign from "./components/campaigns/bank/Products.vue";
import ProductSummary from "./components/campaigns/bank/ProductSummary";
import CampaignDetailAndSummary from "./components/campaigns/bank/CampaignDetailAndSummary";
import DraftCampaignDetailAndSummary from "./components/campaigns/bank/DraftCampaignDetailAndSummary";
import CampaignEditPage from "./components/campaigns/bank/CampaignEditPage.vue";
import CampaignProductSummary from "./components/campaigns/bank/CampaignProductSummary";
import DepositorCampaignOffer from "./components/campaigns/depositor/Index";
import AccountSettingsSection from "./components/dashboard/AccountSettingsSection";
import Visibility from "./components/dashboard/Visibility";

import SwitchOrganization from './components/switchorganization/index.vue'

import RequestList from "./components/dashboard/RequestList";
import ProductList from "./components/dashboard/ProductList";
import RepoProductList from "./components/dashboard/RepoProductList";
import BasketType from "./components/dashboard/BasketType";
import DayCountIndex from "./components/dashboard/DayCountIndex.vue";
import CollateralType from "./components/dashboard/CollateralList.vue";

import PurchaseGic from "./components/campaigns/depositor/single-offer/purchasegic/PurchaseGic";

import RequestAccount from "./components/auth/RequestAccount";
// new sign up component section
import SignUpIndex from "./components/auth/signup/SignUpIndex";
import TermsAndConditions from "./components/auth/signup/subpages/TermsAndConditions";

//end of  new sign up section
//post request components import
import ReviewNewOffers from "./components/post-requests/depositor/Review-offers.vue";
import RequestSummary from "./components/post-requests/depositor/request-summary.vue";
import RequestOfferSummary from "./components/post-requests/depositor/RequestOfferSummary.vue";
import RequestFIS from "./components/post-requests/depositor/request-fis.vue";
import RequestOffers from "./components/post-requests/depositor/request-offers.vue";
// post request
import FIDepositSummary from "./components/postrequest/bank/activedeposits/DepositSummary.vue";
import PendingDeposits from "./components/postrequest/depositor/pendingdeposits/PendingDeposits.vue";
import FIPendingDeposits from "./components/postrequest/bank/pendingdeposits/PendingDeposits.vue";
import DepositorCreateGIc from "./components/postrequest/depositor/pendingdeposits/DepositorCreateGIc.vue";
import BankCreateGIc from "./components/postrequest/bank/pendingdeposits/BankCreateGIc.vue";
import DepositSummary from "./components/postrequest/depositor/pendingdeposits/DepositSummary.vue";
import ActiveDeposits from "./components/postrequest/depositor/activedeposits/ActiveDeposits.vue";
import BankActiveDeposits from "./components/postrequest/bank/activedeposits/ActiveDeposits.vue";
import DepositHistory from "./components/postrequest/depositor/history/DepositHistory.vue";
import ReviewOffers from "./components/postrequest/depositor/history/ReviewOffers.vue";
import ViewSummary from "./components/postrequest/depositor/history/ViewSummary.vue";
import PostReqIndex from "./components/postrequest/depositor/postrequest/PostReqIndex.vue";


// new requests
import RequestListing from './components/postrequest/bank/newrequests/RequestListing.vue'
import NewRequestSummary from './components/postrequest/bank/newrequests/RequestSummary.vue'

// in progress 
import InProgressDeposits from "./components/postrequest/bank/inprogressdeposits/InProgressDeposits.vue";
import ViewOfferCard from "./components/postrequest/bank/inprogressdeposits/actions/ViewOfferCard.vue";
import HistoryCard from "./components/history/bank/HistoryCard.vue";

// repos marketplace

// ct
// post request
import PostRepoDepositOffer from "./components/repo-marketplace/collateraltaker/postrequest/PostRepoDepositOffer.vue";
// review offers
import RepoReviewRequest from "./components/repo-marketplace/collateraltaker/reviewoffers/Index.vue";
import RepoSummary from "./components/repo-marketplace/collateraltaker/reviewoffers/RepoSummary.vue";
// pending trades
import RepoCtPendingTrade from "./components/repo-marketplace/collateraltaker/pendingtrades/Index.vue";
import RepoCtPendingTradeSummary from "./components/repo-marketplace/collateraltaker/pendingtrades/OfferSummary.vue";

// pending trades
import RepoCtMyOffers from "./components/repo-marketplace/collateraltaker/myoffers/Index.vue";
import RepoCtMyOffersSummary from "./components/repo-marketplace/collateraltaker/myoffers/OfferSummary.vue";
// Active trades
import RepoCtActiveTrade from "./components/repo-marketplace/collateraltaker/activetrades/Index.vue";
import RepoCtActiveTradeSummary from "./components/repo-marketplace/collateraltaker/activetrades/OfferSummary.vue";
// History trades
import RepoCtHistoryTrade from "./components/repo-marketplace/collateraltaker/history/Index.vue";
import RepoCtHistoryTradeSummary from "./components/repo-marketplace/collateraltaker/history/DepositSummary.vue";
import RepoCtRequestHistoryTradeSummary from "./components/repo-marketplace/collateraltaker/history/RequestHistorySummary.vue";

// cg

// create baskets request
import CreateBasket from "./components/repo-marketplace/collateralgiver/repo-baskets/CreateBasket.vue";
import ViewBaskets from "./components/repo-marketplace/collateralgiver/repo-baskets/ViewBaskets.vue";
import ViewCollaterals from "./components/repo-marketplace/collateralgiver/repo-baskets/ViewCollaterals.vue";
import ViewTripartyBasket from "./components/repo-marketplace/collateralgiver/repo-baskets/ViewTripartyBasket.vue";
import ViewBilateralColaterals from "./components/repo-marketplace/collateralgiver/repo-baskets/ViewBilateralColaterals.vue";
// new request
import ViewAllNewRequests from "./components/repo-marketplace/collateralgiver/new-requests/ViewAllNewRequests.vue";
import NewRepoRequestSummary from "./components/repo-marketplace/collateralgiver/new-requests/NewRequestSummary.vue";

// post-request
import CGPostRequest from "./components/repo-marketplace/collateralgiver/post-request/Index.vue";
import CGPostRequestAI from "./components/repo-marketplace/collateralgiver/post-request/AiIndex.vue";
// inprogress
import ViewAllInprogress from "./components/repo-marketplace/collateralgiver/in-progress/ViewAllInprogress.vue";
import InprogressRequestSummary from "./components/repo-marketplace/collateralgiver/in-progress/InprogressRequestSummary.vue";
// pending trades
import RepoCgPendingTrade from "./components/repo-marketplace/collateralgiver/pendingtrades/Index.vue";
import RepoCgPendingTradeSummary from "./components/repo-marketplace/collateralgiver/pendingtrades/OfferSummary.vue";
// active trades
import RepoCgActiveTrade from "./components/repo-marketplace/collateralgiver/activetrades/Index.vue";
import RepoCgActiveTradeSummary from "./components/repo-marketplace/collateralgiver/activetrades/OfferSummary.vue";
// active trades
import RepoCgHistoryTrade from "./components/repo-marketplace/collateralgiver/historytrades/Index.vue";
import RepoCgHistoryTradeSummary from "./components/repo-marketplace/collateralgiver/historytrades/OfferSummary.vue";

// launchpad
import launchpad from "./components/launchpad/Index.vue";



// component
// launchpad
Vue.component('launch-pad', launchpad);
Vue.component('switch-organization', SwitchOrganization);

// repos
Vue.component('repo-post-a-request', PostRepoDepositOffer);

Vue.component('repo-review-offers', RepoReviewRequest);
Vue.component('repo-request-summary', RepoSummary);

Vue.component('repo-ct-pending-trades', RepoCtPendingTrade);
Vue.component('repo-ct-pending-trade-summary', RepoCtPendingTradeSummary);
// my offes
Vue.component('repo-ct-my-offers', RepoCtMyOffers);
Vue.component('repo-ct-my-offers-summary', RepoCtMyOffersSummary);

Vue.component('repo-ct-active-trades', RepoCtActiveTrade);
Vue.component('repo-ct-active-trade-summary', RepoCtActiveTradeSummary);

Vue.component('repo-ct-history-trades', RepoCtHistoryTrade);
Vue.component('repo-ct-history-trade-summary', RepoCtHistoryTradeSummary);
Vue.component('repo-ct-request-history-trade-summary', RepoCtRequestHistoryTradeSummary);


// cg
Vue.component('view-all-new-requests', ViewAllNewRequests);

Vue.component('create-baskets', CreateBasket);
Vue.component('view-baskets', ViewBaskets);
Vue.component('view-collaterals', ViewCollaterals);
Vue.component('view-triparty-baskets', ViewTripartyBasket);
Vue.component('view-bilateral-baskets', ViewBilateralColaterals);

Vue.component('cg-post-request', CGPostRequest);
Vue.component('publish-rate-with-ai', CGPostRequestAI);

Vue.component('view-trade-request-summary', NewRepoRequestSummary);
Vue.component('view-all-in-progress', ViewAllInprogress);
Vue.component('view-in-progress-summary', InprogressRequestSummary);

Vue.component('repo-cg-pending-trades', RepoCgPendingTrade);
Vue.component('repo-cg-pending-trade-summary', RepoCgPendingTradeSummary);

Vue.component('repo-cg-active-trades', RepoCgActiveTrade);
Vue.component('repo-cg-active-trade-summary', RepoCgActiveTradeSummary);

Vue.component('repo-cg-history-trades', RepoCgHistoryTrade);
Vue.component('repo-cg-history-trade-summary', RepoCgHistoryTradeSummary);

Vue.component('chat-messages', ChatMessages);
Vue.component('chat-form', ChatForm);
Vue.component('notify-count', NotifyCount);
Vue.component('chats-count', ChatsCount);
Vue.component('login-form', Login);
Vue.component('depositor-sign-up', DepositorSignUp);
Vue.component('reset-password', ResetPassword);
Vue.component('change-password', ChangePassword);
Vue.component('verify-otp', VerifyOtp);
Vue.component('sign-up', SignUp);
Vue.component('sign-up-step1', SignUpStep1);
Vue.component('sign-up-step2', SignUpStep2);
Vue.component('profile-setting', Profile);
Vue.component('account-setting', AccountSetting);
Vue.component('depositor-account-setting', DepositorAccountSetting);
Vue.component('create-gic', CreateGic);
Vue.component('user-form', UserForm);
Vue.component('user-list', UserList);
Vue.component('admin-list', AdminList);
Vue.component('create-blog', CreateBlog);
Vue.component('list-blogs', BlogListing);
Vue.component('blog-detail', BlogDetails);
Vue.component('blog-card', BlogCard);
Vue.component('ye-table', YETable);
Vue.component('test-component', TestComponent);

Vue.component("about-us", AboutUs);
Vue.component("about-us-team-member", TeamMemberCard);
Vue.component("sign-up-step3", SignUpStep3);

Vue.component("m-button", MButton);
Vue.component("m-offer", MOffer);
Vue.component("m-offer-fi", MOfferFI);
Vue.component("m-add-offer-model", MAddOfferModel);
Vue.component("m-default-page", MDefaultPage);
Vue.component("m-update-feature-model", MUpdateFeatureModel);
Vue.component("marketplace-filter-page1", FilterPage1);
Vue.component("depositor-marketplace", DepositorMarketPlace);
Vue.component("marketplace-featured", Featured);

Vue.component("admin-marketplace", AdminMarketplace);
Vue.component("investor-calculator", Calculator);
Vue.component("rates-calculator", RatesCalculator);

Vue.component("request-account", RequestAccount);
Vue.component("product-list", ProductList);
Vue.component("repo-product-list", RepoProductList);
Vue.component("create-basket-types", BasketType);
Vue.component("create-day-count-conventions", DayCountIndex);
Vue.component("create-collatral-types", CollateralType);
Vue.component("respond-to-request", RespondToRequest);

//camapigns
Vue.component("bank-campaign", BankCampaign);
Vue.component("drafts-campaigns", DraftsCampaign);
Vue.component("products-campaigns", ProductsCampaign);
Vue.component("groups-campaigns", GroupsCampaign);
Vue.component("product-summary", ProductSummary);
Vue.component("campaign-summary", CampaignDetailAndSummary);
Vue.component("draft-campaign-summary", DraftCampaignDetailAndSummary);

Vue.component("campaign-edit", CampaignEditPage);

Vue.component("campaign-product-summary", CampaignProductSummary);
Vue.component("depositor-campaign-offer", DepositorCampaignOffer);
Vue.component("purchase-gic", PurchaseGic);
//camapigns

Vue.component("new-sign-up", SignUpIndex);

Vue.component("terms-and-conditions", TermsAndConditions);

Vue.component("review-new-offers", ReviewNewOffers);
Vue.component("request-fis", RequestFIS);
Vue.component("request-offers", RequestOffers);
Vue.component("request-summary", RequestSummary);

Vue.component("terms-and-conditions", TermsAndConditions);

// pending deposits
Vue.component("pending-deposits", PendingDeposits);
Vue.component("depositor-create-gic", DepositorCreateGIc);
Vue.component("pr-deposit-summary", DepositSummary);
Vue.component("pr-active-deposits", ActiveDeposits);
Vue.component("in-progress-deposits", InProgressDeposits);
Vue.component("offer-summary-card", ViewOfferCard);
Vue.component("bank-history", HistoryCard);

Vue.component("bank-active-deposits", BankActiveDeposits);
Vue.component("bank-pending-deposits", FIPendingDeposits);
Vue.component("bank-create-gic", BankCreateGIc);
Vue.component("fi-deposit-summary", FIDepositSummary);
Vue.component("depositor-history", DepositHistory);
Vue.component("review-offers", ReviewOffers);
Vue.component("view-summary-for-history", ViewSummary);
Vue.component("post-request-index", PostReqIndex);

Vue.component('new-requests', RequestListing)
Vue.component('new-request-summary', NewRequestSummary)

