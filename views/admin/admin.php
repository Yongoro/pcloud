<!DOCTYPE html>
<html>
    <head>
        <title>Administration form</title>
        <link rel='stylesheet' href='pcloud/web/views/admin.css' type='text/css'/> 
        <link href='http://fonts.googleapis.com/css?family=Architects+Daughter' rel='stylesheet' type='text/css'>
        <meta charset="utf-8" />
        <meta name="viewport"  content=" width=320 ,user-scalable=no, minimum-scale=1.0 maximum-scale=1.0"  />

    </head>

    <body>
    	<div id="bloc_page">
    		<!-- definition header bande superieure au menu navigation -->
    		<header id="logo">
    			<p> PCLOUD ADMINISTRATION</p>
    		</header>

    		<!-- definition du header pour menu navigation-->
    		<header id="menu">
			    <nav role="navigation">
			        <ul>
			            <li><a href="#" title="file management">FILE</a><span class="darrow">&#9660;</span>
			            	<ul class="sub1">
			            		<li><a href="#">add</a></li>
			            		<li><a href="#">remove</a></li>
			            		<li><a href="#">other</a></li>
			            	</ul>
			            </li>
			            <li><a href="#" title="user management">USER</a> <span class="darrow">&#9660;</span>
			            	<ul class="sub1">
			            		<li><a href="#">add</a></li>
			            		<li><a href="#">remove</a></li>
			            		<li><a href="#">other</a></li>
			            	</ul>
			            </li>
			            <li><a href="#" title="group management">GROUP</a> <span class="darrow">&#9660;</span>
			            	<ul class="sub1">
			            		<li><a href="#">FILE</a> <span class="rarrow">&#9654;</span>
			            			<ul class="sub2">
					            		<li><a href="#">add</a></li>
					            		<li><a href="#">modify</a></li>
					            		<li><a href="#">remove</a></li>
					            	</ul>
			            		</li>
			            		<li><a href="#">USER</a> <span class="rarrow">&#9654;</span>
			            			<ul class="sub2">
					            		<li><a href="#">add</a></li>
					            		<li><a href="#">modify</a></li>
					            		<li><a href="#">remove</a></li>
					            	</ul>
			            		</li>
			            		<li><a href="#">other</a></li>
			            	</ul> 
			            </li>                      
			            <li><a href="#" title="contact us">Contact</a> <span class="darrow">&#9660;</span>
			            	<ul class="sub1">
			            		<li><a href="#">by email</a></li>
			            		<li><a href="#">by a form</a></li>
			            		<li><a href="#">&copy Copyright</a></li>
			            	</ul>
			            </li>
			        </ul>
			    </nav>
			</header>
			<!-- fin header menu navigation-->

			
			<section id="welcome">
    			<h1> {{nom}} 'S ADMIN PAGE</h1>
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