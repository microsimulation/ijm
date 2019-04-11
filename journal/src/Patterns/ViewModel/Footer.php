<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class Footer implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $year;
    private $mainMenu;
    private $listHeading;
    private $links;
    private $button;
    private $footerMenuLinks;
    private $logos;

    public function __construct(
        MainMenu $mainMenu = null,
        array $footerMenuLinks = null,
        InvestorLogos $investorLogos = null
    ) {
        $this->year = (int) date('Y');
        if ($mainMenu) {
            $this->mainMenu = true;
            $this->listHeading = $mainMenu['listHeading'];
            $this->links = $mainMenu['links'];
            $this->button = $mainMenu['button'];
        }
        if ($footerMenuLinks) {
            Assertion::notEmpty($footerMenuLinks);
            Assertion::allIsInstanceOf($footerMenuLinks, Link::class);
            $this->footerMenuLinks = $footerMenuLinks;
        }
        if ($investorLogos) {
            $this->logos = $investorLogos['logos'];
        }
    }

    public function getTemplateName() : string
    {
        return 'patterns/footer.mustache';
    }
}
