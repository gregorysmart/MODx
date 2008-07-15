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

/**
 * This file contains the modMail email service interface definition.
 * @package modx
 * @subpackage mail
 */

/**#@+
 * modMail service constants representing mail attribute keys.
 * @var string
 */
define('MODX_MAIL_BODY',            'mail_body');
define('MODX_MAIL_BODY_TEXT',       'mail_body_text');
define('MODX_MAIL_CHARSET',         'mail_charset');
define('MODX_MAIL_CONTENT_TYPE',    'mail_content_type');
define('MODX_MAIL_ENCODING',        'mail_encoding');
define('MODX_MAIL_ENGINE',          'mail_engine');
define('MODX_MAIL_ENGINE_PATH',     'mail_engine_path');
define('MODX_MAIL_ERROR_INFO',      'mail_error_info');
define('MODX_MAIL_FROM',            'mail_from');
define('MODX_MAIL_FROM_NAME',       'mail_from_name');
define('MODX_MAIL_HOSTNAME',        'mail_hostname');
define('MODX_MAIL_LANGUAGE',        'mail_language');
define('MODX_MAIL_PRIORITY',        'mail_priority');
define('MODX_MAIL_READ_TO',         'mail_read_to');
define('MODX_MAIL_SENDER',          'mail_sender');
define('MODX_MAIL_SERVICE',         'mail_service');
define('MODX_MAIL_SMTP_AUTH',       'mail_smtp_auth');
define('MODX_MAIL_SMTP_HELO',       'mail_smtp_helo');
define('MODX_MAIL_SMTP_HOSTS',      'mail_smtp_hosts');
define('MODX_MAIL_SMTP_KEEPALIVE',  'mail_smtp_keepalive');
define('MODX_MAIL_SMTP_PASS',       'mail_smtp_pass');
define('MODX_MAIL_SMTP_PORT',       'mail_smtp_port');
define('MODX_MAIL_SMTP_PREFIX',     'mail_smtp_prefix');
define('MODX_MAIL_SMTP_SINGLE_TO',  'mail_smtp_single_to');
define('MODX_MAIL_SMTP_TIMEOUT',    'mail_smtp_timeout');
define('MODX_MAIL_SMTP_USER',       'mail_smtp_user');
define('MODX_MAIL_SUBJECT',         'mail_subject');
/**#@-*/

/**
 * Defines the interface for the modX email service.
 *
 * @abstract Implement a derivative of this class to define an actual email service implementation.
 * @package modx
 * @subpackage mail
 */
class modMail {
    /**
     * A reference to the modX instance communicating with this service instance.
     * @var modX
     */
    var $modx= null;
    /**
     * A collection of attributes defining all of the details of email communication.
     * @var array
     */
    var $attributes= array();
    /**
     * The mailer object responsible for implementing the modMail methods.
     * @var object
     */
    var $mailer= null;
    var $headers= array();
    /**
     * An array of address types: to, cc, bcc, reply-to
     * @var array
     */
    var $addresses= array(
        'to' => array(),
        'cc' => array(),
        'bcc' => array(),
        'reply-to' => array(),
    );
    /**
     * An array of attached files
     * @var array
     */
    var $files= array();

    /**#@+
     * Constructs a new instance of the modPHPMailer class.
     *
     * {@inheritdoc}
     */
    function modMail(& $modx, $attributes= array()) {
        $this->_construct($modx, $attributes);
    }
    /** @ignore */
    function __construct(& $modx, $attributes= array()) {
        $this->modx= $modx;
        if (is_array($attributes)) {
            $this->attributes= $attributes;
        }
    }
    /**#@-*/

    /**
     * Gets a reference to an attribute of the mail object.
     *
     * @param string $key The attribute key.
     * @return mixed A reference to the attribute, or null if no attribute value is set for the key.
     */
    function & get($key) {
        $value= null;
        if (isset($this->attributes[$key])) {
            $value = & $this->attributes[$key];
        }
        return $value;
    }

    /**
     * Sets the value of an attribute of the mail object.
     *
     * {@internal Override this method in a derivative to set the appropriate attributes of the
     * actual mailer implementation being used. Make sure to call this parent implementation first
     * and then set the value of the corresponding mailer attribute as a reference to the attribute
     * set in $this->attributes}
     *
     * @abstract
     * @param string $key The key of the attribute to set.
     * @param mixed $value The value of the attribute.
     */
    function set($key, $value) {
        $this->attributes[$key]= $value;
    }

    /**
     * Add a new recipient email address to one of the valid address type buckets.
     *
     * @param string $type The address type to add; to, cc, bcc, or reply-to.
     * @param string $email The email address.
     * @param string $name An optional name for the addressee.
     * @return boolean Indicates if the address was set/unset successfully.
     */
    function address($type, $email, $name= '') {
        $set= false;
        $type = strtolower($type);
        if (isset($this->addresses[$type])) {
            if ($email === null && isset($this->addresses[$type][$email])) {
                $this->addresses[$type][$email]= null;
                $set= true;
            } else {
                $this->addresses[$type][$email]= array($email, $name);
                $set= true;
            }
        }
        return $set;
    }

    function header($header) {
        $set= false;
        $parsed= explode(':', $header, 2);
        if ($parsed && count($parsed) == 2) {
            $this->headers[]= $parsed;
            $set= true;
        }
        return $set;
    }

    /**
     * Send an email setting any supplied attributes before sending.
     *
     * {@internal You should implement the rest of this method in a derivative class.}
     *
     * @abstract
     * @param array $attributes Attributes to override any existing attributes before sending.
     * @return boolean Indicates if the email was sent successfully.
     */
    function send($attributes= array()) {
        if (is_array($attributes)) {
            while (list($attrKey, $attrVal) = each($attributes)) {
                $this->set($attrKey, $attrVal);
            }
        }
        return false;
    }

    /**
     * Reset the mail service, clearing addresses and attributes.
     *
     * @param array $attributes An optional array of attributes to apply after reset.
     */
    function reset($attributes= null) {
        $this->addresses= array(
            'to' => array(),
            'cc' => array(),
            'bcc' => array(),
            'reply-to' => array(),
        );
        if (!is_array($attributes)) $attributes= array();
        foreach ($this->attributes as $attrKey => $attrVal) {
            if (array_key_exists($attrKey, $attributes)) {
                $this->set($attrKey, $attributes[$attrKey]);
            } else {
                $this->set($attrKey, null);
            }
        }
    }

    /**
     * Get an instance of the email class responsible for sending emails from the modEmail service.
     *
     * {@internal Implement this function in derivatives and call it in the constructor after all
     * other dependencies have been satisfied.}
     *
     * @abstract
     * @access protected
     * @return boolean Indicates if the mailer class was instantiated successfully.
     */
    function _getMailer() {
        $this->modx->_log(XPDO_LOG_LEVEL_ERROR, 'Attempt to call abstract function _getMailer() in modMail class. You must implement this function in a derivative of modMail.');
        return false;
    }
    
    /**
     * Attach a file to the attachments queue.
     * 
     * @access public
     * @param string $file The absolute path to the file
     */
    function attach($file) {
        array_push($this->files,$file);
    }
    
    /**
     * Clear all existing attachments.
     * 
     * @access public
     */
    function clearAttachments() {
        $this->files = array();
    }
}
?>