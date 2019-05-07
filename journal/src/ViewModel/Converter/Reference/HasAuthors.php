<?php

namespace Microsimulation\Journal\ViewModel\Converter\Reference;

use eLife\ApiSdk\Model;
use eLife\ApiSdk\Model\AuthorEntry;
use Microsimulation\Journal\Patterns\ViewModel;

trait HasAuthors
{
    private function createAuthors(array $authors, bool $etAl, array $suffixes) : ViewModel\ReferenceAuthorList
    {
        $authors = array_map(function (Model\AuthorEntry $author) {
            return ViewModel\Author::asLink(new ViewModel\Link($author->toString(), 'https://scholar.google.com/scholar?q=%22author:'.urlencode($author->toString()).'%22'));
        }, $authors);

        if (count($suffixes)) {
            $suffix = trim(array_reduce(array_filter($suffixes), function (string $carry, string $suffix) {
                return $carry.' ('.$suffix.')';
            }, ''));
        } else {
            $suffix = '';
        }

        if ($etAl) {
            $suffix = 'et al. '.$suffix;
        }

        return new ViewModel\ReferenceAuthorList($authors, $suffix);
    }

    private function createAuthorsString(array $authors, bool $etAl = false) : string
    {
        $authors = implode(', ', array_map(function (AuthorEntry $author) {
            return $author->toString();
        }, $authors));

        if ($etAl) {
            $authors .= ' et al.';
        }

        return $authors;
    }

    private function createAuthorsSuffix($object) : array
    {
        // hack for missing date, takes in a Reference object like BookReference, etc.
        if ($object->getDate()->getYear() > 1000) {
            $authorsSuffix = [$object->getDate()->format().$object->getDiscriminator()];
        } else {
            $authorsSuffix = [];
        }
        return $authorsSuffix;
    }

    private function pruneAuthors(array $authors) : array
    {
        // Hack to prune out group authors of name n/a used as a placeholder
        $referenceAuthors = array();
        foreach($authors as $referenceAuthor)
        {
            if (property_exists($referenceAuthor, 'name') && $referenceAuthor->getName() == 'n/a') {
                continue;
            }
            $referenceAuthors[] = $referenceAuthor;
        }
        return $referenceAuthors;
    }
}
