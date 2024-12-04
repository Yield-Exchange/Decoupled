<?php

namespace App\View\Navigation;

use App\Models\Campaign;
use App\Models\FICampaignGroup;
use App\Models\FICampaignProduct;

class SideBar extends NavigationCommon
{
    private $mycampaignscount = 0, $mydraftscount = 0, $myproductscount = 0, $mygroupscount = 0, $organization;
    public function __construct($userGroup, $organization = null)
    {
        $this->organization = $organization;
        $this->setUserGroup($userGroup);
        $this->setView('dashboard/sidebar-menu');
    }

    public function banks()
    {
        $this->mycampaignscount = Campaign::where("fi_id", $this->organization->id)->whereIn('status', ['ACTIVE', 'SCHEDULED'])->count();
        $this->mydraftscount = Campaign::where("fi_id", $this->organization->id)->where('status', 'DRAFT')->count();
        $this->myproductscount = FICampaignProduct::where("fi_id", $this->organization->id)->count();
        $this->mygroupscount = FICampaignGroup::where("fi_id", $this->organization->id)->where('group_deletion_status', '!=', 'INACTIVE')->where("group_type", "User Created")->count();
        $return = array(
            [
                'permission' => null,
                'url' => url('/dashboard'),
                'name' => 'Dashboard',
                'icon' => asset('assets/dashboard/icons/dash.svg'),
                'submenu' => null,
                'url_name' => 'dashboard',
            ],
            [
                'permission' => 'bank/my-campaigns/page-access',
                'url' => url('/campaigns'),
                'name' => 'Campaigns',
                'icon' => asset('assets/dashboard/icons/compaign.svg'),
                'submenu' => array(
                    [
                        'permission' => null,
                        'url' => url('/campaigns'),
                        'name' => 'My Campaigns (' . $this->mycampaignscount . ')',
                        'url_name' => 'campaigns',
                    ],
                    [
                        'permission' => null,
                        'url' => url('/campaigns/drafts'),
                        'name' => 'Drafts (' . $this->mydraftscount . ')',
                        'url_name' => 'campaigns/drafts',
                    ],
                    [
                        'permission' => null,
                        'url' => url('/campaigns/products'),
                        'name' => 'My Products (' . $this->myproductscount . ')',
                        'url_name' => 'campaigns/products',
                    ],
                    [
                        'permission' => null,
                        'url' => url('/campaigns/groups'),
                        'name' => 'My Groups (' . $this->mygroupscount . ')',
                        'url_name' => 'campaigns/groups',
                    ]
                ),
                'is_beta' => true,
                'url_name' => 'campaigns',
            ],
            [
                'permission' => 'bank/new-requests/page-access',
                'url' => url('/new-requests'),
                'name' => 'New Requests',
                'icon' => asset('assets/dashboard/icons/new-requests.svg'),
                'submenu' => null,
                'url_name' => 'new-requests',
            ],
            [
                'permission' => 'bank/in-progress/page-access',
                'url' => url('/in-progress'),
                'name' => 'In Progress',
                'icon' => asset('assets/dashboard/icons/in-progress.svg'),
                'submenu' => null,
                'url_name' => 'in-progress',
            ],
            [
                'permission' => 'bank/pending-deposits/page-access',
                'url' => url('/bank-pending-deposits'),
                'name' => 'Pending Deposits',
                'icon' => asset('assets/dashboard/icons/pending-deposit.svg'),
                'submenu' => null,
                'url_name' => 'bank-pending-deposits',
            ],
            [
                'permission' => 'bank/active-deposits/page-access',
                'url' => url('/bank-active-deposits'),
                'name' => 'Active Deposits',
                'icon' => asset('assets/dashboard/icons/active-deposit.svg'),
                'submenu' => null,
                'url_name' => 'bank-active-deposits',
            ],
            [
                'permission' => 'bank/history/page-access',
                'url' => url('/bank-history'),
                'name' => 'History',
                'icon' => asset('assets/dashboard/icons/history.svg'),
                'submenu' => null,
                'url_name' => 'bank-history',
            ],
        );
        $canaccessrepos = $this->organization->organizationHas('enable_repos');
        if ($canaccessrepos) {
            $return[] = [
                'permission' => null,
                'url' => url('/view-all-new-requests'),
                'name' => 'Repos Exchange',
                'icon' => asset('assets/dashboard/icons/D-Menu.svg'),
                'submenu' => array(
                    [
                        'permission' => null,
                        'url' => url('view-all-new-requests'),
                        'name' => 'New Requests',
                        'icon' => asset('assets/dashboard/icons/D-Chart.svg'),
                        'submenu' => null,
                        'url_name' => 'view-all-new-requests',
                    ],
                    [
                        'permission' => null,
                        'url' => url('/view-all-in-progress'),
                        'name' => 'In Progress',
                        'icon' => asset('assets/dashboard/icons/D-Chart.svg'),
                        'submenu' => null,
                        'url_name' => 'view-all-in-progress',
                    ],
                    [
                        'permission' => null,
                        'url' => url('/cg-repos-pending-trades'),
                        'name' => 'Pending Trades',
                        'icon' => asset('assets/dashboard/icons/D-Chart.svg'),
                        'submenu' => null,
                        'url_name' => 'cg-repos-pending-trades',
                    ],
                    [
                        'permission' => null,
                        'url' => url('/cg-repos-active-trades'),
                        'name' => 'Active Trades',
                        'icon' => asset('assets/dashboard/icons/D-Chart.svg'),
                        'submenu' => null,
                        'url_name' => 'cg-repos-active-trades',
                    ],
                    [
                        'permission' => null,
                        'url' => url('/cg-repos-history-trades'),
                        'name' => 'History',
                        'icon' => asset('assets/dashboard/icons/D-Chart.svg'),
                        'submenu' => null,
                        'url_name' => 'cg-repos-history-trades',
                    ],
                ),
                'url_name' => 'repos',
                'is_beta' => true,
            ];
        }

        if (!$this->organization->enable_campaigns) {
            // Define the name you want to remove
            $nameToRemove = 'Campaigns';

            // Use array_filter() with an anonymous function to remove elements with the specified name
            $return = array_filter($return, fn ($item) => $item['name'] !== $nameToRemove);
        }

        return $return;
    }

