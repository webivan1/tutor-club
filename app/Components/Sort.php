<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace App\Components;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Request;

class Sort
{
    public const SORT_ASC = 'asc';
    public const SORT_DESC = 'desc';

    /**
     * @var bool whether the sorting can be applied to multiple attributes simultaneously.
     * Defaults to `false`, which means each time the data can only be sorted by one attribute.
     */
    private $enableMultiSort = false;

    /**
     * @var array list of attributes that are allowed to be sorted. Its syntax can be
     * described using the following example:
     *
     * ```php
     * [
     *     'age',
     *     'name' => [
     *         'asc' => ['first_name' => SORT_ASC, 'last_name' => SORT_ASC],
     *         'desc' => ['first_name' => SORT_DESC, 'last_name' => SORT_DESC],
     *         'default' => SORT_DESC,
     *         'label' => 'Name',
     *     ],
     * ]
     * ```
     *
     * In the above, two attributes are declared: `age` and `name`. The `age` attribute is
     * a simple attribute which is equivalent to the following:
     *
     * ```php
     * 'age' => [
     *     'asc' => ['age' => SORT_ASC],
     *     'desc' => ['age' => SORT_DESC],
     *     'default' => SORT_ASC,
     *     'label' => Inflector::camel2words('age'),
     * ]
     * ```
     *
     * Since 2.0.12 particular sort direction can be also specified as direct sort expression, like following:
     *
     * ```php
     * 'name' => [
     *     'asc' => '[[last_name]] ASC NULLS FIRST', // PostgreSQL specific feature
     *     'desc' => '[[last_name]] DESC NULLS LAST',
     * ]
     * ```
     *
     * The `name` attribute is a composite attribute:
     *
     * - The `name` key represents the attribute name which will appear in the URLs leading
     *   to sort actions.
     * - The `asc` and `desc` elements specify how to sort by the attribute in ascending
     *   and descending orders, respectively. Their values represent the actual columns and
     *   the directions by which the data should be sorted by.
     * - The `default` element specifies by which direction the attribute should be sorted
     *   if it is not currently sorted (the default value is ascending order).
     * - The `label` element specifies what label should be used when calling [[link()]] to create
     *   a sort link. If not set, [[Inflector::camel2words()]] will be called to get a label.
     *   Note that it will not be HTML-encoded.
     *
     * Note that if the Sort object is already created, you can only use the full format
     * to configure every attribute. Each attribute must include these elements: `asc` and `desc`.
     */
    private $attributes = [];

    /**
     * @var string the name of the parameter that specifies which attributes to be sorted
     * in which direction. Defaults to `sort`.
     * @see params
     */
    private $sortParam = 'sort';

    /**
     * @var array the order that should be used when the current request does not specify any order.
     * The array keys are attribute names and the array values are the corresponding sort directions. For example,
     *
     * ```php
     * [
     *     'name' => SORT_ASC,
     *     'created_at' => SORT_DESC,
     * ]
     * ```
     *
     * @see attributeOrders
     */
    private $defaultOrder;

    /**
     * @var string the character used to separate different attributes that need to be sorted by.
     */
    private $separator = ',';

    /**
     * @var array parameters (name => value) that should be used to obtain the current sort directions
     * and to create new sort URLs. If not set, `$_GET` will be used instead.
     *
     * In order to add hash to all links use `array_merge($_GET, ['#' => 'my-hash'])`.
     *
     * The array element indexed by [[sortParam]] is considered to be the current sort directions.
     * If the element does not exist, the [[defaultOrder|default order]] will be used.
     *
     * @see sortParam
     * @see defaultOrder
     */
    private $params;

    /**
     * @return bool
     */
    public function isEnableMultiSort(): bool
    {
        return $this->enableMultiSort;
    }

