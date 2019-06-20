<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('test_method'))
{
    function test_method($var = '')
    {
        return $var;
    }   

    function gen_ekusd($ccy, $amount, $date){
    	$ci=& get_instance();
    	$ci->load->database(); 
    	$sql = "select * from closing_rate where ccy ='".$ccy."' AND tanggal='".$date."'"; 
    	$row_ccy = $ci->db->query($sql)->row();
    	// var_dump($sql);

    	$sql = "select * from closing_rate where ccy = 'USD' AND tanggal='".$date."'"; 
    	$row_usd = $ci->db->query($sql)->row();

    	$result = 0;
    	if ($ccy == 'USD'){
    		$result = $amount;
    	} else {
    		$amount_idr = $amount * $row_ccy->closingrate;
    		$result = $amount_idr / $row_usd->closingrate;
    	}
    	return $result;
    }
}
