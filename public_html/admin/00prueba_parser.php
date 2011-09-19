<?php

header("Content-Type: text/html; charset=UTF-8");

require('inc/iniciar.php');

require_once(DOKU_INC.'inc/events.php');
require_once(DOKU_INC.'inc/parser/parser.php');
require_once(DOKU_INC.'inc/parser/xhtml.php');



$valor = <<<EOD

== Términos y Condiciones – Website Bodegas Castillo Viejo S.A.  ==

Please Read These Terms Carefully Before Using This Site.
This Web site is provided by Bodegas Castillo Viejo (Bodegas Castillo Viejo S.A.) and may be used for informational purposes only. By using the site or downloading materials from the site, you agree to abide by the terms and conditions set forth in this notice. If you do not agree to abide by these terms and conditions do not use the site or download materials from the site.

=== Limited License ===

Subject to the terms and conditions set forth in this Agreement, Bodegas Castillo Viejo grants you a non-exclusive, non-transferable, limited right to access, use and display this site and the materials thereon. You agree not to interrupt or attempt to interrupt the operation of the site in any way.
Bodegas Castillo Viejo authorizes you to view and download the information at this Web site only for your personal, non-commercial use. This authorization is not a transfer of title in the information and copies of the information and is subject to the following restrictions: 1) you must retain, on all copies of the information downloaded, all copyright and other proprietary notices contained in the information; 2) you may not modify the information in any way or reproduce or publicly display, perform, or distribute or otherwise use them for any public or commercial purpose; and 3) you must not transfer the information to any other person unless you give them notice of, and they agree to accept, the obligations arising under these terms and conditions of use. You agree to abide by all additional restrictions displayed on the Site as it may be updated from time to time.

=== Disclaimer ===

THE MATERIALS MAY CONTAIN INACCURACIES AND TYPOGRAPHICAL ERRORS. BODEGAS CASTILLO VIEJO DOES NOT WARRANT THE ACCURACY OR COMPLETENESS OF THE MATERIALS OR THE RELIABILITY OF ANY ADVICE, OPINION, STATEMENT OR OTHER INFORMATION DISPLAYED OR DISTRIBUTED THROUGH THE SITE. YOU ACKNOWLEDGE THAT ANY RELIANCE ON ANY SUCH OPINION, ADVICE, STATEMENT, MEMORANDUM, OR INFORMATION SHALL BE AT YOUR SOLE RISK. BODEGAS CASTILLO VIEJO RESERVES THE RIGHT, IN ITS SOLE DISCRETION, TO CORRECT ANY ERRORS OR OMISSIONS IN ANY PORTION OF THE SITE. BODEGAS CASTILLO VIEJO MAY MAKE ANY OTHER CHANGES TO THE SITE, THE MATERIALS AND THE PRODUCTS, PROGRAMS, SERVICES OR PRICES (IF ANY) DESCRIBED IN THE SITE AT ANY TIME WITHOUT NOTICE.
THIS SITE, THE INFORMATION AND MATERIALS ON THE SITE, AND THE SOFTWARE MADE AVAILABLE ON THE SITE, ARE PROVIDED "AS IS" WITHOUT ANY REPRESENTATION OR WARRANTY, EXPRESS OR IMPLIED, OF ANY KIND, INCLUDING, BUT NOT LIMITED TO, WARRANTIES OF MERCHANTABILITY, NONINFRINGEMENT, OR FITNESS FOR ANY PARTICULAR PURPOSE. SOME JURISDICTIONS DO NOT ALLOW FOR THE EXCLUSION OF IMPLIED WARRANTIES, SO THE ABOVE EXCLUSIONS MAY NOT APPLY TO YOU.

=== Third Party Sites ===

As a convenience to you,
Bodegas Castillo Viejo may provide, on this Site, links to Web sites operated by other entities. If you use these sites, you will leave this Site. If you decide to visit any linked site, you do so at your own risk and it is your responsibility to take all protective measures to guard against viruses or other destructive elements. Bodegas Castillo Viejo makes no warranty or representation regarding, and does not endorse, any linked Web sites or the information appearing thereon or any of the products or services described thereon. Links do not imply that Bodegas Castillo Viejo or this Site sponsors, endorses, is affiliated or associated with, or is legally authorized to use any trademark, trade name, logo or copyright symbol displayed in or accessible through the links, or that any linked site is authorized to use any trademark, trade name, logo or copyright symbol of Bodegas Castillo Viejo or any of its affiliates or subsidiaries. External Links to the Site All links to the Site must be approved in writing by Bodegas Castillo Viejo, except that Bodegas Castillo Viejo consents to links in which: (i) the link is a text-only link containing only the name "Bodegas Castillo Viejo"; (ii) the link "points" only to www.castilloviejo.com and not to deeper pages;(iii) the link, when activated by a user, displays that page full-screen in a fully operable and navigable browser window and not within a "frame" on the linked website; and (iv) the appearance, position, and other aspects of the link may neither create the false appearance that an entity or its activities or products are associated with or sponsored by Bodegas Castillo Viejo nor be such as to damage or dilute the goodwill associated with the name and trademarks of Bodegas Castillo Viejo or its Affiliates. Bodegas Castillo Viejo reserves the right to revoke this consent to link at any time in its sole discretion.

