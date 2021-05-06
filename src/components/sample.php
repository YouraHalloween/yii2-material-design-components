<?php

class AnyClassParent
{
    public function AnyFunc1(): static
    {
        //...some actions
        return $this;
    }
}

class AnyClassChild extends AnyClassParent
{
    public function AnyFunc2(): AnyClassChild
    {
        //...some actions
        return $this;
    }
}

$component = new AnyClassChild();
$component
    ->AnyFunc1(/* params */)
    ->AnyFunc1(/* params */)
    ->AnyFunc1(/* params */)   
    ->AnyFunc2() 
    ->AnyFunc2(/* params */);
