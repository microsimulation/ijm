<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class SiteHeaderNavBar implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $classesInner;
    private $classesOuter;
    private $linkedItems = [];

    private function __construct(array $linkedItems, string $type)
    {
        Assertion::allIsInstanceOf($linkedItems, NavLinkedItem::class);
        Assertion::notEmpty($linkedItems);

        $linkedItems = array_values($linkedItems);

        for ($i = 0; $i < count($linkedItems); ++$i) {
            $classes = ['nav-'.$type.'__item'];

            if (0 === $i) {
                $classes[] = $classes[0].'--first';
            }
            if ((count($linkedItems) - 1) === $i) {
                $classes[] = $classes[0].'--last';
            }

            if ('search' === $linkedItems[$i]['rel']) {
                $classes[] = $classes[0].'--search';
            }

            if (isset($linkedItems[$i]['button'])) {
                $classes[] = 'nav-secondary__item--hide-narrow';
            }

            if (isset($linkedItems[$i]['loginControl']) && (bool) $linkedItems[$i]['loginControl']['isLoggedIn']) {
                $classes[] = 'nav-secondary__item--logged-in';
            }

            $newLinkedItem = FlexibleViewModel::fromViewModel($linkedItems[$i])
                ->withProperty('classes', implode(' ', $classes));

            if (!empty($linkedItems[$i]['picture'])) {
                $textClasses = $newLinkedItem['textClasses'];

                $newLinkedItem = $newLinkedItem
                    ->withProperty('textClasses', trim($textClasses.' nav-'.$type.'__menu_text'));
            }

            $this->linkedItems[] = $newLinkedItem;
        }

        $this->classesOuter = 'nav-'.$type;
        $this->classesInner = 'nav-'.$type.'__list clearfix';
    }

    public static function primary(array $linkedItems) : SiteHeaderNavBar
    {
        return new static($linkedItems, 'primary');
    }

    public static function secondary(array $linkedItems) : SiteHeaderNavBar
    {
        return new static($linkedItems, 'secondary');
    }

    public function getTemplateName() : string
    {
        return 'patterns/site-header-nav-bar.mustache';
    }
}
