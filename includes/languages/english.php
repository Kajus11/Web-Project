<?php

    function lang($phrase) {

        static $lang = array(


            'HOME_ADMIN'     => 'Home',
            'CATEGORIES'     => 'Categories',
            'PRODUCTS'         => 'Products',
            'MEMBERS'         => 'Members',
            'COMMENTS'        => 'Comments',
            'STATISTICS'     => 'Statistics',
            'LOGS'             => 'Logs',
            '' => '',
            '' => '',
            '' => '',
            '' => '',
            '' => ''
        );

        return $lang[$phrase];

    }