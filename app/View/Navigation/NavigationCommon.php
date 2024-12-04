<?php

namespace App\View\Navigation;

use Illuminate\Support\Str;

class NavigationCommon implements NavigationInterface
{
    private $userGroup;
    private $view;
    private $user;

    function setUserGroup($userGroup){
        $this->user = auth()->user();

        if(!$userGroup){
            $this->knowUserType();
        }else{
            $this->userGroup = $userGroup;
        }
    }

    function setView($view){
        $this->view = $view;
    }

    function render(){
        echo $this->build()->render();
    }

    function build(){
        $menu=null;
        if(!in_array($this->userGroup,['admin','banks','depositors','auth'])){
            throw new \Exception("menu not found for user group ".$this->userGroup);
        }

        $menu = $this->{$this->userGroup}();
        $user = $this->user;
        return view($this->view,compact('menu','user'));
    }

    function admin()
    {
        // TODO: Implement admin() method.
    }

    function banks()
    {
        // TODO: Implement banks() method.
    }

    function depositors()
    {
        // TODO: Implement depositors() method.
    }

    private function knowUserType()
    {
        $this->userGroup = $this->user->is_super_admin ? 'admin' : (
            Str::lower($this->user->organization->type)."s"
        );
    }

    function auth()
    {
        // TODO: Implement auth() method.
    }
}