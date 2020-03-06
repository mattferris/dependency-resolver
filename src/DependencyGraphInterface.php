<?php

/**
 * DependencyResolver - A dependency resolver library for PHP
 *
 * DependencyGraphInterface.php
 * @copyright Copyright (c) 2020 Matt Ferris
 * @author Matt Ferris <matt@bueller.ca>
 *
 * Licensed under BSD 2-clause license
 * github.com/mattferris/dependency-resolver/blob/master/license.txt
 */

namespace MattFerris\DependencyResolver;

interface DependencyGraphInterface
{
    public function addDependency($object, array $dependencies);
    public function resolve();
}
