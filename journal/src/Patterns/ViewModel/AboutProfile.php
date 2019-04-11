<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class AboutProfile implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $name;
    private $role;
    private $image;
    private $profile;

    public function __construct(string $name, string $role = null, Picture $image = null, string $profile = null)
    {
        Assertion::notBlank($name);

        $this->name = $name;
        $this->role = $role;
        $this->image = $image;
        $this->profile = $profile;
    }

    public function getTemplateName() : string
    {
        return 'patterns/about-profile.mustache';
    }
}
