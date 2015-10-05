<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of config
 *
 * @author ragavendran
 */
class config {
    function getConnection()
    {
        return mysqli_connect("localhost","ashineor_bharat","Ashine123Ashine123","ashineor_bharat");
    }
    
}
