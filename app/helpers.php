<?php

use App\Constants;
use App\CustomEncoder;
use App\Http\Controllers\Dashboard\DepositChatRoomController;
use App\Models\ActivityLog;
use App\Models\Deposit;
use App\Models\DepositRequest;
use App\Models\InstitutionList;
use App\Models\LoginActivity;
use App\Models\Campaign;
use App\Models\Offer;
use App\Models\Blog;
use App\Models\CGTradeRequest;
use App\Models\CGTradeRequestInvitedCTOffer;
use App\Models\CTRequestDepositTradeEvent;
use App\Models\CTTradeRequest;
use App\Models\CTTradeRequestCGOffer;
use App\Models\CTTradeRequestOfferDeposit;
use App\Models\NAICS;
use App\Models\Organization;
use App\Models\PotentialYearlyDeposits;
use App\Models\Preference;
use App\Models\Product;
use App\Models\SystemInterestRate;
use App\Models\SystemSetting;
use App\Models\TradeCollateralBasket;
use App\Models\TradeOrganizationCollateral;
use App\Models\TradeOrganizationCollateralCUSIP;
use App\Models\TradeTriBasketThirdParty;
use App\Models\UsersDemoGraphicData;
use App\Models\UsersIPAddress;
use App\Services\Depositor\Summary;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use \Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Session\Session;

if (!function_exists('loginActivities')) {
    function loginActivities($event_type, $user_agent, $user_id)
    {
        LoginActivity::create([
            'event_time' => getUTCDateNow(),
            'activity_type' => $event_type,
            'user_agent' => json_encode($user_agent),
            'user_id' => $user_id,
        ]);
    }
}

if (!function_exists('systemActivities')) {
    function systemActivities($user_id, $query_string, $location, $from_location = "")
    {
        $user = DB::table('users')->find($user_id);
        ActivityLog::create([
            'location' => $location,
            'query_string' => $query_string,
            'event_date' => getUTCDateNow(),
            'user_id' => $user_id,
            'created_at' => getUTCDateNow(),
            'from_location' => $from_location,
            'admin_loggedin_as' => $user ? $user->admin_loggedin_as : NULL
        ]);
    }
}

if (!function_exists('getRandomNumberBetween')) {
    function getRandomNumberBetween($min, $max)
    {
        return rand($min, $max);
    }
}
if (!function_exists('getDateFormatted')) {
    function getDateFormatted($datetime)
    {

        $datetime_to_local = changeDateFromUTCtoLocal($datetime . " 11:59:59", \App\Constants::DATE_TIME_NEW_12_24_HRS_FORMAT);
        $carbonDate = Carbon::parse($datetime_to_local);
        return $carbonDate->format('d F Y');
    }
}

if (!function_exists('getUTCDateNow')) {
    function getUTCDateNow($time = true)
    {
        $date = getUTCTimeNow();
        if ($time) {
            return $date->format(Constants::DATE_TIME_FORMAT);
        }

        return $date->format(Constants::DATE_FORMAT);
    }
}
if (!function_exists('getPathPart')) {
    function getPathPart($section = 0)
    {
        $path = request()->path();
        $segments = explode('/', $path);

        if (!empty($segments)) {
            $firstSegment = $segments[$section];
            return $firstSegment;
        } else {
            return $path;
        }
    }
}

if (!function_exists('getUTCEmailDateNow')) {
    function getUTCEmailDateNow($time = true)
    {
        $date = getUTCTimeNow();
        if ($time) {
            return $date->format(Constants::EMAIL_DATE_FORMAT);
        }

        return $date->format(Constants::EMAIL_DATE_FORMAT);
    }
}

if (!function_exists('getUTCTimeNow')) {
    function getUTCTimeNow()
    {
        return Carbon::now('UTC');
    }
}

