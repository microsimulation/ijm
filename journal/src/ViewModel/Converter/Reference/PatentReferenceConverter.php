<?php

namespace Microsimulation\Journal\ViewModel\Converter\Reference;

use eLife\ApiSdk\Model\Reference\PatentReference;
use Microsimulation\Journal\ViewModel\Converter\ViewModelConverter;
use Microsimulation\Journal\Patterns\ViewModel;

final class PatentReferenceConverter implements ViewModelConverter
{
    use HasAuthors;

    /**
     * @param PatentReference $object
     */
    public function convert($object, string $viewModel = null, array $context = []) : ViewModel
    {
        $title = $object->getTitle();
        if ($object->getNumber()) {
            $title .= ' ('.$object->getNumber().')';
        }

        $origin = [];
        if ($object->getAssignees()) {
            $origin[] = $this->createAuthorsString($object->getAssignees(), $object->assigneesEtAl());
        }
        $origin[] = $object->getPatentType();
        $origin[] = $object->getCountry();

        // hack for missing date
        $authorsSuffix = $this->createAuthorsSuffix($object);

        $authors = [$this->createAuthors($object->getInventors(), $object->inventorsEtAl(), $authorsSuffix)];

        return ViewModel\Reference::withOutDoi(new ViewModel\Link($title, $object->getUri()), $origin, $authors);
    }

    public function supports($object, string $viewModel = null, array $context = []) : bool
    {
        return $object instanceof PatentReference;
    }
}
