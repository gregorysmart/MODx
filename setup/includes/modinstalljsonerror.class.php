<?php
/*
 * MODx Revolution
 *
 * Copyright 2006, 2007, 2008 by the MODx Team.
 * All rights reserved.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 */

class modInstallJSONError {
    var $fields;
    var $message;
    var $type;

    function modInstallJSONError($message= '', $type= 'error') {
        $this->__construct($message, $type);
    }
    function __construct($message= '', $type= 'error') {
        $this->message= $message;
        $this->fields= array ();
        $this->type= $type;
    }

    function process($message= '', $status = false, $object = null) {
        $objarray= $this->_process($message, $status, $object);
        @header("Content-Type: text/json; charset=UTF-8");
        if ($message != '') $this->message= $message;

        return xPDO :: toJSON(array (
            'message' => $this->message,
            'fields' => $this->fields,
            'type' => $this->type,
            'object' => $objarray,
            'success' => $status,
        ));
    }

    /**
     * Process errors and return a proper output value.
     *
     * @param string $message The error message to output.
     * @param boolean $status Whether or not the action is a success or failure.
     * @param object|array $object The object to send back to output.
     * @return string|object|array The transformed object data array.
     */
    function _process($message = '', $status = false, $object = null) {
        if ($status === true) {
            $s = $this->_validate();
            if ($s !== '') {
                $status = false;
                $message = $s;
            }
        }
        $this->status = (boolean) $status;

        if ($message != '') {
            $this->message = $message;
        }
        $objarray = array ();
        if (is_array($object)) {
            $obj = reset($object);
            if (is_object($obj) && is_a($obj, 'xPDOObject')) {
                $this->total = count($object);
            }
            unset ($obj);
        }
        $objarray = $this->toArray($object);
        return $objarray;
    }

    function addField($name, $error) {
        $this->fields[]= array (
            'name' => $name,
            'error' => $error
        );
    }

    function getFields() {
        $f= array ();
        foreach ($this->fields as $fi) $f[]= $fi['name'];
        return $f;
    }

    function hasError() {
        return count($this->fields) > 0 || ($this->message != '' && $this->type == 'error');
    }

    function setType($type= 'error') {
        $this->type= $type;
    }

    function failure($message = '', $object = null) {
        while (@ ob_end_clean()) {}
        die($this->process($message, false, $object));
    }

    function success($message = '', $object = null) {
        die($this->process($message, true, $object));
    }
}
