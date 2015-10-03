/*
 * CbiotScripts
 *
 * This script contains several scripts used in Cbiot System
 *
 * Author: Arthur Kalsing
 * Some scripts were copied/adapted from websites (see reference before each snipet)
 *
 */

//------------------------------------------------------------------------

//JavaScript expansion to add remove() method
//http://stackoverflow.com/questions/3387427/javascript-remove-element-by-id
Element.prototype.remove = function() {
	this.parentElement.removeChild(this);
}
NodeList.prototype.remove = HTMLCollection.prototype.remove = function() {
	for(var i = 0, len = this.length; i < len; i++) {
		if(this[i] && this[i].parentElement) {
			this[i].parentElement.removeChild(this[i]);
		}
	}
}

function getSelectedText(elementId) {
    var elt = document.getElementById(elementId);

    if (elt.selectedIndex == -1)
        return null;

    return elt.options[elt.selectedIndex].text;
}

var contact_count = 1;
function addContact() {
	
	if(contact_count >= 10) {
		alert("Nro maximo de contatos atingido!");
		return;
	}
				
	var pt1 = document.getElementById('part_1');
	var pt2 = document.getElementById('part_2');
	var pt3 = document.getElementById('part_3');
	var pt4 = document.getElementById('part_4');
	var pt5 = document.getElementById('part_5');
	
	var container = document.getElementById('contacts_container');
	var contact_type = document.getElementById('tipo_contato_0');
	var contact_value = document.getElementById('contato_0');
	
	var plus_sign = document.getElementById('plus_sign');
	var minus_sign = document.getElementById('minus_sign');
	
	//append form-group to contacts_container div
	var tmp = pt1.cloneNode(true);
	while (tmp.hasChildNodes()) {
		tmp.removeChild(tmp.lastChild);
	}
	tmp.id = pt1.id + "_" + contact_count;
	container.appendChild(tmp);
	//set container to new form-group created
	container = document.getElementById(pt1.id + "_" + contact_count);
	//append label
	tmp = pt2.cloneNode(true);
	tmp.id = pt2.id + "_" + contact_count;
	container.appendChild(tmp);
	//append the select div
	tmp = pt3.cloneNode(true);
	while (tmp.hasChildNodes()) {
		tmp.removeChild(tmp.lastChild);
	}
	tmp.id = pt3.id + "_" + contact_count;
	container.appendChild(tmp);
	//set container to new div created
	container = document.getElementById(pt3.id + "_" + contact_count);
	//append the select
	tmp = contact_type.cloneNode(true);
	tmp.name = 'tipo_contato_' + contact_count;
	tmp.id = 'tipo_contato_' + contact_count;
	container.appendChild(tmp);
	//set container back to our form-group
	container = document.getElementById(pt1.id + "_" + contact_count);
	//append label
	tmp = pt4.cloneNode(true);
	tmp.id = pt4.id + "_" + contact_count;
	container.appendChild(tmp);
	//append the input text div
	tmp = pt5.cloneNode(true);
	while (tmp.hasChildNodes()) {
		tmp.removeChild(tmp.lastChild);
	}
	tmp.id = pt5.id + "_" + contact_count;
	container.appendChild(tmp);
	//set container to new div created
	container = document.getElementById(pt5.id + "_" + contact_count);
	//append the input
	tmp = contact_value.cloneNode(true);
	tmp.name = 'contato_' + contact_count;
	tmp.id = 'contato_' + contact_count;
	tmp.value = "";
	container.appendChild(tmp);
	
	//set container back to our form-group
	container = document.getElementById(pt1.id + "_" + contact_count);
	//remove plus_sign from last element
	tmp = plus_sign.cloneNode(true);
	document.getElementById("plus_sign").remove();
	//add plus_sign
	container.appendChild(tmp);
	//remove minus_sign from last element
	tmp = minus_sign.cloneNode(true);
	tmp.style.visibility="visible";
	document.getElementById("minus_sign").remove();
	//add minus_sign
	container.appendChild(tmp);
			
	contact_count += 1;
	return;
}

