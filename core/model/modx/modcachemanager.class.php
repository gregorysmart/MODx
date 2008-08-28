<?php
/**
 * Contains the xPDOCacheManager implementation for MODx.
 * @package modx
 */

/**
 * The default xPDOCacheManager instance for MODx.
 *
 * Through this class, MODx provides several types of default, file-based
 * caching to reduce load and dependencies on the database, including:
 * <ul>
 * <li>partial modResource caching, which stores the object properties,
 * along with individual modElement cache items</li>
 * <li>full caching of modContext and modSystemSetting data</li>
 * <li>object-level caching</li>
 * <li>db query-level caching</li>
 * <li>optional JSON object caching for increased Ajax performance
 * possibilities</li>
 * </ul>
 *
 * @package modx
 */
class modCacheManager extends xPDOCacheManager {
    var $modx= null;

    function modCacheManager(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
        $this->modx= & $xpdo;
    }

    /**
     * Generates a cache file for a MODx site Context.
     *
     * Context cache files can override site configuration settings and are
     * responsible for loading the various lisitings and maps in the modX class,
     * including documentListing, documentMap, and eventMap.  It can also be
     * used to setup or transform any other modX properties.
     *
     * @todo Refactor the generation of documentMap, aliasMap, and
     * resourceListing so it uses less memory/file size.
     *
     * @param modContext $obj  The modContext instance to be cached.
     * @return boolean true if the modContext is successfully cached.
     */
    function generateContext($key) {
        $written= false;
        $obj= $this->modx->getObject('modContext', $key, true);
        if (is_object($obj) && is_a($obj, 'modContext') && $obj->get('key')) {
            $fileName= $this->modx->getCachePath() . $obj->getCacheFileName();
            $content= "<?php \n";

            $contextConfig= $this->modx->config;

            // generate the ContextSettings
            $content .= "\$this->config= array();\n";
            if ($settings= $obj->getMany('modContextSetting')) {
                foreach ($settings as $setting) {
                    $k= $setting->get('key');
                    $v= $setting->get('value');
                    $matches = array();
                    if (preg_match_all('~\{(.*?)\}~', $v, $matches, PREG_SET_ORDER)) {
                        $matchValue= '';
                        foreach ($matches as $match) {
                            if (isset ($this->modx->config["{$match[1]}"])) {
                                $matchValue= $this->modx->config["{$match[1]}"];
                            } else {
                                $matchValue= '';
                            }
                            $v= str_replace($match[0], $matchValue, $v);
                        }
                    }
                    $content .= "\$this->config['{$k}']= " . var_export($v, true) . ";\n";
                    $contextConfig["{$k}"]= $v;
                }
            }

            // generate the documentMap, aliasMap, and resourceListing
            $tblResource= $this->modx->getTableName('modResource');
            $resourceFields= 'id,parent,alias,isfolder,content_type';
            if (isset ($contextConfig['cache_context_resourceFields']) && $contextConfig['cache_context_resourceFields']) {
                $resourceFields= $contextConfig['cache_context_resourceFields'];
            }
            $resourceCols= $this->modx->getSelectColumns('modResource', '', '', explode(',', $resourceFields));
            $bindings= array (':context_key' => array('value' => $obj->get('key'), 'type' => PDO_PARAM_STR));
            $criteria= new xPDOCriteria($this->modx, "SELECT {$resourceCols} FROM {$tblResource} WHERE (`context_key` = :context_key OR `context_key` IS NULL) AND `deleted` = 0 ORDER BY `parent` ASC, `menuindex` ASC", $bindings, false);
            if (!$collContentTypes= $this->modx->getCollection('modContentType')) {
                $htmlContentType= $this->modx->newObject('modContentType');
                $htmlContentType->set('name', 'HTML');
                $htmlContentType->set('description', 'HTML content');
                $htmlContentType->set('mime_type', 'text/html');
                $htmlContentType->set('file_extensions', 'html,htm');
                $collContentTypes['1']= $htmlContentType;
            }
            $collResources= array();
            if ($criteria->prepare() && $criteria->stmt->execute()) {
                $collResources= $criteria->stmt->fetchAll(PDO_FETCH_OBJ);
            }
            if ($collResources) {
                $content .= "\$this->resourceMap= array ();\n";
                $content .= "\$this->resourceListing= array ();\n";
                $content .= "\$this->aliasMap= array ();\n";
//                if (defined('MODX_COMPATMODE') && MODX_COMPATMODE == '0.9.5')
                    $content .= "\$this->documentMap= array ();\n";
//                if (defined('MODX_COMPATMODE') && MODX_COMPATMODE == '0.9.5')
                    $content .= "\$this->documentListing= & \$this->resourceListing;\n";
                $containerSuffix= isset ($contextConfig['container_suffix']) ? $contextConfig['container_suffix'] : '';
                if (!empty ($collResources)) {
                    $localMap= array ();
                    foreach ($collResources as $r) {
                        $parentId= isset($r->parent) ? strval($r->parent) : "0";
//                        if (defined('MODX_COMPATMODE') && MODX_COMPATMODE == '0.9.5')
                            $content .= "\$this->documentMap[]= array('{$parentId}' => '" . $r->id . "');\n";
                        $content .= "\$this->resourceMap['{$parentId}'][]= " . $r->id . ";\n";
                        $resourceValues= get_object_vars($r);
                        $content .= "\$this->resourceListing['" . $r->id . "']= " . var_export($resourceValues, true) . ";\n";
                        $resAlias= '';
                        $resPath= '';
                        $contentType= isset ($collContentTypes[$r->content_type]) ? $collContentTypes[$r->content_type] : $collContentTypes['1'];
                        if ((isset ($obj->config['friendly_urls']) && $obj->config['friendly_urls']) || $contextConfig['friendly_urls']) {
                            if ((isset ($obj->config['friendly_alias_urls']) && $obj->config['friendly_alias_urls']) || $contextConfig['friendly_alias_urls']) {
                                $resAlias= $r->alias;
                                if (empty ($resAlias)) $resAlias= $r->id;
                                $parentResource= '';
                                if ((isset ($obj->config['use_alias_path']) && $obj->config['use_alias_path'] == 1) || $contextConfig['use_alias_path']) {
                                    $pathParentId= $parentId;
                                    $parentResources= array ();
                                    $currResource= $r;
                                    $parentSql= "SELECT {$resourceCols} FROM {$tblResource} WHERE `id` = :parent LIMIT 1";
                                    $hasParent= (boolean) $pathParentId;
                                    if ($hasParent) {
                                        if ($parentStmt= $this->modx->prepare($parentSql)) {
                                            $parentStmt->bindParam(':parent', $pathParentId);
                                            if ($parentStmt->execute()) {
                                                while ($hasParent && $currResource= $parentStmt->fetch(PDO_FETCH_OBJ)) {
                                                    $parentAlias= $currResource->alias;
                                                    if (empty ($parentAlias))
                                                        $parentAlias= "{$pathParentId}";
                                                    $parentResources[]= "{$parentAlias}";
                                                    $pathParentId= $currResource->parent;
                                                    $hasParent= ($pathParentId > 0 && $parentStmt->execute());
                                                }
                                            }
                                        }
                                    }
                                    $resPath= !empty ($parentResources) ? implode('/', array_reverse($parentResources)) : '';
                                }
                            } else {
                                $resAlias= $r->id;
                            }
                            if (!empty($containerSuffix) && $r->isfolder) {
                                $resourceExt= $containerSuffix;
                            } else {
                                $resourceExt= $contentType->getExtension();
                            }
                            if (!empty($resourceExt)) {
                                $resAlias .= $resourceExt;
                            }
                        } else {
                            $resAlias= $r->id;
                        }
                        $content .= "\$this->resourceListing['" . $r->id . "']['path']= '{$resPath}';\n";
                        if (!empty ($resPath)) {
                            $resPath .= '/';
                        }
                        if (isset ($localMap[$resPath . $resAlias])) {
                            $this->modx->log(XPDO_LOG_LEVEL_ERROR, "Resource alias {$resPath}{$resAlias} already exists for resource id = {$localMap[$resPath . $resAlias]}; skipping duplicate resource alias for resource id = {$r->id}");
                            continue;
                        }
                        $localMap[$resPath . $resAlias]= $r->id;
                        $content .= "\$this->aliasMap['{$resPath}{$resAlias}']= " . $r->id . ";\n";
                    }
                }
            }

            // generate the eventMap and pluginCache
            $eventMap= $this->modx->getEventMap($obj->get('key'));
            if (is_array ($eventMap)) {
                $content .= "\$this->eventMap= " . var_export($eventMap, true) . ";\n";
                if ($eventMap) {
                    $pluginIds= array ();
                    $this->modx->loadClass('modScript');
                    foreach ($eventMap as $pluginKeys) {
                        foreach ($pluginKeys as $pluginKey) {
                            if (isset ($pluginIds[$pluginKey])) {
                                continue;
                            }
                            $pluginIds[$pluginKey]= $pluginKey;
                            $plugins[$pluginKey]= $this->modx->getObject('modPlugin', $pluginKey, true);
                        }
                    }
                    if (!empty ($plugins)) {
                        foreach ($plugins as $pluginId => $plugin) {
                            if (!is_object($plugin)) {
                                continue;
                            }
                            $pluginName= $plugin->getScriptName();
                            $content .= $this->generateObject($plugin, $pluginName);
                            $content .= "\$this->pluginCache[" . $pluginId . "]= & \${$pluginName};\n";
                        }
                    }
                }
            }
            $written= $this->writeFile($fileName, $content);
        }
        return $written;
    }

