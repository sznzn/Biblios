<?php
// 在php中， callable 类型表示可以被调用或执行的东西，它是一个可以引用多种可调用函数或方法的类型。
//具体示例
// 1.普通函数
function simpleFunction() {
    return "Hello, World!";
}

// 2.匿名函数/闭包
$anonymousFunction = function() {
    return "Hello, World!";
}

// 3.类方法
class MyClass {
    public function myMethod() {
        return "Hello, World!";
    }

    public static function myStaticMethod() {
        return "Hello, World!";
    }
}

// callalbe 的使用示例
function processCallable(callable $callable) {
    return $callable();
}

// 使用普通函数
processCallable('simpleFunction');

// 使用匿名函数
processCallable($ananymousFunction);

// 使用类方法
$obj = new MyClass();
processCallable([$obj, 'myMethod']);

// 使用静态方法
processCallable(['MyClass', 'myStaticMethod']);

// 使用箭头函数
processCallable(fn() => "Hello, World!");
//

//实际例子




