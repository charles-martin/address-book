<?php

    /**
     * Created by PhpStorm.
     * User: Charles.Martin
     * Date: 8/11/2015
     * Time: 10:48 AM
     */

    use \Phalcon\Mvc\Model;

    class Address extends Model {
        public $id;
        public $name;
        public $street;
        public $city;
        public $state;
        public $zip;
        public $phone;
        public $cell;
        public $fax;
        public $email;
    }