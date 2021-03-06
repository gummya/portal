<div class="row wrapper border-bottom white-bg page-heading" style="margin-top:0;">	
	  <h2 class="m-l-md"><?php if($std['last_name']==''){echo $std['first_name'].' '.$std['middle_name'];}else{ echo $std['last_name'].', '.$std['first_name'].' '.$std['middle_name'];} ?></h2>
  <div class="col-lg-6">
    <ol class="breadcrumb">
      <li class="active">
        <strong>SY: Semester</strong>
      </li>
      <li>
      	<select id="sy" class="custom" name="sy">
          <?php foreach ($sy as $key => $value) { ?>
          <option value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
          <?php } ?>
       	</select>
      </li>
    </ol>
	</div>
  <div class="col-lg-6">
    <?php echo form_open(base_url().'semester/search'); ?>
    <div class="row pull-right">
      <div class="input-group col-sm-12">
        <input type="text" placeholder="e.g., Student ID or Name" name="search" class="form-control col-sm-4" required="">
        <div class="input-group-btn">
          <button class="btn btn-primary" type="submit">Search</button>
        </div>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<hr style="border-bottom:2px solid #737373; margin: 0;">
<div class="row m-r-none m-l-none" style="margin-bottom: 3px !important;">
  <div class="ibox m-b-none">
    <div class="ibox-content">
      <table class="table table-hover">
        <thead>
        <tr>
          <th class="col-md-1 text-center">Subject Code</th>
          <th class="col-md-4 text-center">Subject Title</th>
          <th class="col-md-1 text-center">Units</th>
          <th class="col-md-2 text-center">Final Grade</th>
          <th class="col-md-1 text-center"><div class="label label-primary">New</div>Point Equivalent</th>
          <th class="col-md-1 text-center"><div class="label">Old</div>Point Equivalent</th>
          <th class="col-md-1 text-center">Letter Equivalent</th>
          <th class="col-md-1 text-center">Remarks</th>
        </tr>
        </thead>
        <tbody id="theTable">
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="middle-box text-center animated fadeInDown" id="theError"></div>

<script>
  $(document).ready(function(){
    populateTable();
    $('#sy').on('change', function(){
      populateTable();
    });
  });

  function populateTable(){
    var id = $('#sy').val();
    $.ajax({
      type: 'POST',
      url: base_url+'semester/populateTable',
      data: ({
        type: 'populateTable',
        id: id,
        idd: '<?= $std['id'] ?>'
      }),
      dataType: 'json',
      success: function(data){
        if(data.status=='success'){
          console.log(data);
          $('#theTable').empty();
          $('#theError').empty();
          if(data.courses.length>0){
            var num1=0.00, tUnits=0;
            for(var i=0,j=data.courses.length;i<j;i++){
              num1 += parseFloat(data.courses[i].grade)*parseInt(data.courses[i].units);
              tUnits += parseInt(data.courses[i].units);
              var content = '<tr><td class="text-center">'+data.courses[i].code+'</td><td>'+data.courses[i].title+'</td><td class="text-center">'+data.courses[i].units+'</td><td class="text-center">'+data.courses[i].grade+'</td><td class="text-center">'+data.courses[i].npe+'</td><td class="text-center">'+data.courses[i].ope+'</td><td class="text-center">'+data.courses[i].le+'</td><td class="text-center">'+data.courses[i].remarks+'</td></tr>';
              $('#theTable').append(content);
            }
            num1 = num1/tUnits;
            var a,b,c,d;
            if(num1>=98.00&&num1<=100.00)    {a = '4.00'; b = '1.00'; c = 'A+'; d = 'PASSED';}
            else if(num1>=95.00&&num1<=97.99){a = '3.75'; b = '1.25'; c = 'A'; d = 'PASSED';}
            else if(num1>=92.00&&num1<=94.99){a = '3.50'; b = '1.50'; c = 'A-'; d = 'PASSED';}
            else if(num1>=89.00&&num1<=91.99){a = '3.25'; b = '1.75'; c = 'B+'; d = 'PASSED';} 
            else if(num1>=86.00&&num1<=88.99){a = '3.00'; b = '2.00'; c = 'B'; d = 'PASSED';} 
            else if(num1>=83.00&&num1<=85.99){a = '2.75'; b = '2.25'; c = 'B-'; d = 'PASSED';} 
            else if(num1>=79.00&&num1<=82.99){a = '2.50'; b = '2.50'; c = 'C+'; d = 'PASSED';} 
            else if(num1>=76.00&&num1<=78.99){a = '2.25'; b = '2.75'; c = 'C'; d = 'PASSED';} 
            else if(num1>=75.00&&num1<=75.99){a = '2.00'; b = '3.00'; c = 'C-'; d = 'PASSED';} 
            else if(num1>= 0.00&&num1<=74.99){a = '1.00'; b = '5.00'; c = 'D'; d = 'FAILED';}
            num1 = num1.toFixed(2);
            var content = '<tr><td></td><td class="text-right"><b>Total Units:</b></td><td class="text-center"><b>24</b></td><td></td><td></td><td></td><td></td><td></td></tr>';
            content += '<tr><td></td><td class="text-right"><b>GENERAL WEIGHTED AVERAGE(GWA):</b></td><td></td><td class="text-center"><b>'+num1+'</b></td><td class="text-center"><b>'+a+'</b></td><td class="text-center"><b>'+b+'</b></td><td class="text-center"><b>'+c+'</b></td><td></td></tr><tr><td></td><td class="text-right"><b>ACADEMIC STANDING:</b></td><td class="text-center"><b>';

            if(num1>=75.00){
              content += 'GS';
            } else {
              content += 'WS';
            }

            content += '</b></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
            $('#theTable').append(content);
          } else {
            var content = '<h3 class="font-bold">No Data Found.</h3>';
            $('#theError').append(content);
          }
        }
      }
    });
  }
</script>

<style>
.input-group .form-control {
  float: right !important;
  width: 50% !important;
}
.custom {
  background-color: #FFFFFF;
  background-image: none;
  border: 1px solid #e5e6e7;
  border-radius: 1px;
  color: inherit;
  padding: 6px 12px;
  transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
  font-size: 14px;
}
</style>