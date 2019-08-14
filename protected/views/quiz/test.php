<!-- <div class="well wizard-example">
    <div class="wizard" id="MyWizard">
        <ul class="steps horizontal">
            <li class="active" data-target="#step1"><span class="badge badge-info">1</span>Step 1<span class="chevron"></span>

            </li>
            <li data-target="#step2"><span class="badge">2</span>Step 2<span class="chevron"></span>

            </li>
            <li data-target="#step3"><span class="badge">3</span>Step 3<span class="chevron"></span>

            </li>
            <li data-target="#step4"><span class="badge">4</span>Step 4<span class="chevron"></span>

            </li>
            <li data-target="#step5"><span class="badge">5</span>Step 5<span class="chevron"></span>

            </li>
        </ul>
        <div class="actions">
            <button class="btn btn-xs btn-prev"> <i class="glyphicon glyphicon-arrow-left"></i>Prev</button>
            <button class="btn btn-xs btn-next" data-last="Finish">Next<i class="glyphicon glyphicon-arrow-right"></i>

            </button>
        </div>
    </div>
    <div class="step-content">
        <div class="step-pane active" id="step1">
             <h2 class=""><i class="glyphicon glyphicon-magic"> </i> &nbsp; Welcome to the Bootstrap Wizard Example.</h2>This is the first step in this wizard example...
            <br class="">
            <img class="img-polaroid img-responsive" src="//placehold.it/200x150">
            <br class="">Click 'Next' to continue.</div>
        <div class="step-pane" id="step2">
             <h2 class="">Step 2</h2>Now you are at the 2nd step of this wizard example.
            <br class="">
        </div>
        <div class="step-pane" id="step3">
             <h2 class="">Okay</h2>Now you are at the 3rd step of this wizard example.
            <br class="">
        </div>
        <div class="step-pane" id="step4">
             <h2 class="">Almost Done.</h2>Now you are at the 4th step of this wizard example. Click 'Next' to finish
            up.</div>
        <div class="step-pane" id="step5">
             <h2 class="">Done!</h2>The wizard is complete. Pretty exciting stuff, eh?. <a id="btnStep2" href="#" class="">Go back to step 2.</a>

        </div>
    </div>
    <br class="">
    <input class="btn btn-default" id="btnWizardPrev" value="Back" type="button">
    <input class="btn btn-primary" id="btnWizardNext" value="Next" type="button">
</div>
<div id="push" class=""></div> -->

<script type="text/javascript">
  /*$('#MyWizard').on('change', function(e, data) {
    console.log('change');
    if(data.step===3 && data.direction==='next') {
      // return e.preventDefault();
    }
  });
  $('#MyWizard').on('changed', function(e, data) {
    console.log('changed');
  });
  $('#MyWizard').on('finished', function(e, data) {
    console.log('finished');
  });
  $('#btnWizardPrev').on('click', function() {
    $('#MyWizard').wizard('previous');
  });
  $('#btnWizardNext').on('click', function() {
    $('#MyWizard').wizard('next','foo');
  });
  $('#btnWizardStep').on('click', function() {
    var item = $('#MyWizard').wizard('selectedItem');
    console.log(item.step);
  });
  $('#MyWizard').on('stepclick', function(e, data) {
    console.log('step' + data.step + ' clicked');
    if(data.step===1) {
      // return e.preventDefault();
    }
  });

  // optionally navigate back to 2nd step
  $('#btnStep2').on('click', function(e, data) {
    $('[data-target=#step2]').trigger("click");
  });*/

</script>


<div class="container" id="myWizard">
  
   <h3></h3>
  
   <hr>
  
   <div class="progress">
     <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="1" aria-valuemin="1" aria-valuemax="5" style="width: 20%;">
       Soal 1 dari 5 Soal
     </div>
   </div>
  
   <div class="navbar">
      <div class="navbar-inner">
            <ul class="nav nav-pills">
               <li class="active"><a href="#step1" data-toggle="tab" data-step="1">Soal 1</a></li>
               <li><a href="#step2" data-toggle="tab" data-step="2">Soal 2</a></li>
               <li><a href="#step3" data-toggle="tab" data-step="3">Soal 3</a></li>
               <li><a href="#step4" data-toggle="tab" data-step="4">Soal 4</a></li>
               <li><a href="#step5" data-toggle="tab" data-step="5">Soal 5</a></li>
            </ul>
      </div>
   </div>
   <div class="tab-content">
      <div class="tab-pane fade in active" id="step1">
         
        <div class="well"> 
          
            <label>Soal 1</label>
            <select class="form-control input-lg">
              <option value="What was the name of your first pet?">What was the name of your first pet?</option>
              <option value="Where did you first attend school?">Where did you first attend school?</option>
              <option value="What is your mother's maiden name?">What is your mother's maiden name?</option>
              <option value="What is your favorite car model?">What is your favorite car model?</option>
            </select>
            <br>
            <label>Enter Response</label>
            <input class="form-control input-lg">
            
        </div>

         <a class="btn btn-default btn-lg next" href="#">Selanjutnya</a>
      </div>
      <div class="tab-pane fade" id="step2">
         <div class="well"> 
          
            <label>Soal 2</label>
            <select class="form-control  input-lg">
              <option value="What was the name of your first pet?">What was the name of your first pet?</option>
              <option selected="" value="Where did you first attend school?">Where did you first attend school?</option>
              <option value="What is your mother's maiden name?">What is your mother's maiden name?</option>
              <option value="What is your favorite car model?">What is your favorite car model?</option>
            </select>
            <br>
            <label>Enter Response</label>
            <input class="form-control  input-lg">
            
         </div>
         <a class="btn btn-default next" href="#">Selanjutnya</a>
      </div>
      <div class="tab-pane fade" id="step3">
        <div class="well"> <h2>Soal 3</h2> Add another step here..</div>
         <a class="btn btn-default next" href="#">Selanjutnya</a>
      </div>
      <div class="tab-pane fade" id="step4">
        <div class="well"> <h2>Soal 4</h2> Add another almost done step here..</div>
         <a class="btn btn-default next" href="#">Selanjutnya</a>
      </div>
      <div class="tab-pane fade" id="step5">
        <div class="well"> <h2>Soal 5</h2> You're Done!</div>
         <a class="btn btn-success first" href="#">Start over</a>
      </div>
   </div>
  
</div>
<script type="text/javascript">
  $('.next').click(function(){

    var nextId = $(this).parents('.tab-pane').next().attr("id");
    $('[href=#'+nextId+']').tab('show');
    return false;
    
  })

  $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    
    //update progress
    var step = $(e.target).data('step');
    var percent = (parseInt(step) / 5) * 100;
    
    $('.progress-bar').css({width: percent + '%'});
    $('.progress-bar').text("Soal " + step + " dari 5 Soal");
    
    //e.relatedTarget // previous tab
    
  })

  $('.first').click(function(){

    $('#myWizard a:first').tab('show')

  })
</script>