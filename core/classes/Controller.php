<?php
/**
 * Created by PhpStorm.
 * User: Pasha
 * Date: 13.12.2018
 * Time: 13:20
 */

namespace Core\Classes;


abstract class Controller
{
    protected $view;

    public function __construct()
    {
        $this->view = new View();
    }

    public abstract function index();
}