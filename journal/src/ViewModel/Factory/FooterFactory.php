<?php

namespace Microsimulation\Journal\ViewModel\Factory;

use Microsimulation\Journal\Patterns\ViewModel\Footer;
use Microsimulation\Journal\Patterns\ViewModel\Link;
use Microsimulation\Journal\Patterns\ViewModel\MainMenu;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class FooterFactory
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function createFooter() : Footer
    {
        return new Footer();
    }
}
