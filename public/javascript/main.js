var isEnseignantApp=true;


// Vérification que l'on doit pas rediriger la page
function check_redirection() {
	if ($('redirection')) {
		window.setInterval('redirigeAccueil();',500);
	}
}

function popupForm(champ_maj,id_link) {
	$('form_nav').id.value=id_link;
	$('form_nav').section.value=champ_maj;
	$('form_nav').page.value='popupform';
	$('form_nav').set('send', 	{
		url: 'index.php',
		method: 'post',
		onSuccess: function(transport) {
			$('popup').innerHTML = transport;	
		},
		onRequest: function(transport) {
			$('popup').innerHTML = '<div class="loader">Chargement en cours ...<br/><br/> <img src="public/images/ajax-loader.gif"/></div>';
		},
		onFailure: function() {
			$('popup').innerHTML = 'Failed to submit the form ...';
		}
	}
	);
	$('form_nav').send();
	$('popup_overlay').setStyle('display','block');  
	$('popup').setStyle('display','block');
}

// Fonction pour faire disparaitre les formulaires popup
function cancelPopupForm() {
	$('popup').setStyle('display','none');
	$('popup_overlay').setStyle('display','none');  
}



// Fonction permettant de rediriger vers l'accueil pour les pages non-trouvées ou non-autorisées
function redirigeAccueil() {
	document.location.href='index.php';
}

// Fonction principale permettant de mettre à jour le contenu de la page via AJAX
function affElement(id,page,section,action,div_target) {
	$('form_nav').id.value=id;
	$('form_nav').page.value=page;
	$('form_nav').action.value=action;
	$('form_nav').section.value=section;

	//pour debuguer
	//	$('form_nav').target='fenetre_debug';
	//	$('form_nav').submit();
	//en cas de debug ne pas exectuer

	$('form_nav').set('send', 	{
				url: 'index.php',
				method: 'post',
				onSuccess: function(transport) {
					$(div_target).innerHTML = transport;
					if ($('update_titre_page')) {
						window.document.title=$('update_titre_page').innerHTML;
					}
					check_redirection();
					
					
					if(document.getElementById('tabs')) {
						var tabs = $('tabs');
						var sub_tabs=tabs.getChildren('li .subtabs');
						
						$(sub_tabs[0].id).setStyle('background-color','#bbe7e7');
						$('content_'+sub_tabs[0].id).setStyle('display','block');
						
						sub_tabs.each(function(item){
							if ((item.id!='retour_liste') && (item.id!='bouton_sauver') && (item.id!='')) {
								item.addEvent('click', showFunction.bind($('content_'+item.id)));
							}
						});						
					}		
					$$('input.DatePicker').each( function(el){
						new DatePicker(el);
					});
	

					if (document.getElementById('frm_connexion_wiki')) {
						connexion_wiki();
					}
					if (document.getElementById('frm_connexion_wikiadmin')) {
						connexion_wikiadmin();
					}
					place_changed_values();
				
				},
				onRequest: function(transport) {
					$(div_target).innerHTML = '<div class="loader1">Chargement en cours ...<br/><br/> <img src="public/images/ajax-loader.gif"/></div>';
					$('temps_chargement').innerHTML='';
				},
				onFailure: function() {
					$(div_target).innerHTML = 'Element access failed';
				}
			}
		);
	$('form_nav').send();
}

// Fonction permettant d'afficher les différents tabs quand on édite un élément
var showFunction = function() {
	$$('.hidden').setStyle('display', 'none'); 
	this.setStyle('display', 'block');
	var to_white=$('tabs').getChildren('li');
	$$('#tabs li').setStyle('background-color', '#ffffff');
	if ($('bouton_sauver')) {
		$('bouton_sauver').setStyle('background-color', '#005959');
	}
	$('retour_liste').setStyle('background-color', '#005959');
	$(this.id.substr(8,30)).setStyle('background-color','#bbe7e7');
	
	if ($('update')) {
		$('update').active_tab.value=this.id.substr(8,30);
	}
}

// Fonction permettant de soummettre un formulaire via AJAX
function submitForm(id_form) {
	
	var div_target='content';
	$(id_form).set('send', 	{
				url: 'index.php',
				method: 'post',
				onSuccess: function(transport) {

					$(div_target).innerHTML = transport;
					
					if(document.getElementById('tabs')) {
						var tabs = $('tabs');
						var sub_tabs=tabs.getChildren('li .subtabs');
						
						$(sub_tabs[0].id).setStyle('background-color','#bbe7e7');
						$('content_'+sub_tabs[0].id).setStyle('display','block');
						
						sub_tabs.each(function(item){
							if ((item.id!='retour_liste') && (item.id!='bouton_sauver') && (item.id!='')) {
								item.addEvent('click', showFunction.bind($('content_'+item.id)));
							}
						});						
					}
					place_changed_values();
					$$('input.DatePicker').each( function(el){
						new DatePicker(el);
					});
				},
				onRequest: function(transport) {
					$(div_target).innerHTML = '<div class="loader1">Chargement en cours ...<br/><br/> <img src="public/images/ajax-loader.gif"/></div>';
				},
				onFailure: function() {
					$(div_target).innerHTML = 'Failed to submit the form ...';
				}
			}
		);
	
	$(id_form).send();
}

