<?php 

namespace Tonning\Metabox;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Tonning\Metaboxes\MetaboxBuilder
 */
class MetaboxFacade extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'metabox'; }
}