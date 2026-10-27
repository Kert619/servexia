<?php

namespace App\Enums;

enum Page: string
{
    case AuthLoginPage = 'Auth/LoginPage';
    case AuthRegisterPage = 'Auth/RegisterPage';
    case CompanyPage = 'CompanyPage';
    case HomePage = 'HomePage';
    case AdminDashboard = 'Admin/DashboardPage';
    case AdminServices = 'Admin/ServicePage';
    case AdminCategories = 'Admin/CategoryPage';
}
