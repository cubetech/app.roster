<?php

    define('dire', '../../');
    include(dire . '_env/exec.php');
    
    $item_id = vGET('item_id');
    
    if(isset($item_id) && $item_id!='') {
        $query = mysql_query('SELECT i.*, 
                                     c.name AS categoryname,
                                     b.barcode as fullbarcode,
                                     s.status as statusname
                                FROM item i 
                                LEFT JOIN 
                                category c ON (i.category = c.id)
                                LEFT JOIN
                                barcode b ON (i.barcode = b.id)
                                LEFT JOIN
                                status s ON (i.status = s.id)
                                WHERE i.id="'.$item_id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
        $item = mysql_fetch_array($query);
        if(!$item) {
            error('own', 'Dieser Artikel wurde nicht gefunden.');
        } elseif($item['delete']!=0) {
            error('own', 'Dieser Artikel ist gel&ouml;scht. Bitte zuerst wiederherstellen.');
        }
    }
    
    $customer = array();
    $compcust = array();
    $query = mysql_query('SELECT * FROM `customer`') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    while($fetch=mysql_fetch_array($query)) {
        if(!$fetch['company'] || $fetch['company']=='') {
            array_push($customer, $fetch);
        } else {
            array_push($compcust, $fetch);
        }
    }
    
    $title = 'Paket erstellen';
    
    write_header($title);
    
    linenav('Zur&uuml;ck', '../', 'Speichern', 'javascript:if($(\'#form\').valid()) { document.getElementById(\'form\').submit(); }', 'icon-chevron-left', 'icon-fire icon-white');
    
    ?>
        </div>
        <form id="form" name="form" method="POST" enctype="multipart/form-data" action="save.php">
        <div class="row-fluid">
        	<div class="span3">
        		<div class="well sidebar-nav">
        			<ul class="nav nav-list">
        				<li class="nav-header">Kategorie</li>
        			</ul>
        		</div>
        	</div>
        	
        	<div class="span9">
                <h1><?=$title?></h1>
                
                <hr>
                
                <div class="row-fluid">
                    <div class="span6">
                    
                        <h2>Ausleihpaket</h2>
                        <br />
                        
                    </div>
                    
                        
                  <div class="span6">
                  
                    <h2>Kunde</h2>
                	    <br />
                	    <select id="customer" name="customer">
                	        <option id="manual" value="manual">Manuell eingeben</option>
                	        <option>--------------------</option>
                                <?
                                    foreach($compcust as $c) {
                                        echo '<option value="'.$c['id'].'">'.$c['company'].' - '.$c['prename'].' '.$c['name'].'</option>';
                                    }

                                    foreach($customer as $c) {
                                        echo '<option value="'.$c['id'].'">'.$c['prename'].' '.$c['name'].'</option>';
                                    }
                                ?>
                	    </select>
                	    
                	    <div id="manual_form">
                	    
                	        <label for="name">Vorname</label><input id="prename" name="data[prename]" type="text" value="" class="required" minlength="2" />
                	        <label for="name">Name</label><input id="name" name="data[name]" type="text" value="" class="required" minlength="2" />
                	        <label for="name">Firma</label><input id="company" name="data[company]" type="text" value="" />
                	        <label for="name">Adresse</label><input id="address" name="data[address]" type="text" value="" class="required" minlength="2" />
                	        <label for="name">PLZ/Ort</label><input id="zip" name="data[zip]" type="text" value="" style="width: 4em;"/> <input id="location" name="data[location]" type="text" value="" class="required" minlength="2" style="width: 11.1em;" />
                	        <label for="name">Telefon</label><input id="phone" name="data[phone]" type="text" value="" />
                	        <label for="name">Mailadresse</label><input id="mail" name="data[mail]" type="text" value="" />
                	        <label for="name">Web</label><input id="web" name="data[web]" type="text" value="" />
                	        <input type="hidden" id="hidden" name="data[hidden]" value="false" />
                	    
                	    </div>
                	    
                        <script type="text/javascript">
                            $('#customer').change(function() {
                                if($(this).find('option:selected').attr('id')!='manual') {
                                    $('#manual_form').hide();
                                    $('#hidden').val('true');
                                } else {
                                    $('#manual_form').show();
                                    $('#hidden').val('false');
                                }
                            });
                        </script>
                        
                  </div><!--/span-->
                </div>
                <div class="row-fluid">
                    
                    <div class="span12">
                    
                      <h2>Artikel</h2>
                      <br />
                      Artikel per Barcode hinzuf&uuml;gen:&nbsp;&nbsp;&nbsp; <input type="text" id="barcode" name="barcode" />
                      <table class="table table-striped" id="items">
                          <thead>
                              <tr>
                                  <th>#</th>
                                  <th>Barcode</th>
                                  <th>Kategorie</th>
                                  <th class="big5">Artikel</th>
                                  <th>Status</th>
                                  <th class="tableicons"></th>
                              </tr>
                          </thead>
                          <tbody id="itembody">
                        <?php
                            
                        $i = $item;
                        
                            echo '
                                    <tr id="'.$i['id'].'">
                                        <td>'.$i['id'].'</td>
                                        <td><a href="'.dire.'barcode/detail/?id='.$i['barcode'].'">'.$i['fullbarcode'].'</a></td>
                                        <td><a href="'.dire.'category/?id='.$i['category'].'">'.$i['categoryname'].'</a></td>
                                        <td><a href="'.dire.'item/detail/?id='.$i['id'].'">'.$i['name'].'</a></td>
                                        <td>' . $i['statusname'] . '</td>
                                        <td>' . 
                                        gen_right_btn('', 'javascript:void(0);', 'icon-remove icon-white', 'btn btn-mini btn-danger" id="'.$i['id'].'', 'Artikel l&ouml;schen', false) . '</td>
                                    </tr>
                                ';

                        ?>
                        </tbody>
                    </table>
                    
                    <div id="hiddeninputs" style="width: 0; height: 0;">
                        <input type="hidden" id="hidden<?=$i['id']?>" name="item[]" value="<?=$i['id']?>" />
                    </div>
                    
                    <script type="text/javascript">
                       $('.btn').click(function(){
                           $($(this).closest("tr")).remove();
                           $("#hidden" + $(this).attr('id')).remove();
                       });
                       $(document).ready(function() { 
                           $('#barcode').bind('keypress', function(event) {
                               var code=event.charCode || event.keyCode;
                               if(code && code == 13) { 
                                   $("#form").ajaxForm({
                                        url: 'add.php',
                                        success: showResponse,
                                    }).submit();
                                    function showResponse(responseText, statusText, xhr, $form)  {
                                        var item = eval('(' + responseText + ')');
                                        if($("#" + item.id).length == 0) {
                                            $("#itembody").append('<tr id="' + item.id + '"><td>' + item.id + '</td><td><a href="' + item.dire + 'barcode/detail/?id=' + item.barcode + '">' + item.fullbarcode + '</a></td><td>bla</td><td><a href="' + item.dire + 'item/detail/?id=' + item.id + '">' + item.name + '</a></td><td>' + item.statusname + '</td><td><a class="btn btn-mini btn-danger" id="' + item.id + '" href="javascript:void(0);" title="Artikel l&ouml;schen"><i class="icon-remove icon-white"></i></a></td></tr>');
                                            $("#hiddeninputs").append('<input type="hidden" id="hidden' + item.id + '" name="item[]" value="' + $("#barcode").val() + '" />');
                                            $('.btn').click(function(){
                                                $($(this).closest("tr")).remove();
                                                $("#hidden" + $(this).attr('id')).remove();
                                            });
                                        }
                                    }
                                   $("#barcode").val('');
                               }
                           });
                       });
                    </script>
                      
                    </div><!--/span-->
                    
                </div>
                    
         	</div>
            </form>
        </div>
        <div class="row-fluid">
    	
    <?php
    
    linenav();    
    
    write_footer();
    
?>