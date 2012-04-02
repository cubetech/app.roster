<?php

    function gen_left_btn($left, $lefthref, $lefticon, $leftbtn) {
    
        return '
                <p class="pull-left">
                	<a class="' . $leftbtn . '" href="' . $lefthref . '">
                	    <i class="' . $lefticon . '"></i> ' . $left . '
                	</a>
                </p>
        ';
        
   } //function 
   
   function gen_right_btn($right, $righthref, $righticon, $rightbtn) {
       
       return '
               <p class="pull-right">
                   <a class="' . $rightbtn . '" href="' . $righthref . '">
                       <i class="' . $righticon . '"></i> ' . $right . '
                   </a>
               </p>
       ';
   
   }

    function linenav($left='', $lefthref='', $right='', $righthref='', $lefticon='icon-chevron-left', $righticon='icon-plus icon-white', $leftbtn='btn', $rightbtn='btn btn-primary') {
    
        // Check if session call
        
        echo '</div>
        <div class="row-fluid">';
        
        if(!isset($left) || $left == '') {
            echo $_SESSION['linenav'];
            return true;
        } //if
    
        // Left button
    
        if(isset($left) && $left!='') {
        
            if(!isset($lefthref)) {
                error('own', 'You don\'t set the lefthref in the function breadnav().');    
            } //if
            
            $lbtn = gen_left_btn($left, $lefthref, $lefticon, $leftbtn);
            $_SESSION['linenav'] = $lbtn;
            echo $lbtn;
            
        } //if
        
        // Right button
        
        if(isset($right) && $right!='') {
        
            if(!isset($righthref)) {
                error('own', 'You don\'t set the righthref in the function breadnav().');
            } //if
            
            $rbtn = gen_right_btn($right, $righthref, $righticon, $rightbtn);
            $_SESSION['linenav'] .= $rbtn;
            echo $rbtn;
            
        } //if
        
        // Repair row-fluid
        
        echo '<br /><br /><br />
              </div>
              <div class="row-fluid">';

    }
    
?>