<?php

use PHPUnit\Framework\TestCase;
use MattFerris\DependencyResolver\DependencyGraph;

class DependencyGraphTest extends TestCase
{
    public function testResolve() {
        $graph = new DependencyGraph();
        $graph->addDependency('foo', ['bar']);
        $graph->addDependency('bar', ['baz']);

        $this->assertEquals(['baz', 'bar', 'foo'], $graph->resolve());
    }
}
