<?php
/**
 * Represents a template variable element.
 *
 * @todo Refactor this to allow user-defined and configured input and output
 * widgets.
 * @package modx
 */
class modTemplateVar extends modElement {
    /**
	 * @var array Supported bindings for MODx
	 */
	var $bindings= array (
        'FILE',
        'CHUNK',
        'DOCUMENT',
        'SELECT',
        'EVAL',
        'INHERIT',
        'DIRECTORY'
    );

    function modTemplateVar(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
        $this->_token = '*';
    }

    /**
     * Process the template variable and return the output.
     *
     * {@inheritdoc}
     */
    function process($properties= null, $content= null) {
        parent :: process($properties, $content);
        if ($this->_cacheable && isset ($this->xpdo->elementCache[$this->_tag])) {
            $this->_output= $this->xpdo->elementCache[$this->_tag];
        } else {
            if (!is_string($this->_content) || empty($this->_content)) {
                $this->_content= $this->renderOutput($this->xpdo->resourceIdentifier);
            }
            if (is_string($this->_content) && !empty ($this->_content)) {
                // collect element tags in the content and process them
                $maxIterations= isset ($this->xpdo->config['parser_max_iterations']) ? intval($this->xpdo->config['parser_max_iterations']) : 10;
                $this->xpdo->parser->processElementTags($this->_tag, $this->_content, false, false, '[[', ']]', array(), $maxIterations);
            }

            // apply output filtering
            $this->filterOutput();

            // copy the content source to the output buffer
            $this->_output= $this->_content;

            // cache the content
            $this->cache();
        }
        $this->_processed= true;
        // finally, return the processed element content
        return $this->_output;
    }

    /**
     * Get the value of a template variable for a resource.
     *
     * @param integer $documentId The id of the resource; 0 defaults to the
     * current resource.
     * @return mixed The raw value of the template variable in context of the
     * specified (or current) resource.
     */
    function getValue($documentId= 0) {
        $value= null;
        if ($documentId) {
            if ($documentId === $this->xpdo->resourceIdentifier && isset ($this->xpdo->documentObject[$this->get('name')]) && is_array($this->xpdo->documentObject[$this->get('name')])) {
                $value= $this->xpdo->documentObject[$this->get('name')][1];
            } else {
                $document = $this->xpdo->getObject('modTemplateVarResource',array(
                    'tmplvarid' => $this->get('id'),
                    'contentid' => $documentId,
                ),true);
                if ($document != null) {
                    $value= $document->get('value');
                }
            }
        }
        if ($value === null) {
            $value= $this->get('default_text');
        }
        return $value;
    }

    /**
     * Set the value of a template variable for a resource.
     *
     * @param integer $documentId The id of the resource; 0 defaults to the
     * current resource.
     * @param mixed $value The value to give the template variable for the
     * specified document.
     */
    function setValue($documentId= 0, $value= null) {
        $oldValue= '';
        if (intval($documentId)) {
            $bindings= array (
                ':tv_id' => $this->get('id'),
                ':document_id' => $documentId,
            );
            $tvd = $this->xpdo->getObject('modTemplateVarResource',array(
                'tmplvarid' => $this->get('id'),
                'contentid' => $documentId,
            ),true);
                        
            if (!$tvd) {
                $tvd= $this->xpdo->newObject('modTemplateVarResource');
            }
            if ($value !== $this->get('default_text')) {
                if (!$tvd->_new) {
                    $tvd->set('value', $value);
                } else {
                    $tvd->set('document_id', $documentId);
                    $tvd->set('value', $value);
                    $this->addOne($tvd);
                }
            } elseif (!$tvd->_new && ($value === null || $value === $this->get('default_text'))) {
                $tvd->remove();
            }
        }
    }

