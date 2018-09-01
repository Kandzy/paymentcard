<?php
/**
 * Created by PhpStorm.
 * User: dkliukin
 * Date: 9/1/18
 * Time: 13:34
 */

namespace App\Controllers;


use Interop\Container\ContainerInterface;

class Controller
{
    protected $container;

    public function __construct(ContainerInterface $c)
    {
        $this->container = $c;
    }

    public function __get($name)
    {
        if ($this->container->{$name}) {
            return $this->container->{$name};
        }
    }
}