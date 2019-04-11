<?php

namespace Microsimulation\Journal\ViewModel\Converter;

use eLife\ApiSdk\Model\HasThumbnail;
use Microsimulation\Journal\Helper\HasViewModelConverter;
use Microsimulation\Journal\Patterns\ViewModel;

trait CreatesTeaserImage
{
    use HasViewModelConverter;

    final private function prominentTeaserImage(HasThumbnail $object) : ViewModel\TeaserImage
    {
        return ViewModel\TeaserImage::prominent(
            $this->getViewModelConverter()->convert($object->getThumbnail(), null, ['width' => 251, 'height' => 141])
        );
    }

    final private function bigTeaserImage(HasThumbnail $object) : ViewModel\TeaserImage
    {
        return ViewModel\TeaserImage::big(
            $this->getViewModelConverter()->convert($object->getThumbnail(), null, ['width' => 257, 'height' => 144])
        );
    }

    final private function smallTeaserImage(HasThumbnail $object) : ViewModel\TeaserImage
    {
        return ViewModel\TeaserImage::small(
            $this->getViewModelConverter()->convert($object->getThumbnail(), null, ['width' => 72, 'height' => 72])
        );
    }
}
