<!DOCTYPE html>
<html>
    <head>
        <title>Administration form</title>

        <link rel='stylesheet' href='/web/views/admin/admin.css' type='text/css'/> 
        <link href='http://fonts.googleapis.com/css?family=Architects+Daughter' rel='stylesheet' type='text/css'>
        <meta charset="utf-8" />
        <meta name="viewport"  content=" width=320 ,user-scalable=no, minimum-scale=1.0 maximum-scale=1.0"  />
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />        
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
        <script type="text/javascript" src="../../web/controlers/admin/admin.js"></script>        
    </head>

    <body>
    	<div id="bloc_page">
    		<!-- definition header bande superieure au menu navigation -->
    		<header id="logo">
    			<p> PCLOUD ADMINISTRATION</p>
    		</header>
            <div id="feed"></div>
            <div id="feed1">
        	    <form>
                    <label> nom: <br>
                        <input type="text" placeholder="nom">
                    </label> 
                    <label> description:<br>
                        <textarea></textarea>   
                    </label>
                    <label>
                        <iframe  frameborder="0px"  src="../../../index.php/admin/file/upload"></iframe>
                    </label>
                </form>        	
            </div>
    		<!-- definition du header pour menu navigation-->
    		<header id="menu">
			    <nav role="navigation">
			        <ul>
			            <li><a href="#" title="file management">FILE</a><span class="darrow">&#9660;</span>
			            	<ul class="sub1">
			            		<li><a href="/index.php/admin/file/upload" id="add">Add</a></li>
			            		<li><a href="#">All files</a></li>
			            		<li><a href="#">other</a></li>
			            	</ul>
			            </li>
			            <li><a href="#" title="user management">USER</a> <span class="darrow">&#9660;</span>
			            	<ul class="sub1">
			            		<li><a href="/index.php/admin/file/form">Subscribers</a></li>
			            		<li><a href="#">Connected</a></li>
                                <li><a href="#">Denied</a></li>
			            		<li><a href="#">All Users</a></li>
			            	</ul>
			            </li>
			            <li><a href="#" title="group management">GROUP</a> <span class="darrow">&#9660;</span>
			            	<ul class="sub1">
			            		<li><a href="#">FILE</a> <span class="rarrow">&#9654;</span>
			            			<ul class="sub2">
					            		<li><a href="#">New File Group</a></li>
					            		<li><a href="#">All File Groups</a></li>
					            		<li><a href="#">other</a></li>
					            	</ul>
			            		</li>
			            		<li><a href="#">USER</a> <span class="rarrow">&#9654;</span>
			            			<ul class="sub2">
					            		<li><a href="#">New User Group</a></li>
					            		<li><a href="#">All User Groups</a></li>
					            		<li><a href="#">other</a></li>
					            	</ul>
			            		</li>
			            		<li><a href="#">other</a></li>
			            	</ul> 
			            </li>                      
			            <li><a href="#" title="contact">Contact</a> <span class="darrow">&#9660;</span>
			            	<ul class="sub1">
			            		<li><a href="/index.php/user/sendMail/#">Send User Mail</a></li>
			            		<li><a href="#">Send Group Mail</a></li>
			            		<li><a href="#">&copy Copyright</a></li>
			            	</ul>
			            </li>
			        </ul>
			    </nav>
			</header>
			<!-- fin header menu navigation-->

			
			<section id="welcome">
    			<h1> {{nom}} 'S ADMIN PAGE</h1>
    			<br>
    			<h3> {{msg}}</h3>    			
				
    		</section>
    		

			<!-- definition du footer -->

			<!--
    		<footer>
			    <p>			    	
			    	<p>Copyright &copy; 2014. -- Ndiaye & Wondeu --</p>
			    	<a href="#">contacter !</a>
			    </p> 
			</footer>
			-->
			<!-- fin footer -->

    	</div>
    </body>
</html>