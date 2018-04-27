<?php

namespace App\Entity;

use App\Entity\Advert\AdvertAttribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $category_id
 * @property string $label
 * @property string $variants
 * @property boolean $required
 * @property integer $sort
 * @property string $type
 *
 * @property string|integer $value
 */
class Attribute extends Model
{
    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
     * @var array
     */
    public $fillable = [
        'category_id', 'label', 'variants', 'required', 'sort', 'type'
    ];

    /**
     * @var array
     */
    public $casts = [
        'required' => 'boolean'
    ];

    public const TYPE_SELECT = 'select';
    public const TYPE_NUMBER = 'number';
    public const TYPE_FLOAT = 'float';
    public const TYPE_TEXT = 'text';
    public const TYPE_CHECKBOX = 'checkbox';
    public const TYPE_RADIO = 'radio';

    /**
     * @return array
     */
    public static function types(): array
    {
        return [
            self::TYPE_SELECT => 'Поле с выбором',
            self::TYPE_NUMBER => 'Поле с числом',
            self::TYPE_FLOAT => 'Число с плавующей точкой',
            self::TYPE_TEXT => 'Текстовое поле',
            self::TYPE_CHECKBOX => 'Чекбокс',
            self::TYPE_RADIO => 'Радио кнопка'
        ];
    }

    /**
     * @return bool
     */
    public function isSelect(): bool
    {
        return $this->type === self::TYPE_SELECT;
    }

    /**
     * @return bool
     */
    public function isNumber(): bool
    {
        return $this->type === self::TYPE_NUMBER;
    }

    /**
     * @return bool
     */
    public function isFloat(): bool
    {
        return $this->type === self::TYPE_FLOAT;
    }

    /**
     * @return bool
     */
    public function isText(): bool
    {
        return $this->type === self::TYPE_TEXT;
    }

    public function isCheckbox(): bool
    {
        return $this->type === self::TYPE_CHECKBOX;
    }

    /**
     * @return bool
     */
    public function isRadio(): bool
    {
        return $this->type === self::TYPE_RADIO;
    }

    /**
     * @return bool
     */
    public function isStyleInlineField(): bool
    {
        return $this->isFloat() || $this->isNumber() || $this->isSelect() || $this->isText();
    }

    /**
     * @return bool
     */
    public function isStyleCheckField(): bool
    {
        return !$this->isStyleInlineField();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function value()
    {
        return $this->belongsTo(AdvertAttribute::class, 'id', 'attribute_id');
    }

    /**
     * Get the variants
     *
     * @return array
     */
    public function variantsToArray(): array
    {
        $variants = explode("\n", $this->variants);

        if (count($variants) === 1) {
            $variants = array_shift($variants);
            if (strpos('@', $variants) !== false) {
                list($model, $method) = explode('@', $variants);

                if (!class_exists($model)) {
                    throw new \DomainException('Undefined class ' . $model);
                }

                return call_user_func([new $model, $method]);
            } else {
                list($key, $value) = explode('=', $variants);
                return [trim($key) => trim($value)];
            }
        }

        $result = [];

        foreach ($variants as $variant) {
            list($key, $value) = explode('=', $variant);
            $result[trim($key)] = trim($value);
        }

        return $result;
    }
}