if (!function_exists('archiveTable')) {
    function archiveTable($id, $table, $user_id, $action = "")
    {
        $utc_date_now = getUTCDateNow();
        $user_id = \auth()->check() ? \auth()->id() : $user_id;
        switch ($table) {
            case "deposits":
                $deposit = Deposit::find($id);
                if ($deposit) {
                    \DB::table('deposits_archive')->insertGetId(
                        [
                            'reference_no' => $deposit->reference_no,
                            'offer_id' => $deposit->offer_id,
                            'offered_amount' => $deposit->offered_amount,
                            'gic_start_date' => $deposit->gic_start_date,
                            'gic_number' => $deposit->gic_number,
                            'maturity_date' => $deposit->maturity_date,
                            'status' => $deposit->status,
                            'created_at' => $deposit->created_at,
                            'modified_at' => $deposit->modified_at,
                            'modified_by' => $deposit->modified_by,
                            'is_test' => $deposit->is_test,
                        ]
                    );

                    $deposit->modified_at = $utc_date_now;
                    $deposit->modified_by = Auth::id();
                    $deposit->modified_section = $action;
                    $deposit->save();
                }
                break;
            case "depositor_requests":
                $depositor_request = DepositRequest::find($id);
                if ($depositor_request) {
                    \DB::table('depositor_requests_archive')->insertGetId(
                        [
                            'reference_no' => $depositor_request->reference_no,
                            'term_length_type' => $depositor_request->term_length_type,
                            'term_length' => $depositor_request->term_length,
                            'lockout_period_days' => $depositor_request->lockout_period_days,
                            'lockout_period_months' => $depositor_request->lockout_period_months,
                            'closing_date_time' => $depositor_request->closing_date_time,
                            'amount' => $depositor_request->amount,
                            'currency' => $depositor_request->currency,
                            'date_of_deposit' => $depositor_request->date_of_deposit,
                            'compound_frequency' => $depositor_request->compound_frequency,
                            'requested_rate' => $depositor_request->requested_rate,
                            'requested_short_term_credit_rating' => $depositor_request->requested_short_term_credit_rating,
                            'requested_deposit_insurance' => $depositor_request->requested_deposit_insurance,
                            'special_instructions' => $depositor_request->special_instructions,
                            'request_status' => $depositor_request->request_status,
                            'created_date' => $depositor_request->created_date,
                            'closed_date' => $depositor_request->closed_date,
                            'user_id' => $depositor_request->user_id,
                            'product_id' => $depositor_request->product_id,
                            'modified_date' => $depositor_request->modified_date,
                            'modified_section' => $depositor_request->modified_section,
                            'modified_by' => $depositor_request->modified_by,
                            'is_test' => $depositor_request->is_test,
                        ]
                    );

                    $depositor_request->modified_date = $utc_date_now;
                    $depositor_request->modified_by = $user_id;
                    $depositor_request->modified_section = $action;
                    $depositor_request->save();
                }
                break;
            case "offers":
                $offer = Offer::find($id);
                if ($offer) {
                    $archive_id = \DB::table('offers_archives')->insertGetId(
                        [
                            'invitation_id' => $offer->invitation_id,
                            'on_behalf_of' => $offer->on_behalf_of,
                            'reference_no' => $offer->reference_no,
                            'maximum_amount' => $offer->maximum_amount,
                            'minimum_amount' => $offer->minimum_amount,
                            'interest_rate_offer' => $offer->interest_rate_offer,
                            'rate_held_until' => $offer->rate_held_until,
                            'product_disclosure_statement' => $offer->product_disclosure_statement,
                            'product_disclosure_url' => $offer->product_disclosure_url,
                            'special_instructions' => $offer->special_instructions,
                            'offer_status' => $offer->offer_status,
                            'created_date' => $offer->created_date,
                            'modified_date' => $offer->modified_date,
                            'modified_section' => $offer->modified_section,
                            'modified_by' => $offer->modified_by,
                            'is_test' => $offer->is_test,
                            'user_id' => $offer->user_id,
                            'offer_withdrawal_reason' => $offer->offer_withdrawal_reason,
                            'counter_offer_archive_id' => $offer->counter_offer_archive_id,
                            'fixed_rate' => $offer->fixed_rate,
                            'rate_operator' => $offer->rate_operator,
                            'prime_rate' => $offer->prime_rate,
                            'rate_type' => $offer->rate_type,
                        ]
                    );

                    $offer->modified_date = $utc_date_now;
                    $offer->modified_by = $user_id;
                    $offer->modified_section = $action;
                    $offer->save();
                    return $archive_id;
                }
                break;
            case "blogs":
                $blog = Blog::find($id);
                if ($blog) {
                    \DB::table('blogs_archive')->insertGetId(
                        [
                            'blog_id' => $blog->id,
                            'title' => $blog->title,
                            'status' => $blog->status,
                            'body' => $blog->body,
                            'main_image' => $blog->main_image,
                            'created_by' => $blog->created_by,
                            'updated_by' => $blog->updated_by,
                            'modified_section' => $blog->modified_section,
                            'modified_date' => $blog->modified_date,
                            'modified_by' => $blog->modified_by,
                            'seo_title' => $blog->seo_title,
                            'description' => $blog->description,
                            'seo_description' => $blog->seo_description,
                            'tags' => $blog->tags,
                            'category_id' => $blog->category_id,
                        ]
                    );

                    $blog->modified_date = $utc_date_now;
                    $blog->modified_by = $user_id;
                    $blog->modified_section = $action;
                    $blog->save();
                }
                break;
            case "users":
                $user = User::find($id);
                if ($user) {
                    \DB::table('users_archive')->insertGetId(
                        [
                            'user_id' => $user->id,
                            'name' => $user->name,
                            'profile_pic' => $user->profile_pic,
                            'email' => $user->email,
                            'account_opening_date' => $user->account_opening_date,
                            'account_status' => $user->account_status,
                            'modified_date' => $user->modified_date,
                            'modified_section' => $user->modified_section,
                            'modified_by' => $user->modified_by,
                            'failed_login_attempts' => $user->failed_login_attempts,
                            'account_closure_date' => $user->account_closure_date,
                            'account_closure_reason' => $user->account_closure_reason,
                            'last_login' => $user->last_login,
                            'is_non_partnered_fi' => $user->is_non_partnered_fi,
                            'created_by' => $user->created_by,
                            'is_temporary' => $user->is_temporary,
                            'password_changed' => $user->password_changed,
                            'account_manager' => $user->account_manager,
                            'inviter_name' => $user->inviter_name,
                            'is_test' => $user->is_test,
                        ]
                    );

                    $user->modified_date = $utc_date_now;
                    $user->modified_by = $user_id;
                    $user->modified_section = $action;
                    $user->save();
                }
                break;
            case "organizations":
                $organization = Organization::find($id);
                if ($organization) {
                    \DB::table('organizations_archive')->insertGetId(
                        [
                            'organization_id' => $organization->id,
                            'name' => $organization->name,
                            'logo' => $organization->logo,
                            'type' => $organization->type,
                            'admin_user_id' => $organization->admin_user_id,
                            'is_non_partnered_fi' => $organization->is_non_partnered_fi,
                            'created_by' => $organization->created_by,
                            'is_temporary' => $organization->is_temporary,
                            'account_manager' => $organization->account_manager,
                            'inviter_name' => $organization->inviter_name,
                            'status' => $organization->status,
                            'modified_section' => $organization->modified_section,
                            'created_at' => $organization->created_at,
                            'modified_by' => $organization->modified_by,
                            'updated_at' => $organization->updated_at,
                            'is_test' => $organization->is_test,
                        ]
                    );

                    $organization->updated_at = $utc_date_now;
                    $organization->modified_by = $user_id;
                    $organization->modified_section = $action;
                    $organization->save();
                }
                break;
            case 'system_settings':
                $system_settings = SystemSetting::find($id);
                if ($system_settings) {
                    \DB::table('system_settings_archive')->insertGetId(
                        [
                            'value' => $system_settings->value,
                            'created_date' => $system_settings->created_date,
                            'modified_date' => $system_settings->modified_date,
                            'modified_by' => $system_settings->modified_by,
                        ]
                    );

                    $system_settings->modified_date = $utc_date_now;
                    $system_settings->modified_by = $user_id;
                    $system_settings->save();
                }
                break;
            case "market_place_offers":
                $market_place_offer = Campaign::find($id);
                if ($market_place_offer) {
                    \DB::table('market_place_offers_archives')->insertGetId(
                        [
                            'market_place_offer_id' => $market_place_offer->id,
                            'is_featured' => $market_place_offer->is_featured,
                            'reference_no' => $market_place_offer->reference_no,
                            'term_length_type' => $market_place_offer->term_length_type,
                            'term_length' => $market_place_offer->term_length,
                            'product_id' => $market_place_offer->product_id,
                            'lockout_period' => $market_place_offer->lockout_period,
                            'minimum_amount' => $market_place_offer->minimum_amount,
                            'maximum_amount' => $market_place_offer->maximum_amount,
                            'compound_frequency' => $market_place_offer->compound_frequency,
                            'interest_paid' => $market_place_offer->interest_paid,
                            'organization_id' => $market_place_offer->organization_id,
                            'created_by' => $market_place_offer->created_by,
                            'modified_by' => $market_place_offer->modified_by,
                            'modified_section' => $market_place_offer->modified_section,
                            'is_test' => $market_place_offer->is_test,
                            'status' => $market_place_offer->status,
                            'created_at' => $market_place_offer->created_at,
                            'updated_at' => $market_place_offer->updated_at,
                            'currency' => $market_place_offer->currency
                        ]
                    );

                    $market_place_offer->modified_by = $user_id;
                    $market_place_offer->modified_section = $action;
                    $market_place_offer->save();
                }
                break;

                case 'c_t_trade_request_offer_deposits':
                    $deposit = CTTradeRequestOfferDeposit::find($id);
                   return DB::table('c_t_trade_request_offer_deposits_archive')->insertGetId(
                        [
                            'c_r_trade_request_offer_deposits_id'=>$id,
                            'deposit_reference_no' =>$deposit->deposit_reference_no,
                            'offered_amount' =>$deposit->offered_amount,
                            'trade_date' =>$deposit->trade_date,
                            'gic_number' =>$deposit->gic_number,
                            'maturity_date' =>$deposit->maturity_date,
                            'deposit_status' =>$deposit->deposit_status,
                            'created_at' =>$deposit->created_at,
                            'modified_date' =>$deposit->modified_date,
                            'modified_section' =>$deposit->modified_section,
                            'admins_notified' =>$deposit->admins_notified,
                            'admins_notified_date' =>$deposit->admins_notified_date,
                            'created_by' =>$deposit->created_by,
                            'deposit_inactivate_reason' =>$deposit->deposit_inactivate_reason,
                            'redemption_date' =>$deposit->redemption_date,
                            // 'active_trate_event' =>$deposit->active_trate_event,
                            // 'active_trade_events_batch_number' =>$deposit->active_trade_events_batch_number,   
                        ]
                    );
                    break;
        }
    }
}


if (!function_exists('setCookie')) {
    function setCookie($key, $value, $lifeTime = null)
    {
        if ($lifeTime) {
            setcookie($key, $value, time() + $lifeTime);
        } else {
            setcookie($key, $value);
        }
    }
}

if (!function_exists('getCookie')) {
    function getCookie(Request $request, $key)
    {
        return $request->cookie($key);
    }
}

if (!function_exists('getAdminEmails')) {
    function getAdminEmails($excluded_admin = null)
    {
        //        $return = User::with(['roleType'])->whereHas('roleType',function ($query) {
        //            $query->where('description', 'Admin');
        //        })->whereNotIn('account_status', ['CLOSED']);

        $return  = User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->where('roles.name', '=', 'system-administrator')
            ->select([
                'users.*'
            ])->whereNotIn('users.account_status', ['CLOSED']);

        if ($excluded_admin) {
            $return = $return->where('users.id', '!=', $excluded_admin->id);
        }

        return $return->groupBy('users.id')->pluck('email')->toArray();
    }
}

