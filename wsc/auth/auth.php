<?php

namespace wsc\auth;
use wsc\user\User;
use wsc\application\Application;

/**
 *
 * Auth (2013 - 12 - 01)
 * 
 * Klasse um Benutzer zu authentifizieren.
 * 
 * @author 		Michael Strasser
 * @name 		Auth
 * @version		1.0
 * @copyright	2013 - Michael Strasser
 * @license		Alle Rechte vorbehalten.
 *        
 */
class Auth 
{
	private $auth		= NULL;
	private $account	= NULL;
	private $cookie		= NULL;
	private $userid		= NULL;
	
	private $errors		= NULL;
	
	private $application;
	private $db;
	
	public function __construct(Application &$application)
	{
		if($application instanceof Application)
		{
			$this->application	= $application;
		}
		
		$this->db				= $this->application->load("Database");
		
		$this->recognizeUser();
	}
	
	private function recognizeUser()
	{
		//Ein neuer Benutzer besucht die Website
		if(!isset($_SESSION['recognizedUser']))
		{
			//Kann er �ber ein Cookie eingeloggt werden?
			if($this->checkCookie() == false)
			{
				//Nein, dann muss es ein Gast sein.
				$this->guest();
			}
		}
		//...wir haben den Benutzer bereits gekannt.
		else
		{
			//$this->getUser();
		}
	}
	
	public function login($account, $auth, $cookie = false)
	{
		$this->account	= $account;
		$this->auth		= $auth;
		$this->cookie	= $cookie;
		
		
		$this->checkUserData();
		
		if(empty($this->errors))
		{
			$this->checkAccountBans();
			$this->checkAccountActivated();
		}

		if(empty($this->errors))
		{
			$_SESSION['loggedIn']	= true;
			$_SESSION['userid']		= $this->userid;
			
			if($this->cookie === true)
			{
				$this->setLoginCookie();
			}
			
			$this->newUser();
			$this->writeLoginProtocol();
			
			return true;
		}
		
		return false;
	}
	
	public function logout()
	{
		session_unset();
		session_destroy();
		setcookie("login", "", time()-1);
		unset($_COOKIE['login']);
		
		$this->recognizeUser();
	}
	
	public function getUser()
	{
		return unserialize($_SESSION['user']);
	}
	
	public function isAuthenticated()
	{
		if($_SESSION['recognizedUser'] === true)
		{
			return true;
		}
		else 
		{
			return false;
		}
	}
	
