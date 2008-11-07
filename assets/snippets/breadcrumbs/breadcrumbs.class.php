<?php
/**
 * BreadCrumbs
 *
 * @package breadcrumbs
 */
/**
 * BreadCrumbs Class
 *
 * @package breadcrumbs
 */
class BreadCrumbs {
    /**
     * @var array $_crumbs An array of crumbs stored so far.
     * @access private
     */
	var $_crumbs;

    /**#@+
     * The BreadCrumbs constructor.
     *
     * @param modX $modx A reference to the modX constructor.
     * @param array $config A configuration array.
     */
	function BreadCrumbs(&$modx,$config) {
		$this->__construct($modx,$config);
	}
    /** @ignore */
	function __construct(&$modx,$config) {
		$this->modx =& $modx;
		$this->config = $config;
		$this->_crumbs = array();
	}
    /**#@-*/

    /**
     * Show the current resource's breadcrumbs.
     *
     * @access public
     * @param modResource $resource The resource to load.
     */
	function showCurrentPage($resource) {
		// show current page, as link or not
		if ($this->config['showCurrentCrumb']) {

			$titleToShow = $resource->get($this->config['titleField'])
				? $resource->get($this->config['titleField'])
				: $resource->pagetitle;

			if ($this->config['currentAsLink'] && (!$this->config['respectHidemenu'] || ($this->config['respectHidemenu'] && $resource->hidemenu != 1 ))) {

				$descriptionToUse = ($resource->get($this->config['descField']))
					? $resource->get($this->config['descField'])
					: $resource->pagetitle;
				$this->_crumbs[] = '<a class="B_currentCrumb" href="[[~'.$this->modx->resource->id.']]" title="'.$descriptionToUse.'">'.$titleToShow.'</a>';

			} else {
				$this->_crumbs[] = '<span class="B_currentCrumb">'.$resource->pagetitle.'</span>';
			}
		}
	}

    /**
     * Get the mediary crumbs for an object.
     *
     * @access public
     * @param integer $resource_id The ID of the resource to pull from.
     * @param integer &$count
     */
	function getMiddleCrumbs($resource_id,&$count) {
		// insert '...' if maximum number of crumbs exceded
		if ($count >= $this->config['maxCrumbs']) {
			$this->_crumbs[] = '<span class="B_hideCrumb">...</span>';
			return false;
		}

		$wa = array(
			'id' => $resource_id,
		);
		if (!$this->config['pathThruUnPub']) {
			$wa['published'] = 1;
			$wa['deleted'] = 0;
		}
		$parent = $this->modx->getObject('modResource',$wa);

		if ($parent->get('id') != $this->modx->config['site_start']) {
			if (!$this->config['respectHidemenu'] || ($this->config['respectHidemenu'] && $parent->get('hidemenu') != 1)) {
				$titleToShow = $parent->get($this->config['titleField'])
					? $parent->get($this->config['titleField'])
					: $parent->get('pagetitle');
				$descriptionToUse = $parent->get($this->config['descField'])
					? $parent->get($this->config['descField'])
					: $parent->get('pagetitle');

				$this->_crumbs[] = '<a class="B_crumb" href="[[~'.$parent->get('id').']]" title="'.$descriptionToUse.'">'.$titleToShow.'</a>';
			}
		} // end if

		$count++;
		if ($parent->get('parent') != 0) {
			$this->getMiddleCrumbs($parent->get('parent'),$count);
		}
	}

    /**
     * Render the breadcrumbs.
     *
     * @access public
     */
	function load() {
		if ($this->config['showCrumbsAtHome']
		|| ($this->modx->documentIdentifier == $this->modx->config['site_start'])) return false;

		// get current resource parent info
		$resource = $this->modx->resource;

		// assemble intermediate crumbs
		$crumbCount = 0;
		$this->getMiddleCrumbs($resource->id,$crumbCount);

		// add home link if desired
		if ($this->config['showHomeCrumb'] && ($this->modx->resource->id != $this->modx->config['site_start'])) {
			$this->_crumbs[] = '<a class="B_homeCrumb" href="[[~'.$this->modx->config['site_start'].']]" title="'.$this->config['homeCrumbDescription'].'">'.$this->config['homeCrumbTitle'].'</a>';
		}

		$this->_crumbs = array_reverse($this->_crumbs);
		$this->_crumbs[0] = '<span class="B_firstCrumb">'.$this->_crumbs[0].'</span>';
		$this->_crumbs[count($this->_crumbs)-1] = '<span class="B_lastCrumb">'.$this->_crumbs[count($this->_crumbs)-1].'</span>';

		return '<span class="B_crumbBox">'. join($this->_crumbs, ' '.$this->config['crumbSeparator'].' ').'</span>';
	}
}