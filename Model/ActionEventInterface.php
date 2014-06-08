<?php

namespace Intaro\RuleEngineBundle\Model;

interface ActionEventInterface
{
    /**
     * @access public
     * @return string
     */
    public function getName();

    /**
     * @access public
     * @return void
     */
    public function getObjects();

    /**
     * @access public
     * @return void
     */
    public function getDelay();
}