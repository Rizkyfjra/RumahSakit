<?php $zenius = @Option::model()->findByAttributes(array('key_config' => 'zenius'))->value; ?>
<div id="sidebar-wrapper">
    <ul class="sidebar-nav" style="max-height: 500px; overflow:scroll;
    -webkit-overflow-scrolling: touch;">
        <li style="text-indent:0px;line-height:20px;list-style:none">
            <a style="padding-top:2px;padding-bottom:2px;cursor:pointer"
               href="<?php echo $this->createUrl('/user/view/' . Yii::app()->user->id); ?>">
                <div class="sidebar-profile" style="cursor:inherit">
                    <div class="sidebar-profile-picture" style="cursor:inherit">
                        <img src="<?php echo $img_url; ?>" alt="Profile Picture" class="img-circle"
                             style="width:25px;height:25px">
                    </div>
                    <div class="sidebar-profile-text" style="cursor:inherit">
                        <h4 style="color:#fff;cursor:inherit"><?php echo ucwords(Yii::app()->user->displayName); ?></h4>
                        <p style="color:#fff;cursor:inherit"><?php echo ucwords($textdash); ?></p>
                    </div>
                </div>
            </a>
        </li>

        <li class="<?php echo '', ((strpos(strtolower(Yii::app()->request->requestUri), 'site_') === false || strpos(strtolower(Yii::app()->request->requestUri), 'site_page') !== false) && strpos(strtolower(Yii::app()->controller->id . '/' . Yii::app()->controller->action->id), 'site') !== false ? 'active' : '') ?>">
            <a href="<?php echo $this->createUrl('/assignment'); ?>"><i class="icon-sidebar icon-pn-home"></i>
                Beranda</a>
        </li>
        <?php if (Yii::app()->user->YiiAdmin) { ?>
            <li class="<?php echo '', ((strpos(strtolower(Yii::app()->request->requestUri), 'user_') === false || strpos(strtolower(Yii::app()->request->requestUri), 'user_page') !== false) && strpos(strtolower(Yii::app()->controller->id . '/' . Yii::app()->controller->action->id), 'user') !== false ? 'active' : '') ?>">
                <a href="<?php echo $this->createUrl('/user'); ?>"><i class="icon-sidebar icon-pn-user"></i>
                    Pengguna</a>
            </li>
        <?php } ?>

        
        <li class="<?php echo '', ((strpos(strtolower(Yii::app()->request->requestUri), 'assignment_') === false || strpos(strtolower(Yii::app()->request->requestUri), 'assignment_page') !== false) && strpos(strtolower(Yii::app()->controller->id), 'studentassignment') === false && strpos(strtolower(Yii::app()->controller->id . '/' . Yii::app()->controller->action->id), 'assignment') !== false ? 'active' : '') ?>">
            <a href="<?php echo $this->createUrl('/assignment'); ?>"><i class="icon-sidebar icon-pn-task"></i> Tindakan</a>
           
        <li class="<?php echo '', ((strpos(strtolower(Yii::app()->request->requestUri), 'lesson_') === false || strpos(strtolower(Yii::app()->request->requestUri), 'lesson_page') !== false) && strpos(strtolower(Yii::app()->controller->id . '/' . Yii::app()->controller->action->id), 'lesson') !== false ? 'active' : '') ?>">
            <a href="<?php echo $this->createUrl('/lesson'); ?>"><i class="icon-sidebar icon-pn-exam"></i>
                Obat</a>
            <?php if (!Yii::app()->user->YiiStudent) { ?>
                <button onclick="location.href = '<?php echo $this->createUrl('/lesson/create') ?>';"
                        class="btn btn-sidebar-action btn-xs btn-success">
                    <i class="fa fa-plus"></i>
                </button>
            <?php } ?>
        </li>
       </li>
      
        <?php if (Yii::app()->user->YiiAdmin) { ?>
            <li class="<?php echo '', ((strpos(strtolower(Yii::app()->request->requestUri), 'exam') === false || strpos(strtolower(Yii::app()->request->requestUri), 'exam') !== false) && strpos(strtolower(Yii::app()->controller->id . '/' . Yii::app()->controller->action->id), 'exam') !== false ? 'active' : '') ?>">
                <a href="<?php echo $this->createUrl('/exam'); ?>"><i class="icon-sidebar icon-pn-exam"></i>
                    Administrasi Inap</a>
                <?php if (!Yii::app()->user->YiiStudent) { ?>
                    <button onclick="location.href = '<?php echo $this->createUrl('/exam/create') ?>';"
                            class="btn btn-sidebar-action btn-xs btn-success">
                        <i class="fa fa-plus"></i>
                    </button>
                <?php } ?>
            </li>


            <li class="<?php echo '', ((strpos(strtolower(Yii::app()->request->requestUri), 'exam') === false || strpos(strtolower(Yii::app()->request->requestUri), 'exam') !== false) && strpos(strtolower(Yii::app()->controller->id . '/' . Yii::app()->controller->action->id), '/announcements/create') !== false ? 'active' : '') ?>">
                <a href="<?php echo $this->createUrl('/announcements'); ?>"><i class="icon-sidebar icon-pn-exam"></i>
                    Pengumuman</a>
                <?php if (!Yii::app()->user->YiiStudent) { ?>
                    <button onclick="location.href = '<?php echo $this->createUrl('/announcements/create') ?>';"
                            class="btn btn-sidebar-action btn-xs btn-success">
                        <i class="fa fa-plus"></i>
                    </button>
                <?php } ?>
            </li>

        <?php } ?>
        <?php
        if (Yii::app()->user->YiiTeacher) {
            $kelasnyawali = ClassDetail::model()->findAll(array('condition' => 'teacher_id = ' . Yii::app()->user->id));
            ?>
            <li class="<?php //echo '', (strpos(strtolower(Yii::app()->request->requestUri), 'score')===false && strpos(strtolower(Yii::app()->controller->id.'/'.Yii::app()->controller->action->id),'score')!==false ? 'active' : '')  ?>">
                <a href="<?php echo $this->createUrl('/option/aturLocal') ?>"><i class="fa fa-wrench"></i> ATUR SEMESTER</a>
            </li>
            <?php if (!empty($kelasnyawali[0])) {
                ?>
                <li class="<?php //echo '', (strpos(strtolower(Yii::app()->request->requestUri), 'score')===false && strpos(strtolower(Yii::app()->controller->id.'/'.Yii::app()->controller->action->id),'score')!==false ? 'active' : '')  ?>">
                    <a href="<?php echo $this->createUrl('/clases/' . $kelasnyawali[0]->id); ?>"><i
                            class="icon-sidebar icon-pn-grade"></i> <?php echo $kelasnyawali[0]->name; ?></a>
                </li>
                <?php
            }
        }
        ?>
        <?php
        $online = @Option::model()->findByAttributes(array('key_config' => 'online'))->value;
        if ($online) {
            ?>
            <li class="">
                <a href="<?php echo $this->createUrl('/site/hasilkd') ?>"><i class="icon-sidebar icon-pn-exam"></i> 
                    Pemetaan nilai</a>
            </li>
<?php } ?>
    </ul>
</div>
