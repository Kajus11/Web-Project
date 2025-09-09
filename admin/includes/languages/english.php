<?php

	function lang($phrase) {

		static $lang = array(

			// Navbar Links

			'HOME_ADMIN' 	=> 'Home',
			'CATEGORIES' 	=> 'Categories',
			'PRODUCTS' 		=> 'Products',
			'PRODUCTS' 	=> 'Products',
			'MEMBERS' 		=> 'Members',
			'FEEDBACKS'		=> 'Feedbacks',
			'STATISTICS' 	=> 'Statistics',
			'LOGS' 			=> 'Logs',
			'' => '',
			'' => '',
			'' => '',
			'' => '',
			'' => ''
		);

		return $lang[$phrase];

	}
