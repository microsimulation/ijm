<?php

namespace Microsimulation\Journal;

use Microsimulation\Journal\DependencyInjection\GuzzleMiddlewarePass;
use Microsimulation\Journal\DependencyInjection\HttpCachePass;
use Microsimulation\Journal\DependencyInjection\MonologStacktracesPass;
use Microsimulation\Journal\DependencyInjection\ViewModelConverterPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class AppBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new GuzzleMiddlewarePass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, -1);
        $container->addCompilerPass(new HttpCachePass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 33);
        $container->addCompilerPass(new MonologStacktracesPass());
        $container->addCompilerPass(new ViewModelConverterPass());
    }
}
