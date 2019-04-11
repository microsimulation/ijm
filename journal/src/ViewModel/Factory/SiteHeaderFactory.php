<?php

namespace Microsimulation\Journal\ViewModel\Factory;

use eLife\ApiSdk\Model\HasSubjects;
use eLife\ApiSdk\Model\Model;
use eLife\ApiSdk\Model\Profile;
use eLife\ApiSdk\Model\Subject;
use Microsimulation\Journal\Patterns\ViewModel\Button;
use Microsimulation\Journal\Patterns\ViewModel\CompactForm;
use Microsimulation\Journal\Patterns\ViewModel\Form;
use Microsimulation\Journal\Patterns\ViewModel\Image;
use Microsimulation\Journal\Patterns\ViewModel\Input;
use Microsimulation\Journal\Patterns\ViewModel\Link;
use Microsimulation\Journal\Patterns\ViewModel\LoginControl;
use Microsimulation\Journal\Patterns\ViewModel\NavLinkedItem;
use Microsimulation\Journal\Patterns\ViewModel\Picture;
use Microsimulation\Journal\Patterns\ViewModel\SearchBox;
use Microsimulation\Journal\Patterns\ViewModel\SiteHeader;
use Microsimulation\Journal\Patterns\ViewModel\SiteHeaderNavBar;
use Microsimulation\Journal\Patterns\ViewModel\SubjectFilter;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use function Microsimulation\Journal\Patterns\mixed_visibility_text;

final class SiteHeaderFactory
{
    private $urlGenerator;
    private $packages;
    private $requestStack;

    public function __construct(UrlGeneratorInterface $urlGenerator, Packages $packages, RequestStack $requestStack)
    {
        $this->urlGenerator = $urlGenerator;
        $this->packages = $packages;
        $this->requestStack = $requestStack;
    }

    public function createSiteHeader(Model $item = null, Profile $profile = null) : SiteHeader
    {
        if ($this->requestStack->getCurrentRequest() && 'search' !== $this->requestStack->getCurrentRequest()->get('_route')) {
            $searchItem = NavLinkedItem::asIcon(new Link('Search the eLife site', $this->urlGenerator->generate('search')),
                new Picture(
                    [
                        [
                            'srcset' => $this->packages->getUrl('assets/patterns/img/patterns/molecules/nav-primary-search-ic.svg'),
                            'type' => 'image/svg+xml',
                        ],
                    ],
                    new Image(
                        $this->packages->getUrl('assets/patterns/img/patterns/molecules/nav-primary-search-ic_1x.png'),
                        [
                            2 => $this->packages->getUrl('assets/patterns/img/patterns/molecules/nav-primary-search-ic_2x.png'),
                            1 => $this->packages->getUrl('assets/patterns/img/patterns/molecules/nav-primary-search-ic_1x.png'),
                        ],
                        ''
                    )
                ),
                false,
                true,
                'search'
            );
        } else {
            $searchItem = NavLinkedItem::asIcon(new Link('Search'),
                new Picture(
                    [
                        [
                            'srcset' => $this->packages->getUrl('assets/patterns/img/patterns/molecules/nav-primary-search-disabled-ic.svg'),
                            'type' => 'image/svg+xml',
                        ],
                    ],
                    new Image(
                        $this->packages->getUrl('assets/patterns/img/patterns/molecules/nav-primary-search-disabled-ic_1x.png'),
                        [
                            2 => $this->packages->getUrl('assets/patterns/img/patterns/molecules/nav-primary-search-disabled-ic_2x.png'),
                            1 => $this->packages->getUrl('assets/patterns/img/patterns/molecules/nav-primary-search-disabled-ic_1x.png'),
                        ],
                        'Search icon'
                    )
                ),
                false,
                true,
                'search'
            );
        }

        $primaryLinks = SiteHeaderNavBar::primary([
            NavLinkedItem::asLink(new Link('Home', $this->urlGenerator->generate('home'))),
            NavLinkedItem::asLink(new Link('Issues', $this->urlGenerator->generate('collections'))),
            $searchItem,
        ]);

        $secondaryLinks = [
            NavLinkedItem::asLink(new Link('About', $this->urlGenerator->generate('about'))),
        ];

        $secondaryLinks = SiteHeaderNavBar::secondary($secondaryLinks);

        if ($item instanceof HasSubjects) {
            $subject = $item->getSubjects()[0];
        } elseif ($item instanceof Subject) {
            $subject = $item;
        } else {
            $subject = null;
        }

        if ($this->requestStack->getCurrentRequest() && 'search' !== $this->requestStack->getCurrentRequest()->get('_route')) {
            $searchBox = new SearchBox(
                new CompactForm(
                    new Form($this->urlGenerator->generate('search'), 'search', 'GET'),
                    new Input('Search by keyword or author', 'search', 'for', null, 'Search by keyword or author'),
                    'Search'
                ),
                $subject ? new SubjectFilter('subjects[]', $subject->getId(), $subject->getName()) : null
            );
        } else {
            $searchBox = null;
        }

        return new SiteHeader($this->urlGenerator->generate('home'), $primaryLinks, $secondaryLinks, $searchBox);
    }
}