    /**
     * Returns the processed output of a template variable.
     *
     * @param integer $documentId The id of the resource; 0 defaults to the
     * current resource.
     * @return mixed The processed output of the template variable.
     */
    function renderOutput($documentId= 0) {
        $value= $this->getValue($documentId);

        // process any TV commands in value
        $value= $this->processBindings($value, $documentId);

        $param= array ();
        if ($paramstring= $this->get('display_params')) {
            $cp= split("&", $paramstring);
            foreach ($cp as $p => $v) {
                $v= trim($v); // trim
                $ar= split("=", $v);
                if (is_array($ar) && count($ar) == 2) {
                    $params[$ar[0]]= $this->decodeParamValue($ar[1]);
                }
            }
        }

        $name= $this->get('name');

        $id= "tv$name";
        $format= $this->get('display');
        $tvtype= $this->get('type');
        switch ($format) {
            case 'image' :
                $images= $this->parseInput($value, '||', 'array');
                $o= '';
                foreach ($images as $image) {
                    if (!is_array($image)) {
                        $image= explode('==', $image);
                    }
                    $src= $image[0];
                    if ($src) {
                        $id= ($params['id'] ? 'id="' . $params['id'] . '"' : '');
                        $alt= htmlspecialchars($params['alttext']);
                        $class= $params['class'];
                        $style= $params['style'];
                        $attributes= $params['attrib'];
                        $o .=<<<EOD
<img {$id} src="{$src}" alt="{$alt}" class="{$class}" style="{$style}" {$attributes} />
EOD;
                    }
                }
                break;

            case "delim" : // display as delimitted list
                $value= $this->parseInput($value, "||");
                $p= $params['format'] ? $params['format'] : ",";
                if ($p == "\\n")
                    $p= "\n";
                $o= str_replace("||", $p, $value);
                break;

            case "string" :
                $value= $this->parseInput($value);
                $format= strtolower($params['format']);
                if ($format == 'upper case')
                    $o= strtoupper($value);
                else
                    if ($format == 'lower case')
                        $o= strtolower($value);
                    else
                        if ($format == 'sentence case')
                            $o= ucfirst($value);
                        else
                            if ($format == 'capitalize')
                                $o= ucwords($value);
                            else
                                $o= $value;
                break;

            case "date" :
                $value= $this->parseInput($value);
                // Check for MySQL style date - Adam Crownoble 8/3/2005
                $date_match= '^([0-9]{2})-([0-9]{2})-([0-9]{4})\ ([0-9]{2}):([0-9]{2}):([0-9]{2})$';
                $matches= array ();
                if (strpos($value, '-') !== false && ereg($date_match, $value, $matches)) {
                    $timestamp= mktime($matches[4], $matches[5], $matches[6], $matches[2], $matches[1], $matches[3]);
                } else { // If it's not a MySQL style date, then use strtotime to figure out the date
                    $timestamp= strtotime($value);
                }
                $p= $params['format'] ? $params['format'] : "%A %d, %B %Y";
                $o= strftime($p, $timestamp);
                break;

            case "floater" :
                $value= $this->parseInput($value, " ");
                $this->xpdo->regClientStartupScript("manager/media/script/bin/webelm.js");
                $o= "<script type=\"text/javascript\">";
                $o .= "  document.setIncludePath('manager/media/script/bin/');";
                $o .= "  document.addEventListener('oninit',function(){document.include('dynelement');document.include('floater');});";
                $o .= "  document.addEventListener('onload',function(){var o = new Floater('$id','" . addslashes(mysql_escape_string($value)) . "','" . $params['x'] . "','" . $params['y'] . "','" . $params['pos'] . "','" . $params['gs'] . "');});";
                $o .= "</script>";
                $o .= "<script type=\"text/javascript\">Floater.Render('$id','" . $params['width'] . "','" . $params['height'] . "','" . $params['class'] . "','" . $params['style'] . "');</script>";
                break;

            case "marquee" :
                $transfx= ($params['tfx'] == 'Horizontal') ? 2 : 1;
                $value= $this->parseInput($value, " ");
                $this->xpdo->regClientStartupScript("manager/media/script/bin/webelm.js");
                $o= "<script type=\"text/javascript\">";
                $o .= "  document.setIncludePath('manager/media/script/bin/');";
                $o .= "  document.addEventListener('oninit',function(){document.include('dynelement');document.include('marquee');});";
                $o .= "  document.addEventListener('onload',function(){var o = new Marquee('$id','" . addslashes(mysql_escape_string($value)) . "','" . $params['speed'] . "','" . ($params['pause'] == 'Yes' ? 1 : 0) . "','" . $transfx . "'); o.start()});";
                $o .= "</script>";
                $o .= "<script type=\"text/javascript\">Marquee.Render('$id','" . $params['width'] . "','" . $params['height'] . "','" . $params['class'] . "','" . $params['style'] . "');</script>";
                break;

            case "ticker" :
                $transfx= ($params['tfx'] == 'Fader') ? 2 : 1;
                $delim= ($params['delim']) ? $params['delim'] : "||";
                if ($delim == "\\n")
                    $delim= "\n";
                $value= $this->parseInput($value, $delim, "array");
                $this->xpdo->regClientStartupScript("manager/media/script/bin/webelm.js");
                $o= '<script type="text/javascript">';
                $o .= "  document.setIncludePath('manager/media/script/bin/');";
                $o .= "  document.addEventListener('oninit',function(){document.include('dynelement');document.include('ticker');});";
                $o .= "  document.addEventListener('onload',function(){";
                $o .= "  var o = new Ticker('$id','" . $params['delay'] . "','" . $transfx . "'); ";
                for ($i= 0; $i < count($value); $i++) {
                    $o .= "  o.addMessage('" . addslashes(mysql_escape_string($value[$i])) . "');";
                }
                $o .= "  });";
                $o .= "</script>";
                $o .= "<script type=\"text/javascript\">Ticker.Render('$id','" . $params['width'] . "','" . $params['height'] . "','" . $params['class'] . "','" . $params['style'] . "');</script>";
                break;

            case "hyperlink" :
                $value= $this->parseInput($value, "||", "array");
                for ($i= 0; $i < count($value); $i++) {
                    list ($name, $url)= is_array($value[$i]) ? $value[$i] : explode("==", $value[$i]);
                    if (!$url)
                        $url= $name;
                    if ($o)
                        $o .= '<br />';
                    $o .= "<a href='$url'" . " title='" . ($params["title"] ? $this->xpdo->db->escape($params["title"]) : $name) . "'" . ($params["class"] ? " class='" . $params["class"] . "'" : "") . ($params["style"] ? " style='" . $params["style"] . "'" : "") . ($params["target"] ? " target='" . $params["target"] . "'" : "") . ($params["attrib"] ? " " . $this->xpdo->db->escape($params["attrib"]) : "") . ">" . ($params["text"] ? $this->xpdo->db->escape($params["text"]) : $name) . "</a>";
                }
                break;

            case "htmltag" :
                $value= $this->parseInput($value, "||", "array");
                $tagid= $params['tagid'];
                $tagname= ($params['tagname']) ? $params['tagname'] : 'div';
                for ($i= 0; $i < count($value); $i++) {
                    $tagvalue= is_array($value[$i]) ? implode(" ", $value[$i]) : $value[$i];
                    if (!$url)
                        $url= $name;
                    $o .= "<$tagname id='" . ($tagid ? $tagid : "tv" . $id) . "'" . ($params["class"] ? " class='" . $params["class"] . "'" : "") . ($params["style"] ? " style='" . $params["style"] . "'" : "") . ($params["attrib"] ? " " . $params["attrib"] : "") . ">" . $tagvalue . "</$tagname>";
                }
                break;

            case "richtext" :
                $value= $this->parseInput($value);
                $w= $params['w'] ? $params['w'] : '100%';
                $h= $params['h'] ? $params['h'] : '400px';
                $richtexteditor= $params['edt'] ? $params['edt'] : "";
                $this->xpdo->regClientStartupScript("manager/media/script/bin/webelm.js");
                $o= '<div class="MODX_RichTextWidget"><textarea id="' . $id . '" name="' . $id . '" style="width:' . $w . '; height:' . $h . ';">';
                $o .= htmlspecialchars($value);
                $o .= '</textarea></div>';
                $replace_richtext= array (
                    $id
                );
                // setup editors
                if (!empty ($replace_richtext) && !empty ($richtexteditor)) {
                    // invoke OnRichTextEditorInit event
                    $evtOut= $this->xpdo->invokeEvent("OnRichTextEditorInit", array (
                        'editor' => $richtexteditor,
                        'elements' => $replace_richtext,
                        'forfrontend' => 1,
                        'width' => $w,
                        'height' => $h
                    ));
                    if (is_array($evtOut))
                        $o .= implode("", $evtOut);
                }
                break;

            case "viewport" :
                $value= $this->parseInput($value);
                $id= '_' . time();
                if (!$params['vpid'])
                    $params['vpid']= $id;
                if ($_SESSION['browser'] == 'ns' && $_SESSION['browser_version'] < '5.0') {
                    $sTag= "<ilayer";
                    $eTag= "</ilayer>";
                } else {
                    $sTag= "<iframe";
                    $eTag= "</iframe>";
                }
                $autoMode= "0";
                $w= $params['width'];
                $h= $params['height'];
                if ($params['stretch'] == 'Yes') {
                    $w= "100%";
                    $h= "100%";
                }
                if ($params['asize'] == 'Yes' || ($params['awidth'] == 'Yes' && $params['aheight'] == 'Yes')) {
                    $autoMode= "3"; //both
                } else
                    if ($params['awidth'] == 'Yes') {
                        $autoMode= "1"; //width only
                    } else
                        if ($params['aheight'] == 'Yes') {
                            $autoMode= "2"; //height only
                        }

                $this->xpdo->regClientStartupScript("manager/media/script/bin/viewport.js");
                $o= $sTag . " id='" . $params['vpid'] . "' name='" . $params['vpid'] . "' ";
                if ($params['class'])
                    $o .= " class='" . $params['class'] . "' ";
                if ($params['style'])
                    $o .= " style='" . $params['style'] . "' ";
                if ($params['attrib'])
                    $o .= $params['attrib'] . " ";
                $o .= "scrolling='" . ($params['sbar'] == 'No' ? "no" : ($params['sbar'] == 'Yes' ? "yes" : "auto")) . "' ";
                $o .= "src='" . $value . "' frameborder='" . $params['borsize'] . "' ";
                $o .= "onload=\"window.setTimeout('ResizeViewPort(\\\\'" . $params['vpid'] . "\\\\'," . $autoMode . ")',100);\" width='" . $w . "' height='" . $h . "' ";
                $o .= ">";
                $o .= $eTag;
                break;

            case "datagrid" :
                include_once $this->xpdo->config['base_path'] . "manager/includes/controls/datagrid.class.php";
                $grd= new DataGrid('', $value);

                $grd->noRecordMsg= $params['nrmsg'];

                $grd->columnHeaderClass= $params['chdrc'];
                $grd->tableClass= $params['tblc'];
                $grd->itemClass= $params['itmc'];
                $grd->altItemClass= $params['aitmc'];

                $grd->columnHeaderStyle= $params['chdrs'];
                $grd->tableStyle= $params['tbls'];
                $grd->itemStyle= $params['itms'];
                $grd->altItemStyle= $params['aitms'];

                $grd->columns= $params['cols'];
                $grd->fields= $params['flds'];
                $grd->colWidths= $params['cwidth'];
                $grd->colAligns= $params['calign'];
                $grd->colColors= $params['ccolor'];
                $grd->colTypes= $params['ctype'];

                $grd->cellPadding= $params['cpad'];
                $grd->cellSpacing= $params['cspace'];
                $grd->header= $params['head'];
                $grd->footer= $params['foot'];
                $grd->pageSize= $params['psize'];
                $grd->pagerLocation= $params['ploc'];
                $grd->pagerClass= $params['pclass'];
                $grd->pagerStyle= $params['pstyle'];
                $o= $grd->render();
                break;

            default :
                $value= $this->parseInput($value);
                if ($tvtype == 'checkbox' || $tvtype == 'listbox-multiple') {
                    // remove delimiter from checkbox and listbox-multiple TVs
                    $value= str_replace('||', '', $value);
                }
                $o= (string) $value;
                break;
        }
        return $o;
    }