function removeContact() {
	if(contact_count == 1) {
		// nothing to do
		return;
	}
	if(contact_count >= 3) {
		var plus_sign = document.getElementById('plus_sign');
		var minus_sign = document.getElementById('minus_sign');

		//adding plus and minus to last added row
		var container = document.getElementById('part_1' + '_' + (contact_count-2));
		container.appendChild(plus_sign);
		container.appendChild(minus_sign);
		//removing current row
		document.getElementById('part_1' + '_' + (contact_count-1)).remove();
		
		contact_count -= 1;
		return;
	} else {	// == 2
		var plus_sign = document.getElementById('plus_sign');
		var minus_sign = document.getElementById('minus_sign');
		
		//adding plus and minus to first row
		var container = document.getElementById('part_1');
		container.appendChild(plus_sign);
		minus_sign.style.visibility="hidden";
		container.appendChild(minus_sign);
		//removing current row
		document.getElementById('part_1' + '_' + (contact_count-1)).remove();
		
		contact_count -= 1;
		return;
	}			
}
		
//------------------------------------------------------------------------
 var MAX_DEVICE = 255;
 var device_count = 1;
function addDevice() {
	
	if(device_count >= MAX_DEVICE) {
		alert("Nro maximo de dispositivos atingido!");
		return;
	}
				
	var pt1 = document.getElementById('parte_1');
	var pt2 = document.getElementById('parte_2');
	var pt3 = document.getElementById('parte_3');
	var pt4 = document.getElementById('parte_4');
	var pt5 = document.getElementById('parte_5');
	var pt6 = document.getElementById('parte_6');
	var pt7 = document.getElementById('parte_7');
	
	var container = document.getElementById('devices_container');
	var device_type = document.getElementById('tipo_dispositivo_0');
	var device_mac = document.getElementById('dispositivo_mac_0');
	var device_registration = document.getElementById('dispositivo_registration_0');
	var device_hostname = document.getElementById('dispositivo_hostname_0');
	var device_location = document.getElementById('dispositivo_location_0');
	
	var plus_sign = document.getElementById('plus_sign_dev');
	var minus_sign = document.getElementById('minus_sign_dev');
	
	//append form-group to devices_container div
	var tmp = pt1.cloneNode(true);
	while (tmp.hasChildNodes()) {
		tmp.removeChild(tmp.lastChild);
	}
	tmp.id = pt1.id + "_" + device_count;
	container.appendChild(tmp);
	
	//set container to new form-group created
	container = document.getElementById(pt1.id + "_" + device_count);
	//append label
	tmp = pt2.cloneNode(true);
	tmp.id = pt2.id + "_" + device_count;
	container.appendChild(tmp);
	//append the select div
	tmp = pt3.cloneNode(true);
	while (tmp.hasChildNodes()) {
		tmp.removeChild(tmp.lastChild);
	}
	tmp.id = pt3.id + "_" + device_count;
	container.appendChild(tmp);
	//set container to new div created
	container = document.getElementById(pt3.id + "_" + device_count);
	//append the select
	tmp = device_type.cloneNode(true);
	tmp.name = 'tipo_dispositivo_' + device_count;
	tmp.id = 'tipo_dispositivo_' + device_count;
	container.appendChild(tmp);
	//set container back to our form-group
	container = document.getElementById(pt1.id + "_" + device_count);
	
	//append the input text div
	tmp = pt4.cloneNode(true);
	while (tmp.hasChildNodes()) {
		tmp.removeChild(tmp.lastChild);
	}
	tmp.id = pt4.id + "_" + device_count;
	container.appendChild(tmp);
	//set container to new div created
	container = document.getElementById(pt4.id + "_" + device_count);
	//append the input
	tmp = device_registration.cloneNode(true);
	tmp.name = 'dispositivo_registration_' + device_count;
	tmp.id = 'dispositivo_registration_' + device_count;
	tmp.value = "";
	container.appendChild(tmp);
	
	//set container back to our form-group
	container = document.getElementById(pt1.id + "_" + device_count);
	
	//append the input text div
	tmp = pt5.cloneNode(true);
	while (tmp.hasChildNodes()) {
		tmp.removeChild(tmp.lastChild);
	}
	tmp.id = pt5.id + "_" + device_count;
	container.appendChild(tmp);
	//set container to new div created
	container = document.getElementById(pt5.id + "_" + device_count);
	//append the input
	tmp = device_hostname.cloneNode(true);
	tmp.name = 'dispositivo_hostname_' + device_count;
	tmp.id = 'dispositivo_hostname_' + device_count;
	tmp.value = "";
	container.appendChild(tmp);
	
	//set container back to our form-group
	container = document.getElementById(pt1.id + "_" + device_count);
	
	//append the input text div
	tmp = pt6.cloneNode(true);
	while (tmp.hasChildNodes()) {
		tmp.removeChild(tmp.lastChild);
	}
	tmp.id = pt6.id + "_" + device_count;
	container.appendChild(tmp);
	//set container to new div created
	container = document.getElementById(pt6.id + "_" + device_count);
	//append the input
	tmp = device_mac.cloneNode(true);
	tmp.name = 'dispositivo_mac_' + device_count;
	tmp.id = 'dispositivo_mac_' + device_count;
	tmp.value = "";
	container.appendChild(tmp);
	
	//set container back to our form-group
	container = document.getElementById(pt1.id + "_" + device_count);
	
	//append the input text div
	tmp = pt7.cloneNode(true);
	while (tmp.hasChildNodes()) {
		tmp.removeChild(tmp.lastChild);
	}
	tmp.id = pt7.id + "_" + device_count;
	container.appendChild(tmp);
	//set container to new div created
	container = document.getElementById(pt7.id + "_" + device_count);
	//append the input
	tmp = device_location.cloneNode(true);
	tmp.name = 'dispositivo_location_' + device_count;
	tmp.id = 'dispositivo_location_' + device_count;
	tmp.value = "";
	container.appendChild(tmp);
		
	//set container back to our form-group
	container = document.getElementById(pt1.id + "_" + device_count);
	//remove plus_sign from last element
	tmp = plus_sign.cloneNode(true);
	document.getElementById("plus_sign_dev").remove();
	//add plus_sign
	container.appendChild(tmp);
	//remove minus_sign from last element
	tmp = minus_sign.cloneNode(true);
	tmp.style.visibility="visible";
	document.getElementById("minus_sign_dev").remove();
	//add minus_sign
	container.appendChild(tmp);
			
	device_count += 1;
	return;
}

