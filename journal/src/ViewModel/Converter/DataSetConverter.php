<?php

namespace Microsimulation\Journal\ViewModel\Converter;

use eLife\ApiSdk\Model\DataSet;
use Microsimulation\Journal\ViewModel\Converter\Reference\HasAuthors;
use Microsimulation\Journal\Patterns\ViewModel;

final class DataSetConverter implements ViewModelConverter
{
    use HasAuthors;

    /**
     * @param DataSet $object
     */
    public function convert($object, string $viewModel = null, array $context = []) : ViewModel
    {
        $origin = [];
        if ($object->getDataId()) {
            $origin[] = 'ID '.$object->getDataId();
        }
        if ($object->getDetails()) {
            $origin[] = rtrim($object->getDetails(), '.');
        }

        $authors = [$this->createAuthors($object->getAuthors(), $object->authorsEtAl(), [$object->getDate()->format()])];

        if ($object->getDoi()) {
            return ViewModel\Reference::withDoi($object->getTitle(), new ViewModel\Doi($object->getDoi()), $origin, $authors);
        }

        return ViewModel\Reference::withOutDoi(new ViewModel\Link($object->getTitle(), $object->getUri()), $origin, $authors);
    }

    public function supports($object, string $viewModel = null, array $context = []) : bool
    {
        return $object instanceof DataSet;
    }
}
