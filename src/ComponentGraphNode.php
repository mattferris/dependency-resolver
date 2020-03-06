<?php

namespace MattFerris\DependencyResolver;

class ComponentGraphNode
{
    protected $component;
    protected $dependsOn = [];
    protected $dependents = [];

    public function __construct($component, array $dependsOn = null) {
        $this->component = $component;
        $this->dependsOn = $dependsOn;
    }

    public function dependsOn(ComponentGraphNode $node) {
        if (isset($this->dependsOn[$node->getComponent()])) {
            throw new DuplicateDependencyException($this, $node);
        }
        $this->dependsOn[$node->getComponent()] = $node;
        $node->providesFor($this);
    }

    public function providesFor(ComponentGraphNode $node) {
        if (isset($this->dependent[$node->getComponent()])) {
            throw new DuplicateDependentException($this, $node);
        }
        $this->dependents[$node->getComponent()] = $node;
    }

    public function getComponent() {
        return $this->component;
    }

    public function getDependencies() {
        return $this->dependsOn;
    }

    public function getDependents() {
        return $this->dependents;
    }
}

