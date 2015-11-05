/* ATN Simple Cart
 * Copyright 2014 ATN Solutions
 * http://www.atnsolutions.com
 *
 * Licensed under the Apache License v2.0
 * http://www.apache.org/licenses/LICENSE-2.0 */ 
 jQuery(document).ready(function(){
	 //InitList();
});
 
 
var cart_products = Array();
var cart_quantities = Array();
var str_checkout_buttons='<a href="javascript:ProceedUpdate()" class="btn btn-xs btn-default add-right-margin">Update</a> <a href="javascript:GoToPayment()" type="button" class="btn btn-xs btn-info">Checkout</a>';

function ShowPopup(x)
{
	document.getElementById("popup").innerHTML=x;
	document.getElementById("popup").style.display="block";
	setTimeout('HidePopup()', 3000);
}

function HidePopup()
{
	document.getElementById("popup").style.display="none";
}

function AddToCart(x)
{
	var product_index = cart_products.indexOf(x);
	
	if (product_index > -1) 
	{
		cart_quantities[x]++;
		
	}
	else
	{
		cart_products.push(x);
		cart_quantities[x] = 1;
	}
	ShowPopup("The product was added successfully to your cart!");
	UpdateCart();
}

function DeleteProduct(x)
{
	var product_index = cart_products.indexOf(x);
	
	if (product_index > -1) 
	{
		cart_products.splice(product_index, 1);
		cart_quantities[x] = 0;
	}
	
}
function DeleteItem(x)
{
	if (confirm("Really you Want To Delete This Item?") == true) {
		var current_quantity = 0;
		
		if(current_quantity==0)
		{
			DeleteProduct(x);
		}
		UpdateCart();
    } else {
        
    }
		
}

