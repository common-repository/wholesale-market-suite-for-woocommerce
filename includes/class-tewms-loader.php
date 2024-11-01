<?php

/**
 * Register all actions and filters for the plugin
 *
 * @link       https://tekniskera.com/
 * @since      1.0.0
 *
 * @package    Tewms
 * @subpackage Tewms/admin
 */

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    Tewms
 * @subpackage Tewms/admin
 * @author     Teknisk Era  <info@tekniskera.com>
 */
class Tewms_Loader
{

	/**
	 * The array of actions registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $tewms_actions    The actions registered with WordPress to fire when the plugin loads.
	 */
	protected $tewms_actions;

	/**
	 * The array of filters registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $tewms_filters    The filters registered with WordPress to fire when the plugin loads.
	 */
	protected $tewms_filters;

	/**
	 * Initialize the collections used to maintain the actions and filters.
	 *
	 * @since    1.0.0
	 */
	public function __construct()
	{

		$this->tewms_actions = array();
		$this->tewms_filters = array();

	}

	/**
	 * Add a new action to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string               $tewms_hook             The name of the WordPress action that is being registered.
	 * @param    object               $tewms_component        A reference to the instance of the object on which the action is defined.
	 * @param    string              $tewms_callback         The name of the function definition on the $tewms_component.
	 * @param    int                  $tewms_priority         Optional. The priority at which the function should be fired. Default is 10.
	 * @param    int                  $tewms_accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1.
	 */
	public function tewms_add_action($tewms_hook, $tewms_component, $tewms_callback, $tewms_priority = 10, $tewms_accepted_args = 1)
	{
		$this->tewms_actions = $this->tewms_add($this->tewms_actions, $tewms_hook, $tewms_component, $tewms_callback, $tewms_priority, $tewms_accepted_args);
	}

	/**
	 * Add a new filter to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string               $tewms_hook             The name of the WordPress filter that is being registered.
	 * @param    object               $tewms_component        A reference to the instance of the object on which the filter is defined.
	 * @param    string               $tewms_callback        The name of the function definition on the $tewms_component.
	 * @param    int                  $tewms_priority         Optional. The priority at which the function should be fired. Default is 10.
	 * @param    int                  $tewms_accepted_args    Optional. The number of arguments that should be passed to the $tewms_callback. Default is 1
	 */
	public function tewms_add_filter($tewms_hook, $tewms_component, $tewms_callback, $tewms_priority = 10, $tewms_accepted_args = 1)
	{
		$this->tewms_filters = $this->tewms_add($this->tewms_filters, $tewms_hook, $tewms_component, $tewms_callback, $tewms_priority, $tewms_accepted_args);
	}

	/**
	 * A utility function that is used to register the actions and hooks into a single
	 * collection.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param    array                $tewms_hooks            The collection of hooks that is being registered (that is, actions or filters).
	 * @param    string               $tewms_hook             The name of the WordPress filter that is being registered.
	 * @param    object               $tewms_component        A reference to the instance of the object on which the filter is defined.
	 * @param    string               $tewms_callback        The name of the function definition on the $tewms_component.
	 * @param    int                  $tewms_priority         The priority at which the function should be fired.
	 * @param    int                  $tewms_accepted_args    The number of arguments that should be passed to the $tewms_callback.
	 * @return   array                                  The collection of actions and filters registered with WordPress.
	 */
	private function tewms_add($tewms_hooks, $tewms_hook, $tewms_component, $tewms_callback, $tewms_priority, $tewms_accepted_args)
	{

		$tewms_hooks[] = array(
			'hook' => $tewms_hook,
			'component' => $tewms_component,
			'callback' => $tewms_callback,
			'priority' => $tewms_priority,
			'accepted_args' => $tewms_accepted_args
		);

		return $tewms_hooks;

	}

	/**
	 * Register the filters and actions with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function tewms_run()
	{

		foreach ($this->tewms_filters as $tewms_hook) {
			add_filter($tewms_hook['hook'], array($tewms_hook['component'], $tewms_hook['callback']), $tewms_hook['priority'], $tewms_hook['accepted_args']);
		}

		foreach ($this->tewms_actions as $tewms_hook) {
			add_action($tewms_hook['hook'], array($tewms_hook['component'], $tewms_hook['callback']), $tewms_hook['priority'], $tewms_hook['accepted_args']);
		}

	}

}