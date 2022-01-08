<?php declare(strict_types=1);

namespace HBS\SacEnhancer\Formatter\Arrays;

use HBS\Helpers\ArrayHelper;

class FilterNullsFormatter extends BaseFormatter
{
    protected function formatArray(array $response)
    {
        return ArrayHelper::filterNulls($response);
    }
}