	/**
     * Renders input forms for the template variable.
     *
     * @param integer $resourceId The id of the resource; 0 defaults to the
     * current resource.
	 * @param string $style Extra style parameters.
     * @return mixed The rendered input for the template variable.
     */
    function renderInput($resourceId= 0, $style= '') {
        $field_html= '';
		$this->xpdo->smarty->assign('style',$style);
		$value = $this->get('value');
		if (!$value || $value == '') {
			$this->set('value',$this->getValue($resourceId));
		}
		$this->xpdo->smarty->assign('tv',$this);

        switch ($this->get('type')) {
            case 'text': // handler for regular text boxes
            case 'email': // handles email input fields
            case 'number': // handles the input of numbers
				$field_html .= $this->xpdo->smarty->fetch('element/tv/widgets/textbox.tpl');
                break;

            case 'textareamini': // handler for textarea mini boxes
				$field_html .= $this->xpdo->smarty->fetch('element/tv/widgets/textareamini.tpl');
                break;

            case 'textarea': // handler for textarea boxes
            case 'htmlarea': // handler for textarea boxes (deprecated)
            case 'richtext': // handler for textarea boxes
				$field_html .= $this->xpdo->smarty->fetch('element/tv/widgets/richtext.tpl');
                break;

            case 'date':
                $field_html .= $this->xpdo->smarty->fetch('element/tv/widgets/date.tpl');
                break;

            case 'dropdown': // handler for select boxes
                $index_list = $this->parseInputOptions($this->processBindings($this->get('elements'),$this->get('name')));
				$items = array();
				while (list($item, $itemvalue) = each ($index_list)) {
					list($item,$itemvalue) = (is_array($itemvalue)) ? $itemvalue : explode("==",$itemvalue);
					if (strlen($itemvalue)==0) $itemvalue = $item;
					$items[] = array(
						'text' => htmlspecialchars($item),
						'value' => $itemvalue
					);
				}
				$this->xpdo->smarty->assign('tvitems',$items);
                $field_html .= $this->xpdo->smarty->fetch('element/tv/widgets/dropdown.tpl');
                break;

            case 'listbox': // handler for select boxes
                $index_list = $this->parseInputOptions($this->processBindings($this->get('elements'),$this->get('name')));
				$opts = array();
                while (list($item, $itemvalue) = each ($index_list))
                {
                    list($item,$itemvalue) = (is_array($itemvalue)) ? $itemvalue : explode("==",$itemvalue);
                    if (strlen($itemvalue)==0) $itemvalue = $item;
					$opts[] = array(
						'value' => htmlspecialchars($itemvalue),
						'text' => htmlspecialchars($item),
						'selected' => $itemvalue == $this->get('value')
					);
                }
				$this->xpdo->smarty->assign('opts',$opts);
                $field_html .= $this->xpdo->smarty->fetch('element/tv/widgets/listbox-single.tpl');
                break;

            case 'listbox-multiple': // handler for select boxes where you can choose multiple items
                $this->set('value',explode("||",$this->get('value')));
                $index_list = $this->parseInputOptions($this->processBindings($this->get('elements'),$this->get('name')));
				$opts = array();
                while (list($item, $itemvalue) = each ($index_list)) {
                    list($item,$itemvalue) = (is_array($itemvalue)) ? $itemvalue : explode("==",$itemvalue);
                    if (strlen($itemvalue)==0) $itemvalue = $item;
					$opts[] = array(
						'value' => htmlspecialchars($itemvalue),
						'text' => htmlspecialchars($item),
						'selected' => in_array($itemvalue,$this->get('value')),
					);
                }
				$this->xpdo->smarty->assign('opts',$opts);
                $field_html .= $this->xpdo->smarty->fetch('element/tv/widgets/listbox-multiple.tpl');
                break;

            case 'url': // handles url input fields
                $urls= array(''=>'--', 'http://'=>'http://', 'https://'=>'https://', 'ftp://'=>'ftp://', 'mailto:'=>'mailto:');
				$this->xpdo->smarty->assign('urls',$urls);
                foreach($urls as $k => $v){
                    if(strpos($this->get('value'),$v)!==false) {
                        $this->set('value',str_replace($v,'',$this->get('value')));
                    }
                }
				$field_html .= $this->xpdo->smarty->fetch('element/tv/widgets/url.tpl');
                break;

            case 'checkbox': // handles check boxes
                $this->set('value',explode("||",$this->get('value')));
                $index_list = $this->parseInputOptions($this->processBindings($this->get('elements'),$this->get('name')));
				$opts = array();
                while (list($item, $itemvalue) = each ($index_list))
                {
                    list($item,$itemvalue) =  (is_array($itemvalue)) ? $itemvalue : explode("==",$itemvalue);
                    if (strlen($itemvalue)==0) $itemvalue = $item;
					$opts[] = array(
						'value' => htmlspecialchars($itemvalue),
						'text' => htmlspecialchars($item),
						'checked' => in_array($itemvalue,$this->get('value')),
					);
                }
				$this->xpdo->smarty->assign('opts',$opts);
                $field_html .= $this->xpdo->smarty->fetch('element/tv/widgets/checkbox.tpl');
                break;

            case 'option': // handles radio buttons
                $index_list = $this->parseInputOptions($this->processBindings($this->get('elements'),$this->get('name')));
				$opts = array();
                while (list($item, $itemvalue) = each ($index_list))
                {
                    list($item,$itemvalue) =  (is_array($itemvalue)) ? $itemvalue : explode("==",$itemvalue);
                    if (strlen($itemvalue)==0) $itemvalue = $item;
                    $opts[] = array(
						'value' => htmlspecialchars($itemvalue),
						'text' => htmlspecialchars($item),
						'checked' => $itemvalue == $this->get('value')
					);
                }
				$this->xpdo->smarty->assign('opts',$opts);
                $field_html .= $this->xpdo->smarty->fetch('element/tv/widgets/radio.tpl');
                break;

            case 'image':   // handles image fields using htmlarea image manager
				$this->xpdo->smarty->assign('base_url',$this->xpdo->config['base_url']);
                $field_html .= $this->xpdo->smarty->fetch('element/tv/widgets/image.tpl');
                break;

            case 'file': // handles the input of file uploads
				$this->xpdo->smarty->assign('base_url',$this->xpdo->config['base_url']);
                $field_html .= $this->xpdo->smarty->fetch('element/tv/widgets/file.tpl');
                break;

            default: // the default handler -- for errors, mostly
                $field_html .= $this->xpdo->smarty->fetch('element/tv/widgets/textbox.tpl');
                break;
        } // end switch statement
        return $field_html;
    }

