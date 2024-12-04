<?php

namespace App\View\Navigation;

interface NavigationInterface{
    function setUserGroup($userGroup);

    function setView($view);

    function admin();

    function banks();

    function depositors();

    function auth();
}