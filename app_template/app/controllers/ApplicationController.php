<?php
class ApplicationController extends Controller
{
    function __construct($params) {
        // in this place you can add anything
        // that be call before any controller action
        parent::__construct($params);
    }
}