	/**
	 * Decodes special function-based chars from a parameter value.
	 *
	 * @param string $s The string to decode.
	 * @return string The decoded string.
	 */
    function decodeParamValue($s) {
        $s= str_replace("%3D", '=', $s); // =
        $s= str_replace("%26", '&', $s); // &
        return $s;
    }

    /**
	 * Returns an string if a delimiter is present. Returns array if is a recordset is present.
	 *
	 * @param mixed $src Source object, either a recordset, PDO object, array or string.
	 * @param string $delim Delimiter for string parsing.
	 * @param string $type Type to return, either 'string' or 'array'.
	 *
	 * @return string|array If delimiter present, returns string, otherwise array.
	 */
    function parseInput($src, $delim= "||", $type= "string") { // type can be: string, array
        if (is_resource($src)) {
            // must be a recordset
            $rows= array ();
//            $nc= mysql_num_fields($src);
            while ($cols= mysql_fetch_row($src))
                $rows[]= ($type == "array") ? $cols : implode(" ", $cols);
            return ($type == "array") ? $rows : implode($delim, $rows);
        } elseif (is_object($src)) {
            $rs= $src->fetchAll(PDO_FETCH_ASSOC);
            if ($type != "array") {
                foreach ($rs as $row) {
                    $rows[]= implode(" ", $row);
                }
            } else {
                $rows= $rs;
            }
            return ($type == "array" ? $rows : implode($delim, $rows));
        } elseif (is_array($src) && $type == "array") {
            return ($type == "array" ? $src : implode($delim, $src));
        } else {
            // must be a text
            if ($type == "array")
                return explode($delim, $src);
            else
                return $src;
        }
    }

