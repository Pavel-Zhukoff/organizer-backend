<?php
/**
 * Created by PhpStorm.
 * User: Pasha
 * Date: 12.12.2018
 * Time: 19:10
 */

namespace App\Controller;


use core\classes\Controller;

class Error extends Controller
{
    public function index()
    {
        
    }

    public function pageNotFound()
    {
        echo 404;
    }
}