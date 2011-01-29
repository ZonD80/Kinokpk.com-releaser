<?php
class IPAddressSubnetSniffer {
	/*****************************************************************
	 * Author:  Jeff Silverman
	 * Date:    28-OCT-2005
	 * Version: 0.1
	 * Copyright пїЅ 2005, The Johns Hopkins University, All rights reserved.
	 * To Do:
	 *       Need to let the "okay ip addresses" be set after class
	 *       instantiation
	 *
	 * Description:
	 * This class determines whether a given IP address is in a given
	 * list of subnets.  The idea is to use this determination to allow/deny
	 * access to a PHP script based on IP address. Because of the way a
	 * particular web environment that I was using was set up, I was
	 * unable to use a .htaccess file for this task.  I could not
	 * find a built-in PHP function or functions to do this task, so this class
	 * was built.
	 * For example, to emulate the following .htaccess file:
	 *
	 * ".htaccess"
	 *************
	 *   order allow,deny
	 *   allow from 123.45.6.7
	 *   allow from 012.34.5.
	 *   deny from all
	 *************
	 *
	 * You would do the following using this class:
	 * 1) create a list (array) of "okay" subnets:
	 * $okay = array("123.45.6.7", "12.34.5");
	 *
	 * 2) Then instantiate the class:
	 * $ipsniff = new IPAddressSubnetSniffer( $okay );
	 *
	 * 3) Then test an IP address to see if it is "okay" (meaning, i
	 * the same subnet):
	 *
	 * $ip = "123.45.6.9";
	 * if ( $ipsniff->ip_is_allowed( $ip ) ){
	 *     echo "It's fine";
	 * }
	 * Real World example -- to test a user's IP address to allow/deny
	 access you can do something like this at the start of a particular
	 page:

	 <?php
	 $okay = array("123.45.6.7", "12.34.5");
	 $ipsniff = new IPAddressSubnetSniffer( $okay );
	 if ( ! $ipsniff->ip_is_allowed( $_SERVER['REMOTE_ADDR'] ) ){
	 echo "You are not allowed access to this page";
	 exit;
	 }
	 ?>

	 *****************************************************************/
	var $allowed_subnets;
	function IPAddressSubnetSniffer ($allowed_subnets) {
		// "allowed_subnets" defaults to ALL allowed subnets
		$this->allowed_subnets = $allowed_subnets;
		$this->setup_binary_subnet_list();
	}

	function setup_binary_subnet_list(){
		if ($this->allowed_subnets) {
			foreach ( $this->allowed_subnets as $sn ){
				unset($ip_octets);
				unset($mask_octets);
				if ( ! preg_match("/\//", $sn) ){
					// assume mask is "natural", create value for mask
					$ip_octets = preg_split("#\.#si", $sn);
					$ip_octets = array_pad($ip_octets, 4, 0);
					foreach ( $ip_octets as $o ){
						if ($o != 0){
							$mask_octets[] = 255;
						} else {
							$mask_octets[] = 0;
						}
					}
				} else {
					// Do masking according to notation
					list($ip, $mask) = explode("/", $sn);
					$ip_octets = preg_split("#\.#si", $ip);
					if ( preg_match("/^\d\d*$/", $mask) ){
						for ( $m = 0; $m < $mask; $m++){
							$mask_octets .= "1";
						}
						$mask_octets = str_pad($mask_octets, 32, '0');
						$mask_octets = preg_replace("/(\d{8})/", "$1.", $mask_octets);
						$mask_octets = preg_replace("/\.$/", "", $mask_octets);
						$mask_octets = preg_split("#\.#si", $mask_octets);
						foreach ( $mask_octets as $k => $v){
							$mask_octets[$k] = bindec($v);
						}
					} else {
						$mask_octets = preg_split("#\.#si", $mask);
					}
				}
				unset($bin_sn);
				unset($masks);
				for ( $o = 0; $o < count($ip_octets); $o++ ){
					$bin_sn[] = $this->get_bin($ip_octets[$o] & $mask_octets[$o]);
					$masks[] = $this->get_bin($mask_octets[$o]);
				}
				$subnets[] = join(".", $bin_sn);
				$mask_list[] = join(".", $masks);
			}

			$this->binary_subnet_ips = $subnets;
			$this->binary_subnet_masks = array_unique($mask_list);
		} else {
			$this->binary_subnet_masks = NULL;
		}
		return true;
	}

	function get_bin($number){
		return str_pad(decbin($number),8,'0',STR_PAD_LEFT);
		return decbin($number);
	}

	function ip2bin( $ip ){
		$ip_octets = preg_split("#\.#si", $ip);
		unset($bin_sn);
		for ( $o = 0; $o < count($ip_octets); $o++ ){
			$bin_sn[] = $this->get_bin($ip_octets[$o]);
		}
		return join(".", $bin_sn);
	}

	function bin2ip( $ip ){
		$ip_octets = preg_split("#\.#si", $ip);
		unset($bin_sn);
		for ( $o = 0; $o < count($ip_octets); $o++ ){
			$bin_sn[] = bindec($ip_octets[$o]);
		}
		return join(".", $bin_sn);
	}

	function apply_mask( $ip, $mask ){
		$ip_octets = preg_split("#\.#si", $ip);
		$mask_octets = preg_split("#\.#si", $mask);
		unset($bin_sn);
		for ( $o = 0; $o < count($ip_octets); $o++ ){
			$bin_sn[] = $this->get_bin(intval($ip_octets[$o]) & intval($mask_octets[$o]));
		}
		$subnet = join(".", $bin_sn);
		return $subnet;
	}

	function ip_is_allowed( $ip ){
		if (!$this->binary_subnet_masks) return false; else {
			foreach ( $this->binary_subnet_masks as $mask ){
				$subnet = $this->apply_mask( $ip, $this->bin2ip($mask) );
				$out[] = $subnet;
			}
			// Need to walk through all the possible subnets that the given IP
			// could be a part of.  Just use "in_array()"
			foreach ( $out as $net ){
				if ( in_array( $net, $this->binary_subnet_ips ) ) return true;
			}
			return false;
		}
	}
}
