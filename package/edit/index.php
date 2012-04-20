<?php

    define('dire', '../../');
    include(dire . '_env/exec.php');
    
    $id = vGET('id');
    
    $query = mysql_query('SELECT * FROM `package` WHERE `id`="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $package = mysql_fetch_array($query);
    
    if(!is_array($package)) {
        error('transfer');
    }
    
    $customer_id = $package['customer_id'];
    
    $query = mysql_query('SELECT * FROM `customer` WHERE `id`="'.$customer_id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $cust = mysql_fetch_array($query);
    
    $customer = array();
    $compcust = array();
    $query = mysql_query('SELECT * FROM `customer` WHERE `delete`!="1"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    while($fetch=mysql_fetch_array($query)) {
        if(!$fetch['company'] || $fetch['company']=='') {
            array_push($customer, $fetch);
        } else {
            array_push($compcust, $fetch);
        }
    }
    
    $item = array();
    $query = mysql_query('SELECT p.*,
                                i.*,
                                b.barcode as fullbarcode,
                                s.status as statusname
                                FROM `packageitem` p 
                                LEFT JOIN
                                `item` i ON (i.id = p.item_id)
                                LEFT JOIN
                                `barcode` b on (b.id = i.barcode)
                                LEFT JOIN
                                `status` s on (s.id = i.status)
                                WHERE p.package_id="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    while($fetch=mysql_fetch_array($query))
        array_push($item, $fetch);
    
    $status = array();
    $query = mysql_query('SELECT * FROM `status` WHERE `type`="package"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    while($fetch=mysql_fetch_array($query))
        array_push($status, $fetch);
    
    $title = 'Paket bearbeiten';
    
    write_header($title);
    
    linenav('Zur&uuml;ck', '../', 'Speichern', 'javascript:if($(\'#form\').valid()) { document.getElementById(\'form\').submit(); }', 'icon-chevron-left', 'icon-fire icon-white');
    
    ?>
        </div>
        <form id="form" name="form" method="POST" enctype="multipart/form-data" action="save.php">
        <div class="row-fluid">
        	
        	<div class="span12">
                <h1><?=$title?></h1>
                
                <hr>
                
                <div class="row-fluid">
                    <div class="span3">
                    
                        <h2>Ausleihpaket</h2>
                        <br />
                	    
                	    <label for="name">Name des Pakets</label><input id="packagename" name="data[packagename]" type="text" value="<?=$package['name']?>" class="required" minlength="2" />
                	    <label for="datepicker">R&uuml;ckgabedatum (voraussichtlich)</label><input id="datepicker" name="data[datepicker]" type="text" value="<?=date('d.m.Y', $package['duedate'])?>" />
                        <label for="name">Status</label>
                        <select name="data[status]">
                            <?php
                                foreach($status as $s) {
                                    $selected = '';
                                    if($s['id']==$package['status']) {
                                        $selected = ' selected';
                                    }
                                    echo '<option value="'.$s['id'].'"'.$selected.'>'.$s['status'].'</option>
                                    ';
                                }
                            ?>
                        </select>
                    </div>
                    
                        
                  <div class="span3">
                  
                    <h2>Kunde</h2>
                	    <br />
                	    <select id="customer" name="customer">
                	        <option id="manual" value="manual">Manuell eingeben</option>
                	        <option>--------------------</option>
                                <?
                                    foreach($compcust as $c) {
                                        $selected = '';
                                        if(isset($customer_id) && $customer_id==$c['id']) {
                                            $selected = ' selected';
                                        }
                                        echo '<option value="'.$c['id'].'"'.$selected.'>'.$c['company'].' - '.$c['prename'].' '.$c['name'].'</option>';
                                    }

                                    foreach($customer as $c) {
                                        $selected = '';
                                        if(isset($customer_id) && $customer_id==$c['id']) {
                                            $selected = ' selected';
                                        }
                                        echo '<option value="'.$c['id'].'"'.$selected.'>'.$c['prename'].' '.$c['name'].'</option>';
                                    }
                                ?>
                	    </select>
                	    
                	    <div id="manual_form">
                	    
                	        <label for="name">Vorname</label><input id="prename" name="client[prename]" type="text" value="<?=$cust['prename']?>" class="required" minlength="2" />
                	        <label for="name">Name</label><input id="name" name="client[name]" type="text" value="<?=$cust['name']?>" class="required" minlength="2" />
                	        <label for="name">Firma</label><input id="company" name="client[company]" type="text" value="<?=$cust['company']?>" />
                	        <label for="name">Adresse</label><input id="address" name="client[address]" type="text" value="<?=$cust['address']?>" class="required" minlength="2" />
                	        <label for="name">PLZ/Ort</label><input id="zip" name="client[zip]" type="text" value="<?=$cust['zip']?>" style="width: 4em;"/> <input id="location" name="client[location]" type="text" value="<?=$cust['location']?>" class="required" minlength="2" style="width: 11.1em;" />
                	        <label for="name">Telefon</label><input id="phone" name="client[phone]" type="text" value="<?=$cust['phone']?>" />
                	        <label for="name">Mailadresse</label><input id="mail" name="client[mail]" type="text" value="<?=$cust['mail']?>" />
                	        <label for="name">Web</label><input id="web" name="client[web]" type="text" value="<?=$cust['web']?>" />
                	        <input type="hidden" id="hidden" name="data[hidden]" value="false" />
                	        <input type="hidden" id="id" name="data[id]" value="<?=$package['id']?>" />
                	    
                	    </div>
                	    
                        <script type="text/javascript">
                            $(document).ready(function() {
                                if($('select.#customer').val()!='manual') {
                                    $('#manual_form').hide();
                                    $('#hidden').val('true');
                                }
                            });
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
                    
                    <div class="span6">
                    
                      <h2>Artikel</h2>
                      <br />
                      Artikel per Barcode hinzuf&uuml;gen:&nbsp;&nbsp;&nbsp; <input type="text" id="barcode" name="barcode" />
                      <table class="table table-striped" id="items">
                          <thead>
                              <tr>
                                  <th>#</th>
                                  <th>Barcode</th>
                                  <th class="big5">Artikel</th>
                                  <th>Status</th>
                                  <th class="tableicons"></th>
                              </tr>
                          </thead>
                          <tbody id="itembody">
                        <?php
                            
                        foreach($item as $i) {
                        
                            echo '
                                    <tr id="'.$i['id'].'">
                                        <td>'.$i['id'].'</td>
                                        <td><a href="'.dire.'barcode/detail/?id='.$i['barcode'].'">'.$i['fullbarcode'].'</a></td>
                                        <td><a href="'.dire.'item/detail/?id='.$i['id'].'">'.$i['name'].'</a></td>
                                        <td style="white-space: nowrap;"><nobr>' . $i['statusname'] . '</nobr></td>
                                        <td>' . 
                                        gen_right_btn('', 'javascript:void(0);', 'icon-remove icon-white', 'btn btn-mini btn-danger" id="'.$i['id'].'', 'Artikel l&ouml;schen', false) . '</td>
                                    </tr>
                                ';

                            }

                        ?>
                        </tbody>
                    </table>
                    
                    <div id="hiddeninputs" style="width: 0; height: 0;">
                    <?php
                        foreach($item as $i) {
                            echo '<input type="hidden" id="hidden' . $i['id'] . '" name="item[]" value="' . $i['id'] . '" />';
                        }
                    ?>
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
                                            $("#itembody").append('<tr id="' + item.id + '"><td>' + item.id + '</td><td><a href="' + item.dire + 'barcode/detail/?id=' + item.barcode + '">' + item.fullbarcode + '</a></td><td><a href="' + item.dire + 'item/detail/?id=' + item.id + '">' + item.name + '</a></td><td>' + item.statusname + '</td><td><a class="btn btn-mini btn-danger" id="' + item.id + '" href="javascript:void(0);" title="Artikel l&ouml;schen"><i class="icon-remove icon-white"></i></a></td></tr>');
                                            $("#hiddeninputs").append('<input type="hidden" id="hidden' + item.id + '" name="item[]" value="' + item.id + '" />');
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