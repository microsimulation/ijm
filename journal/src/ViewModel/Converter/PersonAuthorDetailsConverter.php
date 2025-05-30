<?php

namespace Microsimulation\Journal\ViewModel\Converter;

use eLife\ApiSdk\Collection\EmptySequence;
use eLife\ApiSdk\Model\PersonAuthor;
use Microsimulation\Journal\Patterns\PatternRenderer;
use Microsimulation\Journal\Patterns\ViewModel;
use Microsimulation\Journal\Patterns\ViewModel\AuthorDetails;

final class PersonAuthorDetailsConverter implements ViewModelConverter
{
    use AuthorDetailsConverter;
    use CreatesId;

    private $viewModelConverter;
    private $patternRenderer;

    public function __construct(ViewModelConverter $viewModelConverter, PatternRenderer $patternRenderer)
    {
        $this->viewModelConverter = $viewModelConverter;
        $this->patternRenderer = $patternRenderer;
    }

    /**
     * @param PersonAuthor $object
     */
    public function convert($object, string $viewModel = null, array $context = []) : ViewModel
    {
        $name = $object->toString();

        if ($object->getRole()) {
            $name .= ", {$object->getRole()}";
        }

        if ($object->isDeceased()) {
            $name .= ' (deceased)';
        }

        return AuthorDetails::forPerson(
            $this->createId($object),
            $name,
            $this->findDetails($object, $context['authors'] ?? new EmptySequence()),
            $object->getOrcid() ? new ViewModel\Orcid($object->getOrcid()) : null
        );
    }

    public function supports($object, string $viewModel = null, array $context = []) : bool
    {
        return $object instanceof PersonAuthor;
    }

    protected function getPatternRenderer() : PatternRenderer
    {
        return $this->patternRenderer;
    }

    protected function getViewModelConverter() : ViewModelConverter
    {
        return $this->viewModelConverter;
    }
}
