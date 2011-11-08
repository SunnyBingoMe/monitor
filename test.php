<?php 
require 'sunny_function.php';
?>
<?php
//echo phpinfo();
?>
<!DOCTYPE unspecified PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http: //www.w3.org/TR/html4/loose.dtd">
<?php 
$output = shell_exec("shutdown -P now");
echo $output;
?>
<?php 
//echo "<img src=\"graph_img_src_url.php\" />";
//$output =  shell_exec("snmpget 192.168.184.10 -v2c -cpublic 1.3.6.1.2.1.1.1.0 ");
//echo ( $output);
?>
<?php

    for( $i = 0; $i < 3; ++ $i )
    {
    	
	        echo ' [', $i, '] ';
	        switch( $i )
	        {
	        	case 0: 
	        		echo 'zero'; break;
	            case 1:  
	            	echo 'one' ; continue 2;
	            case 2:  
	            	echo 'two' ; break;
	        }
	        echo ' <' , $i, '> ';
	        brn();
    	
    }

?>

<?php

/*continue also accepts an optional numeric argument which 
	tells it how many levels of enclosing loops it should skip.*/

for($k=0;$k<2;$k++){ //First loop
	echo "k: $k\n".returnBrn();
	for($j=0;$j<4;$j++){//Second loop
		echo "j: $j\n".returnBrn();
		for($i=0;$i<6;$i++){//Third loop
      		echo "i: $i\n".returnBrn();	
		    if($i>2){
		    	echo" i > 2 .";
		    	continue 2;// If $i >2 ,Then it skips to the Second loop(level 2),And starts the next step,
		    }
		    echo "_i: $i\n".returnBrn();
		}
		echo "_j: $j\n".returnBrn();
	}
	echo "_k: $k\n".returnBrn();
}

?>