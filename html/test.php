<?php
    ini_set("display_errors",1);
    $a = array("a"=>1,"b"=>include("test2.php"));
    var_dump($a);
/*
interface B
{
    public function f ();
}
interface C extends B
{
    public function f2 ();
}
interface D extends B
{
    public function f3 ();
}
class A implements C,D
{
    public function f ()
    {
        return "1";
    }
    public function f2 ()
    {
        return "2";
    }
    public function f3 ()
    {
        return "3";
    }
}
    $a = new A;
    print $a->f();
    print $a->f2();
    print $a->f3();
*/
/*
    $a = new A();
    var_dump($a->b);
    var_dump($a->b());

class A
{
    public function __get ($name)
    {
        return new B();
    }
    public function __call ($name, $args)
    {
        return call_user_func_array($this->$name, $args);
    }
}
class B
{
    public function __invoke ()
    {
        return "OK";
    }
}
*/