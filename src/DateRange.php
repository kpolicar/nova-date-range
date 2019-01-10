<?php

namespace Kpolicar\DateRange;

use DateTimeInterface;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class DateRange extends Field
{
    const DEFAULT_SEPARATOR = '-';
    const DEFAULT_NULL_VALUE = '/';

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'date-range';

    /**
     * The field dates' separator.
     *
     * @var string
     */
    protected $separator = DateRange::DEFAULT_SEPARATOR;

    /**
     * The field dates' null value display.
     *
     * @var string
     */
    protected $nullDisplay = DateRange::DEFAULT_NULL_VALUE;


    /**
     * @inheritdoc
     */
    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        if (is_array($name)) $name = implode('-', $name);
        if (is_array($attribute)) $attribute = implode('-', $attribute);

        parent::__construct($name, $attribute, $resolveCallback);
    }

    /**
     * @inheritdoc
     */
    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if (!$request->exists($requestAttribute)) return;

        [$from, $to] = $this->parseAttribute($attribute);
        [$valueFrom, $valueTo] = $this->parseResponse($request[$requestAttribute]);
        data_set($model, $to, $valueTo);
        data_set($model, $from, $valueFrom);
    }

    /**
     * @inheritdoc
     */
    protected function resolveAttribute($resource, $attribute)
    {
        [$from, $to] = $this->parseAttribute($attribute);
        $fromValue = $this->formatDisplayValue(data_get($resource, $from));
        $toValue = $this->formatDisplayValue(data_get($resource, $to));

        return "$fromValue $this->separator $toValue";
    }

    /**
     * Format the datetime value
     * @param $value
     * @return string
     */
    protected function formatDisplayValue($value)
    {
        return $value instanceof DateTimeInterface ? $value->toDateString() : $this->nullDisplay;
    }

    /**
     * Set the date format (Moment.js) that should be used to display the date.
     *
     * @param  string  $format
     * @return $this
     */
    public function format($format)
    {
        return $this->withMeta(['format' => $format]);
    }

    /**
     * Indicate that the date field is nullable.
     *
     * @return $this
     */
    public function nullable()
    {
        return $this->withMeta(['nullable' => true]);
    }

    /**
     * Set the separator for the field's dates
     *
     * @param $separator
     * @return $this
     */
    public function separator($separator)
    {
        $this->separator = $separator;
        return $this->withMeta(['separator' => $separator]);
    }

    /**
     * Set the null display value for the field dates
     *
     * @param $value
     */
    public function nullDisplay($value)
    {
        $this->nullDisplay = $value;
        return $this;
    }

    /**
     * Parse the attribute name to retrieve the affected model attributes
     *
     * @param $attribute
     * @return array
     */
    protected function parseAttribute($attribute)
    {
        return explode('-', $attribute);
    }

    /**
     * Parse the response to retrieve the raw values
     *
     * @param $attribute
     * @return array
     */
    protected function parseResponse($attribute)
    {
        if ($attribute === null) {
            return [null, null];
        }
        
        return array_pad(explode(" $this->separator ", $attribute), 2, null);
    }
}
