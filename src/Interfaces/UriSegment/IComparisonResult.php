<?php

namespace Zap\Routing\Interfaces\UriSegment;

interface IComparisonResult
{
    /**
     * Returns name of any matching user value found in this segment, as defined in segment template
     * @return string
     */
    public function getSegmentName() : \string;

    /**
     * Returns variable name for user value extracted form segment
     * @param string $name
     * @return IComparisonResult
     */
    public function setSegmentName(\string $name = null) : IComparisonResult;

    /**
     * Returns any matching user value found in this segment
     * @return int|float|string
     */
    public function getSegmentValue();

    /**
     * Sets a user value extracted from segment
     * @param int|float|string $value
     * @return IComparisonResult
     */
    public function setSegmentValue($value = null) : IComparisonResult;

    /**
     * Returns true if segment comparison against URI template was a success
     * @return bool
     */
    public function isSuccess() : \bool;

    /**
     * Sets the success flag
     * @param bool $flag
     * @return IComparisonResult
     */
    public function setIsSuccess(\bool $flag = false) : IComparisonResult;
}
