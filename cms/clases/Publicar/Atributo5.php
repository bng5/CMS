<?php

require_once(DOKU_INC.'inc/events.php');
require_once(DOKU_INC.'inc/parser/parser.php');
require_once(DOKU_INC.'inc/parser/xhtml.php');

/**
 * Área de texto
 *
 * @author pablo
 */
class Publicar_Atributo5 extends Publicar_Atributo {

	public static function valor($leng, $attr_k, $attr_v, $valores, &$item = null, &$db_item = null) {

		if(!is_array($attr_v['extra']))
			$attr_v['extra'] = unserialize($attr_v['extra']);
		$mysqli = BaseDatos::Conectar();
		$leng_id = $leng->id;
		//$attr_v['extra'] = unserialize($attr_v['extra']);
		$almacenamiento = 'text';//Atributos::$almacenamiento[$attr_v['tipo_id']];

		// es lista, por ahora no me preocupo
		if($attr_v['unico'] == 0) {
			if($valores) {
				foreach($valores AS $attr_valores)
					$item[$attr_k][] = $attr_valores[$almacenamiento];
			}
		}
		elseif($attr_v['unico'] == 1) {
			if($valores[$almacenamiento] && $attr_v['extra']['f']) {
				$item[$attr_k] = self::formato($valores[$almacenamiento]);
				$valores[$almacenamiento] = serialize($item[$attr_k]);
			}
			else
				$item[$attr_k] = $valores[$almacenamiento];
			
			if($attr_v['en_listado'])
					$db_item['text__'.$attr_v['identificador']] = $valores[$almacenamiento] ? "'".$mysqli->real_escape_string($valores[$almacenamiento])."'" : 'NULL';
		}
		// Es multilingüe
		elseif($attr_v['unico'] == 2) {
			// Tiene formato
			if($valores[$leng_id]['text'] && $attr_v['extra']['f']) {
				/*
				* Parche Zooko
				* bajada es parte de descripción
				*/
//                if($attr_k == 14) {
                    if($pos_marca = mb_strpos($valores[$leng_id]['text'], "¶"))
                        $valores[$leng_id]['text'] = mb_substr($valores[$leng_id]['text'], 0, $pos_marca).mb_substr($valores[$leng_id]['text'], $pos_marca+1);
                    else
                        $pos_marca = 200;
                    $item[$attr_k] = self::formato($valores[$leng_id]['text']);
                    $valores[$leng_id]['text'] = serialize(self::formato(mb_substr($valores[$leng_id]['text'], 0, $pos_marca)));
                    // bsq
                    $bsq_texto .= $valores[$leng_id]['text']." ";
//                }
//                else {
//                    $item[$attr_k] = self::formato($valores[$leng_id]['text']);
//                    $valores[$leng_id]['text'] = serialize($item[$attr_k]);
//                }
				/*
				* FIN Parche Zooko
				* bajada es parte de descripción
				*/
			}
			// No tiene formato
			else {
				$item[$attr_k] = $valores[$leng_id]['text'];
				$bsq_texto .= $valores[$leng_id]['text']." ";
			}
			if($attr_v['en_listado'])
				$db_item[$almacenamiento.'__'.$attr_v['identificador']] = $valores[$leng_id]['text'] ? "'".$mysqli->real_escape_string($valores[$leng_id]['text'])."'" : 'NULL';
			//$item[$attr_k] = $valores[$attr_k][$leng_id][$attr_v['tipo']];
		}
	}

	private static function formato($valor) {
		$Parser = new Doku_Parser();
		$Parser->Handler = new Doku_Handler();
		$Parser->addMode('header',new Doku_Parser_Mode_Header());
		$Parser->addMode('strong', new Doku_Parser_Mode_Formatting('strong'));
		$Parser->addMode('emphasis', new Doku_Parser_Mode_Formatting('emphasis'));
		$Parser->addMode('underline', new Doku_Parser_Mode_Formatting('underline'));
		$Parser->addMode('monospace', new Doku_Parser_Mode_Formatting('monospace'));
		$Parser->addMode('subscript', new Doku_Parser_Mode_Formatting('subscript'));
		$Parser->addMode('superscript', new Doku_Parser_Mode_Formatting('superscript'));
		$Parser->addMode('deleted', new Doku_Parser_Mode_Formatting('deleted'));
		$Parser->addMode('internallink',new Doku_Parser_Mode_InternalLink());
		$Parser->addMode('media',new Doku_Parser_Mode_Media());
		$Parser->addMode('externallink',new Doku_Parser_Mode_ExternalLink());
		$Parser->addMode('eol',new Doku_Parser_Mode_Eol());
		$Parser->addMode('linebreak',new Doku_Parser_Mode_Linebreak());
		//$instructions = $Parser->parse($valores[$attr_k][$leng_id][$attr_v['tipo']]);
		//$valores[$attr_k][$leng_id][$attr_v['tipo']] = serialize($instructions);
		return $Parser->parse($valor);
		/*
		$inSection = FALSE;
		$startPos = 0;
		$endPos = 0;
		$Renderer = & new Doku_Renderer_XHTML();
		foreach($instructions as $instruction)
		 {
		  call_user_func_array(array(&$Renderer, $instruction[0]),$instruction[1]);
		 }
		$valores[$attr_k][$leng_id][$attr_v['tipo']] = $Renderer->doc;
		*/
	}
}

?>