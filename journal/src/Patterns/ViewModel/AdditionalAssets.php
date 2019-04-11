<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class AdditionalAssets implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $heading;
    private $assets;

    public function __construct(
        string $heading = null,
        array $assets
    ) {
        Assertion::notEmpty($assets);
        Assertion::allIsInstanceOf($assets, AdditionalAsset::class);

        $this->heading = $heading;
        $this->assets = $assets;
    }

    public function getTemplateName() : string
    {
        return 'patterns/additional-assets.mustache';
    }
}
