<?php

/**
 * Description of Formstring
 *
 * @author pablo
 */
class VistaAdmin_FormCampo14 extends VistaAdmin_Form {

    public $id, $nombre, $indice = 0, $sugerido, $unico, $subtipo, $identificador, $poromision, $valor_id, $valor, $valores, $extra, $log, $formato;
    private $item, $label, $v, $pref, $campo_nombre_pref, $niveles; //, $x = array("id" => 0)
    private $tipo = 'string';

    public function __construct($item_id = false) {

        $this->log = '';
        $this->mysqli = $mysqli;
        //$this->campo_id_pref = $this->campo_nombre_pref = "hook[facebook]";
        $this->campo_id_pref = $this->campo_nombre_pref = "dato";
        $this->item = $item_id;
        $this->niveles = array(0);
        $this->niveles_cierres = array();
        $this->superior_niv = 0;
    }

    function __destruct() {
        $pop = end($this->niveles);
        while ($pop != 0) {
            array_pop($this->niveles);
            echo $this->niveles_cierres[$pop];
            unset($this->niveles_cierres[$pop]);
            $pop = end($this->niveles);
        }
    }

    public function mostrar() {
		$this->log .= "\n\nid: {$this->id}\nsuperior: {$this->superior}\n";
		//if($this->superior_niv != $this->superior)
		// {
		if(in_array($this->superior, $this->niveles)) {
			$retorno = '';
			$ii = count($this->niveles);
			$pop = end($this->niveles);
			while($pop != $this->superior) {
				array_pop($this->niveles);
				$retorno .= $this->niveles_cierres[$pop];
				unset($this->niveles_cierres[$pop]);
				$ii--;
				$pop = end($this->niveles);
			}
		}
		// else

		//}
		//array_push($this->niveles, $this->superior);
		array_push($this->niveles, $this->id);
		$this->log .= var_export($this->niveles, true)."\n";
		$this->log .= var_export($this->valores, true)."\n";
		//$this->superior_niv = $this->superior;
		$this->superior_niv = $this->id;

		//$campo_tipo = "campo".$this->tipo;
		$this->indice++;

		if($this->unico == 1) {
			$nombre_campo = $this->valores[0] ? "[m][{$this->id}][{$this->valores[0]['id']}]" : "[n][{$this->id}][]";
			echo $this->label(0, $this->campo_id_pref.$this->indice)."<td><input type=\"hidden\" name=\"{$this->campo_nombre_pref}{$nombre_campo}\" id=\"{$this->campo_id_pref}{$this->indice}\" value=\"".htmlspecialchars($this->valores[0]['string'])."\" size=\"45\" /><div id=\"muro\"></div></td>";// tabindex=\"2\"
		}

		//$this->v = $valor_id ? "[m][{$this->tipo}][{$this->valor_id}]" : "[n][{$this->id}][]";

		//$retorno.$this->$campo_tipo();

?>

<script type="text/javascript">
// <![CDATA[

//function fijarPost(evento) {
function fijarPost(event, id, userId) {
    if(event.originalTarget.tagName == 'a') {
        event.stopPropagation();
        return;
    }
    if(muro.activo) {
        if(muro.activo == id)
            return;
        var prev = document.getElementById('fbComment'+muro.activo);
        if(prev != null) {
            prev.className = 'fbComment';
        }
    }
    muro.activo = id;
    event.currentTarget.className = 'fbComment actual';
    document.getElementById('<?php echo $this->campo_id_pref.$this->indice; ?>').value = id+','+userId;

}
function agregerListenerPost(comentario, id, userId) {
    comentario.addEventListener("click", function(evento) { fijarPost(evento, id, userId); }, false);
}

function postFb(data) {
    console.log(data);
    var muro = document.getElementById('muro');
    while(muro.firstChild) {
       muro.removeChild(muro.firstChild);
    }

    var id, comentario, nombre, message, img, link, media, ul, li, caption;
    id = data.id.split("_");
    comentario = muro.appendChild(document.createElement('div'));
    comentario.id = 'fbComment'+data.id;
    comentario.className = 'fbComment'+(muro.activo == data.id ? ' actual' : '');

        img = new Image();
        img.className = 'avatar';
        img.src = 'http://graph.facebook.com/'+data.from.id+'/picture?type=square';
        comentario.appendChild(img);

        nombre = comentario.appendChild(document.createElement('h4'));
        nombre.appendChild(document.createTextNode(data.from.name));

        message = comentario.appendChild(document.createElement('p'));
        message.appendChild(document.createTextNode(data.message));

        if(data.link) {
            media = comentario.appendChild(document.createElement('div'));
            media.className = 'media';
                img = new Image();
                img.src = data.picture;
                media.appendChild(img);

                link = media.appendChild(document.createElement('a'));
                link.href = data.link;
                link.setAttribute('target', '_blank');
                link.appendChild(document.createTextNode(data.name));

                if(data.caption) {
                    caption = media.appendChild(document.createElement('span'));
                    caption.appendChild(document.createTextNode(data.caption));
                }

                description = media.appendChild(document.createElement('p'));
                description.appendChild(document.createTextNode(data.description));
        }

        ul = comentario.appendChild(document.createElement('ul'));
        li = ul.appendChild(document.createElement('li'));
        link = li.appendChild(document.createElement('a'));
        link.href = 'http://www.facebook.com/permalink.php?story_fbid='+id[1]+'&id='+id[0];
        link.setAttribute('target', '_blank');
        link.appendChild(document.createTextNode('Ver comentario'));

        if(data.likes) {
            li = ul.appendChild(document.createElement('li'));
            li.appendChild(document.createTextNode('Me gusta: '+data.likes.count));
        }
        li = ul.appendChild(document.createElement('li'));
        li.appendChild(document.createTextNode('Comentarios: '+data.comments.count));
}

function muroFb(data) {
    var muro = document.getElementById('muro');
    while(muro.firstChild) {
       muro.removeChild(muro.firstChild);
    }

    var id, comentario, nombre, message, img, link, media, ul, li, caption;
    for(var i = 0; i < data.data.length; i++) {
        id = data.data[i].id.split("_");
        comentario = muro.appendChild(document.createElement('div'));
        comentario.id = 'fbComment'+data.data[i].id;
        comentario.className = 'fbComment'+(muro.activo == data.data[i].id ? ' actual' : '');
        agregerListenerPost(comentario, data.data[i].id, data.data[i].from.id);
            img = new Image();
            img.className = 'avatar';
            img.src = 'http://graph.facebook.com/'+data.data[i].from.id+'/picture?type=square';
            comentario.appendChild(img);

            nombre = comentario.appendChild(document.createElement('h4'));
            nombre.appendChild(document.createTextNode(data.data[i].from.name));

            message = comentario.appendChild(document.createElement('p'));
            message.appendChild(document.createTextNode(data.data[i].message));

            if(data.data[i].link) {
                media = comentario.appendChild(document.createElement('div'));
                media.className = 'media';
                    img = new Image();
                    img.src = data.data[i].picture;
                    media.appendChild(img);

                    link = media.appendChild(document.createElement('a'));
                    link.href = data.data[i].link;
                    link.setAttribute('target', '_blank');
                    link.appendChild(document.createTextNode(data.data[i].name));

                    if(data.data[i].caption) {
                        caption = media.appendChild(document.createElement('span'));
                        caption.appendChild(document.createTextNode(data.data[i].caption));
                    }

                    description = media.appendChild(document.createElement('p'));
                    description.appendChild(document.createTextNode(data.data[i].description));
            }

            ul = comentario.appendChild(document.createElement('ul'));
            li = ul.appendChild(document.createElement('li'));
            link = li.appendChild(document.createElement('a'));
            link.href = 'http://www.facebook.com/permalink.php?story_fbid='+id[1]+'&id='+id[0];
            link.setAttribute('target', '_blank');
            link.appendChild(document.createTextNode('Ver comentario'));

            if(data.data[i].likes) {
                li = ul.appendChild(document.createElement('li'));
                li.appendChild(document.createTextNode('Me gusta: '+data.data[i].likes.count));
            }
            li = ul.appendChild(document.createElement('li'));
            li.appendChild(document.createTextNode('Comentarios: '+data.data[i].comments.count));
    }
    if(data.paging) {
        var paginado = muro.appendChild(document.createElement('div'));
        if(data.paging.previous) {
            link = paginado.appendChild(document.createElement('a'));
            link.appendChild(document.createTextNode('Anterior'));
            link.addEventListener("click", function() { new Ajast(data.paging.previous, {}); }, false);
        }
        if(data.paging.next) {
            link = paginado.appendChild(document.createElement('a'));
            link.appendChild(document.createTextNode('Siguiente'));
            link.addEventListener("click", function() { new Ajast(data.paging.next, {}); }, false);
        }
    }

    console.log(data);
    //console.log(data);
}

var muro = {activo: null};
//muro.siguiente = \'https://graph.facebook.com/168828573167421/feed\';
//muro.params = {access_token: \'AAAD1t7Ls0HUBAGnCDLspIsEZCGuVmKVl921Emliqg4ZAfKuaCBhmNogX8ZBCFndVvdeZC6BY5FFNg729o44DjhvVabLc9ASqne67KXPrUFU00ZB3khBe9\', callback: \'muroFb\'};
muro.access_token = 'AAAD1t7Ls0HUBAGnCDLspIsEZCGuVmKVl921Emliqg4ZAfKuaCBhmNogX8ZBCFndVvdeZC6BY5FFNg729o44DjhvVabLc9ASqne67KXPrUFU00ZB3khBe9';
muro.siguiente = 'https://graph.facebook.com/168828573167421/feed?'+
    'access_token='+muro.access_token+'&'+
    'callback=muroFb';
muro.params = {};

<?php

if($this->valores[0]['string']) {
    $partes = explode(',', $this->valores[0]['string']);
    echo '
window.addEventListener("load", function() { new Ajast(\'https://graph.facebook.com/'.$partes[0].'?callback=postFb&access_token=\'+muro.access_token, muro.params); }, false);';
}
else {
    echo '
window.addEventListener("load", function() { new Ajast(muro.siguiente, muro.params); }, false);';
}

?>


// ]]>
</script>

<?php

    }



}
