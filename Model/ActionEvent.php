<?php

namespace Intaro\RuleEngineBundle\Model;

class ActionEvent implements ActionEventInterface
{
    private $name;
    private $objects;
    private $delay;

    public function __construct($name, array $objects = array(), $delay = 0)
    {
        $this->name = $name;
        $this->objects = $objects;
        $this->delay = $delay;
    }

    /**
     * @access public
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @access public
     * @return void
     */
    public function getObjects()
    {
        return $this->objects;
    }

    /**
     * @access public
     * @return void
     */
    public function getDelay()
    {
        return $this->delay;
    }
}