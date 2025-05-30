<?php

namespace Microsimulation\Journal\ViewModel\Converter\Reference;

use eLife\ApiSdk\Model\Reference\ThesisReference;
use Microsimulation\Journal\ViewModel\Converter\ViewModelConverter;
use Microsimulation\Journal\Patterns\ViewModel;

final class ThesisReferenceConverter implements ViewModelConverter
{
    use HasAuthors;

    /**
     * @param ThesisReference $object
     */
    public function convert($object, string $viewModel = null, array $context = []) : ViewModel
    {
        // hack for missing date
        $authorsSuffix = $this->createAuthorsSuffix($object);
        if (empty($authorsSuffix)) {
            $yearSuffix = '';
        } else {
            $yearSuffix = '('.$authorsSuffix[0].')';
        }

        $authors = [new ViewModel\ReferenceAuthorList([ViewModel\Author::asText($object->getAuthor()->getPreferredName())], $yearSuffix)];

        $query = [
            'title' => strip_tags($object->getTitle()),
            'author' => $object->getAuthor()->getPreferredName(),
            'publication_year' => $object->getDate()->getYear(),
        ];

        $abstracts = [new ViewModel\Link('Google Scholar', 'https://scholar.google.com/scholar_lookup?'.str_replace(['%5B0%5D=', '%5B1%5D='], '=', http_build_query($query)))];

        if ($object->getDoi()) {
            return ViewModel\Reference::withDoi($object->getTitle(), new ViewModel\Doi($object->getDoi()), [$object->getPublisher()->toString()], $authors, $abstracts);
        }

        return ViewModel\Reference::withOutDoi(new ViewModel\Link($object->getTitle(), $object->getUri()), [$object->getPublisher()->toString()], $authors, $abstracts);
    }

    public function supports($object, string $viewModel = null, array $context = []) : bool
    {
        return $object instanceof ThesisReference;
    }
}
