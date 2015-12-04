<?php

namespace Zap\Routing\Interfaces\UriSegment;

interface IComparator
{
    /**
     * @param null|int|float|string $actualValue
     * @return IComparator
     */
    public function setActualValue($actualValue = null);

    /**
     * @param string|null $templateString
     * @return IComparator
     */
    public function setTemplateString(\string $templateString = null);

    /**
     * Compares $actualString against $templateString and returns a result object with all extracted info
     * @return IComparisonResult
     */
    public function compare() : IComparisonResult;
}
