<?php

class Foo {
    public function add(): self
    {
        var_dump(get_class($this));
        return $this;
    }
}

class FooChild extends Foo {
    public function remove(): FooChild
    {
        return $this;
    }
}

$v = new FooChild();
$v->add()->remove();