if (!function_exists('getAdminEmailsAndTimezone')) {
    function getAdminEmailsAndTimezone($excluded_admin = null)
    {
        // ->join('', 'demographic_user_data.user_id', '=', 'users.id')
        $return  = User::join('role_user', 'role_user.user_id', '=', 'users.id')
            // ->join('roles','role_user.role_id','=','roles.id')
            ->crossJoin('demographic_user_data')
            // ->where('roles.name','=','system-administrator')
            ->select([
                'users.*'
            ])->whereNotIn('users.account_status', ['CLOSED']);

        if ($excluded_admin) {
            $return = $return->where('users.id', '!=', $excluded_admin->id);
        }

        return $return->groupBy('users.id')->get();
    }
}

if (!function_exists('verifyCaptcha')) {
    function verifyCaptcha($response)
    {
        if (App::environment('local') || can_skip_robot_check_and_otp()) {
            return true;
        }

        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) { // cloudflare users
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }

        $post_data = http_build_query(
            array(
                'secret' => config('app.SECRET_KEY'),
                'response' => $response,
                //                'remoteip' => $_SERVER['REMOTE_ADDR']
            )
        );
        $opts = array(
            'http' =>
            array(
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => $post_data
            )
        );
        $context = stream_context_create($opts);
        $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
        $result = json_decode($response);

        if ($result->success) {
            return true;
        }
        return false;
    }
}

if (!function_exists('systemActiveUsersStatuses')) {
    function systemActiveUsersStatuses()
    {
        return ['PENDING', 'ACTIVE', 'LOCKED', 'SUSPENDED', 'REVIEWING', 'INVITED'];
    }
}

if (!function_exists('isNotSupposedToReceiveMail')) {
    function isNotSupposedToReceiveMail($email, $filerArray)
    {

        true;
        //        try {
        //            $foundrecords = User::where("email", $email)->whereNotIn("status", $filerArray)->count();
        //            if ($foundrecords > 0) {
        //                return false;
        //            }
        //            return true;
        //        }catch(Exception $exception){
        //            return
        //        }
    }
}

if (!function_exists('systemActiveOrganizationStatuses')) {
    function systemActiveOrganizationStatuses($include_pending = false)
    {
        $return =  ['ACTIVE', 'INVITED', 'REVIEWING'];
        if ($include_pending) {
            array_push($return, 'PENDING');
        }
        return $return;
    }
}

if (!function_exists('getFIs')) {
    function getFIs($non_existing_check = true)
    {
        if ($non_existing_check) {
            $existing_fis_names = User::whereIn('account_status', systemActiveUsersStatuses())->get()->pluck('name')->toArray();
            $fis = InstitutionList::where('status', 'ACTIVE')->whereNotIn('name', $existing_fis_names)->get();
        } else {
            $fis = InstitutionList::where('status', 'ACTIVE')->get();
        }
        return $fis;
    }
}

if (!function_exists('provinces')) {
    function provinces()
    {
        return [
            'Alberta',
            'British Columbia',
            'Manitoba',
            'New Brunswick',
            'Newfoundland and Labrador',
            'Nova Scotia',
            'Ontario',
            'Prince Edward Island',
            'Saskatchewan',
            'Nunavut',
            'Quebec',
            'NorthWest Territories',
            'Yukon',
            "Other International"
        ];
    }
}

if (!function_exists('timezonesList')) {
    function timezonesList()
    {
        return [
            'Newfoundland' => 'Newfoundland - St_Johns, GMT ' . Carbon::now('America/St_Johns')->format('P'),
            'Atlantic' => 'Atlantic - Halifax, GMT ' . Carbon::now('America/Halifax')->format('P'),
            'Atlantic no DST' => 'Atlantic no DST - America/Blanc-Sablon, GMT ' . Carbon::now('America/Blanc-Sablon')->format('P'),
            'Eastern' => 'Eastern - America/Toronto, GMT ' . Carbon::now('America/Toronto')->format('P'),
            'Eastern no DST' => 'Eastern no DST - America/Atikokan, GMT ' . Carbon::now('America/Atikokan')->format('P'),
            'Central' => 'Central - America/Winnipeg, GMT ' . Carbon::now('America/Winnipeg')->format('P'),
            'Central no DST' => 'Central no DST - America/Regina, GMT ' . Carbon::now('America/Regina')->format('P'),
            'Mountain' => 'Mountain - America/Edmonton, GMT ' . Carbon::now('America/Edmonton')->format('P'),
            'Mountain no DST' => 'Mountain no DST - America/Creston, GMT ' . Carbon::now('America/Creston')->format('P'),
            'Pacific' => 'Pacific - America/Vancouver, GMT ' . Carbon::now('America/Vancouver')->format('P')
        ];
    }
}

if (!function_exists('convertTime')) {
    function convertTime($time, $initialZone, $finalZone)
    {
        return Carbon::create($time, getZone($initialZone))->setTimezone(getZone($finalZone));
    }
}

if (!function_exists('getZone')) {
    function getZone($zone)
    {
        switch ($zone) {
            case 'Newfoundland':
                $timezone = 'America/St_Johns';
                break;
            case 'Atlantic':
                $timezone = 'America/Halifax';
                break;
            case 'Atlantic no DST':
                $timezone = 'America/Blanc-Sablon';
                break;
            case 'Eastern':
                $timezone = 'America/Toronto';
                break;
            case 'Eastern no DST':
                $timezone = 'America/Atikokan';
                break;
            case 'Central':
                $timezone = 'America/Winnipeg';
                break;
            case 'Central no DST':
                $timezone = 'America/Regina';
                break;
            case 'Mountain':
                $timezone = 'America/Edmonton';
                break;
            case 'Mountain no DST':
                $timezone = 'America/Creston';
                break;
            case 'Pacific':
                $timezone = 'America/Vancouver';
                break;
            default:
                $timezone = 'America/Vancouver';
                break;
        };
        return $timezone;
    }
}

if (!function_exists('nice_number')) {
    function nice_number($n)
    {
        // first strip any formatting;
        $n = @(0 + str_replace(",", "", $n));

        // is this a number?
        if (!is_numeric($n)) {
            return false;
        }

        // now filter it;
        if ($n >= 1000000000000) {
            return round(($n / 1000000000000), 2) . ' T';
        } elseif ($n >= 1000000000) {
            return round(($n / 1000000000), 2) . ' B';
        } elseif ($n >= 1000000) {
            return round(($n / 1000000), 2) . ' M';
        } elseif ($n >= 1000) {
            return round(($n / 1000), 2) . ' K';
        }

        return number_format($n);
    }
}

if (!function_exists('formatInterest')) {
    function formatInterest($rate, $is_variable = false, $rate_operator = null, $show_tool_tip = false)
    {
        if ($is_variable) {
            $system_setting = getSystemSettings('prime_rate');
            $prime_rate = $system_setting->value;
            if (!empty($prime_rate)) {
                $tool_tip_title = "Prime Rate: " . $prime_rate . "% " . $rate_operator . " Spread Rate: " . $rate . '%';
                $tool_tip = "<a href='javascript:void()' data-toggle='tooltip' title='" . $tool_tip_title . "'><i class='fa fa-info-circle'></i></a>";
                if ($rate_operator == "+") {
                    $return = getInterest($prime_rate + $rate, true);
                } else {
                    $return = getInterest($prime_rate - $rate, true);
                }
                if ($show_tool_tip) {
                    return $return . ' ' . $tool_tip;
                }
                return $return;
            }
        }
        return getInterest($rate);
    }
}

if (!function_exists('getInterest')) {
    function getInterest($get_val, $is_variable = false)
    {
        if ($get_val != null && $get_val > 0) {
            if ($get_val / 2 < 0.5) {
                $get_val = floatval($get_val);
                $a = round($get_val, 2);
                if (strpos($a, ".") !== false) {
                    return number_format($a, 2) . "%";
                } else {
                    return $a . ".00%";
                }
            } else {
                $a = round($get_val, 2);
                if (strpos($a, ".") !== false) {
                    return number_format($a, 2) . "%";
                } else {
                    return $a . ".00%";
                }
            }
        } else {
            if ($is_variable) {
                return $get_val . '%';
            } else {
                return '-';
            }
        }
    }
}

