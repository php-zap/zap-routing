<?php

namespace Zap\Routing\UriSegment;

/**
 * Class UriSegmentTemplate
 * @package Zap\Routing\UriSegment
 * @author Gabor Zelei
 */
class UriSegmentTemplate
{
    const SEGMENT_TYPE_CONSTANT = 'constant';
    const SEGMENT_TYPE_FLOAT = 'float';
    const SEGMENT_TYPE_INT = 'int';
    const SEGMENT_TYPE_STRING = 'string';
    const SEGMENT_TYPE_REGEX = 'regex';

    const DELIMITER_START = '[';
    const DELIMITER_END = ']';

    const PARTS_SEPARATOR = ':';

    /**
     * @var string
     */
    private $rawTemplateString;

    /**
     * @var string
     */
    private $segmentName = null;

    /**
     * @var string
     */
    private $segmentType = self::SEGMENT_TYPE_CONSTANT;

    /**
     * @var string
     */
    private $regex;

    /**
     * UriSegmentTemplate constructor.
     * @param string $templateString
     */
    public function __construct(string $templateString)
    {
        $this->rawTemplateString = trim($templateString);
        $this->extractSegmentConfig();
    }

    /**
     * @param string $actualString
     * @return bool
     */
    public function matches(string $actualString = null) : bool
    {
        switch ($this->segmentType) {
            case static::SEGMENT_TYPE_INT:
            case static::SEGMENT_TYPE_FLOAT:
            case static::SEGMENT_TYPE_STRING:
                return !empty($actualString);
                break;
            case static::SEGMENT_TYPE_REGEX:
                return boolval(preg_match($this->regex, $actualString)) == true;
                break;
            default:
                return ($this->rawTemplateString == $actualString);
                break;
        }
    }

    /**
     * Returns variable name associated with the value extracted from this segment
     * @return string
     */
    public function getName() : string
    {
        return $this->segmentName;
    }

    /**
     * Converts an input value to the type detected for this segment
     * @param mixed $value
     * @return float|int|string
     */
    public function convertValueToDetectedType($value)
    {
        switch ($this->segmentType) {
            case static::SEGMENT_TYPE_INT:
                return intval($value);
                break;
            case static::SEGMENT_TYPE_FLOAT:
                return floatval($value);
                break;
            case static::SEGMENT_TYPE_STRING:
            case static::SEGMENT_TYPE_REGEX:
            default:
                return strval($value);
                break;
        }
    }

    /**
     * Extracts segment configuration information from raw segment template string
     */
    private function extractSegmentConfig()
    {
        $firstChar = $this->rawTemplateString[0];
        $lastChar = $this->rawTemplateString[strlen($this->rawTemplateString) - 1];

        if (($firstChar !== static::DELIMITER_START) || ($lastChar !== static::DELIMITER_END)) {
            $this->segmentType = static::SEGMENT_TYPE_CONSTANT;
            return;
        }

        $templateString = trim($this->rawTemplateString, static::DELIMITER_START . static::DELIMITER_END);
        $parts = $this->getSegmentTemplateParts($templateString);

        $this->segmentName = $parts[0];
        $this->segmentType = isset($parts[1]) ? $this->getValidSegmentType($parts[1]) : static::SEGMENT_TYPE_STRING;
        $this->regex = isset($parts[2]) ? $parts[2] : '';
    }

    /**
     * Extracts, cleans and returns URI segment template parts
     * @param string $templateString
     * @return array
     */
    private function getSegmentTemplateParts(string $templateString)
    {
        $parts = explode(static::PARTS_SEPARATOR, $templateString);

        foreach ($parts as $index => $part) {
            $parts[$index] = trim($part);
        }

        return $parts;
    }

    /**
     * Makes sure extracted segment type is valid for non-constant-type segments
     * @param string $segmentType
     * @return string
     */
    private function getValidSegmentType(string $segmentType = '') : string
    {
        $validSegmentTypes = [
            static::SEGMENT_TYPE_INT,
            static::SEGMENT_TYPE_FLOAT,
            static::SEGMENT_TYPE_STRING,
            static::SEGMENT_TYPE_REGEX
        ];

        if (!empty($segmentType) && in_array($segmentType, $validSegmentTypes)) {
            return $segmentType;
        } else {
            // Default to type "string"
            return static::SEGMENT_TYPE_STRING;
        }
    }
}