    /**
     * @param bool $enableMultiSort
     */
    public function setEnableMultiSort(bool $enableMultiSort)
    {
        $this->enableMultiSort = $enableMultiSort;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @return string
     */
    public function getSortParam(): string
    {
        return $this->sortParam;
    }

    /**
     * @param string $sortParam
     */
    public function setSortParam(string $sortParam)
    {
        $this->sortParam = $sortParam;
    }

    /**
     * @return array
     */
    public function getDefaultOrder(): array
    {
        return $this->defaultOrder;
    }

    /**
     * @param array $defaultOrder
     */
    public function setDefaultOrder(array $defaultOrder)
    {
        $this->defaultOrder = $defaultOrder;
    }

    /**
     * @return string
     */
    public function getSeparator(): string
    {
        return $this->separator;
    }

    /**
     * @param string $separator
     */
    public function setSeparator(string $separator)
    {
        $this->separator = $separator;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * Normalizes the [[attributes]] property.
     */
    public function init()
    {
        $attributes = [];
        foreach ($this->attributes as $name => $attribute) {
            if (!is_array($attribute)) {
                $attributes[$attribute] = [
                    'asc' => [$attribute => self::SORT_ASC],
                    'desc' => [$attribute => self::SORT_DESC],
                ];
            } elseif (!isset($attribute['asc'], $attribute['desc'])) {
                $attributes[$name] = array_merge([
                    'asc' => [$name => self::SORT_ASC],
                    'desc' => [$name => self::SORT_DESC],
                ], $attribute);
            } else {
                $attributes[$name] = $attribute;
            }
        }
        $this->attributes = $attributes;
    }

    /**
     * Returns the columns and their corresponding sort directions.
     * @param bool $recalculate whether to recalculate the sort directions
     * @return array the columns (keys) and their corresponding sort directions (values).
     * This can be passed to [[\yii\db\Query::orderBy()]] to construct a DB query.
     */
    public function getOrders($recalculate = false)
    {
        $attributeOrders = $this->getAttributeOrders($recalculate);
        $orders = [];
        foreach ($attributeOrders as $attribute => $direction) {
            $definition = $this->attributes[$attribute];
            $columns = $definition[$direction === self::SORT_ASC ? 'asc' : 'desc'];
            if (is_array($columns)) {
                foreach ($columns as $name => $dir) {
                    $orders[$name] = $dir;
                }
            } else {
                $orders[] = $columns;
            }
        }

        return $orders;
    }

    /**
     * @param Builder $builder
     * @return void
     */
    public function orderWithQuery(Builder $builder): void
    {
        $orders = $this->getOrders();

        if (empty($orders)) {
            return;
        }

        foreach ($orders as $key => $sort) {
            $builder->orderBy($key, $sort);
        }
    }

    /**
     * @var array the currently requested sort order as computed by [[getAttributeOrders]].
     */
    private $_attributeOrders;

    /**
     * Returns the currently requested sort information.
     * @param bool $recalculate whether to recalculate the sort directions
     * @return array sort directions indexed by attribute names.
     * Sort direction can be either `SORT_ASC` for ascending order or
     * `SORT_DESC` for descending order.
     */
    public function getAttributeOrders($recalculate = false)
    {
        if ($this->_attributeOrders === null || $recalculate) {
            $this->_attributeOrders = [];

            if (($params = $this->params) === null) {
                $params = Request::all();
            }

            if (isset($params[$this->sortParam])) {
                foreach ($this->parseSortParam($params[$this->sortParam]) as $attribute) {
                    $descending = false;
                    if (strncmp($attribute, '-', 1) === 0) {
                        $descending = true;
                        $attribute = substr($attribute, 1);
                    }

                    if (isset($this->attributes[$attribute])) {
                        $this->_attributeOrders[$attribute] = $descending ? self::SORT_DESC : self::SORT_ASC;
                        if (!$this->enableMultiSort) {
                            return $this->_attributeOrders;
                        }
                    }
                }
            }
            if (empty($this->_attributeOrders) && is_array($this->defaultOrder)) {
                $this->_attributeOrders = $this->defaultOrder;
            }
        }

        return $this->_attributeOrders;
    }

    /**
     * Parses the value of [[sortParam]] into an array of sort attributes.
     *
     * The format must be the attribute name only for ascending
     * or the attribute name prefixed with `-` for descending.
     *
     * For example the following return value will result in ascending sort by
     * `category` and descending sort by `created_at`:
     *
     * ```php
     * [
     *     'category',
     *     '-created_at'
     * ]
     * ```
     *
     * @param string $param the value of the [[sortParam]].
     * @return array the valid sort attributes.
     * @since 2.0.12
     * @see $separator for the attribute name separator.
     * @see $sortParam
     */
    protected function parseSortParam($param)
    {
        return is_scalar($param) ? explode($this->separator, $param) : [];
    }

    /**
     * Sets up the currently sort information.
     * @param array|null $attributeOrders sort directions indexed by attribute names.
     * Sort direction can be either `SORT_ASC` for ascending order or
     * `SORT_DESC` for descending order.
     * @param bool $validate whether to validate given attribute orders against [[attributes]] and [[enableMultiSort]].
     * If validation is enabled incorrect entries will be removed.
     * @since 2.0.10
     */
    public function setAttributeOrders($attributeOrders, $validate = true)
    {
        if ($attributeOrders === null || !$validate) {
            $this->_attributeOrders = $attributeOrders;
        } else {
            $this->_attributeOrders = [];
            foreach ($attributeOrders as $attribute => $order) {
                if (isset($this->attributes[$attribute])) {
                    $this->_attributeOrders[$attribute] = $order;
                    if (!$this->enableMultiSort) {
                        break;
                    }
                }
            }
        }
    }

    /**
     * Returns the sort direction of the specified attribute in the current request.
     * @param string $attribute the attribute name
     * @return bool|null Sort direction of the attribute. Can be either `SORT_ASC`
     * for ascending order or `SORT_DESC` for descending order. Null is returned
     * if the attribute is invalid or does not need to be sorted.
     */
    public function getAttributeOrder($attribute)
    {
        $orders = $this->getAttributeOrders();

        return isset($orders[$attribute]) ? $orders[$attribute] : null;
    }

    /**
     * Generates a hyperlink that links to the sort action to sort by the specified attribute.
     * Based on the sort direction, the CSS class of the generated hyperlink will be appended
     * with "asc" or "desc".
     * @param string $attribute the attribute name by which the data should be sorted by.
     * @param array $options additional HTML attributes for the hyperlink tag.
     * There is one special attribute `label` which will be used as the label of the hyperlink.
     * If this is not set, the label defined in [[attributes]] will be used.
     * If no label is defined, [[\yii\helpers\Inflector::camel2words()]] will be called to get a label.
     * Note that it will not be HTML-encoded.
     * @return string the generated hyperlink
     * @throws \Exception if the attribute is unknown
     */
    public function link($attribute, $options = [])
    {
        if (($direction = $this->getAttributeOrder($attribute)) !== null) {
            $class = $direction === self::SORT_DESC ? 'desc' : 'asc';
            if (isset($options['class'])) {
                $options['class'] .= ' ' . $class;
            } else {
                $options['class'] = $class;
            }
        }

        $url = $this->createUrl($attribute);
        $options['data-sort'] = $this->createSortParam($attribute);

        if (isset($options['label'])) {
            $label = $options['label'];
            unset($options['label']);
        } else {
            if (isset($this->attributes[$attribute]['label'])) {
                $label = $this->attributes[$attribute]['label'];
            } else {
                $label = $attribute;
            }
        }

        return \Html::link($url, $label, $options);
    }

    /**
     * Creates a URL for sorting the data by the specified attribute.
     * This method will consider the current sorting status given by [[attributeOrders]].
     * For example, if the current page already sorts the data by the specified attribute in ascending order,
     * then the URL created will lead to a page that sorts the data by the specified attribute in descending order.
     * @param string $attribute the attribute name
     * @param bool $absolute whether to create an absolute URL. Defaults to `false`.
     * @return string the URL for sorting. False if the attribute is invalid.
     * @throws InvalidConfigException if the attribute is unknown
     * @see attributeOrders
     * @see params
     */
    public function createUrl($attribute, $absolute = false)
    {
        if (($params = $this->params) === null) {
            $params = Request::all();
        }

        $params[$this->sortParam] = $this->createSortParam($attribute);

        $url = Request::url();

        return $url . '?' . http_build_query($params);
    }

    /**
     * Creates the sort variable for the specified attribute.
     * The newly created sort variable can be used to create a URL that will lead to
     * sorting by the specified attribute.
     * @param string $attribute the attribute name
     * @return string the value of the sort variable
     * @throws \Exception if the specified attribute is not defined in [[attributes]]
     */
    public function createSortParam($attribute)
    {
        if (!isset($this->attributes[$attribute])) {
            throw new \Exception("Unknown attribute: $attribute");
        }
        $definition = $this->attributes[$attribute];
        $directions = $this->getAttributeOrders();
        if (isset($directions[$attribute])) {
            $direction = $directions[$attribute] === self::SORT_DESC ? self::SORT_ASC : self::SORT_DESC;
            unset($directions[$attribute]);
        } else {
            $direction = isset($definition['default']) ? $definition['default'] : self::SORT_ASC;
        }

        if ($this->enableMultiSort) {
            $directions = array_merge([$attribute => $direction], $directions);
        } else {
            $directions = [$attribute => $direction];
        }

        $sorts = [];
        foreach ($directions as $attribute => $direction) {
            $sorts[] = $direction === self::SORT_DESC ? '-' . $attribute : $attribute;
        }

        return implode($this->separator, $sorts);
    }

    /**
     * Returns a value indicating whether the sort definition supports sorting by the named attribute.
     * @param string $name the attribute name
     * @return bool whether the sort definition supports sorting by the named attribute.
     */
    public function hasAttribute($name)
    {
        return isset($this->attributes[$name]);
    }

    /**
     * @return array
     */
    public function urlAttributes(): array
    {
        $params = [];

        foreach ($this->getAttributes() as $attribute => $data) {
            $params[] = [
                'attribute' => $attribute,
                'label' => $data['label'],
                'url' => $this->createUrl($attribute),
                'order' => $this->getAttributeOrder($attribute),
                'active' => !is_null($this->getAttributeOrder($attribute))
            ];
        }

        return $params;
    }
}