if (!function_exists('remove00AndPercentInterestRate')) {
    function remove00AndPercentInterestRate($rate)
    {
        return (float) str_replace("%", "", $rate);
    }
}

if (!function_exists('getSystemSettings')) {
    function getSystemSettings($keys = null)
    {
        if ($keys == null) {
            return SystemSetting::all();
        }

        if (is_array($keys)) {
            return SystemSetting::whereIn('key', $keys)->get()->keyBy('key');
        }
        return SystemSetting::where('key', $keys)->first();
    }
}

if (!function_exists('getNewSystemSettings')) {
    function getNewSystemSettings($keys = null)
    {
        if ($keys == null) {
            return SystemInterestRate::all();
        }

        if (is_array($keys)) {
            return SystemInterestRate::whereIn('rate_label', $keys)->get()->keyBy('rate_label');
        }

        return SystemInterestRate::where('rate_label', $keys)->first();
    }
}

if (!function_exists('changeDateFromUTCtoLocal')) {
    function changeDateFromUTCtoLocal($date, $format = null, $parseFormat = null, $timezone = null, $user = null)
    {
        Log::alert('here');
        Log::alert(json_encode($user));
        try {
            $user = $user ? $user : Auth::user();
            $time_zone = $timezone == null ? $user->timezone : $timezone;
            return Carbon::createFromFormat($parseFormat == null ? Constants::DATE_TIME_FORMAT : $parseFormat, $date, 'UTC')
                ->setTimezone(formattedTimezone($time_zone))->format($format == null ? Constants::DATE_TIME_FORMAT : $format);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
        Log::alert('687');
        return $date;
    }
}

if (!function_exists('changeEmailDateFromUTCtoLocal')) {
    function changeEmailDateFromUTCtoLocal($date, $user = null)
    {
        Log::alert(json_encode($user));
        try {
            $user = $user ? $user : Auth::user();
            $time_zone = $user->timezone;
            $date =  Carbon::createFromFormat('M d Y', $date)
                ->setTimezone(formattedTimezone($time_zone))->format('M d Y');
            return Carbon::parse($date)->format('M d Y');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }

        Log::alert($date);

        return $date;
    }
}


if (!function_exists('changeDateFromLocalToUTC')) {
    function changeDateFromLocalToUTC($date, $format = null, $parseFormat = null, $timezone = null)
    {
        try {
            $user = Auth::user();
            $time_zone = $timezone == null ? $user->timezone : $timezone;
            return Carbon::createFromFormat($parseFormat == null ? Constants::DATE_TIME_FORMAT : $parseFormat, $date, formattedTimezone($time_zone))
                ->setTimezone("UTC")->format($format == null ? Constants::DATE_TIME_FORMAT : $format);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage() . 'changeDateFromLocalToUTC');
        }

        return $date;
    }
}

if (!function_exists('formattedTimezone')) {
    function formattedTimezone($timezone)
    {
        $timezone = strtolower(trim($timezone));
        if (strcmp($timezone, "newfoundland") == 0) {
            $zone = "America/St_Johns";
        } else if (strcmp($timezone, "atlantic") == 0) {
            $zone = "America/Halifax";
        } else if (strcmp($timezone, "atlantic no dst") == 0) {
            $zone = "America/Blanc-Sablon";
        } else if (strcmp($timezone, "eastern") == 0) {
            $zone = "America/Toronto";
        } else if (strcmp($timezone, "eastern no dst") == 0) {
            $zone = "America/Atikokan";
        } else if (strcmp($timezone, "central") == 0) {
            $zone = "America/Winnipeg";
        } else if (strcmp($timezone, "central no dst") == 0) {
            $zone = "America/Regina";
        } else if (strcmp($timezone, "mountain") == 0) {
            $zone = "America/Edmonton";
        } else if (strcmp($timezone, "mountain no dst") == 0) {
            $zone = "America/Creston";
        } else if (strcmp($timezone, "pacific") == 0) {
            $zone = "America/Vancouver";
        } else {
            $zone = "America/Winnipeg";
        }

        return $zone;
    }
}

if (!function_exists('timezoneOffsetFromUTC')) {
    function timezoneOffsetFromUTC($timezone)
    {
        return Carbon::now(formattedTimezone($timezone))->format('P');
    }
}

if (!function_exists('getUserPreference')) {
    function getUserPreference($name, $user_id = null)
    {
        if (auth::check()) {
            if ($user_id != null) {
                $user = User::find($user_id);
            } else {
                $user = \auth()->user();
            }
            if ($user) {
                $preference = Preference::where('name', $name)->first();
                if ($preference) {
                    $setting = $user->preference->where('preference_id', $preference->id)->first();
                    if ($setting) {
                        return $setting->value;
                    }
                }
            }
        }

        return "";
    }
}

if (!function_exists('generateOfferContractID')) {
    function generateOfferContractID($dep_ref, $start = 1)
    {

        $new_dep_ref = $dep_ref . $start;
        $contracts = Deposit::where('reference_no', $new_dep_ref)->first();

        if ($contracts) {
            return generateOfferContractID($dep_ref, $start + 1);
        }

        return $new_dep_ref;
    }
}

if (!function_exists('setEmailHeader')) {
    function setEmailHeader($header)
    {
        $environment = getEnvironmentNameEmailTag();
        return App::environment('production') || empty($environment)
            ? $header
            : $environment . ' - ' . $header;
    }
}

if (!function_exists('getEnvironmentNameEmailTag')) {
    function getEnvironmentNameEmailTag()
    {
        $base_url = url('/');
        //        Log::alert($base_url);
        if (strpos($base_url, 'uat') !== false) {
            return 'UAT';
        } else if (strpos($base_url, 'dev') !== false) {
            return 'DEV';
        } else if (!App::environment('production')) {
            return 'LOCALHOST';
        }

        return '';
    }
}

if (!function_exists('generateDepositRequestReference')) {
    function generateDepositRequestReference()
    {
        $count_ = DepositRequest::all()->count();
        do {
            $incr_ = 100 + $count_;
            $deposit_reference = date('ymd') . $incr_;
            $contracts = DepositRequest::where('reference_no', $deposit_reference)->first();
            $count_++;
        } while ($contracts);

        return $deposit_reference;
    }
}

if (!function_exists('generateDepositRequestBulkReference')) {
    function generateDepositRequestBulkReference()
    {
        $count_ = DepositRequest::whereNotNull('bulk_reference_no')->groupBy('bulk_reference_no')->count();
        do {
            $incr_ = 1 + $count_;
            $deposit_reference = date('ymd') . $incr_;
            $contracts = DepositRequest::where('bulk_reference_no', $deposit_reference)->first();
            $count_++;
        } while ($contracts);

        return $deposit_reference;
    }
}


if (!function_exists('generateOfferReference')) {
    function generateOfferReference()
    {
        $count_ = Offer::all()->count();
        do {
            $incr_ = 100 + $count_;
            $reference = date('ymd') . $incr_;
            $contracts = Offer::where('reference_no', $reference)->first();
            $count_++;
        } while ($contracts);

        return $reference;
    }
}
if (!function_exists('generateNonInvitedFITerms')) {
    function generateNonInvitedFITerms()
    {
        $file_name = str_replace(" ", "_", \auth()->user()->name) . "_Terms&Conditions_" . date(Constants::DATE_TIME_FORMAT_FOR_URL_NAMES) . ".pdf";
        $pdf = PDF::loadView('dashboard.bank.non-fi-sections.terms');
        $pdf->save('uploads/no_fi_terms/' . $file_name);
        return url('uploads/no_fi_terms/' . $file_name);
    }
}

