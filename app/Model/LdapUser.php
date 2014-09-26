<?php  
class LdapUser extends AppModel 
{ 
    var $name = 'LdapUser'; 
    var $useTable = false; 

    var $host       = 'ldap.example.com'; 
    var $port       = 389; 
    var $baseDn = 'dc=example,dc=com'; 
    var $user       = 'cn=admin,dc=example,dc=com'; 
    var $pass       = 'password'; 

    var $ds; 
} 

function __construct() 
{ 
    parent::__construct(); 
    $this->ds = ldap_connect($this->host, $this->port); 
    ldap_set_option($this->ds, LDAP_OPT_PROTOCOL_VERSION, 3); 
    ldap_bind($this->ds, $this->user, $this->pass); 
} 

function __destruct() 
{ 
    ldap_close($this->ds); 
} 

function auth($uid, $password) 
{ 
    $result = $this->findAll('uid', $uid); 

    if($result[0]) 
    { 
        if (ldap_bind($this->ds, $result[0]['dn'], $password)) 
            { 
                return true; 
            } 
            else 
            { 
                return false; 
            } 
    } 
    else 
    { 
        return false; 
    } 
} 


?>