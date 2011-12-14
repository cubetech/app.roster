<?

    function showBox($content, $color='grey') {
        $colclass = 'box';

        if($color=='nogradient')
            $colclass = 'box nogradient';
        elseif($color=='purple')
            $colclass = 'box purple';

        $return = '
                <div class="'.$colclass.'" style="margin-bottom: 2em;">
                    <div class="box-top">
                        <span class="box-top-left"></span>
                        <span class="box-top-right"></span>
                    </div>
                    <div class="box-main">
                        <div class="box-main-content-wrapper">
                            <div class="box-main-content">
                                '.$content.'
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="box-bottom">
                        <span class="box-bottom-left"></span>
                        <span class="box-bottom-right"></span>
                    </div>
                </div>
        ';
        return $return;
    }
    
    function showDoubleBox($content, $content2, $color1='grey', $color2='grey', $switch='left') {
        $width1 = '55em';
        $width2 = '27em';
        $colclass1 = 'box';
        $colclass2 = 'box';
        
        if($switch=='left') {
            $width1 = '27em';
            $width2 = '55em';
        } elseif($switch=='center') {
            $width1 = '41em';
            $width2 = '41em';
        }

        if($color1=='nogradient')
            $colclass1 = 'box nogradient';
        elseif($color1=='purple')
            $colclass1 = 'box purple';

        if($color2=='nogradient')
            $colclass2 = 'box nogradient';
        elseif($color2=='purple')
            $colclass2 = 'box purple';

        $return = '<table style="width: 85em;"><tr>';

        if($content!='') {
            $return .= '<td style="width: '.$width1.'; vertical-align: top;">
                    <div class="'.$colclass1.'" style="float: left; width: '.$width1.'; margin-bottom: 1em; margin-right: 1em;">
                        <div class="box-top">
                            <span class="box-top-left"></span>
                            <span class="box-top-right"></span>
                        </div>
                        <div class="box-main">
                            <div class="box-main-content-wrapper">
                                <div class="box-main-content">
                                    '.$content.'
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="box-bottom">
                            <span class="box-bottom-left"></span>
                            <span class="box-bottom-right"></span>
                        </div>
                    </div>
                        </td>
                ';
            }
            if($content2!='') {
                $return .= '<td style="width: '.$width2.'; vertical-align: top;">
                <div class="'.$colclass2.'" style="float: left; width: '.$width2.'; margin-bottom: 1em;">
                    <div class="box-top">
                        <span class="box-top-left"></span>
                        <span class="box-top-right"></span>
                    </div>
                    <div class="box-main">
                        <div class="box-main-content-wrapper">
                            <div class="box-main-content">
                                '.$content2.'
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                    <div class="box-bottom">
                        <span class="box-bottom-left"></span>
                        <span class="box-bottom-right"></span>
                    </div>
                </div>
                    </td>
                </tr>
            </table>
                ';
            } else {
            $return .= '</tr></table>';
            }
        return $return;
    }
