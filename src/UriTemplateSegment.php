<?php

namespace Zap\Routing;

/**
 * Class SegmentTemplate
 * @package Zap\Routing
 * @author Gabor Zelei
 */
class UriTemplateSegment
{
    const TEMPLATE_START_CHAR = '[';
    const TEMPLATE_END_CHAR = ']';
    const PARTS_SEPARATOR = ':';
    const REGEX_SEPARATOR = '|';

    const TYPE_FLOAT = 'float';
    const TYPE_INT = 'int';
    CONST TYPE_REGEX = 'regex';
    const TYPE_STRING = 'string';

    /**
     * @var bool
     */
    private $isStaticSegment = true;

    /**
     * @var string
     */
    private $type = self::TYPE_STRING;

    /**
     * @var string
     */
    private $variableName = '';

    /**
     * UriTemplateSegment constructor.
     * @param string $templateString
     */
    public function __construct(string $templateString)
    {
        $this->templateString = $templateString;
        $this->process();
    }

    /**
     * @return string
     */
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * Converts $value into the currently detected type and returns it
     * @param string $value
     * @return float|int|string|string
     */
    public function getValueWithType(string $value)
    {
        switch ($this->getType()) {
            case static::TYPE_FLOAT:
                return floatval($value);
                break;
            case static::TYPE_INT:
                return intval($value);
                break;
            case static::TYPE_STRING:
                return $value;
                break;
            default:
                if (substr($this->templateString, 0, strlen(static::TYPE_REGEX)) == static::TYPE_REGEX) {
                    return $this->processForRegex($value);
                }

                return $value;
                break;
        }
    }

    /**
     * @return string
     */
    public function getVariableName() : string
    {
        return $this->variableName;
    }

    /**
     * @return bool
     */
    public function isStatic() : bool
    {
        return $this->isStaticSegment;
    }

    /**
     * Runs a value through regex. If it's a match, returns the value, otherwise returns null
     * @param string $value
     * @return string
     */
    private function processForRegex(string $value) : string
    {
        $parts = explode(static::REGEX_SEPARATOR, $this->templateString);

        if (isset($parts[1]) && (boolval(preg_match($parts[1], $value)) === true)) {
            return $value;
        }

        return null;
    }

    private function process()
    {
        $this->setIsStatic($this->templateString);

        if (!$this->isStatic()) {
            $template = ltrim(rtrim($this->templateString, static::TEMPLATE_END_CHAR), static::TEMPLATE_START_CHAR);
            $segmentParts = explode(static::PARTS_SEPARATOR, $template);
            $this->variableName = isset($segmentParts[0]) ? $segmentParts[0] : '';

            if (isset($segmentParts[1])) {
                $this->type = $segmentParts[1];
            }
        }
    }

    /**
     * Sets $this->isStaticSegment to false if segment template needs to be processed
     * @param string $template
     */
    private function setIsStatic(string $template)
    {
        $lastCharPos = strlen($template) - 1;
        $start = strpos($template, static::TEMPLATE_START_CHAR);
        $end = strpos($template, static::TEMPLATE_END_CHAR, $lastCharPos);
        $this->isStaticSegment = (($start == 0) && ($end == $lastCharPos));
    }
}