function removeDevice() {
	if(device_count == 1) {
		// nothing to do
		return;
	}
	if(device_count >= 3) {
		var plus_sign = document.getElementById('plus_sign_dev');
		var minus_sign = document.getElementById('minus_sign_dev');

		//adding plus and minus to last added row
		var container = document.getElementById('parte_1' + '_' + (device_count-2));
		container.appendChild(plus_sign);
		container.appendChild(minus_sign);
		//removing current row
		document.getElementById('parte_1' + '_' + (device_count-1)).remove();
		
		device_count -= 1;
		return;
	} else {	// == 2
		var plus_sign = document.getElementById('plus_sign_dev');
		var minus_sign = document.getElementById('minus_sign_dev');
		
		//adding plus and minus to first row
		var container = document.getElementById('parte_1');
		container.appendChild(plus_sign);
		minus_sign.style.visibility="hidden";
		container.appendChild(minus_sign);
		//removing current row
		document.getElementById('parte_1' + '_' + (device_count-1)).remove();
		
		device_count -= 1;
		return;
	}			
}

function addDeviceAdm() {
	
	if(device_count >= MAX_DEVICE) {
		alert("Nro maximo de dispositivos atingido!");
		return;
	}
				
	var pt1 = document.getElementById('parte_1');
	var pt2 = document.getElementById('parte_2');
	var pt3 = document.getElementById('parte_3');
	var pt4 = document.getElementById('parte_4');
	var pt5 = document.getElementById('parte_5');
	var pt6 = document.getElementById('parte_6');
	var pt7 = document.getElementById('parte_7');
	var pt8 = document.getElementById('parte_8');
	
	var container = document.getElementById('devices_container');
	var device_type = document.getElementById('tipo_dispositivo_0');
	var device_mac = document.getElementById('dispositivo_mac_0');
	var device_registration = document.getElementById('dispositivo_registration_0');
	var device_hostname = document.getElementById('dispositivo_hostname_0');
	var device_ip = document.getElementById('dispositivo_ip_0');
	var device_location = document.getElementById('dispositivo_location_0');
	
	var plus_sign = document.getElementById('plus_sign_dev');
	var minus_sign = document.getElementById('minus_sign_dev');
	
	//append form-group to devices_container div
	var tmp = pt1.cloneNode(true);
	while (tmp.hasChildNodes()) {
		tmp.removeChild(tmp.lastChild);
	}
	tmp.id = pt1.id + "_" + device_count;
	container.appendChild(tmp);
	
	//set container to new form-group created
	container = document.getElementById(pt1.id + "_" + device_count);
	
	//append the select div
	tmp = pt3.cloneNode(true);
	while (tmp.hasChildNodes()) {
		tmp.removeChild(tmp.lastChild);
	}
	tmp.id = pt3.id + "_" + device_count;
	container.appendChild(tmp);
	//set container to new div created
	container = document.getElementById(pt3.id + "_" + device_count);
	//append the select
	tmp = device_type.cloneNode(true);
	tmp.name = 'tipo_dispositivo_' + device_count;
	tmp.id = 'tipo_dispositivo_' + device_count;
	container.appendChild(tmp);
	
	//set container back to our form-group
	container = document.getElementById(pt1.id + "_" + device_count);
	
	//append the input text div
	tmp = pt4.cloneNode(true);
	while (tmp.hasChildNodes()) {
		tmp.removeChild(tmp.lastChild);
	}
	tmp.id = pt4.id + "_" + device_count;
	container.appendChild(tmp);
	//set container to new div created
	container = document.getElementById(pt4.id + "_" + device_count);
	//append the input
	tmp = device_registration.cloneNode(true);
	tmp.name = 'dispositivo_registration_' + device_count;
	tmp.id = 'dispositivo_registration_' + device_count;
	tmp.value = "";
	container.appendChild(tmp);
	
	//set container back to our form-group
	container = document.getElementById(pt1.id + "_" + device_count);
	
	//append the input text div
	tmp = pt5.cloneNode(true);
	while (tmp.hasChildNodes()) {
		tmp.removeChild(tmp.lastChild);
	}
	tmp.id = pt5.id + "_" + device_count;
	container.appendChild(tmp);
	//set container to new div created
	container = document.getElementById(pt5.id + "_" + device_count);
	//append the input
	tmp = device_ip.cloneNode(true);
	tmp.name = 'dispositivo_ip_' + device_count;
	tmp.id = 'dispositivo_ip_' + device_count;
	tmp.value = "";
	container.appendChild(tmp);
	
	//set container back to our form-group
	container = document.getElementById(pt1.id + "_" + device_count);
	
	//append the input text div
	tmp = pt6.cloneNode(true);
	while (tmp.hasChildNodes()) {
		tmp.removeChild(tmp.lastChild);
	}
	tmp.id = pt6.id + "_" + device_count;
	container.appendChild(tmp);
	//set container to new div created
	container = document.getElementById(pt6.id + "_" + device_count);
	//append the input
	tmp = device_hostname.cloneNode(true);
	tmp.name = 'dispositivo_hostname_' + device_count;
	tmp.id = 'dispositivo_hostname_' + device_count;
	tmp.value = "";
	container.appendChild(tmp);
	
	//set container back to our form-group
	container = document.getElementById(pt1.id + "_" + device_count);
	
	//append the input text div
	tmp = pt7.cloneNode(true);
	while (tmp.hasChildNodes()) {
		tmp.removeChild(tmp.lastChild);
	}
	tmp.id = pt7.id + "_" + device_count;
	container.appendChild(tmp);
	//set container to new div created
	container = document.getElementById(pt7.id + "_" + device_count);
	//append the input
	tmp = device_mac.cloneNode(true);
	tmp.name = 'dispositivo_mac_' + device_count;
	tmp.id = 'dispositivo_mac_' + device_count;
	tmp.value = "";
	container.appendChild(tmp);
	
	//set container back to our form-group
	container = document.getElementById(pt1.id + "_" + device_count);
	
	//append the input text div
	tmp = pt8.cloneNode(true);
	while (tmp.hasChildNodes()) {
		tmp.removeChild(tmp.lastChild);
	}
	tmp.id = pt8.id + "_" + device_count;
	container.appendChild(tmp);
	//set container to new div created
	container = document.getElementById(pt8.id + "_" + device_count);
	//append the input
	tmp = device_location.cloneNode(true);
	tmp.name = 'dispositivo_location_' + device_count;
	tmp.id = 'dispositivo_location_' + device_count;
	tmp.value = "";
	container.appendChild(tmp);
		
	//set container back to our form-group
	container = document.getElementById(pt1.id + "_" + device_count);
	//remove plus_sign from last element
	tmp = plus_sign.cloneNode(true);
	document.getElementById("plus_sign_dev").remove();
	//add plus_sign
	container.appendChild(tmp);
	//remove minus_sign from last element
	tmp = minus_sign.cloneNode(true);
	tmp.style.visibility="visible";
	document.getElementById("minus_sign_dev").remove();
	//add minus_sign
	container.appendChild(tmp);
			
	device_count += 1;
	return;
}

