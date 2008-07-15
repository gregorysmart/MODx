<?php
/**
 * The core MODx user class.
 *
 * @package modx
 */
class modUser extends modPrincipal {
    /**
     * A collection of contexts which the current principal is authenticated in.
     * @var array
     */
    var $sessionContexts= array ();

    function modUser(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }

    /**
     * Loads the principal attributes that define a modUser security profile.
     *
     * {@inheritdoc}
     *
     * @todo Remove the legacy docgroup support.
     */
    function loadAttributes($target, $context = '') {
        $context = !empty($context) ? $context : $this->xpdo->context->get('key');
        if ($this->_attributes === null) {
            $this->_attributes = array();
            if (isset($_SESSION["modx.{$context}.principal.attributes"])) {
                $this->_attributes = $_SESSION["modx.{$context}.principal.attributes"];
            }
        }
        if (!isset($this->_attributes[$target])) {
            switch ($target) {
                case 'modAccessResourceGroup' :
                    $legacyDocGroups = array();
                    $accessTable = $this->xpdo->getTableName($target);
                    $policyTable = $this->xpdo->getTableName('modAccessPolicy');
                    $memberTable = $this->xpdo->getTableName('modUserGroupMember');
                    $memberRoleTable = $this->xpdo->getTableName('modUserGroupRole');
                    $sql = "SELECT acl.target, acl.principal, mr.authority, acl.policy, p.data FROM {$accessTable} acl " .
                            "LEFT JOIN {$policyTable} p ON p.id = acl.policy " .
                            "JOIN {$memberTable} mug ON acl.principal_class = 'modUserGroup' " .
                            "AND (acl.context_key = :context OR acl.context_key IS NULL OR acl.context_key = '') " .
                            "AND mug.member = :principal " .
                            "AND mug.user_group = acl.principal " .
                            "JOIN {$memberRoleTable} mr ON mr.id = mug.role " .
                            "AND mr.authority <= acl.authority " .
                            "GROUP BY acl.target, acl.principal, acl.authority, acl.policy";
                    $bindings = array(
                        ':principal' => $this->get('id'),
                        ':context' => $context
                    );
                    $query = new xPDOCriteria($this->xpdo, $sql, $bindings);
//                    $this->xpdo->_log(XPDO_LOG_LEVEL_ERROR, 'Attribute query: ' . print_r($query->sql, 1));
                    if ($query->stmt && $query->stmt->execute()) {
                        foreach ($query->stmt->fetchAll(PDO_FETCH_ASSOC) as $row) {
                            $this->_attributes[$target][$row['target']][$row['principal']] = array(
                                'authority' => $row['authority'],
                                'policy' => $row['data'] ? xPDO :: fromJSON($row['data'], true) : array(),
                            );
                            $legacyDocGroups[$row['target']]= $row['target'];
                        }
                    }
                    $_SESSION[$context . 'Docgroups']= array_values($legacyDocGroups);
                    break;
                default :
                    break;
            }
            $_SESSION["modx.{$context}.principal.attributes"] = $this->_attributes;
        }
    }

    /**
     * Determines if this user is authenticated in a specific context.
     *
     * Separate session contexts can allow users to login/out of specific sub-sites
     * individually (or in collections).
     *
     * @param string $sessionContext The context to determine if the user is
     * authenticated in.
     * @return boolean true, if the user is authenticated in the specified
     * context, false otherwise.
     */
    function isAuthenticated($sessionContext= 'web') {
        $isAuthenticated= false;
        if (!empty ($sessionContext) && is_string($sessionContext)) {
            if ($this->hasSessionContext($sessionContext)) {
                $isAuthenticated= true;
            }
            elseif (isset ($_SESSION[$sessionContext . "Validated"])) { // legacy support
                $isAuthenticated= ($_SESSION[$sessionContext . "Validated"] == 1);
            }
        }
        return $isAuthenticated;
    }

    /**
     * Ends a user session completely, including all contexts.
     */
    function endSession() {
        if (is_array($this->sessionContexts)) {
            $this->removeSessionContext($this->sessionContexts);
        }
        @ session_destroy();
    }

    /**
     * Change the user password.
     *
     * @param string $newPassword Password to set.
     * @param string $oldPassword Current password for validation.
     * @return boolean Indicates if password was successfully changed.
     * @todo Add support for configurable password encoding.
     */
    function changePassword($newPassword, $oldPassword) {
        $changed= false;
        if ($this->get('password') === md5($oldPassword)) {
            if (!empty ($newPassword)) {
                $this->set('password', $newPassword);
                $changed= $this->save();
                $contextKey= $this->xpdo->context->get('key');
                switch ($contextKey) {
                	case 'web':
                        $this->xpdo->invokeEvent("OnWebChangePassword", array (
                            "userid" => $this->get('id'),
                            "username" => $this->get('username'),
                            "userpassword" => $newPassword)
                        );
                		break;
                	case 'mgr':
                        $this->xpdo->invokeEvent("OnManagerChangePassword", array (
                            "userid" => $this->get('id'),
                            "username" => $this->get('username'),
                            "userpassword" => $newPassword)
                        );
                		break;
                }
                return $changed;
            }
        }
        return false;
    }

