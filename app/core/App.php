<?php

class App
{
    protected $controller = "HomeController";
    protected $method = "index";
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseURL();

        if (isset($url[0])) {
            $this->controller = ucfirst($url[0]) . "Controller";
            unset($url[0]);
        }

        if (isset($url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        }

        $this->params = $url ? array_values($url) : [];

        require_once "../app/controllers/" . $this->controller . ".php";

        $this->controller = new $this->controller;

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseURL()
    {
        if (isset($_GET['url'])) {
            return explode("/", filter_var(rtrim($_GET['url'], "/"), FILTER_SANITIZE_URL));
        }
    }
}