<?php

namespace Zap\Routing\UriSegment;

/**
 * Class ComparatorResult
 * @package Zap\Routing\UriSegment
 * @author Gabor Zelei
 */
class ComparatorResult
{
    /**
     * @var string
     */
    private $segmentName;

    /**
     * @var int|float|string
     */
    private $segmentValue;

    /**
     * @var bool
     */
    private $success = false;

    /**
     * Chainable factory method for convenience
     * @return ComparatorResult
     */
    public static function create() : ComparatorResult
    {
        return new static();
    }

    /**
     * Returns name of any matching user value found in this segment, as defined in segment template
     * @return string
     */
    public function getSegmentName() : string
    {
        return $this->segmentName;
    }

    /**
     * Returns variable name for user value extracted form segment
     * @param string $name
     * @return ComparatorResult
     */
    public function setSegmentName(string $name = null) : ComparatorResult
    {
        $this->segmentName = $name;
        return $this;
    }

    /**
     * Returns any matching user value found in this segment
     * @return int|float|string
     */
    public function getSegmentValue()
    {
        return $this->segmentValue;
    }

    /**
     * Sets a user value extracted from segment
     * @param int|float|string $value
     * @return ComparatorResult
     */
    public function setSegmentValue($value = null) : ComparatorResult
    {
        $this->segmentValue = $value;
        return $this;
    }

    /**
     * Returns true if segment comparison against URI template was a success
     * @return bool
     */
    public function isSuccess() : bool
    {
        return $this->success;
    }

    /**
     * Sets the success flag
     * @param bool $flag
     * @return ComparatorResult
     */
    public function setIsSuccess(bool $flag = false) : ComparatorResult
    {
        $this->success = $flag;
        return $this;
    }
}
