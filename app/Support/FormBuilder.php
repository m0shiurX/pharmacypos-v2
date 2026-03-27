<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

/**
 * Drop-in replacement for LaravelCollective Form builder.
 * Supports the same method signatures used throughout the POS views.
 */
class FormBuilder
{
    protected ?string $model = null;

    public function setModel($model): void
    {
        $this->model = null;
        if (is_object($model) || is_array($model)) {
            $this->model = $model;
        }
    }

    public function getModel()
    {
        return $this->model;
    }

    /**
     * Open a form tag.
     * Usage: Form::open(['url' => '...', 'method' => 'post', 'id' => '...', 'files' => true, 'class' => '...'])
     */
    public function open(array $options = []): HtmlString
    {
        $method = strtoupper($options['method'] ?? 'POST');
        $htmlMethod = in_array($method, ['GET', 'POST']) ? $method : 'POST';

        $attributes = [];
        $attributes['method'] = $htmlMethod;

        if (isset($options['url'])) {
            $attributes['action'] = $options['url'];
        } elseif (isset($options['route'])) {
            $attributes['action'] = is_array($options['route'])
                ? route($options['route'][0], array_slice($options['route'], 1))
                : route($options['route']);
        } elseif (isset($options['action'])) {
            $attributes['action'] = is_array($options['action'])
                ? action($options['action'][0], array_slice($options['action'], 1))
                : action($options['action']);
        }

        if (! empty($options['files'])) {
            $attributes['enctype'] = 'multipart/form-data';
        }

        foreach (['id', 'class', 'enctype', 'accept-charset', 'autocomplete', 'novalidate', 'target'] as $attr) {
            if (isset($options[$attr])) {
                $attributes[$attr] = $options[$attr];
            }
        }

        if (isset($options['model'])) {
            $this->setModel($options['model']);
        }

        $html = '<form'.$this->attributes($attributes).'>';

        // Add CSRF token for non-GET forms
        if ($htmlMethod !== 'GET') {
            $html .= csrf_field();
        }

        // Add method spoofing for PUT, PATCH, DELETE
        if (! in_array($method, ['GET', 'POST'])) {
            $html .= method_field($method);
        }

        return new HtmlString($html);
    }

    /**
     * Close a form tag.
     */
    public function close(): HtmlString
    {
        $this->model = null;

        return new HtmlString('</form>');
    }

    /**
     * Model-bound form.
     */
    public function model($model, array $options = []): HtmlString
    {
        $this->setModel($model);

        return $this->open($options);
    }

    /**
     * Create a label element.
     * Usage: Form::label('name', 'Label Text', ['class' => '...'])
     */
    public function label(string $name, ?string $value = null, array $options = []): HtmlString
    {
        $value = $value ?? ucfirst(str_replace(['_', '-'], ' ', $name));
        $options['for'] = $options['for'] ?? $name;

        return new HtmlString('<label'.$this->attributes($options).'>'.e($value).'</label>');
    }

    /**
     * Create a text input.
     * Usage: Form::text('name', $value, ['class' => '...', 'placeholder' => '...'])
     */
    public function text(string $name, ?string $value = null, array $options = []): HtmlString
    {
        return $this->input('text', $name, $value, $options);
    }

    /**
     * Create an email input.
     */
    public function email(string $name, ?string $value = null, array $options = []): HtmlString
    {
        return $this->input('email', $name, $value, $options);
    }

    /**
     * Create a number input.
     */
    public function number(string $name, ?string $value = null, array $options = []): HtmlString
    {
        return $this->input('number', $name, $value, $options);
    }

    /**
     * Create a password input.
     */
    public function password(string $name, array $options = []): HtmlString
    {
        return $this->input('password', $name, null, $options);
    }

    /**
     * Create a hidden input.
     */
    public function hidden(string $name, ?string $value = null, array $options = []): HtmlString
    {
        return $this->input('hidden', $name, $value, $options);
    }

    /**
     * Create a file input.
     */
    public function file(string $name, array $options = []): HtmlString
    {
        return $this->input('file', $name, null, $options);
    }

    /**
     * Create a generic input element.
     */
    public function input(string $type, string $name, $value = null, array $options = []): HtmlString
    {
        $options['type'] = $type;
        $options['name'] = $name;

        if (! isset($options['id'])) {
            $options['id'] = $this->getIdAttribute($name, $options);
        }

        if ($type !== 'file' && $type !== 'password') {
            $value = $this->getValueAttribute($name, $value);
            $options['value'] = $value;
        }

        return new HtmlString('<input'.$this->attributes($options).'>');
    }

    /**
     * Create a textarea.
     * Usage: Form::textarea('name', $value, ['class' => '...', 'rows' => 3])
     */
    public function textarea(string $name, ?string $value = null, array $options = []): HtmlString
    {
        $options['name'] = $name;
        if (! isset($options['id'])) {
            $options['id'] = $this->getIdAttribute($name, $options);
        }
        if (! isset($options['rows'])) {
            $options['rows'] = 10;
        }
        if (! isset($options['cols'])) {
            $options['cols'] = 50;
        }

        $value = $this->getValueAttribute($name, $value);

        return new HtmlString('<textarea'.$this->attributes($options).'>'.e($value ?? '').'</textarea>');
    }