function removeDeviceAdm() {
	if(device_count == 1) {
		// nothing to do
		return;
	}
	if(device_count >= 3) {
		var plus_sign = document.getElementById('plus_sign_dev');
		var minus_sign = document.getElementById('minus_sign_dev');

		//adding plus and minus to last added row
		var container = document.getElementById('parte_1' + '_' + (device_count-2));
		container.appendChild(plus_sign);
		container.appendChild(minus_sign);
		//removing current row
		document.getElementById('parte_1' + '_' + (device_count-1)).remove();
		
		device_count -= 1;
		return;
	} else {	// == 2
		var plus_sign = document.getElementById('plus_sign_dev');
		var minus_sign = document.getElementById('minus_sign_dev');
		
		//adding plus and minus to first row
		var container = document.getElementById('parte_1');
		container.appendChild(plus_sign);
		minus_sign.style.visibility="hidden";
		container.appendChild(minus_sign);
		//removing current row
		document.getElementById('parte_1' + '_' + (device_count-1)).remove();
		
		device_count -= 1;
		return;
	}			
}
		

	
//------------------------------------------------------------------------
 
 
function validarCPF( cpf ) {
	var filtro = /^\d{3}.\d{3}.\d{3}-\d{2}$/i;
	
	if(!filtro.test(cpf)) {
		window.alert("CPF invalido. Tente novamente.");
		return false;
	}
   
	cpf = remove(cpf, ".");
	cpf = remove(cpf, "-");
	
	if(cpf.length != 11 || cpf == "11111111111" ||
		cpf == "22222222222" || cpf == "33333333333" || cpf == "44444444444" ||
		cpf == "55555555555" || cpf == "66666666666" || cpf == "77777777777" ||
		cpf == "88888888888" || cpf == "99999999999")
	{
		window.alert("CPF invalido. Por favor digite um CPF valido.");
		return false;
    }

	soma = 0;
	for(i = 0; i < 9; i++) {
		soma += parseInt(cpf.charAt(i)) * (10 - i);
	}
	
	resto = 11 - (soma % 11);
	if(resto == 10 || resto == 11)
	{
		resto = 0;
	}
	if(resto != parseInt(cpf.charAt(9))) {
		window.alert("CPF invalido. Por favor digite um CPF valido.");
		return false;
	}
	
	soma = 0;
	for(i = 0; i < 10; i ++) {
		soma += parseInt(cpf.charAt(i)) * (11 - i);
	}
	resto = 11 - (soma % 11);
	if(resto == 10 || resto == 11) {
		resto = 0;
	}
	
	if(resto != parseInt(cpf.charAt(10))) {
		window.alert("CPF invalido. Por favor digite um CPF valido.");
		return false;
	}
	
	return true;
}

function remove(str, sub) {
	i = str.indexOf(sub);
	r = "";
	if (i == -1) return str;
	{
		r += str.substring(0,i) + remove(str.substring(i + sub.length), sub);
	}
	return r;
}

// MASCARA ( mascara(o,f) e execmascara() ) CRIADAS POR ELCIO LUIZ
function mascara(o,f) {
	v_obj=o
	v_fun=f
	setTimeout("execmascara()",1)
}

function execmascara() {
	v_obj.value=v_fun(v_obj.value)
}

function cpf_mask(v) {
	v=v.replace(/\D/g,"")                 //Remove tudo o que não é dígito
	v=v.replace(/(\d{3})(\d)/,"$1.$2")    //Coloca ponto entre o terceiro e o quarto dígitos
	v=v.replace(/(\d{3})(\d)/,"$1.$2")    //Coloca ponto entre o setimo e o oitava dígitos
	v=v.replace(/(\d{3})(\d)/,"$1-$2")    //Coloca ponto entre o decimoprimeiro e o decimosegundo dígitos
	return v
}

//------------------------------------------------------------------------
