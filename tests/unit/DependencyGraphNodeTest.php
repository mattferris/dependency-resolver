<?php

use PHPUnit\Framework\TestCase;
use MattFerris\DependencyResolver\DependencyGraphNode;

class DependencyGraphNodeTest extends TestCase
{
    public function testConstruct() {
        $node = new DependencyGraphNode('foo');
        $this->assertEquals('foo', $node->getObject());
        $this->assertCount(0, $node->getDependencies());
    }

    /**
     * @depends testConstruct
     */
    public function testConstructWithDepends() {
        $barNode = new DependencyGraphNode('bar');
        $fooNode = new DependencyGraphNode('foo', [$barNode]);
        $this->assertEquals(['bar' => $barNode], $fooNode->getDependencies());
    }

    /**
     * @depends testConstruct
     */
    public function testDependsOn() {
        $fooNode = new DependencyGraphNode('foo');
        $barNode = new DependencyGraphNode('bar');
        $fooNode->dependsOn($barNode);

        $this->assertEquals(['bar' => $barNode], $fooNode->getDependencies());
        $this->assertEquals(['foo' => $fooNode], $barNode->getDependents());
    }
}

