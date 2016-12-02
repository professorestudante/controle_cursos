
        <?php
         
        include_once 'header.php';
        if(isset($_SESSION["email"]) or isset($_SESSION["matricula"])){
                  unset($_SESSION["email"]);
        unset($_SESSION["matricula"]); 
        }
        ?>
        <form action ="controlador.php" method="POST" id="acesso">
	
            <div class="barra"><h3>ACESSO</h3></div>
            <ul id="ul_acesso">
                <li>USUÁRIO<input type="email" id="cx_email" required="required" name="email"/></li>
                <li>SENHA<input type="password" id="cx_matricula" required="required" name="matricula"/></li>
        <li><input type="submit" value="Entrar" id="submit"><input type="reset" value="Limpar" id="reset"></li>
	</ul>
	
       </form>
        
        <div class="instrucoes">
            <h3>Acesso à área do cursista</h3>
            <p>
                Insira seus dados para ter acesso aos certificados referentes aos cursos
                realizados no Programa de Formação de Coordenadores Escolares da Sefor.
            </p>
             </div>
        
       <?php
        include_once 'footer.php';
       
