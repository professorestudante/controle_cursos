
        <?php
        include_once 'header.php';
        echo '<div class="areaCursista">';
        ?>
        
            <h3>Autenticidade do Certificado</h3><br><br>
        <p>O objetivo desta página é validar se o certificado que você possui 
            em mãos foi emitido pela Célula de Formação, Programas e Projetos - CEFOP.
            Digite o Código de Autenticidade (impresso no certificado, 
            do lado superior direito).</p>
        <br>
        <p><strong>Atenção:</strong> Cada certificado emitido possui um código verificador.</p>
        <form action="controleValida.php" method="POST">
            <p><input type="text" name="verifica" id="cx_valida"></p>
            <input type="submit" value="Validar" id="submit3">
                <input type="reset" value="Cancelar" id="reset3">
        </form>


        <?php
        include_once 'footer.php';
        ?>
