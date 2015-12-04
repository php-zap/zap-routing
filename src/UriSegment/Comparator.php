<?php

namespace Zap\Routing\UriSegment;

use Zap\Routing\Interfaces\UriSegment\IComparator;
use Zap\Routing\Interfaces\UriSegment\IComparisonResult;

/**
 * Class Comparator
 * @package Zap\Routing\UriSegment
 * @author Gabor Zelei
 */
class Comparator implements IComparator
{
    /**
     * @var string
     */
    private $actualString;

    /**
     * @var IComparisonResult
     */
    private $result;

    /**
     * @var string
     */
    private $templateString;

    /**
     * Comparator constructor.
     * @param string $templateString
     * @param string|null $actualValue
     * @param IComparisonResult|null $resultContainer    For DI, mainly testing
     */
    public function __construct(
        \string $templateString = '',
        \string $actualValue = null,
        IComparisonResult $resultContainer = null
    ) {
        $this->actualString = $actualValue;
        $this->result = is_null($resultContainer) ? new ComparisonResult() : $resultContainer;
        $this->templateString = $templateString;
    }

    /**
     * @param null|int|float|string $actualValue
     * @return IComparator
     */
    public function setActualValue($actualValue = null)
    {
        $this->actualString = strval($actualValue);
        return $this;
    }

    /**
     * @param string|null $templateString
     * @return IComparator
     */
    public function setTemplateString(\string $templateString = null)
    {
        if (is_null($templateString)) {
            throw new \InvalidArgumentException();
        }

        $this->templateString = $templateString;
        return $this;
    }

    /**
     * Compares $actualString against $templateString and returns a result object with all extracted info
     * @param UriSegmentTemplate|null $segmentTemplate
     * @return IComparisonResult
     */
    public function compare(UriSegmentTemplate $segmentTemplate = null) : IComparisonResult
    {
        $segmentTemplate = is_null($segmentTemplate) ? new UriSegmentTemplate($this->templateString) : $segmentTemplate;
        return $this->result
            ->setIsSuccess($segmentTemplate->matches($this->actualString))
            ->setSegmentName($segmentTemplate->getName())
            ->setSegmentValue($segmentTemplate->convertValueToDetectedType($this->actualString));
    }
}