    /**
     * Generates a cache file for the a MODx site.
     *
     * @param Resource $obj  The Resource instance to be cached.
     * @return string  The cache filename.
     */
    function generateConfig() {
        $fileName= $this->modx->getCachePath() . "config.cache.php";
        $content= "<?php \n";
        $content .= "\$this->config= is_array(\$this->config) ? \$this->config : array();\n";
        if ($collection= $this->modx->getCollection('modSystemSetting')) {
            foreach ($collection as $setting) {
                $k= $setting->get('key');
                $v= $setting->get('value');
                $matches= array();
                if (preg_match_all('~\{(.*?)\}~', $v, $matches, PREG_SET_ORDER)) {
                    $matchValue= '';
                    foreach ($matches as $match) {
                        if (isset ($this->modx->config["{$match[1]}"])) {
                            $matchValue= $this->modx->config["{$match[1]}"];
                        } else {
                            $matchValue= '';
                        }
                        $v= str_replace($match[0], $matchValue, $v);
                    }
                }
                $content .= "\$this->config['{$k}']= " . var_export($v, true) . ";\n";
            }
        }
        $written= $this->writeFile($fileName, $content);
        return $fileName;
    }

    /**
     * Generates a cache file for a Resource or Resource-derived object.
     *
     * Resource classes can define their own cacheFileName.
     *
     * @param modResource $obj  The Resource instance to be cached.
     * @return boolean  True if the cache file was successfully written.
     */
    function generateResource($obj) {
        $written= false;
        if (isset ($this->modx->config['cache_disabled']) && $this->modx->config['cache_disabled']) {
            return false;
        }
        if (isset ($this->modx->config['cache_resource']) && $this->modx->config['cache_resource']) {
            if (is_object($obj) && is_a($obj, 'modResource') && $obj->_processed && $obj->get('cacheable')) {
                $fileName= $this->modx->getCachePath() . $obj->getCacheFileName();
                $objArray= $obj->toArray('', true);
                $content= "<?php \n";
                $content .= "\$resource= \$this->modx->newObject('" . $obj->_class . "');\n";
                $content .= "\$resource->fromArray(" . var_export($objArray, true) . ", '', true, true);\n";
                $content .= "\$resource->_content= " . var_export($obj->_content, true) . ";\n";
                $content .= "\$resource->_processed= " . ($obj->_processed ? 'true' : 'false') . ";\n";
                //TODO: remove legacy docGroups and cache ABAC policies instead
                if ($docGroups= $obj->getMany('modResourceGroupResource')) {
                    $content.= "\$docGroups= array ();\n";
                    foreach ($docGroups as $docGroup) {
                        $content.= $this->generateObject($docGroup, 'docGroup', false, false, 'this');
                        $content.= "\$docGroups[]= \$docGroup;\n";
                    }
                    $content.= "\$resource->addMany(\$docGroups);\n";
                }
                if (is_array($this->modx->elementCache)) {
                    foreach ($this->modx->elementCache as $tag => $out) {
                        $content .= "\$this->modx->elementCache['" .$tag . "']= " . var_export($out, true) . ";\n";
                    }
                }
                $written= $this->writeFile($fileName, $content);
            }
        }
        return $written;
    }