if (!function_exists('notify')) {
    function notify($data)
    {
        return (new DepositChatRoomController())->sendNotifications($data);
    }
}

if (!function_exists('is_admin_route')) {
    function is_admin_route($request)
    {
        return $request->segment(1) == "yie-admin";
    }
}

if (!function_exists('removeAmPm')) {
    function removeAmPm($date)
    {
        return rtrim(str_replace("AM", "", str_replace("PM", "", $date)));
    }
}

if (!function_exists('unfetchableRoles')) {
    function unfetchableRoles()
    {
        return ['system-administrator'];
    }
}

if (!function_exists('readOnlyRoles')) {
    function readOnlyRoles()
    {
        return ['system-administrator', 'organization-administrator'];
    }
}

if (!function_exists('logEmailSent')) {
    function logEmailSent($mail_array)
    {
        try {
            return \DB::table('sent_emails')->insert([
                'to' => json_encode($mail_array['to']),
                'message' => base64_encode($mail_array['message']),
                'subject' => $mail_array['subject'],
                'status' => 'SENT',
                'created_at' => getUTCDateNow(),
                'updated_at' => getUTCDateNow()
            ]);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }
}

if (!function_exists('strpos_arr')) {
    function strpos_arr($haystack, $needle)
    {
        if (!is_array($haystack)) $haystack = array($haystack);
        foreach ($haystack as $what) {
            if (($pos = strpos($what, $needle)) !== false) return $pos;
        }
        return false;
    }
}

if (!function_exists('readCSV')) {
    function readCSV($csvFile, $array)
    {
        $line_of_text = [];
        $file_handle = fopen($csvFile, 'r');
        while (!feof($file_handle)) {
            $line_of_text[] = fgetcsv($file_handle, 0, $array['delimiter']);
        }
        fclose($file_handle);
        return $line_of_text;
    }
}

if (!function_exists('timeIn_12_24_format')) {
    function timeIn_12_24_format($datetime)
    {
        $datetime_to_local = changeDateFromUTCtoLocal($datetime, \App\Constants::DATE_TIME_NEW_12_24_HRS_FORMAT);
        $hour = date('H', strtotime($datetime_to_local));
        if ($hour > 12) {
            return changeDateFromUTCtoLocal($datetime, 'Y-m-d H:i');
        }

        return $datetime_to_local;
    }
}


if (!function_exists('can_switch_to_organizations')) {
    function can_switch_to_organizations($user, $organization = null)
    {
        if (!$user) {
            return false;
        }

        if ($organization == null) {
            $organization = $user->organization;
        }

        return $organization && $organization->allow_multi_organizations == 1;
    }
}

if (!function_exists('get_user_type')) {
    function get_user_type($user)
    {
        if (!$user) {
            return '';
        }

        $organization = $user->organization;
        return $organization ? $organization->type : ($user->is_super_admin ? 'Admin' : '');
    }
}

if (!function_exists('sanitize_file_name')) {
    function sanitize_file_name($name)
    {
        $string = str_replace("'", "", $name);
        $string = str_replace("\"", "", $string);
        $string = str_replace(" ", "_", $string);
        $string = str_replace(",", "", $string);
        return $string;
    }
}

if (!function_exists('counter_offer_status_formatter')) {
    function counter_offer_status_formatter($status, $organization)
    {
        if ($organization->type == 'BANK') {
            $status = str_replace("COUNTERED", "SENT", $status);
            //            $status = str_replace("ACCEPTED", "SENT", $status);
            $status = str_replace("PENDING", "RECEIVED", $status);
            $status = str_replace("CLOSED_ON_COUNTERED", "RECEIVED", $status);
            return strtolower($status);
        }

        $status = str_replace("COUNTERED", "RECEIVED", $status);
        $status = str_replace("PENDING", "SENT", $status);
        $status = str_replace("CLOSED_ON_COUNTERED", "SENT", $status);
        return strtolower($status);
    }
}

if (!function_exists('generateMarketPlaceOfferReference')) {
    function generateMarketPlaceOfferReference()
    {
        $count_ = Campaign::count();
        do {
            $incr_ = 100 + $count_;
            $reference = date('ymd') . $incr_;
            $offers = Campaign::where('reference_no', $reference)->first();
            $count_++;
        } while ($offers);

        return $reference;
    }
}

if (!function_exists('calculate_interest_earned')) {
    function calculate_interest_earned($amount, $market_place_offer)
    {
        if (!in_array($market_place_offer->product_name, ['Cashable', 'Notice deposit', 'Non-Redeemable', 'Short Term'])) {
            return '';
        }

        switch ($market_place_offer->term_length_type) {
            case 'DAYS':
                return round(($amount * $market_place_offer->interest_rate / 100 * $market_place_offer->term_length) / 365, 2);
            case 'MONTHS':
                return round(($amount * $market_place_offer->interest_rate / 100 * $market_place_offer->term_length) / 12, 2);
        }
        return '';
    }
}

if (!function_exists('can_skip_robot_check_and_otp')) {
    function can_skip_robot_check_and_otp()
    {
        if (request()->has('automated_test') && \request()->automated_test == 1 && config('app.env') != 'production') {
            return true;
        }

        return false;
    }
}

if (!function_exists('get_product_id_from_description')) {
    function get_product_id_from_description($description)
    {
        $product = Product::where('description', $description)->first();
        if ($product) {
            return $product->id;
        }
        return null;
    }
}

if (!function_exists('formatPhone')) {
    function formatPhone($data)
    {
        if (strpos($data, ')') !== false) {
            return $data;
        }

        if (preg_match('/^\+\d(\d{3})(\d{3})(\d{4})$/', $data,  $matches)) {
            return $matches[1] . '-' . $matches[2] . '-' . $matches[3];
        }

        return $data;
    }
}

if (!function_exists('arrayHasData')) {
    function arrayHasData($array)
    {
        return !empty($array) && is_array($array) && count($array) > 0;
    }
}

if (!function_exists('checkStartsWith')) {

    function checkStartsWith($string, $substring)
    {
        if (is_string($substring)) {
            return strpos($string, $substring) === 0;
        }

        return false;
    }
}

if (!function_exists('sanitize_amount')) {
    function sanitize_amount($val)
    {
        $string = str_replace(" ", "", $val);
        $string = str_replace(",", "", $val);
        return $string;
    }
}


if (!function_exists('getUTCTimeNowAddSubtractDays')) {
    function getUTCTimeNowAddSubtractDays($daysToAddOrSubtract)
    {
        return Carbon::now('UTC')->addDays($daysToAddOrSubtract);
    }
}


if (!function_exists("hasLockoutPeriod")) {
    function hasLockoutPeriod($prod)
    {
        $description = strtolower($prod);
        return stripos($description, 'notice deposit') !== false || stripos($description, 'cashable') !== false;
    }
}
if (!function_exists('calculateTimeDifference')) {
    function calculateTimeDifference($givenDate)
    {
        // Current date and time
        $currentDateTime = Carbon::now();
        $givenDateTime = Carbon::parse($givenDate);

        // Calculate the difference
        $timeDifference = $currentDateTime->diff($givenDateTime);

        // Get the difference in hours and minutes
        $hours = $timeDifference->h + ($timeDifference->days * 24);
        $minutes = $timeDifference->i;

        return "$hours Hrs $minutes Mins.";
    }
}
if (!function_exists('ai_summary')) {
    function ai_summary($organization)
    {
        return (new Summary($organization))->generate();
    }
}

if (!function_exists('naics_description')) {
    function naics_description($id)
    {
        $industry = NAICS::find($id);
        return ($industry) ? $industry->description : null;
    }
}

if (!function_exists('deposit_band')) {
    function deposit_band($id)
    {
        $deposit = PotentialYearlyDeposits::find($id);
        return ($deposit) ? $deposit->band : null;
    }
}

if (!function_exists('adminLoginAsClientBack')) {
    function adminLoginAsClientBack($returnBool = false)
    {
        try {

            $user = \auth()->user();
            if ($user && $user->admin_loggedin_as) {
                $ipReq = getIp();
                $admin_loggedin_as_agent = $user->admin_loggedin_as_agent ? json_decode($user->admin_loggedin_as_agent) : null;
                if (!empty($admin_loggedin_as_agent->{'x-user-ip'}) && $admin_loggedin_as_agent->{'x-user-ip'} && is_countable($admin_loggedin_as_agent->{'x-user-ip'}) && in_array($ipReq, $admin_loggedin_as_agent->{'x-user-ip'})) {
                    if ($returnBool) {
                        return true;
                    }
                    $code=CustomEncoder::urlValueEncrypt($user->admin_loggedin_as."_".getRandomNumberBetween(9999, 99999));
                    return '<a href="/back-to-yie?code=' . $code . '" class="btn btn-info ml-md-3 mr-md-auto">Go back to admin</a>';

                }
            }

            $user->admin_loggedin_as = NULL;
            $user->save();
        } catch (\Exception $exception) {
        }

        if ($returnBool) {
            return false;
        }
        return '';
    }
}
if (!function_exists('getIp')) {
    function getIp($user="")
    {
        if($user){
           $foundrecord= UsersIPAddress::where("user_id",CustomEncoder::urlValueDecrypt($user))->where("status","ACTIVE")->first();
           if($foundrecord){
            return $foundrecord->logged_ip;
           }
           return session('my_ip');
        }else{
            return session('my_ip');
        }

        // Retrieve the IP address
        // $response = \Illuminate\Support\Facades\Http::get('https://api.ipify.org', [
        //     'format' => 'json',
        //     'token' => '21f42bdc4db951'
        // ]);

        // // Check if the request was successful
        // if ($response->successful()) {
        //     // Get the JSON response body as an array
        //     $data = $response->json();

        //     // Access the IP address from the response
        //     // Add the IP address to the request headers
        // }

        
        // return null;
    }
}
if (!function_exists('getCurrentIp')) {
    function getCurrentIp()
    {
        $response = \Illuminate\Support\Facades\Http::get('https://api.ipify.org', [
            'format' => 'json',
            'token' => '21f42bdc4db951'
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $ipAddress=$data['ip'];
            session()->put('my_ip', $ipAddress);
            return $ipAddress;
        }else{
            session()->put('my_ip', "");
            return "";
        }
    }
}

if (!function_exists('ratesUser')) { // this will be used as a placeholder to the user wo logged in a client
    function ratesUser()
    {
        $now = Carbon::now();

        // Create a new User instance
        $user = User::make([
            "id" => 0,
            "name" => "Rates",
            "profile_pic" => null,
            "email" => "rates@yieldexchange.ca",
            "account_opening_date" => $now,
            "account_status" => "ACTIVE",
            "modified_date" => $now,
            "modified_section" => "last_login",
            "modified_by" => 0,
            "failed_login_attempts" => 0,
            "account_closure_date" => null,
            "account_closure_reason" => null,
            "last_login" => $now,
            "is_non_partnered_fi" => 0,
            "created_by" => 0,
            "is_temporary" => 0,
            "password_changed" => 0,
            "account_manager" => null,
            "inviter_name" => null,
            "is_test" => 0,
            "firstname" => "Yield Exchange",
            "lastname" => "Admin",
            "switched_organization_id" => 0,
            "is_system_admin" => 0,
            "requires_password_update" => 0,
            "admin_loggedin_as" => null,
            "admin_loggedin_as_agent" => null,
            "super_user" => true
        ]);

        // Create a new UsersDemoGraphicData instance
        $demographic = UsersDemoGraphicData::make([
            'user_id' => 0,
            'location' => 'Yield Exchange',
            'job_title' => 'Admin',
            'department' => 'Admin',
            'phone' => '+1 (778) 668-2484',
            'timezone' => 'Central',
            'updated_at' => null,
            'created_at' => null
        ]);

        // Associate demographic data with the user
        $user->setRelation('demographicData', $demographic);

        return $user;
    }

    if (!function_exists('convertBackToUTC')) {

        function convertBackToUTC($utcTime)
        {
            $user = Auth::user();
            $utcTime = Carbon::createFromFormat('Y-m-d H:i', $utcTime)->format('Y-m-d H:i');
            $carbonTime = Carbon::createFromFormat('Y-m-d H:i', $utcTime, 'UTC');
            $convertedtime = $carbonTime->setTimezone(formattedTimezone($user->timezone));
            return $convertedtime->format('Y-m-d H:i');
        }
    }

    if (!function_exists('convertToUTC')) {

        function convertToUTC($timee)
        {
            $user = Auth::user();
            $formatted = formattedTimezone($user->timezone);
            $carbonTime = Carbon::createFromFormat('Y-m-d H:i', $timee, $formatted);
            $utcTime = $carbonTime->utc();
            return $utcTime;
        }
    }

    if (!function_exists('getUsersTimeNow')) {

        function getUsersTimeNow($timee)
        {
            $user = Auth::user();
            $formatted = formattedTimezone($user->timezone);
            $carbonTime = Carbon::createFromFormat('Y-m-d H:i', $timee, $formatted);
            return $carbonTime;
        }
    }

    if (!function_exists('generateTradeRequestReference')) {
        function generateTradeRequestReference()
        {
            $count_ = CTTradeRequest::all()->count();
            do {
                $incr_ = 100 + $count_;
                $deposit_reference = date('ymd') . $incr_;
                $contracts = CTTradeRequest::where('reference_no', $deposit_reference)->first();
                $count_++;
            } while ($contracts);

            return $deposit_reference;
        }
    }
    if (!function_exists('generateCGTradeRequestReference')) {
        function generateCGTradeRequestReference()
        {
            $count_ = CGTradeRequest::all()->count();
            do {
                $incr_ = 100 + $count_;
                $deposit_reference = date('ymd') . $incr_;
                $contracts = CGTradeRequest::where('reference_no', $deposit_reference)->first();
                $count_++;
            } while ($contracts);

            return $deposit_reference;
        }
    }
    if (!function_exists('generateCGTradeRequestOfferReference')) {
        function generateCGTradeRequestOfferReference()
        {
            $count_ = CGTradeRequestInvitedCTOffer::all()->count();
            do {
                $incr_ = 100 + $count_;
                $reference = date('ymd') . $incr_;
                $contracts = CGTradeRequestInvitedCTOffer::where('offer_reference_no', $reference)->first();
                $count_++;
            } while ($contracts);

            return $reference;
        }
    }

    if (!function_exists('generateTradeOfferReference')) {
        function generateTradeOfferReference()
        {
            $count_ = Offer::all()->count();
            do {
                $incr_ = 100 + $count_;
                $reference = date('ymd') . $incr_;
                $contracts = CTTradeRequestCGOffer::where('offer_reference_no', $reference)->first();
                $count_++;
            } while ($contracts);

            return $reference;
        }
    }
    if (!function_exists('generateTradeOfferContractID')) {
        function generateTradeOfferContractID($dep_ref, $start = 1)
        {
            $new_dep_ref = $dep_ref . $start;
            $contracts = CTTradeRequestOfferDeposit::where('deposit_reference_no', $new_dep_ref)->first();
            if ($contracts) {
                return generateTradeOfferContractID($dep_ref, $start + 1);
            }
            return $new_dep_ref;
        }
    }
    if (!function_exists('eventReceivingOrganization')) {
        function eventReceivingOrganization($depositId, $initiatingOrg)
        {
            if ($initiatingOrg->type === "BANK") {
                $org = CTTradeRequestOfferDeposit::where("id", $depositId)->first();
                return $org->c_t_organization;
            } else if ($initiatingOrg->type === "DEPOSITOR") {
                $org = CTTradeRequestOfferDeposit::where("id", $depositId)->first();
                return $org->c_g_organization;
            }
        }
    }
    if (!function_exists('generateTradeEventBatchNumber')) {
        function generateTradeEventBatchNumber($depositId)
        {

            $batch = CTRequestDepositTradeEvent::where("c_t_trade_request_offer_deposit_id", $depositId)->orderBy("id", "DESC")->first();
            $nextbatchno = 0;
            if ($batch) {
                $nextbatchno = $batch->batch_no + 1;
            } else {
                $nextbatchno = 1;
            }
            return $nextbatchno;
        }
    }

    if (!function_exists('archiveRepoTable')) {
        function archiveRepoTable($fromid, $archivetablename, $datafrom, $actioning_user, $action = "")
        {
            $utc_date_now = getUTCDateNow();
            $user_id = Auth::user()->id;

            switch ($archivetablename) {
                case "trade_requests":

                    //archive trade_offers
                    $c_r_trade_requests = [
                        'c_r_trade_requests_id' => $fromid,
                        'investment_amount' => $datafrom['investment_amount'],
                        'reference_no' => $datafrom['reference_no'],
                        'bulk_reference_no' => $datafrom['bulk_reference_no'],
                        'term_length_type' => $datafrom['term_length_type'],
                        'term_length' => $datafrom['term_length'],
                        'trade_time' => $datafrom['trade_time'],
                        'currency' => $datafrom['currency'],
                        'request_status' => $datafrom['request_status'],
                        'closed_date' => $datafrom['closed_date'],
                        'organization_id' => $datafrom['organization_id'],
                        'user_id' => $datafrom['user_id'],
                        'admin_loggedin_as' => $datafrom['admin_loggedin_as'],
                        'is_test' => $datafrom['is_test'],
                        'is_demo' => $datafrom['is_demo'],
                        'modified_date' => $datafrom['modified_date'],
                        'modified_section' => $datafrom['modified_section'],
                        'special_instructions' => $datafrom['special_instructions'],
                        'created_at' => $utc_date_now,
                        'request_withdrawal_reason' => $datafrom['request_withdrawal_reason'],
                        'performed_by' => $actioning_user,
                        'prompting_action' => $action,
                        'settlement_date' => $datafrom['settlement_date'],
                        'interest_calculation_options_id' => $datafrom['interest_calculation_options_id'],
                    ];
                    DB::table("c_t_trade_requests_archive")->insert($c_r_trade_requests);
                    break;

                case "trade_offers":
                    //archive trade_offers
                    $tradeOfferBasket = [
                        'c_r_trade_request_c_t_offers_id' => $fromid,
                        'invitation_id' => $datafrom['invitation_id'],
                        'offer_reference_no' => $datafrom['offer_reference_no'],
                        'offer_minimum_amount' => $datafrom['offer_minimum_amount'],
                        'offer_maximum_amount' => $datafrom['offer_maximum_amount'],
                        'offer_trade_product_id' => $datafrom['offer_trade_product_id'],
                        'offer_term_length_type' => $datafrom['offer_term_length_type'],
                        'offer_term_length' => $datafrom['offer_term_length'],
                        'offer_interest_rate' => $datafrom['offer_interest_rate'],
                        'trade_tri_basket_third_party_id' => $datafrom['trade_tri_basket_third_party_id'],
                        'trade_organization_collateral_c_u_s_i_p_s_id' => $datafrom['trade_organization_collateral_c_u_s_i_p_s_id'],
                        'trade_settlement_period_id' => $datafrom['trade_settlement_period_id'],
                        'settlement_date' => $datafrom['settlement_date'],
                        'interest_calculation_options_id' => $datafrom['interest_calculation_options_id'],
                        'cg_trade_request_id' => $datafrom['cg_trade_request_id'],
                        'cg_trade_request_offer_id' => $datafrom['cg_trade_request_offer_id'],
                        'trade_date' => $datafrom['trade_date'],
                        'offer_status' => $datafrom['offer_status'],
                        'created_at' => $utc_date_now,
                        'rate_type' => 'fixed',
                        'variable_rate_value' => 0.00,
                        'fixed_rate' => 0.00,
                        'product_disclosure_statement' => $datafrom['product_disclosure_statement'],
                        'product_disclosure_url' => $datafrom['product_disclosure_url'],
                        'rate_operator' => $datafrom['rate_operator'],
                        'performed_by' => $actioning_user,
                        'prompting_action' => $action
                    ];

                    DB::table("c_t_trade_request_c_g_offers_archive")->insert($tradeOfferBasket);
                    break;

                case "trade_deposits":
                    //archive trade_deposits
                    $tradeRequestOfferDeposits = [
                        'c_r_trade_request_offer_deposits_id' =>  $fromid,
                        'offer_id' => $datafrom['offer_id'],
                        'deposit_reference_no' => $datafrom['deposit_reference_no'],
                        'offered_amount' => $datafrom[''],
                        'trade_date' => $datafrom['trade_date'],
                        'maturity_date' => $datafrom['maturity_date'],
                        'deposit_status' => $datafrom['deposit_status'],
                        'modified_date' => $datafrom['modified_date'],
                        'modified_section' => $datafrom['modified_section'],
                        'admins_notified' => '0',
                        'admins_notified_date' => $datafrom['admins_notified_date'],
                        'created_by' => $datafrom['created_by'],
                        'deposit_inactivate_reason' => $datafrom['deposit_inactivate_reason'],
                        'redemption_date' => $datafrom['redemption_date'],
                        'created_at' => $utc_date_now,
                        'active_trade_event' => $datafrom['active_trade_event'],
                        'active_trade_events_batch_number' => $datafrom['active_trade_events_batch_number'],
                        'performed_by' => $actioning_user,
                        'prompting_action' => $action
                    ];
                    break;
                    DB::table("c_t_trade_request_offer_deposits_archive")->insert($tradeRequestOfferDeposits);

                case "trade_basket_types":
                    //archive trade_basket_types
                    $basketType = [
                        'basket_type_id' => $fromid,
                        'basket_name' => $datafrom['basket_name'],
                        'basket_description' => $datafrom['basket_description'],
                        'disabled' => 0,
                        'created_at' => $utc_date_now,
                        'performed_by' => $actioning_user,
                        'prompting_action' => $action
                    ];
                    break;
                    DB::table("trade_basket_types_archive")->insert($basketType);

                case "trade_collateral_baskets":
                    //archive trade_collateral_baskets
                    $tradeColBasket = [
                        'trade_basket_type_id' => $fromid,
                        'basket_id' => $datafrom['basket_id'],
                        'basket_name' => $datafrom['basket_name'],
                        'is_disabled' => '0',
                        'disabled_until' => $datafrom['disabled_until'],
                        'created_at' => $utc_date_now,
                        'currency' => $datafrom['currency'],
                        'type' => $datafrom['type'],
                        'organization_id' => $datafrom['organization_id'],
                        'user_id' => $datafrom['user_id'],
                        'rating' => $datafrom['rating'],
                        'performed_by' => $actioning_user,
                        'prompting_action' => $action
                    ];

                    DB::table("trade_collateral_baskets_archive")->insert($tradeColBasket);
                    break;

                case "trade_organization_collaterals":
                    //archive trade_organization_collaterals
                    $tradeOrganizationCollateral = [
                        'trade_organization_collateral_id' => $fromid,
                        'trade_collateral_id' => $datafrom[''],
                        'organization_id' => $datafrom[''],
                        'user_id' => $datafrom[''],
                        'CUSIP_code' => $datafrom[''],
                        'trade_collateral_issuer_id' => $datafrom[''],
                        'maturity_date' => $datafrom[''],
                        'collateral_status' => 'PENDING',
                        'performed_by' => $actioning_user,
                        'prompting_action' => $action,
                        'created_at' => $utc_date_now,
                    ];

                    DB::table("trade_organization_collaterals_archive")->insert($tradeOrganizationCollateral);
                    break;

                case "trade_products":
                    //archive trade_products
                    $tradeProducts = [
                        'trade_products_id' => $fromid,
                        'product_name' => $datafrom['product_name'],
                        'is_disabled' => '0',
                        'disabled_until' => $datafrom['disabled_until'],
                        'created_at' => $utc_date_now,
                    ];

                    DB::table("trade_products_archive")->insert($tradeProducts);
                    break;

                case "trade_tri_basket_third_parties":
                    //archive trade_tri_basket_third_parties   
                    $tradeOrganizationCollateralThird = [
                        'trade_organization_collateral_id' => $fromid,
                        'trade_collateral_id' => $datafrom['trade_collateral_id'],
                        'organization_id' => $datafrom['organization_id'],
                        'user_id' => $datafrom['user_id'],
                        'CUSIP_code' => $datafrom['CUSIP_code'],
                        'trade_collateral_issuer_id' => $datafrom['trade_collateral_issuer_id'],
                        'maturity_date' => $datafrom['maturity_date'],
                        'collateral_status' => 'PENDING',
                        'performed_by' => $actioning_user,
                        'prompting_action' => $action,
                        'created_at' => $utc_date_now,
                    ];

                    DB::table("trade_tri_basket_third_parties_archive")->insert($tradeOrganizationCollateralThird);
                    break;
                case "request_pref_cols":
                    // return $datafrom;
                    //archive trade_tri_basket_third_parties   
                    foreach ($datafrom as $item) {
                        $preferedob['c_t_trade_request_id'] = $item['c_t_trade_request_id'];
                        $preferedob['preferred_collateral_id'] = $item['preferred_collateral_id'];
                        $preferedob['c_t_trade_request_preferred_collaterals_id'] = $item['id'];
                        DB::table("c_t_trade_request_preferred_collaterals_archive")->insert($preferedob);
                    }
                    break;
                case "cg_trade_offers":
                    //archive trade_offers
                    $tradeOfferBasket = [
                        'c_g_trade_request_invited_c_t_offer_id' => $fromid,
                        'c_g_trade_request_invited_c_t_id' => $datafrom['c_g_trade_request_invited_c_t_id'],
                        'offer_reference_no' => $datafrom['offer_reference_no'],
                        'offer_minimum_amount' => $datafrom['offer_minimum_amount'],
                        'offer_maximum_amount' => $datafrom['offer_maximum_amount'],
                        'offer_trade_product_id' => $datafrom['offer_trade_product_id'],
                        'offer_term_length_type' => $datafrom['offer_term_length_type'],
                        'offer_term_length' => $datafrom['offer_term_length'],
                        'offer_interest_rate' => $datafrom['offer_interest_rate'],
                        'trade_collateral_basket_id' => $datafrom['trade_collateral_basket_id'],
                        'rate_valid_until' => $datafrom['rate_valid_until'],
                        'interest_calculation_options_id' => $datafrom['interest_calculation_options_id'],
                        'trade_organization_collateral_c_u_s_i_p_s_id' => $datafrom['trade_organization_collateral_c_u_s_i_p_s_id'],
                        'trade_tri_basket_third_party_id' => $datafrom['trade_tri_basket_third_party_id'],
                        'rate_type' => $datafrom['rate_type'],
                        'variable_rate_value' => $datafrom['variable_rate_value'],
                        'fixed_rate' => $datafrom['fixed_rate'],
                        'product_disclosure_statement' => $datafrom['product_disclosure_statement'],
                        'product_disclosure_url' => $datafrom['product_disclosure_url'],
                        'rate_type' => $datafrom['rate_type'],
                        'variable_rate_value' =>$datafrom['variable_rate_value'],
                        'fixed_rate' => $datafrom['fixed_rate'],
                        'rate_operator' => $datafrom['rate_operator'],
                        'currency' => $datafrom['currency'],
                        'performed_by' => $actioning_user,
                        'prompting_action' => $action,
                    ];

                    DB::table("c_g_trade_request_invited_c_t_offers_archive")->insert($tradeOfferBasket);
                    break;
                default:

                    break;
                    // request_pref_cols
            }
        }
    }
    function generateNextNumber($previous_number, $type)
    {
        list($prefix, $type, $numeric_part) = explode('_', $previous_number);
        $incremented_number = (int)$numeric_part + 1;
        $new_numeric_part = str_pad($incremented_number, strlen($numeric_part), '0', STR_PAD_LEFT);
        $next_number = $prefix . '_' . $type . '_' . $new_numeric_part;
        return $next_number;
    }
    if (!function_exists('generateDummyBasket')) {

        function generateDummyBasket($ctObject, $orgbasketRef)
        {
            $user =  Auth::user();
            $counterexists = TradeTriBasketThirdParty::where("organization_id", $ctObject->id)->where("trade_collateral_basket_id", $orgbasketRef)->where("is_dummy", 1)->first();
            if ($counterexists == null) {

                $latest = TradeTriBasketThirdParty::where("is_dummy", 1)->orderBy("id", "DESC")->first();

                $latestbasket = "YIE_T_0000000000";
                if ($latest != null) {
                    $latestbasket = $latest->basket_id;
                }

                $basketID = generateNextNumber($latestbasket, "tri");
                $counterPartyInfo = [
                    'basket_id' =>  $basketID,
                    'organization_id' => $ctObject->id,
                    'is_dummy' => 1,
                    'trade_collateral_basket_id' => $orgbasketRef
                ];
                $counterPartyInfo['created_at'] = getUTCTimeNow();
                $created_third_party = TradeTriBasketThirdParty::create($counterPartyInfo);
                return $created_third_party;
            } else {
                return $counterexists;
            }
        }
    }
    if (!function_exists('generateDummyOrgCollateral')) {
        function generateDummyOrgCollateral($collateralObject)
        {
            $user =  Auth::user();
            $latest = TradeOrganizationCollateral::query();
            $latest->whereHas("tradeOrganizationCUSSIP", function ($query) {
                $query->where("is_dummy", 1);
            });
            $latest = $latest->orderBy("id", "DESC")->first();
            $prevcuspisCode = "YIE_B_0000000000";

            if ($latest != null) {
                $prevcuspisCode = $latest->tradeOrganizationCUSSIP[0]->CUSIP_code;
            }

            $cuspisCode = generateNextNumber($prevcuspisCode, "bi");
            $colInfo = [

                'trade_collateral_issuer_id' => 0,
                'currency' => "CAD",
                'rating' => "N/A",
                'organization_id' => $user->organization->id,

            ];
            $colInfo['created_at'] = getUTCTimeNow();
            $colInfo['collateral_status'] = "PENDING";
            $colInfo['user_id'] = $user->id;
            $created_basket = TradeOrganizationCollateral::create($colInfo);
            if ($created_basket) {
                $cusiInfo = [
                    'trade_collateral_id' => $collateralObject->id,
                    'CUSIP_code' =>  $cuspisCode,
                    'trade_organization_collateral_id' =>  $created_basket->id,
                    'is_dummy' => 1,
                    'cusips_status' => 'ACTIVE'
                ];
                $created_cusip = TradeOrganizationCollateralCUSIP::create($cusiInfo);
                return $created_cusip;
            }
        }
    }

    if (!function_exists('generateRepoOfferContractID')) {
        function generateRepoOfferContractID($dep_ref, $start = 1)
        {

            $new_dep_ref = $dep_ref . $start;
            $contracts = CTTradeRequestOfferDeposit::where('deposit_reference_no', $new_dep_ref)->first();

            if ($contracts) {
                return generateRepoOfferContractID($dep_ref, $start + 1);
            }

            return $new_dep_ref;
        }
    }

    if (!function_exists('shortenCollateralID')) {
        function shortenCollateralID($id)
        {
            $part1 = strtoupper(substr($id, 0, 2));
            $part2 = 'XXX';

            $part3 = strtoupper(substr($id, -3));

            return $part1 . $part2 . $part3;
        }
    }

    if (!function_exists('generateSlug')) {
        function generateSlug($word)
        {
            $pattern = '/[^a-zA-Z0-9]/';
            $replacement = '_';
            $string = $word;
            $result = preg_replace($pattern, $replacement, $string);
            return $result;
        }
    }
}
