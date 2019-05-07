<?php

namespace Microsimulation\Journal\ViewModel\Converter\Reference;

use eLife\ApiSdk\Model\Reference\WebReference;
use Microsimulation\Journal\ViewModel\Converter\ViewModelConverter;
use Microsimulation\Journal\Patterns\ViewModel;

final class WebReferenceConverter implements ViewModelConverter
{
    use HasAuthors;

    /**
     * @param WebReference $object
     */
    public function convert($object, string $viewModel = null, array $context = []) : ViewModel
    {
        $origin = [];
        if ($object->getWebsite()) {
            $origin[] = $object->getWebsite();
        }
        if ($object->getAccessed()) {
            $origin[] = 'Accessed '.$object->getAccessed()->format();
        }

        // hack for missing date
        $authorsSuffix = $this->createAuthorsSuffix($object);

        $referenceAuthors = $this->pruneAuthors($object->getAuthors());

        $authors = [$this->createAuthors($referenceAuthors, $object->authorsEtAl(), $authorsSuffix)];

        return ViewModel\Reference::withOutDoi(new ViewModel\Link($object->getTitle(), $object->getUri()), $origin, $authors);
    }

    public function supports($object, string $viewModel = null, array $context = []) : bool
    {
        return $object instanceof WebReference;
    }
}