function UpdateCart()
{
	var cart_html = "";
	var print = "";
	var subtotal_reil=0;
	var subtotal_dollar=0;
	if(cart_products.length>0)
	{
		cart_html+='<table id="tb_cart" cellpadding="0" cellspacing="0" width="100%" >';
		cart_html+='<thead>';
		cart_html+='<tr>';
		cart_html+='<th>'+table_no+'</th>';
		cart_html+='<th>'+table_prodcut+'</th>';
		cart_html+='<th>'+table_Uni_Price+'</th>';
		cart_html+='<th width="30">'+table_Qty+'</th>';
		cart_html+='<th>'+table_Amount+'</th>';
		cart_html+='<th>'+table_Delete+'</th>';
		cart_html+='</tr>';
		cart_html+='</thead>';
		cart_html+='<tbody>';
		
		print+='<table id="print_cart" style="width: 100%; border:0 0 0px 0 solid #DDD;position: relative;padding: 0px;margin: 0px;border-spacing: 0px;border-collapse: collapse;">';
		print+='<thead>';
		print+='<tr style="border-bottom: 1px solid #DDD;font-size: 9px; background-color: #F2EDED !important;">';
		print+='<th>'+table_no+'</th>';
		print+='<th>'+table_prodcut+'</th>';
		print+='<th>'+table_Uni_Price+'</th>';
		print+='<th width="30">'+table_Qty+'</th>';
		print+='<th>'+table_Amount+'</th>';
		print+='</tr>';
		print+='</thead>';
		print+='<tbody>';
		for(var i=0;i<cart_products.length;i++)
		{
			var unitPrice = parseFloat(product_prices[cart_products[i]]).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
			var totalAmount = parseFloat(product_prices[cart_products[i]]*cart_quantities[cart_products[i]]).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
			inx = i+1;
			cart_html+='<tr>';
			cart_html+='<td align="center">'+inx+'</td>';
			cart_html+='<td><input type="hidden" name="proId_'+inx+'" id="proId_'+inx+'" value="'+proId[cart_products[i]]+'" />'+products[cart_products[i]]+'</td>';
			cart_html+='<td><input type="hidden" name="unitPrice_'+inx+'" id="unitPrice_'+inx+'" value="'+product_prices[cart_products[i]]+'" />'+unitPrice+' '+currency_symbol[cart_products[i]]+'</td>';
			cart_html+='<td width="40px"><input style="text-align:center;" name="quantity_'+inx+'" id="quantity_'+cart_products[i]+'" type="text" value="'+cart_quantities[cart_products[i]]+'" maxlength="3" Onchange="ProceedUpdate();" class="quantity-text"/></td>';
			cart_html+='<td>'+totalAmount+' '+currency_symbol[cart_products[i]]+'</td>';
			cart_html+='<td align="center"><span style="cursor:pointer" Onclick="DeleteItem('+cart_products[i]+')" class="icon-trash"></span></td>';
			cart_html+='</tr>';
			
			print+='<tr class="odd gradeX" style="border-bottom: 1px solid #DDD; font-size: 9px;">';
			print+='<td align="center">'+inx+'</td>';
			print+='<td style="padding-left:5%">'+products[cart_products[i]]+'</td>';
			print+='<td style="padding-left:5%">'+unitPrice+' '+currency_symbol[cart_products[i]]+'</td>';
			print+='<td style="text-align:center;">'+cart_quantities[cart_products[i]]+'</td>';
			print+='<td style="padding-left:5%">'+totalAmount+' '+currency_symbol[cart_products[i]]+'</td>';
			print+='</tr>';
			//alert(currency_id[cart_products[i]]);
//			if(currency_id[cart_products[i]]==1){
//				var dollar = parseFloat((product_prices[cart_products[i]]*cart_quantities[cart_products[i]]));
//				var reil = parseFloat((product_prices[cart_products[i]]*cart_quantities[cart_products[i]])*rate[cart_products[i]]);
//				alert(rate[cart_products[i]]);
//				alert('dollar'+product_prices[cart_products[i]]+'*'+cart_quantities[cart_products[i]]);
//				alert('reil'+product_prices[cart_products[i]]+'*'+cart_quantities[cart_products[i]]+"*"+rate[cart_products[i]]);
//			}else{
//				var dollar = parseFloat((product_prices[cart_products[i]]*cart_quantities[cart_products[i]]/rate[cart_products[i]]));
//				var reil = parseFloat((product_prices[cart_products[i]]*cart_quantities[cart_products[i]]));
//				alert(rate[cart_products[i]]);
//				alert('reial'+product_prices[cart_products[i]]+'*'+cart_quantities[cart_products[i]]);
//				alert('dollar'+product_prices[cart_products[i]]+'*'+cart_quantities[cart_products[i]]+"/"+rate[cart_products[i]]);
//			}
//			subtotal_dollar+=dollar;
//			subtotal_reil+=reil;
			subtotal_dollar+=(currency_id[cart_products[i]]==1)?product_prices[cart_products[i]]*cart_quantities[cart_products[i]]:parseFloat((product_prices[cart_products[i]]*cart_quantities[cart_products[i]])/rate[cart_products[i]]);
			subtotal_reil+=(currency_id[cart_products[i]]==2)?product_prices[cart_products[i]]*cart_quantities[cart_products[i]]:(product_prices[cart_products[i]]*cart_quantities[cart_products[i]])*rate[cart_products[i]];
			
			if(inx!=1) {
					var identity = $('#identity').val();
					$('#identity').val(identity+','+inx);
				} else {
					$('#identity').val(inx);
				}
		}
		cart_html+='</tbody>';
		cart_html+='</table>';
		
		print+='</tbody>';
		print+='</table>';
		
		cart_html+='<div class="row-fluid" style="margin-top:10px">';
			cart_html+='<div class="span6"></div>';
			cart_html+='<div class="span6">';
				cart_html+='<div class="total">';
					cart_html+='<div class="span4">';
						cart_html+='<p><strong><span>'+grenTotal_reil+'</span></strong></p>';
					cart_html+='</div>';
					cart_html+='<div class="span2">';
						cart_html+='<p><strong><span>:</span></strong></p>';
					cart_html+='</div>';
					cart_html+='<div class="span6">';
						cart_html+='<p><strong>'+subtotal_reil.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")+' <em style="float: right;padding-right: 10%;"> Reil</em></strong></p>';
					cart_html+='</div>';
				cart_html+='</div>';
				cart_html+='<div class="total">';
					cart_html+='<div class="span4">';
						cart_html+='<p><strong><span>'+grenTotal_dollar+'</span></strong></p>';
					cart_html+='</div>'; 
					cart_html+='<div class="span2">';
						cart_html+='<p><strong><span>:</span></strong></p>';
					cart_html+='</div>';
					cart_html+='<div class="span6">';
						cart_html+='<input type="hidden" name="totalreil" id="totalriel" value="'+subtotal_reil+'" /><input type="hidden" name="totaldollar" id="totaldollar" value="'+subtotal_dollar+'" /><p><strong>'+subtotal_dollar.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")+' <em style="float: right;padding-right: 10%;"> USD</em></strong></p>';
					cart_html+='</div>';
				cart_html+='</div>';
			cart_html+='</div>';
		cart_html+='</div>';
		
		print+='<div style="height: 30px;"></div> ';
		print+='<div class="row-form" style="width:100%; padding:0;">';
			print+='<div style="width:40%; padding:0; float: left">&nbsp;</div>';
			print+='<div style="width:60%; padding:0; float: left"> ';
				print+='<div style="width:100%; padding:0;float: left;">';
					print+='<div style="width:55%;float: left;text-align: left;">'+grenTotal_reil+'</div>';
					print+='<div style="float: left;">:</div>';
					print+='<div style="width:35%;float: right;padding-right:10px;text-align: right;">'+subtotal_reil.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")+'(៛)</div>';
				print+='</div>';
				print+='<div style="width:100%; padding:0;float: left;">';
					print+='<div style="width:55%;float: left;text-align: left;">'+grenTotal_dollar+'</div>';
					print+='<div style="float: left;">:</div>';
					print+='<div style="width:35%;float: right;padding-right:10px;text-align: right;">'+subtotal_dollar.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")+'($)</div>';
				print+='</div>';
			print+='</div>';
		print+='</div>';
	}
	else
	{
		cart_html = "The cart is empty!";
		print = "The cart is empty!";
	}
	
	document.getElementById("Cart").innerHTML=cart_html;
	document.getElementById("print").innerHTML=print;
}

