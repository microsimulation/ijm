<?php

namespace Microsimulation\Journal\ViewModel\Converter;

use eLife\ApiSdk\Model\Collection;
use eLife\ApiSdk\Model\Subject;
use Microsimulation\Journal\Helper\ModelName;
use Microsimulation\Journal\Patterns\PatternRenderer;
use Microsimulation\Journal\Patterns\ViewModel;
use Microsimulation\Journal\Patterns\ViewModel\Paragraph;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class CollectionReadMoreItemConverter implements ViewModelConverter
{
    use CreatesDate;

    private $patternRenderer;
    private $urlGenerator;

    public function __construct(PatternRenderer $patternRenderer, UrlGeneratorInterface $urlGenerator)
    {
        $this->patternRenderer = $patternRenderer;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param Collection $object
     */
    public function convert($object, string $viewModel = null, array $context = []) : ViewModel
    {
        return new ViewModel\ReadMoreItem(
            new ViewModel\ContentHeaderReadMore(
                $object->getTitle(),
                $this->urlGenerator->generate('collection', [$object]),
                $object->getSubjects()->map(function (Subject $subject) {
                    return new ViewModel\Link($subject->getName());
                })->toArray(),
                null,
                ViewModel\Meta::withLink(
                    new ViewModel\Link(ModelName::singular('collection'), $this->urlGenerator->generate('collections')),
                    $this->simpleDate($object, $context)
                )
            ),
            $object->getImpactStatement() ? $this->patternRenderer->render(new Paragraph($object->getImpactStatement())) : null,
            $context['isRelated'] ?? false
        );
    }

    public function supports($object, string $viewModel = null, array $context = []) : bool
    {
        return $object instanceof Collection && ViewModel\ReadMoreItem::class === $viewModel;
    }
}
