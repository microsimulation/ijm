<?php

namespace Microsimulation\Journal\ViewModel\Converter;

use eLife\ApiSdk\Model\Subject;
use Microsimulation\Journal\Helper\CreatesIiifUri;
use Microsimulation\Journal\Patterns\ViewModel;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class SubjectBlockLinkConverter implements ViewModelConverter
{
    use CreatesIiifUri;

    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param Subject $object
     */
    public function convert($object, string $viewModel = null, array $context = []) : ViewModel
    {
        return new ViewModel\BlockLink(
            new ViewModel\Link(
                $object->getName(),
                $this->urlGenerator->generate('subject', [$object])
            )
        );
    }

    public function supports($object, string $viewModel = null, array $context = []) : bool
    {
        return $object instanceof Subject && ViewModel\BlockLink::class === $viewModel;
    }
}
