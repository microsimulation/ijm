<?php

namespace Microsimulation\Journal\ViewModel\Converter;

use eLife\ApiSdk\Model\Cover;
use Microsimulation\Journal\Helper\CreatesIiifUri;
use Microsimulation\Journal\ViewModel\Factory\PictureBuilderFactory;
use Microsimulation\Journal\Patterns\ViewModel;

final class CoverBlockLinkConverter implements ViewModelConverter
{
    use CreatesIiifUri;

    private $pictureBuilderFactory;

    public function __construct(PictureBuilderFactory $pictureBuilderFactory)
    {
        $this->pictureBuilderFactory = $pictureBuilderFactory;
    }

    /**
     * @param Cover $object
     */
    public function convert($object, string $viewModel = null, array $context = []) : ViewModel
    {
        $image = $object->getBanner();

        $builder = $this->pictureBuilderFactory->forImage($image, 251, 169);

        return new ViewModel\BlockLink(
            $context['link'],
            $builder->build()
        );
    }

    public function supports($object, string $viewModel = null, array $context = []) : bool
    {
        return $object instanceof Cover && ViewModel\BlockLink::class === $viewModel && isset($context['link']);
    }
}
