<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
 <html xmlns="http://www.w3.org/1999/xhtml"> 
 
	<head> 
		
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /> 
		
		<title> <?php echo $title_for_layout; ?></title> 
		
		<!--                       CSS                       --> 
	  
		<!-- Main Stylesheet --> 
		<link rel="stylesheet" href="<?php echo $this->webroot?>css/style.css" type="text/css" media="screen" /> 
		
		<!-- Invalid Stylesheet. This makes stuff look pretty. Remove it if you want the CSS completely valid --> 
		<!---<link rel="stylesheet" href="resources/css/invalid.css" type="text/css" media="screen" />	--->
		<link rel="stylesheet" href="<?php echo $this->webroot?>css/blue.css" type="text/css" media="screen" /> 
		<!-- Colour Schemes
	  
		Default colour scheme is green. Uncomment prefered stylesheet to use it.	
		<link rel="stylesheet" href="resources/css/red.css" type="text/css" media="screen" />  
	 
		--> 
		
		<!-- Internet Explorer Fixes Stylesheet --> 
		
		<!--[if lte IE 7]>
			<link rel="stylesheet" href="resources/css/ie.css" type="text/css" media="screen" />
		<![endif]--> 
		
		<!--                       Javascripts                       --> 
  
		<!-- jQuery --> 
		<script type="text/javascript" src="<?php echo $this->webroot?>js/jquery/jquery-1.3.2.min.js"></script> 
		
		<!-- jQuery Configuration --> 
		<script type="text/javascript" src="<?php echo $this->webroot?>js/jquery/simpla.jquery.configuration.js"></script> 
		
		<!-- Facebox jQuery Plugin --> 
		<script type="text/javascript" src="<?php echo $this->webroot?>js/jquery/facebox.js"></script> 
		
		<!-- jQuery WYSIWYG Plugin --> 
		<script type="text/javascript" src="<?php echo $this->webroot?>js/jquery/jquery.wysiwyg.js"></script> 
		
		<!-- Internet Explorer .png-fix --> 
		
		<!--[if IE 6]>
			<script type="text/javascript" src="resources/scripts/DD_belatedPNG_0.0.7a.js"></script>
			<script type="text/javascript">
				DD_belatedPNG.fix('.png_bg, img, li');
			</script>
		<![endif]--> 
		
	</head> 
  
	<body><div id="body-wrapper"> <!-- Wrapper for the radial gradient background --> 
		
		<div id="sidebar"><div id="sidebar-wrapper"> <!-- Sidebar with logo and menu --> 
			
			<h1 id="sidebar-title"><a href="#">SocialDart Admin</a></h1> 
		  
			<!-- Logo (221px wide) --> 
			<a href="<?php echo $this->webroot?>"><img id="logo" src="<?php echo $this->webroot?>img/logo.png" alt="Simpla Admin logo" /></a> 
		  
			<!-- Sidebar Profile links --> 
			<div id="profile-links"> 
				Hello, <a href="#" title="Edit your profile">John Doe</a>, you have <a href="#messages" rel="modal" title="3 Messages">3 Messages</a><br /> 
				<br /> 
				<a href="#" title="View the Site">Your Account</a> | <a href="#" title="Sign Out">Sign Out</a> 
			</div>        
			
			<ul id="main-nav">  <!-- Accordion Menu --> 
				
				<li> 
					<a href="http://www.google.com" class="nav-top-item no-submenu"> <!-- Add the class "no-submenu" to menu items with no sub menu --> 
						Dashboard
					</a>       
				</li> 
				
				<li> 
					<a href="#" class="nav-top-item current"> <!-- Add the class "current" to current menu item --> 
					Bots
					</a> 
					<ul> 
						<li><a href="#">Auto Follow</a></li> 
						<li><a class="current" href="#">Synchronize</a></li> <!-- Add class "current" to sub menu items also --> 
						<li><a href="#">Clone</a></li> 
						<li><a href="#">Keyword Follow</a></li> 
					</ul> 
				</li> 
				
				<li> 
					<a href="#" class="nav-top-item"> 
						Reports
					</a> 
					<ul> 
						<li><a href="#">Overall</a></li> 
						<li><a href="#">By Campaign</a></li> 
					</ul> 
				</li> 
				
			
				
				<li> 
					<a href="#" class="nav-top-item"> 
						Settings
					</a> 
					<ul> 
						<li><a href="#">General</a></li> 
						<li><a href="#">Frequency</a></li> 
						<li><a href="#">Your Account</a></li> 
						<li><a href="#">Billing</a></li> 
					</ul> 
				</li>      
				
			</ul> <!-- End #main-nav --> 
			
			<div id="messages" style="display: none"> <!-- Messages are shown when a link with these attributes are clicked: href="#messages" rel="modal"  --> 
				
				<h3>3 Messages</h3> 
			 
				<p> 
					<strong>17th May 2009</strong> by Admin<br /> 
					Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus magna. Cras in mi at felis aliquet congue.
					<small><a href="#" class="remove-link" title="Remove message">Remove</a></small> 
				</p> 
			 
				<p> 
					<strong>2nd May 2009</strong> by Jane Doe<br /> 
					Ut a est eget ligula molestie gravida. Curabitur massa. Donec eleifend, libero at sagittis mollis, tellus est malesuada tellus, at luctus turpis elit sit amet quam. Vivamus pretium ornare est.
					<small><a href="#" class="remove-link" title="Remove message">Remove</a></small> 
				</p> 
			 
				<p> 
					<strong>25th April 2009</strong> by Admin<br /> 
					Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus magna. Cras in mi at felis aliquet congue.
					<small><a href="#" class="remove-link" title="Remove message">Remove</a></small> 
				</p> 
				
				<form action="" method="post"> 
					
					<h4>New Message</h4> 
					
					<fieldset> 
						<textarea class="textarea" name="textfield" cols="79" rows="5"></textarea> 
					</fieldset> 
					
					<fieldset> 
					
						<select name="dropdown" class="small-input"> 
							<option value="option1">Send to...</option> 
							<option value="option2">Everyone</option> 
							<option value="option3">Admin</option> 
							<option value="option4">Jane Doe</option> 
						</select> 
						
						<input class="button" type="submit" value="Send" /> 
						
					</fieldset> 
					
				</form> 
				
			</div> <!-- End #messages --> 
			
		</div></div> <!-- End #sidebar --> 
		
		<div id="main-content"> <!-- Main Content Section with everything --> 
			
	<!-- Page Head --> 
			<h2>Welcome @John</h2> 
			<p id="page-intro">4,450 Followers | 4,234 Following | +34 since yesterday</p> 
 			
 			<div><?php echo $content_for_layout;?></div>
			
			<div id="footer"> 
				<small> 
						&#169; Copyright 2009 Simpla Admin | Powered by <a href="http://themeforest.net/item/simpla-admin-flexible-user-friendly-admin-skin/46073">Simpla Admin</a> | <a href="#">Top</a> 
				</small> 
			</div><!-- End #footer --> 
			
		</div> <!-- End #main-content --> 
		
	</div></body> 
  
</html>