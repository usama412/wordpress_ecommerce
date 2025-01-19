<?php

namespace Vehica\Model\Post\Field;


/**
 * Interface RewritableField
 * @package Vehica\Model\Post\Field
 */
interface RewritableField
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getKey();

    /**
     * @return string
     */
    public function getRewrite();

    /**
     * @param string $rewrite
     */
    public function setRewrite($rewrite);

}