Information Provided By You Any personally identifiable information you may provide to Bodegas Castillo Viejo via this Site is protected by the Privacy Statement associated with this Site. Bodegas Castillo Viejo does not want you to, and you should not, send any confidential or proprietary information to Bodegas Castillo Viejo via the Site. You agree that any information or materials that you or individuals acting on your behalf provide to Bodegas Castillo Viejo will not be considered confidential or proprietary. By providing any such information or materials to Bodegas Castillo Viejo, you grant to Bodegas Castillo Viejo an unrestricted, irrevocable, worldwide, royalty-free license to use, reproduce, display, publicly perform, transmit and distribute such information and materials, and you further agree that Bodegas Castillo Viejo is free to use any ideas, concepts or know-how that you or individuals acting on your behalf provide to Bodegas Castillo Viejo. You further recognize that Bodegas Castillo Viejo does not want you to, and you warrant that you shall not, provide any information or materials to Bodegas Castillo Viejo that is defamatory, threatening, obscene, harassing, or otherwise unlawful, or that incorporates the proprietary material of another.
Limitations of Damages IN NO EVENT SHALL BODEGAS CASTILLO VIEJO OR ANY OF ITS SUBSIDIARIES BE LIABLE TO ANY ENTITY FOR ANY DIRECT, INDIRECT, SPECIAL, CONSEQUENTIAL OR OTHER DAMAGES (INCLUDING, WITHOUT LIMITATION, ANY LOST PROFITS, BUSINESS INTERRUPTION, LOSS OF INFORMATION OR PROGRAMS OR OTHER DATA ON YOUR INFORMATION HANDLING SYSTEM) THAT ARE RELATED TO THE USE OF, OR THE INABILITY TO USE, THE CONTENT, MATERIALS, AND FUNCTIONS OF THE SITE OR ANY LINKED WEBSITE, EVEN IF LSQA IS EXPRESSLY ADVISED OF THE POSSIBILITY OF SUCH DAMAGES.
Changes Bodegas Castillo Viejo reserves the right, at its sole discretion, to change, modify, add or remove any portion of this Agreement in whole or in part, at any time. Changes in this Agreement will be effective when notice of such change is posted. Your continued use of the Site after any changes to this Agreement are posted will be considered acceptance of those changes.

Bodegas Castillo Viejo may terminate, change, suspend or discontinue any aspect of the Bodegas Castillo Viejo Site, including the availability of any features of the Site, at any time. Bodegas Castillo Viejo may also impose limits on certain features and services or restrict your access to parts or the entire Site without notice or liability. Bodegas Castillo Viejo may terminate the authorization, rights and license given above and, upon such termination, you shall immediately destroy all Materials.
International Users and Choice of Law This Site is controlled, operated and administered by Bodegas Castillo Viejo from its office in Uruguay. Bodegas Castillo Viejo makes no representation that materials at this site are appropriate or available for use at other locations outside Uruguay and access to them from territories where their contents are illegal is prohibited. You may not use the Site or export the information in violation of U. S. export laws and regulations. If you access this Site from a location outside Uruguay, you are responsible for compliance with all local laws.

This Agreement constitutes the entire agreement between Bodegas Castillo Viejo and you with respect to your use of the Site. Any cause of action you may have with respect to your use of the Site must be commenced within one (1) year after the claim or cause of action arises. If for any reason a court of competent jurisdiction finds any provision of the Agreement or portion thereof, to be unenforceable, that provision shall be enforced to the maximum extent permissible so as to effect the intent of the Agreement, and the remainder of this Agreement shall continue in full force and effect.
EOD;



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

$mapa = $Parser->parse($valor);
//print_r($mapa);

$Renderer = new Doku_Renderer_XHTML();
foreach($mapa as $instruction)
    call_user_func_array(array(&$Renderer, $instruction[0]), $instruction[1]);

echo $Renderer->doc;

?>