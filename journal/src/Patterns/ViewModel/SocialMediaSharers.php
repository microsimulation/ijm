<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;
use function rawurlencode;

final class SocialMediaSharers implements ViewModel
{
    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $facebookUrl;
    private $twitterUrl;
    private $emailUrl;
    private $redditUrl;

    public function __construct(string $title, string $url)
    {
        Assertion::notBlank($title);
        Assertion::url($url);

        $encodedTitle = rawurlencode($title);
        $encodedUrl = rawurlencode($url);

        $this->facebookUrl = "https://facebook.com/sharer/sharer.php?u={$encodedUrl}";
        $this->twitterUrl = "https://twitter.com/intent/tweet/?text={$encodedTitle}&url={$encodedUrl}";
        $this->emailUrl = "mailto:?subject={$encodedTitle}&body={$encodedUrl}";
        $this->redditUrl = "https://reddit.com/submit/?title={$encodedTitle}&url={$encodedUrl}";
    }

    public function getTemplateName() : string
    {
        return 'patterns/social-media-sharers.mustache';
    }
}