    /**
     * Returns an array of user session context keys.
     */
    function getSessionContexts() {
        if (!is_array($this->sessionContexts) || empty ($this->sessionContexts)) {
            $this->sessionContexts= array ();
            if (isset ($_SESSION['modx.user.contextTokens'])) {
                $this->sessionContexts= $_SESSION['modx.user.contextTokens'];
            } else {
                $legacyContextTokens= array ();
                if (isset ($_SESSION["webValidated"]) && $_SESSION["webValidated"] == 1) {
                    $legacyContextTokens[]= 'web';
                }
                if (isset ($_SESSION["mgrValidated"]) && $_SESSION["mgrValidated"] == 1) {
                    $legacyContextTokens[]= 'mgr';
                }
                foreach ($legacyContextTokens as $token)
                    $this->addSessionContext($token);
            }
            $_SESSION['modx.user.contextTokens']= $this->sessionContexts;
        }
        return $this->sessionContexts;
    }

    /**
     * Adds a new context to the user session context array.
     */
    function addSessionContext($context) {
        if (!is_array($this->sessionContexts)) {
            $this->sessionContexts= array ();
        }

        $regenerated= false;
        if (version_compare(XPDO_PHP_VERSION, '4.3.2', '>=')) {
            if (version_compare(XPDO_PHP_VERSION, '5.1.0', '>=')) {
                session_regenerate_id(true);
                $regenerated= true;
            } else {
                $oldSessionId= session_id();
                session_regenerate_id();
                if (!version_compare(XPDO_PHP_VERSION, '4.3.3','>=')) {
                    if (isset ($this->config['session_cookie_path'])) {
                        $cookiePath= $this->config['session_cookie_path'];
                    } else {
                        $cookiePath= $this->config['base_path'];
                    }

                    $cookieExpiration= 0;
                    $cookieLifetime= ini_get('session.cookie_lifetime');
                    if (isset ($_SESSION['modx.' . $context . '.session.cookie.lifetime']) && is_numeric($_SESSION['modx.' . $context . '.session.cookie.lifetime'])) {
                        $cookieLifetime= intval($_SESSION['modx.' . $context . '.session.cookie.lifetime']);
                    }
                    if ($cookieLifetime) {
                        $cookieExpiration= time() + $cookieLifetime;
                    }
                    setcookie(session_name(), session_id(), $cookieExpiration, $cookiePath);
                }
                //HACK: [PHP < 5.1.0] Removing the old session object from the database so it can't be hijacked.
                if ($oldSession= $this->xpdo->getObject('modSession', array ('id' => $oldSessionId), false)) {
                    $oldSession->remove();
                }
                $regenerated= true;
            }
        }

        $this->getOne('modUserProfile');
        $ua= & $this->modUserProfile;
        if ($ua && is_a($ua, 'modUserProfile')) {
            if ($context == 'web') {
                $_SESSION['webShortname']= $this->get('username');
                $_SESSION['webFullname']= $ua->get('fullname');
                $_SESSION['webEmail']= $ua->get('email');
                $_SESSION['webValidated']= 1;
                $_SESSION['webInternalKey']= $this->get('id');
                $_SESSION['webValid']= base64_encode($this->get('password'));
                $_SESSION['webUser']= base64_encode($this->get('username'));
                $_SESSION['webFailedlogins']= $ua->get('failedlogincount');
                $_SESSION['webLastlogin']= $ua->get('lastlogin');
                $_SESSION['webnrlogins']= $ua->get('logincount');
                $_SESSION['webUserGroupNames']= ''; // reset user group names
            }
            elseif ($context == 'mgr') {
                $_SESSION['usertype']= 'manager'; // user is a backend user -- deprecated
                $_SESSION['mgrShortname']= $this->get('username');
                $_SESSION['mgrFullname']= $ua->get('fullname');
                $_SESSION['mgrEmail']= $ua->get('email');
                $_SESSION['mgrValidated']= 1;
                $_SESSION['mgrInternalKey']= $this->get('id');
                $_SESSION['mgrFailedlogins']= $ua->get('failedlogincount');
                $_SESSION['mgrLastlogin']= $ua->get('lastlogin');
                $_SESSION['mgrLogincount']= $ua->get('logincount'); // login count
//                $_SESSION['mgrRole']= $ua->get('role');
//                $ua->getOne('modUserRole');
//                if ($role= & $ua->modUserRole) {
//                    $_SESSION['mgrPermissions']= $role->toArray();
//                }
            }
//            $documentGroups= array ();
//            if ($memberships= $this->getMany('modUserGroupMember')) {
//                foreach ($memberships as $membership) {
//                    if ($documentGroupAccess= $membership->getMany('modUserGroupResourceGroup')) {
//                        foreach ($documentGroupAccess as $dga) {
//                            $documentGroups[]= $dga->documentgroup;
//                        }
//                    }
//                }
//            }
//            $_SESSION[$context . 'Docgroups']= $documentGroups;
            if ($ua && !isset ($this->sessionContexts[$context]) || $this->sessionContexts[$context] != $this->get('id')) {
                $ua->set('failedlogincount', 0);
                $ua->set('logincount', $ua->logincount + 1);
                $ua->set('lastlogin', $ua->thislogin);
                $ua->set('thislogin', time());
                $ua->set('sessionid', session_id());
                $ua->save();
            }
        }
        $this->sessionContexts[$context]= $this->get('id');
        $_SESSION['modx.user.contextTokens']= $this->sessionContexts;
    }