    public function depositors()
    {

        $canaccessrepos = $this->organization->organizationHas('enable_repos');

        $return = array(
            [
                'permission' => null,
                'url' => url('/dashboard'),
                'name' => 'Dashboard',
                'icon' => asset('assets/dashboard/icons/dash.svg'),
                'submenu' => null,
                'url_name' => 'dashboard',
            ],
            [
                'permission' => 'depositor/my-offers---campaigns/page-access',
                'url' => url('/inv-camp-offers'),
                'name' => 'My Offers',
                'icon' => asset('assets/dashboard/icons/D-Menu.svg'),
                'submenu' => null,
                'url_name' => 'inv-camp-offers*',
                'is_beta' => true,
            ],
            [
                'permission' => 'depositor/post-request/page-access',
                'url' => url('/post-request'),
                'name' => 'Post Request',
                'icon' => asset('assets/dashboard/icons/D-Chart.svg'),
                'submenu' => null,
                'url_name' => 'post-request',
            ],
            [
                'permission' => 'depositor/review-offers/page-access',
                'url' => url('/review-offers'),
                'name' => 'Review Offers',
                'icon' => asset('assets/dashboard/icons/D-Note.svg'),
                'submenu' => null,
                'url_name' => ['review-offers', 'pick-offers*', 'edit-post-request*', 'request/summary*', 'request-summary', 'summary/request/invited_institutions*'],
            ],
            [
                'permission' => 'depositor/pending-deposits/page-access',
                'url' => url('/pending-deposits'),
                'name' => 'Pending Deposits',
                'icon' => asset('assets/dashboard/icons/D-User.svg'),
                'submenu' => null,
                'url_name' => 'pending-deposits',
            ],
            [
                'permission' => 'depositor/active-deposits/page-access',
                'url' => url('/active-deposits'),
                'name' => 'Active Deposits',
                'icon' => asset('assets/dashboard/icons/D-Setting - 3 2.svg'),
                'submenu' => null,
                'url_name' => 'active-deposits',
            ],


            [
                'permission' => 'depositor/history/page-access',
                'url' => url('/depositor-history'),
                'name' => 'History',
                'icon' => asset('assets/dashboard/icons/D-Setting - 3.svg'),
                'submenu' => null,
                'url_name' => 'depositor-history',
            ],
        );

        if ($canaccessrepos) {
            $return[] = [
                'permission' => null,
                'url' => url('/repos'),
                'name' => 'Repos Exchange',
                'icon' => asset('assets/dashboard/icons/D-Menu.svg'),
                'submenu' => array(
                    [
                        'permission' => null,
                        'url' => url('/repos'),
                        'name' => 'Post Request',
                        'icon' => asset('assets/dashboard/icons/D-Chart.svg'),
                        'submenu' => null,
                        'url_name' => 'repos',
                    ],
                    [
                        'permission' => null,
                        'url' => url('/repos-reviews'),
                        'name' => 'Review Offers',
                        'icon' => asset('assets/dashboard/icons/D-Chart.svg'),
                        'submenu' => null,
                        'url_name' => 'repos-reviews',
                    ],
                    [
                        'permission' => null,
                        'url' => url('/ct-repos-pending-trades'),
                        'name' => 'Pending Trades',
                        'icon' => asset('assets/dashboard/icons/D-Chart.svg'),
                        'submenu' => null,
                        'url_name' => 'ct-repos-pending-trades',
                    ],
                    [
                        'permission' => null,
                        'url' => url('/ct-repos-active-trades'),
                        'name' => 'Active Trades',
                        'icon' => asset('assets/dashboard/icons/D-Chart.svg'),
                        'submenu' => null,
                        'url_name' => 'ct-repos-active-trades',
                    ],
                    [
                        'permission' => null,
                        'url' => url('/ct-repos-history-trades'),
                        'name' => 'History',
                        'icon' => asset('assets/dashboard/icons/D-Chart.svg'),
                        'submenu' => null,
                        'url_name' => 'ct-repos-history-trades',
                    ],
                ),
                'url_name' => 'repos',
                'is_beta' => true,
            ];
        }

        if (!$this->organization->enable_campaigns) {
            // Define the name you want to remove
            $nameToRemove = 'My Offers';

            // Use array_filter() with an anonymous function to remove elements with the specified name
            $return = array_filter($return, fn ($item) => $item['name'] !== $nameToRemove);
        }

        return $return;
    }