    /**
     * Create a select dropdown.
     * Usage: Form::select('name', $options, $selected, ['class' => '...', 'multiple' => true, 'placeholder' => '...'])
     */
    public function select(string $name, $list = [], $selected = null, array $selectAttributes = [], array $optionsAttributes = [], array $optgroupsAttributes = []): HtmlString
    {
        $selectAttributes['name'] = $name;
        if (! isset($selectAttributes['id'])) {
            $selectAttributes['id'] = $this->getIdAttribute($name, $selectAttributes);
        }

        // Handle multiple select
        if (isset($selectAttributes['multiple']) && $selectAttributes['multiple']) {
            if (! str_ends_with($name, '[]')) {
                $selectAttributes['name'] = $name.'[]';
            }
        }

        $selected = $this->getValueAttribute($name, $selected);

        $html = '<select'.$this->attributes($selectAttributes).'>';

        // Add placeholder option
        if (isset($selectAttributes['placeholder'])) {
            $html .= '<option value="">'.e($selectAttributes['placeholder']).'</option>';
        }

        foreach ((array) $list as $value => $display) {
            if (is_array($display)) {
                // Optgroup
                $html .= '<optgroup label="'.e($value).'">';
                foreach ($display as $optValue => $optDisplay) {
                    $html .= $this->getSelectOption($optValue, $optDisplay, $selected);
                }
                $html .= '</optgroup>';
            } else {
                $html .= $this->getSelectOption($value, $display, $selected);
            }
        }

        $html .= '</select>';

        return new HtmlString($html);
    }

    /**
     * Create a checkbox input.
     * Usage: Form::checkbox('name', $value, $checked, ['class' => '...'])
     */
    public function checkbox(string $name, $value = '1', $checked = null, array $options = []): HtmlString
    {
        return $this->checkable('checkbox', $name, $value, $checked, $options);
    }

    /**
     * Create a radio input.
     * Usage: Form::radio('name', $value, $checked, ['class' => '...'])
     */
    public function radio(string $name, $value = null, $checked = null, array $options = []): HtmlString
    {
        if (is_null($value)) {
            $value = $name;
        }

        return $this->checkable('radio', $name, $value, $checked, $options);
    }

    /**
     * Create a submit button.
     */
    public function submit(?string $value = null, array $options = []): HtmlString
    {
        return new HtmlString('<input type="submit"'.$this->attributes(array_merge($options, ['value' => $value])).'>');
    }

    /**
     * Create a CSRF token field.
     */
    public function token(): HtmlString
    {
        return new HtmlString((string) csrf_field());
    }

    // ---- Internal helpers ----

    protected function checkable(string $type, string $name, $value, $checked, array $options): HtmlString
    {
        $options['type'] = $type;
        $options['name'] = $name;
        $options['value'] = $value;

        if (! isset($options['id'])) {
            $options['id'] = $this->getIdAttribute($name, $options).'_'.$value;
        }

        if ($checked === true || $checked === 1 || $checked === '1') {
            $options['checked'] = 'checked';
        } elseif (is_null($checked)) {
            $modelValue = $this->getModelValueAttribute($name);
            if ($type === 'checkbox') {
                if (is_array($modelValue)) {
                    $options['checked'] = in_array($value, $modelValue) ? 'checked' : null;
                } else {
                    $options['checked'] = ((string) $modelValue === (string) $value) ? 'checked' : null;
                }
            } else {
                $options['checked'] = ((string) $modelValue === (string) $value) ? 'checked' : null;
            }
        }

        if (! isset($options['checked']) || $options['checked'] === null) {
            unset($options['checked']);
        }

        return new HtmlString('<input'.$this->attributes($options).'>');
    }

    protected function getSelectOption($value, $display, $selected): string
    {
        $isSelected = false;
        if ($selected instanceof Collection) {
            $selected = $selected->toArray();
        }
        if (is_array($selected)) {
            $isSelected = in_array((string) $value, array_map('strval', $selected));
        } elseif (! is_null($selected)) {
            $isSelected = ((string) $value === (string) $selected);
        }

        $selectedAttr = $isSelected ? ' selected="selected"' : '';

        return '<option value="'.e($value).'"'.$selectedAttr.'>'.e($display).'</option>';
    }

    protected function getValueAttribute(string $name, $value = null)
    {
        // Old input takes precedence
        $old = old($this->transformKey($name));
        if (! is_null($old)) {
            return $old;
        }

        // Explicit value
        if (! is_null($value)) {
            return $value;
        }

        // Model value
        return $this->getModelValueAttribute($name);
    }

    protected function getModelValueAttribute(string $name)
    {
        if (! $this->model) {
            return null;
        }

        $key = $this->transformKey($name);

        if (is_array($this->model)) {
            return data_get($this->model, $key);
        }

        return data_get($this->model, $key);
    }

    protected function transformKey(string $key): string
    {
        return str_replace(['.', '[]', '[', ']'], ['_', '', '.', ''], $key);
    }

    protected function getIdAttribute(string $name, array $attributes): string
    {
        if (isset($attributes['id'])) {
            return $attributes['id'];
        }

        return str_replace(['.', '[', ']'], ['_', '_', ''], $name);
    }

    protected function attributes(array $attributes): string
    {
        $html = [];

        foreach ($attributes as $key => $value) {
            if (is_numeric($key)) {
                // Boolean attribute like 'required', 'disabled', 'readonly'
                $html[] = $value;
            } elseif ($value === true) {
                $html[] = $key;
            } elseif ($value === false || $value === null) {
                continue;
            } else {
                $html[] = $key.'="'.e($value).'"';
            }
        }

        return count($html) > 0 ? ' '.implode(' ', $html) : '';
    }
}
