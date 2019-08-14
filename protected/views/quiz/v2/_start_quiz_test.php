 <?php
         $form=$this->beginWidget('CActiveForm', array(
            'id'=>'questions',
            'action'=>Yii::app()->createUrl('quiz/submitQuiz'),
            // Please note: When you enable ajax validation, make sure the corresponding
            // controller action is handling ajax validation correctly.
            // There is a call to performAjaxValidation() commented in generated controller code.
            // See class documentation of CActiveForm for details on this.
            'enableAjaxValidation'=>false,
             'htmlOptions'=>array(
                               'onsubmit'=>"return false;"/* Disable normal form submit */

                             ),
          )); ?>
          
          <input type="text" name="qid" >
          <input id="dude" name="dude"   type="text">
         
          <?php $this->endWidget(); ?>

          <script type="text/javascript">


          // console.log(localStorage);


  $(document).ready(function() {
 console.log(localStorage);
// localStorage.clear();
                $( function() {
      $("#questions").sisyphus({
        autoRelease: false,
        timeout: 1,
        onSave: function() {
          // alert("asdfasdf");
          console.log("berhasil");
        }
      });
      //$("#wkt").sisyphus();
      // or you can persist all forms data at one command
      // $( "form" ).sisyphus();
    } );

               } ); 

          </script>