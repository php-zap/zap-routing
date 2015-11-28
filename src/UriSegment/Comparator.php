<?php

namespace Zap\Routing\UriSegment;

/**
 * Class Comparator
 * @package Zap\Routing\UriSegment
 * @author Gabor Zelei
 */
class Comparator
{
    /**
     * @var string
     */
    private $actualString;

    /**
     * @var string
     */
    private $templateString;

    /**
     * Comparator constructor.
     * @param string $templateString
     * @param string $actualString
     */
    public function __construct(string $templateString, string $actualString = null)
    {
        $this->actualString = $actualString;
        $this->templateString = $templateString;
    }

    /**
     * Chainable factory method for convenience
     * @param string $templateString
     * @param string $actualString
     * @return Comparator
     */
    public static function create(string $templateString, string $actualString = null) : Comparator
    {
        return new static($templateString, $actualString);
    }

    /**
     * Compares $actualString against $templateString and returns a result object with all extracted info
     * @return ComparisonResult
     */
    public function compare() : ComparisonResult
    {
        $segmentTemplate = new UriSegmentTemplate($this->templateString);
        return ComparisonResult::create()
            ->setIsSuccess($segmentTemplate->matches($this->actualString))
            ->setSegmentName($segmentTemplate->getName())
            ->setSegmentValue($segmentTemplate->convertValueToDetectedType($this->actualString));
    }
}
