<?php

/**
 * DependencyResolver - A dependency resolver library for PHP
 *
 * DependencyGraphNode.php
 * @copyright Copyright (c) 2020 Matt Ferris
 * @author Matt Ferris <matt@bueller.ca>
 *
 * Licensed under BSD 2-clause license
 * github.com/mattferris/dependency-resolver/blob/master/license.txt
 */

namespace MattFerris\DependencyResolver;

class DependencyGraphNode
{
    protected $object;
    protected $dependsOn = [];
    protected $dependents = [];
    protected $satisfied = [];

    public function __construct($object, array $dependencies = []) {
        $this->object = $object;

        foreach ($dependencies as $dep) {
            $this->dependsOn($dep);
        }
    }

    public function dependsOn(DependencyGraphNode $node) {
        if (isset($this->dependsOn[$node->getObject()])) {
            return;
        }
        $this->dependsOn[$node->getObject()] = $node;
        $node->providesFor($this);
    }

    public function providesFor(DependencyGraphNode $node) {
        if (isset($this->dependent[$node->getObject()])) {
            return;
        }
        $this->dependents[$node->getObject()] = $node;
    }

    public function satisfy(DependencyGraphNode $node) {
        if (!isset($this->dependsOn[$node->getObject()])) {
            throw new DependencyNotDefinedException($this, $node);
        }
        if (!isset($this->satisfied[$node->getObject()])) {
            $this->satisfied[$node->getObject()] = true;
        }
    }

    public function isSatisfied() {
        foreach (array_keys($this->dependsOn) as $dep) {
            if (!isset($this->satisfied[$dep])) {
                return false;
            }
        }
        return true;
    }

    public function activate() {
        foreach ($this->dependents as $dep) {
            $dep->satisfy($this);
        }
    }

    public function getObject() {
        return $this->object;
    }

    public function getDependencies() {
        return $this->dependsOn;
    }

    public function getDependents() {
        return $this->dependents;
    }
}

