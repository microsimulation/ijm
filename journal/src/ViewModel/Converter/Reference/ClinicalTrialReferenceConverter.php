<?php

namespace Microsimulation\Journal\ViewModel\Converter\Reference;

use eLife\ApiSdk\Model\Reference\ClinicalTrialReference;
use Microsimulation\Journal\ViewModel\Converter\ViewModelConverter;
use Microsimulation\Journal\Patterns\ViewModel;

final class ClinicalTrialReferenceConverter implements ViewModelConverter
{
    use HasAuthors;

    /**
     * @param ClinicalTrialReference $object
     */
    public function convert($object, string $viewModel = null, array $context = []) : ViewModel
    {
        // hack for missing date
        $authorsSuffix = $this->createAuthorsSuffix($object);
        if(empty($authorsSuffix))
        {
            $yearSuffix = '';
        } else {
            $yearSuffix = $authorsSuffix[0];
        }

        if ($object->getDate()->getYear() > 1000) {
            $yearSuffix = $object->getDate()->format().$object->getDiscriminator();
        } else {
            $yearSuffix = '';
        }
        $referenceAuthors = $this->pruneAuthors($object->getAuthors());

        $authors = [$this->createAuthors($referenceAuthors, $object->authorsEtAl(), [$object->getAuthorsType(), $yearSuffix])];

        return ViewModel\Reference::withOutDoi(new ViewModel\Link($object->getTitle(), $object->getUri()), [], $authors);
    }

    public function supports($object, string $viewModel = null, array $context = []) : bool
    {
        return $object instanceof ClinicalTrialReference;
    }
}
