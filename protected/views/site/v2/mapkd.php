<?php
if ($school != "all") {
    $classlist = $class_list[$school];
} else {
    $classlist = array();
}
?>
<style type="text/css">
    .tab-pane h1 {
        font-family: "ProximaNova-Bold";
        font-size: 72px;
    }
    .gm-map {
        display: block;
        width: 100%;
        height: 560px;
    }
    .map {
        display: block;
        width: 100%;
        height: 560px;
    }
    #page-content-wrapper {
        padding: 0px !important;
    }
</style>
<div class="container-fluid" style="background: #ecf0f5">
    <div class="row">
        <div class="col-md-12">
            <!-- <div id="bc1" class="btn-group btn-breadcrumb">
            <?php //echo CHtml::link('<i class="fa fa-home"></i> Beranda', array('/site/index'), array('class' => 'btn btn-default'));  ?>
            <?php //echo CHtml::link('<div>Hasil Ujian KD</div>', array('#'), array('class' => 'btn btn-success')); ?>
            </div> -->
        </div>
        <div class="col-md-12">
            <div class="login-box">
                <div class="col-card">
                    <form>
                        <?php echo CHtml::dropDownList('type', $type, array('uts'=>'UTS', 'uas'=>'UAS', 'us'=>'US'), array(
                            'class' => 'selectpicker',
                            'id' => 'typelist',
                            'data-style' => 'btn-default input-lg',
                            'data-live-search' => 'true'));
                        ?>
                        <?php
                        echo CHtml::dropDownList('quiz', $quiz, $quiz_list, array(
                            'class' => 'selectpicker',
                            'id' => 'quizlist',
                            'data-style' => 'btn-default input-lg',
                            'data-live-search' => 'true'));
                        ?>
                        <?php
                        echo CHtml::dropDownList('school', $school, $school_list, array(
                            'class' => 'selectpicker',
                            'id' => 'schoolist',
                            'data-style' => 'btn-default input-lg',
                            'data-live-search' => 'true'));
                        ?>
                        <?php
                        echo CHtml::dropDownList('class', $class, $classlist, array(
                            'class' => 'selectpicker',
                            'id' => 'classlist',
                            'data-style' => 'btn-default input-lg',
                            'data-live-search' => 'true'));
                        ?>
                        <input type="submit" class="btn btn-success input-lg pull-right" style="width: 205px" value="Cari...">
                    </form>
                </div>
                <div class="col-card">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation">
<?php echo CHtml::link('<i class="fa fa-list"></i> Nilai KD', array('site/hasilkd?quiz=' . $quiz . '&type=' . $type . '&school=' . $school . '&class=' . $class), array('class' => '')); ?>
                            <!-- <a href="#tabChart" aria-controls="nilai" role="tab" data-toggle="tab">
                            <i class="fa fa-line-chart"></i> Chart KD -->
                        </li>
                        <li role="presentation">
<?php echo CHtml::link('<i class="fa fa-list"></i> Chart KD', array('site/chartkd?quiz=' . $quiz . '&type=' . $type . '&school=' . $school . '&class=' . $class), array('class' => '')); ?>
                        </li>
                        <li role="presentation" class="active">
                            <a href="#tabNilai" aria-controls="tabSoal" role="tab" data-toggle="tab">
                                <i class="fa fa-bar-chart"></i> Map KD
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="tabNilai">
                            <div class="row">
                                <div class="map"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDv-yvQ2ICjoK2x6H0aGUCAttA-jdTDO_w"></script>   
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/libraries/gmap3.js"></script>     
<script type="text/javascript">
    if ($(window).width() > 960) {
        $("#wrapper").toggleClass("toggled");
    }
    $(document).ready(function () {
        var classes = <?php echo json_encode($class_list); ?>;
        var quizes = <?php echo json_encode($quiz_list); ?>;
        var school = <?php echo json_encode($school); ?>;
        var location = <?php echo json_encode($location); ?>;

        $.fn.fill_class = function (id) {
            var classlist = classes[id];
            var option = '';
            $.each(classlist, function (k, v) {
                option += '<option value="' + k + '">' + v + '</option>';
            });
            $('#classlist').html(option);
        };

        $.fn.fill_quiz = function (id) {
            var quizlist = quizes[id];
            var option = '';
            $.each(quizlist, function (k, v) {
                option += '<option value="' + k + '">' + v + '</option>';
            });
            $('#quizlist').html(option);
        };

        $(this).on('change', '#schoolist', function () {
            $('#classlist').empty();
//            $('#quizlist').empty();

            var id = $(this).val();
            $(this).fill_class(id);
//            $(this).fill_quiz(id);
            $('.selectpicker').selectpicker('refresh');
        });
        var markers = [];
        var circles = [];
        $.each(location, function (key, data) {
            if (data.score > 80) {
                color = "#28a745";
            } else if (data.score > 64) {
                color = "#007bff";
            } else if (data.score > 44) {
                color = "#ffc107";
            } else if (data.score < 45) {
                color = "#dc3545";
            }
            markers.push({
                position: [data.lat, data.lng],
                opacity: 1.0,
                icon: "http://icons.iconarchive.com/icons/icons-land/vista-map-markers/24/Map-Marker-Ball-Pink-icon.png",
                label: '',
                title: key.toString()
            });
            circles.push({
                center: [data.lat, data.lng],
                radius: 125,
                fillColor: color,
                fillOpacity: 0.7,
                strokeOpacity: 0.0
            });
        });

        $('.map')
                .gmap3({
                    center: [-6.919463, 107.615346],
                    zoom: 13,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                })
                .marker(markers)
                .on('click', function (marker) {
                    alert(marker.title);
//            var infowindow = new google.maps.InfoWindow({
//                content: "contentString"
//            });
//            infowindow.open(map, marker);
//            marker.setIcon('http://maps.google.com/mapfiles/marker_green.png');
                })
                .circle(circles);
    });
</script>
