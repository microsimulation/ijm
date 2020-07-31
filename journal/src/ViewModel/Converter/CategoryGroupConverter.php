<?php

namespace Microsimulation\Journal\ViewModel\Converter;

use eLife\ApiSdk\Model\Collection;
use Microsimulation\Journal\Patterns\ViewModel;
use Microsimulation\Journal\Helper\ModelName;
use Microsimulation\Journal\Patterns\ViewModel\ListHeading;
use Microsimulation\Journal\Patterns\ViewModel\Teaser;
use Microsimulation\Journal\Patterns\ViewModel\TeaserFooter;
use Microsimulation\Journal\Patterns\ViewModel\Link;
use Microsimulation\Journal\Patterns\ViewModel\Meta;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CategoryGroupConverter implements ViewModelConverter
{
    use CreatesContextLabel;
    use CreatesDate;

    // private $viewModelConverter;
    // private $urlGenerator;

    // public function __construct(
    //     ViewModelConverter $viewModelConverter,
    //     UrlGeneratorInterface $urlGenerator
    // ) {
    //     $this->viewModelConverter = $viewModelConverter;
    //     $this->urlGenerator = $urlGenerator;
    // }

    public function convert($object, string $viewModel = null, array $context = []): ViewModel
    {
        $items = [];
        $groupName;
        $startYear;
        /** @var Collection $collection */
        foreach($object->toArray() as $collection) {
            $year = intval($collection->getPublishedDate()->format('Y'));

            $teaser = Teaser::secondary(
                $collection->getTitle(),
                // $this->urlGenerator->generate('collection', [$object]),
                null,
                null,
                $this->createContextLabel($collection),
                null,
                TeaserFooter::forNonArticle(
                    // Meta::withLink(new Link(ModelName::singular('collection'), $this->urlGenerator->generate('collections')), $this->simpleDate($object, $context))
                    Meta::withLink(new Link(ModelName::singular('collection'), null), $this->simpleDate($collection, $context))
                )
            );

            if (!isset($groupName) && !isset($startYear)) {
                $startYear = $year;
                $endYear = $year - 2;
                $groupName = "{$startYear}-{$endYear}";
            }

            if ($year > ($startYear - 3)) {
                if (!isset($items[$groupName])) {
                    $items[$groupName] = [
                        "groupName" => $groupName,
                        "teasers" => []
                    ];
                }

                $items[$groupName]["teasers"][] = $teaser;
            } else {
                $startYear = $year;
                $endYear = $year - 2;
                $groupName = "{$startYear}-{$endYear}";

                $items[$groupName] = [
                    "groupName" => $groupName,
                    "teasers" => []
                ];
                $items[$groupName]["teasers"][] = $teaser;
            }
        }

        $teasers = [];

        foreach($items as $item) {
            $teaser = (object)[];

            $teaser->groupName = $item["groupName"];
            $teaser->teasers = $item["teasers"];
            $teasers[] = $teaser;
        }

        return $viewModel::basic(
            $teasers
            , new ListHeading($context["heading"])
        );
    }

    public function supports($object, string $viewModel = null, array $context = []): bool
    {
        return ViewModel\CategoryGroup::class === $viewModel;
    }

    protected function getViewModelConverter() : ViewModelConverter
    {
        return $this->viewModelConverter;
    }
}