    function generateLexiconCache($namespace = 'core',$focus = 'default',$language = 'en') {
    	$written= false;

        $namespace = $this->modx->getObject('modNamespace',$namespace);
        if ($namespace == null) return false;

        $focus = $this->modx->getObject('modLexiconFocus',array(
            'namespace' => $namespace->name,
            'name' => $focus,
        ));
        if ($focus == null) return false;

        $fileName = $this->modx->getCachePath().'lexicon/'.$language.'/'.$namespace->name.'/'.$focus->name.'.cache.php';

        $content= "<?php \n";
        $c= $this->modx->newQuery('modLexiconEntry');
        $c= $c->where(array(
            'focus' => $focus->name,
            'language' => $this->modx->config['manager_language'],
        ));
        $c= $c->sortby('name','ASC');
        $entries= $this->modx->getCollection('modLexiconEntry',$c);

        foreach ($entries as $entry) {
        	$v = str_replace("'","\'",$entry->value);
            $content .= '$_lang[\''.$entry->name.'\'] = \''.$v.'\';'."\n";
        }

        $written= $this->writeFile($fileName, $content);
        return $written;
    }

	 /**
     * Generates a cache file for the manager actions.
     *
     * @access public
     * @param modResource $obj  The Actions
     * @return boolean  True if the cache file was successfully written.
     */
    function generateActionMap($fileName) {
        $written= false;
		$c = $this->modx->newQuery('modAction');
		$c->sortby('context_key,controller','ASC');
		$actions = $this->modx->getCollection('modAction',$c);

		$content = "<?php \n";
		$content .= " \$this->modx->actionMap = array(";
		foreach ($actions as $action) {
			$objArray = $action->toArray('',true);
            $ctx = $action->getOne('Context');
            if ($ctx != null && $ctx->get('key') != 'mgr') {
                $bp = $ctx->getOne('modContextSetting',array(
                    'key' => $ctx->get('key').'.base_path',
                ));
                $bu = $ctx->getOne('modContextSetting',array(
                    'key' => $ctx->get('key').'.base_url',
                ));
                $objArray['context'] = $ctx->get('key');
                if ($bp != null && $bu != null) {
                    $objArray['context_path'] = $this->modx->config['base_path'].$bp->value;
                    $objArray['context_url'] = $this->modx->config['base_url'].$bu->value;
                } else {
                    $objArray['context_path'] = $this->modx->config['manager_path'];
                    $objArray['context_url'] = $this->modx->config['manager_url'];
                }
            } else {
                $objArray['context'] = 'mgr';
                $objArray['context_path'] = $this->modx->config['manager_path'];
                $objArray['context_url'] = $this->modx->config['manager_url'];
            }

			$content .= '"'.$action->id.'" => '.var_export($objArray, true).",\n";
		}
		$content .= ");";
		$written= $this->writeFile($fileName, $content);
        return $written;
    }