// Fonction appelant le script d'export pour la génération des fichiers
function genererFichier(requete,type) {
	window.open('index.php?page=export&type='+type+'&requete='+requete);
}

var timer2;
function lancetimer2(id){
	if (timer2) {
        	window.clearInterval(timer2);
	}
	
	timer2=window.setInterval('soumetRecherche(\''+id+'\');',1000);
}


//Fonction pour soumettre une recherche
function soumetRecherche(id) {
	clearTimeout(timer2);
	
	var div_target='content';
	$(id).set('send', 	{
			url: 'index.php',
			method: 'post',
			onSuccess: function(transport) {
				$(div_target).innerHTML = transport;
			},
			onRequest: function(transport) {
				$(div_target).innerHTML = '<div class="loader">Chargement en cours ...<br/><br/> <img src="public/images/ajax-loader.gif"/></div>';
			},
			onFailure: function() {
				$(div_target).innerHTML = 'Failed to submit the form ...';
			}
		}
	);
	
	$(id).send();
}

// Fonction permettant d'ajouter un temps de 500ms avant de regénérer les tables
var timer1;
function lancetimer(){
	if (timer1) {
        	window.clearInterval(timer1);
	}
	timer1=window.setInterval('rechargeTable();',1000);
}

// Fonction permettant de recharger la table avec les nouveaux filtrages
function rechargeTable(){
	clearTimeout(timer1);
	var table=$('table_liste');
	var target='content_tab';

	$('form_filtrage').set('send', 	{
				url: 'index.php',
				method: 'post',
				onSuccess: function(transport) {
					$(target).innerHTML = transport;
					$('n_record').innerHTML=test;				
				},
				onRequest: function(transport) {					
					$(target).innerHTML= '<div class="loader1">Chargement en cours ...<br/><br/> <img src="public/images/ajax-loader.gif"/></div>';
					$('temps_chargement').innerHTML='';
				},
				onFailure: function() {
					$(target).innerHTML = 'Failed to reload the table ...';
				}
			}
		);
	$('form_filtrage').send();
}

// Fonction permettant de se connecter au wiki interne
function connexion_wiki() {
    $('frm_connexion_wiki').action='https://enseignant.ipgp.fr/wiki/index.php?title=Spécial:Connexion&action=submitlogin&type=login&returnto=Accueil';
    $('frm_connexion_wiki').method='post';
    $('frm_connexion_wiki').submit();
}
// Fonction permettant de se connecter au wiki admin (stockant les fichiers privés)
function connexion_wikiadmin() {
    $('frm_connexion_wikiadmin').action='https://enseignant.ipgp.fr/wikiadmin/index.php?title=Spécial:Connexion&action=submitlogin&type=login&returnto=Accueil';
    $('frm_connexion_wikiadmin').method='post';
    $('frm_connexion_wikiadmin').submit();
}

// Fonction permettant de mettre en dessous des valeurs de la base celle du SED pour simplifier la synchronisation
function place_changed_values() {		
	$$('div.newval').each(function(item){
		var item_parent=$(item.id.substr(4,50)).getParent();
		var isselect=item_parent.innerHTML.indexOf('<select ');
		if(isselect==-1) {
			var html_aff="<span>"+item.getChildren('span')[0].innerHTML+"</span>";
		} else {
			var valeur=item.getChildren('span')[0].innerHTML;
			var select_item=item_parent.getChildren('select')[0];
			var html_aff='';
			var select_item_tab=select_item.getChildren('option');
			for (i=0;i<select_item_tab.length;i++) {
				if (select_item_tab[i].value==valeur) {
					html_aff=select_item_tab[i].text;
				}
			}
			html_aff='<span>'+html_aff+'</span>';
		}
http://localhost:15280/enseignant/index.php
		item_parent.innerHTML+='<div class="newval" id="'+item.id.substr(4,50)+'_af">'+item.innerHTML+html_aff+'</div>';
			
		item.destroy();
	});
}

