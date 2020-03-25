<?php
/**
 * The base Post class for our site
 */

namespace Content;

class Business extends Post {

	public $PostClass = '\Content\Post';

	/**
	 * Build a single line address string
	 */
	public function address_string(){

		$parts = [];

		if ($this->street_address){
			$parts[] = $this->street_address;
		}

		if ($this->city){
			$parts[] = $this->city;
		}

		if ($this->state){
			$parts[] = $this->state;
		}

		$address_string = implode(', ', $parts);

		if ($this->zip_code){
			$address_string = $address_string . ' ' . $this->zip_code;
		}

		return trim($address_string);

	}

}