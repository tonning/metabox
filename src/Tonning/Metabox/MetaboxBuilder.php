<?php

namespace Tonning\Metabox;

use App\Media;
use Collective\Html\FormBuilder;
use Collective\Html\HtmlBuilder;

class MetaboxBuilder extends FormBuilder {

	public $model;

	public function withModel($model)
	{
		$this->setModel($model);
	}

	/**
	 * Generate a standard metabox
	 *
	 * @param $title
	 * @param array $fields
	 * @param bool $header
	 * @param bool $solid
	 * @return string
	 */
	public function standard($title, $fields = array(), $header = true, $solid = false)
	{
		return $this->box($title, null, $fields, $header, $solid);
	}

	/**
	 * Generate a info metabox
	 *
	 * @param $title
	 * @param array $fields
	 * @param bool $header
	 * @param bool $solid
	 * @return string
	 */
	public function info($title, $fields = array(), $header = true, $solid = false)
	{
		return $this->box($title, 'info', $fields, $header, $solid);
	}

	/**
	 * Generate a success metabox
	 *
	 * @param $title
	 * @param array $fields
	 * @param bool $header
	 * @param bool $solid
	 * @return string
	 */
	public function success($title, $fields = array(), $header = true, $solid = false)
	{
		return $this->box($title, 'success', $fields, $header, $solid);
	}

	/**
	 * Generate a warning metabox
	 *
	 * @param $title
	 * @param array $fields
	 * @param bool $header
	 * @param bool $solid
	 * @return string
	 */
	public function warning($title, $fields = array(), $header = true, $solid = false)
	{
		return $this->box($title, 'warning', $fields, $header, $solid);
	}

	/**
	 * Generate a danger metabox
	 *
	 * @param $title
	 * @param array $fields
	 * @param bool $header
	 * @param bool $solid
	 * @return string
	 */
	public function danger($title, $fields = array(), $header = true, $solid = false)
	{
		return $this->box($title, 'danger', $fields, $header, $solid);
	}


	/**
	 * Build up the box content
	 *
	 * @param $title
	 * @param null $type
	 * @param array $fields
	 * @param bool $header
	 * @param bool $solid
	 * @return mixed
	 */
	public function box($title, $type = null, $fields = array(), $header = true, $solid = false)
	{
		$content = '';

		if ($this->getModel())
			$meta = $this->model->meta;

		foreach ($fields as $name => $field) {
			$options = (isset($field['options'])) ? $field['options'] : null;

			if ($field['type'] != 'heading') {

				$label = (isset($field['label'])) ? $field['label'] : null;
				$value = (isset($meta[$name])) ? $meta[$name] : '';

				$content .= '<div class="form-group">';
					if ($label)
						$content .= $this->make('heading', $label, null, array());
					$content .= $this->make($field['type'], $name, $value, $options);
				$content .= '</div>';

			// Type: Heading
			} else {
				$content .= $this->make($field['type'], $field['title'], null, $options);
			}

		}

		return $this->makeMetabox($title, $content, $type, $header, $solid);
	}

	/**
	 * Make the given type of metabox
	 *
	 * @param $type
	 * @param $name
	 * @param $value
	 * @param array $options
	 * @return mixed
	 */
	private function make($type, $name, $value, $options = array())
	{
		return $this->$type($name, $value, $options);
	}


	/**
	 * Wrap the metabox content in the container
	 *
	 * @param $title
	 * @param $content
	 * @param null $type
	 * @param bool $header
	 * @param bool $solid
	 * @return string
	 */
	public function makeMetabox($title, $content, $type = null, $header = true, $solid = false)
	{
		$boxType = ($type) ? ' box-' . $type : null;
		$boxSolid = ($solid) ? ' box-solid' : null;

		$box = '<div class="box' . $boxType . $boxSolid . '">';

		if ($header) {
			$box .= '<div class="box-header with-borders"><h3 class="box-title">' . $title . '</h3>' .
						'<div class="box-tools pull-right">' .
            				'<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>' .
						'</div>' .
					'</div>';
		}

		$box .= '<div class="box-body">' .
					$content .
				'</div>' .
			'</div>';

		return $box;
	}

	/**
	 * Create a textarea input field.
	 *
	 * @param  string  $name
	 * @param  string  $value
	 * @param  array   $options
	 * @return string
	 */
	public function editor($name, $value = null, $options = array())
	{
		if ( ! isset($options['name'])) $options['name'] = $name;

		// Next we will look for the rows and cols attributes, as each of these are put
		// on the textarea element definition. If they are not present, we will just
		// assume some sane default values for these attributes for the developer.
		$options = $this->setTextAreaSize($options);

		$options['id'] = $this->getIdAttribute($name, $options);

		$value = (string) $this->getValueAttribute($name, $value);

		unset($options['size']);

		// Next we will convert the attributes into a string form. Also we have removed
		// the size attribute, as it was merely a short-cut for the rows and cols on
		// the element. Then we'll create the final textarea elements HTML for us.
		$options = $this->html->attributes($options);

		return '<textarea' . $options . '>' . e($value) . '</textarea>' .
		       '<script>CKEDITOR.replace( "' . $name .'" );</script>';
	}

	/**
	 * Select a media file
	 *
	 * @param $name
	 * @param $value
	 * @param array $options
	 * @return string
	 */
	public function media($name, $value, $options = array())
	{
		$media = new Media();
		$src = ($file = $media->find($value)) ? $thumbnailUrl = $media->getThumbnail($file) : '';
		$value = (isset($thumbnailUrl)) ? $value : '';

		$thumbnail = '<div class="media-manager-image-preview-container">' .
			'<div id="' . $name . '" class="media-manager-image-preview">' .
				'<div class="image-container" v-on="click: openMediaManagerModal">' .
					'<img v-attr="src: thumbnail" data-src="' . $src . '" height="150" width="150">' .
				'</div>' .
				$this->hidden($name, $value, array('data-value' => $value, 'v-attr' => 'value: id')) .
			'<p class="btn btn-default btn-sm pull-right remove-thumbnail" v-on="click: remove()"><i class="fa fa-remove"></i></p>' .
			'</div>' .
			'<div class="clearfix"></div>' .
		'</div>';

		return $thumbnail;
	}

	public function heading($content, $value, $options = array())
	{
		$class = (isset($options['class'])) ? 'bg-' . $options['class'] : 'bg-gray';

		return '<div class="box-header heading ' . $class . '"><h5>' . $content . '</h5></div>';
	}

	public function currency($name, $value, $options = array())
	{
		$field = '<div class="input-group col-xs-12 col-sm-6 col-md-3">' .
                	'<span class="input-group-addon">Kr.</span>' .
					$this->text('price', null, $options) .
					'<span class="input-group-addon">,00</span>' .
				'</div>';

		return $field;
	}

	public function link($name, $value, $options = array())
    {
        return '<div class="input-group col-xs-12">' .
            $this->text('link', $value, $options) .
            '<span class="input-group-addon"><i class="fa fa-external-link"></i></span>' .
            '</div>';
    }

	/**
	 * @return mixed
	 */
	public function getModel()
	{
		return $this->model;
	}

	/**
	 * @param mixed $model
	 */
	public function setModel($model)
	{
		$this->model = $model;
	}
}
