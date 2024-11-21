<?php
/**
Se verifica que la clave ingresada es igual a la cadena encriptada
*/
if(md5($_POST['clave'])=='fbc71ce36cc20790f2eeed2197898e71'){
 echo 'usuario correcto';
}else{
 echo 'usuario incorrecto';
};
?> 
