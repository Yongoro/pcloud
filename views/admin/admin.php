<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel='stylesheet' href='/web/views/admin/admin.css' type='text/css' media='all'/>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />        
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
        <meta name="viewport"  content=" width=320 ,user-scalable=no, minimum-scale=1.0 maximum-scale=1.0"  />
        <meta charset="utf-8" />
        
        <title>administration </title>
    </head>
    <body>
        
    <header>
      <span><a href=""><img src="/web/images/fichier.jpeg" alt="gerer les fichiers" ></img></a></span>
      <span><a href=""><img src="/web/images/vue.jpg" ></img></a></span>
      <span><a href=""><img src="/web/images/users.jpg" ></img></a></span>
      <span><a href=""><img src="/web/images/operation.png" ></img></a></span>
      <span><a href=""><img src="/web/images/statistique.jpg" ></img></a></span>
      <span><a href=""><img src="/web/images/annuler.png" ></img></a></span>
      <span><a href=""><img src="/web/images/recherche.jpg" ></img></a></span>
      <input type="text" name="group_user" value="recherche">
    </header>


     <div  class ="content" >
     <div  class ="partieGauch" >
         <section class="partieDemandeEncours">demande en cours   
            <ul>
              {% for demande  in demandes %}
              <li> <ul> <li> <a href="">{{demande.id_user}}</li><li>{{demande.date_subscription_user}} </a></li></ul></li>
              {% endfor %}
           </ul>
     </section >

         <section class="partieFichiersRecents" > fichiers récents
             <ul>
              {% for fichier  in files %}
               <li> <ul> <li>{{fichier.id_file}}</li><li>{{fichier.date_creat_file}}</li></ul></li>
               {% endfor%}
            </ul>



         </section >   
     </div>

      <div  class ="partieMilieu">
    
         <div class=" partieGroupFichier">
                <div class="addGroupFichier">
                 <form method="post" action="addGroup">
                         <fieldset>
                            <legend>nouveau groupe de fichiers</legend>
                              <label for="group_fichier">groupe </label> <input type="text" id="group_fichier"><br><br>
                               <textarea name="details" rows=4 cols=40> description</textarea><br><br>
                               <input type="submit" value="ajouter" name="add_user">
                         </fieldset>
                  </form> 
                </div>
                <div class=" partie5GroupFichier">  <form>
                         <fieldset>
                            <legend>nouveau fichier</legend>
                              <label for="group_fichier">nom</label> <input type="text" id="group_fichier"><br><br>
                             <label for="group_fichier">type</label> <input type="text" id="group_fichier"><br><br>
                               <input type="submit" value="ajouter" name="add_user">
                         </fieldset>
                  </form> 

                </div>
         </div>
         <div class=" partieGroupUser"> 
                <div class=" partie5GroupUser">    
                  <form>
                         <fieldset>
                            <legend >nouvel utilisateur</legend>
                               <label for="group_user">nom </label> <input type="text" name="group_user"><br><br>
                               <label for="group_user">prenom </label> <input type="text" name="group_user"><br><br>
                               <label for="group_user">pseudo</label> <input type="text" name="group_user"><br><br>
                               <label for="group_user">passe </label> <input type="text" name="group_user"><br><br>
                               <label for="group_user">passe</label> <input type="text" name="group_user"><br><br>
                               <label for="group_user">email </label> <input type="text" name="group_user"><br><br>
                                 <label for="sexe">sexe </label> 
                                 <input type="radio" name="sexe" value="1">homme<input type="radio" name="sexe" value="0">femme<br>
                               <input type="submit" value="ajouter" name="add_user">
                         </fieldset>
                  </form> 
                </div>
             
                <div class="addGroupUser">
                   <form>
                         <fieldset>
                            <legend >nouveau group d'utilisateurs</legend>
                               <label for="group_user">nom </label> <input type="text" name="group_user"><br><br>
                               <textarea name="details" rows=4 cols=40> description</textarea><br><br>
                               <input type="submit" value="ajouter" name="add_user">
                         </fieldset>
                  </form> 
                  <form>
                         <fieldset style=" height: 120px;">
                            <legend>attribuer des droits</legend>
                           <label for="group_user">nom </label> <select name="group_user" size="1">
                               <OPTION>administration
                               <OPTION>amis
                               <OPTION>faculte
                               <OPTION>famille
                               <OPTION>autres
                            </select><br><br>
                           <label for="group_fichier">vue</label> <select name="group_file" size="1">
                               <OPTION>administration
                               <OPTION>amis
                               <OPTION>faculte
                               <OPTION>famille
                               <OPTION>autres
                            </select><br><br>
                            <input type="submit" value="attribuer" name="add_user">
                         </fieldset>
                  </form> 
                </div>
       </div>
       
     </div>


      <div class ="partieDroit">
         <section class="partieStat"> statistiques 
         <ul>
         <li style="margin-bottom:3px; margin-top:3px;">connexion en cours : nombreUser</li>
         <li style="margin-bottom:3px; margin-top:3px;">utilisateurs: users</li>
         <li style="margin-bottom:3px; margin-top:3px;">fichiers: files</li>
         <li style="margin-bottom:3px; margin-top:3px; ">groupe de fichiers : groupsFichiers</li>
         <li style="margin-bottom:3px; margin-top:3px;">groupe d'utilisateurs: groupsUsers</li>
        </ul>
 </section > 
         <section class="partieUtilisateursConnnectes"> liste des utilisateurs connectés
        <ul>
           {% for connecte  in connectes %}
           <li><a href="">{{connecte.pseudo_user}}</a></li>
            {% endfor %}
         </ul>
         </section >
         <section class="partieOperationRecentes" > opérations récentes
            <ul>
               {% for operation  in operations %}
                 <li><a href="">{{operation.name_operation}}</a></li>
               {% endfor %}
            </ul>
     </section > 
     </div>
     </div>
    <footer>
         
     
       
        
    </footer>
    </body>
</html>
