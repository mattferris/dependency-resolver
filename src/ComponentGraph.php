<?php

namespace MattFerris\DependencyResolver;

class ComponentGraph
{
    protected $nodes = [];

    public function __construct(array $nodes = []) {
        $this->nodes = $nodes;
    }

    public function addDependency($component, array $dependencies) {
        $compNode = null;
        if (!isset($this->nodes[$component])) {
            $compNode = new ComponentGraphNode($component);
            $this->nodes[$component] = $compNode;
        } else {
            $compNode = $this->nodes[$component];
        }

        foreach ($dependencies as $dep) {
            $depNode = null;
            if (!isset($this->nodes[$dep])) {
                $depNode = new ComponentGraphNode($dep);
                $this->nodes[$dep] = $depNode;
            } else {
                $depNode = $this->nodes[$dep];
            }
            $compNode->dependsOn($depNode);
        }

        return $this;
    }

    protected function prepare(ComponentGraphNode $node, &$stack, &$next) {
        $stack[$node->getComponent()] = true;
        $dependents = $node->getDependents();
        foreach ($dependents as $comp => $dep) {
            if (!isset($next[$comp]) && !isset($stack[$comp])) {
                $next[$comp] = $dep;
            }
        }
    }

    public function resolve()
    {
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

