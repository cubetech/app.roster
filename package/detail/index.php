<?php

    define('dire', '../../');
    include(dire . '_env/exec.php');
    
    $id = iGET('id');
    $title = 'Detail Ausleihpaket ID '.$id;
    
    $query = mysql_query('SELECT * FROM `package` WHERE `id`="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
    $package = mysql_fetch_array($query);
    
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
        
    if($package['status']==8) {
        $title .= ' (zur&uuml;ckgebucht)';
    }
    
    write_header($title);
    
    addbutton('Quittung drucken', '../print/?id=' . $id, 'btn-inverse', 'icon-print icon-white');
    addbutton('Bearbeiten', '../edit/?id=' . $id, 'btn-warning', 'icon-pencil icon-white');
    if($package['delete']==0) {
        addbutton('L&ouml;schen', '../delete/?id=' . $id);
    } else {
        addbutton('Wiederherstellen', '../delete/?id=' . $id, '', 'icon-ok');
    }
    if($package['status']!=8) {
        addbutton('Paket zur&uuml;ckbuchen', 'javascript:if(confirm(\'Sind Sie sicher?\')) { window.location = \'' . dire . 'package/return/?id=' . $id . '\'; }', 'btn-primary', 'icon-refresh icon-white');
    }
    linenav('Zur&uuml;ck', '../');
    
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
                        
                        <dl>
                            <dt>Name des Pakets</dt><dd><?=$package['name']?></dd>
                            <dt>Geplante Ausleihdauer</dt><dd><?=date('d.m.Y', $package['startdate'])?> - <?=date('d.m.Y', $package['duedate'])?></dd>
                            <?php
                                if(isset($package['returndate']) && $package['returndate']>0 && $package['status']==8) {
                                    echo '<dt>Zur&uuml;ckgebucht am</dt><dd>'.date('d.m.Y H:i', $package['returndate']).'</dd>';
                                }
                            ?>
                        </dl>
                        
                    </div>
                    
                        
                  <div class="span3">
                  
                    <h2>Kunde</h2>
                	    <br />

                        <dl>
                            <dt>Ausleiher</dt><dd><?=$package['customer']?></dd>
                            <dt>Ansprechperson</dt><dd><?=$package['person']?></dd>
                        </dl>

                  </div><!--/span-->
                    
                    <div class="span6">
                    
                      <h2>Ausgeleihte Artikel</h2>
                      <br />
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
                                        gen_right_btn('', 'javascript:if(confirm(\'Sind Sie sicher?\')) { window.location = \'' . dire . 'return/return.php?id=' . $i['barcode'] . '&pid=' . $id . '\'; }', 'icon-refresh icon-white', 'btn btn-mini btn-warning" id="'.$i['id'].'', 'Artikel zur&uuml;ckbuchen', false) . '</td>
                                    </tr>
                                ';

                            }

                        ?>
                        </tbody>
                    </table>
                    
                    
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