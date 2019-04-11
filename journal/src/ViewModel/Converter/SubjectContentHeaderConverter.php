<?php

namespace Microsimulation\Journal\ViewModel\Converter;

use eLife\ApiSdk\Model\Subject;
use Microsimulation\Journal\Patterns\ViewModel;

final class SubjectContentHeaderConverter implements ViewModelConverter
{
    /**
     * @param Subject $object
     */
    public function convert($object, string $viewModel = null, array $context = []) : ViewModel
    {
        return new ViewModel\ContentHeader(
            $object->getName(),
            null,
            $object->getImpactStatement()
        );
    }

    public function supports($object, string $viewModel = null, array $context = []) : bool
    {
        return $object instanceof Subject && ViewModel\ContentHeader::class === $viewModel;
    }
}
