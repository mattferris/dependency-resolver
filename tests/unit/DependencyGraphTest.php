<?php

use PHPUnit\Framework\TestCase;
use MattFerris\DependencyResolver\DependencyGraph;

class DependencyGraphTest extends TestCase
{
    public function testLinearResolve() {
        $graph = new DependencyGraph();
        $graph->addDependency('foo', ['bar']);
        $graph->addDependency('bar', ['baz']);

        $this->assertEquals(['baz', 'bar', 'foo'], $graph->resolve());
    }

    /**
     * @depends testLinearResolve
     */
    public function testInterBranchResolve() {
        $graph = new DependencyGraph();
        $graph->addDependency('b1.n3', ['b1.n2','b2.n3']);
        $graph->addDependency('b1.n2', ['b1.n1']);
        $graph->addDependency('b2.n4', ['b2.n3']);
        $graph->addDependency('b2.n3', ['b2.n2']);
        $graph->addDependency('b2.n2', ['b2.n1']);

        $result = ['b1.n1', 'b2.n1', 'b1.n2', 'b2.n2', 'b2.n3', 'b1.n3', 'b2.n4'];
        $this->assertEquals($result, $graph->resolve());
    }
}
