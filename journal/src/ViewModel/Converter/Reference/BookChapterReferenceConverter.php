<?php

namespace Microsimulation\Journal\ViewModel\Converter\Reference;

use eLife\ApiSdk\Model\Reference\BookChapterReference;
use Microsimulation\Journal\Helper\Callback;
use Microsimulation\Journal\ViewModel\Converter\ViewModelConverter;
use Microsimulation\Journal\Patterns\ViewModel;

final class BookChapterReferenceConverter implements ViewModelConverter
{
    use HasAuthors;
    use HasPublisher;

    /**
     * @param BookChapterReference $object
     */
    public function convert($object, string $viewModel = null, array $context = []) : ViewModel
    {
        $bookTitle = $object->getBookTitle();
        if ($object->getVolume()) {
            $bookTitle .= ', '.$object->getVolume();
        }
        if ($object->getEdition()) {
            $bookTitle .= ' ('.$object->getEdition().')';
        }

        $origin = [
            'In: '.$this->createAuthorsString($object->getEditors(), $object->editorsEtAl()).', editors',
            '<i>'.$bookTitle.'</i>',
            $this->publisherToString($object->getPublisher()),
        ];
        if ($object->getIsbn()) {
            $origin[] = 'ISBN '.$object->getIsbn();
        }
        $origin[] = $object->getPages()->toString();

        // hack for missing date
        $authorsSuffix = $this->createAuthorsSuffix($object);

        $referenceAuthors = $this->pruneAuthors($object->getAuthors());

        $authors = [$this->createAuthors($referenceAuthors, $object->authorsEtAl(), $authorsSuffix)];

        $abstracts = [];
        if ($object->getPmid()) {
            $abstracts[] = new ViewModel\Link('PubMed', 'https://www.ncbi.nlm.nih.gov/pubmed/'.$object->getPmid());
        }

        $query = [
            'title' => strip_tags($object->getChapterTitle()),
            'author' => array_map(Callback::method('toString'), $object->getAuthors()),
            'publication_year' => $object->getDate()->getYear(),
            'pmid' => $object->getPmid(),
            'isbn' => $object->getIsbn(),
            'pages' => $object->getPages()->toString(),
        ];

        $abstracts[] = new ViewModel\Link('Google Scholar', 'https://scholar.google.com/scholar_lookup?'.str_replace(['%5B0%5D=', '%5B1%5D='], '=', http_build_query($query)));

        if ($object->getDoi()) {
            return ViewModel\Reference::withDoi($object->getChapterTitle(), new ViewModel\Doi($object->getDoi()), $origin, $authors, $abstracts);
        }

        return ViewModel\Reference::withOutDoi(new ViewModel\Link($object->getChapterTitle()), $origin, $authors, $abstracts);
    }

    public function supports($object, string $viewModel = null, array $context = []) : bool
    {
        return $object instanceof BookChapterReference;
    }
}
