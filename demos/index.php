<?php

use Go\Aop\Features;
use Go\Core\AspectKernel;
use Go\Core\AspectContainer;

use Go\Aop\Aspect;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\Before;

use Doctrine\Common\Annotations\Annotation;

/**
 * @var $load \Composer\Autoload\ClassLoader
 */
$load = include __DIR__ . '/../vendor/autoload.php';
// 使用其它的命名空间 Other
$load->add('Other\\', [__DIR__ ]);
/**
 * @Annotation
 * @Target("METHOD")
 */
class Loggable extends Annotation
{

}

/**
 * @see http://go.aopphp.com/docs/pointcut-reference/
 */
class LoggingAspect implements Aspect
{

    /**
     * @Before("@execution(Loggable)")
     */
    public function beforeMethodExecution(MethodInvocation $invocation)
    {
        echo '在对象之前调用: ',
        $invocation,
        ' 参数: ',
        json_encode($invocation->getArguments()),
        PHP_EOL;
    }
}

class AwesomeAspectKernel extends AspectKernel
{
    protected function configureAop(AspectContainer $container)
    {
        $container->registerAspect(new LoggingAspect());
    }
}

AwesomeAspectKernel::getInstance()->init([
    'debug' => true,
    'appDir' => __DIR__ . '/',
    'cacheDir' => __DIR__ . '/cache',
//    'features' => Features::INTERCEPT_FUNCTIONS,
    'includePaths' => [
        __DIR__
    ]
]);
//  aop-demo/vendor/composer/autoload_static.php 路径中，已经自动命名了 Demo 命名空间的路径


$example = new \Demo\LoggingDemo();
$example->execute('do something ok');


// 方法二：载入其它的文件，当然你也可以在 spl_autoload_register 中使用
//include_once \Go\Instrument\Transformer\FilterInjectorTransformer::rewrite(__DIR__ . '/Other/LoggingDemo.php');

$demo2 = new \Other\LoggingDemo();
$demo2->execute("do something no");