	public function isLoggedIn()
	{
		if(isset($_SESSION['loggedIn']))
		{
			if($_SESSION['loggedIn'] === true)
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
	
	private function checkCookie()
	{
	
		if(isset($_COOKIE['login']))
		{
			$sql	= "SELECT * FROM userdata WHERE session_id = '" . $_COOKIE['login'] . "'";
			$res	= $this->db->query($sql) or die("SQL-Fehler in Datei: " . ___FILE___ . ":" . ___LINE___ . "<br /><br />" . $this->db->error);
			$num	= $res->num_rows;
				
			if($num == 1)
			{
				$row	= $res->fetch_assoc();
				$this->login($row['username'], $row['session_id']);
	
				return true;
			}
			elseif($num == 0)
			{
				$this->logout();
				$this->addError("Login fehlgeschlagen. Cookie fehlerhaft.");
			}
			elseif($num > 1)
			{
				$this->logout();
				$this->addError("Login fehlgeschlagen - Sicherheitsrisiko endeckt! Bitte manuell einloggen.");
			}
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * Es wird festgestellt, ob ein Account mit den �bermittelten Benutzerdaten existiert.
	 *
	 * @return (bool) true oder (bool) false
	 * @since 1.0
	 */
	private function checkUserData()
	{
		if(!empty($this->account) && !empty($this->auth))
		{
			$sql	= "
					SELECT
						*
					FROM
						userdata
					WHERE
						(username = '".$this->account."' OR email = '".$this->account."') AND (password = '". md5($this->auth)."' OR session_id = '" . $this->auth . "')
					LIMIT
						1";
				
			$res	= $this->db->query($sql) or die($this->db->error);
			$row	= $res->fetch_assoc();
			$num	= $res->num_rows;
	
			if($num == 1)
			{
				$this->userid	= $row['id'];
				return true;
			}
			else
			{
				$this->addError("Anmeldeinformationen fehlerhaft! Bitte die Eingaben &uuml;berpr&uuml;fen.");
			}
		}
		else
		{
			$this->addError("Anmeldung fehlgeschlagen. Keine Accountinformationen &uuml;bermittelt.");
		}
	}
	
	/**
	 * Es wird festgestellt, ob der Account von einem Administrator gesperrt wurde.
	 *
	 * @return (bool) true oder (bool) false
	 * @since 1.0
	 */
	private function checkAccountBans()
	{
		$sql	= "	SELECT
						*
					FROM
						account_lock
					WHERE
						userid = " . $this->userid;
	
		$res	= $this->db->query($sql) or die($this->db->error);
		$row	= $res->fetch_assoc();
		$num	= $res->num_rows;
	
		if($num === 0)
		{
			return true;
		}
		else
		{
			$this->addError("Benutzerkonto ist gesperrt.");
		}
	}

	/**
	 * Es wird festgestellt, ob der Account aktiviert wurde.
	 *
	 * @return (bool) true oder (bool) false
	 * @since 1.0
	 */
	private function checkAccountActivated()
	{
		$sql	= "	SELECT
						*
					FROM
						account_activation
					WHERE
						userid	= " . $this->userid;
	
		$res	= $this->db->query($sql) or die($this->db->error);
		$row	= $res->fetch_assoc();
	
		if($row['active'] == 1)
		{
			return true;
		}
		else
		{
			$this->addError("Dieses Benutzerkonto wurde nicht aktiviert. Bitte Maileingang &uuml;berpr&uuml;fen und Konto aktivieren.");
		}
	
	}
	
	/**
	 * Erzeugt ein Login-Cookie
	 *
	 * @since 1.1
	 */
	private function setLoginCookie()
	{
		$cookie	= setcookie("login", session_id(), time()+(60*60*24*30));
		$sql	= "UPDATE userdata SET session_id = '". session_id() ."' WHERE id = ". $this->userid;
		$res	= $this->db->query($sql) or die("SQL-Fehler in Datei: " . ___FILE___ . ":" . ___LINE___ . "<br /><br />" . $this->db->error);
	
	}
	
	private function guest()
	{
		$sql	= "SELECT * FROM userdata WHERE username = 'guest'";
		$res	= $this->db->query($sql) or die($this->db->error);
		$row	= $res->fetch_assoc();
		$num	= $res->num_rows;
	
		if($num == 1)
		{
			$this->userid		= $row['id'];
			$this->newUser();
		}
		else
		{
			throw new \Exception("Es ist kein Gastkonto vorhanden.");
		}
	}
	
	private function newUser()
	{
		$_SESSION['recognizedUser'] = true;
		$_SESSION['user']	= serialize(new User($this->application, $this->userid));
	
	}
	
	/**
	 * Es wird ein Eintrag in das Login-Protokoll erstellt.
	 *
	 * @since 1.0
	 */
	private function writeLoginProtocol()
	{
		$sql	= "	INSERT login_protocol
										(
											userid,
											ip,
											time
										)
					VALUES
						(
							'" . $this->userid . "',
							'" . $_SERVER['REMOTE_ADDR'] . "',
							'" . time() . "'
						)";
		$res	= $this->db->query($sql) or die($this->db->error);
	}
	
	private function addError($message)
	{
		$this->errors[]	= $message;
	}
	
	public function getErrors()
	{
		if(!empty($this->errors))
		{
			return $this->errors;
		}
		
		return false;
	}
}

?>