	/**
	 * Parses input options sent through postback.
	 *
	 * @param mixed $v The options to parse, either a resource, array or string.
	 * @return mixed The parsed options.
	 */
    function parseInputOptions($v) {
        $a = array();
        if(is_array($v)) return $v;
        else if(is_resource($v)) {
            while ($cols = mysql_fetch_row($v)) $a[] = $cols;
        }
        else $a = explode("||", $v);
        return $a;
    }

	/**
	 * Process bindings assigned to a template variable.
	 *
	 * @param string $value The value specified from the binding.
	 * @param integer $documentId The document in which the TV is assigned.
	 * @return string The processed value.
	 */
    function processBindings($value= '', $documentId= 0) {
		$etomite =& $this->xpdo; // backward compat for eval/snippets
        $modx =& $this->xpdo;
        $nvalue= trim($value);
        if (substr($nvalue,0,1)!='@') return $value;
        else {
            list($cmd,$param) = $this->parseBinding($nvalue);
            $cmd = trim($cmd);
            switch ($cmd) {
                case 'FILE':
                    $output = $this->processFileBinding($param);
                    break;

                case 'CHUNK':       // retrieve a chunk and process it's content
                    $chunk = $this->xpdo->getChunk($param);
                    $output = $chunk;
                    break;

                case 'DOCUMENT':    // retrieve a document and process it's content
                    $rs = $this->xpdo->getDocument($param);
                    if (is_array($rs)) $output = $rs['content'];
                    else $output = 'Unable to locate document '.$param;
                    break;

                case 'SELECT': // selects a record from the cms database
                    $dbtags['DBASE'] = $this->xpdo->db->config['dbase'];
                    $dbtags['PREFIX'] = $this->xpdo->db->config['table_prefix'];
                    foreach($dbtags as $key => $pValue)
                        $param = str_replace('[[+'.$key.']]', $pValue, $param);
                    $rs = $this->xpdo->db->query('SELECT '.$param);
                    $output = $rs;
                    break;

                case 'EVAL':        // evaluates text as php codes return the results
                    $output = eval($param);
                    break;

                case 'INHERIT':
                    $output = $param; // Default to param value if no content from parents
                    $doc = $this->xpdo->getDocument($this->xpdo->documentIdentifier,'id,parent');

                    while($doc['parent'] != 0) {
                        $parent_id = $doc['parent'];
                        if($doc = $this->xpdo->getDocument($parent_id, 'id,parent')) {
                            $tv = $this->xpdo->getTemplateVar($this->get('name'), '*', $doc['id']);
                            if($tv['value'] && substr($tv['value'],0,1) != '@') {
                                $output = $tv['value'];
                                break 2;
                            }
                        } else {
                            // Get unpublished document
                            $doc = $this->xpdo->getDocument($parent_id, 'id,parent',0);
                        }
                    }
                    break;

                case 'DIRECTORY':
                    $files = array();
                    $path = $this->xpdo->config['base_path'].$param;
                    if(substr($path,-1,1)!='/') { $path.='/'; }
                    if(!is_dir($path)) { die($path); break;}
                    $dir = dir($path);
                    while(($file = $dir->read())!==false) {
						if(substr($file,0,1)!='.') {
							$files[] = "{$file}=={$param}{$file}";
						}
                    }
                    asort($files);
                    $output = implode('||',$files);
                    break;

                default:
                    $output = $value;
                    break;

            }
            // support for nested bindings
            return is_string($output) && ($output!=$value) ? $this->processBindings($output) : $output;
        }
    }

