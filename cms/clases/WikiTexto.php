<?php

require_once(DOKU_INC.'inc/events.php');
require_once(DOKU_INC.'inc/parser/parser.php');
require_once(DOKU_INC.'inc/parser/xhtml.php');

class WikiTexto {

	/**
	 *
	 * @param array $instrucciones
	 * @return string
	 */
	public static function render_html($instrucciones) {
		$Renderer = & new Doku_Renderer_XHTML();
		foreach($instrucciones as $instruccion)
			call_user_func_array(array(&$Renderer, $instruccion[0]),$instruccion[1]);
		return $Renderer->doc;
	}

	/**
	 *
	 * @param string $wikitexto
	 * @return array
	 */
	public static function parse($wikitexto) {
		$Parser = & new Doku_Parser();
		$Parser->Handler = & new Doku_Handler();
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
		return $Parser->parse($wikitexto);
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