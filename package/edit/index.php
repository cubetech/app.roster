<?php

    define('dire', '../../');
    include(dire . '_env/exec.php');
    
    $id = vGET('id');
    
    $query = mysql_query('SELECT * FROM `package` WHERE `id`="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $package = mysql_fetch_array($query);
    
    if(!is_array($package)) {
        error('transfer');
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
                                WHERE p.package_id="'.$id.'" AND p.back=0') or sqlError(__FILE__,__LINE__,__FUNCTION__);
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
                <h1><?php print $title?></h1>
                
                <hr>
                
                <div class="row-fluid">
                    <div class="span3">
                    
                        <h2>Ausleihpaket</h2>
                        <br />
                	    
                	    <label for="name">Name des Pakets</label><input id="packagename" name="data[packagename]" type="text" value="<?php print $package['name']?>" class="required" minlength="2" />
                	    <label for="datepicker">R&uuml;ckgabedatum (voraussichtlich)</label><input id="datepicker" name="data[datepicker]" type="text" value="<?php $package['duedate'] != 0 ? print(date('d.m.Y', $package['duedate'])) : print("unbefristet"); ?>" />
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

                	    <label for="customer">Ausleiher</label><input id="customer" name="data[customer]" type="text" value="<?php print $package['customer']?>" />
                	    <label for="person">Ansprechperson</label><input id="person" name="data[person]" type="text" value="<?php print $package['person']?>" />
                	    <input type="hidden" name="data[id]" value="<?php print $package['id']?>" />

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