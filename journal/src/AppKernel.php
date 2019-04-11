<?php

namespace Microsimulation\Journal;

use Bobthecow\Bundle\MustacheBundle\BobthecowMustacheBundle;
use Cocur\Slugify\Bridge\Symfony\CocurSlugifyBundle;
use Csa\Bundle\GuzzleBundle\CsaGuzzleBundle;
use Symfony\Bundle\DebugBundle\DebugBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Kernel;
use WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new AppBundle(),
            new BobthecowMustacheBundle(),
            new CocurSlugifyBundle(),
            new CsaGuzzleBundle(),
            new FrameworkBundle(),
            new MonologBundle(),
            new TwigBundle(),
            new WhiteOctoberPagerfantaBundle(),
        ];

        if ('dev' === $this->getEnvironment()) {
            $bundles[] = new DebugBundle();
            $bundles[] = new WebProfilerBundle();
        }

        return $bundles;
    }

    public function getName()
    {
        return 'journal';
    }

    public function getRootDir()
    {
        return $this->getProjectDir().'/app';
    }

    public function getProjectDir()
    {
        return __DIR__.'/..';
    }

    public function getCacheDir()
    {
        return $this->getProjectDir().'/var/cache';
    }

    public function getLogDir()
    {
        return $this->getProjectDir().'/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getProjectDir().'/app/config/config_'.$this->getEnvironment().'.yml');
    }

    public function run(Request $request)
    {
        $response = $this->handle($request);
        $response->send();
        $this->terminate($request, $response);
    }
}
