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
        $canaccessrepos = $this->organization->organizationHas('enable_repos');
        $enable_campaigns = $this->organization->organizationHas('enable_campaigns');

        $lunchpad = [
            [
                'show_icon' => true,
                'permission' => null,
                'url' => url('/launchpad'),
                'name' => 'Launchpad',
                'icon' => asset('assets/dashboard/icons/launchpad.svg'),
                'type' => 'Launch',
                'routes' => ['launchpad'],
                'submenu' => null,
                'url_name' => 'launchpad',
                'is_beta' => false,
            ]
        ];
        $gic_menu =
            [
                'show_icon' => true,
                'permission' => null,
                'url' => url('/'),
                'name' => 'GIC Exchange',
                'icon' => asset('assets/dashboard/icons/gicexchange.svg'),
                'type' => 'GIC',
                'routes' => ['dashboard', 'campaigns', 'account-setting', 'place-offer', 'new-requests', 'in-progress', 'bank-pending-deposits', 'bank', 'offer', 'bank-active-deposits', 'deposit', 'bank-history'],
                'url_name' => '/',
                'is_beta' => false,
                'submenu' => [
                    [
                        'show_icon' => true,
                        'permission' => null,
                        'url' => url('/dashboard'),
                        'name' => 'Dashboard',
                        'icon' => asset('assets/dashboard/icons/dash.svg'),
                        'submenu' => null,
                        'url_name' => 'dashboard',
                    ],
                    [
                        'show_icon' => true,
                        'permission' => 'bank/my-campaigns/page-access',
                        'url' => url('/campaigns'),
                        'name' => 'Campaigns',
                        'icon' => asset('assets/dashboard/icons/compaign.svg'),
                        'submenu' => [
                            [
                                'show_icon' => false,
                                'permission' => null,
                                'url' => url('/campaigns'),
                                'name' => 'My Campaigns (' . $this->mycampaignscount . ')',
                                'url_name' => 'campaigns',
                                'submenu' => null,
                                'icon' => null,
                            ],
                            [
                                'show_icon' => false,
                                'permission' => null,
                                'url' => url('/campaigns/drafts'),
                                'name' => 'Drafts (' . $this->mydraftscount . ')',
                                'submenu' => null,
                                'icon' => null,
                                'url_name' => 'campaigns/drafts',
                            ],
                            [
                                'show_icon' => false,
                                'permission' => null,
                                'url' => url('/campaigns/products'),
                                'name' => 'My Products (' . $this->myproductscount . ')',
                                'url_name' => 'campaigns/products',
                                'submenu' => null,
                                'icon' => null,
                            ],
                            [
                                'show_icon' => false,
                                'permission' => null,
                                'url' => url('/campaigns/groups'),
                                'name' => 'My Groups (' . $this->mygroupscount . ')',
                                'url_name' => 'campaigns/groups',
                                'submenu' => null,
                                'icon' => null,
                            ]
                        ],
                        'routes' => ['campaigns/groups', 'campaigns/products', 'campaigns', 'campaigns/drafts'],
                        'is_beta' => true,
                        'url_name' => 'campaigns',
                    ],
                    [
                        'show_icon' => true,
                        'permission' => 'bank/new-requests/page-access',
                        'url' => url('/new-requests'),
                        'name' => 'New Requests',
                        'icon' => asset('assets/dashboard/icons/new-requests.svg'),
                        'submenu' => null,
                        'url_name' => 'new-requests',
                    ],
                    [
                        'show_icon' => true,
                        'permission' => 'bank/in-progress/page-access',
                        'url' => url('/in-progress'),
                        'name' => 'In Progress',
                        'icon' => asset('assets/dashboard/icons/in-progress.svg'),
                        'submenu' => null,
                        'url_name' => 'in-progress',
                    ],
                    [
                        'show_icon' => true,
                        'permission' => 'bank/pending-deposits/page-access',
                        'url' => url('/bank-pending-deposits'),
                        'name' => 'Pending Deposits',
                        'icon' => asset('assets/dashboard/icons/pending-deposit.svg'),
                        'submenu' => null,
                        'url_name' => 'bank-pending-deposits',
                    ],
                    [
                        'show_icon' => true,
                        'permission' => 'bank/active-deposits/page-access',
                        'url' => url('/bank-active-deposits'),
                        'name' => 'Active Deposits',
                        'icon' => asset('assets/dashboard/icons/active-deposit.svg'),
                        'submenu' => null,
                        'url_name' => 'bank-active-deposits',
                    ],
                    [
                        'show_icon' => true,
                        'permission' => 'bank/history/page-access',
                        'url' => url('/bank-history'),
                        'name' => 'History',
                        'icon' => asset('assets/dashboard/icons/history.svg'),
                        'submenu' => null,
                        'url_name' => 'bank-history',
                    ],
                ]
            ];
        $return = $lunchpad;

        $repo_menu = [
            'show_icon' => true,
            'permission' => null,
            'url' => url('/repos/view-all-new-requests'),
            'name' => 'Repos Exchange',
            'icon' => asset('assets/dashboard/icons/D-Menu.svg'),
            'submenu' => array(

                [
                    'show_icon' => true,
                    'permission' => null,
                    'url' => url('/repos/publish-rates-offers'),
                    'name' => 'Publish Rates Offers',
                    'icon' => asset('assets/dashboard/icons/compaign.svg'),
                    'submenu' => null,
                    'url_name' => 'repos/publish-rates-offers',
                ],
                [
                    'show_icon' => true,
                    'permission' => null,
                    'url' => url('/repos/view-all-new-requests'),
                    'name' => 'New Requests',
                    'icon' => asset('assets/dashboard/icons/new-requests.svg'),
                    'submenu' => null,
                    'url_name' => 'repos/view-all-new-requests',
                ],
                [
                    'show_icon' => true,
                    'permission' => null,
                    'url' => url('/repos/view-all-in-progress'),
                    'name' => 'Submitted Offers',
                    'icon' => asset('assets/dashboard/icons/in-progress.svg'),
                    'submenu' => null,
                    'url_name' => 'repos/view-all-in-progress',
                ],
                [
                    'show_icon' => true,
                    'permission' => null,
                    'url' => url('/repos/cg-repos-pending-trades'),
                    'name' => 'Pending Trades',
                    'icon' => asset('assets/dashboard/icons/pending-deposit.svg'),
                    'submenu' => null,
                    'url_name' => 'repos/cg-repos-pending-trades',
                ],
                [
                    'show_icon' => true,
                    'permission' => 'bank/repos/active-trades',
                    'url' => url('/repos/cg-repos-active-trades'),
                    'name' => 'Active Trades',
                    'icon' => asset('assets/dashboard/icons/active-deposit.svg'),
                    'submenu' => null,
                    'url_name' => 'repos/cg-repos-active-trades',
                ],
                [
                    'show_icon' => true,
                    'permission' => 'bank/repos/trade-history',
                    'url' => url('/repos/cg-repos-history-trades'),
                    'name' => 'History',
                    'icon' => asset('assets/dashboard/icons/history.svg'),
                    'submenu' => null,
                    'url_name' => 'repos/cg-repos-history-trades',
                ],
                [
                    'show_icon' => true,
                    'permission' => 'bank/repos/baskets-menu-access',
                    'url' => url('/repos/view-baskets'),
                    'name' => 'Basket Manager',
                    'icon' => asset('assets/dashboard/icons/compaign.svg'),
                    'submenu' => array(
                        [
                            'show_icon' => false,
                            'permission' => null,
                            'url' => url('/repos/create-basket'),
                            'name' => 'Create Basket',
                            'url_name' => 'repos/create-basket',
                            'submenu' => null,
                            'icon' => null,
                        ],
                        [
                            'show_icon' => false,
                            'permission' => null,
                            'url' => url('/repos/view-baskets'),
                            'name' => 'Tri-Party Baskets',
                            'url_name' => 'repos/view-baskets',
                            'submenu' => null,
                            'icon' => null,
                        ],
                        [
                            'show_icon' => false,
                            'permission' => null,
                            'url' => url('/repos/view-collaterals'),
                            'name' => 'Bilateral Baskets ',
                            'url_name' => 'repos/view-collaterals',
                            'submenu' => null,
                            'icon' => null,
                        ],
                    ),
                    'routes' => ['repos/view-collaterals', 'repos/view-baskets', 'repos/create-basket'],

                    'is_beta' => false,
                    'url_name' => 'repo/baskets',
                ],

            ),
            'url_name' => 'repos',
            'is_beta' => true,
        ];

        if ($canaccessrepos) {
            $return[] = $repo_menu;
        }
        if ($enable_campaigns) {
            $return[] = $gic_menu;
        }


        if (!$this->organization->enable_campaigns) {
            // Define the name you want to remove
            $nameToRemove = 'Campaigns';

            // Use array_filter() with an anonymous function to remove elements with the specified name
            $return = array_filter($return, fn($item) => $item['name'] !== $nameToRemove);
        }

        return $return;
    }

    public function depositors()
    {
        $enable_campaigns = $this->organization->organizationHas('enable_campaigns');
        $canaccessrepos = $this->organization->organizationHas('enable_repos');

        $lunchpad = [
            [
                'show_icon' => true,
                'permission' => null,
                'url' => url('/launchpad'),
                'name' => 'Launchpad',
                'icon' => asset('assets/dashboard/icons/launchpad.svg'),
                'type' => 'Launch',
                'routes' => ['launchpad'],
                'submenu' => null,
                'url_name' => 'launchpad',
                'is_beta' => false,
            ]
        ];


        $gic_menu = [
            'show_icon' => true,
            'permission' => null,
            'url' => url('/'),
            'name' => 'GIC Exchange',
            'icon' => asset('assets/dashboard/icons/gicexchange.svg'),
            'type' => 'GIC',
            'url_name' => ['dashboard', 'inv-camp-offers', 'post-request', 'request-summary', 'review-offers', 'request', 'pending-deposits', 'active-deposits', 'depositor-history'],
            'routes' => ['dashboard', 'inv-camp-offers', 'post-request', 'request-summary', 'review-offers', 'request', 'pending-deposits', 'active-deposits', 'depositor-history'],
            'submenu' =>   [
                [
                    'permission' => null,
                    'show_icon' => true,
                    'type' => 'GIC',
                    'url' => url('/dashboard'),
                    'name' => 'Dashboard',
                    'icon' => asset('assets/dashboard/icons/dash.svg'),
                    'submenu' => null,
                    'url_name' => 'dashboard',
                ],
                [
                    'show_icon' => true,
                    'permission' => 'depositor/my-offers---campaigns/page-access',
                    'url' => url('/inv-camp-offers'),
                    'name' => 'My Offers',
                    'type' => 'GIC',
                    'icon' => asset('assets/dashboard/icons/D-Menu.svg'),
                    'submenu' => null,
                    'url_name' => 'inv-camp-offers*',
                    'is_beta' => true,
                ],
                [
                    'show_icon' => true,
                    'permission' => 'depositor/post-request/page-access',
                    'url' => url('/post-request'),
                    'type' => 'GIC',
                    'name' => 'Post Request',
                    'icon' => asset('assets/dashboard/icons/D-Chart.svg'),
                    'submenu' => null,
                    'url_name' => 'post-request',
                ],
                [
                    'show_icon' => true,
                    'permission' => 'depositor/review-offers/page-access',
                    'url' => url('/review-offers'),
                    'type' => 'GIC',
                    'name' => 'Review Offers',
                    'icon' => asset('assets/dashboard/icons/D-Note.svg'),
                    'submenu' => null,
                    'url_name' => ['review-offers', 'pick-offers*', 'edit-post-request*', 'request/summary*', 'request-summary', 'summary/request/invited_institutions*'],
                ],
                [
                    'show_icon' => true,
                    'permission' => 'depositor/pending-deposits/page-access',
                    'url' => url('/pending-deposits'),
                    'type' => 'GIC',
                    'name' => 'Pending Deposits',
                    'icon' => asset('assets/dashboard/icons/D-User.svg'),
                    'submenu' => null,
                    'url_name' => 'pending-deposits',
                ],
                [
                    'show_icon' => true,
                    'permission' => 'depositor/active-deposits/page-access',
                    'url' => url('/active-deposits'),
                    'name' => 'Active Deposits',
                    'type' => 'GIC',
                    'icon' => asset('assets/dashboard/icons/D-Setting - 3 2.svg'),
                    'submenu' => null,
                    'url_name' => 'active-deposits',
                ],
                [
                    'show_icon' => true,
                    'permission' => 'depositor/history/page-access',
                    'url' => url('/depositor-history'),
                    'name' => 'History',
                    'type' => 'GIC',
                    'icon' => asset('assets/dashboard/icons/D-Setting - 3.svg'),
                    'submenu' => null,
                    'url_name' => 'depositor-history',
                ],
            ],
            'url_name' => '/',
            'is_beta' => false,
        ];
        $repo_menu = [
            'show_icon' => true,
            'permission' => null,
            'url' => url('/repos/ct-repos-my-offers'),
            'name' => 'Repos Exchange',
            'icon' => asset('assets/dashboard/icons/D-Menu.svg'),
            'type' => 'REPOS',
            'routes' => ['repos'],
            'submenu' => [
                [
                    'show_icon' => true,
                    'permission' => null,
                    'url' => url('/repos/ct-repos-my-offers'),
                    'name' => 'Offers in Market',
                    'type' => 'REPOS',
                    'icon' => asset('assets/dashboard/icons/compaign.svg'),
                    'submenu' => null,
                    'url_name' => 'repos/ct-repos-my-offers',
                ],
                [
                    'show_icon' => true,
                    'permission' => null,
                    'url' => url('/repos/post-request'),
                    'name' => 'Post Request',
                    'icon' => asset('assets/dashboard/icons/D-Chart.svg'),
                    'type' => 'REPOS',
                    'submenu' => null,
                    'url_name' => 'repos/post-request',
                ],
                [
                    'show_icon' => true,
                    'permission' => null,
                    'url' => url('/repos/repos-reviews'),
                    'name' => 'Review Offers',
                    'type' => 'REPOS',
                    'icon' => asset('assets/dashboard/icons/D-Note.svg'),
                    'submenu' => null,
                    'url_name' => 'repos/repos-reviews',
                ],
                [
                    'show_icon' => true,
                    'permission' => null,
                    'url' => url('/repos/ct-repos-pending-trades'),
                    'type' => 'REPOS',
                    'name' => 'Pending Trades',
                    'icon' => asset('assets/dashboard/icons/D-User.svg'),
                    'submenu' => null,
                    'url_name' => 'repos/ct-repos-pending-trades',
                ],
                [
                    'show_icon' => true,
                    'permission' => null,
                    'url' => url('/repos/ct-repos-active-trades'),
                    'type' => 'REPOS',
                    'name' => 'Active Trades',
                    'icon' => asset('assets/dashboard/icons/D-Setting - 3 2.svg'),
                    'submenu' => null,
                    'url_name' => 'repos/ct-repos-active-trades',
                ],
                [
                    'show_icon' => true,
                    'permission' => null,
                    'url' => url('/repos/ct-repos-history-trades'),
                    'type' => 'REPOS',
                    'name' => 'History',
                    'icon' => asset('assets/dashboard/icons/D-Setting - 3.svg'),
                    'submenu' => null,
                    'url_name' => 'repos/ct-repos-history-trades',
                ],
            ],
            'url_name' => 'repos',
            'is_beta' => true,
        ];

        $return = $lunchpad;

        if ($canaccessrepos) {
            $return[] = $repo_menu;
        }
        if ($enable_campaigns) {
            $return[] = $gic_menu;
        }




        if (!$this->organization->enable_campaigns) {
            // Define the name you want to remove
            $nameToRemove = 'My Offers';

            // Use array_filter() with an anonymous function to remove elements with the specified name
            $return = array_filter($return, fn($item) => $item['name'] !== $nameToRemove);
        }

        return $return;
    }

    public function admin()
    {
        return array(
            [
                'permission' => null,
                'show_icon' => true,
                'url' => route('admin.home'),
                'name' => 'Dashboard',
                'icon' => asset('assets/dashboard/icons/dash.svg'),
                'submenu' => null,
                'url_name' => 'yie-admin/dashboard',
            ],
            [
                'permission' => 'admin/organizations-onboard/page-access',
                'show_icon' => true,
                'url' => route('admin.users', 'users_onboard'),
                'name' => 'Users & Organizations',
                'icon' => asset('assets/dashboard/icons/in-progress.svg'),
                'submenu' => array(
                    [
                        'permission' => 'admin/organizations-onboard/page-access',
                        'show_icon' => false,
                        'url' => route('admin.users', 'users_onboard'),
                        'name' => 'Organizations Onboard',
                        'icon' => asset('assets/dashboard/icons/dash.svg'),
                        'submenu' => null,
                        'url_name' => 'yie-admin/users/users_onboard',
                    ],
                    [
                        'permission' => 'admin/gic-investors/page-access',
                        'show_icon' => false,
                        'url' => route('admin.users', 'depositors'),
                        'name' => 'GIC Investors',
                        'icon' => asset('assets/dashboard/icons/dash.svg'),
                        'submenu' => null,
                        'url_name' => 'yie-admin/users/depositors',
                    ],
                    [
                        'permission' => 'admin/banks/page-access',
                        'show_icon' => false,
                        'url' => route('admin.users', 'banks'),
                        'name' => 'Banks',
                        'icon' => asset('assets/dashboard/icons/dash.svg'),
                        'submenu' => null,
                        'url_name' => 'yie-admin/users/banks',
                    ],
                    [
                        'permission' => null,
                        'show_icon' => false,
                        'url' => route('repsond-to-requests'),
                        'name' => 'GIC/Repo Access Requests',
                        'icon' => asset('assets/dashboard/icons/pending-deposit.svg'),
                        'submenu' => null,
                        'url_name' => ['yie-admin/repsond-to-requests'],
                    ],
                    [
                        'permission' => 'admin/non-partnered-fi/page-access',
                        'show_icon' => false,
                        'url' => route('admin.users', 'non_partnered_fi'),
                        'name' => 'Non Partnered FI',
                        'icon' => asset('assets/dashboard/icons/dash.svg'),
                        'submenu' => null,
                        'url_name' => 'yie-admin/users/non_partnered_fi',
                    ],
                    [
                        'permission' => 'admin/manage-admins/page-access',
                        'show_icon' => false,
                        'url' => route('admin.users', 'admins'),
                        'name' => 'Manage Admins',
                        'icon' => asset('assets/dashboard/icons/dash.svg'),
                        'submenu' => null,
                        'url_name' => 'yie-admin/users/admins',
                    ],
                    [
                        'permission' => 'admin/manage-admins/page-access',
                        'show_icon' => false,
                        'url' => route('admin.users', 'waiting_list'),
                        'name' => 'Users Waiting List',
                        'icon' => asset('assets/dashboard/icons/dash.svg'),
                        'submenu' => null,
                        'url_name' => 'yie-admin/users/waiting-list',
                    ],
                ),
                'url_name' => ['yie-admin/users*', 'yie-admin/repsond-to-requests'],
            ],
            [
                'permission' => null,
                'show_icon' => true,
                'url' => route('product.list'),
                'name' => 'Products',
                'icon' => asset('assets/dashboard/icons/pending-deposit.svg'),
                'submenu' => null,
                'url_name' => ['yie-admin/product-list', 'yie-admin/product-list'],
            ],
            [
                'permission' => null,
                'show_icon' => true,
                'url' => route('product.repo.list'),
                'name' => 'Repo Products',
                'icon' => asset('assets/dashboard/icons/pending-deposit.svg'),
                'submenu' => null,
                'url_name' => ['yie-admin/-repo-product-list', 'yie-admin/repo-product-list'],
            ],
            [
                'permission' => null,
                'show_icon' => true,
                'url' => route('create-collateral'),
                'name' => 'Collaterals',
                'icon' => asset('assets/dashboard/icons/pending-deposit.svg'),
                'submenu' => null,
                'url_name' => ['yie-admin/create-collateral', 'yie-admin/create-collateral'],
            ],
            [
                'permission' => null,
                'show_icon' => true,
                'url' => route('basket.type'),
                'name' => 'Basket Types',
                'icon' => asset('assets/dashboard/icons/pending-deposit.svg'),
                'submenu' => null,
                'url_name' => ['yie-admin/create-basket-types'],
            ],
            [
                'permission' => null,
                'show_icon' => true,
                'url' => route('create-day-count-conventions'),
                'name' => 'Day Count Conventions',
                'icon' => asset('assets/dashboard/icons/pending-deposit.svg'),
                'submenu' => null,
                'url_name' => ['yie-admin/create-day-count-conventions'],
            ],
            [
                'permission' => 'admin/activity-logs/page-access',
                'show_icon' => true,
                'url' => route('admin.system.activity.logs'),
                'name' => 'Activity Logs',
                'icon' => asset('assets/dashboard/icons/pending-deposit.svg'),
                'submenu' => null,
                'url_name' => ['yie-admin/system/activity-logs', 'yei-admin/login/activity-logs/data'],
            ],
            [
                'permission' => 'admin/roles/page-access',
                'show_icon' => true,
                'url' => route('admin.roles.index'),
                'name' => 'Users Roles',
                'icon' => asset('assets/dashboard/icons/in-progress.svg'),
                'submenu' => null,
                'url_name' => ['yie-admin/roles', 'yie-admin/roles/index', 'yie-admin/roles-permissions/*'],
            ],
            [
                'permission' => 'admin/system-settings/page-access',
                'show_icon' => true,
                'url' => route('admin.system.settings'),
                'name' => 'System Settings',
                'icon' => asset('assets/dashboard/icons/history.svg'),
                'submenu' => null,
                'url_name' => 'yie-admin/system/settings',
            ],
            [
                'permission' => 'admin/industries/page-access',
                'show_icon' => true,
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
                'show_icon' => false,
                'permission' => null,
                'url' => url('dashboard'),
                'name' => 'Terms and conditions',
                'icon' => asset('assets/dashboard/icons/dash.svg'),
                'submenu' => null,
                'url_name' => 'first-login/confirm-organization-seats',
            ]);
        } else {
            $array = array([
                'show_icon' => false,
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
