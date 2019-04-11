<?php

namespace Microsimulation\Journal\Patterns\ViewModel;

use Assert\Assertion;
use Microsimulation\Journal\Patterns\ArrayAccessFromProperties;
use Microsimulation\Journal\Patterns\ArrayFromProperties;
use Microsimulation\Journal\Patterns\ViewModel;

final class Button implements ViewModel
{
    const SIZE_CUSTOM = 'custom';
    const SIZE_MEDIUM = 'medium';
    const SIZE_SMALL = 'small';
    const SIZE_EXTRA_SMALL = 'extra-small';

    const STYLE_DEFAULT = 'default';
    const STYLE_LOGIN = 'login';
    const STYLE_OUTLINE = 'outline';
    const STYLE_SPEECH_BUBBLE = 'speech-bubble';

    const TYPE_BUTTON = 'button';
    const TYPE_SUBMIT = 'submit';
    const TYPE_RESET = 'reset';

    use ArrayAccessFromProperties;
    use ArrayFromProperties;

    private $classes;
    private $path;
    private $text;
    private $type;
    private $id;
    private $name;

    private function __construct(string $text, string $size, string $style, bool $isActive, string $name = null, string $id = null, bool $isFullWidth = true)
    {
        Assertion::notBlank($text);
        Assertion::choice($size, [self::SIZE_CUSTOM, self::SIZE_MEDIUM, self::SIZE_SMALL, self::SIZE_EXTRA_SMALL]);
        Assertion::choice($style, [self::STYLE_DEFAULT, self::STYLE_LOGIN, self::STYLE_OUTLINE, self::STYLE_SPEECH_BUBBLE]);
        if (self::STYLE_LOGIN === $style) {
            Assertion::true(self::SIZE_EXTRA_SMALL === $size);
        }

        $classes = [];

        if (self::SIZE_MEDIUM !== $size && self::STYLE_LOGIN !== $style && self::SIZE_CUSTOM !== $size) {
            $classes[] = 'button--'.$size;
        }

        if (self::STYLE_OUTLINE === $style && false === $isActive) {
            $classes[] = 'button--outline-inactive';
        } else {
            $classes[] = 'button--'.$style;

            if (false === $isActive) {
                $classes[] = 'button--inactive';
            }
        }

        if (true === $isFullWidth) {
            $classes[] = 'button--full';
        }

        $this->text = $text;
        $this->classes = implode(' ', $classes);
        $this->id = $id;
        $this->name = $name;
    }

    public static function form(
        string $text,
        string $type,
        string $name = null,
        string $size = self::SIZE_MEDIUM,
        string $style = self::STYLE_DEFAULT,
        string $id = null,
        bool $isActive = true,
        bool $isFullWidth = false
    ) : Button {
        Assertion::choice($type, [self::TYPE_BUTTON, self::TYPE_SUBMIT, self::TYPE_RESET]);

        $button = new static($text, $size, $style, $isActive, $name, $id, $isFullWidth);
        $button->type = $type;

        return $button;
    }

    public static function link(
        string $text,
        string $path,
        string $size = self::SIZE_MEDIUM,
        string $style = self::STYLE_DEFAULT,
        bool $isActive = true,
        bool $isFullWidth = false,
        string $id = null
    ) : Button {
        Assertion::notBlank($path);

        $button = new static($text, $size, $style, $isActive, null, $id, $isFullWidth);
        $button->path = $path;

        return $button;
    }

    public static function speechBubble(
        string $text,
        bool $isActive = true,
        string $name = null,
        string $id = null,
        bool $isPopulated = false,
        bool $isSmall = false
    ) : Button {
        $button = new static($text, self::SIZE_CUSTOM, self::STYLE_SPEECH_BUBBLE, $isActive, $name, $id, false);
        $button->type = self::TYPE_BUTTON;

        if ($isPopulated) {
            $button->classes .= ' button--speech-bubble-populated';
        }

        if ($isSmall) {
            $button->classes .= ' button--speech-bubble-small';
        }

        return $button;
    }

    public function getTemplateName() : string
    {
        return 'patterns/button.mustache';
    }
}
