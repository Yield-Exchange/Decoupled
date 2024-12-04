<?php

namespace App\View\Navigation;

class NavBarActionBar extends NavigationCommon {

    private $organizations_common_menu;

    function __construct($userGroup=null)
    {
        $this->setUserGroup($userGroup);
        $this->setView('dashboard/navbar-actionbar');
        $this->setCommonOrganizationMenu();
    }

    function banks(){
        return array_merge($this->organizations_common_menu, array());
    }

    function depositors(){
        return array_merge($this->organizations_common_menu, array());
    }

    function admin()
    {
        return array([
            'permission'=>null,
            'url'=> url('yie-admin/profile-setting'),
            'name'=>'Profile Settings',
            'icon'=>asset('assets/dashboard/icons/dash.svg'),
        ]);
    }

    private function setCommonOrganizationMenu()
    {
        $this->organizations_common_menu =  array(
            [
                'permission'=>'universal/organization-setting/page-access',
                'url'=>url('account-setting'),
                'name'=>'Organization Settings',
                'icon'=>asset('assets/dashboard/icons/dash.svg')
            ],
            [
                'permission'=>'universal/users/page-access',
                'url'=>route('users.index'),
                'name'=>'Organization Users',
                'icon'=>asset('assets/dashboard/icons/dash.svg')
            ],
            [
                'permission'=>null,
                'url'=> url('profile-setting'),
                'name'=>'Profile Settings',
                'icon'=>asset('assets/dashboard/icons/dash.svg'),
            ],
        );
    }
}