function GoToPayment()
{

	if(cart_products.length==0)
	{
		ShowPopup("Your cart is empty!"); 
	}
	else
	{
		var subtotal=0; 
		var products_list="";
		
		for(var i=0;i<cart_products.length;i++)
		{
			if(products_list!="") products_list+=", ";
			products_list+= products[cart_products[i]]+" ("+cart_quantities[cart_products[i]]+")";
			subtotal+=product_prices[cart_products[i]]*cart_quantities[cart_products[i]];
		}
		document.getElementById("products_value").value=subtotal;
		document.getElementById("products_list").value=products_list;
		document.getElementById("cart-container").style.display="none";
		document.getElementById("payment-container").style.display="block";
	}
}

function GoToCart()
{
	document.getElementById("cart-container").style.display="block";
	document.getElementById("payment-container").style.display="none";
}



function SubmitPaymentForm()
{
	ShowPopup('<img src="atn-simple-cart/images/loading.gif" alt="loading"/> Please wait while processing your order');
	document.getElementById("payment-from").submit();
}

function ProceedUpdate()
{
	

	for(var i=0;i<cart_products.length;i++)
	{
	
	
		var current_quantity = parseInt(document.getElementById("quantity_"+cart_products[i]).value) || 0;
	
		if(current_quantity==0)
		{
			DeleteProduct(cart_products[i]);
		}
		else
		{
			cart_quantities[cart_products[i]] = current_quantity;
		}
	
		
	}
	
	UpdateCart();
}