    /**
     * Generates a file representing an executable modScript function.
     *
     * @param modScript $objElement A {@link modScript} instance to generate the
     * script file for.
     * @param string $objContent Optional script content to override the
     * persistent instance.
     * @param boolean $returnFunction Indicates if the function should be
     * returned as content rather than written to file.
     * @return boolean|string true if the file is successfully written, or the
     * actual generated content of the function if $returnFunction is true;
     * false otherwise.
     */
    function generateScriptFile($objElement, $objContent= null, $returnFunction= false) {
        $written= false;
        if (is_object($objElement) && is_a($objElement, 'modScript')) {
            $className= strtolower($objElement->_class);
            switch ($className) {
                case 'modsnippet':
                    $scriptContent= $objElement->_fields['snippet'];
                    break;
                case 'modplugin':
                    $scriptContent= $objElement->_fields['plugincode'];
                    break;
                case 'modmodule':
                    $scriptContent= $objElement->_fields['modulecode'];
                    break;
                default:
                    return false;
                    break;
            }
            $fileName= $objElement->getScriptFileName();
            $scriptName= $objElement->getScriptName();

            $content= '';
            if (!$returnFunction) $content.= "<?php \n";
            $content .= "function {$scriptName}(\$scriptProperties= array()) {\n";
            $content .= "global \$modx;\n";
            $content .= "if (is_array(\$scriptProperties)) {\n";
            $content .= "extract(\$scriptProperties, EXTR_SKIP);\n";
            $content .= "}\n";
            $content .= $scriptContent . "\n";
            $content .= "}\n";
            if ($returnFunction) {
                return $content;
            }
            $written= $this->writeFile($fileName, $content);
        }
        return $written;
    }