    public function admin()
    {
        return array(
            [
                'permission' => null,
                'url' => route('admin.home'),
                'name' => 'Dashboard',
                'icon' => asset('assets/dashboard/icons/dash.svg'),
                'submenu' => null,
                'url_name' => 'yie-admin/dashboard',
            ],
            [
                'permission' => 'admin/organizations-onboard/page-access',
                'url' => route('admin.users', 'users_onboard'),
                'name' => 'Users & Organizations',
                'icon' => asset('assets/dashboard/icons/in-progress.svg'),
                'submenu' => array(
                    [
                        'permission' => 'admin/organizations-onboard/page-access',
                        'url' => route('admin.users', 'users_onboard'),
                        'name' => 'Organizations Onboard',
                        'icon' => asset('assets/dashboard/icons/dash.svg'),
                        'submenu' => null,
                        'url_name' => 'yie-admin/users/users_onboard',
                    ],
                    [
                        'permission' => 'admin/gic-investors/page-access',
                        'url' => route('admin.users', 'depositors'),
                        'name' => 'GIC Investors',
                        'icon' => asset('assets/dashboard/icons/dash.svg'),
                        'submenu' => null,
                        'url_name' => 'yie-admin/users/depositors',
                    ],
                    [
                        'permission' => 'admin/banks/page-access',
                        'url' => route('admin.users', 'banks'),
                        'name' => 'Banks',
                        'icon' => asset('assets/dashboard/icons/dash.svg'),
                        'submenu' => null,
                        'url_name' => 'yie-admin/users/banks',
                    ],
                    [
                        'permission' => 'admin/non-partnered-fi/page-access',
                        'url' => route('admin.users', 'non_partnered_fi'),
                        'name' => 'Non Partnered FI',
                        'icon' => asset('assets/dashboard/icons/dash.svg'),
                        'submenu' => null,
                        'url_name' => 'yie-admin/users/non_partnered_fi',
                    ],
                    [
                        'permission' => 'admin/manage-admins/page-access',
                        'url' => route('admin.users', 'admins'),
                        'name' => 'Manage Admins',
                        'icon' => asset('assets/dashboard/icons/dash.svg'),
                        'submenu' => null,
                        'url_name' => 'yie-admin/users/admins',
                    ],
                    [
                        'permission' => 'admin/manage-admins/page-access',
                        'url' => route('admin.users', 'waiting_list'),
                        'name' => 'Users Waiting List',
                        'icon' => asset('assets/dashboard/icons/dash.svg'),
                        'submenu' => null,
                        'url_name' => 'yie-admin/users/waiting-list',
                    ],
                ),
                'url_name' => 'yie-admin/users*',
            ],
            [
                'permission' => null,
                'url' => route('product.list'),
                'name' => 'Products',
                'icon' => asset('assets/dashboard/icons/pending-deposit.svg'),
                'submenu' => null,
                'url_name' => ['yie-admin/product-list', 'yie-admin/product-list'],
            ],
            [
                'permission' => null,
                'url' => route('product.repo.list'),
                'name' => 'Repo Products',
                'icon' => asset('assets/dashboard/icons/pending-deposit.svg'),
                'submenu' => null,
                'url_name' => ['yie-admin/-repo-product-list', 'yie-admin/repo-product-list'],
            ],
            [
                'permission' => 'admin/activity-logs/page-access',
                'url' => route('admin.system.activity.logs'),
                'name' => 'Activity Logs',
                'icon' => asset('assets/dashboard/icons/pending-deposit.svg'),
                'submenu' => null,
                'url_name' => ['yie-admin/system/activity-logs', 'yei-admin/login/activity-logs/data'],
            ],
            [
                'permission' => 'admin/roles/page-access',
                'url' => route('admin.roles.index'),
                'name' => 'Users Roles',
                'icon' => asset('assets/dashboard/icons/in-progress.svg'),
                'submenu' => null,
                'url_name' => ['yie-admin/roles', 'yie-admin/roles/index', 'yie-admin/roles-permissions/*'],
            ],
            [
                'permission' => 'admin/system-settings/page-access',
                'url' => route('admin.system.settings'),
                'name' => 'System Settings',
                'icon' => asset('assets/dashboard/icons/history.svg'),
                'submenu' => null,
                'url_name' => 'yie-admin/system/settings',
            ],
            [
                'permission' => 'admin/industries/page-access',
                'url' => route('industries.index'),
                'name' => 'Industries',
                'icon' => asset('assets/dashboard/icons/pending-deposit.svg'),
                'submenu' => null,
                'url_name' => 'yie-admin/system/settings',
            ],
        );
    }

    public function auth()
    {
        $user = auth()->user();
        if (!$user->organization->accepted_terms_and_conditions && $user->organization->type == "DEPOSITOR") {
            $array = array([
                'permission' => null,
                'url' => url('dashboard'),
                'name' => 'Terms and conditions',
                'icon' => asset('assets/dashboard/icons/dash.svg'),
                'submenu' => null,
                'url_name' => 'first-login/confirm-organization-seats',
            ]);
        } else {
            $array = array([
                'permission' => null,
                'url' => url('first-login/confirm-organization-seats'),
                'name' => 'Confirm Users',
                'icon' => asset('assets/dashboard/icons/dash.svg'),
                'submenu' => null,
                'url_name' => 'first-login/confirm-organization-seats',
            ]);
        }
        return $array;
    }
}
