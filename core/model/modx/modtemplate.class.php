<?php
/**
 * Represents a content element that serves as a resource template.
 *
 * @package modx
 */
class modTemplate extends modElement {
    function modTemplate(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
        $this->_cacheable= false;
    }

    /**
     * Process the template content and return the output.
     *
     * {@inheritdoc}
     */
    function process($properties= null, $content= null) {
        parent :: process($properties, $content);
        // turn the processed properties into placeholders
        if (is_array($this->_properties)) {
            $this->xpdo->toPlaceholders($this->_properties, $this->get('templatename'));
        }

        // get the current revision appropriate for the current request
        if (!is_string($this->_content) || empty($this->_content))
            $this->_content= $this->get('content');

        if (is_string($this->_content) && !empty ($this->_content)) {
            // collect element tags in the content and process them
            $maxIterations= isset ($this->xpdo->config['parser_max_iterations']) ? intval($this->xpdo->config['parser_max_iterations']) : 10;
            $this->xpdo->parser->processElementTags($this->_tag, $this->_content, false, false, '[[', ']]', array(), $maxIterations);
        }
        $this->filterOutput();
        // copy the instance content source to the output buffer
        $this->_output= $this->_content;
        $this->_processed= true;
        return $this->_output;
    }

    /**
     * Gets a collection of objects related by aggregate or composite relations.
     *
     * {@inheritdoc}
     *
     * Includes special handling for related objects with alias {@link
     * modTemplateVar}, respecting framework security unless specific criteria
     * are provided.
     */
    function getMany($class, $criteria= null, $cacheFlag= false) {
        $collection= array ();
        if ($class === 'modTemplateVar' && ($criteria === null || strtolower($criteria) === 'all')) {
            $c = $this->xpdo->newQuery('modTemplateVar');
            $c->select('
                DISTINCT modTemplateVar.*,
                modTemplateVar.default_text AS value');
            $c->innerJoin('modTemplateVarTemplate','tvtpl',array(
                '`tvtpl`.`tmplvarid` = `modTemplateVar`.`id`',
                '`tvtpl`.templateid' => $this->id,
            ));
            $c->sortby('`tvtpl`.`rank`,`modTemplateVar`.`rank`');
            
            $collection = $this->xpdo->getCollection('modTemplateVar', $c);
        } else {
            $collection= parent :: getMany($class, $criteria);
        }
        return $collection;
    }

	/**
	 * Grabs an array of Template Variables associated with this Template,
	 * bypassing the many-to-many relationship.
	 *
	 * @return array An array of TVs.
	 */
	function getTVs() {
		$c = $this->xpdo->newQuery('modTemplateVarTemplate');
		$c->where(array('templateid' => $this->id));
		$c->sortby('rank','ASC');
		$tvts = $this->xpdo->getCollection('modTemplateVarTemplate',$c);
		$tvs = array();
		foreach ($tvts as $tvt) {
			$tv = $tvt->getOne('modTemplateVar');
			if ($tv != NULL) {
				$tv->category = $tv->getOne('modCategory');
				$tvs[$tvt->tmplvarid] = $tv;
			}
		}
		return $tvs;
	}
}