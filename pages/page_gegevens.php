<?php
	require dirname(__FILE__).'/../inc/access.php';
?>
<div class="row-fluid">
            <br>
        <div class="span8">
        	<iframe width="800" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.co.uk/maps?f=q&source=s_q&hl=en&geocode=&q=15+Springfield+Way,+Hythe,+CT21+5SH&aq=t&sll=52.8382,-2.327815&sspn=8.047465,13.666992&ie=UTF8&hq=&hnear=15+Springfield+Way,+Hythe+CT21+5SH,+United+Kingdom&t=m&z=14&ll=51.077429,1.121722&output=embed"></iframe>
    	</div>
    	
      	<div class="span4">
    		<h2>Name</h2>
    		<address>
                    <strong>Gegevens:</strong><br>
                    <p> Address user: 20 Strong Bridge<br>
                        Address Company: 15 Springfield Way<br>
                        State: Kent<br>
                        Country: United Kingdon<br>
                        Postcode: CT21 5SH<br>
                        Phone: 01234 567 890</p> 
    		</address>
    	</div>
    </div>

   <div class="row">
       

      <form class="form-horizontal" role="form">
        <fieldset>
            <br>
          <!-- Form Name -->
          <h3>Veranderen</h3>

          <!-- Text input-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">Address</label>
            <div class="col-sm-10">
              <input type="text" placeholder="User" class="form-control">
            </div>
          </div>

          <!-- Text input-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">Address</label>
            <div class="col-sm-10">
              <input type="text" placeholder="Company" class="form-control">
            </div>
          </div>

          <!-- Text input-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">City</label>
            <div class="col-sm-10">
              <input type="text" placeholder="Company" class="form-control">
            </div>
          </div>

          <!-- Text input-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">State</label>
            <div class="col-sm-4">
              <input type="text" placeholder="Company" class="form-control">
            </div>

            <label class="col-sm-2 control-label" for="textinput">Postcode</label>
            <div class="col-sm-4">
              <input type="text" placeholder="Company" class="form-control">
            </div>
          </div>



          <!-- Text input-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">Country</label>
            <div class="col-sm-10">
              <input type="text" placeholder="Company" class="form-control">
            </div>
          </div>
          
           <!-- Text input-->
           <div class="form-group">
            <label class="col-sm-2 control-label" for="textinput">Phone</label>
            <div class="col-sm-10">
              <input type="text" placeholder="Company" class="form-control">
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <div class="pull-right">
                <button type="submit" class="btn btn-default">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
              </div>
            </div>
          </div>

        </fieldset>
      </form>
    </div><!-- /.col-lg-12 -->