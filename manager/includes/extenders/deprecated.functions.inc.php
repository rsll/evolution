<?php
$this->old = new OldFunctions();
class OldFunctions {

    function dbConnect()                 {global $modx;       $modx->db->connect();$modx->rs = $modx->db->conn;}
    function dbQuery($sql)               {global $modx;return $modx->db->query($sql);}
    function recordCount($rs)            {global $modx;return $modx->db->getRecordCount($rs);}
    function fetchRow($rs,$mode='assoc') {global $modx;return $modx->db->getRow($rs, $mode);}
    function affectedRows($rs)           {global $modx;return $modx->db->getAffectedRows($rs);}
    function insertId($rs)               {global $modx;return $modx->db->getInsertId($rs);}
    function dbClose()                   {global $modx;       $modx->db->disconnect();}

    function makeList($array, $ulroot= 'root', $ulprefix= 'sub_', $type= '', $ordered= false, $tablevel= 0) {
        // first find out whether the value passed is an array
        if (!is_array($array)) {
            return "<ul><li>Bad list</li></ul>";
        }
        if (!empty ($type)) {
            $typestr= " style='list-style-type: $type'";
        } else {
            $typestr= "";
        }
        $tabs= "";
        for ($i= 0; $i < $tablevel; $i++) {
            $tabs .= "\t";
        }
        $listhtml= $ordered == true ? $tabs . "<ol class='$ulroot'$typestr>\n" : $tabs . "<ul class='$ulroot'$typestr>\n";
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $listhtml .= $tabs . "\t<li>" . $key . "\n" . $this->makeList($value, $ulprefix . $ulroot, $ulprefix, $type, $ordered, $tablevel +2) . $tabs . "\t</li>\n";
            } else {
                $listhtml .= $tabs . "\t<li>" . $value . "</li>\n";
            }
        }
        $listhtml .= $ordered == true ? $tabs . "</ol>\n" : $tabs . "</ul>\n";
        return $listhtml;
    }


    function getUserData() {
        $client['ip'] = $_SERVER['REMOTE_ADDR'];
        $client['ua'] = $_SERVER['HTTP_USER_AGENT'];
    	return $client;
    }

    # Returns true, install or interact when inside manager
    // deprecated
    function insideManager() {
        $m= false;
        if( defined('IN_MANAGER_MODE') && IN_MANAGER_MODE === true) {
            $m= true;
            if (defined('SNIPPET_INTERACTIVE_MODE') && SNIPPET_INTERACTIVE_MODE == 'true')
                $m= "interact";
            else
                if (defined('SNIPPET_INSTALL_MODE') && SNIPPET_INSTALL_MODE == 'true')
                    $m= "install";
        }
        return $m;
    }

    // deprecated
    function putChunk($chunkName) { // alias name >.<
    	global $modx;
        return $modx->getChunk($chunkName);
    }

    function getDocGroups() {
    	global $modx;
        return $modx->getUserDocGroups();
    } // deprecated

    function changePassword($o, $n) {
        return changeWebUserPassword($o, $n);
    } // deprecated

    function userLoggedIn() {
    	global $modx;
        $userdetails= array ();
        if ($modx->isFrontend() && isset ($_SESSION['webValidated'])) {
            // web user
            $userdetails['loggedIn']= true;
            $userdetails['id']= $_SESSION['webInternalKey'];
            $userdetails['username']= $_SESSION['webShortname'];
            $userdetails['usertype']= 'web'; // added by Raymond
            return $userdetails;
        } else
            if ($modx->isBackend() && isset ($_SESSION['mgrValidated'])) {
                // manager user
                $userdetails['loggedIn']= true;
                $userdetails['id']= $_SESSION['mgrInternalKey'];
                $userdetails['username']= $_SESSION['mgrShortname'];
                $userdetails['usertype']= 'manager'; // added by Raymond
                return $userdetails;
            } else {
                return false;
            }
    }

    function getFormVars($method= "", $prefix= "", $trim= "", $REQUEST_METHOD) {
        //  function to retrieve form results into an associative array
    	global $modx;
        $results= array ();
        $method= strtoupper($method);
        if ($method == "")
            $method= $REQUEST_METHOD;
        if ($method == "POST")
            $method= & $_POST;
        elseif ($method == "GET") $method= & $_GET;
        else
            return false;
        reset($method);
        foreach ($method as $key => $value) {
            if (($prefix != "") && (substr($key, 0, strlen($prefix)) == $prefix)) {
                if ($trim) {
                    $pieces= explode($prefix, $key, 2);
                    $key= $pieces[1];
                    $results[$key]= $value;
                } else
                    $results[$key]= $value;
            }
            elseif ($prefix == "") $results[$key]= $value;
        }
        return $results;
    }

    /**
     * Displays a javascript alert message in the web browser
     *
     * @param string $msg Message to show
     * @param string $url URL to redirect to
     */
    function webAlert($msg, $url= "") {
    	global $modx;
        $msg= addslashes($modx->db->escape($msg));
        if (substr(strtolower($url), 0, 11) == "javascript:") {
            $act= "__WebAlert();";
            $fnc= "function __WebAlert(){" . substr($url, 11) . "};";
        } else {
            $act= ($url ? "window.location.href='" . addslashes($url) . "';" : "");
        }
        $html= "<script>$fnc window.setTimeout(\"alert('$msg');$act\",100);</script>";
        if ($modx->isFrontend())
            $modx->regClientScript($html);
        else {
            echo $html;
        }
    }

    ########################################
    // END New database functions - rad14701
    ########################################
}
