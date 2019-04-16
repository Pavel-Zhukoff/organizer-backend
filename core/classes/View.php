<?php
/**
 * Created by PhpStorm.
 * User: Pasha
 * Date: 13.12.2018
 * Time: 21:57
 */

namespace Core\Classes;


use Core\Utils\Config;

class View
{
    private $viewConfig;
    private $twigLoader;
    private $twigEnv;

    private $response;

    public $renderedView;

    function __construct()
    {
        $this->viewConfig = Config::load('config')['view'];
        $this->twigLoader = new \Twig_Loader_Filesystem($this->viewConfig['path']);
        $this->twigEnv = new \Twig_Environment($this->twigLoader, $this->viewConfig['twig']);

        $this->response = new Response();
    }

    public function render(string $view, array $data = array()) : void
    {
        $view .= '.twig';
        if (!file_exists($this->viewConfig['path'].$view))
            throw new \Exception("View {$view} не найден!");
        $this->renderedView =  $this->twigEnv->render($view, $data);
    }

    public function display() : void
    {
        $this->response->display($this->renderedView);
    }

    public function getView() : string
    {
        return isset($this->renderedView) ? $this->renderedView : NULL;
    }
}