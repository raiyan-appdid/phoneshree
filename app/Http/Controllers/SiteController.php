<?php

namespace App\Http\Controllers;

class SiteController extends Controller
{
    //Home
    public function index()
    {
        // return 'Index page!';
        return redirect('admin');
    }
    //About
    public function about()
    {
        return 'About Page';
    }
    //Contact
    public function contact()
    {
        return 'Contact Page';
    }

}