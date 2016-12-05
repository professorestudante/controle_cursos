<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

        
        $arrayMensagem=array();
        $contato='</br><p>Em caso de dúvida entre em contato conosco:</p>'
                        . '<p>Célula de Formação, Programas e Projetos</p></br>'
                        . '<p>(85) 3101-2061 </p></br><p> (85) 3218-1213  </p></br>'
                        . '<p><a href="mailto:cefop@ct.seduc.ce.gov.br">cefop@ct.seduc.ce.gov.br</a></p>';
        
        
        $arrayMensagem[0]='<h3>Este certificado não pode ser impresso!</h3><br><br><div class="aviso">'
                        .$contato.'</div>'
                        . '<a href="areaCursista.php">Voltar</a>'
                        . '</br></br><a href="acessocursista.php">Sair</a>';
        
        $arrayMensagem[1]='<div class="aviso"><h3>ATENÇÃO!</h3><p>Ao imprimir o certificado com o total '
                                . 'de módulos cursados, você NÃO poderá imprimir separadamente certificados '
                                . 'individuais '
                                . 'dos módulos selecionados.</p>'
                                . $contato.'</div>'
                                . '<p><a href="certificados_total.php">SIM,</a> eu quero o certificado com o '
                                . 'total de módulos cursados</p></br>'
                                .'<p><a href="areaCursista.php">NÃO,</a> eu  quero imprimir certificados '
                                . ' individuais'
                                . ' para cada módulo separadamente.</p>'
                                . '</br></br><a href="acessocursista.php">Sair</a>';
        
        $arrayMensagem[2]='<h3><a href="certificados_total.php">Reimprimir certificado de ';
        $arrayMensagem[3]=' horas</a></h3><br><br><br>'
                        . '<p><div class="aviso">ATENÇÃO! Havendo módulos cursados e não listados neste certificado, os mesmos deverão '
                        . 'ser impressos individualmente na <a href="areaCursista.php"> '
                . 'área do cursista</a></p>'
                        .$contato.'</div>'
                        .'<a href="areaCursista.php">Voltar</a>'
                . '</br></br><a href="acessocursista.php">Sair</a>';
        $arrayMensagem[4]='<div class="aviso"><h3>ATENÇÃO!</h3><br><p>Ao imprimir separadamemente o certificado deste módulo, '
                . 'você NÃO poderá imprimir o certificado de maior carga horária com o total '
                . 'de módulos cursados <p>'.$contato.'</div>'
                        . '<p><a href="certificados_individual.php">SIM,</a> eu quero '
                        . 'imprimir o certificado deste módulo '
                        . ' com carga horária de 60 horas</p></br>'
                        .'<p><a href="areaCursista.php">NÃO,</a> eu quero imprimir '
                        . ' um único certificado com maior carga horária e contendo'
                        . ' todos os módulos cursados</p>'
                        . '</br></br><a href="acessocursista.php">Sair</a>';
        $arrayMensagem[5]='<h3><a href="certificados_total.php">Imprimir certificado de ';