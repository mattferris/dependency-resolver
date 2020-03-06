<?php

/**
 * DependencyResolver - A dependency resolver library for PHP
 *
 * DependencyGraph.php
 * @copyright Copyright (c) 2020 Matt Ferris
 * @author Matt Ferris <matt@bueller.ca>
 *
 * Licensed under BSD 2-clause license
 * github.com/mattferris/dependency-resolver/blob/master/license.txt
 */

namespace MattFerris\DependencyResolver;

class DependencyGraph implements DependencyGraphInterface
{
    protected $nodes = [];

    public function __construct(array $nodes = []) {
        $this->nodes = $nodes;
    }

    public function addDependency($object, array $dependencies) {
        $objectNode = null;
        if (!isset($this->nodes[$object])) {
            $objectNode = new DependencyGraphNode($object);
            $this->nodes[$object] = $objectNode;
        } else {
            $objectNode = $this->nodes[$object];
        }

        foreach ($dependencies as $dep) {
            $depNode = null;
            if (!isset($this->nodes[$dep])) {
                $depNode = new DependencyGraphNode($dep);
                $this->nodes[$dep] = $depNode;
            } else {
                $depNode = $this->nodes[$dep];
            }
            $objectNode->dependsOn($depNode);
        }

        return $this;
    }

    protected function prepare(DependencyGraphNode $node, &$stack, &$next) {
        $stack[$node->getObject()] = true;
        $dependents = $node->getDependents();
        foreach ($dependents as $obj => $dep) {
            if (!isset($next[$obj]) && !isset($stack[$obj])) {
                $next[$obj] = $dep;
            }
        }
    }

    public function resolve() {
        $stack = [];
        $next = [];

        // initialize stack with root nodes
        foreach ($this->nodes as $node) {
            if (count((array)$node->getDependencies()) > 0) {
                continue;
            }
            $this->prepare($node, $stack, $next);
        }

        while (($node = array_shift($next)) !== null) {
            $this->prepare($node, $stack, $next);
        }

        return array_keys($stack);
    }
}

