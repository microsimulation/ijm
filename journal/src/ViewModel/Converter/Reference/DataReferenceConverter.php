<?php

namespace Microsimulation\Journal\ViewModel\Converter\Reference;

use eLife\ApiSdk\Model\Reference\DataReference;
use Microsimulation\Journal\ViewModel\Converter\ViewModelConverter;
use Microsimulation\Journal\Patterns\ViewModel;

final class DataReferenceConverter implements ViewModelConverter
{
    use HasAuthors;

    /**
     * @param DataReference $object
     */
    public function convert($object, string $viewModel = null, array $context = []) : ViewModel
    {
        $origin = [$object->getSource()];
        if ($object->getAssigningAuthority()) {
            $origin[] = $object->getAssigningAuthority()->toString();
        }
        if ($object->getDataId()) {
            $origin[] = 'ID '.$object->getDataId();
        }

        $authors = [];
        $year = true;

        // hack for missing date
        $authorsSuffix = $this->createAuthorsSuffix($object);
        if (empty($authorsSuffix)) {
            $yearSuffix = '';
            $year = false;
        } else {
            $yearSuffix = $authorsSuffix[0];
        }

        if ($object->getCurators()) {
            $authors[] = $this->createAuthors($object->getCurators(), $object->curatorsEtAl(), ['curators', $yearSuffix]);
            $year = false;
        }
        if ($object->getCompilers()) {
            array_unshift($authors, $this->createAuthors($object->getCompilers(), $object->compilersEtAl(), ['compilers', $year ? $object->getDate()->format().$object->getDiscriminator() : '']));
            $year = false;
        }
        if ($object->getAuthors()) {
            $referenceAuthors = $this->pruneAuthors($object->getAuthors());

            array_unshift($authors, $this->createAuthors($referenceAuthors, $object->authorsEtAl(), ['authors', $year ? $object->getDate()->format().$object->getDiscriminator() : '']));
        }

        if ($object->getDoi()) {
            return ViewModel\Reference::withDoi($object->getTitle(), new ViewModel\Doi($object->getDoi()), $origin, $authors);
        }

        return ViewModel\Reference::withOutDoi(new ViewModel\Link($object->getTitle(), $object->getUri()), $origin, $authors);
    }

    public function supports($object, string $viewModel = null, array $context = []) : bool
    {
        return $object instanceof DataReference;
    }
}
