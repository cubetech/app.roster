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
   
    function gen_right_btn($right, $righthref, $righticon, $rightbtn, $alt='', $p=true) {
        
        if($alt=='') {
            $alt = $right;
        }
        
        $btn = '
                    <a class="' . $rightbtn . '" href="' . $righthref . '" title="' . $alt . '">
                        <i class="' . $righticon . '"></i> ' . $right . '
                    </a>
        ';
        
        if($p == true) {
            return '
               <p class="pull-right">
                    ' . $btn . '
               </p>
            ';
        } else {
            return $btn;
        }
    
    }

   function addbutton($name='', $href='', $class='btn-danger', $icon='icon-remove icon-white') {
       if(!@is_array($_SESSION['morebuttons'])) {
           $_SESSION['morebuttons'] = array();
       }
       
       array_push($_SESSION['morebuttons'], array('name'=>$name, 'href'=>$href, 'class'=>$class, 'icon'=>$icon));
       
   }

   function linenav($left='', $lefthref='', $right='', $righthref='', $lefticon='icon-chevron-left', $righticon='icon-plus icon-white', $leftbtn='', $rightbtn='btn-primary') {
    
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
            
            $lbtn = gen_left_btn($left, $lefthref, $lefticon, 'btn ' . $leftbtn);
            $_SESSION['linenav'] = $lbtn;
            echo $lbtn;
            
        } //if
        
        // Right button
        
        if((isset($right) && $right!='') || @is_array($_SESSION['morebuttons'])) {
            
            $rbtn = '';
        
            if(isset($right) && $right!='') {
                if(!isset($righthref)) {
                    error('own', 'You don\'t set the righthref in the function breadnav().');
                } //if
                
                $rbtn .= gen_right_btn($right, $righthref, $righticon, 'btn ' . $rightbtn);
            }
            if(@is_array($_SESSION['morebuttons'])) {
                $_SESSION['morebuttons'] = array_reverse($_SESSION['morebuttons']);
                foreach($_SESSION['morebuttons'] as $b) {
                    $rbtn .= gen_right_btn($b['name'], $b['href'], $b['icon'], 'btn ' . $b['class']);
                }
            }
            $_SESSION['linenav'] .= $rbtn;
            $_SESSION['morebuttons'] = array();
            echo $rbtn;
            
        } //if
        
        // Repair row-fluid
        
        echo '<br /><br /><br />
              </div>
              <div class="row-fluid">';

    }
    
?>