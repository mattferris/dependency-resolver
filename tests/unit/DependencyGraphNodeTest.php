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

    /**
     * @depends testDependsOn
     */
    public function testSatisfy() {
        $fooNode = new DependencyGraphNode('foo');
        $barNode = new DependencyGraphNode('bar');
        $fooNode->dependsOn($barNode);
        $fooNode->satisfy($barNode);

        $this->assertTrue($fooNode->isSatisfied());
    }

    /**
     * @depends testSatisfy
     */
    public function testActivate() {
        $fooNode = new DependencyGraphNode('foo');
        $barNode = new DependencyGraphNode('bar');
        $fooNode->dependsOn($barNode);
        $barNode->activate();

        $this->assertTrue($fooNode->isSatisfied());
    }
}

