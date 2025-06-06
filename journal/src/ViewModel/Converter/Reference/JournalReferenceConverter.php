<?php

namespace Microsimulation\Journal\ViewModel\Converter\Reference;

use eLife\ApiSdk\Model\Reference\JournalReference;
use eLife\ApiSdk\Model\Reference\ReferencePageRange;
use Microsimulation\Journal\Helper\Callback;
use Microsimulation\Journal\ViewModel\Converter\ViewModelConverter;
use Microsimulation\Journal\Patterns\ViewModel;

final class JournalReferenceConverter implements ViewModelConverter
{
    use HasAuthors;

    /**
     * @param JournalReference $object
     */
    public function convert($object, string $viewModel = null, array $context = []) : ViewModel
    {
        $journal = '<i>'.$object->getJournal().'</i>';
        if ($object->getVolume()) {
            $journal .= ' <b>'.$object->getVolume().'</b>:';
            if ($object->getPages() instanceof ReferencePageRange) {
                $journal .= $object->getPages()->getRange();
            } else {
                $journal .= $object->getPages()->toString();
            }
        } elseif ($object->getPages()) {
            $journal .= ' '.$object->getPages()->toString();
        }

        $origin = [$journal];

        // hack for missing date
        $authorsSuffix = $this->createAuthorsSuffix($object);

        $referenceAuthors = $this->pruneAuthors($object->getAuthors());

        $authors = [$this->createAuthors($referenceAuthors, $object->authorsEtAl(), $authorsSuffix)];

        $abstracts = [];
        if ($object->getPmid()) {
            $abstracts[] = new ViewModel\Link('PubMed', 'https://www.ncbi.nlm.nih.gov/pubmed/'.$object->getPmid());
        }

        $query = [
            'title' => strip_tags($object->getArticleTitle()),
            'author' => array_map(Callback::method('toString'), $object->getAuthors()),
            'publication_year' => $object->getDate()->getYear(),
            'journal' => $object->getJournal(),
            'volume' => $object->getVolume(),
            'pages' => $object->getPages()->toString(),
            'pmid' => $object->getPmid(),
        ];

        $abstracts[] = new ViewModel\Link('Google Scholar', 'https://scholar.google.com/scholar_lookup?'.str_replace(['%5B0%5D=', '%5B1%5D='], '=', http_build_query($query)));

        if ($object->getDoi()) {
            return ViewModel\Reference::withDoi($object->getArticleTitle(), new ViewModel\Doi($object->getDoi()), $origin, $authors, $abstracts);
        }

        return ViewModel\Reference::withOutDoi(new ViewModel\Link($object->getArticleTitle()), $origin, $authors, $abstracts);
    }

    public function supports($object, string $viewModel = null, array $context = []) : bool
    {
        return $object instanceof JournalReference;
    }
}
