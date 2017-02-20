<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<p>Envio manual de informaci&oacute;n</p>
<form enctype="multipart/form-data" action="<?= base_url().'index.php/sqlite/zip' ?>" method="POST">
    <div>Nombre de usuario:<input name="username" type="text"/></div>
    <div>Contrase&ntilde;a:<input name="password" type="password"/></div>
    <input name="version" type="hidden" value="1.05"/>
    <!-- MAX_FILE_SIZE debe preceder el campo de entrada de archivo -->
    <!-- <input type="hidden" name="MAX_FILE_SIZE" value="30000" /> -->
    <!-- El nombre del elemento de entrada determina el nombre en el array $_FILES -->
    Enviar este archivo: <input name="file" type="file"/>
    <input type="submit" value="Send File" />
</form>