	/**
	 * Parses bindings to an appropriate format.
	 *
	 * @param string $binding_string The binding to parse.
	 * @return array The parsed binding, now in array format.
	 */
    function parseBinding($binding_string) {
        $match= array ();
        $binding_string= trim($binding_string);
        $regexp= '/@(' . implode('|', $this->bindings) . ')\s*(.*)/is'; // Split binding on whitespace
        if (preg_match($regexp, $binding_string, $match)) {
            // We can't return the match array directly because the first element is the whole string
            $binding_array= array (
                strtoupper($match[1]),
                trim($match[2])
            ); // Make command uppercase
            return $binding_array;
        }
    }

	/**
	 * Special parsing for file bindings.
	 *
	 * @param string $file The absolute location of the file in the binding.
	 * @return string The file buffer from the read file.
	 */
    function processFileBinding($file) {
        // get the file
        if (file_exists($file) && @ $handle= fopen($file,'r')) {
            $buffer= "";
            while (!feof($handle)) {
                $buffer .= fgets($handle, 4096);
            }
            fclose($handle);
        } else {
            $buffer= " Could not retrieve document '$file'.";
        }
        return $buffer;
    }

    /**
     * Loads the access control policies applicable to this template variable.
     *
     * {@inheritdoc}
     */
    function findPolicy($context = '') {
        $policy = array();
        $context = !empty($context) ? $context : $this->xpdo->context->get('key');
        if (empty($this->_policies) || !isset($this->_policies[$context])) {
            $accessTable = $this->xpdo->getTableName('modAccessResourceGroup');
            $policyTable = $this->xpdo->getTableName('modAccessPolicy');
            $resourceGroupTable = $this->xpdo->getTableName('modTemplateVarResourceGroup');
            $sql = "SELECT acl.target, acl.principal, acl.authority, acl.policy, p.data FROM {$accessTable} acl " .
                    "LEFT JOIN {$policyTable} p ON p.id = acl.policy " .
                    "JOIN {$resourceGroupTable} rg ON acl.principal_class = 'modUserGroup' " .
                    "AND (acl.context_key = :context OR acl.context_key IS NULL OR acl.context_key = '') " .
                    "AND rg.tmplvarid = :element " .
                    "AND rg.documentgroup = acl.target " .
                    "GROUP BY acl.target, acl.principal, acl.authority, acl.policy";
            $bindings = array(
                ':element' => $this->get('id'),
                ':context' => $context
            );
            $query = new xPDOCriteria($this->xpdo, $sql, $bindings);
            if ($query->stmt && $query->stmt->execute()) {
                foreach ($query->stmt->fetchAll(PDO_FETCH_ASSOC) as $row) {
                    $policy['modAccessResourceGroup'][$row['target']][$row['principal']] = array(
                        'authority' => $row['authority'],
                        'policy' => $row['data'] ? xPDO :: fromJSON($row['data'], true) : array(),
                    );
                }
            }
            $this->_policies[$context] = $policy;
        } else {
            $policy = $this->_policies[$context];
        }
        return $policy;
    }
}
?>