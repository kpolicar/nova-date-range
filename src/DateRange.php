<?php

namespace Kpolicar\DateRange;

use DateTimeInterface;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class DateRange extends Field
{
    const DEFAULT_SEPERATOR = '-';

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'date-range';

    /**
     * The field dates' seperator
     *
     * @var string
     */
    protected $seperator;


    /**
     * @inheritdoc
     */
    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        if (is_array($name)) $name = implode('-', $name);
        if (is_array($attribute)) $attribute = implode('-', $attribute);

        $this->seperator(static::DEFAULT_SEPERATOR);

        parent::__construct($name, $attribute, $resolveCallback);
    }

    /**
     * @inheritdoc
     */
    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if ($request->exists($requestAttribute)) {
            [$from, $to] = $this->parseAttribute($attribute);
            [$valueFrom, $valueTo] = $this->parseResponse($request[$requestAttribute]);
            data_set($model, $to, $valueTo);
            data_set($model, $from, $valueFrom);
        }
    }

    /**
     * @inheritdoc
     */
    protected function resolveAttribute($resource, $attribute)
    {
        [$from, $to] = $this->parseAttribute($attribute);
        $fromValue = data_get($resource, $from);
        $toValue = data_get($resource, $to);

        return $fromValue ? ($fromValue->toDateString()." $this->seperator ".($toValue ? $toValue->toDateString() : '/')) : null;
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
     * Set the date format (Moment.js) that should be used to display the placeholder.
     *
     * @param  string  $format
     * @return $this
     */
    public function placeholderFormat($format)
    {
        return $this->withMeta(['placeholderFormat' => $format]);
    }

	/**
	 * Indicate that the field should be nullable.
	 *
	 * @param  bool $nullable
	 * @param  array|Closure $values
	 * @return $this
	 */
    public function nullable($nullable = true, $values = null)
    {
        return $this->withMeta(['nullable' => $nullable]);
    }

    /**
     * Set the seperator for the field's dates
     *
     * @param $seperator
     * @return $this
     */
    public function seperator($seperator)
    {
        $this->seperator = $seperator;
        return $this->withMeta(['seperator' => $seperator]);
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

        return array_pad(explode(" $this->seperator ", $attribute), 2, null);
    }
}