    /**
     * Removes a user session context.
     *
     * @param string|array $context The context key or an array of context keys.
     */
    function removeSessionContext($context) {
        if (is_array($this->sessionContexts)) {
            $contextToken= array ();
            if (is_array($context)) {
                foreach ($context as $ctx) {
                    $contextToken[$ctx]= $this->get('id');
                    $this->removeSessionContextVars($ctx);
                }
            } else {
                $contextToken[$context]= $this->get('id');
                $this->removeSessionContextVars($context);
            }
            $this->sessionContexts= array_diff_assoc($this->sessionContexts, $contextToken);
        }
    }

    /**
     * Removes the session vars associated with a specific context.
     *
     * @param string $context The context key.
     */
    function removeSessionContextVars($context) {
        if (is_string($context) && !empty ($context)) {
            switch ($context) {
                case 'web' :
                    unset ($_SESSION['webShortname']);
                    unset ($_SESSION['webFullname']);
                    unset ($_SESSION['webEmail']);
                    unset ($_SESSION['webValidated']);
                    unset ($_SESSION['webInternalKey']);
                    unset ($_SESSION['webValid']);
                    unset ($_SESSION['webUser']);
                    unset ($_SESSION['webFailedlogins']);
                    unset ($_SESSION['webLastlogin']);
                    unset ($_SESSION['webnrlogins']);
                    unset ($_SESSION['webUsrConfigSet']);
                    unset ($_SESSION['webUserGroupNames']);
                    unset ($_SESSION['webDocgroups']);
                    break;

                case 'mgr' :
                    unset ($_SESSION['usertype']);
                    unset ($_SESSION['mgrShortname']);
                    unset ($_SESSION['mgrFullname']);
                    unset ($_SESSION['mgrEmail']);
                    unset ($_SESSION['mgrValidated']);
                    unset ($_SESSION['mgrInternalKey']);
                    unset ($_SESSION['mgrFailedlogins']);
                    unset ($_SESSION['mgrLastlogin']);
                    unset ($_SESSION['mgrLogincount']); // login count
                    unset ($_SESSION['mgrRole']);
                    unset ($_SESSION['mgrDocgroups']);
                    break;

                default :
                    break;
            }
            $this->removeSessionCookie($context);
        }
    }

    function removeSessionCookie($context) {

    }

    /**
     * Checks if the user has a specific session context.
     */
    function hasSessionContext($context) {
        $hasContext= false;
        if (is_array($this->sessionContexts)) {
            $contextTokens= array ();
            if (is_array($context)) {
                foreach ($context as $ctx) {
                    $contextTokens[$ctx]= $this->get('id');
                }
            }
            elseif (is_string($context)) {
                $contextTokens[$context]= $this->get('id');
            }
            $hasContext= (count(array_intersect_assoc($contextTokens, $this->sessionContexts)) == count($contextTokens));
        }
        return $hasContext;
    }

    function countMessages($read = '') {
        if ($read == 'read') { $read = 1; } elseif ($read == 'unread') { $read = 0; }
        $criteria= array ('recipient' => $this->get('id'));
        if ($read) {
            $criteria['messageread']= $read;
        }
        return $this->xpdo->getCount('modUserMessage', $criteria);
    }

	/**
	 * Gets settings in array format
	 */
	function getSettings() {
		$settings = array();
		$uss = $this->xpdo->getCollection('modUserSetting',array('user' => $this->id));
		foreach ($uss as $us) {
			$settings[$us->get('key')] = $us->value;
		}
		$this->settings = $settings;
		return $settings;
	}
}
?>