    /**
     * Clear part or all of the MODx cache.
     *
     * @param array $paths An optional array of paths, relative to the cachePath, to be deleted.
     * @param array $options An optional associative array of cache clearing options: <ul>
     * <li><strong>objects</strong>: an array of objects or paths to flush from the db object cache</li>
     * <li><strong>extensions</strong>: an array of file extensions to match when deleting the cache directories</li>
     * </ul>
     */
    function clearCache($paths= array(), $options= array('objects' => '*')) {
        $results= array();
        $delObjs= array();
        if (isset($options['objects'])) {
            // clear object cache by key, or * = flush entire object cache
            if (is_array($options['objects'])) {
                foreach ($options['objects'] as $key) {
                    if ($this->delete($key))
                        $delObjs[]= $key;
                }
            }
            elseif (is_string($options['objects']) && $options['objects'] == '*') {
                $delObjs= $this->clean();
            }
        }
        $results['deleted_objects']= $delObjs;
        if (!isset($options['extensions']) || empty($options['extensions'])) {
            $extensions= array('.cache.php');
        } else {
            $extensions= $options['extensions'];
        }
        if (empty($paths)) {
            $paths= array('');
        }
        $delFiles= array();
        while (list($pathIdx, $path)= each($paths)) {
            $deleted= false;
            $abspath= $this->modx->cachePath . $path;
            if (file_exists($abspath)) {
                if (is_dir($abspath)) {
                    $deleted= $this->deleteTree($abspath, false, true, $extensions);
                } else {
                    if (@unlink($abspath)) {
                        $deleted= array($path);
                    }
                }
                if (is_array($deleted))
                    $delFiles= $delFiles + $deleted;
            }
            if ($path == '') break;
        }
        $results['deleted_files']= $delFiles;
        $results['deleted_files_count']= count($delFiles);

        $publishingResults= array();
        if (isset($options['publishing']) && $options['publishing']) {
            // publish and unpublish resources using pub_date and unpub_date checks
            $rows_pub = $this->modx->getCollection('modResource',array(
                'pub_date:!=' => 0,
                'pub_date:<' => time(),
            ));
            foreach ($rows_pub as $r) {
                $r->set('published',1);
                $r->set('pub_date',0);
                $r->save();
            }
            $rows_unpub = $this->modx->getCollection('modResource',array(
                'unpub_date:!=' => 0,
                'unpub_date:<' => time(),
            ));
            foreach ($rows_unpub as $r) {
                $r->set('published',0);
                $r->set('unpub_date',0);
                $r->save();
            }
            $publishingResults['published']= count($rows_pub);
            $publishingResults['unpublished']= count($rows_unpub);

            // update publish time file
            $timesArr= array ();
            $minpub= 0;
            $minunpub= 0;
            $sql= "SELECT MIN(`pub_date`) FROM " . $this->modx->getTableName('modResource') . " WHERE `pub_date` > ?";
            $stmt= $this->modx->prepare($sql);
            if ($stmt) {
                $stmt->bindValue(1, time());
                if ($stmt->execute()) {
                    foreach ($stmt->fetchAll(PDO_FETCH_NUM) as $value) {
                        $minpub= $value[0];
                        unset($value);
                        break;
                    }
                } else {
                    $publishingResults['errors'][]= sprintf($this->modx->lexicon('cache_publish_event_error'),$stmt->errorInfo());
                }
            }
            else {
                $publishingResults['errors'][]= sprintf($this->modx->lexicon('cache_publish_event_error'),$sql);
            }
            if ($minpub) $timesArr[]= $minpub;

            $sql= "SELECT MIN(`unpub_date`) FROM " . $this->modx->getTableName('modResource') . " WHERE `unpub_date` > ?";
            $stmt= $this->modx->prepare($sql);
            if ($stmt) {
                $stmt->bindValue(1, time());
                if ($stmt->execute()) {
                    foreach ($stmt->fetchAll(PDO_FETCH_NUM) as $value) {
                        $minunpub= $value[0];
                        unset($value);
                        break;
                    }
                } else {
                    $publishingResults['errors'][]= sprintf($this->modx->lexicon('cache_unpublish_event_error'), $stmt->errorInfo());
                }
            } else {
                $publishingResults['errors'][]= sprintf($this->modx->lexicon('cache_unpublish_event_error'), $sql);
            }
            if ($minunpub) $timesArr[]= $minunpub;

            if (count($timesArr) > 0) {
                $nextevent= min($timesArr);
            } else {
                $nextevent= "0";
            }

            // write the file
            $filename= $this->modx->cachePath . 'sitePublishing.idx.php';
            $somecontent= "<?php \$cacheRefreshTime={$nextevent};";
            if (!$this->writeFile($filename, $somecontent)) {
                $publishingResults['errors'][]= $this->modx->lexicon('cache_sitepublishing_file_error');
            }
            $results['publishing']= $publishingResults;
        }

        // invoke OnCacheUpdate event
        $this->modx->invokeEvent('OnCacheUpdate', array(
            'results' => $results,
        ));

        return $results;
    }
}
?>