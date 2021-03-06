<?php

namespace Renatio\FormBuilder\Models;

use Model;
use October\Rain\Database\Traits\Sortable;
use October\Rain\Database\Traits\Validation;
use Twig;

/**
 * Class Section
 * @package Renatio\FormBuilder\Models
 */
class Section extends Model
{

    use Validation;
    use Sortable;

    /**
     * @var array
     */
    public $implement = ['@RainLab.Translate.Behaviors.TranslatableModel'];

    /**
     * @var string
     */
    public $table = 'renatio_formbuilder_sections';

    /**
     * @var array
     */
    public $rules = [
        'label' => 'max:255',
        'name'  => 'required|max:255',
        'class' => 'max:255',
    ];

    /**
     * @var array
     */
    public $attributeNames = [
        'label' => 'renatio.formbuilder::lang.field.label',
        'name'  => 'renatio.formbuilder::lang.field.name',
        'class' => 'renatio.formbuilder::lang.field.class',
    ];

    /**
     * @var array
     */
    protected $fillable = ['label', 'name', 'class'];

    /**
     * @var array
     */
    public $translatable = ['label'];

    /**
     * @var array
     */
    public $belongsTo = [
        'form' => ['Renatio\FormBuilder\Models\Form'],
    ];

    /**
     * @var array
     */
    public $hasMany = [
        'fields' => ['Renatio\FormBuilder\Models\Field'],
    ];

    /**
     * @param $query
     * @return mixed
     */
    public function scopeIsVisible($query)
    {
        return $query->where('is_visible', true);
    }

    /**
     * @return mixed
     */
    public function getHtmlAttribute()
    {
        $fieldType = FieldType::where('code', 'section')->remember(1)->first();

        return Twig::parse($fieldType->markup, $this->prepareFieldOptions());
    }

    /**
     * @return array
     */
    private function prepareFieldOptions()
    {
        return [
            'label'         => $this->label,
            'name'          => $this->name,
            'class'         => $this->class,
            'wrapper_begin' => $this->wrapper_begin,
            'wrapper_end'   => $this->wrapper_end,
            'fields'        => $this->fields->implode('html'),
        ];
    }

}
