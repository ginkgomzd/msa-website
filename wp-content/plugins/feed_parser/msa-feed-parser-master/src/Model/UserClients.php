<?php
/**
 * Created by PhpStorm.
 * User: ljubi
 * Date: 10/19/2018
 * Time: 8:34 PM
 */

namespace MainStreetAdvocates\Model;



class UserClients extends \GinkgoStreetLabs\Model {

	public $id;
	public $client;

	function __construct() {
		parent::__construct();
	}

	/**
	 *
	 */
	public function insertClientStates() {
		$states = json_decode( '[
			    {
			        "name": "Alabama",
			        "abbreviation": "AL"
			    },
			    {
				    "name": "Alaska",
			        "abbreviation": "AK"
			    },
			    {
				    "name": "American Samoa",
			        "abbreviation": "AS"
			    },
			    {
				    "name": "Arizona",
			        "abbreviation": "AZ"
			    },
			    {
				    "name": "Arkansas",
			        "abbreviation": "AR"
			    },
			    {
				    "name": "California",
			        "abbreviation": "CA"
			    },
			    {
				    "name": "Colorado",
			        "abbreviation": "CO"
			    },
			    {
				    "name": "Connecticut",
			        "abbreviation": "CT"
			    },
			    {
				    "name": "Delaware",
			        "abbreviation": "DE"
			    },
			    {
				    "name": "District Of Columbia",
			        "abbreviation": "DC"
			    },
			    {
				    "name": "Federated States Of Micronesia",
			        "abbreviation": "FM"
			    },
			    {
				    "name": "Florida",
			        "abbreviation": "FL"
			    },
			    {
				    "name": "Georgia",
			        "abbreviation": "GA"
			    },
			    {
				    "name": "Guam",
			        "abbreviation": "GU"
			    },
			    {
				    "name": "Hawaii",
			        "abbreviation": "HI"
			    },
			    {
				    "name": "Idaho",
			        "abbreviation": "ID"
			    },
			    {
				    "name": "Illinois",
			        "abbreviation": "IL"
			    },
			    {
				    "name": "Indiana",
			        "abbreviation": "IN"
			    },
			    {
				    "name": "Iowa",
			        "abbreviation": "IA"
			    },
			    {
				    "name": "Kansas",
			        "abbreviation": "KS"
			    },
			    {
				    "name": "Kentucky",
			        "abbreviation": "KY"
			    },
			    {
				    "name": "Louisiana",
			        "abbreviation": "LA"
			    },
			    {
				    "name": "Maine",
			        "abbreviation": "ME"
			    },
			    {
				    "name": "Marshall Islands",
			        "abbreviation": "MH"
			    },
			    {
				    "name": "Maryland",
			        "abbreviation": "MD"
			    },
			    {
				    "name": "Massachusetts",
			        "abbreviation": "MA"
			    },
			    {
				    "name": "Michigan",
			        "abbreviation": "MI"
			    },
			    {
				    "name": "Minnesota",
			        "abbreviation": "MN"
			    },
			    {
				    "name": "Mississippi",
			        "abbreviation": "MS"
			    },
			    {
				    "name": "Missouri",
			        "abbreviation": "MO"
			    },
			    {
				    "name": "Montana",
			        "abbreviation": "MT"
			    },
			    {
				    "name": "Nebraska",
			        "abbreviation": "NE"
			    },
			    {
				    "name": "Nevada",
			        "abbreviation": "NV"
			    },
			    {
				    "name": "New Hampshire",
			        "abbreviation": "NH"
			    },
			    {
				    "name": "New Jersey",
			        "abbreviation": "NJ"
			    },
			    {
				    "name": "New Mexico",
			        "abbreviation": "NM"
			    },
			    {
				    "name": "New York",
			        "abbreviation": "NY"
			    },
			    {
				    "name": "North Carolina",
			        "abbreviation": "NC"
			    },
			    {
				    "name": "North Dakota",
			        "abbreviation": "ND"
			    },
			    {
				    "name": "Northern Mariana Islands",
			        "abbreviation": "MP"
			    },
			    {
				    "name": "Ohio",
			        "abbreviation": "OH"
			    },
			    {
				    "name": "Oklahoma",
			        "abbreviation": "OK"
			    },
			    {
				    "name": "Oregon",
			        "abbreviation": "OR"
			    },
			    {
				    "name": "Palau",
			        "abbreviation": "PW"
			    },
			    {
				    "name": "Pennsylvania",
			        "abbreviation": "PA"
			    },
			    {
				    "name": "Puerto Rico",
			        "abbreviation": "PR"
			    },
			    {
				    "name": "Rhode Island",
			        "abbreviation": "RI"
			    },
			    {
				    "name": "South Carolina",
			        "abbreviation": "SC"
			    },
			    {
				    "name": "South Dakota",
			        "abbreviation": "SD"
			    },
			    {
				    "name": "Tennessee",
			        "abbreviation": "TN"
			    },
			    {
				    "name": "Texas",
			        "abbreviation": "TX"
			    },
			    {
				    "name": "Utah",
			        "abbreviation": "UT"
			    },
			    {
				    "name": "Vermont",
			        "abbreviation": "VT"
			    },
			    {
				    "name": "Virgin Islands",
			        "abbreviation": "VI"
			    },
			    {
				    "name": "Virginia",
			        "abbreviation": "VA"
			    },
			    {
				    "name": "Washington",
			        "abbreviation": "WA"
			    },
			    {
				    "name": "West Virginia",
			        "abbreviation": "WV"
			    },
			    {
				    "name": "Wisconsin",
			        "abbreviation": "WI"
			    },
			    {
				    "name": "Wyoming",
			        "abbreviation": "WY"
			    }
			]' );
		foreach ( $states as $state ) {
			$query = self::$conn->prepare(
				"INSERT INTO `client_settings` (`type`, `category`, `client_id`,`ismailactive`,`isfrontactive`) VALUES ('state', '{$state->abbreviation}',{$this->id},1,1)"
			);
			$this->bindQueryParams( $query );
			$query->execute();
		}
	}

	public function returnID() {
		$query = self::$conn->prepare(
			'SELECT ID FROM `user_clients` WHERE client = "' . $this->client . '"'
		);
		$this->bindQueryParams( $query );
		$query->execute();
		$this->id = $query->fetchColumn();

		return $this->id;
	}

	protected function getTableName() {
		return 'user_clients';
	}

}