// Fonction permettant d'utiliser la valeur du SED pour remplir la base
function update_value(obj,champ) {
	$(champ).value=$(obj).getParent().getChildren('span')[0].innerHTML;
	$(champ+'_af').setStyle('display','none');
}
// Fonction permettant de faire disparaitre la différence avec le SED
function remove_value(obj) {
	$(obj).getParent().setStyle('display','none');
}
// Fonction permettant de mettre à jour le SED avec la valeur de la base
function correct_sed_etudiants(champ) {
	var id_etudiant=$('id_etudiant').value;
	var val=$(champ).value;

	$('form_nav').id.value=id_etudiant+'=='+champ+'=='+val;
	$('form_nav').page.value='update_sed';
	$('form_nav').set('send', 	{
		url: 'index.php',
		method: 'post',
		onSuccess: function(transport) {
			$('popup').innerHTML= transport;
		},
		onRequest: function(transport) {
			$('popup').innerHTML = '<div class="loader">Chargement en cours ...<br/><br/> <img src="public/images/ajax-loader.gif"/></div>';
		},
		onFailure: function() {
			$('popup').innerHTML = 'Failed to submit the form ...';
		}
	});
	$('form_nav').send();
	$('popup').innerHTML='Le champ '+champ+'a été mis à jour';
	$('popup').setStyle('display','block'); 
}
// Faire disparaire la popup disant que le SED a été mis à jour
function clearUpdateSED(champ) {
	$('popup').setStyle('display','none');
	$(champ+'_af').setStyle('display','none');
}

// Permet de créer dans le SED l'étudiant
function ajouterEtudiantSED(id_etudiant) {
	$('form_nav').id.value=id_etudiant;
	$('form_nav').page.value='update_sed';
	$('form_nav').set('send', {
		url: 'index.php',
		method: 'post',
		onSuccess: function(transport) {
			$('popup').innerHTML= transport;
		},
		onRequest: function(transport) {
			$('popup').innerHTML = '<div class="loader">Chargement en cours ...<br/><br/> <img src="public/images/ajax-loader.gif"/></div>';
		},
		onFailure: function() {
			$('popup').innerHTML = 'Failed to submit the form ...';
		}
	});
	$('form_nav').send();
	$('popup').setStyle('display','block'); 
}

// Faire disparaire la popup disant que l'étudiant a été ajouté sur le SED
function clearAjouterEtudiantSED(id_etudiant) {
	$('popup').setStyle('display','none');
	$(id_etudiant).setStyle('display','none');
}

// Permet de synchroniser les valeurs entre le SED et la base
function synchroBaseSED(id_etudiant,champ,sens) {
	$('form_nav').id.value=id_etudiant+'=='+champ+'=='+sens;
	$('form_nav').page.value='update_sed';
	$('form_nav').set('send', 	{
		url: 'index.php',
		method: 'post',
		onSuccess: function(transport) {
			$('popup').innerHTML= transport;
		},
		onRequest: function(transport) {
			$('popup').innerHTML = '<div class="loader">Chargement en cours ...<br/><br/> <img src="public/images/ajax-loader.gif"/></div>';
		},
		onFailure: function() {
			$('popup').innerHTML = 'Failed to submit the form ...';
		}
	});
	$('form_nav').send();
	$('popup').setStyle('display','block'); 
}

// Fonction permettant de mettre à jour les horaires d'un enseignant
function updateHoraires(id_enseignant,id_ue) {
	
	var section='horaires';
	var action=id_enseignant+'-'+id_ue;
	var li_enseignant=$('heures_'+id_enseignant);
	var heures=li_enseignant.getElements('input');
	heures.each(function(item){
		if (item.name!='') {
			action=action+'-'+item.name+'%'+item.value;
		}
	});
	action=action+'-'+'heures['+id_enseignant+'][evolution]%'+$('evolution').value;

	$('form_nav').section.value=section;
	$('form_nav').action.value=action;
	$('form_nav').page.value='popupform';
	$('form_nav').set('send', 	{
		url: 'index.php',
		method: 'post',
		onSuccess: function(transport) {
			$('popup').innerHTML = transport;	
		},
		onRequest: function(transport) {
			$('popup').innerHTML = '<div class="loader">Chargement en cours ...<br/><br/> <img src="public/images/ajax-loader.gif"/></div>';
		},
		onFailure: function() {
			$('popup').innerHTML = 'Failed to submit the form ...';
		}
	}
	);
	$('form_nav').send();
	$('popup').setStyle('display','block');
}

// Fonction permettant de virer les espaces
function trim (myString) {
	return myString.replace(/^\s+/g,'').replace(/\s+$/g,'')
} 

// Fonction permettant de mettre à jour la div annexes pour la gestion des données
function navAnnexes(table,id,action) {
	$('nav_annexes').table.value=table;
	$('nav_annexes').id.value=id;
	$('nav_annexes').action.value=action;
		
	$('nav_annexes').set('send', 	{
				url: 'index.php',
				method: 'post',
				onSuccess: function(transport) {
					$('content').innerHTML = transport;
				},
				onRequest: function(transport) {
					$('content').innerHTML = '<div class="loader">Chargement en cours ...<br/><br/> <img src="public/images/ajax-loader.gif"/></div>';
					$('temps_chargement').innerHTML='';
				},
				onFailure: function() {
					$('content').innerHTML = 'Element access failed';
				}
			}
		);
	$('nav_annexes').send();
}
